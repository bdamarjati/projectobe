<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="register-box">
    <div class="register-logo">
        <div align="center">
            <img style="width: 65%;" src="<?= \Yii::$app->request->BaseUrl . '\images\uns.png' ?>" />
        </div>
    </div>
    <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'nama') ?>

        <?= $form->field($model, 'nip') ?>


        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>