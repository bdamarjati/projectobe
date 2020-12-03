<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RefCpmk */

$this->title = 'Update CPMK : ' . $model->kode;
$this->params['breadcrumbs'][] = ['label' => 'CPMK', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->kode, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data CPMK</h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
            // 'modelcpl' => $modelcpl,
        ]) ?>
    </div>
</div>