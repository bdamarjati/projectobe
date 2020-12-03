<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\RefDosen */

$this->title = 'View : '.$model->nama_dosen;
$this->params['breadcrumbs'][] = ['label' => 'Dosen Pengajar', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View';
\yii\web\YiiAsset::register($this);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?= Html::encode($this->title) ?></h1>
    </div>
    <div class="panel-body">
        <?php
        if (Yii::$app->assign->is(["administrator"])) {
        ?>
            <p align="right">
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        <?php
        }
        ?>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                // 'id',
                'kode_dosen',
                'nip',
                'nama_dosen',
                [
                    'class'      => 'kartik\grid\DataColumn',   // can be omitted, as it is the default
                    'attribute'  => 'status',
                    'format'     => 'raw',
                    'filterInputOptions' => [
                        'placeholder' => '',
                    ],
                    'value' => function ($dataProvider) {
                        if ($dataProvider->status == 1) {
                            return 'Aktif';
                        } elseif ($dataProvider->status == 9) {
                            return 'Tidak Aktif';
                        }
                        // return $dataProvider->status; // $data['name'] for array data, e.g. using SqlDataProvider.
                    },
                ],
                // 'created_at',
                // 'updated_at',
                // 'created_user',
                // 'updated_user',
            ],
        ]) ?>
        <?= Html::button(
            'Back',
            array(
                'name' => 'btnBack',
                'class' => 'btn btn-danger',
                'onclick' => "history.go(-1)",
            )
        ); ?>
    </div>
</div>