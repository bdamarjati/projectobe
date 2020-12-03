<?php

use backend\models\FileUpload;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\MataKuliahTayang */

$this->title = 'View : ' . $model['refMataKuliah']->nama;
$this->params['breadcrumbs'][] = ['label' => 'Mata Kuliah Tayang', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View';
\yii\web\YiiAsset::register($this);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data</h1>
    </div>
    <div class="panel-body">
        <?php
        if (Yii::$app->assign->is(["administrator"])) {
        ?>
            <p align="right">
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?php
                if (!FileUpload::findOne(['id_mata_kuliah_tayang' => $model->id, 'jenis' => 'nilai'])) {
                ?>
                    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                <?php
                }
                ?>
            </p>
        <?php
        }
        ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [                                                  // the owner name of the model
                    'label' => 'Tahun Ajaran',
                    'value' => $model['tahunAjaran']->tahun,
                ],
                'semester',
                [                                                  // the owner name of the model
                    'label' => 'Mata Kuliah',
                    'value' => $model['refMataKuliah']->nama,
                ],
                [                                                  // the owner name of the model
                    'label' => 'Kelas',
                    'value' => $model['refKelas']->kelas,
                ],
                [                                                  // the owner name of the model
                    'label' => 'Dosen Pengajar',
                    'value' => $model['refDosen']->nama_dosen,
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