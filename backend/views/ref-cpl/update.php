<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RefCpl */

$this->title = 'Update : ' . $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'Capaian Pembelajaran Lulusan', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel panel-default">
    <div class="panel-heading">

        <h3 class="panel-title">Data</h3>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>