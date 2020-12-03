<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MataKuliahTayang */

$this->title = 'Tambah Mata Kuliah Tayang';
$this->params['breadcrumbs'][] = ['label' => 'Mata Kuliah Tayang', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data</h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>