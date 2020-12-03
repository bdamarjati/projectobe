<?php

namespace backend\controllers;

use backend\models\FileUpload;
use Yii;
use backend\models\Krs;
use backend\models\searchs\Krs as KrsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use backend\models\searchs\RefKelas;
use backend\models\searchs\RefMataKuliah;
use backend\models\MataKuliahTayang;
use backend\models\RefDosen;
use backend\models\RefMahasiswa;
use backend\models\searchs\RefTahunAjaran;
use backend\models\SetupAplikasi;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use backend\models\UploadFileImporter;
use yii\helpers\Html;


/**
 * KrsController implements the CRUD actions for Krs model.
 */
class KrsController extends Controller
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
     * Index digunakan untuk menampilkan dan memproses halaman untuk import KRS.
     * Jika terdapat data yang masuk maka code dibawah $model->load(Yii::$app->request->post() akan tereksekusi
     * Jika file terdapat datanya maka code dibawah $model->file akan tereksekusi
     * Mengambil data pada excel dan menyimpannya ke variabel $spreadsheetData dalam bentuk array
     * Mengambil beberapa value pada beberap sel excel yaitu fakultas, program studi, semester dan data enkripsi
     * Melakukan Decrypt pada data enkripsi
     * Mengambil beberapa data yang perlu ditampilkan dari database berdasarkan data decyprt
     * Membuat nama file
     * Menyimpan file yang diupload ke folder $path
     * Melakukan penyimpanan atau memperbarui nama file ke database
     * Mengirim data ke view
     */
    public function actionIndex($update = 0)
    {
        if (!(Yii::$app->getRequest()->getQueryParam('jk'))) {
            return $this->redirect([
                '/mata-kuliah-tayang',
            ]);
        }
        $model = new UploadFileImporter();
        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstances($model, 'file');
            if ($model->file) {
                $xlsx = $model->file[0]->tempName;
                $reader = new Xlsx();
                $spreadsheet = $reader->load($xlsx);
                $spreadsheetData = $spreadsheet->getActiveSheet()->toArray();

                $fakultas      = $spreadsheet->getActiveSheet()->getCell('C4')->getValue();
                $program_studi = $spreadsheet->getActiveSheet()->getCell('C5')->getValue();
                $semester      = $spreadsheet->getActiveSheet()->getCell('C7')->getValue();
                $encrypt       = $spreadsheet->getActiveSheet()->getCell('B12')->getValue();

                $decrypt = \Yii::$app->encrypter->decrypt($encrypt);
                $tayang      = MataKuliahTayang::findOne($decrypt);
                $mata_kuliah = RefMataKuliah::findOne($tayang->id_ref_mata_kuliah);
                $kelas       = RefKelas::findOne($tayang->id_ref_kelas);
                $tahun       = RefTahunAjaran::findOne($tayang->id_tahun_ajaran);
                $dosen       = RefDosen::findOne($tayang->id_ref_dosen);

                $nama = 'krs_' .
                    $mata_kuliah->kode . '_' .
                    $mata_kuliah->nama . '_' .
                    $kelas->kelas . '_Tahun_' .
                    $tahun->tahun;

                $path = Yii::getAlias("@backend/uploads/file_krs");
                $base = "{$path}/{$nama}.xlsx";
                @unlink($base);
                // echo "<pre>";
                // print_r($base);
                // exit;
                $files = $model->file[0]->saveAs($base, FALSE);
                if ($files) {
                    $flag = true;
                    $newData = false;
                    $data = $exist = FileUpload::findOne(['id_mata_kuliah_tayang' => $decrypt, 'jenis' => 'krs']);
                    if (!$data) {
                        $newData = true;
                        $data = new FileUpload();
                    }
                    if ($update || $newData) {
                        $data->id_mata_kuliah_tayang = $decrypt;
                        $data->file_name = $nama;
                        $data->base_name = $model->file[0]->baseName;
                        $data->jenis = 'krs';
                        $flag = $flag && $data->save(FALSE);
                        if ($update && $exist) {
                        }
                    }
                }

                if (count($spreadsheetData) > 1) {
                    for ($i = 0; $i < 14; $i++) {
                        unset($spreadsheetData[$i]);
                    }

                    return $this->render('view', [
                        'model'            => $spreadsheetData,
                        'update'           => $update,
                        'fakultas'         => $fakultas,
                        'program_studi'    => $program_studi,
                        'tahun_ajaran'     => $tahun->tahun,
                        'semester'         => $semester,
                        'kode_mata_kuliah' => $mata_kuliah->kode,
                        'mata_kuliah'      => $mata_kuliah->nama,
                        'kelas'            => $kelas->kelas,
                        'dosen'            => $dosen->nama_dosen,
                        'encrypt'          => $encrypt,
                    ]);
                } else {
                    Yii::$app->session->setFlash('error', 'Minimal terdapat 1 record data.');

                    return $this->refresh();
                }
            } else {
                Yii::$app->session->setFlash('error', 'Gagal mengunggah file.');
            }
        }

        $model->file = null;
        return $this->render('index', [
            'model'  => $model,
            'update' => $update,
        ]);
    }

    /**
     * Mendownload File yang diupload terakhir kali
     * @param integer $jk
     * Mengambil data file dari tabel FileUpload berdasarkan $jk
     * Mengambil file dari folder file_krs dengan nama dari database
     */
    public function actionFileUpload()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $jk = Yii::$app->getRequest()->getQueryParam('jk');

        $data = FileUpload::findOne(['id_mata_kuliah_tayang' => $jk, 'jenis' => 'krs']);
        $file = Yii::getAlias("@backend/uploads/file_krs/{$data->file_name}.xlsx");
        if (file_exists($file)) {
            return Yii::$app->response->sendFile(
                $file,
                $data->file_name . '.xlsx'
            );
        }
    }

    /**
     * MELIHAT HASIL IMPORT YANG PERNAH DIUPLOAD
     * @param integer $jk
     * Mengambil beberapa data yang diperlukan dari database berdasarkan $jk
     * Mengambil data KRS mahasiswa dari tabel KRS berdasarkan $jk
     */
    public function actionKrsUpload()
    {
        $jk                   = Yii::$app->getRequest()->getQueryParam('jk');
        $data['tayang']       = MataKuliahTayang::findOne(['id' => $jk]);
        $data['mata_kuliah']  = RefMataKuliah::findOne($data['tayang']->id_ref_mata_kuliah);
        $data['kelas']        = RefKelas::findOne($data['tayang']->id_ref_kelas);
        $data['tahun_ajaran'] = RefTahunAjaran::findOne($data['tayang']->id_tahun_ajaran);
        $data['dosen']        = RefDosen::findOne($data['tayang']->id_ref_dosen);

        $data['krs']      = Krs::find()
            ->select('*')
            ->joinWith('refMahasiswa')
            ->where([Krs::tableName() . '.id_mata_kuliah_tayang' => $jk])
            ->andWhere([Krs::tableName() . '.status' => 1])
            ->all();

        return $this->render('krs-upload', [
            'data' => $data,
        ]);
    }

    /**
     * Menghapus mahasiswa berdasarkan $jk dan $js
     * @param integer $jk
     * @param integer $js
     * mencari data sesuai dengan id_ref_mahasiswa dan id_mata_kuliah_tayang pada tabel KRS
     * menghapus data yang ditemukan
     */
    public function actionDeleteMahasiswa()
    {
        $id_mata_kuliah_tayang  = Yii::$app->getRequest()->getQueryParam('jk');
        $id_mahasiswa           = Yii::$app->getRequest()->getQueryParam('js');

        $model = Krs::findOne(['id_ref_mahasiswa' => $id_mahasiswa, 'id_mata_kuliah_tayang' => $id_mata_kuliah_tayang]);;
        if ($model) {
            // $model->status  = 0;
            $model->delete();
            Yii::$app->session->setFlash('error', [['Delete', 'Data Berhasil Dihapus']]);
        }
        return $this->redirect(['krs/krs-upload/', 'jk' => $id_mata_kuliah_tayang]);
    }

    /**
     * Menghapus mahasiswa berdasarkan $jk dan $js secara bersamaan
     * @param integer $jk
     * @param integer $js
     * Menghapus semua data sesuai id_ref_mahasiswa dan id_mata_kuliah_tayang
     */
    public function actionDeleteMultiple()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $js = $request->post('js');
        $jk = $request->post('jk');

        // echo 'pre';
        // print_r($js);
        // exit();
        foreach ($js as $id_mahasiswa) {
            $model = Krs::deleteAll(['id_ref_mahasiswa' => $js, 'id_mata_kuliah_tayang' => $jk]);;
        }
    }

    public function actionProsesAjax($update = 0)
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;

        $update        = $update == 1;

        $data          = $request->post('data');
        $encrypt       = $request->post('encrypt');

        $decrypt = \Yii::$app->encrypter->decrypt($encrypt);

        $hr     = "<td></td>";
        if (!empty($data)) {
            $no   = $data[0];
            $nim  = strtoupper(trim($data[1]));
            $nama = $data[2];

            $id_mahasiswa = RefMahasiswa::findOne(['nim' => $nim]);

            if (!$nim || !$id_mahasiswa) {
                $required = "<td><span class='label label-danger'>Wajib Diisi</span></td>";
                $html = "<td><span class='label label-danger'>Error</span><br></td>";
                // $html .= "<td>{$no}</td>";

                // $html .= $hr;

                if (!$nim)
                    $html .= $required;
                else if (!$id_mahasiswa)
                    $html .= "<td><span class='label label-danger'>Nim Tidak Ada</span></td>";
                else
                    $html .= "<td>{$nim}</td>";

                if (!$nama)
                    $html .= $required;
                else
                    $html .= "<td>{$nama}</td>";

                return [
                    'code' => 200,
                    'description' => 'Error Validasi',
                    'data' => [
                        'class' => 'danger',
                        'html'  => "$html",
                    ],
                ];
            }

            $html = [
                $hr,
                "<td class='text-monospace'>{$nim}</td>",
                "<td>{$nama}</td>",
                $hr
            ];

            $status = $desc = '';
            $statust = $desct = '';


            // echo '<pre>';
            // print_r($tahun);
            // exit;
            $transaction  = Yii::$app->db->beginTransaction();
            try {
                $desc   .= $desct;
                $status .= $statust;
                $flag = true;

                // for ($i = 0; $i < $count; $i++) {
                $newData = false;
                $data    = $exist = Krs::findOne(['id_ref_mahasiswa' => $id_mahasiswa->id, 'id_mata_kuliah_tayang' => $decrypt]);
                if (!$data) {
                    $newData = true;
                    $data    = new Krs();
                } else {
                    $desct   = 'Skip Nim ';
                    $statust = "<span class='label label-warning'>Skip Nim</span><br>";
                }
                if ($update || $newData) {
                    $data->id_mata_kuliah_tayang = $decrypt;
                    $data->id_ref_mahasiswa      = $id_mahasiswa->id;
                    if ($update) {
                        $data->updated_user = Yii::$app->user->identity->username;
                    } else {
                        $data->created_user = Yii::$app->user->identity->username;
                    }
                    $flag = $flag && $data->save(false);

                    if ($update && $exist) {
                        $desct   = 'Update Mahasiswa ';
                        $statust = "<span class='label label-warning'>Update Nim</span><br>";
                    }
                }
                // }


                $desc   .= $desct;
                $status .= $statust;

                if ($flag) {
                    $transaction->commit();

                    // Yii::$app->assign->addAssign('Wali Murid', Yii::$app->id, $user->id);
                    // Yii::$app->assign->addAssign('Siswa', Yii::$app->id, $user->id);

                    $class   = $status ? 'warning' : 'success';
                    $html[0] = $status ? "<td>{$status}</td>" : "<td><span class='label label-success'>Sukses</span></td>";
                } else {
                    $transaction->rollBack();

                    $desc    = 'Roll Back Error Save';
                    $class   = 'danger';
                    $html[0] = "<td><span class='label label-danger'>Error Save</span></td>";
                }
            } catch (\yii\db\Exception $e) {
                $transaction->rollBack();

                $desc    = 'Roll Back Error Save';
                $class   = 'danger';
                $html[0] = "<td><span class='label label-danger'>Error Save</span></td>";
            }
            $htmlString = '';
            foreach ($html as $value) {
                $htmlString .= $value;
            }

            return [
                'code' => 200,
                'description' => $desc,
                'data' => [
                    'class' => $class,
                    'html'  => $htmlString,
                ],
            ];
        }

        return [
            'code' => 401,
            'description' => 'No Data',
            'data' => [
                'class' => 'danger',
                'html'  => "<td><span class='label label-warning'>Error</span></td>
					<td colspan='30'>No Data</td>",
            ],
        ];
    }

    /**
     * Function untuk mendownload template KRS
     * @param integer $jk
     * Mengambil beberapa data dari tabel database berdasarkan $jk
     * membuat nama file template
     * Mengambil file excel template yang telah disediakan pada folder templates
     * menuliskan beberapa nilai ke sel tertentu
     * menuliskan ekstensi file
     * mengirimkan file sebagai respons dari function
     */
    public function actionDownloadTemplate()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (empty(Yii::$app->getRequest()->getQueryParam('jk'))) {
            return $this->redirect([
                '/mata-kuliah-tayang'
            ]);
        }
        $id_tayang = Yii::$app->getRequest()->getQueryParam('jk');

        $model       = MataKuliahTayang::findOne($id_tayang);
        $mata_kuliah = RefMataKuliah::findOne($model->id_ref_mata_kuliah);
        $kelas       = RefKelas::findOne($model->id_ref_kelas);
        $tahun       = RefTahunAjaran::findOne($model->id_tahun_ajaran);
        $dosen       = RefDosen::findOne($model->id_ref_dosen);
		$aplikasi    = SetupAplikasi::find()->one();
        $nama = 'krs_' .
            $mata_kuliah->kode . '_' .
            $mata_kuliah->nama . '_' .
            $kelas->kelas . '_Tahun_' .
            $tahun->tahun;

        $base        = Yii::getAlias('@backend/modules/import/templates/template-krs.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($base);
        $worksheet   = $spreadsheet->getActiveSheet();

        $worksheet->setCellValue('A2', strtoupper($aplikasi->univ_id));  //Univ
		$worksheet->setCellValue('C4', strtoupper($aplikasi->fakultas_id));  //Fakultas
		$worksheet->setCellValue('C5', 'S1 - '.strtoupper($aplikasi->prodi_id));  //Prodi
        $worksheet->setCellValue('C6', $tahun->tahun);  //Tahun Ajaran
        $worksheet->setCellValue('C7', $model->semester);  //Semester
        $worksheet->setCellValue('C8', $mata_kuliah->kode);  //Kode Mata Kuliah
        $worksheet->setCellValue('C9', $mata_kuliah->nama);  //Nama Mata Kuliah
        $worksheet->setCellValue('C10', $kelas->kelas);  //Kelas
        $worksheet->setCellValue('C11', $dosen->nama_dosen);  //Pengampu

        $encrypt    = \Yii::$app->encrypter->encrypt($id_tayang);
        $worksheet->setCellValue('B12', $encrypt);  //id mata kuliah tayang


        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        $path = Yii::getAlias("@backend/uploads/import_nilai");

        $base = "{$path}/krs.xlsx";
        @unlink($base);
        $writer->save($base);

        return Yii::$app->response->sendFile(
            $base,
            $nama . '.xlsx'
        );
    }

    /**
     * Displays a single Krs model.
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
     * TIDAK DIGUNAKAN
     * Creates a new Krs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Krs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * TIDAK DIGUNAKAN
     * Updates an existing Krs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('warning', [['Update', 'Data Berhasil Diperbarui']]);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * TIDAK DIGUNAKAN
     * Deletes an existing Krs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $model->status  = 0;
            $model->save();
            Yii::$app->session->setFlash('erro', [['Delete', 'Data Berhasil Dihapus']]);
        }
        return $this->redirect(['index']);
    }

    /**
     * TIDAK DIGUNAKAN
     * Finds the Krs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Krs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Krs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
