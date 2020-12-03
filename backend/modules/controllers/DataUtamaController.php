<?php

namespace backend\modules\import\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RefCplController implements the CRUD actions for RefCpl model.
 */
class DataUtamaController extends Controller
{
    public function actionIndex()
	{
        return ("Hello Word");
    }

}