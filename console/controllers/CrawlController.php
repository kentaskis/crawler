<?php
namespace console\controllers;

use console\jobs\ParseJob;
use yii\console\Controller;
use yii\redis\Connection;

class CrawlController extends Controller
{

    public function actionIndex(){

        $url = '/store/apps/top/category/GAME';
        /** @var Connection $redis */
        $redis = \Yii::$app->redis;
        $redis->sadd('gameLinks',$url);
        \Yii::$app->queue->delay(2)->push(new ParseJob([
            'url' => $url
        ]));
    }

}