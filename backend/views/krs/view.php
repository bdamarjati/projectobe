<?php

/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Proses Import KRS';
$this->params['breadcrumbs'][] = $this->title;

$jk        = Yii::$app->getRequest()->getQueryParam('jk');

$count     = count($model);
$min       = min(array_keys((array) $model));
$max       = max(array_keys((array) $model));
$modelJson = Json::encode($model);

$url = Url::toRoute(['proses-ajax', 'update' => $update]);
$js = <<< JS
var model = $modelJson,
	max     = $max,
	min     = $min,
	count   = $count,
	proses  = 0,
	success = 0,
	warning = 0,
	error   = 0;

generate(model, max, min);

function generate(model, max, key)
{
	proses++;

	tr = $("#tr" + key);
	tr.addClass('info');
	tr[0].childNodes[0].innerHTML = '<span class="label label-primary">Dalam Proses</span>';

	$.post( "$url", {
        data            : model[key],
        encrypt         : "$encrypt"
	}).done(function( data ) {
		
		tr.removeClass('info');
		tr.addClass(data['data']['class']);
		tr.html(data['data']['html']);

        if (data['data']['class'] === 'danger') {
			error++;
        } else if (data['data']['class'] === 'success') {
			success++;
        } else if (data['data']['class'] === 'warning') {
			warning++;
        }

		$("#proses").html(proses + ' / ' + count + ' <small>Data</small>');
		$("#error").html(error + ' <small>Data</small>');
		$("#warning").html(warning + ' <small>Data</small>');
		$("#success").html(success + ' <small>Data</small>');

		$("#status-" + key).html(alert);

        if (max >= (key + 1)) {
            generate(model, max, key + 1);
        }
	});
}

JS;
$this->registerJs($js);

$css = <<< CSS
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background: none;
    outline: none;
    border: none;
}
CSS;
$this->registerCss($css);
?>

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-cogs"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Terproses</span>
                <span id="proses" class="info-box-number">0 <smal>Data</smal></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-ban"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Error</span>
                <span id="error" class="info-box-number">0 <smal>Data</smal></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-warning"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Peringatan</span>
                <span id="warning" class="info-box-number">0 <smal>Data</smal></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Sukses</span>
                <span id="success" class="info-box-number">0 <smal>Data</smal></span>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-body">
        <?php echo Html::a('<i class="fa fa-arrow-left"></i> Kembali ke Halaman Unggah File', ['/krs', 'jk' => $jk], ['class' => 'btn-social btn btn-']) ?>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <input value="<?php echo $encrypt ?>" hidden>

                <h4 class="text-center">UNIVERSITAS SEBELAS MARET</h4>
                <div class="form-group ">
                    <label id="fakultas" class="col-sm-2 control-label">Fakultas</label>
                    <div class="col-sm-10">
                        <input value="<?php echo $fakultas ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="program_studi" class="col-sm-2 control-label">Program Studi</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $program_studi ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="tahun_ajaran" class="col-sm-2 control-label">Tahun Ajaran</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $tahun_ajaran ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="semester" class="col-sm-2 control-label">Semester</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $semester ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="mata_kuliah" class="col-sm-2 control-label">Mata Kuliah</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $mata_kuliah ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="kelas" class="col-sm-2 control-label">Kelas</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $kelas ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="dosen" class="col-sm-2 control-label">Pengampu</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $dosen ?>" class="form-control" readonly>
                    </div>
                </div>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th colspan="1"></th>
                            <th class="text-center" colspan="2">DATA MAHASISWA</th>
                            <!-- <th class="text-center" colspan="5">DATA NILAI</th> -->
                        </tr>
                        <tr>
                            <th class="text-center">STATUS</th>
                            <!-- <th class="text-center">#</th> -->
                            <!-- <th class="text-center">KEY</th> -->

                            <th class="text-center">NIM</th>
                            <th class="text-center">NAMA</th>

                            <!-- <th class="text-center">CPMK1</th>
                            <th class="text-center">CPMK2</th>
                            <th class="text-center">CPMK3</th>
                            <th class="text-center">CPMK4</th>
                            <th class="text-center">CPMK5</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($model as $key => $data) {
                            // unset($data[3]);
                            echo "<tr id='tr{$key}'>";
                            // echo "<td></td>";
                            foreach ($data as $k => $value) {
                                // echo '<pre>';
                                // print_r($data[1]);
                                // exit;

                                echo $k == 1 ? "<td class='text-monospace'>{$value}</td>" : "<td>{$value}</td>";
                            }
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>