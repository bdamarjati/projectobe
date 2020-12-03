<?php

namespace backend\controllers;

use Yii;
use backend\models\CapaianMahasiswa;
use backend\models\Krs;
use backend\models\MataKuliahTayang;
use backend\models\RefCpmk;
use backend\models\RefDosen;
use backend\models\RefKelas;
use backend\models\RefMahasiswa;
use backend\models\RefMataKuliah;
use backend\models\RefTahunAjaran;
use backend\models\searchs\CapaianMahasiswa as CapaianMahasiswaSearch;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;

/**
 * CapaianMahasiswaController implements the CRUD actions for CapaianMahasiswa model.
 */
class CapaianMahasiswaController extends Controller
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
                // 'actions' => [
                //     'delete' => ['POST'],
                // ],
            ],
        ];
    }

    /**
     * TIDAK DIGUNAKAN
     * Lists all CapaianMahasiswa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CapaianMahasiswaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * UPDATE DIMODIF DENGAN PERULANGAN SESUAI JUMLAH CPMK PADA MATAKULIAH
     * @return mixed
     * perulangan dilakukan berdasarkan jumlah cpmk pada mata kuliah
     * nilai disimpan sesuai id 
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($jk, $js)
    {
        $model = $this->findModel($jk, $js);
        if ($models = (Yii::$app->request->post())) {
            foreach ($model['capaian'] as $key => $value) {
                $key = $key + 1;
                $data = CapaianMahasiswa::findOne([$value->id]);
                $data->updated_user = Yii::$app->user->identity->username;
                $data->nilai = $models["cpmk{$key}"];
                $data->save();
            }
            Yii::$app->session->setFlash('warning', [['Update', 'Data Berhasil Diperbarui']]);
            return $this->redirect(['nilai-upload', 'jk' => $jk]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * FUNCTION DELETE DIMODIF UNTUK MENGHAPUS DATA NILAI CPMK PER MAHASISWA
     * @param integer $jk
     * @param integer $js
     * Mengambil data id cpmk berdasarkan id mata kuliah tayang
     * Melakukan perulangan berdasarkan jumlah cpmk
     * Menghapus data berdasarkan mahasiswa dan id cpmk
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {
        $id_mata_kuliah_tayang = Yii::$app->getRequest()->getQueryParam('jk');
        $id_mahasiswa           = Yii::$app->getRequest()->getQueryParam('js');

        $mata_kuliah_tayang = MataKuliahTayang::find()
            ->joinWith('refCpmks')
            ->where([MataKuliahTayang::tableName() . '.id' => $id_mata_kuliah_tayang])
            ->all();

        $count = count($mata_kuliah_tayang[0]['refCpmks']);

        foreach ($mata_kuliah_tayang as $key => $value) {
            foreach ($value['refCpmks'] as $key => $value) {
                $exist = CapaianMahasiswa::findOne(['id_ref_mahasiswa' => $id_mahasiswa, 'id_ref_cpmk' => $value->id]);
                // $exist->status = 0;
                $exist->delete();
                Yii::$app->session->setFlash('erro', [['Delete', 'Data Berhasil Dihapus']]);
            }
        }
        return $this->redirect(['nilai-upload', 'jk' => $id_mata_kuliah_tayang]);
    }

    /**
     * Finds the CapaianMahasiswa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CapaianMahasiswa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($jk, $js)
    {
        $data['tayang']       = MataKuliahTayang::findOne(['id' => $jk]);
        $model['mahasiswa']    = RefMahasiswa::findOne(['id' => $js]);
        $data['mata_kuliah']  = RefMataKuliah::findOne($data['tayang']->id_ref_mata_kuliah);
        $data['kelas']        = RefKelas::findOne($data['tayang']->id_ref_kelas);
        $data['tahun_ajaran'] = RefTahunAjaran::findOne($data['tayang']->id_tahun_ajaran);
        $data['dosen']        = RefDosen::findOne($data['tayang']->id_ref_dosen);

        $model['capaian']      = CapaianMahasiswa::find()
            ->select([
                'capaian_mahasiswa.*',
            ])
            ->joinWith(
                [
                    'refCpmk' => function ($query) {
                        $jk                   = Yii::$app->getRequest()->getQueryParam('jk');
                        $data['tayang']       = MataKuliahTayang::findOne(['id' => $jk]);
                        $data['mata_kuliah']  = RefMataKuliah::findOne($data['tayang']->id_ref_mata_kuliah);
                        $query->where(['ref_cpmk.id_ref_mata_kuliah' => $data['mata_kuliah']->id]);
                    }
                ]
            )
            ->andWhere([CapaianMahasiswa::tableName() . '.tahun' => $data['tahun_ajaran']->tahun])
            ->andWhere([CapaianMahasiswa::tableName() . '.kelas' => $data['kelas']->kelas])
            ->andWhere([CapaianMahasiswa::tableName() . '.semester' => $data['tayang']->semester])
            ->andWhere([CapaianMahasiswa::tableName() . '.id_ref_mahasiswa' => $js])
            // ->asArray()
            ->all();
        if (($model['capaian']) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * MELIHAT NILAI MAHASISWA YANG PERNAH DIIMPORT
     * @param integer $jk
     * Mengambil beberapa data yang diperlukan dari database berdasarkan $jk
     * Mengambil data nilai mahasiswa berdasarkan id_cpmk, tahun, kelas, semester dari tabel capaian mahasiswa
     * membuat index array menjadi id_ref_mahasiswa dengan bantuan arrayhelper yii2
     * mengirim data ke view
     */
    public function actionNilaiUpload()
    {
        $jk                   = Yii::$app->getRequest()->getQueryParam('jk');
        $data['tayang']       = MataKuliahTayang::findOne(['id' => $jk]);
        $data['mata_kuliah']  = RefMataKuliah::findOne($data['tayang']->id_ref_mata_kuliah);
        $data['kelas']        = RefKelas::findOne($data['tayang']->id_ref_kelas);
        $data['tahun_ajaran'] = RefTahunAjaran::findOne($data['tayang']->id_tahun_ajaran);
        $data['dosen']        = RefDosen::findOne($data['tayang']->id_ref_dosen);

        $data['capaian']      = CapaianMahasiswa::find()
            ->select([
                'capaian_mahasiswa.*',
            ])
            ->joinWith(
                [
                    'refCpmk' => function ($query) {
                        $jk                   = Yii::$app->getRequest()->getQueryParam('jk');
                        $data['tayang']       = MataKuliahTayang::findOne(['id' => $jk]);
                        $data['mata_kuliah']  = RefMataKuliah::findOne($data['tayang']->id_ref_mata_kuliah);
                        $query->where(['ref_cpmk.id_ref_mata_kuliah' => $data['mata_kuliah']->id]);
                    }
                ]
            )
            ->andWhere([CapaianMahasiswa::tableName() . '.tahun' => $data['tahun_ajaran']->tahun])
            ->andWhere([CapaianMahasiswa::tableName() . '.kelas' => $data['kelas']->kelas])
            ->andWhere([CapaianMahasiswa::tableName() . '.semester' => $data['tayang']->semester])
            ->asArray()
            ->all();

        $data['capaian'] = ArrayHelper::index($data['capaian'], null, 'id_ref_mahasiswa');

        // echo '<pre>';
        // print_r($data['capaian']);
        // exit;
        return $this->render('nilai-upload', [
            'data' => $data,
        ]);
    }

    /**
     * TIDAK DIGUNAKAN
     */
    public function actionTranskip()
    {
        $data['transkip'] = RefMataKuliah::find()
            ->select([
                'ref_mata_kuliah.id', 'ref_mata_kuliah.kode', 'ref_mata_kuliah.nama',

                'ref_cpmk.id id_cpmk', 'ref_cpmk.kode kode_cpmk', 'ref_cpmk.id_ref_mata_kuliah',

                'capaian_mahasiswa.id id_capaian', 'capaian_mahasiswa.nilai', 'capaian_mahasiswa.id_ref_cpmk',
                'capaian_mahasiswa.id_ref_mahasiswa id_mhs', 'capaian_mahasiswa.nilai',
            ])
            ->joinWith(['refCpmks.' . 'capaianMahasiswas' => function ($query) {
                $query->where(['id_ref_mahasiswa' => 105]);
            }])
            ->all();
        return $this->render('transkip-nilai', [
            'data' => $data,
        ]);
    }

    /**
     * MENDOWNLOAD TRANSKIP NILAI MAHASISWA YANG PERNAH DI IMPORT
     * @param integer $jk
     * Mengambil semua data nilai per RefMataKuliah  dari database berdasarkan $jk = id_ref_mahasiswa
     * Mengambil data mahasiswa berdasarkan $jk
     * Membuat nama file
     * Meload template transkip nilai dari folder templates
     * Menuliskan data mahasiswa ke dalam file excel
     * Menuliskan data Mata Kuliah dan nilainya sesuai jumlah CPMK ke dalam file excel
     * menuliskan ekstensi sebagai .xlsx
     * Mengirim file excel sebagai response
     */
    public function actionDownloadTranskip()
    {
        $data['transkip'] = RefMataKuliah::find()
            ->select([
                'ref_mata_kuliah.id', 'ref_mata_kuliah.kode', 'ref_mata_kuliah.nama',

                'ref_cpmk.id id_cpmk', 'ref_cpmk.kode kode_cpmk', 'ref_cpmk.id_ref_mata_kuliah',

                'capaian_mahasiswa.id id_capaian', 'capaian_mahasiswa.nilai', 'capaian_mahasiswa.id_ref_cpmk',
                'capaian_mahasiswa.id_ref_mahasiswa id_mhs', 'capaian_mahasiswa.nilai',
            ])
            ->joinWith(['refCpmks.' . 'capaianMahasiswas' => function ($query) {
                $jk                   = Yii::$app->getRequest()->getQueryParam('jk');
                $query->where(['id_ref_mahasiswa' => $jk]);
            }])
            ->all();
        $jk          = Yii::$app->getRequest()->getQueryParam('jk');
        $data['mahasiswa'] = RefMahasiswa::findOne(['id' => $jk]);

        $nama = 'Transkip Nilai_' .
            $data['mahasiswa']->nim . '_' .
            $data['mahasiswa']->nama;

        $base        = Yii::getAlias('@backend/modules/import/templates/template-transkip.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($base);
        $worksheet   = $spreadsheet->getActiveSheet();

        $worksheet->setCellValue('C4', $data['mahasiswa']->nama);  //Nama
        $worksheet->setCellValue('C5', $data['mahasiswa']->nim);  //Nim
        $worksheet->setCellValue('C6', $data['mahasiswa']->angkatan);  //Angkatan

        $no = 1;
        foreach ($data['transkip'] as $key => $data) {

            $row = 11 + $key;
            $worksheet->setCellValue('A' . $row, $no++);  //No
            $worksheet->setCellValue('B' . $row, $data->kode);  //Kode MK
            $worksheet->setCellValue('C' . $row, $data->nama);  //Nama MK 

            foreach ($data['refCpmks'] as $key => $value) {
                $huruf = ['D', 'E', 'F', 'G', 'H'];
                $worksheet->setCellValue($huruf[$key] . $row, $value['capaianMahasiswas'][0]->nilai);  //NILAI MK
            }
        }
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        $path = Yii::getAlias("@backend/uploads/transkip_nilai");

        $base = "{$path}/Transkip_Nilai.xlsx";
        @unlink($base);
        $writer->save($base);

        return Yii::$app->response->sendFile(
            $base,
            $nama . '.xlsx'
        );
    }

    /**
     * MENGHAPUS NILAI CPMK BERDASARKAN $JK = ID MAHASISWA
     * @param integer $jk
     * @param integer $js
     * Mengambil data id cpmk berdasarkan id mata kuliah tayang
     * Melakukan perulangan berdasarkan jumlah mahasiswa yang dipilih
     * Melakukan perulangan berdasarkan jumlah cpmk
     * Menghapus data berdasarkan mahasiswa dan id cpmk
     * Menghapus semua data sesuai id_ref_mahasiswa dan id_mata_kuliah_tayang
     */
    public function actionDeleteMultiple()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $js = $request->post('js');
        $jk = $request->post('jk');

        $mata_kuliah_tayang = MataKuliahTayang::find()
            ->joinWith('refCpmks')
            ->where([MataKuliahTayang::tableName() . '.id' => $jk])
            ->all();
        // $count = count($mata_kuliah_tayang[0]['refCpmks']);

        foreach ($js as $id_mahasiswa) {
            foreach ($mata_kuliah_tayang as $key => $value) {
                foreach ($value['refCpmks'] as $key => $value) {
                    CapaianMahasiswa::deleteAll(['id_ref_mahasiswa' => $id_mahasiswa, 'id_ref_cpmk' => $value->id]);
                }
            }
        }
    }

    public function actionEvaluasiMataKuliah($jk)
    {

        $data['tayang']       = MataKuliahTayang::findOne(['id' => $jk]);
        $data['mata_kuliah']  = RefMataKuliah::findOne($data['tayang']->id_ref_mata_kuliah);
        $data['kelas']        = RefKelas::findOne($data['tayang']->id_ref_kelas);
        $data['tahun_ajaran'] = RefTahunAjaran::findOne($data['tayang']->id_tahun_ajaran);
        $data['dosen']        = RefDosen::findOne($data['tayang']->id_ref_dosen);

        $data['cpmk']   = RefCpmk::find()
            ->where(['id_ref_mata_kuliah' => $data['mata_kuliah']->id])
            ->all();
        foreach ($data['cpmk'] as $key => $value) {
            $average_nilai[]      = CapaianMahasiswa::find()
                ->select([
                    'capaian_mahasiswa.*',
                ])
                ->andWhere([CapaianMahasiswa::tableName() . '.id_ref_cpmk' => $value->id])
                ->andWhere([CapaianMahasiswa::tableName() . '.tahun' => $data['tahun_ajaran']->tahun])
                ->andWhere([CapaianMahasiswa::tableName() . '.kelas' => $data['kelas']->kelas])
                ->andWhere([CapaianMahasiswa::tableName() . '.semester' => $data['tayang']->semester])
                ->average('nilai');
        }


        // Sangat Baik > 85 == $sb
        // Baik 70 - 85 == $baik
        // Cukup 60 - 70 == $cukup
        // Kurang < 60 == $kurang



        foreach ($data['cpmk'] as $key => $value) {
            $nilai      = CapaianMahasiswa::find()
                ->select([
                    'capaian_mahasiswa.*',
                ])
                ->andWhere([CapaianMahasiswa::tableName() . '.id_ref_cpmk' => $value->id])
                ->andWhere([CapaianMahasiswa::tableName() . '.tahun' => $data['tahun_ajaran']->tahun])
                ->andWhere([CapaianMahasiswa::tableName() . '.kelas' => $data['kelas']->kelas])
                ->andWhere([CapaianMahasiswa::tableName() . '.semester' => $data['tayang']->semester])
                ->all();
            $sb     = 0;
            $baik   = 0;
            $cukup  = 0;
            $kurang = 0;

            foreach ($nilai as $key => $val) {
                if ($val->nilai >= 85) {
                    $sb += 1;
                }
                if ($val->nilai < 85 && $val->nilai >= 70) {
                    $baik += 1;
                }
                if ($val->nilai < 70 && $val->nilai >= 60) {
                    $cukup += 1;
                }
                if ($val->nilai < 60) {
                    $kurang += 1;
                }
            }
            $keterangan["sangat_baik"][] = $sb;
            $keterangan["baik"][] = $baik;
            $keterangan["cukup"][] = $cukup;
            $keterangan["kurang"][] = $kurang;
        }
        // echo "<pre>";
        // print_r($keterangan);
        // exit;

        return $this->render('evaluasi-mata-kuliah', [
            'data' => $data,
            'average_nilai' => $average_nilai,
            'keterangan' => $keterangan
        ]);
    }
}
