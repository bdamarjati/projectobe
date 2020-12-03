<?php

use backend\models\RefCpl;
use backend\models\RefMataKuliah;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\RefCpmk */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ref-cpmk-form">

    <?php $form = ActiveForm::begin(); ?>

    <!-- <?= $form->field($model, 'id_ref_mata_kuliah')->textInput() ?> -->
    <?php
    $data = ArrayHelper::map(RefMataKuliah::find()->where(['status'=>1])->all(), 'id', 'nama');

    echo $form->field($model, 'id_ref_mata_kuliah')->widget(Select2::classname(), [
        'data' => $data,
        'options' => [
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);
    ?>

    <?= $form->field($model, 'kode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isi')->textarea(['rows' => 6]) ?>

    <?php
    // $data = ArrayHelper::map(RefCpl::find()->all(), 'id', 'isi');

    // echo $form->field($modelcpl, 'id')->widget(Select2::classname(), [
    //     'data' => $data,
    //     'options' => [
    //         'placeholder' => '- Pilih -'
    //     ],
    //     'pluginOptions' => [
    //         'allowClear' => true
    //     ],
    // ]);
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
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>