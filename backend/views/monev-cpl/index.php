<?php

use backend\models\RefCpl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$this->registerJsFile("https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js", [
	'depends' => ["yii\web\JqueryAsset"]
]);
$this->registerJsFile("@web/js/utils.js", [
	'depends' => ["yii\web\JqueryAsset"]
]);

$_data = Json::encode(array_values($data));
$_label = RefCpl::find()
	->orderBy(['id' => SORT_ASC])
	->where(['status' => 1])
	->all();
$_label = ArrayHelper::getColumn($_label, 'kode');
$_label = Json::encode(array_values($_label));

$url = Url::to(['/report/chart']);
// echo "<pre>";print_r($_data);exit;
$js = <<< JS

		$('#download').click(function(){
			/*Get image of canvas element*/
			var radar = document.getElementById("radar").toDataURL("image/png");
			var bar   = document.getElementById("vertical-bar").toDataURL("image/png");
			$.ajax({
					url: "{$url}",
					type: 'POST',
					data: {radar: radar, bar: bar, id_mahasiswa:$id_mahasiswa}
				});
		});

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
<?php
if ($mahasiswa->status == 1) {
	$status = 'Aktif';
} else if ($mahasiswa->status == 9) {
	$status = 'DO';
} else if ($mahasiswa->status == 8) {
	$status = 'Lulus';
} else if ($mahasiswa->status == 7) {
	$status = 'Undur Diri';
} else if ($mahasiswa->status == 6) {
	$status = 'Hilang';
} else if ($mahasiswa->status == 5) {
	$status = 'Meninggal Dunia';
} else {
	$status = 'Tidak Ditemukan';
}
$this->title = 'Capaian Pembelajaran Lulusan Alumni Per Individu';
?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-body ">
				<div class="form-group">
					<div class="row">
						<div class="col-sm-6">
							<?php
							echo Html::a('<i></i> Individu', ['landing-individual-lulusan'], [
								'class' => 'btn btn-primary btn-flat',
								'role' => 'modal-remote',
							]);
							?>
							<?php
							echo Html::a('<i></i> Angkatan', ['landing-angkatan-lulusan'], [
								'class' => 'btn btn-primary btn-flat',
								'role' => 'modal-remote',
							]);
							?>
							<?php
							echo Html::a('<i></i> Semester', ['landing-semester-lulusan'], [
								'class' => 'btn btn-primary btn-flat',
								'role' => 'modal-remote',
							]);
							?>
						</div>
						<div class="col-sm-6">
							<p align="right">
								<?= Html::a('Transkip Nilai', ['/capaian-mahasiswa/download-transkip/', 'jk' => $id_mahasiswa], ['class' => 'btn btn-success']) ?>
								<a id="download" download="ChartImage.jpg" class="btn btn-primary float-right bg-flat-color-1" title="Download">
									<i class="fa fa-download"></i>
								</a>
							</p>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label id="nim" class="col-sm-2 control-label">Nim</label>
					<div class="col-sm-10">
						<input value="<?php echo $mahasiswa->nim ?>" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label id="nim" class="col-sm-2 control-label">Nama</label>
					<div class="col-sm-10">
						<input value="<?php echo $mahasiswa->nama ?>" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label id="nim" class="col-sm-2 control-label">Angkatan</label>
					<div class="col-sm-10">
						<input value="<?php echo $mahasiswa->angkatan ?>" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label id="nim" class="col-sm-2 control-label">Status</label>
					<div class="col-sm-10">
						<input value="<?php echo $status ?>" class="form-control" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="box box-solid">

	<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#home">Grafik Radar</a></li>
		<li><a data-toggle="tab" href="#menu1">Grafik Bar</a></li>
	</ul>

	<div class="tab-content">
		<div id="home" class="tab-pane fade in active">
			<div style="width:80% , center">
				<canvas id="radar"></canvas>
			</div>
		</div>
		<div id="menu1" class="tab-pane fade in active">
			<div id="container" style="width: 50%, center">
				<canvas id="vertical-bar"></canvas>
			</div>
		</div>
	</div>
</div>