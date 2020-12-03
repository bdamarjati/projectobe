<?php

namespace backend\controllers;

use backend\models\RefCpmk;
use Yii;
use backend\models\RelasiCpmkCpl;
use backend\models\searchs\RelasiCpmkCpl as RelasiCpmkCplSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * RelasiCpmkCplController implements the CRUD actions for RelasiCpmkCpl model.
 */
class RelasiCpmkCplController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * Lists all RelasiCpmkCpl models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RelasiCpmkCplSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RelasiCpmkCpl model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RelasiCpmkCpl model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RelasiCpmkCpl();
        $model1 = new RefCpmk();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_user = Yii::$app->user->identity->username;
            $model->save();
            Yii::$app->session->setFlash('success', [['Success', 'Data Berhasil Dimasukkan']]);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'model1' => $model1,
        ]);
    }

    /**
     * dependent dropdown untuk mengambil CPMK pada Form Relasi CPMK ke CPL
     * data dikirim kembali sesuai format data yang dibutuhkan
     */
    public function actionCpmk()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $mk = $parents[0];
                $out = RefCpmk::find()
                    ->select('id,kode label,isi name')
                    ->where(['id_ref_mata_kuliah' => $mk])
                    ->andWhere(['status' => 1])
                    ->asArray()
                    ->all();
                $out = ArrayHelper::index($out, null, 'label');
                // echo '<pre>';
                // print_r($out);
                // exit();

                return ['output' => $out, 'selected' => ''];
            }
        }
        return ['output' => $out, 'selected' => ''];
    }

    /**
     * Updates an existing RelasiCpmkCpl model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model1 = RefCpmk::findOne($model->id_ref_cpmk);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_user = Yii::$app->user->identity->username;
            $model->save();
            Yii::$app->session->setFlash('warning', [['Update', 'Data Berhasil Diperbarui']]);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'model1' => $model1,
        ]);
    }

    /**
     * Deletes an existing RelasiCpmkCpl model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->updated_user = Yii::$app->user->identity->username;
            $model->status  = 0;
            $model->save();
            Yii::$app->session->setFlash('error', [['Delete', 'Data Berhasil Dihapus']]);
        }
        return $this->redirect(['index']);
    }

    public function actionTotalBobot()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $id_ref_cpl = $request->post('id_ref_cpl');
        $bobot = RelasiCpmkCpl::find()->where(['status' => 1, 'id_ref_cpl' => $id_ref_cpl])->sum('bobot');

        return[
            'data' => $bobot,
        ];
    }

    /**
     * Finds the RelasiCpmkCpl model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RelasiCpmkCpl the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RelasiCpmkCpl::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
