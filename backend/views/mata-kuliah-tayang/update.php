<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\MataKuliahTayang */

$this->title = 'Update Mata Kuliah Tayang ' .$model['refMataKuliah']->nama;
$this->params['breadcrumbs'][] = ['label' => 'Mata Kuliah Tayang', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data Mata Kuliah Tayang</h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>