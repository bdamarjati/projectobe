<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CapaianMahasiswa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="capaian-mahasiswa-form">
    <div class="row">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-md-12" style="padding-left:20%;padding-right:20%">
            <div class="form-group row">
                <label name="nama" class="col-sm-2 control-label">Nama</label>
                <div class="col-sm-10">
                    <input value="<?php echo $model['mahasiswa']->nama ?>" class="form-control" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label name="nim" class="col-sm-2 control-label">NIM</label>
                <div class="col-sm-10">
                    <input value="<?php echo $model['mahasiswa']->nim ?>" class="form-control" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label name="angkatan" class="col-sm-2 control-label">Angkatan</label>
                <div class="col-sm-10">
                    <input value="<?php echo $model['mahasiswa']->angkatan ?>" class="form-control" readonly>
                </div>
            </div>
            <?php
            foreach ($model['capaian'] as $key => $model) {
                // echo '<pre>';
                // print_r($form);
                // exit;
            ?>
                <div class="form-group row">
                    <label class="col-sm-2 control-label">CPMK <?= $key + 1 ?></label>
                    <div class="col-sm-10">
                        <input value="<?php echo $model['nilai'] ?>" name="cpmk<?= $key + 1 ?>" class="form-control">
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="form-group">
                <?= Html::button(
                    'Back',
                    array(
                        'name' => 'btnBack',
                        'class' => 'btn btn-danger',
                        'onclick' => "history.go(-1)",
                    )
                ); ?>
                <?= Html::button(
                    'Save',
                    array(
                        'name'  => 'btnSubmit',
                        'class' => 'btn btn-success',
                        'type'  => 'submit'
                    )
                ); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>