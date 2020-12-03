<?php

/**
 * @link http://www.diecoding.com/
 * @author Die Coding (Sugeng Sulistiyawan) <diecoding@gmail.com>
 * @copyright Copyright (c) 2018
 */

use backend\models\MataKuliahTayang;
use backend\models\RefCpmk;
use backend\models\RefMahasiswa;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Tampilan Nilai Mata Kuliah';
$this->params['breadcrumbs'][] = $this->title;
$jk        = Yii::$app->getRequest()->getQueryParam('jk');
$mk_tayang = MataKuliahTayang::findOne($jk);
$cpmks     = RefCpmk::find()
    ->where(['id_ref_mata_kuliah' => $mk_tayang->id_ref_mata_kuliah])
    ->Andwhere(['status' => 1])
    ->all();
$total_cpmk = count($cpmks);

$css = <<< CSS
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background: none;
    outline: none;
    border: none;
}
.removeRow{
        background-color: #FF6347;
        color:#FFFFFF;
    }
CSS;
$this->registerCss($css);

$js = <<< JS
$(document).ready(function(){
    $('.chk_boxes1').click(function(){
        if($(this).is(':checked')){
            $(this).closest('tr').addClass('removeRow');
        } else {
            $(this).closest('tr').removeClass('removeRow');
        }
    });

    $('#btn_delete').click(function(){
        if(confirm("Apakah Anda yakin ingin menghapus data ini?")){
            var js = [];
            var jk = $jk;

            $(':checkbox:checked').each(function(i){
                js.push($(this).attr('data-id'));
            });

            if(js.length === 0){
                alert("Pilih minimal satu data");
            }else{
                $.ajax({
                url:"delete-multiple",
                method:'POST',
                data:{'js':js,'jk':jk},
                success:function(){
                        for(var i=0; i<js.length; i++){
                        $('tr#'+js[i]+'').fadeOut('slow');
                        }
                    }
                });
            }
        } else {
            return false;
        }
    });

        $('.check_all').click(function() {
            $('.chk_boxes1').prop('checked', this.checked);
            if($(this).is(':checked')){
                $('.check').addClass('removeRow');
            } else {
                $('.check').removeClass('removeRow');
            }
        });
    });
JS;
$this->registerJs($js);
?>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div div class="col-md-6">
                <?php echo Html::a('<i class="fa fa-arrow-left"></i> Kembali ke Halaman Unggah File', ['/data-utama', 'jk' => $jk], ['class' => 'btn-social btn btn-']) ?>
            </div>
            <div class="col-md-6" align="right">
                <?php
                echo Html::a('<i class="fa fa-eye"></i> Download File Upload', ['/data-utama/file-upload', 'jk' => $jk], [
                    'class' => 'btn btn-success btn-flat',
                ]);
                echo Html::a('Evaluasi MK', ['evaluasi-mata-kuliah', 'jk' => $jk], [
                    'class' => 'btn btn-primary btn-flat',
                ]);
                ?>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-12">
                <h4 class="text-center">UNIVERSITAS SEBELAS MARET</h4>
                <div class="form-group ">
                    <label id="fakultas" class="col-sm-2 control-label">Fakultas</label>
                    <div class="col-sm-10">
                        <input value="TEKNIK" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="program_studi" class="col-sm-2 control-label">Program Studi</label>

                    <div class="col-sm-10">
                        <input value="Teknik Elektro" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="tahun_ajaran" class="col-sm-2 control-label">Tahun Ajaran</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $data['tahun_ajaran']->tahun ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="semester" class="col-sm-2 control-label">Semester</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $data['tayang']->semester ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="mata_kuliah" class="col-sm-2 control-label">Mata Kuliah</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $data['mata_kuliah']->nama ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="kelas" class="col-sm-2 control-label">Kelas</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $data['kelas']->kelas ?>" class="form-control" readonly>
                    </div>
                </div>
                <div class="form-group ">
                    <label id="dosen" class="col-sm-2 control-label">Pengampu</label>

                    <div class="col-sm-10">
                        <input value="<?php echo $data['dosen']->nama_dosen ?>" class="form-control" readonly>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <br>
        <div class="row">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr></tr>
                        <tr>
                            <th colspan="1"></th>
                            <th class="text-center" colspan="2">DATA MAHASISWA</th>
                            <th class="text-center" colspan="5">DATA NILAI</th>
                        </tr>
                        <tr>
                            <th>
                                <input type="checkbox" class="check_all" />
                            </th>
                            <th class="text-center">NO</th>
                            <th class="text-center">NIM</th>
                            <th class="text-center">NAMA</th>
                            <?php
                            for ($i = 1; $i <= $total_cpmk; $i++) {
                                echo "<th class='text-center'>CPMK {$i}</th>";
                            }
                            ?>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;

                        foreach ($data['capaian'] as $key => $data) {
                            $mahasiswa = RefMahasiswa::findOne(['id' => $key]);
                        ?>
                            <tr class="check" id="<?php echo $key ?>">
                                <td>
                                    <input type="checkbox" name="js" class="chk_boxes1" data-id="<?php echo $key ?>" />
                                </td>
                                <td class="text-center"><?php echo $no++ ?></td>
                                <td class="text-left"><?php echo $mahasiswa->nim ?></td>
                                <td class="text-left"><?php echo $mahasiswa->nama ?></td>
                                <?php
                                foreach ($data as $value) {
                                ?>
                                    <td class="text-center"><?php echo $value['nilai'] ?></td>
                                <?php
                                }
                                ?>
                                <td class="text-center">
                                    <?php
                                    echo Html::a('<i class="fa fa-pencil"></i>', ['/capaian-mahasiswa/update', 'jk' => $jk, 'js' => $key], [
                                        'class' => 'btn btn-primary btn-xs',
                                        'data-original-title'  => 'Perbarui',
                                        'title'                => 'Perbarui',
                                        'data-toggle'          => 'tooltip'
                                    ]);
                                    ?>
                                    <?php
                                    echo Html::a('<i class="fa fa-trash"></i>', ['/capaian-mahasiswa/delete', 'jk' => $jk, 'js' => $key], [
                                        'class' => 'btn btn-danger btn-xs',
                                        'data-original-title'  => 'Hapus',
                                        'title'                => 'Hapus',
                                        'data-toggle'          => 'tooltip',
                                        'role'                 => 'modal-remote',
                                        'data-confirm'         => false,
                                        'data-method'          => false, // for overide yii data api
                                        'data-request-method'  => 'post',
                                        'data-confirm-title'   => 'Konfirmasi',
                                        'data-confirm-message' => 'Apakah anda yakin akan menghapus data ini?',
                                    ]);
                                    ?>
                                </td>
                            </tr>

                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger btn-ms">Delete Selected</button>
            </div>
        </div>
    </div>
</div>