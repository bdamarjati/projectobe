<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\CapaianMahasiswa */

$this->title = 'Create Capaian Mahasiswa';
$this->params['breadcrumbs'][] = ['label' => 'Capaian Mahasiswas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="capaian-mahasiswa-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
