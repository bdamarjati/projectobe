<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CapaianMahasiswa */

$this->title = 'Update Nilai Mahasiswa';
$this->params['breadcrumbs'][] = ['label' => 'Capaian Mahasiswas', 'url' => ['nilai-upload']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data Nilai</h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>