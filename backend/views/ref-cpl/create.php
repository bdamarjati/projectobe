<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RefCpl */

$this->title = 'Tambah CPL';
$this->params['breadcrumbs'][] = ['label' => 'Capaian Pembelajaran Lulusan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title">CPL Baru</h1>
        </div>
        <div class="panel-body">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>

</div>