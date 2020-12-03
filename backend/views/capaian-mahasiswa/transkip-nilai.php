<?php

/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Proses Import Nilai';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">UNIVERSITAS SEBELAS MARET</h4>
                <!-- <div class="form-group ">
                    <label id="dosen" class="col-sm-2 control-label">Pengampu</label>

                    <div class="col-sm-10">
                        <input value="<?/*php echo $dosen */ ?>" class="form-control" readonly>
                    </div>
                </div> -->

            </div>
        </div>
        <br>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <!-- <tr>
                            <th colspan="1"></th>
                            <th class="text-center" colspan="2">DATA MAHASISWA</th>
                            <th class="text-center" colspan="5">DATA NILAI</th>
                        </tr> -->
                        <tr>
                            <th class="text-center">NO</th>
                            <!-- <th class="text-center">#</th> -->
                            <!-- <th class="text-center">KEY</th> -->

                            <th class="text-center">KODE</th>
                            <th class="text-center">NAMA MATAKULIAH</th>

                            <th class="text-center">CPMK 1</th>
                            <th class="text-center">CPMK 2</th>
                            <th class="text-center">CPMK 3</th>
                            <th class="text-center">CPMK 4</th>
                            <th class="text-center">CPMK 5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data['transkip'] as $data) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $no++ ?></td>
                                <td class="text-center"><?php echo $data->kode ?></td>
                                <td class="text-left"><?php echo $data->nama ?></td>
                                <?php
                                foreach ($data['refCpmks'] as $key => $value) {
                                    // echo '<pre>';
                                    // print_r($value['capaianMahasiswas']['0']->nilai);
                                    // exit();
                                ?>
                                <td class="text-center"><?php echo $value['capaianMahasiswas']['0']->nilai ?></td>
                                <?php
                                }
                                ?>
                            </tr>

                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>