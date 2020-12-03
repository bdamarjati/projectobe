<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RefMahasiswa */

$this->title = 'Tambah Referensi Mahasiswa';
$this->params['breadcrumbs'][] = ['label' => 'Referensi Mahasiswa', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data Mahasiswa</h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>