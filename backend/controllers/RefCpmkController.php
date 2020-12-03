<?php

namespace backend\controllers;

use Yii;
use backend\models\RefCpmk;
use backend\models\searchs\RefCpl;
use backend\models\searchs\RefCpmk as RefCpmkSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RefCpmkController implements the CRUD actions for RefCpmk model.
 */
class RefCpmkController extends Controller
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
     * Lists all RefCpmk models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RefCpmkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RefCpmk model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            // 'modelcpl' => $this->findModelCpl($idcpl),
        ]);
    }

    /**
     * Creates a new RefCpmk model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RefCpmk();
        $modelcpl = new RefCpl();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_user = Yii::$app->user->identity->username;
            $model->save();
            Yii::$app->session->setFlash('success', [['Success', 'Data Berhasil Dimasukkan']]);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'modelcpl' => $modelcpl,
        ]);
    }

    /**
     * Updates an existing RefCpmk model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        // $modelcpl = $this->findModelCpl($idcpl);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_user = Yii::$app->user->identity->username;
            $model->save();
            Yii::$app->session->setFlash('warning', [['Update', 'Data Berhasil Diperbarui']]);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            // 'modelcpl' => $modelcpl,
        ]);
    }

    /**
     * Deletes an existing RefCpmk model.
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

    /**
     * Finds the RefCpmk model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RefCpmk the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RefCpmk::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelCpl($id)
    {
        if (($model = RefCpl::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
