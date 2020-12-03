<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\SetupAplikasi */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Setup Aplikasis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setup-aplikasi-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Setup Aplikasi', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'univ_id',
            'univ_en',
            'fakultas_id',
            'fakultas_en',
            //'prodi_id',
            //'prodi_en',
            //'nama_dekan',
            //'nip_dekan',
            //'nama_kaprodi',
            //'nip_kaprodi',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
