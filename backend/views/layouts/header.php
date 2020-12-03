<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


$css = <<< CSS
.skin-blue-light .main-header .navbar {
    background-color: #00c0ef;
}
.skin-blue-light .main-header .logo {
    background-color: #009abf;
    color: #ffffff;
    border-bottom: 0 solid transparent;
}

.skin-blue-light .main-header .navbar .sidebar-toggle:hover {
    background-color: 	#01a9c3;
}

.skin-blue .main-header .logo:hover {
    background-color: 	#01a9c3;
}

.skin-blue-light .main-header li.user-header {
    background-color: #00c0ef;
}
CSS;
$this->registerCss($css);
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><b>OBE</b></span><span class="logo-lg">' . 'Aplikasi <b>OBE</b>' . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/../../images/user.png" class="user-image" alt="User Image" />
                        <span class="hidden-xs"><?php echo (Yii::$app->user->identity->username) ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/../../images/user.png" class="img-circle" alt="User Image" />

                            <p>
                                <?php echo ucfirst(Yii::$app->user->identity->nama) ?>
                                <small><?php echo ucfirst(Yii::$app->user->identity->nip) ?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?php echo \Yii::$app->request->BaseUrl.'/site/profile'?>" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>