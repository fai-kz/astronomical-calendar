<link rel='stylesheet' href='css/styles.css'>
<p class="title"><b>Astronomical calendar </b></p>
<!--<p class="title"><b>Астрономический календарь для дома пр. Ветеранов 25</b></p>-->
<form action="print_eng_calend.php" method="post">
    <p class="tag"><button type="submit" name="submission">Save as pdf</button></p>
</form>
<?php
require "test.php";
//require "check_new.php";
//include "sun.php";
//require "sun.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    if (md5($_POST['norobot']) == $_SESSION['randomnr2']) {
        $longitude1 = $_POST["long"];
        $_SESSION["long"] = $longitude1;
        $longitude2 = $_POST["long1"];
        $_SESSION["long1"] = $longitude2;
        $longitude3 = $_POST["long2"];
        $_SESSION["long2"] = $longitude3;
        $sec_long_symb = "0";
        if (abs( $longitude3) < 10){
            $sec_long_symb = $sec_long_symb.(string)$longitude3;
        }
        $sign_long = $_POST['long_type'];
        $_SESSION["long_type"] = $sign_long;
        if($sign_long){
            $sign_long = "W";
        }
        else{
            $sign_long = "E";
        }
        $deg_long_symb = "0";
        $sec_long_symb = "0";
        $min_long_symb = "0";
        $longitude = $longitude1 + ($longitude2 / 60) + ($longitude3 / 3600);
        if (abs($longitude1) < 10){
            $deg_long_symb = $deg_long_symb.(string)$longitude1;
        }
        else{
            $deg_long_symb = (string)((int)$longitude1);
        }
        if (abs($longitude2) < 10){
            $min_long_symb = $min_long_symb.(string)((int)$longitude2);
        }
        else{
            $min_long_symb = (string)$longitude2;
        }
        if (abs($longitude3) < 10){
            $sec_long_symb = $sec_long_symb.(string)((int)$longitude3);
        }
        else{
            $sec_long_symb = (string)$longitude3;
        }

        $name_long = $deg_long_symb."&deg:".$min_long_symb."':".$sec_long_symb."''".$sign_long;
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

        $latitude1 = $_POST["lat"];
        $_SESSION["lat"] = $latitude1;
        $latitude2 = $_POST["lat1"];
        $_SESSION["lat1"] = $latitude2;
        $latitude3 = $_POST["lat2"];
        $_SESSION["lat2"] = $latitude3;
        $sign_lat = $_POST['lat_type'];
        $_SESSION["lat_type"] = $sign_lat;
        if($sign_lat){
            $sign_lat = "S";
        }
        else{
            $sign_lat = "N";
        }
        $latitude = $latitude1 + ($latitude2 / 60) + ($latitude3 / 3600);
        $deg_lat_symb = "0";
        $sec_lat_symb = "0";
        $min_lat_symb = "0";
        if (abs($latitude1) < 10){
            $deg_lat_symb = $deg_lat_symb.(string)((int)$latitude1);
        }
        else{
            $deg_lat_symb = (string)$latitude1;
        }
        if (abs($latitude2) < 10){
            $min_lat_symb = $min_lat_symb.(string)((int)$latitude2);
        }
        else{
            $min_lat_symb = (string)$latitude2;
        }
        if (abs( $latitude3) < 10){
            $sec_lat_symb = $sec_lat_symb.(string)((int)$latitude3);
        }
        else{
            $sec_lat_symb = (string)$latitude3;
        }
        $name_lat = $deg_lat_symb."&deg:".$min_lat_symb."':".$sec_lat_symb."''".$sign_lat;
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
        $altitude = $_POST["altitude"];
        $_SESSION["altitude"] =  $altitude;
        if (is_numeric($altitude) == false) {
            echo "<p style='color: red'>Attention. Input height has incorrect value. The value is not a number</p>" . "<br>";
            echo "<p style='color: red'>If you use fractional value, the separating sign is a dot (not comma)!</p>" . "<br>";
            echo "<p style='color: red'>Do not use spaces and letters</p>" . "<br>";
            exit();
        }
        $time_zone = $_POST["zone"];
        $_SESSION["zone"] = $time_zone;
        $time_zone = abs($time_zone);
        $time_sign = $_POST["utc_sign"];
        $_SESSION["utc_sign"] = $time_sign;
        if ($time_sign){
            $time_sign = "-".(string)$time_zone;
        }
        else{
            $time_sign = "+".(string)$time_zone;
        }
        if ((is_numeric($time_zone) == false) or (abs($time_zone) > 12)) {
            echo "<p style='color: red'>Attention. Input height has incorrect value. The value is not a number or exceeds 12!</p>" . "<br>";
            echo "<p style='color: red'>If you use fractional value, the separating sign is a dot (not comma)</p>" . "<br>";
            echo "<p style='color: red'>Do not use spaces and letters!</p>" . "<br>";
            exit();
        }

        $year_id = (int)trim($_POST["year"]);
        $_SESSION["year"] = $year_id;
        if (($year_id < 0) or ($year_id > 4000) or is_numeric($year_id) == false) {
            $year_id = 0;
            echo "<p style='color: red'>Attention. Input year has incorrect value! </p>" . "<br>";
            echo "<p style='color: red'>The value is not a number or out of 0 - 4000 </p>" . "<br>";
            echo "<p style='color: red'>Do not use spaces and letters!</p>" . "<br>";
            exit();
        }
        $month_id = $_POST["month"];
        $_SESSION["month"] = $month_id;
        switch ($month_id) {
            case '1':
                $month_id = 1;
                break;
            case '2':
                $month_id = 2;
                break;
            case '3':
                $month_id = 3;
                break;
            case '4':
                $month_id = 4;
                break;
            case '5':
                $month_id = 5;
                break;
            case '6':
                $month_id = 6;
                break;
            case '7':
                $month_id = 7;
                break;
            case '8':
                $month_id = 8;
                break;
            case '9':
                $month_id = 9;
                break;
            case '10':
                $month_id = 10;
                break;
            case '11':
                $month_id = 11;
                break;
            case '12':
                $month_id = 12;
                break;
            default:
                $month_id = 0;
                break;
        }

        $data_type = $_POST['type_data'];
        $_SESSION['type_data'] = $data_type;

        if ($year_id and $month_id and $data_type == 4) {
            $g = array();
            $g = month_sid_time($year_id, $month_id);
            echo "<div align='center'>";
            echo "Year: " . $year_id . ", Month: " . $month_id . "<br>";
            echo "<br>";
            echo "<b> Широта: </b>" . $name_lat ."&nbsp &nbsp". "<b> Долгота: </b>" . $name_long ."&nbsp &nbsp"." <b>Часовой пояс от UTC: </b>" . $time_sign . "<br>";
            echo "<br>";
            echo "<p><b>Локальное звездное время на 00:00:00 местного времени</b></p>";
            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'> &nbsp &nbsp Число &nbsp &nbsp </th>
            <th align='center'>&nbsp &nbsp LST (чч:мм:сс) &nbsp &nbsp </th>
            </tr>
            </thead>
            <tbody>";
            for ($i = 0; $i < count($g); $i++) {
                echo "<tr><td align='center'>" . ($i + 1) . "</td><td align='center'>" . $g[$i] . "</td></tr>";
            }
            echo "</tbody></table>";

            $sun = month_sun_time_new($year_id, $month_id);
            $twi_astr = month_twi_time_astr($year_id, $month_id);
            $twi_nav = month_twi_time_nav($year_id, $month_id);
            $twi_civil = month_twi_time_civil($year_id, $month_id);
            echo "<p><b>&nbsp &nbsp</b></p>";
            echo "Год: " . $year_id . ", Месяц: " . $month_id;

            echo "<p><b>Восход и заход Солнца, сумерки по местному времени </b></p>";

            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'>Число </th>
            <th align='center'>Начало астр. сумерек </th>
            <th align='center'>Начало нав. сумерек </th>
            <th align='center'>Начало гражд. сумерек </th>
            <th align='center'> Восход Солнца  </th>
            <th align='center'> Заход Солнца </th>
            <th align='center'>Конец гражд. сумерек </th>
            <th align='center'>Конец нав. сумерек </th>
            <th align='center'>Конец астр. сумерек </th>

            </tr>
            </thead>
            <tbody>";
            for ($i = 0; $i < count($sun); $i++) {
                echo "<tr><td align='center'>" . ($i + 1) . "</td><td align='center'>" . $twi_astr[$i][0] . "</td><td align='center'>" . $twi_nav[$i][0] . "</td><td align='center'>" . $twi_civil[$i][0] . "</td><td align='center'>" . $sun[$i][0] . "</td><td align='center'>" . $sun[$i][1] . "</td><td align='center'>" . $twi_civil[$i][1] . "</td><td align='center'>" . $twi_nav[$i][1] . "</td><td align='center'>" . $twi_astr[$i][1] . "</td></tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
        } elseif (($year_id and $month_id and $data_type == 3)) {
            $moon_rise_set = month_moon($year_id, $month_id);
            $moon_phase = Moon_phase_month($year_id, $month_id);
            $moon_type = moon_type_eng($year_id, $month_id);
            echo "<div align='center'>";
            echo "Year: " . $year_id . ", Month: " . $month_id . "<br>";
            echo "<br>";
            echo "<b>Latitude: </b>" . $name_lat ."&nbsp &nbsp". " <b>Longitude: </b>" . $name_long ."&nbsp &nbsp"." <b>time zone from UTC: </b>" . $time_sign . "<br>";
            echo "<p><b>Rise, set of the Moon at the local time (hh:mm:ss), phases</b></p>";


            echo "<table align='center'>";
            echo "<thead>
                <tr>
                <th align='center'> &nbsp Day &nbsp </th>
                <th align='center'> &nbsp Moon rise &nbsp </th>
                <th align='center'> &nbsp Moon set &nbsp </th>
                <th align='center'> &nbsp Phase &nbsp </th>
                <th align='center'> &nbsp &nbsp Growth  &nbsp &nbsp </th>
                </tr>
            </thead>
            <tbody>";
            for ($i = 0; $i < count($moon_rise_set); $i++) {
                echo "<tr><td align='center'>" . ($i + 1) . "</td><td align='center'>" . $moon_rise_set[$i][0] . "</td><td align='center'>" . $moon_rise_set[$i][1] . "</td><td align='center'>" . $moon_phase[$i] . "</td><td align='center'>&nbsp &nbsp" . $moon_type[$i] . "&nbsp &nbsp</td></tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
            echo "&nbsp";
            echo "<div style='color: #0e356e'>Due to fast orbital motion of the Moon there can be cases when there is only a moon rise (or a moon set) durning a day. Such situation is marked as --:--:-- </div>";
        } elseif (($year_id and $month_id and $data_type == 2)) {
            $sun = month_sun_time_new($year_id, $month_id);
            $twi_astr = month_twi_time_astr($year_id, $month_id);
            $twi_nav = month_twi_time_nav($year_id, $month_id);
            $twi_civil = month_twi_time_civil($year_id, $month_id);
            echo "<div align='center'>";
            echo "Year: " . $year_id . ", Month: " . $month_id . "<br>";
            echo "<br>";
            echo "<b>Latitude: </b>" . $name_lat ."&nbsp &nbsp". " <b> Longitude: </b>" . $name_long ."&nbsp &nbsp"." <b>Time zone from UTC: </b>" . $time_sign . "<br>";
            echo "<p><b>Rise, set of the Sun and twilights at the local time (hh:mm:ss)</b></p>";

            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'> &nbsp Day <br> <br> &nbsp</th>
            <th align='center'> Beginning of <br> an astronomical <br> twilight </th>
            <th align='center'> Beginning of <br> a navigation <br> twilight</th>
            <th align='center'> Beginning of <br> a civil <br> twilight </th>
            <th align='center'>  Sun <br> rise  <br> </th>
            <th align='center'> Sun <br> set <br>  </th>
            <th align='center'> Day <br> length <br>  </th>
            <th align='center'>  End of <br> a civil <br> twilight  </th>
            <th align='center'> End of <br> a navigation <br> twilight </th>
            <th align='center'> End of <br> an astronomical <br> twilight </th>

            </tr>
            </thead>
            <tbody>";
            for ($i = 0; $i < count($sun); $i++) {
                echo "<tr><td align='center'>" . ($i + 1) . "</td><td align='center'>" . $twi_astr[$i][0] . "</td><td align='center'>" . $twi_nav[$i][0] . "</td><td align='center'>" . $twi_civil[$i][0] . "</td><td align='center'>" . $sun[$i][0] . "</td><td align='center'>" . $sun[$i][1]. "</td><td align='center'>" . $sun[$i][2] . "</td><td align='center'>" . $twi_civil[$i][1] . "</td><td align='center'>" . $twi_nav[$i][1] . "</td><td align='center'>" . $twi_astr[$i][1] . "</td></tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
        } elseif (($year_id and $month_id and $data_type == 1)) {
            $g = array();
            $g = month_sid_time($year_id, $month_id);
            echo "<div align='center'>";
            echo "Year: " . $year_id . ", Month: " . $month_id . "<br>";
            echo "<br>";
            echo "<b> Latitude: </b>" . $name_lat ."&nbsp &nbsp". " <b> Longitude: </b>" . $name_long ."&nbsp &nbsp"." <b> Time zone from UTC: </b>" . $time_sign . "<br>";;
//            echo "<p><b>&nbsp &nbsp</b></p>";
            echo "<p><b>Local siderial time at 00:00:00 local civil time </b></p>";
//            echo "<p><b>&nbsp &nbsp</b></p>";
            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'> &nbsp &nbsp Day &nbsp &nbsp </th>
            <th align='center'>&nbsp &nbsp LST (hh:mm:ss) &nbsp &nbsp </th>
            </tr>
            </thead>
            <tbody>";
            for ($i = 0; $i < count($g); $i++) {
                echo "<tr><td align='center'>" . ($i + 1) . "</td><td align='center'>" . $g[$i] . "</td></tr>";
            }
            echo "</tbody></table>";
            echo "</div>";

        } elseif (($year_id and $data_type == 5)) {
            $sol_eq_jd = equinox_solstice_year_jd($year_id);
            $sping = Date_time_from_jd($sol_eq_jd[0]);
            $summer = Date_time_from_jd($sol_eq_jd[1]);
            $fall = Date_time_from_jd($sol_eq_jd[2]);
            $winter = Date_time_from_jd($sol_eq_jd[3]);
            $loc_sping = Date_time_from_jd($sol_eq_jd[4]);
            $loc_summer = Date_time_from_jd($sol_eq_jd[5]);
            $loc_fall = Date_time_from_jd($sol_eq_jd[6]);
            $loc_winter = Date_time_from_jd($sol_eq_jd[7]);
            echo "<div align='center'>";
            echo "Год: " . $year_id;
            echo "<p><b>Equinoxes and solstices at UTC time</b></p>";
            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'>  Тип  </th>
            <th align='center'> &nbsp &nbsp Month &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Day &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Time &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp  &nbsp &nbsp </th>
            </tr>
            </thead>
            <tbody>";
            echo "<tr><td align='center'> Spring equinox: </td><td align='center'>" . $sping[1] . "</td><td align='center'>" . $sping[2] . "</td><td align='center'>" . hours_to_sep($sping[6]) ."</td><td align='center'> "."</td></tr>";
            echo "<tr><td align='center'> Summer solstice: </td><td align='center'>" . $summer[1] . "</td><td align='center'>" . $summer[2] . "</td><td align='center'>" . hours_to_sep($summer[6]) ."</td><td align='center'> "."</td></tr>";
            echo "<tr><td align='center'> Autumn equinox: </td><td align='center'>" . $fall[1] . "</td><td align='center'>" . $fall[2] . "</td><td align='center'>" . hours_to_sep($fall[6]) . "</td><td align='center'> "."</td></tr>";
            echo "<tr><td align='center'> Winter solstice: </td><td align='center'>" . $winter[1] . "</td><td align='center'>" . $winter[2] . "</td><td align='center'>" . hours_to_sep($winter[6]) . "</td><td align='center'> "."</td></tr>";
            echo "</tbody></table>";
            echo "</div>";

            echo "<p>&nbsp &nbsp</p>";
            echo "<p align='center'><b>Equinoxes and solstices at local (UTC " . $time_sign . ") time </b></p>";
            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'>  Тип  </th>
            <th align='center'> &nbsp &nbsp Month &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Day &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Time &nbsp &nbsp </th>
             <th align='center'> &nbsp &nbsp  &nbsp &nbsp </th>
            </tr>
            </thead>
            <tbody>";
            echo "<tr><td align='center'> Spring equinox: </td><td align='center'>" . $loc_sping[1] . "</td><td align='center'>" . $loc_sping[2] . "</td><td align='center'>" . hours_to_sep($loc_sping[6]) . "</td><td align='center'> "."</td></tr>";
            echo "<tr><td align='center'> Summer solstice: </td><td align='center'>" . $loc_summer[1] . "</td><td align='center'>" . $loc_summer[2] . "</td><td align='center'>" . hours_to_sep($loc_summer[6]) . "</td><td align='center'> "."</td></tr>";
            echo "<tr><td align='center'> Autumn equinox:  </td><td align='center'>" . $loc_fall[1] . "</td><td align='center'>" . $loc_fall[2] . "</td><td align='center'>" . hours_to_sep($loc_fall[6]) . "</td><td align='center'>"."</td></tr>";
            echo "<tr><td align='center'> Winter solstice: </td><td align='center'>" . $loc_winter[1] . "</td><td align='center'>" . $loc_winter[2] . "</td><td align='center'>" . hours_to_sep($loc_winter[6]) . "</td><td align='center'> "."</td></tr>";
            echo "</tbody></table>";
            echo "</div>";
        } else {
            echo "You input incorrect data or did not specify the year and / or month" . "<br>";
        }
    }
    else {

        echo "Incorrectly input numbers from the picture (captcha). Come back";
    }

    if(isset($_SERVER['HTTP_REFERER'])) {
        $urlback = htmlspecialchars($_SERVER['HTTP_REFERER']);
        echo "<br>";
        echo "<a href='$urlback' class='history-back'> Back </a><br>";
    }



}
?>



