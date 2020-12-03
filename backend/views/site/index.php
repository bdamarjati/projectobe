<?php

/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
<div class="site-index">
    <div class="body-content">
        <section class="content">
            <div class="row">
                <div class="col-sm-8">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="info-box bg-green" title="Data Mahasiswa">
                                <span class="info-box-icon"><i class="fa fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Data Mahasiswa</span>
                                    <span class="info-box-number"><?php echo $data['all'] ?></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width:100%"></div>
                                    </div>
                                    <span class="progress-description">
                                        <a class="small-box-footer" href="ref-mahasiswa" style="color: #fff;">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                                    </span>
                                </div>
                            </div>
                            <div class="info-box bg-aqua" title="Data Dosen">
                                <span class="info-box-icon"><i class="fa fa-building-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Data Dosen</span>
                                    <span class="info-box-number"><?php echo $data['dosen'] ?></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width:100%"></div>
                                    </div>
                                    <span class="progress-description">
                                        <a class="small-box-footer" href="ref-dosen" style="color: #fff;">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                                    </span>
                                </div>
                            </div>
                            <div class="info-box bg-red" title="Data Tahun Ajaran">
                                <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Data Tahun Ajaran</span>
                                    <span class="info-box-number"><?php echo $data['tahun'] ?></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width:100%"></div>
                                    </div>
                                    <span class="progress-description">
                                        <a class="small-box-footer" href="ref-tahun-ajaran" style="color: #fff;">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box bg-aqua" title="Data Kelas">
                                <span class="info-box-icon"><i class="fa fa-building-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Data Mata Kuliah</span>
                                    <span class="info-box-number"><?php echo $data['mk'] ?></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width:100%"></div>
                                    </div>
                                    <span class="progress-description">
                                        <a class="small-box-footer" href="ref-mata-kuliah" style="color: #fff;">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                                    </span>
                                </div>
                            </div>
                            <div class="info-box bg-yellow" title="Data Admin">
                                <span class="info-box-icon"><i class="fa fa-user-secret"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Data Admin</span>
                                    <span class="info-box-number"><?php echo $data['admin'] ?></span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width:100%"></div>
                                    </div>
                                    <span class="progress-description">
                                        <a class="small-box-footer" href="#" style="color: #fff;">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                                    </span>
                                </div>
                            </div>
                            <div class="info-box bg-blue" title="Log Database">
                                <span class="info-box-icon"><i class="fa fa-database"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Log Database</span>
                                    <span class="info-box-number">0</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width:100%"></div>
                                    </div>
                                    <span class="progress-description">
                                        <a class="small-box-footer" href="/setup/log/index" style="color: #fff;">Lihat Detail <i class="fa fa-arrow-circle-right"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="box box-solid">
                        <div class="box-body text-center">

                            <h1 id="clock"> </h1>
                        </div>
                        <div class="box-footer text-center">
                            <b><i class="fa fa-calendar"></i> <?php echo date('D, d M Y') ?></b>
                        </div>
                    </div>

                    <div class="box box-solid">
                        <div class="box-body text-center">
                            <div align="center">
                                <img class="img-circle" style="width: 80px; height: 80px;" src="<?= \Yii::$app->request->BaseUrl . '\images\user.png' ?>" />
                            </div>
                        </div>
                        <div class="box-body no-padding">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td width="90px">NIP</td>
                                        <td width="1px">:</td>
                                        <td><?php echo ucfirst(Yii::$app->user->identity->nip) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td><b><?php echo ucfirst(Yii::$app->user->identity->nama) ?></b></td>
                                    </tr>
                                    <tr>
                                        <td>Jabatan</td>
                                        <td>:</td>
                                        <td><?php echo ucfirst(Yii::$app->user->identity->auth_active) ?></td>
                                    </tr>
                                    <tr>
                                        <td>Username</td>
                                        <td>:</td>
                                        <td><?php echo (Yii::$app->user->identity->username) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="box-footer text-center">
                            <a class="btn btn-danger" href="/site/change-password">Ubah Kata Sandi</a> <a class="btn btn-danger" href="/site/change-photo">Ubah Foto</a> <br>
                            <br>
                        </div> -->
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </section>
    </div>
</div>
<script>
    function digitalClock() {
        var d = new Date();
        var h = d.getHours();
        var m = d.getMinutes();
        var s = d.getSeconds();
        var hrs;
        var mins;
        var tsecs;
        var secs;
        hrs = h;
        mins = m;
        secs = s;
        var ctime = hrs + ":" + mins + ":" + secs;
        document.getElementById("clock").firstChild.nodeValue = ctime;
    }
    window.onload = function() {
        digitalClock();
        setInterval('digitalClock()', 1000);
    }
</script>