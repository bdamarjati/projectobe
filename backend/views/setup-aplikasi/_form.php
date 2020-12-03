<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SetupAplikasi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setup-aplikasi-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'univ_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'univ_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fakultas_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fakultas_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prodi_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prodi_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama_dekan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nip_dekan')->textInput() ?>

    <?= $form->field($model, 'nama_kaprodi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nip_kaprodi')->textInput() ?>

    <!-- <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div> -->

    <?php ActiveForm::end(); ?>

</div>
