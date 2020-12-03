<?php


use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

$url = \yii\helpers\Url::to(['angkatan-list','status'=>1]);
?>

<div style="margin: 0 12px 20px;">
    <?php $form = ActiveForm::begin([
        // 'action'    => Url::to(['download', 'dl' => 1])
    ]); ?>
    <?php

    echo $form->field($model, 'angkatan')->widget(Select2::classname(), [
        'options' => ['multiple'=>false, 'placeholder' => 'Ketik tahun angkatan ...'],
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

    ?>
    <?php ActiveForm::end(); ?>
</div>