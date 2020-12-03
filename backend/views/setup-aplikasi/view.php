<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SetupAplikasi */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Setup Aplikasis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="setup-aplikasi-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'univ_id',
            'univ_en',
            'fakultas_id',
            'fakultas_en',
            'prodi_id',
            'prodi_en',
            'nama_dekan',
            'nip_dekan',
            'nama_kaprodi',
            'nip_kaprodi',
        ],
    ]) ?>

</div>
