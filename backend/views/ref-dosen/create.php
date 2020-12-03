<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RefDosen */

$this->title = 'Tambah Dosen Pengajar';
$this->params['breadcrumbs'][] = ['label' => 'Ref Dosens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>