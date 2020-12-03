<?php

namespace backend\controllers;

use backend\models\CapaianMahasiswa;
use backend\models\RefCpl;
use backend\models\RefMahasiswa;
use backend\models\RelasiCpmkCpl;
use kartik\mpdf\Pdf;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use backend\models\searchs\RefCpl as RefCplSearch;
use backend\models\SetupAplikasi;
use backend\models\UploadFileImporter;
use Mpdf\Mpdf;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Response;

class ReportController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionImage()
    {
        $request = Yii::$app->request;
        Yii::$app->response->format = Response::FORMAT_JSON;
        $_POST['cek'];
    }

    public function actionChart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $radar = Yii::$app->request->post('radar');
        $img   = str_replace('data:image/png;base64,', '', $radar);
        $img   = str_replace(' ', '+', $img);
        $data  = base64_decode($img);
        $path  = Yii::getAlias("@webroot/images");
        $base  = "{$path}/radar.png";
        @unlink($base);
        $radar_done = file_put_contents($base, $data);

        $bar   = Yii::$app->request->post('bar');
        $img   = str_replace('data:image/png;base64,', '', $bar);
        $img   = str_replace(' ', '+', $img);
        $data  = base64_decode($img);
        $path  = Yii::getAlias("@webroot/images");
        $base  = "{$path}/bar.png";
        @unlink($base);
        $bar_done = file_put_contents($base, $data);

        if ($bar_done && $radar_done) {
            $jk   = Yii::$app->request->post('id_mahasiswa');
            return  $this->redirect(['landing-skpi', 'jk' => $jk]);
        }
    }

    public function actionLandingSkpi($jk)
    {
        if ($model = Yii::$app->request->post()) {
            $data['refCpl']        = RefCpl::find()->where(['status' => 1])->all();
            $data['setupAplikasi'] = SetupAplikasi::find()->one();
            $data['setupPrint']    = $model;

            $date1 = $data['setupPrint']['tgl_masuk'];
            $date2 = $data['setupPrint']['tgl_lulus'];

            $ts1 = strtotime($date1);
            $ts2 = strtotime($date2);

            $year1 = date('Y', $ts1);
            $year2 = date('Y', $ts2);

            $month1 = date('m', $ts1);
            $month2 = date('m', $ts2);

            $day1 = date('d', $ts1);
            $day2 = date('d', $ts2);

            $total_bulan = (($year2 - $year1) * 12) + ($month2 - $month1);
            $total_hari = $day2 - $day1;
            $semester1 = $total_bulan / 6;
            $semester = floor($semester1);
            $total_bulan = $total_bulan - ($semester * 6);
            if ($total_hari > 15) {
                $total_bulan = $total_bulan + 1;
                if ($total_bulan == 6) {
                    $semester = $semester + 1;
                    $total_bulan = 0;
                }
            }
            $data['total_bulan'] = $total_bulan;
            $data['semester'] = $semester;

            // echo '<pre>';
            // print_r("Total Hari =" . $total_hari);
            // print_r("total bulan =" . $total_bulan);
            // print_r("total semester =" . $semester);
            // exit;

            $mpdf = new Mpdf(['tempDir' => Yii::getAlias("@backend/uploads/temp")]);
            $mpdf->debug = true;
            $mpdf->showImageErrors = true;
            $mpdf->adjustFontDescLineheight = 1.15;
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->AddPageByArray([
                'margin-top' => 38,
                'margin-bottom' => 0,
            ]);
            // $mpdf->SetDefaultBodyCSS('background', "url('images/background_skpi.png')");
            $mpdf->WriteHTML($this->renderPartial('page1', [
                'data' => $data,
            ]));
            $mpdf->AddPage();
            $mpdf->WriteHTML($this->renderPartial('page2', [
                'data' => $data,
            ]));

            $mpdf->Output();
            exit;
        }
        $data = RefMahasiswa::findOne(['id' => $jk]);
        return $this->render(
            'landing-skpi',
            [
                'data' => $data
            ]
        );
    }

    public function actionShockMerchant()
    {
        $ar = [10, 20, 20, 10, 10, 30, 50, 10, 20,];
        $n  = 9;

        sort($ar);
        $count = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($ar[$i] != 0) {
                for ($j = $i + 1; $j < $n; $j++) {
                    if ($ar[$i] == $ar[$j]) {
                        $count++;
                        $ar[$j] = 0;
                        $ar[$i] = 0;
                    }
                }
            }
        }
        echo $count;
    }

    public function actionCountingValleys()
    {
        $s = "UDDDUDUU";
        $n  = 8;
        $lenght = strlen($s);

        $lvl = 0;
        $v = 0;

        for ($i = 0; $i < $lenght; $i++) {
            if ($s[$i] == "U") {
                ++$lvl;
            } elseif ($s[$i] == "D") {
                --$lvl;
            }
            if ($lvl == 0 && $s[$i] == "U") {
                ++$v;
            }
        }
    }

    public function actionSimpleArraySum()
    {
        $ar = [1, 2, 3, 4, 10, 11];
        $nilai = 0;
        foreach ($ar as $key => $value) {
            $nilai += $value;
        }
        echo $nilai;
    }

    public function actionCompareTriplets()
    {
        $a = [5, 6, 7];
        $b = [3, 6, 10];
        $count_a = 0;
        $count_b = 0;
        for ($i = 0; $i < 3; $i++) {
            if ($a[$i] > $b[$i]) {
                $count_a++;
            } elseif ($a[$i] < $b[$i]) {
                $count_b++;
            }
        }
        $result = [$count_a, $count_b];
        print_r($result);
    }

    public function actionAveryBigSumArray()
    {
        $ar = [1000000001, 1000000002, 1000000003, 1000000004, 1000000005];
        $nilai = 0;
        foreach ($ar as $key => $value) {
            $nilai += $value;
        }
        echo $nilai;
    }

    public function actionDiagonalDifferent()
    {
        $ar = [[11, 2, 4], [4, 5, 6], [10, 8, -12]];
        $total_1 = 0;
        $total_2 = 0;
        $n = count($ar);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i == $j) {
                    $total_1 += $ar[$i][$j];
                }
                if ($i + $j == $n - 1) {
                    $total_2 += $ar[$i][$j];
                }
            }
        }
        $total = $total_1 - $total_2;
        $total = abs($total);
        print_r($total);
    }

    public function actionPlusMinus()
    {
        $arr     = [-4, 3, -9, 0, 4, 1];
        $n       = count($arr);
        $positif = 0;
        $negatif = 0;
        $zero    = 0;

        for ($i = 0; $i < $n; $i++) {
            if ($arr[$i] > 0) {
                $positif++;
            } elseif ($arr[$i] < 0) {
                $negatif++;
            } elseif ($arr[$i] == 0) {
                $zero++;
            }
        }
        $ratio_positif = $positif / $n;
        $ratio_negatif = $negatif / $n;
        $ratio_zero    = $zero / $n;

        $positif = number_format($ratio_positif, 6);
        $negatif = number_format($ratio_negatif, 6);
        $zero    = number_format($ratio_zero, 6);

        $result = [$positif, $negatif, $zero];

        print_r($result);
    }

    public function actionTimeConversion()
    {
        $s = "07:05:45PM";
        $jam = strtotime($s);
        $new_date = date('H:i:s', $jam);

        echo $new_date;
    }

    public function actionGrading()
    {
        $grades = [73, 67, 38, 33];
        foreach ($grades as $key => $value) {
            if ($value < 38) {
                $grades[$key] = $value;
            } else if ($value % 5 < 3) {
                $grades[$key] = $value;
            } else {
                $mod = $value % 5;
                $val = $value - $mod + 5;
                $grades[$key] = $val;
            }
        }
        print_r($grades);
        exit;
    }

    public function actionAppleOrange()
    {
        $s = 7;
        $t = 11;
        $a = 5;
        $b = 15;
        $apples = [-2, 2, 1];
        $oranges = [5, -6];
        $count_a = 0;
        $count_b = 0;
        foreach ($apples as $key => $value) {
            $d = $a + $value;
            if ($d >= $s && $d <= $t) {
                $count_a++;
            }
        }
        foreach ($oranges as $key => $value) {
            $d = $b + $value;
            if ($d >= $s && $d <= $t) {
                $count_b++;
            }
        }
        $result = [$count_a, $count_b];
        print_r($result);
    }

    public function actionStairCase()
    {
        $n = 6;
        $input = "#";
        for ($i = 0; $i < $n; $i++) {
            echo "<pre>";
            for ($j = $n; $j > $i; $j--) {
                echo " ";
            }
            for ($j = 0; $j <= $i; $j++) {
                echo $input;
            }
            echo "<pre>";
        }
    }

    public function actionMiniMaxSum()
    {
        $arr = [7, 69, 2, 221, 8974];
        sort($arr);
        $lenght = count($arr);
        $total = 0;
        foreach ($arr as $key => $value) {
            $total += $value;
        }
        $min = $total - $arr[$lenght - 1];
        $max = $total - $arr[0];
        $result = [$min, $max];
        print_r($result);
    }

    public function actionCandles()
    {
        $candles = [3, 2, 1, 3];
        sort($candles);
        $lenght = count($candles);
        $total = 0;
        foreach ($candles as $key => $value) {
            // if ($key != $lenght - 1) {
            if ($value == $candles[$lenght - 1]) {
                $total++;
            }
            // }
        }
        echo $total;
    }

    public function actionBeautyBinary()
    {
        $b      = "01001001011101";
        $lenght = strlen($b);
        $count  = 0;

        for ($i = 0; $i < $lenght; $i++) {
            if ($b[$i] == 1) {

                if ($i + 1 >= $lenght) {
                    $b[$i + 1] = 9;
                }

                if ($b[$i - 1] == 0 && $b[$i + 1] == 0) {
                    $count++;
                    $b[$i] = "9";
                    $b[$i - 1] = "9";
                    $b[$i + 1] = "9";
                }
            }
        }
        echo $count;
    }

    public function actionFizzBuzz()
    {
        $n = 15;
        for ($i = 1; $i <= $n; $i++) {
            if ($i % 5 == 0 && $i % 3 == 0) {
                echo "FizzBuzz";
            } elseif ($i % 3 == 0) {
                echo "Fizz";
            } elseif ($i % 5 == 0) {
                echo "Buzz";
            } else {
                echo $i;
            }
        }
    }

    public function actionTes1()
    {
        $height = [1, 1, 3, 4, 1];
        $first_data = $height;
        $sort_data = $height;
        sort($sort_data);
        $count = 0;
        foreach ($first_data as $key => $value) {
            if ($sort_data[$key] != $value) {
                $count++;
            }
        }
    }

    public function actionDanaTes()
    {
        $x = 4;
        $y = 9;

        $x = $x + $y;
        $y = $x - $y;
        $x = $x - $y;

        echo "<pre>";
        print_r($x);
        print_r($y);
        exit;
    }

    public function actionBreakingRecord()
    {
        $scores = [3, 4, 21, 36, 10, 28, 35, 5, 24, 42];
        $scores = [0, 9, 3, 10, 2, 20];

        $maxmin = ["", ""];
        $result = [0, 0];
        foreach ($scores as $key => $value) {
            if (!$maxmin[1] && !$maxmin[0]) {
                $maxmin[0] = $value;
                $maxmin[1] = $value;
            }
            if ($value > $maxmin[0]) {
                $maxmin[0] = $value;
                $result[0]++;
            } elseif ($value < $maxmin[1]) {
                $maxmin[1] = $value;
                $result[1]++;
            }
        }
        echo '<pre>';
        print_r($result);
        exit;
    }

    public function actionDivisibleSum()
    {
        $n = 6;
        $k = 3;
        $ar = [1, 3, 2, 6, 1, 2];
        $count = 0;
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i < $j) {
                    $sum = $ar[$i] + $ar[$j];
                    if ($sum % $k == 0) {
                        $count++;
                    }
                }
            }
        }

        print_r($count);
    }

    public function actionMigrationBird()
    {
        $arr = [1, 4, 4, 4, 5, 3];
        $lenght = count($arr);
        for ($i = 0; $i < $lenght; $i++) {
            $count = 0;
            for ($j = 0; $j < $lenght; $j++) {
                if ($arr[$i] == $arr[$j]) {
                    $count++;
                }
            }
            $result[$arr[$i]] = $count;
        }
        $nilai = 0;
        $res   = 0;
        foreach ($result as $key => $value) {
            if (!$nilai) {
                $nilai = $value;
            }
            if ($result[$key] > $nilai) {
                if (!$res) {
                    $res = $key;
                }
                if ($key < $res) {
                    $hasil = $key;
                } else {
                    $hasil = $res;
                }
            }
        }
        print_r($hasil);
        exit;
    }

    public function actionDayProgrammer()
    {
        $year = 100;
        if ($year % 4 == 0) {
            $result = "12.09." . $year;
        } else {
            $result = "13.09." . $year;
        }
        echo $result;
    }
}
