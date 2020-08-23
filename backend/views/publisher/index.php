<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searches\PublisherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Publishers';
?>
<div class="publisher-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'address',
            'totalInstalls',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
