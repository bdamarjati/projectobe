<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\RefCpmk */

$this->title = 'View : CPMK '.$model->kode;
$this->params['breadcrumbs'][] = ['label' => 'CPMK', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'CPMK '.$model->kode;
\yii\web\YiiAsset::register($this);
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title">Data Kode CPMK <?php echo $model->kode;?> </h1>
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
                [                                                  // the owner name of the model
                    'label' => 'Mata Kuliah',
                    'value' => $model['refMataKuliah']->nama,
                ],
                'kode',
                'isi:ntext',
                // [                                                  // the owner name of the model
                //     'label' => 'Relasi CPL',
                //     'value' => $modelcpl->isi,
                // ],
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