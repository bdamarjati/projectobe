<?php

use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;


$form = ActiveForm::begin([
    'id' => 'login-form-vertical',
    'action' => Url::to(['data-utama/import'])
]);

?>
<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-body ">
                <div class="form-group">
                    <?php
                    echo $form->field($model, 'id_tahun_ajaran')->widget(Select2::classname(), [
                        'data' => $tahun_ajaran,
                        'options' => [
                            'id'    => 'id_tahun_ajaran',
                            'name'  => 'id_tahun_ajaran',
                            'placeholder' => '- Pilih -'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="form-group">
                    <?php
                    $data   = ['Ganjil', 'Genap'];
                    echo $form->field($model, 'semester')->widget(Select2::classname(), [
                        'data' => $data,
                        'options' => [
                            'id'    => 'id_ref_semester',
                            'name'  => 'id_ref_semester',
                            'placeholder' => '- Pilih -'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-body ">
                <div class="form-group">
                    <?php
                    // echo $form->field($model, 'id_ref_mata_kuliah')->widget(Select2::classname(), [
                    //     'data' => $mata_kuliah,
                    //     'options' => [
                    //         'name' => 'id_ref_mata_kuliah',
                    //         'placeholder' => '- Pilih -'
                    //     ],
                    //     'pluginOptions' => [
                    //         'allowClear' => true
                    //     ],
                    // ]);




                    echo $form->field($model, 'id_ref_mata_kuliah')->widget(DepDrop::classname(), [
                        'options' => [
                            'id' => 'id_ref_mata_kuliah',
                            'name' => 'id_ref_mata_kuliah'
                        ],
                        'pluginOptions' => [
                            'depends' => ['id_tahun_ajaran'],
                            'placeholder' => '- Pilih -',
                            'url' => Url::to(['/data-utama/dep-drop-mata-kuliah'])
                        ]
                    ]);
                    ?>
                </div>
                <div class="form-group">
                    <?php
                    echo $form->field($model, 'id_ref_kelas')->widget(Select2::classname(), [
                        'data' => $kelas,
                        'options' => [
                            'name' => 'id_ref_kelas',
                            'placeholder' => '- Pilih -'
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>
                <div class="form-group">
                    <div class="col-lg-12 ml-auto">

                        <?= Html::submitButton('Next', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>