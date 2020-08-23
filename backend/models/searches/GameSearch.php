<?php

namespace backend\models\searches;

use common\models\Game;
use yii\data\ActiveDataProvider;

/**
 * GameSearch represents the model behind the search form of `common\models\Game`.
 */
class GameSearch extends Game
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id', 'genre_id', 'publisher_id'], 'integer'],
            [['name'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = Game::find()->with(['publisher','genre']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['installs' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'genre_id' => $this->genre_id,
            'publisher_id' => $this->publisher_id,
            'rate' => $this->rate,
            'installs' => $this->installs,
        ]);

        $query->andFilterWhere(['like', 'game.name', $this->name])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'url', $this->url]);

        return $dataProvider;
    }
}
