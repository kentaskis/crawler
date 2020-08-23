<?php

namespace backend\controllers;

use Yii;
use backend\models\searches\GameSearch;
use yii\web\Controller;

/**
 * GameController implements the CRUD actions for Game model.
 */
class GameController extends Controller
{

    /**
     * Lists all Game models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new GameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}
