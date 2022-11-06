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
        $min_lat_symb = (string)$longitude2;
    }
    if (abs($longitude3) < 10) {
        $sec_long_symb = $sec_long_symb.(string)((int)$longitude3);
    } else {
        $sec_long_symb = (string)$longitude3;
    }

    if ((is_numeric($longitude1) == false) or (is_numeric($longitude2) == false) or (is_numeric($longitude3) == false)){
        echo "<p style='color: red'>Attention. Input longitude has incorrect value. The value is not a number </p>". "<br>";
        echo "<p style='color: red'>Do not use spaces and letters</p>" . "<br>";
        echo "<p style='color: red'> If you use fractional value, the separating sign is a dot (not comma)</p>" . "<br>";
        exit();
    }
    if ((abs($longitude) > 180)) {
        echo "<p style='color: red'>Attention. Input longitude exceeds 180 degrees</p>" . "<br>";
        exit();
    }

    $name_long = $deg_long_symb . "°:" . $min_lat_symb . "':" . $sec_long_symb . "''" . $sign_long;
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
    if ((is_numeric($latitude1) == false) or (is_numeric($latitude2) == false) or (is_numeric($latitude3) == false)){
        echo "<p style='color: red'>Attention. Input latitude has incorrect value. The value is not a number </p>". "<br>";
        echo "<p style='color: red'>Do not use spaces and letters!</p>" . "<br>";
        echo "<p style='color: red'>If you use fractional value, the separating sign is a dot (not comma)!</p>" . "<br>";
        exit();
    }
    if ((abs($latitude) > 90)) {
        echo "<p style='color: red'>Attention. Input latitude exceeds 90 degrees!</p>" . "<br>";
        exit();
    }
    $name_lat = $deg_lat_symb . "°:" . $min_lat_symb . "':" . $sec_lat_symb . "''" . $sign_lat;
    $name_lat = iconv('utf-8', 'windows-1251',$name_lat);
    $altitude = $_SESSION["altitude"];
    if (is_numeric($altitude) == false) {
        echo "<p style='color: red'>Attention. Input height has incorrect value. The value is not a number</p>" . "<br>";
        echo "<p style='color: red'>If you use fractional value, the separating sign is a dot (not comma)!</p>" . "<br>";
        echo "<p style='color: red'>Do not use spaces and letters</p>" . "<br>";
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
        echo "<p style='color: red'>Attention. Input height has incorrect value. The value is not a number or exceeds 12!</p>" . "<br>";
        echo "<p style='color: red'>If you use fractional value, the separating sign is a dot (not comma)</p>" . "<br>";
        echo "<p style='color: red'>Do not use spaces and letters!</p>" . "<br>";
        exit();
    }


    $year_id = $_SESSION["year"];
    $month_id = $_SESSION["month"];
    $data_type = $_SESSION["type_data"];

    if (($year_id < 0) or ($year_id > 4000) or is_numeric($year_id) == false) {
        $year_id = 0;
        echo "<p style='color: red'>Attention. Input year has incorrect value! </p>" . "<br>";
        echo "<p style='color: red'>The value is not a number or out of 0 - 4000 </p>" . "<br>";
        echo "<p style='color: red'>Do not use spaces and letters!</p>" . "<br>";
        exit();
    }

    if (($year_id and $month_id and $data_type == 1)) {
        class PDF extends FPDF
        {

            function BasicTable($data)
            {
                $lenght = count($data);
                $this->SetFont("Arial", "B");
                $this->SetX(70);
                $this->Cell(38, 7, "Date", 1, 0, 'C');
                $this->SetX(108);
                $this->Cell(38, 7, "LST (hh:mm:ss)", 1, 0, 'C');
                $this->Ln();
                $this->SetFont("Arial", "");
                for ($i = 0; $i < $lenght; $i++) {
                    $this->SetX(70);
                    $this->Cell(38, 7, $i + 1, 1, 0, 'C');
                    $this->SetX(108);
                    $this->Cell(38, 7, $data[$i], 1, 0, 'C');
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
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage();
        $pdf->SetXY(80, 5);
        $pdf->Cell(100, 16, "Year: " . $year_id . ", Month: " . $month_id, 0);
        $pdf->Ln(10);
        $pdf->SetXY(20, 15);
        $pdf->Cell(100, 16, "Latitude: " . $name_lat . "   " . "Longitude: " . $name_long . "   " . " Time zone from UTC: " . $time_sign, 0);
        $pdf->Ln(10);
        $pdf->SetXY(55, 25);
        $pdf->Cell(100, 16, "Local siderial time at 00:00:00 local civil time", 0);
        $pdf->Ln(20);
        $pdf->SetFont('Arial', '', 12);
        $pdf->BasicTable($data);
        $pdf->AddPage();
        $pdf->MultiCell(190, 8, "The program for calculation of the calendar was prepared by Vitaliy Kim (FAI senior researcher, PhD)", 0);
        $pdf->Ln();
        $pdf->Cell(100, 8, "kim@aphi.kz", 0);
        $pdf->Output();
    } elseif (($year_id and $month_id and $data_type == 2)) {
        class PDF extends FPDF
        {

            function BasicTable($sun, $twi_astr, $twi_nav, $twi_civil)
            {
                $lenght = count($sun);
                $this->SetFont("Arial", "B");
                $this->SetX(10);
                $this->Cell(20, 7, "", "LRT", 0, 'C');
                $this->SetX(30);
                $this->Cell(30, 7, "Beginning of", "LRT", 0, 'C');
                $this->SetX(60);
                $this->Cell(30, 7, "Beginning of", "LRT", 0, 'C');
                $this->SetX(90);
                $this->Cell(30, 7, "Beginning of", "LRT", 0, 'C');
                $this->SetX(120);
                $this->Cell(25, 7, "", "LRT", 0, 'C');
                $this->SetX(145);
                $this->Cell(25, 7, "", "LRT", 0, 'C');
                $this->SetX(170);
                $this->Cell(25, 7, "", "LRT", 0, 'C');
                $this->SetX(195);
                $this->Cell(30, 7, "End of", "LRT", 0, 'C');
                $this->SetX(225);
                $this->Cell(30, 7, "End of", "LRT", 0, 'C');
                $this->SetX(255);
                $this->Cell(30, 7, "End of", "LRT", 0, 'C');
                $this->Ln();
                $this->SetX(10);
                $this->Cell(20, 7, "", "LR", 0, 'C');
                $this->SetX(30);
                $this->Cell(30, 7, "an astron.", "LR", 0, 'C');
                $this->SetX(60);
                $this->Cell(30, 7, "a navigation", "LR", 0, 'C');
                $this->SetX(90);
                $this->Cell(30, 7, "a civil", "LR", 0, 'C');
                $this->SetX(120);
                $this->Cell(25, 7, "Sun", "LR", 0, 'C');
                $this->SetX(145);
                $this->Cell(25, 7, "Sun", "LR", 0, 'C');
                $this->SetX(170);
                $this->Cell(25, 7, "Day", "LR", 0, 'C');
                $this->SetX(195);
                $this->Cell(30, 7, "a civil", "LR", 0, 'C');
                $this->SetX(225);
                $this->Cell(30, 7, "a navigation", "LR", 0, 'C');
                $this->SetX(255);
                $this->Cell(30, 7, "an astron.", "LR", 0, 'C');
                $this->Ln();
                $this->SetX(10);
                $this->Cell(20, 7, "Date", "LRB", 0, 'C');
                $this->SetX(30);
                $this->Cell(30, 7, "twilight", "LRB", 0, 'C');
                $this->SetX(60);
                $this->Cell(30, 7, "twilight", "LRB", 0, 'C');
                $this->SetX(90);
                $this->Cell(30, 7, "twilight", "LRB", 0, 'C');
                $this->SetX(120);
                $this->Cell(25, 7, "rise", "LRB", 0, 'C');
                $this->SetX(145);
                $this->Cell(25, 7, "set", "LRB", 0, 'C');
                $this->SetX(170);
                $this->Cell(25, 7, "length", "LRB", 0, 'C');
                $this->SetX(195);
                $this->Cell(30, 7, "twilight", "LRB", 0, 'C');
                $this->SetX(225);
                $this->Cell(30, 7, "twilight", "LRB", 0, 'C');
                $this->SetX(255);
                $this->Cell(30, 7, "twilight", "LRB", 0, 'C');
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
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage("L");
        $pdf->SetXY(120, 5);
        $pdf->Cell(100, 16, "Year: " . $year_id . ", Month: " . $month_id, 0);
        $pdf->Ln(10);
        $pdf->SetXY(70, 15);
        $pdf->Cell(100, 16, "Latitude: " . $name_lat . "   " . "Longitude: " . $name_long . "   " . " Time zone from UTC: " . $time_sign, 0);
        $pdf->Ln(10);
        $pdf->SetXY(80, 25);
        $pdf->Cell(100, 16, "Rise, set of the Sun and twilights at the local time (hh:mm:ss)", 0);
        $pdf->Ln(20);
        $pdf->SetFont('Arial', '', 12);
        $pdf->BasicTable($sun, $twi_astr, $twi_nav, $twi_civil);
        $pdf->AddPage("L");
        $pdf->MultiCell(200, 8, "The program for calculation of the calendar was prepared by Vitaliy Kim (FAI senior researcher, PhD)", 0);
        $pdf->Ln();
        $pdf->Cell(100, 8, "kim@aphi.kz", 0);
        $pdf->Output();

    }
    elseif ($year_id and $month_id and $data_type == 3) {
        class PDF extends FPDF
        {

            function BasicTable($moon_rise_set, $moon_phase, $moon_type)
            {
                $lenght = count($moon_rise_set);
                $this->SetFont("Arial", "B");
                $this->SetX(45);
                $this->Cell(20, 7, "Date", 1, 0, 'C');
                $this->SetX(65);
                $this->Cell(25, 7, "Moon rise", 1, 0, 'C');
                $this->SetX(90);
                $this->Cell(25, 7, "Moon set", 1, 0, 'C');
                $this->SetX(115);
                $this->Cell(25, 7, "Phase", 1, 0, 'C');
                $this->SetX(140);
                $this->Cell(25, 7, "Growth", 1, 0, 'C');
                $this->Ln();
                $this->SetFont("Arial", "");
                for ($i = 0; $i < $lenght; $i++) {
                    $this->SetX(45);
                    $this->Cell(20, 7, $i + 1, 1, 0, 'C');
                    $this->SetX(65);
                    $this->Cell(25, 7, $moon_rise_set[$i][0], 1, 0, 'C');
                    $this->SetX(90);
                    $this->Cell(25, 7, $moon_rise_set[$i][1], 1, 0, 'C');
                    $this->SetX(115);
                    $this->Cell(25, 7, $moon_phase[$i], 1, 0, 'C');
                    $this->SetX(140);
                    $this->Cell(25, 7, $moon_type[$i], 1, 0, 'C');
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
        $moon_type = moon_type_eng($year_id, $month_id);
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage();
        $pdf->SetXY(80, 5);
        $pdf->Cell(100, 16, "Year: " . $year_id . ", Month: " . $month_id, 0);
        $pdf->Ln(10);
        $pdf->SetXY(30, 15);
        $pdf->Cell(100, 16, "Latitude: " . $name_lat . "   " . "Longitude: " . $name_long . "   " . " Time zone from UTC: " . $time_sign, 0);
        $pdf->Ln(10);
        $pdf->SetXY(40, 25);
        $pdf->Cell(100, 16, "Rise, set of the Moon at the local time (hh:mm:ss), phases", 0);
        $pdf->Ln(20);
        $pdf->SetFont('Arial', '', 12);
        $pdf->BasicTable($moon_rise_set, $moon_phase, $moon_type);
        $pdf->Ln();
        $pdf->MultiCell(190, 8, "Due to fast orbital motion of the Moon there can be cases when there is only a moon rise (or a moon set) durning a day. Such situation is marked as --:--:--", 0);
        $pdf->AddPage();
        $pdf->MultiCell(190, 8, "The program for calculation of the calendar was prepared by Vitaliy Kim (FAI senior researcher, PhD)", 0);
        $pdf->Ln();
        $pdf->Cell(100, 8, "kim@aphi.kz", 0);
        $pdf->Output();
    }

    elseif($year_id and $month_id and $data_type == 5){
        class PDF extends FPDF{
            function BasicTable1($sping, $summer, $fall, $winter){
                $this->SetFont("Arial", "B");
                $this->SetX(40);
                $this->Cell(40, 7, "Type", 1, 0, 'C');
                $this->SetX(80);
                $this->Cell(25, 7, "Month", 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, "Day", 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(25, 7, "Time (UTC)", 1, 0, 'C');
                $this->Ln();
                $this->SetFont("Arial", "");
                $this->SetX(40);
                $this->Cell(40, 7, "Spring equinox:", 1, 0, 'C');
                $this->SetX(80);
                $this->Cell(25, 7, $sping[1], 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, $sping[2], 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(25, 7, hours_to_sep($sping[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(40);
                $this->Cell(40, 7, "Summer solstice:", 1, 0, 'C');
                $this->SetX(80);
                $this->Cell(25, 7, $summer[1], 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, $summer[2], 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(25, 7, hours_to_sep($summer[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(40);
                $this->Cell(40, 7, "Autumn equinox:", 1, 0, 'C');
                $this->SetX(80);
                $this->Cell(25, 7, $fall[1], 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, $fall[2], 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(25, 7, hours_to_sep($fall[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(40);
                $this->Cell(40, 7, "Winter solstice:", 1, 0, 'C');
                $this->SetX(80);
                $this->Cell(25, 7, $winter[1], 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, $winter[2], 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(25, 7, hours_to_sep($winter[6]), 1, 0, 'C');
            }
            function BasicTable2($loc_sping, $loc_summer, $loc_fall, $loc_winter){
                $this->SetFont("Arial", "B");
                $this->SetX(40);
                $this->Cell(40, 7, "Type", 1, 0, 'C');
                $this->SetX(80);
                $this->Cell(25, 7, "Month", 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, "Day", 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(25, 7, "Time (Loc)", 1, 0, 'C');
                $this->Ln();
                $this->SetFont("Arial", "");
                $this->SetX(40);
                $this->Cell(40, 7, "Spring equinox:", 1, 0, 'C');
                $this->SetX(80);
                $this->Cell(25, 7, $loc_sping[1], 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, $loc_sping[2], 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(25, 7, hours_to_sep($loc_sping[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(40);
                $this->Cell(40, 7, "Summer solstice:", 1, 0, 'C');
                $this->SetX(80);
                $this->Cell(25, 7, $loc_summer[1], 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, $loc_summer[2], 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(25, 7, hours_to_sep($loc_summer[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(40);
                $this->Cell(40, 7, "Autumn equinox:", 1, 0, 'C');
                $this->SetX(80);
                $this->Cell(25, 7, $loc_fall[1], 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, $loc_fall[2], 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(25, 7, hours_to_sep($loc_fall[6]), 1, 0, 'C');
                $this->Ln();
                $this->SetX(40);
                $this->Cell(40, 7, "Winter solstice:", 1, 0, 'C');
                $this->SetX(80);
                $this->Cell(25, 7, $loc_winter[1], 1, 0, 'C');
                $this->SetX(105);
                $this->Cell(25, 7, $loc_winter[2], 1, 0, 'C');
                $this->SetX(130);
                $this->Cell(25, 7, hours_to_sep($loc_winter[6]), 1, 0, 'C');
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
        $pdf = new PDF();
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage();
        $pdf->SetXY(80, 5);
        $pdf->Cell(100, 16, "Year: " . $year_id, 0);
        $pdf->Ln();
        $pdf->SetXY(60, 20);
        $pdf->Cell(100, 16, "Equinoxes and solstices at UTC time", 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 12);
        $pdf->BasicTable1($sping, $summer, $fall, $winter);
        $pdf->Ln(20);
        $pdf->SetFont('Arial', '', 14);
        $pdf->SetX(50);
        $pdf->Cell(100, 16, "Equinoxes and solstices at local time (UTC ". $time_sign.")", 0);
        $pdf->Ln();
        $pdf->SetFont('Arial', '', 12);
        $pdf->BasicTable2($loc_sping, $loc_summer, $loc_fall, $loc_winter);
        $pdf->AddPage();
        $pdf->Cell(200, 16, "The program for calculation of the calendar was prepared by Vitaliy Kim (FAI senior researcher, PhD)", 0);
        $pdf->Ln(10);
        $pdf->Cell(100, 16, "kim@aphi.kz", 0);
        $pdf->Output();
    }
    else {
        echo "Error";
    }
}
else{
    echo "Empty request"."<br>";
}
?>