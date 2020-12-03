<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RefCpmk */

$this->title = 'Tambah Referensi CPMK';
$this->params['breadcrumbs'][] = ['label' => 'CPMK', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">CPMK Baru</h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
            'modelcpl' => $modelcpl,
        ]) ?>
    </div>
</div>