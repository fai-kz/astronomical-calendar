<link rel='stylesheet' href='css/styles.css'>
<p class="title"><b> Астрономиялық күнтізбе </b></p>
<!--<p class="title"><b>Астрономический календарь для дома пр. Ветеранов 25</b></p>-->
<form action="print_kaz_calend.php" method="post">
    <p class="tag"><button type="submit" name="submission">pdf қайта жасау</button></p>
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
            $deg_long_symb = $deg_long_symb.(string)((int)$longitude1);
        }
        else{
            $deg_long_symb = (string)$longitude1;
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
            echo "<p style='color: red'>Внимание! Долгота имеет некорректное значение. Введеное вами значение не является числом </p>". "<br>";
            echo "<p style='color: red'>Не используйте пробелы и буквы!</p>" . "<br>";
            echo "<p style='color: red'>Если используете дробное значение, то разделителем служит точка, а не запятая!</p>" . "<br>";
            exit();
        }
        if ((abs($longitude) > 180)) {
            echo "<p style='color: red'>Внимание!Долгота превышает 180 градусов!</p>" . "<br>";
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
            echo "<p style='color: red'>Внимание! Широта имеет некорректное значение. Введеное вами значение не является числом </p>". "<br>";
            echo "<p style='color: red'>Не используйте пробелы и буквы!</p>" . "<br>";
            echo "<p style='color: red'>Если используете дробное значение, то разделителем служит точка, а не запятая!</p>" . "<br>";
            exit();
        }
        if ((abs($latitude) > 90)) {
            echo "<p style='color: red'>Внимание! Широта превышает 90 градусов!</p>" . "<br>";
            exit();
        }
        $altitude = $_POST["altitude"];
        $_SESSION["altitude"] =  $altitude;
        if (is_numeric($altitude) == false) {
            echo "Внимание! Высота над ур. м. имеет некорректное значение. Введеное вами значение не является числом!</p>" . "<br>";
            echo "Если используете дробное значение, то разделителем служит точка, а не запятая!</p>" . "<br>";
            echo "Не используйте пробелы и буквы!</p>" . "<br>";
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
            echo "<p style='color: red'>Внимание! Часовой пояс имеет некорректное значение. Введеное вами значение не является числом или превышает 12!</p>" . "<br>";
            echo "<p style='color: red'>Если используете дробное значение, то разделителем служит точка, а не запятая!</p>" . "<br>";
            echo "<p style='color: red'>Не используйте пробелы и буквы!</p>" . "<br>";
            exit();
        }

        $year_id = (int)trim($_POST["year"]);
        $_SESSION["year"] = $year_id;
        if (($year_id < 0) or ($year_id > 4000) or is_numeric($year_id) == false) {
            $year_id = 0;
            echo "<p style='color: red'>Внимание! Год имеет некорректное значение! </p>" . "<br>";
            echo "<p style='color: red'>Введеное вами значение не является числом! Либо выходит за рамки диапазона от 0 до 4000 </p>" . "<br>";
            echo "<p style='color: red'>Не используйте пробелы и буквы!</p>" . "<br>";
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
            echo "Год: " . $year_id . ", Месяц: " . $month_id . "<br>";
            echo "<b> Широта: </b>" . $name_lat ."&nbsp &nbsp". "<b> Долгота: </b>" . $name_long ."&nbsp &nbsp"." <b>UTC уақыт белдеуі: </b>" . $time_sign . "<br>";
            echo "<br>";
            echo "<p><b>Жергілікті уақыт бойынша 00:00:00 жергілікті жұлдыздық уақыт</b></p>";

            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'> &nbsp &nbsp Күн &nbsp &nbsp </th>
            <th align='center'>&nbsp &nbsp LST (сағ:мм:сс) &nbsp &nbsp </th>
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
            echo "Жыл: " . $year_id . ", Ай: " . $month_id;

            echo "<p><b> Жергілікті уақыт бойынша күннің шығуы, батуы, ымырт </b></p>";

            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'> Күн </th>
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
            $moon_type = moon_type_kaz($year_id, $month_id);
            echo "<div align='center'>";
            echo "Жыл: " . $year_id . ", Ай: " . $month_id . "<br>";
            echo "<br>";
            echo "<b> Ендік: </b>" . $name_lat ."&nbsp &nbsp". " <b> Бойлық
: </b>" . $name_long ."&nbsp &nbsp"." <b> UTC уақыт белдеуі: </b>" . $time_sign . "<br>";
            echo "<p><b> Жергілікті уақыт бойынша айдың шығуы, батуы (сағ:мин:сек), фазалары </b></p>";


            echo "<table align='center'>";
            echo "<thead>
                <tr>
                <th align='center'> &nbsp Күн &nbsp </th>
                <th align='center'> &nbsp Айдың шығуы &nbsp </th>
                <th align='center'> &nbsp Айдың батуы &nbsp </th>
                <th align='center'> &nbsp Фаза &nbsp </th>
                <th align='center'> &nbsp &nbsp Өсу  &nbsp &nbsp </th>
                </tr>
            </thead>
            <tbody>";
            for ($i = 0; $i < count($moon_rise_set); $i++) {
                echo "<tr><td align='center'>" . ($i + 1) . "</td><td align='center'>" . $moon_rise_set[$i][0] . "</td><td align='center'>" . $moon_rise_set[$i][1] . "</td><td align='center'>" . $moon_phase[$i] . "</td><td align='center'>&nbsp &nbsp" . $moon_type[$i] . "&nbsp &nbsp</td></tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
            echo "&nbsp";
            echo "<div style='color: #0e356e'>Айдың салыстырмалы түрде жылдам орбиталық қозғалысына байланысты ағымдағы тәулікте тек Айдың көтерілуі (немесе батуы) болатын жағдайлар мүмкін. Бұл жағдай сәйкес бағандағы сызықша арқылы көрсетіледі.</div>";
        } elseif (($year_id and $month_id and $data_type == 2)) {
            $sun = month_sun_time_new($year_id, $month_id);
            $twi_astr = month_twi_time_astr($year_id, $month_id);
            $twi_nav = month_twi_time_nav($year_id, $month_id);
            $twi_civil = month_twi_time_civil($year_id, $month_id);
            echo "<div align='center'>";
            echo "Год: " . $year_id . ", Месяц: " . $month_id . "<br>";
            echo "<br>";
            echo "<b> Ендік: </b>" . $name_lat ."&nbsp &nbsp". " <b> Бойлық </b>" . $name_long ."&nbsp &nbsp"." <b> UTC уақыт белдеуі: </b>" . $time_sign . "<br>";
            echo "<p><b> Жергілікті уақыт бойынша күннің шығуы, батуы, ымырт (сағ:мин:сек)</b></p>";

            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'> &nbsp Күн <br>  <br> &nbsp</th>
            <th align='center'> Астрономиялық <br> ымырттың <br> басталуы </th>
            <th align='center'> Навигациялық <br> ымырттың <br> басталуы</th>
            <th align='center'> Азаматтық <br> ымырттың <br> басталуы </th>
            <th align='center'>  Күннің <br> шығуы  <br> </th>
            <th align='center'> Күннің <br> батуы <br> </th>
            <th align='center'> Күнінің <br> ұзақтығы <br> </th>
            <th align='center'>  Азаматтық <br> ымырттың <br> соңы  </th>
            <th align='center'>  Навигациялық <br> ымырттың <br> соңы </th>
            <th align='center'> Астрономиялық <br> ымырттың <br> соңы </th>

            </tr>
            </thead>
            <tbody>";
            for ($i = 0; $i < count($sun); $i++) {
                echo "<tr><td align='center'>" . ($i + 1) . "</td><td align='center'>" . $twi_astr[$i][0] . "</td><td align='center'>" . $twi_nav[$i][0] . "</td><td align='center'>" . $twi_civil[$i][0] . "</td><td align='center'>" . $sun[$i][0] . "</td><td align='center'>" . $sun[$i][1] ."</td><td align='center'>" . $sun[$i][2] . "</td><td align='center'>" . $twi_civil[$i][1] . "</td><td align='center'>" . $twi_nav[$i][1] . "</td><td align='center'>" . $twi_astr[$i][1] . "</td></tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
        } elseif (($year_id and $month_id and $data_type == 1)) {
            $g = array();
            $g = month_sid_time($year_id, $month_id);
            echo "<div align='center'>";
            echo "Жыл: " . $year_id . ", Ай: " . $month_id . "<br>";
            echo "<br>";
            echo "<b> Ендік: </b>" . $name_lat ."&nbsp &nbsp". " <b> Бойлық: </b>" . $name_long ."&nbsp &nbsp"." <b> UTC уақыт белдеуі: </b>" . $time_sign . "<br>";;
//            echo "<p><b>&nbsp &nbsp</b></p>";
            echo "<p><b> Жергілікті уақыт бойынша 00:00:00 жергілікті жұлдыздық уақыт </b></p>";
//            echo "<p><b>&nbsp &nbsp</b></p>";
            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'> &nbsp &nbsp Күн &nbsp &nbsp </th>
            <th align='center'>&nbsp &nbsp LST (сағ:мин:сек) &nbsp &nbsp </th>
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
            echo "Жыл: " . $year_id;
            echo "<p><b>UTC уақыт бойынша күн мен түннің теңелуі және күн тоқырауы</b></p>";
            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'>  Тип  </th>
            <th align='center'> &nbsp &nbsp Ай &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Күн &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Уақыт &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp  &nbsp &nbsp </th>
            </tr>
            </thead>
            <tbody>";
            echo "<tr><td align='center'>  Көктемгі күн мен түннің теңелуі: </td><td align='center'>" . $sping[1] . "</td><td align='center'>" . $sping[2] . "</td><td align='center'>" . hours_to_sep($sping[6]) ."</td><td align='center'> "."</td></tr>";
            echo "<tr><td align='center'> Жазғы күн тоқырауы: </td><td align='center'>" . $summer[1] . "</td><td align='center'>" . $summer[2] . "</td><td align='center'>" . hours_to_sep($summer[6]) ."</td><td align='center'> "."</td></tr>";
            echo "<tr><td align='center'> Күзгі күн мен түннің теңелуі: </td><td align='center'>" . $fall[1] . "</td><td align='center'>" . $fall[2] . "</td><td align='center'>" . hours_to_sep($fall[6]) . "</td><td align='center'> "."</td></tr>";
            echo "<tr><td align='center'>  Қысқы күн тоқырауы: </td><td align='center'>" . $winter[1] . "</td><td align='center'>" . $winter[2] . "</td><td align='center'>" . hours_to_sep($winter[6]) . "</td><td align='center'> "."</td></tr>";
            echo "</tbody></table>";
            echo "</div>";

            echo "<p>&nbsp &nbsp</p>";
            echo "<p align='center'><b> Жергілікті уақыт бойынша (UTC " . $time_sign . ") күн мен түннің теңелуі және күн тоқырауы</b></p>";
            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'>  Тип  </th>
            <th align='center'> &nbsp &nbsp Ай &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Күн &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Уақыт &nbsp &nbsp </th>
             <th align='center'> &nbsp &nbsp  &nbsp &nbsp </th>
            </tr>
            </thead>
            <tbody>";
            echo "<tr><td align='center'>  Көктемгі күн мен түннің теңелуі: </td><td align='center'>" . $loc_sping[1] . "</td><td align='center'>" . $loc_sping[2] . "</td><td align='center'>" . hours_to_sep($loc_sping[6]) . "</td><td align='center'> "."</td></tr>";
            echo "<tr><td align='center'> Жазғы күн тоқырауы: </td><td align='center'>" . $loc_summer[1] . "</td><td align='center'>" . $loc_summer[2] . "</td><td align='center'>" . hours_to_sep($loc_summer[6]) . "</td><td align='center'> "."</td></tr>";
            echo "<tr><td align='center'> Күзгі күн мен түннің теңелуі: </td><td align='center'>" . $loc_fall[1] . "</td><td align='center'>" . $loc_fall[2] . "</td><td align='center'>" . hours_to_sep($loc_fall[6]) . "</td><td align='center'>"."</td></tr>";
            echo "<tr><td align='center'>  Қысқы күн тоқырауы: </td><td align='center'>" . $loc_winter[1] . "</td><td align='center'>" . $loc_winter[2] . "</td><td align='center'>" . hours_to_sep($loc_winter[6]) . "</td><td align='center'> "."</td></tr>";
            echo "</tbody></table>";
            echo "</div>";
        } else {
            echo "Вы ввели некорректные данные, либо не указали год и/или месяц" . "<br>";
        }
    }
    else {

        echo "Неправильно введены цифры с картинки (каптчи). Вернитесь назад";
    }

    if(isset($_SERVER['HTTP_REFERER'])) {
        $urlback = htmlspecialchars($_SERVER['HTTP_REFERER']);
        echo "<br>";
        echo "<a href='$urlback' class='history-back'> Назад </a><br>";
    }



}