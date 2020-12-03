<?php

use backend\models\CapaianMahasiswa;
use backend\models\FileUpload;
use backend\models\Krs;
use backend\models\RefCpmk;
use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
use phpDocumentor\Reflection\Types\Null_;
use PHPUnit\Framework\Constraint\IsNull;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\MataKuliahTayang */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerJsFile("@web/js/sweetalert.js", [
    'depends' => ["yii\web\JqueryAsset"]
]);
$js      = <<< JS
function alert() {
   return Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Something went wrong!',
        footer: '<a href>Why do I have this issue?</a>'
    })
}

JS;
$this->registerJs($js);

$this->title = 'Mata Kuliah Tayang';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Tabel Data</h1>
    </div>
    
    <div class="panel-body">
        <?php
        if (Yii::$app->assign->is(["administrator"])) {
        ?>
            <p align="right">
                <?= Html::a('Tambah', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        <?php
        }
        ?>
        <?php Pjax::begin(); ?>
        
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'class'     => 'yii\grid\DataColumn',      // can be omitted, as it is the default
                    'attribute' => 'id_tahun_ajaran',
                    'value'     => function ($dataProvider) {
                        return $dataProvider['tahunAjaran']->tahun; // $data['name'] for array data, e.g. using SqlDataProvider.
                    },
                ],
                'semester',
                [
                    'class'     => 'yii\grid\DataColumn',      // can be omitted, as it is the default
                    'attribute' => 'id_ref_mata_kuliah',
                    'value'     => function ($dataProvider) {
                        return $dataProvider['refMataKuliah']->nama; // $data['name'] for array data, e.g. using SqlDataProvider.
                    },
                ],
                [
                    'class'     => 'yii\grid\DataColumn',      // can be omitted, as it is the default
                    'attribute' => 'id_ref_kelas',
                    'value'     => function ($dataProvider) {
                        return $dataProvider['refKelas']->kelas; // $data['name'] for array data, e.g. using SqlDataProvider.
                    },
                ],

                [
                    'class'    => 'kartik\grid\ActionColumn',
                    'template' => '{all}',
                    'header'   => 'Import KRS',
                    'visible'  => !Yii::$app->assign->is(["dosen", "admin dosen"]),
                    'buttons'  => [
                        'all' => function ($url, $model, $key) {
                            if (Krs::findOne(['id_mata_kuliah_tayang' => $model->id])) {
                                $krs = Html::a(
                                    '<span class="glyphicon glyphicon-info-sign"> KRS</span>',
                                    ['/krs', 'jk' => $model->id],
                                    [
                                        'class' => 'btn-sm btn btn-primary',
                                    ]
                                );
                            } else {
                                $krs = Html::a(
                                    '<i class="glyphicon glyphicon-info-sign"> KRS</i>',
                                    ['/krs', 'jk' => $model->id],
                                    [
                                        'class' => 'btn-sm btn btn-warning',
                                    ]
                                );
                            }
                            return "<div class='btn-group'>
                                {$krs}
                            </div>";
                        },
                    ],
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{all}',
                    'header'   => 'Import Nilai',
                    'visible' => !Yii::$app->assign->is(["administrator"]),
                    'buttons' => [
                        'all' => function ($url, $model, $key) {
                            if (!Krs::findOne(['id_mata_kuliah_tayang' => $model->id])) {
                                $nilai = Html::a(
                                    '<i class="glyphicon glyphicon-info-sign"> Nilai</i>',
                                    [''],
                                    [
                                        'class'        => 'btn-sm btn btn-danger',
                                        'title'        => 'Kartu Rencana Studi Belum di UPLOAD ADMINISTRATOR',
                                    ]
                                );
                            } else if (
                                CapaianMahasiswa::find()
                                ->joinWith(['refCpmk'])
                                ->where([CapaianMahasiswa::tableName() . '.tahun' => $model['tahunAjaran']->tahun])
                                ->andWhere([CapaianMahasiswa::tableName() . '.semester' => $model->semester])
                                ->andWhere([CapaianMahasiswa::tableName() . '.kelas' => $model['refKelas']->kelas])
                                ->andWhere([RefCpmk::tableName() . '.id_ref_mata_kuliah' => $model['refMataKuliah']->id])
                                ->one()
                            ) {
                                $nilai = Html::a(
                                    '<span class="glyphicon glyphicon-info-sign"> Nilai</span>',
                                    ['/data-utama', 'jk' => $model->id],
                                    [
                                        'class' => 'btn-sm btn btn-primary',
                                    ]
                                );
                            } else {
                                $nilai = Html::a(
                                    '<i class="glyphicon glyphicon-info-sign"> Nilai</i>',
                                    ['/data-utama', 'jk' => $model->id],
                                    [
                                        'class' => 'btn-sm btn btn-warning',
                                    ]
                                );
                            }
                            return "<div class='btn-group'>
                                {$nilai}
                            </div>";
                        },
                    ],
                ],


                // ['class' => 'yii\grid\ActionColumn'],
                [
                    'class'   => 'kartik\grid\ActionColumn',
                    'options' => [
                        'style' => 'min-width: 100px',
                    ],
                    'template' => '{view} {update} {delete}',
                    'dropdown' => false,
                    'vAlign'   => 'middle',
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
                            if (!CapaianMahasiswa::find()
                                ->joinWith(['refCpmk'])
                                ->where([CapaianMahasiswa::tableName() . '.tahun' => $model['tahunAjaran']->tahun])
                                ->andWhere([CapaianMahasiswa::tableName() . '.semester' => $model->semester])
                                ->andWhere([CapaianMahasiswa::tableName() . '.kelas' => $model['refKelas']->kelas])
                                ->andWhere([RefCpmk::tableName() . '.id_ref_mata_kuliah' => $model['refMataKuliah']->id])
                                ->one()) {
                                return Html::a('<i class="fa fa-pencil"></i>', $url, [
                                    'data-original-title' => 'Perbarui',
                                    'title'               => 'Perbarui',
                                    'data-toggle'         => 'tooltip',
                                    'class'               => 'btn btn-warning btn-xs',
                                    // 'role'                => 'modal-remote',
                                ]);
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (!CapaianMahasiswa::find()
                                ->joinWith(['refCpmk'])
                                ->where([CapaianMahasiswa::tableName() . '.tahun' => $model['tahunAjaran']->tahun])
                                ->andWhere([CapaianMahasiswa::tableName() . '.semester' => $model->semester])
                                ->andWhere([CapaianMahasiswa::tableName() . '.kelas' => $model['refKelas']->kelas])
                                ->andWhere([RefCpmk::tableName() . '.id_ref_mata_kuliah' => $model['refMataKuliah']->id])
                                ->one()) {
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

        <?php Pjax::end(); ?>
    </div>
</div>