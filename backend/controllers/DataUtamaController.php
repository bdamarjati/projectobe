<?php

namespace backend\controllers;

use backend\models\CapaianMahasiswa as ModelsCapaianMahasiswa;
use backend\models\FileUpload;
use backend\models\Krs;
use backend\models\MataKuliahTayang;
use backend\models\RefDosen;
use backend\models\searchs\CapaianMahasiswa;
use backend\models\searchs\RefKelas;
use backend\models\searchs\RefCpmk;
use backend\models\searchs\RefMataKuliah;
use backend\models\searchs\RefTahunAjaran;
use backend\models\searchs\RefMahasiswa;
use backend\models\searchs\SetupAplikasi;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use backend\models\UploadFileImporter;
use yii\filters\AccessControl;

/**
 * RefCplController implements the CRUD actions for RefCpl model.
 */
class DataUtamaController extends Controller
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

	/**
	 * Index digunakan untuk menampilkan dan memproses halaman untuk import NILAI.
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

				$fakultas         = $spreadsheet->getActiveSheet()->getCell('C4')->getValue();
				$program_studi    = $spreadsheet->getActiveSheet()->getCell('C5')->getValue();
				// $tahun_ajaran     = $spreadsheet->getActiveSheet()->getCell('C6')->getValue();
				$semester         = $spreadsheet->getActiveSheet()->getCell('C7')->getValue();
				// $kode_mata_kuliah = $spreadsheet->getActiveSheet()->getCell('C8')->getValue();
				// $mata_kuliah      = $spreadsheet->getActiveSheet()->getCell('C9')->getValue();
				// $kelas            = $spreadsheet->getActiveSheet()->getCell('C10')->getValue();
				// $dosen            = $spreadsheet->getActiveSheet()->getCell('C11')->getValue();
				$encrypt            = $spreadsheet->getActiveSheet()->getCell('B12')->getValue();

				$decrypt     = \Yii::$app->encrypter->decrypt($encrypt);
				$tayang      = MataKuliahTayang::findOne($decrypt);
				$mata_kuliah = RefMataKuliah::findOne($tayang->id_ref_mata_kuliah);
				$kelas       = RefKelas::findOne($tayang->id_ref_kelas);
				$tahun       = RefTahunAjaran::findOne($tayang->id_tahun_ajaran);
				$dosen       = RefDosen::findOne($tayang->id_ref_dosen);

				$nama = 'nilai_' .
					$mata_kuliah->kode . '_' .
					$mata_kuliah->nama . '_' .
					$kelas->kelas . '_Tahun_' .
					$tahun->tahun;

				$path = Yii::getAlias("@backend/uploads/file_nilai");
				$base = "{$path}/{$nama}.xlsx";
				@unlink($base);
				$files = $model->file[0]->saveAs($base, FALSE);
				if ($files) {
					$flag    = true;
					$newData = false;
					$data = $exist = FileUpload::findOne(['id_mata_kuliah_tayang' => $decrypt, 'jenis' => 'nilai']);
					if (!$data) {
						$newData = true;
						$data    = new FileUpload();
					}
					if ($update || $newData) {
						$data->id_mata_kuliah_tayang = $decrypt;
						$data->file_name             = $nama;
						$data->base_name             = $model->file[0]->baseName;
						$data->jenis                 = 'nilai';
						if ($update) {
							$data->updated_user = Yii::$app->user->identity->username;
						} else {
							$data->created_user = Yii::$app->user->identity->username;
						}
						$flag                        = $flag && $data->save(FALSE);
						if ($update && $exist) {
						}
					}
				}

				if (count($spreadsheetData) > 1) {
					for ($i = 0; $i < 14; $i++) {
						unset($spreadsheetData[$i]);
					}

					// echo "<pre>";
					// print_r($spreadsheetData);
					// exit;

					return $this->render('view', [
						'model'  			=> $spreadsheetData,
						'update'			=> $update,
						'fakultas' 			=> $fakultas,
						'program_studi' 	=> $program_studi,
						'tahun_ajaran' 		=> $tahun->tahun,
						'semester' 			=> $semester,
						'kode_mata_kuliah' 	=> $mata_kuliah->kode,
						'mata_kuliah' 		=> $mata_kuliah->nama,
						'kelas' 			=> $kelas->kelas,
						'dosen' 			=> $dosen->nama_dosen,
						'encrypt' 			=> $encrypt,
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

	// public function actionForm()
	// {
	// 	$model	= new MataKuliahTayang();
	// 	$data['tahun_ajaran'] 	= ArrayHelper::map(RefTahunAjaran::find()->all(), 'id', 'tahun');
	// 	$data['kelas'] 			= ArrayHelper::map(RefKelas::find()->all(), 'id', 'kelas');
	// 	$data['mata_kuliah'] 	= ArrayHelper::map(RefMataKuliah::find()->all(), 'id', 'nama');

	// 	return $this->render('form', [
	// 		'tahun_ajaran'	=> $data['tahun_ajaran'],
	// 		'kelas'			=> $data['kelas'],
	// 		'mata_kuliah'	=> $data['mata_kuliah'],
	// 		'model'			=> $model
	// 	]);
	// }

	// public function actionLandingDownload()
	// {
	// 	Yii::$app->response->format = Response::FORMAT_JSON;
	// 	$model	= new MataKuliahTayang();
	// 	if ($model->load(Yii::$app->request->post())) {
	// 		$mata_kuliah = RefMataKuliah::findOne($model->id_ref_mata_kuliah);
	// 		$kelas       = RefKelas::findOne($model->id_ref_kelas);
	// 		$tahun       = RefTahunAjaran::findOne($model->id_tahun_ajaran);


	// 		$nama = 'nilai_' .
	// 			$mata_kuliah->kode . '_' .
	// 			$mata_kuliah->nama . '_' .
	// 			$kelas->kelas . '_Tahun_' .
	// 			$tahun->tahun;

	// 		$base        = Yii::getAlias('@backend/modules/import/templates/template.xlsx');
	// 		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($base);
	// 		$worksheet   = $spreadsheet->getActiveSheet();

	// 		$worksheet->setCellValue('C6', $tahun->tahun);  //Tahun Ajaran
	// 		$worksheet->setCellValue('C7', $model->semester);  //Semester
	// 		$worksheet->setCellValue('C8', $mata_kuliah->kode);  //Kode Mata Kuliah
	// 		$worksheet->setCellValue('C9', $mata_kuliah->nama);  //Nama Mata Kuliah
	// 		$worksheet->setCellValue('C10', $kelas->kelas);  //Kelas
	// 		$worksheet->setCellValue('C11', 'Pengampu Belum diambil');  //Pengampu

	// 		$encrypt	= \Yii::$app->encrypter->encrypt($model->id_ref_mata_kuliah);
	// 		$worksheet->setCellValue('B12', $encrypt);  //id mata kuliah

	// 		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

	// 		$path = Yii::getAlias("@backend/uploads/import_nilai");

	// 		$base = "{$path}/{$nama}.xlsx";
	// 		@unlink($base);
	// 		$writer->save($base);

	// 		return $this->redirect([
	// 			'download',
	// 			'nama' => $nama,
	// 		]);
	// 	}

	// 	$data['tahun_ajaran'] = ArrayHelper::map(RefTahunAjaran::find()->all(), 'id', 'tahun');
	// 	$data['kelas']        = ArrayHelper::map(RefKelas::find()->all(), 'id', 'kelas');
	// 	$data['mata_kuliah']  = ArrayHelper::map(RefMataKuliah::find()->all(), 'id', 'nama');

	// 	return [
	// 		'title'   => 'Portal Download Template',
	// 		'content' => $this->renderAjax('landing-download', [
	// 			'tahun_ajaran'	=> $data['tahun_ajaran'],
	// 			'kelas'			=> $data['kelas'],
	// 			'mata_kuliah'	=> $data['mata_kuliah'],
	// 			'model'			=> $model
	// 		]),
	// 		'footer'  => '<div class="col-12 text-right">' .
	// 			Html::button(
	// 				'Batal',
	// 				[
	// 					'class'        => 'btn btn-secondary',
	// 					'data-dismiss' => 'modal',
	// 				]
	// 			) . ' ' .
	// 			Html::button(
	// 				'Submit',
	// 				[
	// 					'class'  => 'btn btn-success',
	// 					'type'   => 'submit',
	// 				]
	// 			) .
	// 			'</div>'
	// 	];
	// }

	/**
	 * FUNCTION UNTUK MENDOWNLOAD TEMPLATE NILAI
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
		$mahasiswa   = Krs::find()
			->joinWith('refMahasiswa')
			->where([Krs::tableName() . '.id_mata_kuliah_tayang' => $id_tayang])
			->all();
		$cpmks        = RefCpmk::find()
			->where(['id_ref_mata_kuliah' => $model->id_ref_mata_kuliah])
			->andWhere(['status' => 1])
			->all();

		$nama = 'nilai_' .
			$mata_kuliah->kode . '_' .
			$mata_kuliah->nama . '_' .
			$kelas->kelas . '_Tahun_' .
			$tahun->tahun;

		$base        = Yii::getAlias('@backend/modules/import/templates/template-nilai.xlsx');
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($base);
		$worksheet   = $spreadsheet->getActiveSheet();

		$worksheet->setCellValue('A2', strtoupper($aplikasi->univ_id));  //Univ
		$worksheet->setCellValue('C4', strtoupper($aplikasi->fakultas_id));  //Fakultas
		$worksheet->setCellValue('C5', 'S1 - ' . strtoupper($aplikasi->prodi_id));  //Prodi
		$worksheet->setCellValue('C6', $tahun->tahun);  //Tahun Ajaran
		$worksheet->setCellValue('C7', $model->semester);  //Semester
		$worksheet->setCellValue('C8', $mata_kuliah->kode);  //Kode Mata Kuliah
		$worksheet->setCellValue('C9', $mata_kuliah->nama);  //Nama Mata Kuliah
		$worksheet->setCellValue('C10', $kelas->kelas);  //Kelas
		$worksheet->setCellValue('C11', $dosen->nama_dosen);  //Pengampu

		$total_cpmk = count($cpmks);
		for ($i = 0; $i < $total_cpmk; $i++) {
			$huruf = ['D', 'E', 'F', 'G', 'H'];
			$key   = $i + 1;
			$isi   = $i + 4;
			$worksheet->setCellValue($huruf[$i] . '14', 'CPMK' . $key);  //Jumlah CPMK
			$worksheet->setCellValue('K' . $isi, 'CPMK' . $key);  //Kode CPMK
			$worksheet->setCellValue('L' . $isi, $cpmks[$i]->isi);  //isi CPMK
		}

		$count = count($mahasiswa);
		$no = 1;
		for ($i = 0; $i < $count; $i++) {
			$j = 15 + $i;
			$worksheet->setCellValue('A' . $j, $no++);  //no
			$worksheet->setCellValue('B' . $j, $mahasiswa[$i]['refMahasiswa']->nim);  //nim
			$worksheet->setCellValue('C' . $j, $mahasiswa[$i]['refMahasiswa']->nama);  //nama mahasiswa
		}

		$encrypt	= \Yii::$app->encrypter->encrypt($id_tayang);
		$worksheet->setCellValue('B12', $encrypt);  //id mata kuliah tayang

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$path = Yii::getAlias("@backend/uploads/import_nilai");
		$base = "{$path}/nilai.xlsx";
		@unlink($base);
		$writer->save($base);

		return Yii::$app->response->sendFile(
			$base,
			$nama . '.xlsx'
		);
	}

	/**
	 * TIDAK DIGUNAKAN
	 */
	public function actionDownload($nama)
	{
		$download = Yii::getAlias("@backend/uploads/import_nilai/{$nama}.xlsx");
		return Yii::$app->response->sendFile(
			$download,
			$nama . '.xlsx'
		);
	}

	/**
	 * Mendownload File yang diupload terakhir kali
	 * @param integer $jk
	 * Mengambil data file dari tabel FileUpload berdasarkan $jk
	 * Mengambil file dari folder file_nilai dengan nama dari database
	 */
	public function actionFileUpload()
	{
		Yii::$app->response->format = Response::FORMAT_JSON;
		$jk = Yii::$app->getRequest()->getQueryParam('jk');

		$data = FileUpload::findOne(['id_mata_kuliah_tayang' => $jk, 'jenis' => 'nilai']);
		$file = Yii::getAlias("@backend/uploads/file_nilai/{$data->file_name}.xlsx");
		if (file_exists($file)) {
			return Yii::$app->response->sendFile(
				$file,
				$data->file_name . '.xlsx'
			);
		}
	}

	public function actionProsesAjax($update = 0)
	{
		$request = Yii::$app->request;
		Yii::$app->response->format = Response::FORMAT_JSON;

		$update   = $update == 1;

		$data     = $request->post('data');
		$encrypt  = $request->post('encrypt');
		// $tahun    = $request->post('tahun_ajaran');  // Langsung dari Mata Kuliah Tayang Saja
		// $semester = $request->post('semester');      // Langsung dari Mata Kuliah Tayang Saja
		// $kelas    = $request->post('kelas');         // Langsung dari Mata Kuliah Tayang Saja

		$decrypt      = \Yii::$app->encrypter->decrypt($encrypt);
		$model        = MataKuliahTayang::findOne($decrypt);

		$tahun_ajaran = RefTahunAjaran::findOne($model->id_tahun_ajaran);
		$kelas        = RefKelas::findOne($model->id_ref_kelas);
		$cpmks        = RefCpmk::find()
			->where(['id_ref_mata_kuliah' => $model->id_ref_mata_kuliah])
			->andWhere(['status' => 1])
			->orderBy(['kode' => SORT_ASC])
			->all();
		// echo '<pre>';
		// print_r($cpmks);
		// exit;
		$count = count($cpmks);
		for ($i = 0; $i < $count; $i++) {
			$id_cpmk[] = $cpmks[$i]->id;  //CPMK
		}

		if (!$cpmks) {
			return [
				'code' => 401,
				'description' => 'Token Error',
				'data' => [
					'class' => 'danger',
					'html'  => "<td><span class='label label-warning'>Error Token</span></td>
						<td colspan='30'>Tolong Download Template Kembali</td>",
				],
			];
		}

		$hr     = "<td></td>";
		if (!empty($data)) {
			$no   = $data[0];
			$nim  = strtoupper(trim($data[1]));
			$nama = $data[2];

			for ($i = 0; $i < 5; $i++) {
				$cpmk[] = '-';  //CPMK di set NULL
			}

			for ($i = 0; $i < $count; $i++) {
				$j = $i + 3;
				$cpmk[$i] = $data[$j];  //CPMK
			}


			$id_mahasiswa = RefMahasiswa::findOne(['nim' => $nim]);
			$notin_krs    = Krs::find()
				->joinWith('refMahasiswa')
				->where([Krs::tableName() . '.id_mata_kuliah_tayang' => $decrypt])
				->andWhere([RefMahasiswa::tableName() . '.nim' => $nim])
				->all();

			if (
				!$nim || !$cpmk || !$id_mahasiswa ||
				!$notin_krs || !$cpmk[0] || !$cpmk[1] || !$cpmk[2] || !$cpmk[3] || !$cpmk[4]
			) {

				$required = "<td><span class='label label-danger'>Wajib Diisi</span></td>";
				$html = "<td><span class='label label-danger'>Error</span></td>";

				if (!$nim)
					$html .= $required;
				else if (!$id_mahasiswa)
					$html .= "<td><span class='label label-danger'>Nim Tidak Ada</span></td>";
				else if (!$notin_krs)
					$html .= "<td><span class='label label-danger'>Nim Tidak diKRS</span></td>";
				else
					$html .= "<td>{$nim}</td>";

				if (!$nama)
					$html .= $required;
				else
					$html .= "<td>{$nama}</td>";

				for ($i = 0; $i < $count; $i++) {
					if (!$cpmk[$i])
						$html .= $required;
					else
						$html .= "<td>{$cpmk[$i]}</td>";
				}

				return [
					'code' => 200,
					'description' => 'Error Validasi',
					'data' => [
						'class' => 'danger',
						'html'  => $html,
					],
				];
			}

			if ($count == 5) {
				$html = [
					$hr,
					"<td>{$nim}</td>",
					"<td>{$nama}</td>",
					"<td>{$cpmk[0]}</td>",
					"<td>{$cpmk[1]}</td>",
					"<td>{$cpmk[2]}</td>",
					"<td>{$cpmk[3]}</td>",
					"<td>{$cpmk[4]}</td>",
				];
			} elseif ($count == 4) {
				$html = [
					$hr,
					"<td>{$nim}</td>",
					"<td>{$nama}</td>",
					"<td>{$cpmk[0]}</td>",
					"<td>{$cpmk[1]}</td>",
					"<td>{$cpmk[2]}</td>",
					"<td>{$cpmk[3]}</td>",
				];
			} elseif ($count == 3) {
				$html = [
					$hr,
					"<td>{$nim}</td>",
					"<td>{$nama}</td>",
					"<td>{$cpmk[0]}</td>",
					"<td>{$cpmk[1]}</td>",
					"<td>{$cpmk[2]}</td>",
				];
			} elseif ($count == 2) {
				$html = [
					$hr,
					"<td>{$nim}</td>",
					"<td>{$nama}</td>",
					"<td>{$cpmk[0]}</td>",
					"<td>{$cpmk[1]}</td>",
				];
			} elseif ($count == 1) {
				$html = [
					$hr,
					"<td>{$nim}</td>",
					"<td>{$nama}</td>",
					"<td>{$cpmk[0]}</td>",
				];
			}

			$status = $desc = '';
			$statust = $desct = '';

			$transaction  = Yii::$app->db->beginTransaction();
			try {
				$desc   .= $desct;
				$status .= $statust;
				$flag = true;

				for ($i = 0; $i < $count; $i++) {
					$newData = false;
					$data    = $exist = CapaianMahasiswa::findOne(['id_ref_mahasiswa' => $id_mahasiswa->id, 'id_ref_cpmk' => $id_cpmk[$i]]);
					if (!$data) {
						$newData = true;
						$data    = new CapaianMahasiswa();
					} else {
						$desct   = 'Skip nim ';
						$statust = "<span class='label label-warning'>Skip Nim</span><br>";
					}
					if ($update || $newData) {
						$data->id_ref_cpmk      = $id_cpmk[$i];                 // ID CPMK dalam bentuk array
						$data->id_ref_mahasiswa = $id_mahasiswa->id;
						if ($update) {
							$data->updated_user = Yii::$app->user->identity->username;
						} else {
							$data->created_user = Yii::$app->user->identity->username;
						}
						if ($data->nilai > $cpmk[$i]) {
							$data->nilai = $data->nilai;
						} else {
							$data->nilai = $cpmk[$i];
						}
						$data->tahun        = $tahun_ajaran->tahun;
						$data->semester     = $model->semester;
						$data->kelas        = $kelas->kelas;
						$data->status       = 1;
						$flag               = $flag && $data->save(false);

						if ($update && $exist) {
							$desct   = 'Update Nilai ';
							$statust = "<span class='label label-warning'>Update Nilai</span><br>";
						}
					}
				}


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
}
