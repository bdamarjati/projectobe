<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\CapaianMahasiswa */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Capaian Mahasiswas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="capaian-mahasiswa-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Capaian Mahasiswa', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'id_ref_cpmk',
            'id_ref_mahasiswa',
            'nilai',
            'semester',
            //'status',
            //'created_at',
            //'updated_at',
            //'created_user',
            //'updated_user',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
