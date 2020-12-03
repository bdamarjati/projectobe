<?php

use backend\models\RefCpl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

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
// echo "<pre>";print_r($_data);exit;
$js = <<< JS

		var color = Chart.helpers.color;
		var radarData = {
			labels: $_label,
			datasets: [{
				label: 'Capaian Lulusan Mahasiswa',
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
				label: 'Capaian Lulusan Mahasiswa',
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
						text: '$tahun $semester'
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
						text: '$tahun $semester'
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
    /* border: none; */
}
CSS;
$this->registerCss($css);
$this->title = 'Capaian Pembelajaran Lulusan Per Semester';
?>
<div class="row">
	<?php
	// echo '<pre>';
	// print_r($angkatan);
	// exit;

	?>

	<div class="col-md-12">
		<div class="box box-default">
			<div class="box-body ">
				<div class="form-group">
					<div>
						<?php echo Html::a('<i></i> - Pilih -', ['landing-semester'], [
							'class' => 'btn btn-success btn-flat',
							'role' => 'modal-remote',
						]) ?>
					</div>
				</div>
				<div class="form-group">
					<label id="nim" class="col-sm-2 control-label">Tahun Ajaran</label>
					<div class="col-sm-10">
						<input value="<?php echo $tahun ?>" class="form-control" readonly>
					</div>
				</div>
				<div class="form-group">
					<label id="nim" class="col-sm-2 control-label">Semester</label>
					<div class="col-sm-10">
						<input value="<?php echo $semester ?>" class="form-control" readonly>
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
		<div id="menu1" class="tab-pane fade">
			<div id="container" style="width: 50%, center">
				<canvas id="vertical-bar"></canvas>
			</div>
		</div>
	</div>
</div>