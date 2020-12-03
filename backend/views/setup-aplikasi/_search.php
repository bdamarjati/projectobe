<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\searchs\SetupAplikasi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setup-aplikasi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'univ_id') ?>

    <?= $form->field($model, 'univ_en') ?>

    <?= $form->field($model, 'fakultas_id') ?>

    <?= $form->field($model, 'fakultas_en') ?>

    <?php // echo $form->field($model, 'prodi_id') ?>

    <?php // echo $form->field($model, 'prodi_en') ?>

    <?php // echo $form->field($model, 'nama_dekan') ?>

    <?php // echo $form->field($model, 'nip_dekan') ?>

    <?php // echo $form->field($model, 'nama_kaprodi') ?>

    <?php // echo $form->field($model, 'nip_kaprodi') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
