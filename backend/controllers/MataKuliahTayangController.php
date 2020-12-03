<?php

namespace backend\controllers;

use Yii;
use backend\models\MataKuliahTayang;
use backend\models\RefKelas;
use backend\models\RefMataKuliah;
use backend\models\RefTahunAjaran;
use backend\models\searchs\MataKuliahTayang as MataKuliahTayangSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;

/**
 * MataKuliahTayangController implements the CRUD actions for MataKuliahTayang model.
 */
class MataKuliahTayangController extends Controller
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
     * Lists all MataKuliahTayang models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MataKuliahTayangSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MataKuliahTayang model.
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
     * Membuat Mata Kuliah Tayang baru
     * Jika mata kuliah terdapat isinya maka masuk kondisi $app->request->post()
     * Melakukan pengecekan apakah mata kuliah tayang sudah pernah dibuat atau belum
     * Jika belum maka disimpan
     * Jika sudah maka akan menampilkan sweet alert error "Gagal memasukkan Data"
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MataKuliahTayang();

        if ($model->load(Yii::$app->request->post())) {

            $cek = MataKuliahTayang::findOne([
                'id_tahun_ajaran'    => $model->id_tahun_ajaran,
                'semester'           => $model->semester,
                'id_ref_mata_kuliah' => $model->id_ref_mata_kuliah,
                'id_ref_kelas'       => $model->id_ref_kelas,
                'status'             => '1'
            ]);
            if (!$cek) {
                $model->created_user = Yii::$app->user->identity->username;
                $model->save();
                Yii::$app->session->setFlash('success', [['Success', 'Data Berhasil Dimasukkan']]);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $model = $this->findModel($cek->id);
                Yii::$app->getSession()->setFlash('alert', [
                    'text' => "Gagal Memasukkan Data
                    {$model['refMataKuliah']->nama}
                    Tahun {$model['tahunAjaran']->tahun}
                    Semester {$model->semester} 
                    Kelas {$model['refKelas']->kelas}",
                    'title' => 'Data Pernah Dimasukkan',
                    'type' => 'error',
                    'timer' => 20000,
                    'showConfirmButton' => false
                ]);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Memperbarui Mata Kuliah Tayang berdasarkan id
     * Jika mata kuliah terdapat isinya maka masuk kondisi $app->request->post()
     * Melakukan pengecekan apakah mata kuliah tayang sudah pernah dibuat atau belum
     * Jika belum maka disimpan
     * Jika sudah maka akan menampilkan sweet alert error "Gagal memasukkan Data"
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $cek = MataKuliahTayang::findOne([
                'id_tahun_ajaran'    => $model->id_tahun_ajaran,
                'semester'           => $model->semester,
                'id_ref_mata_kuliah' => $model->id_ref_mata_kuliah,
                'id_ref_kelas'       => $model->id_ref_kelas,
                'status'             => '1'
            ]);
            if (!$cek || ($cek && ($cek->id==$model->id))) {
                $model->updated_user = Yii::$app->user->identity->username;
                $model->save();
                Yii::$app->session->setFlash('warning', [['Update', 'Data Berhasil Diperbarui']]);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $model = $this->findModel($cek->id);
                Yii::$app->getSession()->setFlash('alert', [
                    'text' => "Gagal Memasukkan Data
                    {$model['refMataKuliah']->nama}
                    Tahun {$model['tahunAjaran']->tahun}
                    Semester {$model->semester} 
                    Kelas {$model['refKelas']->kelas}",
                    'title' => 'Data Pernah Dimasukkan',
                    'type' => 'error',
                    'timer' => 20000,
                    'showConfirmButton' => false
                ]);
                return $this->redirect(['update', 'id'=>$model->id]);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MataKuliahTayang model.
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
        }
        Yii::$app->session->setFlash('error', [['Delete', 'Data Berhasil Dihapus']]);
        return $this->redirect(['index']);
    }

    /**
     * TIDAK DIGUNAKAN
     */
    public function actionFileNilai()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model    = new MataKuliahTayang();
        $data['tahun_ajaran'] = ArrayHelper::map(RefTahunAjaran::find()->all(), 'id', 'tahun');
        $data['kelas']        = ArrayHelper::map(RefKelas::find()->all(), 'id', 'kelas');
        $data['mata_kuliah']  = ArrayHelper::map(RefMataKuliah::find()->all(), 'id', 'nama');

        $query = MataKuliahTayang::find()
            ->joinWith("refMataKuliah")
            ->all();
        $data['mk_tayang']  = ArrayHelper::map($query, 'id', 'refMataKuliah.nama');

        return [
            'title'   => 'File Nilai',
            'content' => $this->renderAjax('file-nilai', [
                'tahun_ajaran' => $data['tahun_ajaran'],
                'kelas'        => $data['kelas'],
                'mata_kuliah'  => $data['mata_kuliah'],
                'mk_tayang'    => $data['mk_tayang'],
                'model'        => $model
            ]),
            'footer'  => '<div class="col-12 text-right">' .
                Html::button(
                    'Batal',
                    [
                        'class'        => 'btn btn-secondary',
                        'data-dismiss' => 'modal',
                    ]
                ) . ' ' .
                Html::button(
                    'Submit',
                    [
                        'class'  => 'btn btn-success',
                        'type'   => 'submit',
                    ]
                ) .
                '</div>'
        ];
    }


    /**
     * Finds the MataKuliahTayang model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MataKuliahTayang the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MataKuliahTayang::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
