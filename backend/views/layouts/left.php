<?php

use backend\models\SetupAplikasi;
use yii\helpers\Html;

$css = <<< CSS
.skin-blue .main-sidebar, .skin-blue .left-side {
    background-color: #00252d;
}

.skin-blue .sidebar-menu > li.active > a,
.skin-blue .sidebar-menu > li:hover > a,
.skin-blue .sidebar-menu > li.menu-open > a {
    background: #010f13;
}

.skin-blue .sidebar-menu > li.active > a {
    border-left-color: #ffff;
}

.skin-blue .sidebar-menu > li > .treeview-menu {
    background: #01404e;
}

.skin-blue .sidebar-menu .treeview-menu > li.active > a,
.skin-blue .sidebar-menu .treeview-menu > li > a:hover {
    /* border-left-color: #3c8dbc; */
    background: #013342;
}

.skin-blue-light .sidebar-menu  > li > a {
    color: #555;
    font-weight: 500;
    letter-spacing: 0.6px;
    font-size: 12px;
}
.skin-blue-light .sidebar-menu .treeview-menu>li>a,
.skin-blue-light .sidebar-menu .treeview-menu>li.active>a {
    font-weight: 500;
    letter-spacing: 0.6px;
    font-size: 12px;
}


CSS;
$this->registerCss($css);

?>

<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/../../images/user.png" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p><?php echo (Yii::$app->user->identity->username) ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> <?php echo ucfirst(Yii::$app->user->identity->auth_active) ?></a>
            </div>
        </div>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Dashboard', 'icon' => 'dashboard', 'url' => ['/site']],
                    // ['label' => 'Import Nilai', 'icon' => 'ioxhost', 'url' => ['/data-utama']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Mata Kuliah Tayang', 'icon' => 'black-tie', 'url' => ['/mata-kuliah-tayang']],
                    [
                        'label' => 'CP Lulusan',
                        'icon' => 'graduation-cap',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Referensi CPL', 'icon' => 'circle', 'url' => ['/ref-cpl'],],
                            [
                                'label' => 'Monev CPL',
                                'icon' => 'circle',
                                'url' => ['#'],
                                'items' => [
                                    // [
                                    //     'label' => 'CPL Individual', 'icon' => 'circle',
                                    //     'options' => ['role' => 'modal-remote'], 'url' => ['/monev-cpl/landing-individual'],
                                    // ],
                                    ['label' => 'CPL individu', 'icon' => 'genderless', 'url' => ['/monev-cpl/individual'],],
                                    ['label' => 'CPL Semester', 'icon' => 'genderless', 'url' => ['/monev-cpl/semester'],],
                                    ['label' => 'CPL Angkatan', 'icon' => 'genderless', 'url' => ['/monev-cpl/angkatan'],],
                                    ['label' => 'CPL Alumni', 'icon' => 'genderless', 'url' => ['/monev-cpl/index'],],
                                ]
                            ],
                        ],
                    ],
                    [
                        'label'   => 'CP Mata Kuliah',
                        'icon'    => 'list-alt',
                        'url'     => '#',
                        'items'   => [
                            ['label' => 'Mata Kuliah', 'icon' => 'circle', 'url' => ['/ref-mata-kuliah'],],
                            ['label' => 'CPMK', 'icon' => 'circle', 'url' => ['/ref-cpmk'],],
                            ['label' => 'Relasi CPMK to CPL', 'icon' => 'circle', 'url' => ['/relasi-cpmk-cpl']],
                        ],
                    ],
                    [
                        'label' => 'Data Pendukung',
                        'icon' => 'suitcase',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Mahasiswa', 'icon' => 'circle', 'url' => ['/ref-mahasiswa'],],
                            ['label' => 'Dosen Pengajar', 'icon' => 'circle', 'url' => ['/ref-dosen'],],
                            ['label' => 'Tahun Ajaran', 'icon' => 'circle', 'url' => ['/ref-tahun-ajaran'],],
                            ['label' => 'Kelas', 'icon' => 'circle', 'url' => ['/ref-kelas'],],
                        ],
                    ],
                    ['label' => 'Setup User', 'icon' => 'users', 'url' => ['/user'], 'visible' => Yii::$app->assign->is(["administrator"])],
                    ['label' => 'Tentang', 'icon' => 'info-circle', 'url' => ['#']],
                ],
            ]
        ) ?>
        <ul class="sidebar-menu tree" data-widget="tree">
            <li class="treeview">
                <?php
                if (Yii::$app->assign->is(["administrator"])) {
                    if ($aplikasi = SetupAplikasi::find()->one()) {
                        echo Html::a('<i class="fa fa-user-secret"></i> Pengaturan Aplikasi', ['/setup-aplikasi/update', 'id' => $aplikasi->id], [
                            'role' => 'modal-remote',
                        ]);
                    } else {
                        echo Html::a('<i class="fa fa-user-secret"></i> Pengaturan Aplikasi', ['/setup-aplikasi/create'], [
                            'role' => 'modal-remote',
                        ]);
                    }
                }
                ?>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user-secret"></i> <span>Hak Akses</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <?php foreach (Yii::$app->assign->listAssign as $assign) { ?>
                        <li>
                            <?php
                            $label = "{$assign}";
                            echo Html::a("<i class='fa fa-circle'>$label</i>", ['/site/set-assign'], [
                                'style' => $assign == Yii::$app->assign->active ? 'font-weight: bold; color: #ffff; background-color: #777;' : '',
                                'data' => [
                                    'method' => 'post',
                                    'params' => [
                                        'assign' => $assign,
                                    ]
                                ]
                            ]) ?>
                        </li>
                    <?php } ?>
                </ul>
            </li>
        </ul>
    </section>

</aside>