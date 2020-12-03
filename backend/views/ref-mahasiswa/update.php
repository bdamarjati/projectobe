<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RefMahasiswa */

$this->title = 'Update Mahasiswa : ' . $model->nim;
$this->params['breadcrumbs'][] = ['label' => 'Referensi Mahasiswa', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nim, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data <?php echo $model->nama?></h1>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>