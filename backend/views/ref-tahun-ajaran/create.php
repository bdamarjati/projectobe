<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RefTahunAjaran */

$this->title = 'Tambah Referensi Tahun Ajaran';
$this->params['breadcrumbs'][] = ['label' => 'Referensi Tahun Ajaran', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data Tahun Ajaran</h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>