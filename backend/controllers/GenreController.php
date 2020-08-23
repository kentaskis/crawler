<?php

namespace backend\controllers;

use Yii;
use backend\models\searches\GenreSearch;
use yii\web\Controller;

/**
 * GenreController implements the CRUD actions for Genre model.
 */
class GenreController extends Controller
{

    /**
     * Lists all Genre models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GenreSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
