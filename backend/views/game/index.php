<?php

use common\models\Genre;
use common\models\Publisher;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searches\GameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Games';
?>
<div class="game-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'label' => 'Icon',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::img($data->icon, [
                        'style' => 'width:45px;'
                    ]);
                },
            ],
            [
                'filter' => Select2::widget([
                    'name' => 'GameSearch[genre_id]',
                    'data' => ArrayHelper::map(Genre::find()->all(), 'id', 'name'),
                    'value' => $searchModel->genre_id,
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'Select genre'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'selectOnClose' => true,
                    ]
                ]),
                'attribute' => 'genre_id',
                'value' => function ($data) {
                    return $data->genre->name ?? '';
                },
                'label' => 'Genre',
                'headerOptions' => ['style' => 'width:15%'],
            ],
            [
                'filter' => Select2::widget([
                    'name' => 'GameSearch[publisher_id]',
                    'data' => ArrayHelper::map(Publisher::find()->all(), 'id', 'name'),
                    'value' => $searchModel->publisher_id,
                    'options' => [
                        'class' => 'form-control',
                        'placeholder' => 'Select genre'
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'selectOnClose' => true,
                    ]
                ]),
                'attribute' => 'publisher_id',
                'value' => function ($data) {
                    return $data->publisher->name ?? '';
                },
                'label' => 'Publisher',
                'headerOptions' => ['style' => 'width:15%'],
            ],
            'rate',
            'installs',
//            'url:url',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
