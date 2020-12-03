<?php

use backend\models\RefDosen;
use backend\models\RefKelas;
use backend\models\RefMataKuliah;
use backend\models\RefTahunAjaran;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\MataKuliahTayang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mata-kuliah-tayang-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'id_tahun_ajaran')->textInput() ?> -->
    <?php
    $tahun = ArrayHelper::map(RefTahunAjaran::find()->where(['status'=>1])->all(), 'id', 'tahun');
    echo $form->field($model, 'id_tahun_ajaran')->widget(Select2::classname(), [
        'data' => $tahun,
        'options' => [
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    $semester   = ['Ganjil' => 'Ganjil', 'Genap' => 'Genap'];
    echo $form->field($model, 'semester')->widget(Select2::classname(), [
        'data' => $semester,
        'options' => [
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    $mata_kuliah = ArrayHelper::map(RefMataKuliah::find()->where(['status'=>1])->all(), 'id', 'nama');
    echo $form->field($model, 'id_ref_mata_kuliah')->widget(Select2::classname(), [
        'data' => $mata_kuliah,
        'options' => [
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    $kelas = ArrayHelper::map(RefKelas::find()->where(['status'=>1])->all(), 'id', 'kelas');
    echo $form->field($model, 'id_ref_kelas')->widget(Select2::classname(), [
        'data' => $kelas,
        'options' => [
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    $dosen = ArrayHelper::map(RefDosen::find()->where(['status'=>1])->all(), 'id', 'nama_dosen');
    echo $form->field($model, 'id_ref_dosen')->widget(Select2::classname(), [
        'data' => $dosen,
        'options' => [
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>


    <!-- <?= $form->field($model, 'semester')->textInput(['maxlength' => true]) ?> -->

    <!-- <?= $form->field($model, 'id_ref_mata_kuliah')->textInput() ?> -->

    <!-- <?= $form->field($model, 'id_ref_kelas')->textInput() ?> -->

    <!-- <?= $form->field($model, 'id_ref_dosen')->textInput() ?> -->

    <div class="form-group">
        <?= Html::button(
            'Back',
            array(
                'name' => 'btnBack',
                'class' => 'btn btn-danger',
                'onclick' => "history.go(-1)",
            )
        ); ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>