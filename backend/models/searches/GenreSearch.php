<?php

namespace backend\models\searches;

use common\models\Genre;
use yii\data\ActiveDataProvider;

/**
 * GenreSearch represents the model behind the search form of `common\models\Genre`.
 */
class GenreSearch extends Genre
{
    public $totalInstalls;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
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
        $query = self::find();
        $query->select(['genre.id', 'genre.name', 'sum(game.installs) as totalInstalls'])
            ->joinWith('games')
            ->groupBy('genre.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'genre.name',
                'totalInstalls' => [
                    'asc' => ['totalInstalls' => SORT_ASC],
                    'desc' => ['totalInstalls' => SORT_DESC],
                    'label' => 'Total totalInstalls'
                ]
            ],
            'defaultOrder' => ['totalInstalls' => SORT_DESC]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'genre.id' => $this->id,
        ]);
        $query->andFilterWhere(['like', 'genre.name', $this->name]);

        return $dataProvider;
    }
}
