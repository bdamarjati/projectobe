<?php

use yii\helpers\Html;

$this->title = "Profil";
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-default">
    <div class="panel-body">
        <img src="<?= \Yii::$app->request->BaseUrl . '\images\user.png' ?>" class="profile-user-img img-responsive img-circle" style="width: 200px; height: 200px;">
        <p class="text-muted text-center"><?php echo strtoupper(Yii::$app->user->identity->username) ?></p>

        <table class="table table-striped">
            <tr>
                <td style="text-align: right; width:45%">Nama</td>
                <td style="text-align: center; width:5%">:</td>
                <td style="width:45%"><?php echo ucfirst(Yii::$app->user->identity->nama) ?></td>
            </tr>
            <tr>
                <td style="text-align: right;">NIP</td>
                <td style="text-align: center;">:</td>
                <td><?php echo ucfirst(Yii::$app->user->identity->nip) ?></td>
            </tr>
            <tr>
                <td style="text-align: right;">Hak Akses</td>
                <td style="text-align: center;">:</td>
                <td>
                    <?php foreach (Yii::$app->assign->listAssign as $assign) {
                        $assign = strtoupper($assign);
                        echo ("| {$assign} |");
                    } ?>
                </td>
            </tr>
        </table>

        <!-- <hr> -->

        <!-- <div class="text-center"> -->

            <!-- <?php echo Html::a('Ubah Kata Sandi', ['change-password'], ['class' => 'btn btn-danger']) ?> -->
            <!-- <?php echo Html::a('Ubah Foto', ['change-photo'], ['class' => 'btn btn-danger']) ?> -->

        <!-- </div> -->
    </div>
    <!-- <br>
    <br> -->
</div>