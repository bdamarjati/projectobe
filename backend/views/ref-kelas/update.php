<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RefKelas */

$this->title = 'Update Referensi Kelas: ' . $model->kelas;
$this->params['breadcrumbs'][] = ['label' => 'Ref Kelas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kelas, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data Kelas</h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>