<?php

namespace backend\controllers;

use backend\models\RefMahasiswa;
use Dashboard;
use Yii;
use yii\filters\AccessControl;
// use backend\models\CapaianMahasiswa;
// use backend\models\searchs\CapaianMahasiswa as CapaianMahasiswaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class DashboardController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $mahasiswa_aktif = RefMahasiswa::find()
            ->where(['status' => 1])
            ->count();

        return $this->render('/dashboard', [
            'mahasiswa_aktif'=>$mahasiswa_aktif
        ]);
    }
}
