<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use aryelds\sweetalert\SweetAlert;
use diecoding\toastr\ToastrFlash;

/* @var $this \yii\web\View */
/* @var $content string */


// echo "<pre>";print_r(Yii::$app->assetManager->bundles);exit;
if (Yii::$app->controller->action->id === 'login' || Yii::$app->controller->action->id === 'signup') {
    /**
     * Do not use this code in your template. Remove it. 
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        backend\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
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

    <body class="hold-transition skin-blue-light sidebar-mini">
        <?php $this->beginBody() ?>
        <div class="wrapper">

            <?= $this->render(
                'header.php',
                ['directoryAsset' => $directoryAsset]
            ) ?>

            <?= $this->render(
                'left.php',
                ['directoryAsset' => $directoryAsset]
            )
            ?>

            <?= $this->render(
                'content.php',
                ['content' => $content, 'directoryAsset' => $directoryAsset]
            ) ?>
            <?php
            echo ToastrFlash::widget([
                
            ]);
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
            <?php
            Modal::begin([
                "id"     => "ajaxCrudModal",
                "header"  => "<h4 class='modal-title'></h4>",
                "footer" => "",
                'options' => ['tabindex' => false],
            ]);

            Modal::end();
            ?>
        </div>

        <?php $this->endBody() ?>
    </body>

    </html>
    <?php $this->endPage() ?>
<?php } ?>