<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\RefMahasiswa */

$this->title = $model->nim;
$this->params['breadcrumbs'][] = ['label' => 'Mahasiswa', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data <?php echo $model->nama ?></h1>
    </div>
    <div class="panel-body">
        <?php
        if (Yii::$app->assign->is(["administrator"])) {
        ?>
            <p align="right">
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        <?php
        }
        ?>
        <!-- '1' => 'Aktif',
            '9' => 'DO',
            '8' => 'Lulus',
            '7' => 'Undur Diri',
            '6' => 'Hilang',
            '5' => 'Meninggal Dunia', -->
        <?php
        if ($model->status == 1) {
            $status = 'Aktif';
        } elseif ($model->status == 9) {
            $status = 'DO';
        } elseif ($model->status == 8) {
            $status = 'Lulus';
        } elseif ($model->status == 7) {
            $status = 'Undur Diri';
        } elseif ($model->status == 6) {
            $status = 'Hilang';
        } elseif ($model->status == 5) {
            $status = 'Meninggal Dunia';
        }
        ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'nim',
                'nama',
                'angkatan',
                [                                                  // the owner name of the model
                    'label' => 'Status',
                    'value' => $status,
                ],
            ],
        ]) ?>
        <?= Html::button(
            'Back',
            array(
                'name' => 'btnBack',
                'class' => 'btn btn-danger',
                'onclick' => "history.go(-1)",
            )
        ); ?>
    </div>
</div>