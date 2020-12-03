<?php

use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Cetak SKPI';
$this->params['breadcrumbs'][] = $this->title;

$js = <<< JS

JS;
$this->registerJs($js);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Laporan Surat Keterangan Pendamping Ijazah</h1>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <label class="col-sm-2 control-label">Nama</label>
            <div class="input-group col-sm-10">
                <input value="<?= $data->nama ?>" name="nama" class="form-control" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">NIM</label>
            <div class="input-group col-sm-10">
                <input value="<?= $data->nim ?>" name="nim" class="form-control" readonly>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Tempat dan Tanggal Lahir</label>
            <div class="input-group col-sm-10">
                <input name="ttl" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Tanggal Masuk</label>
            <div class="input-group col-sm-10 ">
                <?= DatePicker::widget([
                    'name' => 'tgl_masuk',
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd MM yyyy'
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Tanggal Lulus</label>
            <div class="input-group col-sm-10">
                <?= DatePicker::widget([
                    'name' => 'tgl_lulus',
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd MM yyyy'
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Total SKS</label>
            <div class="input-group col-sm-10">
                <input type="number" name="total_sks" class="form-control" min="1" max="250">
                <span class="input-group-addon">SKS</span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-10">
                <?= Html::button(
                    'Back',
                    array(
                        'name' => 'btnBack',
                        'class' => 'btn btn-danger',
                        'onclick' => "history.go(-1)",
                    )
                ); ?>
                <?= Html::submitButton('Print', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>