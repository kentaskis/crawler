<?php

namespace backend\controllers;

use Yii;
use backend\models\searches\PublisherSearch;
use yii\web\Controller;

/**
 * PublisherController implements the CRUD actions for Publisher model.
 */
class PublisherController extends Controller
{

    /**
     * Lists all Publisher models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PublisherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
