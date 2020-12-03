<?php

use backend\models\CapaianMahasiswa;
use backend\models\MataKuliahTayang;
use backend\models\RefCpmk;
use yii\helpers\Html;

$this->title = 'Tampilan KRS Mata Kuliah';
$this->params['breadcrumbs'][] = $this->title;
$jk = Yii::$app->getRequest()->getQueryParam('jk');
$mk_tayang = MataKuliahTayang::findOne($jk);

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
                <?php echo Html::a('<i class="fa fa-arrow-left"></i> Kembali ke Halaman Unggah File', ['/krs', 'jk' => $jk], ['class' => 'btn-social btn btn-']) ?>
            </div>
            <div class="col-md-6" align="right">
                <?php
                echo Html::a('<i class="fa fa-eye"></i> Download File Upload', ['file-upload', 'jk' => $jk], [
                    'class' => 'btn btn-success btn-flat',
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
                        <input value="Teknik" class="form-control" readonly>
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
                        <tr>
                            <th colspan="1"></th>
                            <th class="text-center" colspan="7">DATA MAHASISWA</th>
                        </tr>
                        <tr>
                            <th>
                                <input type="checkbox" class="check_all" />
                            </th>
                            <th class="text-center">NO</th>
                            <th class="text-center">NIM</th>
                            <th class="text-center">NAMA</th>
                            <?php
                            // if (!FileUpload::findOne(['id_mata_kuliah_tayang' => $jk, 'jenis' => 'nilai'])) {
                            ?>
                            <th class="text-center">Action</th>
                            <?php
                            // }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data['krs'] as $value) {
                        ?>
                            <?php
                            if (
                                !CapaianMahasiswa::find()
                                    ->joinWith(['refCpmk'])
                                    ->where([CapaianMahasiswa::tableName() . '.tahun' => $data['tahun_ajaran']->tahun])
                                    ->andWhere([CapaianMahasiswa::tableName() . '.semester' => $mk_tayang->semester])
                                    ->andWhere([CapaianMahasiswa::tableName() . '.kelas' => $data['kelas']->kelas])
                                    ->andWhere([CapaianMahasiswa::tableName() . '.id_ref_mahasiswa' => $value['refMahasiswa']->id])
                                    ->andWhere([RefCpmk::tableName() . '.id_ref_mata_kuliah' => $data['mata_kuliah']->id])
                                    ->one()
                            ) {
                            ?>
                                <tr class="check" id="<?php echo $value['refMahasiswa']->id ?>">
                                    <td>
                                        <input type="checkbox" name="js" class="chk_boxes1" data-id="<?php echo $value['refMahasiswa']->id ?>" />
                                    </td>
                                <?php
                            } else {
                                echo "<tr><td></td>";
                            }
                                ?>
                                <td class="text-center"><?php echo $no++ ?></td>
                                <td><?php echo $value['refMahasiswa']->nim ?></td>
                                <td><?php echo $value['refMahasiswa']->nama ?></td>
                                <?php
                                if (
                                    !CapaianMahasiswa::find()
                                        ->joinWith(['refCpmk'])
                                        ->where([CapaianMahasiswa::tableName() . '.tahun' => $data['tahun_ajaran']->tahun])
                                        ->andWhere([CapaianMahasiswa::tableName() . '.semester' => $mk_tayang->semester])
                                        ->andWhere([CapaianMahasiswa::tableName() . '.kelas' => $data['kelas']->kelas])
                                        ->andWhere([CapaianMahasiswa::tableName() . '.id_ref_mahasiswa' => $value['refMahasiswa']->id])
                                        ->andWhere([RefCpmk::tableName() . '.id_ref_mata_kuliah' => $data['mata_kuliah']->id])
                                        ->one()
                                ) {
                                ?>
                                    <td class="text-center">
                                        <?php
                                        echo Html::a('<i class="fa fa-trash"></i>', ['/krs/delete-mahasiswa', 'jk' => $jk, 'js' => $value->id_ref_mahasiswa], [
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
            <div class="col-md-12">
                <button type="button" name="btn_delete" id="btn_delete" class="btn btn-danger btn-ms">Delete Selected</button>
            </div>
        </div>

    </div>
</div>