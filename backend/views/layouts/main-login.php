<?php

use aryelds\sweetalert\SweetAlert;
use backend\assets\AppAsset;
use diecoding\toastr\ToastrFlash;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

dmstr\web\AdminLteAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="login-page" style="background: url(https://files.diecoding.com/assets/images/pattern/symphony.png) fixed; min-height: 659px;">

    <?php $this->beginBody() ?>
    <?php
    echo ToastrFlash::widget([]);
    ?>
    <?php
    if (Yii::$app->session->getFlash('alert')) {
        $message = Yii::$app->session->getFlash('alert');
        echo SweetAlert::widget([
            'options' => [
                'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
                'text' => (!empty($message['text'])) ? Html::encode($message['text']) : 'Text Not Set!',
                'type' => (!empty($message['type'])) ? $message['type'] : SweetAlert::TYPE_INFO,
                'timer' => (!empty($message['timer'])) ? $message['timer'] : 4000,
                'showConfirmButton' => (!empty($message['showConfirmButton'])) ? $message['showConfirmButton'] : true
            ]
        ]);
    }
    ?>
    <?= $content ?>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>