<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Krs */

$this->title = 'Tambah Krs';
$this->params['breadcrumbs'][] = ['label' => 'Kartu Rencana Studi', 'url' => ['index']];
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