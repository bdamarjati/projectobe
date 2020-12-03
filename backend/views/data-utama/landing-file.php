<?php

use backend\models\FileUpload;
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
    $jk = Yii::$app->getRequest()->getQueryParam('jk');
    $data = FileUpload::findOne(['id_mata_kuliah_tayang' => $jk, 'jenis' => 'nilai']);
    $file = Yii::getAlias("@backend/uploads/file_nilai/{$data->file_name}.xlsx");
    echo \lesha724\documentviewer\ViewerJsDocumentViewer::widget([
        'url' => Yii::getAlias("@backend/uploads/file_nilai/write.xlsx"), //url на ваш документ или http://example.com/test.odt
        'width' => '100%',
        'height' => '100%',
    ]);
    ?>
    <?php ActiveForm::end(); ?>
</div>