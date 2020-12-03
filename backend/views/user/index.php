<?php

use yii\helpers\Html;
// use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\widgets\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\searchs\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

// $primary = Yii::$app->application->setup->getConfig("primary", "color");
// $danger  = Yii::$app->application->setup->getConfig("danger", "color");
$this->registerJsFile("@web/js/sweetalert.js", [
    'depends' => ["yii\web\JqueryAsset"]
]);

$js      = <<< JS

$(".btn-admin").click(function() {
    
    var _this  = $(this);
    var url    = $(this).val();
    var assign = $(this).data("assign");
    var label  = $(this).text();

    var text = "Tambahkan";
    if (label == "Yes") {
        text = "Hilangkan";
    }
    Swal.fire({
        title             : "Apakah anda yakin?",
        text              : text + ' Hak Akses ' + assign + ' user ini!',
        type              : "warning",
        showCancelButton  : true,
        confirmButtonText : "OK!"
    }).then(function(result) {
        if (result.value) {
            $.get(url, function (data) {
                if (data) {
                    _this.text("Yes")
                        .addClass("btn-primary")
                        .removeClass("btn-danger");
                    
                        Swal.fire("Added!", "Anda berhasil menambakan Hak Akses " + assign + ".", "success");
                } else {
                    _this.text("No")
                        .addClass("btn-danger")
                        .removeClass("btn-primary");

                    Swal.fire("Revoked!", "Anda berhasil menghilangkan Hak Akses " + assign + ".", "success");
                }
            });
        }
    });
});
JS;
$this->registerJs($js);

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Tabel User</h1>
    </div>
    <div class="panel-body">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                // 'id',
                'username',
                'nama',
                'nip',
                'email:email',
                [
                    'class'    => 'kartik\grid\ActionColumn',
                    'template' => '{all}',
                    'header'   => 'Administrator',
                    'buttons'  => [
                        'all' => function ($url, $model, $key) {
                            $url = Url::to([
                                '/user/assign',
                                'id' => $model->id, 'assign' => 'administrator',
                            ], true);
                            if (Yii::$app->assign->hasAssign('administrator', $model->id)) {
                                $color = 'primary';
                                $label = 'Yes';
                            } else {
                                $color = 'danger';
                                $label = 'No';
                            }

                            $html = Html::button($label, [
                                "type"        => "button",
                                "class"       => "btn btn-$color btn-rounded btn-admin",
                                "value"       => $url,
                                "data-assign" => "administrator",
                                'data-pjax'   => 0,
                            ]);

                            return $html;
                        },
                    ],
                ],
                [
                    'class'    => 'kartik\grid\ActionColumn',
                    'template' => '{all}',
                    'header'   => 'Dosen',
                    'buttons'  => [
                        'all' => function ($url, $model, $key) {
                            $url = Url::to([
                                '/user/assign',
                                'id' => $model->id, 'assign' => 'dosen',
                            ], true);
                            if (Yii::$app->assign->hasAssign('dosen', $model->id)) {
                                $color = 'primary';
                                $label = 'Yes';
                            } else {
                                $color = 'danger';
                                $label = 'No';
                            }

                            $html = Html::button($label, [
                                "type"        => "button",
                                "class"       => "btn btn-$color btn-rounded btn-admin",
                                "value"       => $url,
                                "data-assign" => "dosen",
                                'data-pjax'   => 0,
                            ]);

                            return $html;
                        },
                    ],
                ],

                [
                    'class' => 'kartik\grid\ActionColumn',
                    'options' => [
                        'style' => 'min-width: 100px',
                    ],
                    'template' => '{deactive}',
                    'header'   => 'Status',
                    'dropdown' => false,
                    'vAlign' => 'middle',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<i class="fa fa-eye"></i>', $url, [
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
                        'deactive' => function ($url, $model) {
                            if ($model->status == 10) {
                                $label = 'Aktif';
                                $color = 'success';
                                $confirm = 'Menonaktifkan';
                            } elseif ($model->status == 9) {
                                $label = 'Tidak Aktif';
                                $color = 'warning';
                                $confirm = 'Mengaktifkan';
                                $url = Url::to([
                                    '/user/active',
                                    'id' => $model->id]);
                            }
                            return Html::a($label, $url, [
                                'class'                => "btn btn-$color btn-rounded",
                                'role'                 => 'modal-remote',
                                'data-confirm'         => false,
                                'data-method'          => false, // for overide yii data api
                                'data-request-method'  => 'post',
                                'data-confirm-title'   => 'Konfirmasi',
                                'data-confirm-message' => "Apakah anda yakin akan $confirm user ini?",
                            ]);
                        }
                    ],
                ],
            ],
        ]); ?>

    </div>
</div>