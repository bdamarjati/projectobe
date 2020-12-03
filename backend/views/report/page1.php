<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan SKPI</title>
    <link href="<?php echo Yii::getAlias('@vendor/mpdf/mpdf/css/bootstrap.css') ?>" rel="stylesheet">
    <style>
        table tr td {
            /* border: 1px solid black; */
            background-color: transparent;
        }

        td {
            padding-bottom: 10px;
        }

        /* @page :first {
            background: url('images/background_skpi.png');
        } */
    </style>
</head>
<?php
$head_width = 5;
$isi  = 2;
$_isi_font = 11.5;
$_col_width = 10;
?>

<body style="background: url('images/background_skpi.png');background-image-resolution: from-image;">
    <div class="row">
        <table class="table">
            <tr>
                <td style="background-color:transparent;width:68%">
                    <p style="font-size:15px">PROGRAM STUDI <?= strtoupper($data['setupAplikasi']->prodi_id) ?></p>
                    <i style="font-size:13px;color:grey"> Departemen of <?= ucfirst($data['setupAplikasi']->prodi_en) ?> </i>
                </td>
                <td rowspan="2">
                    <p style="font-size:15px;">Nomor: &nbsp;&nbsp;&nbsp;/UN27.8/PP/2020</p>
                    <i style="font-size:13px;color:grey">Number</i>
                </td>
            </tr>
            <tr>
                <td>
                    <p style="font-size:15px">FAKULTAS <?= strtoupper($data['setupAplikasi']->fakultas_id) ?></p>
                    <i style="font-size:13px;color:grey">Faculty of <?= ucfirst($data['setupAplikasi']->fakultas_en) ?></i>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <br>
    <div style="text-align: center;">
        <h4 style="height: 50px;">
            <b style="height: 10px;"> SURAT KETERANGAN PENDAMPING IJAZAH</b> <br>
            <i style="font-size:16px;color:grey">Diploma Supplement</i>
        </h4>
        <p style="font-size:13px">
            Surat Keterangan Pendamping Ijazah (SKPI) merupakan pelengkap ijazah yang menerangkan <br>
            capaian pembelajaran pemegang ijazah selama masa studi
        </p>
        <i style="font-size:13px;color:grey">
            The Diploma Supplement accompanies a higher education certificate <br>
            providing learning outcomes achievement completed by its holder
        </i>
    </div>
    <p style="height: 43px;"></p>
    <div>
        <table class="table">
            <!-- Nomer 1 -->
            <tr>
                <td colspan="5">
                    <p style="font-size:13px">
                        <b> 1. IDENTITAS PEMEGANG SKPI </b>
                        <i style="font-size:13px;color:grey">/ Identity of Diploma Supplement Holders</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;"></td>
                <td colspan="2">
                    <p style="font-size:13px;">
                        NAMA LENGKAP
                        <i style="font-size:13px;color:grey">/ Full Name</i>
                    </p>
                </td>
                <td colspan="2" style="width: <?= $_col_width ?>%;">
                    <p style="font-size:13px">
                        TANGGAL MASUK
                        <i style="font-size:13px;color:grey">/ Date of Entry</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;"></td>
                <td style="width: <?= $isi ?>%;"></td>
                <td>
                    <p style="font-size:<?= $_isi_font ?>px;"> <?= strtoupper($data['setupPrint']['nama']) ?> </p=>
                </td>
                <td style="width: <?= $isi ?>%;"></td>
                <td>
                    <p style="font-size:<?= $_isi_font ?>px;"> <?= ucfirst($data['setupPrint']['tgl_masuk']) ?></p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;height:30px"></td>
                <td colspan="2">
                    <p style="font-size:13px">
                        NOMOR INDUK MAHASISWA
                        <i style="font-size:13px;color:grey">/ Registration Number</i>
                    </p>
                </td>
                <td colspan="2" style="width: <?= $_col_width ?>%;">
                    <p style="font-size:13px">
                        TANGGAL LULUS
                        <i style="font-size:13px;color:grey">/ Date of Completion</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;"></td>
                <td style="width: <?= $isi ?>%;"></td>
                <td>
                    <p style="font-size:<?= $_isi_font ?>px;"> <?= strtoupper($data['setupPrint']['nim']) ?> </p>
                </td>
                <td style="width: <?= $isi ?>%;"></td>
                <td>
                    <p style="font-size:<?= $_isi_font ?>px;"> <?= ucfirst($data['setupPrint']['tgl_lulus']) ?> </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;height:35px"></td>
                <td colspan="2">
                    <p style="font-size:13px">
                        TEMPAT DAN TANGGAL LAHIR
                        <i style="font-size:13px;color:grey"> / Place and Date of Birth</i>
                    </p>
                </td>
                <td colspan="2" style="width: <?= $_col_width ?>%;">
                    <p style="font-size:13px">
                        GELAR
                        <i style="font-size:13px;color:grey">/ Tittle</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;height: 75px;"></td>
                <td style="width: <?= $isi ?>%;"></td>
                <td style="vertical-align:top">
                    <p style="font-size:<?= $_isi_font ?>px;"> <?= ucfirst($data['setupPrint']['ttl']) ?> </p>
                </td>
                <td style="width: <?= $isi ?>%;"></td>
                <td style="vertical-align:top">
                    <p style="font-size:<?= $_isi_font ?>px;"> Sarjana Teknik (S.T.) <i style="font-size:<?= $_isi_font ?>px;color:grey">/ Bachelor of Engineering</i></p>
                </td>
            </tr>

            <!-- Nomer 2 -->
            <tr>
                <td colspan="5">
                    <p style="font-size:13px">
                        <b> 2. IDENTITAS PENYELENGGARA PROGRAM </b>
                        <i style="font-size:13px;color:grey">/ Identity of Awarding Institutions</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;"></td>
                <td colspan="2">
                    <p style="font-size:13px">
                        PERGURUAN TINGGI
                        <i style="font-size:13px;color:grey">/ Awarding Institutions</i>
                    </p>
                </td>
                <td colspan="2" style="width: <?= $_col_width ?>%;">
                    <p style="font-size:13px">
                        TOTAL SKS
                        <i style="font-size:13px;color:grey">/ Total of Credit Semester Unit</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;"></td>
                <td style="width: <?= $isi ?>%;"></td>
                <td>
                    <p style="font-size:<?= $_isi_font ?>px;">
                        <?= $data['setupAplikasi']->univ_id ?>
                        <i style="font-size:<?= $_isi_font ?>px;color:grey">/<?= $data['setupAplikasi']->univ_en ?></i>
                    </p>
                </td>
                <td style="width: <?= $isi ?>%;"></td>
                <td>
                    <p style="font-size:<?= $_isi_font ?>px;">
                        <?= $data['setupPrint']['total_sks'] ?> sks
                        <i style="font-size:<?= $_isi_font ?>px;color:grey">/<?= $data['setupPrint']['total_sks'] ?> credits</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;height:33px"></td>
                <td colspan="2">
                    <p style="font-size:13px">
                        PROGRAM STUDI
                        <i style="font-size:13px;color:grey">/ Department</i>
                    </p>
                </td>
                <td colspan="2" style="width: <?= $_col_width ?>%;">
                    <p style="font-size:13px">
                        DURASI STUDI REGULER
                        <i style="font-size:13px;color:grey">/ Regular Duration of Study</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;"></td>
                <td style="width: <?= $isi ?>%;"></td>
                <td>
                    <p style="font-size:<?= $_isi_font ?>px;">
                        <?= $data['setupAplikasi']->prodi_id ?>
                        <i style="font-size:<?= $_isi_font ?>px;color:grey">/<?= $data['setupAplikasi']->prodi_en ?></i>
                    </p>
                </td>
                <td style="width: <?= $isi ?>%;"></td>
                <td>
                    <p style="font-size:<?= $_isi_font ?>px;">
                        <?= $data['semester'] ?> Semester <?= $data['total_bulan'] ?> Bulan
                        <i style="font-size:<?= $_isi_font ?>px;color:grey">/<?= $data['semester'] ?> Semester <?= $data['total_bulan'] ?> Month</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;height:30px"></td>
                <td colspan="2">
                    <p style="font-size:13px">
                        FAKULTAS
                        <i style="font-size:13px;color:grey"> / Faculty</i>
                    </p>
                </td>
                <td colspan="2" style="width: <?= $_col_width ?>%;">
                    <p style="font-size:13px">
                        SISTEM PENILAIAN
                        <i style="font-size:13px;color:grey">/ Grading System</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;height:40px"></td>
                <td style="width: <?= $isi ?>%;"></td>
                <td style="vertical-align: top;">
                    <p style="font-size:<?= $_isi_font ?>px;">
                        <?= $data['setupAplikasi']->fakultas_id ?>
                        <i style="font-size:<?= $_isi_font ?>px;color:grey">/<?= $data['setupAplikasi']->fakultas_en ?></i>
                    </p>
                </td>
                <td style="width: <?= $isi ?>%;"></td>
                <td style="vertical-align: top;">
                    <p style="font-size:<?= $_isi_font ?>px;"> A=4; A-=3.7; B+=3.3; B=3; C+=2.7; C=2; D=1; E=0 </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;"></td>
                <td colspan="2">
                    <p style="font-size:13px">
                        JENIS DAN STRATA PENDIDIKAN <br>
                        <i style="font-size:13px;color:grey"> Type and Level of Educations</i>
                    </p>
                </td>
                <td colspan="2" style="width: <?= $_col_width ?>%;">
                    <p style="font-size:13px">
                        PERSYARATAN PENERIMAAN <br>
                        <i style="font-size:13px;color:grey">Entry Requirements</i>
                    </p>
                </td>
            </tr>
            <tr>
                <td style="width: <?= $head_width ?>%;"></td>
                <td style="width: <?= $isi ?>%;"></td>
                <td style="vertical-align: top;">
                    <p style="font-size:<?= $_isi_font ?>px;">
                        Akademik & Sarjana (Strata 1) <br>
                        <i style="font-size:<?= $_isi_font ?>px;color:grey">Academic & Bachelor Degree</i>
                    </p>
                </td>
                <td style="width: <?= $isi ?>%;"></td>
                <td style="vertical-align: top;">
                    <p style="font-size:<?= $_isi_font ?>px;">
                        Lulus Pendidikan Menengah Atas/Sederajat <br>
                        <i style="font-size:<?= $_isi_font ?>px;color:grey">Graduate from High School or Similar Education Level</i>
                    </p>
                </td>
            </tr>
        </table>
    </div>
    <p style="height: 50px;">
    </p>
    <p style="text-align: right;font-size:10px;color:white">
        HALAMAN 1 DARI 3 <i style="font-size:10px;color:white"> / PAGE 1 OF 3</i>
    </p>
</body>

</html>