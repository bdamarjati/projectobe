<?php

/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */

use backend\models\MataKuliahTayang;
use backend\models\RefCpmk;
use backend\models\RefMahasiswa;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

$this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js", [
    'depends' => ["yii\web\JqueryAsset"]
]);
$this->registerJsFile("@web/js/utils.js", [
    'depends' => ["yii\web\JqueryAsset"]
]);

$this->title = 'Evaluasi Mata Kuliah';
$this->params['breadcrumbs'][] = $this->title;
$jk        = Yii::$app->getRequest()->getQueryParam('jk');

$i = 1;
foreach ($data['cpmk'] as $key => $value) {
    $_label[] = "CPMK " . $i;
    $i++;
}
$nama_mk = $data['mata_kuliah']->nama;
$_data = Json::encode(array_values($average_nilai));
$_label = Json::encode(array_values($_label));

$sangat_baik = Json::encode(array_values($keterangan["sangat_baik"]));
$baik = Json::encode(array_values($keterangan["baik"]));
$cukup = Json::encode(array_values($keterangan["cukup"]));
$kurang = Json::encode(array_values($keterangan["kurang"]));

$css = <<< CSS
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background: none;
    outline: none;
    border: none;
}
canvas {
			-moz-user-select: none;
			-webkit-user-select: none;
			-ms-user-select: none;
		}
CSS;
$this->registerCss($css);

// echo "<pre>";
// print_r($keterangan);
// exit;

$js = <<< JS
        var color = Chart.helpers.color;
		var barChartData = {
			labels: $_label,
			datasets: [{
				label: 'Rata-rata CPMK',
				backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
				borderColor: window.chartColors.red,
				borderWidth: 1,
				data: $_data,
				fill: true
			}]
        };
        var barStackChartData = {
			labels: $_label,
			datasets: [
                {
                    label: 'Sangat Baik',
                    backgroundColor: "#caf270",
                    borderWidth: 1,
                    data: $sangat_baik,
                    fill: true
                },{
                    label: 'Baik',
                    backgroundColor: "#45c490",
                    borderWidth: 1,
                    data: $baik,
                    fill: true
                },{
                    label: 'Cukup',
                    backgroundColor: "#008d93",
                    borderWidth: 1,
                    data: $cukup,
                    fill: true
                },{
                    label: 'Kurang',
                    backgroundColor: "#2e5468",
                    borderWidth: 1,
                    data: $kurang,
                    fill: true
                },
            ]
		};

		window.onload = function() {
			console.log(barChartData);
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
						text: '$nama_mk'
					},
					tooltips: {
						enabled: true,
						displayColors: false,
						title: false,
						callbacks: {
							title: function(tooltipItem) {
								return tooltipItem.yLabel;
							},
							label: function(tooltipItem, data) {
								var label = data.labels[tooltipItem.index];
								var val = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
								return val;
							}
						}
					}
				}
			});

            var stack = document.getElementById('bar-stacked').getContext('2d');
			window.myBarStacked = new Chart(stack, {
				type: 'bar',
				data: barStackChartData,
				options: {
					responsive: true,
					legend: {
						position: 'top',
                        display: true
					},
					scales: {
						xAxes:[{
                            stacked: true
                        }],
                        yAxes:[{
                            stacked: true
                        }],
					},
					title: {
						display: true,
						text: '$nama_mk'
					},
				}
			});
		};
JS;
$this->registerJs($js);
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div div class="col-md-6">
                <?php echo Html::a('<i class="fa fa-arrow-left"></i> Kembali', ['/capaian-mahasiswa/nilai-upload', 'jk' => $jk], ['class' => 'btn-social btn btn-']) ?>
            </div>
            <!-- <div class="col-md-6" align="right">
                <?php
                echo Html::a('<i class="fa fa-eye"></i> Download File Upload', ['/data-utama/file-upload', 'jk' => $jk], [
                    'class' => 'btn btn-success btn-flat',
                ]);
                echo Html::a('Evaluasi MK', ['evaluasi-mata-kuliah', 'jk' => $jk], [
                    'class' => 'btn btn-primary btn-flat',
                ]);
                ?>
            </div> -->
        </div>
        <hr>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">UNIVERSITAS SEBELAS MARET</h4>
                <div class="form-group ">
                    <label id="fakultas" class="col-sm-2 control-label">Fakultas</label>
                    <div class="col-sm-10">
                        <input value="TEKNIK" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="program_studi" class="col-sm-2 control-label">Program Studi</label>

                    <div class="col-sm-10">
                        <input value="Teknik Elektro" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="tahun_ajaran" class="col-sm-2 control-label">Tahun Ajaran</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $data['tahun_ajaran']->tahun ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="semester" class="col-sm-2 control-label">Semester</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $data['tayang']->semester ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="mata_kuliah" class="col-sm-2 control-label">Mata Kuliah</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $data['mata_kuliah']->nama ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="kelas" class="col-sm-2 control-label">Kelas</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $data['kelas']->kelas ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="dosen" class="col-sm-2 control-label">Pengampu</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $data['dosen']->nama_dosen ?>" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <br>
        <div class="box box-solid">

            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#home">Rata-rata</a></li>
                <li><a data-toggle="tab" href="#menu1">Evaluasi</a></li>
            </ul>
            <div class="tab-content">
                <div id="home" class="tab-pane fade in active">
                    <div style="width:50% , center">
                        <canvas id="vertical-bar"></canvas>
                    </div>
                </div>
                <div id="menu1" class="tab-pane fade in active">
                    <div id="container" style="width: 50%, center">
                        <canvas id="bar-stacked"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>