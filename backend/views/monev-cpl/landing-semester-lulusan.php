<?php


use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

$url = \yii\helpers\Url::to(['semester-list','status'=>8]);

?>

<div style="margin: 0 12px 20px;">
    <?php $form = ActiveForm::begin([
    ]); ?>
    <?php
    echo $form->field($model, 'tahun')->widget(Select2::classname(), [
        'options' => ['multiple' => false, 'placeholder' => 'Ketik tahun ...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ]
        ],
    ]);

    echo $form->field($model, 'semester')->widget(Select2::classname(), [
        'data' => ['Ganjil' => 'Ganjil', 'Genap' => 'Genap'],
        'options' => [
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);

    ?>
    <?php ActiveForm::end(); ?>
</div>