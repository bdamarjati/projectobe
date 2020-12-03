<?php

use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

$url = \yii\helpers\Url::to(['mahasiswa-list', 'status' => 1]);
?>

<div style="margin: 0 12px 20px;">
    <?php $form = ActiveForm::begin([
        // 'action'    => Url::to(['download', 'dl' => 1])
    ]); ?>
    <?php

    echo $form->field($model, 'id_ref_mahasiswa')->widget(Select2::classname(), [
        'options' => ['multiple' => false, 'placeholder' => 'Ketik nama ...'],
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