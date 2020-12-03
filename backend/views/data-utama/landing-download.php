<?php


use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\select2\Select2;

?>

<div style="margin: 0 12px 20px;">
    <?php $form = ActiveForm::begin([
        // 'action'    => Url::to(['download', 'dl' => 1])
    ]); ?>
    <?php
    echo $form->field($model, 'id_tahun_ajaran')->widget(Select2::classname(), [
        'data' => $tahun_ajaran,
        'options' => [
            // 'id'    => 'id_tahun_ajaran',
            // 'name'  => 'id_tahun_ajaran',
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    $data   = ['Ganjil'=>'Ganjil', 'Genap'=>'Genap'];
    echo $form->field($model, 'semester')->widget(Select2::classname(), [
        'data' => $data,
        'options' => [
            // 'id'    => 'id_ref_semester',
            // 'name'  => 'id_ref_semester',
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    echo $form->field($model, 'id_ref_mata_kuliah')->widget(Select2::classname(), [
        'data' => $mata_kuliah,
        'options' => [
            // 'name' => 'id_ref_mata_kuliah',
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    echo $form->field($model, 'id_ref_kelas')->widget(Select2::classname(), [
        'data' => $kelas,
        'options' => [
            // 'name' => 'id_ref_kelas',
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    ?>
    <?php ActiveForm::end(); ?>
</div>