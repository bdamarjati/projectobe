<?php

use backend\models\RefCpl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

$this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js", [
    'depends' => ["yii\web\JqueryAsset"]
]);
$this->registerJsFile("@web/js/utils.js", [
    'depends' => ["yii\web\JqueryAsset"]
]);

$_data = Json::encode(array_values($individu));
$_label = RefCpl::find()
    ->orderBy(['id' => SORT_ASC])
    ->where(['status' => 1])
    ->all();
$_label = ArrayHelper::getColumn($_label, 'kode');
$_label = Json::encode(array_values($_label));

// echo "<pre>";
// print_r($_label);
// exit;
$js = <<< JS

		var color = Chart.helpers.color;
		var radarData = {
			labels: $_label,
			datasets: [{
				label: 'Nilai Capaian',
				backgroundColor: color(window.chartColors.red).alpha(0.2).rgbString(),
				borderColor: window.chartColors.red,
				pointBackgroundColor: window.chartColors.red,
				data: $_data,
				fill: true
			}]
		};

		var color = Chart.helpers.color;
		var barChartData = {
			labels: $_label,
			datasets: [{
				label: 'Nilai Capaian',
				backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data: $_data,
				fill: true
			}]
		};

		window.onload = function() {
			// window.myRadar = new Chart(document.getElementById('radar'), config);

			var radar = document.getElementById('radar');
			window.myRadar = new Chart(radar, {
				type: 'radar',
				data: radarData,
				options: {
					legend: {
						position: 'top',
					},
					title: {
						display: true,
						text: '$mahasiswa->nama'
					},
					scale: {
						ticks: {
							min: 0,
							max: 100
						}
					}
				}
			});


			var ctx = document.getElementById('vertical-bar').getContext('2d');
			window.myBar = new Chart(ctx, {
				type: 'bar',
				data: barChartData,
				options: {
					responsive: true,
					legend: {
						position: 'top',
					},
					scales: {
						yAxes: [{
							ticks: {
								min: 0,
								max: 100
							}
						}]
					},
					title: {
						display: true,
						text: '$mahasiswa->nama'
					}

				}
			});
		};
JS;

$this->registerJs($js);

$css = <<< CSS
canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background: none;
    outline: none;
}
CSS;
$this->registerCss($css);
?>

<body style="background-color:red">
    <div class="row">
        <table class="table">
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="font-size:14px">PROGRAM STUDI TEKNIK SIPIL</p>
                    <i style="font-size:11px;color:grey"> Departemen of Civil Engineering </i>
                </td>
                <td rowspan="2">
                    <div style="text-align: center;">
                        <p style="font-size:14px; ">Nomor: /UN27.8/PP/2020</p>
                        <i style="font-size:11px;color:grey">Number</i>
                    </div>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="font-size:14px">FAKULTAS TEKNIK</p>
                    <i style="font-size:11px;color:grey">Faculty of Engineering</i>
                </td>
            </tr>
        </table>
    </div>
    <div style="text-align: center;">
        <h4>
            <b> SURAT KETERANGAN PENDAMPING IJAZAH</b> <br>
            <i style="font-size:16px;color:grey">Diploma Supplement</i>
        </h4>
        <p style="font-size:12px">
            Surat Keterangan Pendamping Ijazah (SKPI) merupakan pelengkap ijazah yang <br>
            menerangkan capaian pembelajaran pemegang ijazah selama masa studi
        </p>
        <i style="font-size:11px;color:grey">
            The Diploma Supplement accompanies a higher education certificate <br>
            providing learning outcomes achievement completed by its holder
        </i>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                1 of 2
            </div>
            <div class="col-sm-4">
                2 of 2
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                1 of 3
            </div>
            <div class="col-sm-4">
                2 of 3
            </div>
            <div class="col-sm-4">
                3 of 3
            </div>
        </div>
    </div>
    <br>
    <br>
    <div>
        <table class="table">
            <!-- Nomer 1 -->
            <tr>
                <td style="width: 10%;"></td>
                <td colspan="2">
                    <p style="font-size:12px">
                        <b> 1. IDENTITAS PEMEGANG SKPI </b>
                        <i style="font-size:11px;color:grey">/ Identity of Diploma Supplement Holders</i>
                    </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="font-size:12px">
                        NAMA LENGKAP
                        <i style="font-size:11px;color:grey">/ Full Name</i>
                    </p>
                </td>
                <td>
                    <p style="font-size:12px">
                        TANGGAL MASUK
                        <i style="font-size:11px;color:grey">/ Date of Entry</i>
                    </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p> ADIP SAFIUDIN </p=>
                </td>
                <td>
                    <p style="color:azure"> </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="font-size:12px">
                        NOMOR INDUK MAHASISWA
                        <i style="font-size:11px;color:grey">/ Registration Number</i>
                    </p>
                </td>
                <td>
                    <p style="font-size:12px">
                        TANGGAL LULUS
                        <i style="font-size:11px;color:grey">/ Date of Completion</i>
                    </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="color:azure"> NIM </p>
                </td>
                <td>
                    <p style="color:azure"> tgl lulus </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="font-size:12px">
                        TEMPAT DAN TANGGAL LAHIR
                        <i style="font-size:11px;color:grey"> / Place and Date of Birth</i>
                    </p>
                </td>
                <td>
                    <p style="font-size:12px">
                        GELAR
                        <i style="font-size:11px;color:grey">/ Tittle</i>
                    </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="color:azure"> ttl </p>
                </td>
                <td>
                    <p style="color:azure"> gelar </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>

            <!-- Nomer 2 -->
            <tr>
                <td style="width: 10%;"></td>
                <td colspan="2">
                    <p style="font-size:12px">
                        <b> 2. IDENTITAS PENYELENGGARA PROGRAM </b>
                        <i style="font-size:11px;color:grey">/ Identity of Awarding Institutions</i>
                    </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="font-size:12px">
                        PERGURUAN TINGGI
                        <i style="font-size:11px;color:grey">/ Awarding Institutions</i>
                    </p>
                </td>
                <td>
                    <p style="font-size:12px">
                        TOTAL SKS
                        <i style="font-size:11px;color:grey">/ Total of Credit Semester Unit</i>
                    </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="color:azure"> Universitas Sebelas Maret / Sebelas Maret University </p>
                </td>
                <td>
                    <p style="color:azure"> 144 sks / 144 credits</p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="font-size:12px">
                        PROGRAM STUDI
                        <i style="font-size:11px;color:grey">/ Department</i>
                    </p>
                </td>
                <td>
                    <p style="font-size:12px">
                        DURASI STUDI REGULER
                        <i style="font-size:11px;color:grey">/ Regular Duration of Study</i>
                    </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="color:azure"> Teknik Sipil / Civil Engineering </p>
                </td>
                <td>
                    <p style="color:azure"> 8 Semester / 8 Semester </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="font-size:12px">
                        FAKULTAS
                        <i style="font-size:11px;color:grey"> / Faculty</i>
                    </p>
                </td>
                <td>
                    <p style="font-size:12px">
                        SISTEM PENILAIAN
                        <i style="font-size:11px;color:grey">/ Grading System</i>
                    </p>
                </td>
                <td style="width: 10%;"></td>

            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="color:azure"> Teknik / Engineering </p>
                </td>
                <td>
                    <p style="color:azure"> A=4; A-=3.7; B+=3.3; B=3; C+=2.7; C=2; D=1; E=0 </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="font-size:12px">
                        JENIS DAN STRATA PENDIDIKAN <br>
                        <i style="font-size:11px;color:grey"> Type and Level of Educations</i>
                    </p>
                </td>
                <td>
                    <p style="font-size:12px">
                        PERSYARATAN PENERIMAAN <br>
                        <i style="font-size:11px;color:grey">Entry Requirements</i>
                    </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="color:azure"> Akademik & Sarjana (Strata 1) Academic & Bachelor Degree </p>
                </td>
                <td>
                    <p style="color:azure"> Lulus Pendidikan Menengah Atas/Sederajat Graduate from High School or Similar Education Level </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
        </table>
    </div>
    <!-- <img style="padding-top: 236px; padding-left:20px" src="<?= \Yii::$app->request->BaseUrl . '\images\footer.png' ?>"> -->
    <pagebreak />
    <table class="table">
        <!-- Nomer 3 -->
        <tr>
            <td style="width: 10%;"></td>
            <td colspan="2">
                <p style="font-size:12px">
                    <b> 3. INFORMASI MENGENAI KUALIFIKASI DAN HASIL CAPAIAN </b> <br>
                    <i style="font-size:11px;color:grey">3. INFORMATION OF QUALIFICATION AND ACHIEVEMENT</i>
                </p>
            </td>
            <td style="width: 10%;"></td>
        </tr>
        <tr>
            <td style="width: 10%;"></td>
            <td colspan="2">
                <p style="font-size:12px">
                    <b> 3.1 CAPAIAN PEMBELAJARAN LULUSAN </b> <br>
                    <i style="font-size:11px;color:grey">3.1 LEARNING OUTCOMES</i>
                </p>
            </td>
            <td style="width: 10%;"></td>
        </tr>
        <?php foreach ($data as $cpl) {
        ?>
            <tr>
                <td style="width: 10%;"></td>
                <td>
                    <p style="font-size:12px">
                        <?= $cpl->kode ?>
                    </p>
                </td>
                <td>
                    <p style="font-size:12px;">
                        <?= $cpl->isi ?>
                        <br>
                        <i style="font-size:11px;color:grey">/ In English</i>
                    </p>
                </td>
                <td style="width: 10%;"></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <pagebreak />
    <!-- <img src="<?= \Yii::$app->request->BaseUrl . '\images\header.png' ?>"> -->
    <table class="table">
        <!-- Nomer 3.2 -->
        <tr>
            <td style="width: 10%;"></td>
            <td colspan="2">
                <p style="font-size:12px">
                    <b> 3.2 HASIL CAPAIAN LULUSAN </b> <br>
                    <i style="font-size:11px;color:grey">3.2 GRADUATE ACHIEVEMENT RESULT</i>
                </p>
            </td>
            <td style="width: 10%;"></td>
        </tr>
    </table>
    <br>
    <div style="width:80% , center">
        <canvas id="radar"></canvas>
    </div>
    <div id="container" style="width: 50%, center">
        <canvas id="vertical-bar"></canvas>
    </div>
</body>