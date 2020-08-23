<?php

namespace console\jobs;

use common\models\Game;
use common\models\Genre;
use common\models\Publisher;
use GuzzleHttp\Client;
use phpQuery;
use phpQueryObject;
use Spatie\Browsershot\Browsershot;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use yii\redis\Connection;

class ParseJob extends BaseObject implements JobInterface
{
    private const SITE = 'https://play.google.com';
    public $url;
    public $parseGameDetails = false;


    public function execute($queue)
    {
        if ( (int)\Yii::$app->redis->sismember('parsedUrls', $this->url) === 1) {
            return true;
        }
        echo  $this->url.PHP_EOL;
        $fullUrl = self::SITE.$this->url;
        // если парсим страницу с игрой - яваскрипт выполнять не нужно, Guzzle работает быстрее
        if ($this->parseGameDetails) {
        $client = new Client();
            $res = $client->request('GET', $fullUrl);
            $html = $res->getBody();

        } else {
            $html = Browsershot::url($fullUrl)
//             из-за большого размера окна браузера google подгружает аяксовый контент самостоятельно
            ->windowSize(15360, 8640)
            ->waitUntilNetworkIdle()
                ->timeout(100)
            ->bodyHtml();
        }
        $dom = phpQuery::newDocumentHTML($html);


        if ($this->parseGameDetails) {
            if(!$this->_isGame()){
                $dom->unloadDocument();
                return true;
            }
           $result =  $this->_parseGame($this->url);
        }

        $gameLinks = pq('div.T4LgNb div.b8cIId.ReQCgd.Q9MA7b > a');
        $this->_addLinks($gameLinks->elements, true);
        $moreLinks = pq('div.T4LgNb a.xjAeve ');
        $this->_addLinks($moreLinks->elements);

        $dom->unloadDocument();
        if($result){
            \Yii::$app->redis->sadd('parsedUrls', $this->url);
        }
        return true;
    }

    private function _isGame():bool
    {
        $categories = pq('div.qQKdcc a');
        array_shift($categories->elements);
        foreach ($categories->elements as $element) {
            if(strpos(pq($element)->attr('href'),'GAME') > 0){
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $url
     * @return bool
     */
    private function _parseGame(string $url): bool
    {
        $categories = pq('div.qQKdcc a');
        $publisherDom = array_shift($categories->elements);
        $address = pq('a.hrTbp.euBY6b')->parents('span')->find('div:last')->text();
        $publisherId = $this->_getPublisher(trim(pq($publisherDom)->text()), $address);

        $genreDom = array_shift($categories->elements);
        $genreId = $this->_getGenre(trim(pq($genreDom)->text()));

        if (!$genreId || !$publisherId) {
            \Yii::error('Save error "Genre id or Publisher not found" || genre:"' .
                $genreId .'" || publisher:"'.$publisherId.'"', 'parser');
            return false;
        }

        $game = new Game();

        $game->name = pq('h1.AHFaub span')->text();
        $game->genre_id = $genreId;
        $game->publisher_id = $publisherId;
        $game->icon = pq('img.T75of.sHb2Xb')->attr('src');
        $game->rate = (float)pq('div.K9wGie > div.BHMmbe')->text();
        if($installs = $this->getInfoElement('Installs')){
            $installs = (int)preg_replace('/[^0-9]/', '',$installs->text());
        }
        $game->installs =  $installs ?? 0;
        $game->url = $url;
        if ($game->save()) {
            return true;
        }

        \Yii::error('Save error "Game":' . print_r($game->errors, true), 'parser');
        return false;
    }

    /**
     * @param string $text
     * @return phpQueryObject|bool
     */
    private function getInfoElement(string $text) : ?phpQueryObject
    {
        $info = pq('div.IxB2fe div.hAyfc');
        foreach ($info->elements as $element) {
            $tt = pq($element)->text();
            if(strpos(pq($element)->text(),$text) !== false) {
                return pq($element)->find('div.IQ1z0d span');
            }
        }
        return false;
    }

    /**
     * @param string $name
     * @param string $address
     * @return int
     */
    private function _getPublisher(string $name, string $address): int
    {
        $publisher = Publisher::find()->select('id')->where(['name' => $name])->asArray()->one();
        if ($publisher['id']) {
            return $publisher['id'];
        }
        $publisher = new Publisher(['name' => $name, 'address' => $address]);
        if (!$publisher->save()) {
            \Yii::error('Can\'t create "Publisher" : "' . $name . '" :: "' . $address . '"', 'parser');
            return 0;
        }

        return $publisher->id;
    }

    /**
     * @param string $name
     * @return int
     */
    private function _getGenre(string $name): int
    {
        $genre = Genre::find()->select('id')->where(['name' => $name])->asArray()->one();
        if ($genre['id']) {
            return $genre['id'];
        }
        $genre = new Genre(['name' => $name]);
        if (!$genre->save()) {
            \Yii::error('Can\'t create "Genre" : "' . $name . '"', 'parser');
            return 0;
        }

        return $genre->id;
    }

    /**
     * @param array $links
     * @param bool $parseGameDetails
     */
    private function _addLinks(array $links, bool $parseGameDetails = false): void
    {
        foreach ($links as $link) {
            $link = pq($link)->attr('href');
            $res = \Yii::$app->redis->sadd('gameLinks', $link);
            if ((int)$res === 1) {
                \Yii::$app->queue->delay(2)->push(new self([
                    'url' => $link,
                    'parseGameDetails' => $parseGameDetails
                ]));
            }
        }

    }

}