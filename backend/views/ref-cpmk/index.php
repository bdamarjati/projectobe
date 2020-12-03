<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\RefCpmk */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Referensi CPMK';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Tabel CPMK</h1>
    </div>

    <div class="panel-body">
        <?php
        if (Yii::$app->assign->is(["administrator"])) {
        ?>
            <p align="right">
                <?= Html::a('Tambah Cpmk', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        <?php
        }
        ?>
        <?php // echo $this->render('_search', ['model' => $searchModel]); 
        ?>
        <?php
        // foreach ($searchModel as $value) {
        // echo "<pre>";
        // print_r($searchModel);
        // exit;
        // }
        ?>
        <?= GridView::widget([

            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                // 'id',
                [
                    'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                    'attribute' => 'id_ref_mata_kuliah',
                    'value' => function ($dataProvider) {
                        return $dataProvider['refMataKuliah']->nama; // $data['name'] for array data, e.g. using SqlDataProvider.
                    },
                ],
                [
                    'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                    'attribute' => 'kode',
                    'value' => function ($dataProvider) {
                        return $dataProvider->kode; // $data['name'] for array data, e.g. using SqlDataProvider.
                    },
                ],

                // 'kode',
                'isi:ntext',
                // 'status',
                //'created_at',
                //'updated_at',
                //'created_user',
                //'updated_user',
                // [
                //     'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
                //     'attribute' => 'ref_cpl.kode',
                //     'value' => function ($dataProvider) {
                //         return $dataProvider['refCpl']->kode; // $data['name'] for array data, e.g. using SqlDataProvider.
                //     },
                // ],
                // ['class' => 'yii\grid\ActionColumn'],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'options' => [
                        'style' => 'min-width: 100px',
                    ],
                    'template' => '{view} {update} {delete}',
                    'dropdown' => false,
                    'vAlign' => 'middle',
                    // 'urlCreator' => function($action, $model, $key, $index) {
                    //     $url = Url::to([$action, 'id' => $key]);
                    //     return $url;
                    // },
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<i class="fa fa-info-circle"></i>', $url, [
                                'data-original-title' => 'Lihat',
                                'title'               => 'Lihat',
                                'data-toggle'         => 'tooltip',
                                'class'               => 'btn btn-primary btn-xs',
                                // 'role'                => 'modal-remote',
                            ]);
                        },
                        'update' => function ($url, $model) {
                            return Html::a('<i class="fa fa-pencil"></i>', $url, [
                                'data-original-title' => 'Perbarui',
                                'title'               => 'Perbarui',
                                'data-toggle'         => 'tooltip',
                                'class'               => 'btn btn-warning btn-xs',
                                // 'role'                => 'modal-remote',
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<i class="fa fa-trash"></i>', $url, [
                                'data-original-title'  => 'Hapus',
                                'title'                => 'Hapus',
                                'data-toggle'          => 'tooltip',
                                'class'                => 'btn btn-danger btn-xs',
                                'role'                 => 'modal-remote',
                                'data-confirm'         => false,
                                'data-method'          => false, // for overide yii data api
                                'data-request-method'  => 'post',
                                'data-confirm-title'   => 'Konfirmasi',
                                'data-confirm-message' => 'Apakah anda yakin akan menghapus data ini?',
                            ]);
                        }
                    ],
                    'visibleButtons' =>
                    [
                        'update' => Yii::$app->assign->is(["administrator"]),
                        'delete' => Yii::$app->assign->is(["administrator"]),
                    ]
                ],
            ],
        ]); ?>
    </div>
</div>