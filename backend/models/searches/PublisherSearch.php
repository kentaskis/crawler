<?php

namespace backend\models\searches;

use common\models\Publisher;
use yii\data\ActiveDataProvider;

/**
 * PublisherSearch represents the model behind the search form of `common\models\Publisher`.
 */
class PublisherSearch extends Publisher
{
    public $totalInstalls;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['name', 'address'], 'safe'],
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
        $query->select(['publisher.id', 'publisher.name', 'publisher.address', 'sum(game.installs) as totalInstalls'])
            ->joinWith('games')
            ->groupBy('publisher.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'publisher.id',
                'publisher.name',
                'totalInstalls' => [
                    'asc' => ['totalInstalls' => SORT_ASC],
                    'desc' => ['totalInstalls' => SORT_DESC],
                    'label' => 'Total installs'
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
            'publisher.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'publisher.name', $this->name]);
        $query->andFilterWhere(['like', 'address', $this->address]);

        return $dataProvider;
    }
}
