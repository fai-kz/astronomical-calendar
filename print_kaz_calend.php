<?php

require('fpdf/fpdf.php');
require('test.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    $longitude1 = $_SESSION["long"];
    $longitude2 = $_SESSION["long1"];
    $longitude3 = $_SESSION["long2"];
    $sec_long_symb = "0";
    if (abs($longitude3) < 10) {
        $sec_long_symb = $sec_long_symb . (string)$longitude3;
    }
    $sign_long = $_SESSION['long_type'];
    if ($sign_long) {
        $sign_long = " W";
    } else {
        $sign_long = " E";
    }
    $deg_long_symb = "0";
    $sec_long_symb = "0";
    $min_long_symb = "0";
    $longitude = $longitude1 + ($longitude2 / 60) + ($longitude3 / 3600);
    if (abs($longitude1) < 10) {
        $deg_long_symb = $deg_long_symb.(string)((int)$longitude1);
    } else {
        $deg_long_symb = (string)$longitude1;
    }
    if (abs($longitude2) < 10) {
        $min_long_symb = $min_long_symb.(string)((int)$longitude2);
    } else {
        $min_long_symb = (string)$longitude2;
    }
    if (abs($longitude3) < 10) {
        $sec_long_symb = $sec_long_symb.(string)((int)$longitude3);
    } else {
        $sec_long_symb = (string)$longitude3;
    }

    if ((is_numeric($longitude1) == false) or (is_numeric($longitude2) == false) or (is_numeric($longitude3) == false)){
        echo "<p style='color: red'>Внимание! Долгота имеет некорректное значение. Введеное вами значение не является числом </p>". "<br>";
        echo "<p style='color: red'>Не используйте пробелы и буквы!</p>" . "<br>";
        echo "<p style='color: red'>Если используете дробное значение, то разделителем служит точка, а не запятая!</p>" . "<br>";
        exit();
    }
    if ((abs($longitude) > 180)) {
        echo "<p style='color: red'>Внимание!Долгота превышает 180 градусов!</p>" . "<br>";
        exit();

    }
    $name_long = $deg_long_symb. "°:" . $min_long_symb . "':" . $sec_long_symb . "''" . $sign_long;
    $name_long = iconv('utf-8', 'windows-1251',$name_long);

    $latitude1 = $_SESSION["lat"];
    $latitude2 = $_SESSION["lat1"];
    $latitude3 = $_SESSION["lat2"];
    $sign_lat = $_SESSION['lat_type'];
    if ($sign_lat) {
        $sign_lat = " S";
    } else {
        $sign_lat = " N";
    }
    $latitude = $latitude1 + ($latitude2 / 60) + ($latitude3 / 3600);
    $deg_lat_symb = "0";
    $sec_lat_symb = "0";
    $min_lat_symb = "0";
    if (abs($latitude1) < 10) {
        $deg_lat_symb = $deg_lat_symb.(string)((int)$latitude1);
    } else {
        $deg_lat_symb = (string)$latitude1;
    }
    if (abs($latitude2) < 10) {
        $min_lat_symb = $min_lat_symb.(string)((int)$latitude2);
    } else {
        $min_lat_symb = (string)$latitude2;
    }
    if (abs($latitude3) < 10) {
        $sec_lat_symb = $sec_lat_symb.(string)((int)$latitude3);
    } else {
        $sec_lat_symb = (string)$latitude3;
    }

    $name_lat = $deg_lat_symb. "°:" . $min_lat_symb . "':" . $sec_lat_symb . "''" . $sign_lat;
    $name_lat = iconv('utf-8', 'windows-1251',$name_lat);

    if ((is_numeric($latitude1) == false) or (is_numeric($latitude2) == false) or (is_numeric($latitude3) == false)){
        echo "<p style='color: red'>Внимание! Широта имеет некорректное значение. Введеное вами значение не является числом </p>". "<br>";
        echo "<p style='color: red'>Не используйте пробелы и буквы!</p>" . "<br>";
        echo "<p style='color: red'>Если используете дробное значение, то разделителем служит точка, а не запятая!</p>" . "<br>";
        exit();
    }
    if ((abs($latitude) > 90)) {
        echo "<p style='color: red'>Внимание! Широта превышает 90 градусов!</p>" . "<br>";
        exit();
    }

    $altitude = $_SESSION["altitude"];
    if (is_numeric($altitude) == false) {
        echo "Внимание! Высота над ур. м. имеет некорректное значение. Введеное вами значение не является числом!</p>" . "<br>";
        echo "Если используете дробное значение, то разделителем служит точка, а не запятая!</p>" . "<br>";
        echo "Не используйте пробелы и буквы!</p>" . "<br>";
        exit();
    }

    $time_zone = $_SESSION["zone"];
    $time_zone = abs($time_zone);
    $time_sign = $_SESSION["utc_sign"];
    if ($time_sign) {
        $time_sign = "-" . (string)$time_zone;
    } else {
        $time_sign = "+" . (string)$time_zone;
    }

    if ((is_numeric($time_zone) == false) or (abs($time_zone) > 12)) {
        echo "<p style='color: red'>Внимание! Часовой пояс имеет некорректное значение. Введеное вами значение не является числом или превышает 12!</p>" . "<br>";
        echo "<p style='color: red'>Если используете дробное значение, то разделителем служит точка, а не запятая!</p>" . "<br>";
        echo "<p style='color: red'>Не используйте пробелы и буквы!</p>" . "<br>";
        exit();
    }


    $year_id = $_SESSION["year"];
    $month_id = $_SESSION["month"];
    $data_type = $_SESSION["type_data"];

    if (($year_id < 0) or ($year_id > 4000) or is_numeric($year_id) == false) {
        $year_id = 0;
        echo "<p style='color: red'>Внимание! Год имеет некорректное значение! </p>" . "<br>";
        echo "<p style='color: red'>Введеное вами значение не является числом! Либо выходит за рамки диапазона от 0 до 4000 </p>" . "<br>";
        echo "<p style='color: red'>Не используйте пробелы и буквы!</p>" . "<br>";
        exit();
    }


    if (($year_id and $month_id and $data_type == 1)) {
        class PDF extends FPDF
        {

            function BasicTable($data)
            {
                $lenght = count($data);
                define('FPDF_FONTPATH',"fpdf/font/");
                $this->AddFont('Arial','','arial.php');
                $this->SetFont("Arial", "");
                $data_name = "Ku'n";
                $addit = "(sag':mi'n:sek)";
                $this->SetX(70);
                $this->Cell(38, 7, $data_name, 1, 0, 'C');
                $this->SetX(108);
                $this->Cell(42, 7, "LST ".$addit, 1, 0, 'C');
                $this->Ln();
                $this->SetFont("Arial", "");
                for ($i = 0; $i < $lenght; $i++) {
                    $this->SetX(70);
                    $this->Cell(38, 7, $i + 1, 1, 0, 'C');
                    $this->SetX(108);
                    $this->Cell(42, 7, $data[$i], 1, 0, 'C');
                    $this->Ln();
                }
//        for($i = 0; $i < $lenght; $i++){
//
//            $this->Ln();
//        }
            }
        }

        $pdf = new PDF();
        $data = month_sid_time($year_id, $month_id);
        define('FPDF_FONTPATH',"fpdf/font/");
// добавляем шрифт ариал
        $pdf->AddFont('Arial','','arial.php');
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage();
        $pdf->SetXY(80, 5);
        $god = "Jyl: ";
        $mesyats = "Ai': ";
        $shirota = "Endik: ";
        $dolgota = "Boi'lyq: ";
        $chs_poyas = "UTC y'aqyt beldey'i: ";
        $signat = "Ku'ntizbelik eseptey'ler bag'darlamasyn FAFI ag'a g'ylymi' qyzmetkeri, f.-m.g'.k. Vitalii' Ki'm dai'yndady";
        $pdf->Cell(100, 16, $god . $year_id."  " . $mesyats . $month_id, 0);
        $pdf->Ln(10);
        $pdf->SetXY(30, 15);
        $pdf->Cell(100, 16, $shirota . $name_lat . "   " . $dolgota . $name_long . "   " . $chs_poyas . $time_sign, 0);
        $pdf->Ln(10);
        $pdf->SetXY(40, 25);
        $local_time = "Jergilikti y'aqyt boi'yns'a 00:00:00 jergilikti juldyzdyq y'aqyt";
        $pdf->Cell(100, 16, $local_time, 0);
        $pdf->Ln(20);
        $pdf->SetFont('Arial', '', 12);
        $pdf->BasicTable($data);
        $pdf->AddPage();
        $signat = "Ku'ntizbelik eseptey'ler bag'darlamasyn FAFI ag'a g'ylymi' qyzmetkeri, f.-m.g'.k. Vitalii' Ki'm dai'yndady";
        $pdf->MultiCell(190, 8, $signat, 0);
        $pdf->Ln();
        $pdf->Cell(100, 8, "kim@aphi.kz", 0);
        $pdf->Output();
    } elseif (($year_id and $month_id and $data_type == 2)) {
        class PDF extends FPDF
        {

            function BasicTable($sun, $twi_astr, $twi_nav, $twi_civil)
            {
                $lenght = count($sun);
                $Nachalo = "bastay'ly";
                $Konets = "son'y";;
                $astron = "Astron.";
                $nav = "Navigats.";
                $grazd = "Azamattyq";
                $voshhod = "s'yg'y'y";
                $zahod = "baty'y";
                $dlit = "uzaqtyg'y";
                $data_d = "Ku'n";
                $soln = "Ku'nnin'";
                $dnya = "Ku'nnin'";
                $sumerek = "ymyrttyn'";
                define('FPDF_FONTPATH',"fpdf/font/");
                $this->AddFont('Arial','','arial.php');
                $this->SetFont("Arial", '');
                $this->SetX(10);
                $this->Cell(20, 7, "", "LRT", 0, 'C');
                $this->SetX(30);
                $this->Cell(30, 7, $astron, "LRT", 0, 'C');
                $this->SetX(60);
                $this->Cell(30, 7, $nav, "LRT", 0, 'C');
                $this->SetX(90);
                $this->Cell(30, 7, $grazd, "LRT", 0, 'C');
                $this->SetX(120);
                $this->Cell(25, 7, "", "LRT", 0, 'C');
                $this->SetX(145);
                $this->Cell(25, 7, "", "LRT", 0, 'C');
                $this->SetX(170);
                $this->Cell(25, 7, "", "LRT", 0, 'C');
                $this->SetX(195);
                $this->Cell(30, 7, $grazd, "LRT", 0, 'C');
                $this->SetX(225);
                $this->Cell(30, 7, $nav, "LRT", 0, 'C');
                $this->SetX(255);
                $this->Cell(30, 7, $astron, "LRT", 0, 'C');
                $this->Ln();
                $this->SetX(10);
                $this->Cell(20, 7, "", "LR", 0, 'C');
                $this->SetX(30);
                $this->Cell(30, 7, $sumerek, "LR", 0, 'C');
                $this->SetX(60);
                $this->Cell(30, 7, $sumerek, "LR", 0, 'C');
                $this->SetX(90);
                $this->Cell(30, 7, $sumerek, "LR", 0, 'C');
                $this->SetX(120);
                $this->Cell(25, 7, $soln, "LR", 0, 'C');
                $this->SetX(145);
                $this->Cell(25, 7, $soln, "LR", 0, 'C');
                $this->SetX(170);
                $this->Cell(25, 7, $dnya, "LR", 0, 'C');
                $this->SetX(195);
                $this->Cell(30, 7, $sumerek, "LR", 0, 'C');
                $this->SetX(225);
                $this->Cell(30, 7, $sumerek, "LR", 0, 'C');
                $this->SetX(255);
                $this->Cell(30, 7, $sumerek, "LR", 0, 'C');
                $this->Ln();
                $this->SetX(10);
                $this->Cell(20, 7, $data_d, "LRB", 0, 'C');
                $this->SetX(30);
                $this->Cell(30, 7, $Nachalo, "LRB", 0, 'C');
                $this->SetX(60);
                $this->Cell(30, 7, $Nachalo, "LRB", 0, 'C');
                $this->SetX(90);
                $this->Cell(30, 7, $Nachalo, "LRB", 0, 'C');
                $this->SetX(120);
                $this->Cell(25, 7, $voshhod, "LRB", 0, 'C');
                $this->SetX(145);
                $this->Cell(25, 7, $zahod, "LRB", 0, 'C');
                $this->SetX(170);
                $this->Cell(25, 7, $dlit, "LRB", 0, 'C');
                $this->SetX(195);
                $this->Cell(30, 7, $Konets, "LRB", 0, 'C');
                $this->SetX(225);
                $this->Cell(30, 7, $Konets, "LRB", 0, 'C');
                $this->SetX(255);
                $this->Cell(30, 7, $Konets, "LRB", 0, 'C');
                $this->Ln();

                $this->SetFont("Arial", "");
                for ($i = 0; $i < $lenght; $i++) {
                    $this->SetX(10);
                    $this->Cell(20, 7, $i + 1, 1, 0, 'C');
                    $this->SetX(30);
                    $this->Cell(30, 7, $twi_astr[$i][0], 1, 0, 'C');
                    $this->SetX(60);
                    $this->Cell(30, 7, $twi_nav[$i][0], 1, 0, 'C');
                    $this->SetX(90);
                    $this->Cell(30, 7, $twi_civil[$i][0], 1, 0, 'C');
                    $this->SetX(120);
                    $this->Cell(25, 7, $sun[$i][0], 1, 0, 'C');
                    $this->SetX(145);
                    $this->Cell(25, 7, $sun[$i][1], 1, 0, 'C');
                    $this->SetX(170);
                    $this->Cell(25, 7, $sun[$i][2], 1, 0, 'C');
                    $this->SetX(195);
                    $this->Cell(30, 7, $twi_civil[$i][1], 1, 0, 'C');
                    $this->SetX(225);
                    $this->Cell(30, 7, $twi_nav[$i][1], 1, 0, 'C');
                    $this->SetX(255);
                    $this->Cell(30, 7, $twi_astr[$i][1], 1, 0, 'C');
                    $this->Ln();
                }
//        for($i = 0; $i < $lenght; $i++){
//
//            $this->Ln();
//        }
            }
        }

        $pdf = new PDF();
        $sun = month_sun_time_new($year_id, $month_id);
        $twi_astr = month_twi_time_astr($year_id, $month_id);
        $twi_nav = month_twi_time_nav($year_id, $month_id);
        $twi_civil = month_twi_time_civil($year_id, $month_id);
        define('FPDF_FONTPATH',"fpdf/font/");
// добавляем шрифт ариал
        $pdf->AddFont('Arial','','arial.php');
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage("L");
        $pdf->SetXY(120, 5);
        $god = "Jyl: ";
        $mesyats = "Ai': ";
        $shirota = "Endik: ";
        $dolgota = "Boi'lyq: ";
        $chs_poyas = "UTC y'aqyt beldey'i: ";
        $signat = "Ku'ntizbelik eseptey'ler bag'darlamasyn FAFI ag'a g'ylymi' qyzmetkeri, f.-m.g'.k. Vitalii' Ki'm dai'yndady";
        $sunrise = "Jergilikti y'aqyt boi'yns'a ku'nnin s'yg'y'y, baty'y, ymyrt (sag'at : mi'nut : seky'nd)";
        $pdf->Cell(100, 16, $god . $year_id ."  ". $mesyats . $month_id, 0);
        $pdf->Ln(10);
        $pdf->SetXY(70, 15);
        $pdf->Cell(100, 16, $shirota . $name_lat . "   " . $dolgota . $name_long . "   " . $chs_poyas . $time_sign, 0);
        $pdf->Ln(10);
        $pdf->SetXY(60, 25);
        $pdf->Cell(100, 16, $sunrise, 0);
        $pdf->Ln(20);
        $pdf->SetFont('Arial', '', 12);
        $pdf->BasicTable($sun, $twi_astr, $twi_nav, $twi_civil);
        $pdf->AddPage("L");
        $pdf->Cell(200, 16, $signat, 0);
        $pdf->Ln(10);
        $pdf->Cell(100, 16, "kim@aphi.kz", 0);
        $pdf->Output();

    } elseif ($year_id and $month_id and $data_type == 3) {
        class PDF extends FPDF
        {

            function BasicTable($moon_rise_set, $moon_phase, $moon_type)
            {
                $lenght = count($moon_rise_set);
                $data_d = "Ku'n";
                $Moon_rise = "Ai'dyn' s'yg'y'y";
                $Moon_set = "Ai'dyn' baty'y";
                $Phase = "Faza";
                $Growth = "O'sy'";
                define('FPDF_FONTPATH',"fpdf/font/");
                $this->AddFont('Arial','','arial.php');
                $this->SetFont("Arial", "");
                $this->SetX(25);
                $this->Cell(20, 7, $data_d, 1, 0, 'C');
                $this->SetX(45);
                $this->Cell(30, 7, $Moon_rise, 1, 0, 'C');
                $this->SetX(75);
                $this->Cell(30, 7, $Moon_set, 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, $Phase, 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(50, 7, $Growth, 1, 0, 'C');
                $this->Ln();
                $this->SetFont("Arial", "");
                for ($i = 0; $i < $lenght; $i++) {
                    $this->SetX(25);
                    $this->Cell(20, 7, $i + 1, 1, 0, 'C');
                    $this->SetX(45);
                    $this->Cell(30, 7, $moon_rise_set[$i][0], 1, 0, 'C');
                    $this->SetX(75);
                    $this->Cell(30, 7, $moon_rise_set[$i][1], 1, 0, 'C');
                    $this->SetX(105);
                    $this->Cell(25, 7, $moon_phase[$i], 1, 0, 'C');
                    $this->SetX(130);
                    $this->Cell(50, 7, iconv('utf-8', 'windows-1251', $moon_type[$i]), 1, 0, 'C');
                    $this->Ln();
                }
//        for($i = 0; $i < $lenght; $i++){
//
//            $this->Ln();
//        }
            }
        }

        $pdf = new PDF();
        $moon_rise_set = month_moon($year_id, $month_id);
        $moon_phase = Moon_phase_month($year_id, $month_id);
        $moon_type = moon_type_kaz_lat($year_id, $month_id);
        $god = "Jyl: ";
        $mesyats = "Ai': ";
        $shirota = "Endik: ";
        $dolgota = "Boi'lyq: ";
        $chs_poyas = "UTC y'aqyt beldey'i: ";
        $signat = "Ku'ntizbelik eseptey'ler bag'darlamasyn FAFI ag'a g'ylymi' qyzmetkeri, f.-m.g'.k. Vitalii' Ki'm dai'yndady";
        $moonrise = "Jergilikti y'aqyt boi'yns'a ai'dyn' s'yg'y'y, baty'y (sag'at : mi'ny't : seky'nd)";
        $info = "Ai'dyn' salystyrmaly tu'rde jyldam orbitalyq qozg'alysyna bai'lanysty ag'ymdag'y ta'y'likte tek Ai'dyn' ko'terily'i (nemese baty'y) bolatyn jag'dailar mu'mkin. Bul jag'dai' sa'ikes bag'andag'y syzyqs'a arqyly ko'rsetiledi";
        define('FPDF_FONTPATH',"fpdf/font/");
        $pdf->AddFont('Arial','','arial.php');
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage();
        $pdf->SetXY(80, 5);
        $pdf->Cell(100, 16, $god . $year_id. "  " . $mesyats . $month_id, 0);
        $pdf->Ln(10);
        $pdf->SetXY(30, 15);
        $pdf->Cell(100, 16, $shirota . $name_lat . "   " . $dolgota . $name_long . "   " . $chs_poyas . $time_sign, 0);
        $pdf->Ln(10);
        $pdf->SetXY(30, 25);
        $pdf->Cell(100, 16, $moonrise, 0);
        $pdf->Ln(20);
        $pdf->SetFont('Arial', '', 12);
        $pdf->BasicTable($moon_rise_set, $moon_phase, $moon_type);
        $pdf->Ln();
        $pdf->MultiCell(190, 8, $info, 0);
        $pdf->AddPage();
        $pdf->MultiCell(190, 8, $signat, 0);
        $pdf->Ln();
        $pdf->Cell(100, 10, "kim@aphi.kz", 0);
        $pdf->Output();
    }
    elseif($year_id and $month_id and $data_type == 5){
        class PDF extends FPDF{
            function BasicTable1($sping, $summer, $fall, $winter){
                define('FPDF_FONTPATH',"fpdf/font/");
                $this->AddFont('Arial','','arial.php');
                $this->SetFont("Arial", "");
                $this->SetX(20);
                $this->Cell(70, 7, "Ti'p", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, "Ai'", 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, "Ku'n", 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(30, 7, "Y'aqyt (UTC)", 1, 0, 'C');
                $this->Ln();
                $this->SetFont("Arial", "");
                $this->SetX(20);
                $this->Cell(70, 7, "Ko'ktemgi ku'n men tu'nnin' ten'ely'y", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, $sping[1], 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, $sping[2], 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(30, 7, hours_to_sep($sping[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(20);
                $this->Cell(70, 7, "Jazg'y ku'n toqyray'y", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, $summer[1], 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, $summer[2], 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(30, 7, hours_to_sep($summer[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(20);
                $this->Cell(70, 7, "Ku'zgi ku'n men tu'nnin' ten'ely'y", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, $fall[1], 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, $fall[2], 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(30, 7, hours_to_sep($fall[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(20);
                $this->Cell(70, 7, "Qysqy ku'n toqyray'y", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, $winter[1], 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, $winter[2], 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(30, 7, hours_to_sep($winter[6]), 1, 0, 'C');
            }
            function BasicTable2($loc_sping, $loc_summer, $loc_fall, $loc_winter){
                $this->AddFont('Arial','','arial.php');
                $this->SetFont("Arial", "");
                $this->SetFont("Arial", "");
                $this->SetX(20);
                $this->Cell(70, 7, "Ti'p", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, "Ai'", 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, "Ku'n", 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(30, 7, "Y'aqyt (UTC)", 1, 0, 'C');
                $this->Ln();
                $this->SetFont("Arial", "");
                $this->SetX(20);
                $this->Cell(70, 7, "Ko'ktemgi ku'n men tu'nnin' ten'ely'y", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, $loc_sping[1], 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, $loc_sping[2], 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(30, 7, hours_to_sep($loc_sping[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(20);
                $this->Cell(70, 7, "Jazg'y ku'n toqyray'y", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, $loc_summer[1], 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, $loc_summer[2], 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(30, 7, hours_to_sep($loc_summer[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(20);
                $this->Cell(70, 7, "Ku'zgi ku'n men tu'nnin' ten'ely'y", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, $loc_fall[1], 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, $loc_fall[2], 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(30, 7, hours_to_sep($loc_fall[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(20);
                $this->Cell(70, 7, "Qysqy ku'n toqyray'y", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, $loc_winter[1], 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, $loc_winter[2], 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(30, 7, hours_to_sep($loc_winter[6]), 1, 0, 'C');
            }

        }
        $sol_eq_jd = equinox_solstice_year_jd($year_id);
        $sping = Date_time_from_jd($sol_eq_jd[0]);
        $summer = Date_time_from_jd($sol_eq_jd[1]);
        $fall = Date_time_from_jd($sol_eq_jd[2]);
        $winter = Date_time_from_jd($sol_eq_jd[3]);
        $loc_sping = Date_time_from_jd($sol_eq_jd[4]);
        $loc_summer = Date_time_from_jd($sol_eq_jd[5]);
        $loc_fall = Date_time_from_jd($sol_eq_jd[6]);
        $loc_winter = Date_time_from_jd($sol_eq_jd[7]);
        $signat = "Ku'ntizbelik eseptey'ler bag'darlamasyn FAFI ag'a g'ylymi' qyzmetkeri, f.-m.g'.k. Vitalii' Ki'm dai'yndady";
        $pdf = new PDF();
        define('FPDF_FONTPATH',"fpdf/font/");
        $pdf->AddFont('Arial','','arial.php');
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage();
        $pdf->SetXY(100, 5);
        $pdf->Cell(100, 16, "Jyl: ". $year_id, 0);
        $pdf->Ln();
        $pdf->SetXY(30, 20);
        $pdf->Cell(100, 16, "UTC y'aqyt boi'yns'a ku'n men tu'n ten'ely'y ja'ne toqyray'y", 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 12);
        $pdf->BasicTable1($sping, $summer, $fall, $winter);
        $pdf->Ln(20);
        $pdf->SetX(30);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(100, 16, "Jergilikti y'aqyt boi'yns'a (UTC " . $time_sign . ") ku'n men tu'n ten'ely'y ja'ne toqyray'y", 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetFont('Arial', '', 12);
        $pdf->BasicTable2($loc_sping, $loc_summer, $loc_fall, $loc_winter);
        $pdf->AddPage();
        $pdf->MultiCell(190, 8, $signat, 0);
        $pdf->Ln();
        $pdf->Cell(100, 10, "kim@aphi.kz", 0);
        $pdf->Output();
    }
    else {
        echo "Error";
    }
} else {
    echo "Empty request" . "<br>";
}
