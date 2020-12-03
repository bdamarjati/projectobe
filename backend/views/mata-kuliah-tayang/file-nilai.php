<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;
use kartik\export\ExportMenu;
use kartik\widgets\SwitchInput;



/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\RefKelas */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Import';
$this->params['breadcrumbs'][] = $this->title;
// $id_tahun_ajaran = Yii::$app->request->post('id_tahun_ajaran');
// $id_ref_semester = Yii::$app->request->post('id_ref_semester');
// $id_ref_mata_kuliah = Yii::$app->request->post('id_ref_mata_kuliah');
// $id_ref_kelas = Yii::$app->request->post('id_ref_kelas');
$url = Url::to(['', 'update' => $update]);
$urlOn = Url::to(['', 'update' => 1, $update]);
$urlOf = Url::to(['', 'update' => 0, $update]);


?>



<div class="import-nilai-index panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><strong> Kartu Rencana Studi</strong></h1>

    </div>

    <div class="panel-body">
        <p>
            <?php echo Html::a('<i class="fa fa-download"></i> Template Excel', ['landing-download'], [
                'class' => 'btn btn-success btn-flat',
                'role' => 'modal-remote',
            ]) ?>
        </p>
        <span style="font-size: 12px;">Jika Data Sudah Ada :</span>
        <?php echo SwitchInput::widget([
            'name'          => 'update',
            'value'            => $update,
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

        <?php ActiveForm::end() ?>
    </div>
</div>