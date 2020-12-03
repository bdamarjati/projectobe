<?php

use backend\models\FileUpload;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\RefKelas */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerJsFile("https://cdn.jsdelivr.net/npm/sweetalert2@9", [
    'depends' => ["yii\web\JqueryAsset"]
]);


$this->title = 'Import Nilai';
$this->params['breadcrumbs'][] = $this->title;


$jk = Yii::$app->getRequest()->getQueryParam('jk');
$url = Url::to(['', 'update' => $update, 'jk' => $jk]);
$urlOn = Url::to(['', 'update' => 1, $update, 'jk' => $jk]);
$urlOf = Url::to(['', 'update' => 0, $update, 'jk' => $jk]);

?>

<div class="import-nilai-index panel panel-default">
    <div class="panel-heading">
        <h1><?= Html::encode($this->title) ?></h1>
        <p>
            <?php echo Html::a('<i class="fa fa-download"></i> Template Excel', ['download-template', 'jk' => $jk], [
                'class' => 'btn btn-success btn-flat',
            ]) ?>
            <?php
            if (FileUpload::findOne(['id_mata_kuliah_tayang' => $jk, 'jenis' => 'nilai'])) {
                echo Html::a('<i class="fa fa-eye"></i> Lihat Nilai', ['/capaian-mahasiswa/nilai-upload', 'jk' => $jk], [
                    'class' => 'btn btn-primary btn-flat',
                ]);
            }
            ?>
        </p>
    </div>

    <div class="panel-body">
        <span style="font-size: 12px;">Jika Data Sudah Ada :</span>
        <?php echo SwitchInput::widget([
            'name'          => 'update',
            'value'         => $update,
            'pluginOptions' => [
                'size'     => 'small',
                'onText'   => 'Perbarui',
                'offText'  => 'Lewati',
                'onColor'  => 'danger',
                'offColor' => 'primary',
            ],
            'pluginEvents' => [
                'switchChange.bootstrapSwitch' => "function(e, s) {
					if (s) {
                        $('#form-main').attr('action', '$urlOn');
                        Swal.fire({
                            title: 'Info !',
                            text: 'Jika ada mahasiswa yang mengulang maka nilai yang diambil adalah yang TERBAIK',
                            icon: 'info',
                            confirmButtonText: 'OK'
                        });
					} else {
                        $('#form-main').attr('action', '$urlOf');
					}
				}",
            ],
        ]); ?>

        <?php $form = ActiveForm::begin([
            'id'      => 'form-main',
            'action'  => $url,
            'options' => [
                'enctype' => 'multipart/form-data',
            ]
        ]) ?>
        <?php echo $form->field($model, 'file')->widget(FileInput::classname(), [
            'pluginOptions' => [
                'showRemove' => true,
                'uploadLabel' => 'Import',
            ],
            'options' => [
                'accept' => '.xlsx',
            ]
        ]) ?>
        <?= Html::button(
            'Back',
            array(
                'name' => 'btnBack',
                'class' => 'btn btn-danger',
                'onclick' => "history.go(-1)",
            )
        ); ?>
        <?php ActiveForm::end() ?>
    </div>
</div>