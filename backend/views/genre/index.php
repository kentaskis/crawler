<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searches\GenreSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Genres';
?>
<div class="genre-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'totalInstalls',
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
