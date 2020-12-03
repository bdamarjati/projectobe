<?php

use backend\models\RefCpl;
use backend\models\RefCpmk;
use backend\models\RefMataKuliah as ModelsRefMataKuliah;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\RelasiCpmkCpl */
/* @var $form yii\widgets\ActiveForm */

$js = <<< JS

JS;
$this->registerJs($js);
?>

<div class="relasi-cpmk-cpl-form">
    <?php $form = ActiveForm::begin(); ?>


    <!-- <?= $form->field($model, 'id_ref_cpmk')->textInput() ?> -->
    <?php
    $datas = RefCpmk::find()
        ->joinWith(['refMataKuliah'])
        ->asArray()
        ->all();

    $mk = ModelsRefMataKuliah::findAll(['status' => 1]);
    $mk = ArrayHelper::map($mk, 'id', 'nama');

    echo $form->field($model1, 'id_ref_mata_kuliah')->dropDownList($mk, ['id' => 'nama-id']);

    echo $form->field($model, 'id_ref_cpmk')->widget(DepDrop::classname(), [
        'options' => [
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'depends' => ['nama-id'],
            'url' => Url::to(['/relasi-cpmk-cpl/cpmk']),
            'allowClear' => true

        ],
    ]);

    $datas = RefCpl::find()
        ->where(['status' => 1])
        ->all();
    $cpl = ArrayHelper::map($datas, 'id', 'isi', 'kode');

    echo $form->field($model, 'id_ref_cpl')->widget(Select2::classname(), [
        'data' => $cpl,
        'options' => [
            'placeholder' => '- Pilih -'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'pluginEvents' => [
            "change" => 'function() { 
                var id_ref_cpl = $(this).val();
                $.ajax({
					url: "total-bobot",
					type: "POST",
					data: {id_ref_cpl: id_ref_cpl}
				}).done(function( data ) {
                    var data = data["data"]*100/100;
                    var total = data.toFixed(4);
                    var persen = total*100;
                    document.getElementById("persen_bobot").innerHTML = persen + "%";
                    document.getElementById("total_bobot").innerHTML = total ;
                });
            }',
        ],
    ]);
    ?>

    <div class="form-group">
        <label for="">Total Bobot </label>
        <div class="btn btn-primary">
            <span class="badge badge-light" id="total_bobot"></span>
        </div>
        <span class="badge badge-light">or</span>
        <div class="btn btn-primary">
            <span class="badge badge-light" id="persen_bobot"></span>
        </div>
    </div>

    <?= $form->field($model, 'bobot')->textInput([
        'type' => 'number',
        'min' => 0,
        'max' => 1,
        'step' => 0.01
    ]) ?>

    <!-- <?= $form->field($model, 'id_ref_cpl')->textInput() ?> -->

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