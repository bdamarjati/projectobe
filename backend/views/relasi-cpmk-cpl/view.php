<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\RelasiCpmkCpl */

$this->title = "Relasi";
$this->params['breadcrumbs'][] = ['label' => 'Relasi CPMK CPL', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
                // 'id_ref_cpmk',
                [                                                  // the owner name of the model
                    'label' => 'Mata Kuliah',
                    'value' => $model['refMataKuliah']->nama,
                ],
                [                                                  // the owner name of the model
                    'label' => 'Kode Cpmk',
                    'value' => $model['refCpmk']->kode,
                ],
                [                                                  // the owner name of the model
                    'label' => 'Isi Cpmk',
                    'value' => $model['refCpmk']->isi,
                ],
                [                                                  // the owner name of the model
                    'label' => 'Kode Cpl',
                    'value' => $model['refCpl']->kode,
                ],
                [                                                  // the owner name of the model
                    'label' => 'Isi Cpmk',
                    'value' => $model['refCpl']->isi,
                ],
                [                                                  // the owner name of the model
                    'label' => 'Bobot',
                    'value' => $model->bobot,
                ],
                // 'id_ref_cpl',
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