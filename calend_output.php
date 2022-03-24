<link rel='stylesheet' href='css/styles.css'>
<p class="title"><b>Астрономический календарь </b></p>
<!--<p class="title"><b>Астрономический календарь для дома пр. Ветеранов 25</b></p>-->
<?php
require "test.php";
//require "check_new.php";
//include "sun.php";
//require "sun.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    if (md5($_POST['norobot']) == $_SESSION['randomnr2']) {
        $longitude = $_POST["long"];
        if ((is_numeric($longitude) == false) or (abs($longitude) > 180)) {
            echo "Внимание!Долгота имеет некорректное значение. Введеное вами значение не является числом либо превышает 180 градусов!" . "<br>";
            echo "Если используете дробное значение, то разделителем служит точка, а не запятая!" . "<br>";
            echo "Не используйте пробелы и буквы!" . "<br>";
        }

        $latitude = $_POST["lat"];
        if ((is_numeric($latitude) == false) or (abs($latitude) > 90)) {
            echo "Внимание! Широта имеет некорректное значение. Введеное вами значение не является числом или превышает 90 градусов!" . "<br>";
            echo "Если используете дробное значение, то разделителем служит точка, а не запятая!" . "<br>";
            echo "Не используйте пробелы и буквы!" . "<br>";
        }
        $altitude = $_POST["altitude"];
        if (is_numeric($altitude) == false) {
            echo "Внимание! Высота над ур. м. имеет некорректное значение. Введеное вами значение не является числом!" . "<br>";
            echo "Если используете дробное значение, то разделителем служит точка, а не запятая!" . "<br>";
            echo "Не используйте пробелы и буквы!" . "<br>";
        }
        $time_zone = $_POST["zone"];
        if ((is_numeric($time_zone) == false) or (abs($time_zone) > 12)) {
            echo "Внимание! Часовой пояс имеет некорректное значение. Введеное вами значение не является числом или превышает 12!" . "<br>";
            echo "Если используете дробное значение, то разделителем служит точка, а не запятая!" . "<br>";
            echo "Не используйте пробелы и буквы!" . "<br>";
        }
        $year_id = (int)trim($_POST["year"]);
        if (($year_id < 0) or ($year_id > 4000) or is_numeric($year_id) == false) {
            $year_id = 0;
            echo "Внимание! Год имеет некорректное значение!" . "<br>";
            echo "Введеное вами значение не является числом! Либо выходит за рамки диапазона от 0 до 4000" . "<br>";
            echo "Не используйте пробелы и буквы!" . "<br>";
        }
        $month_id = $_POST["month"];
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

        if ($year_id and $month_id and $data_type == 4) {
            $g = array();
            $g = month_sid_time($year_id, $month_id);
            echo "<div align='center'>";
            echo "Год: " . $year_id . ", Месяц: " . $month_id . "<br>";
            echo "Широта: " . $latitude . " Долгота: " . $longitude . " Часовой пояс от UTC: " . $time_zone . "<br>";

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
            $moon_type = moon_type($year_id, $month_id);
            echo "<div align='center'>";
            echo "Год: " . $year_id . ", Месяц: " . $month_id . "<br>";
            echo "<p> Широта: " . $latitude . ", Долгота: " . $longitude . ", Часовой пояс от UTC: " . $time_zone . "</p>";
            echo "<p><b>Восход и заход Луны по местному времени (чч:мм:cc), фазы</b></p>";

            echo "<table align='center'>";
            echo "<thead>
                <tr>
                <th align='center'> &nbsp Число &nbsp </th>
                <th align='center'> &nbsp Восход Луны &nbsp </th>
                <th align='center'> &nbsp Заход Луны &nbsp </th>
                <th align='center'> &nbsp Фаза &nbsp </th>
                <th align='center'> &nbsp &nbsp Рост  &nbsp &nbsp </th>
                </tr>
            </thead>
            <tbody>";
            for ($i = 0; $i < count($moon_rise_set); $i++) {
                echo "<tr><td align='center'>" . ($i + 1) . "</td><td align='center'>" . $moon_rise_set[$i][0] . "</td><td align='center'>" . $moon_rise_set[$i][1] . "</td><td align='center'>" . $moon_phase[$i] . "</td><td align='center'>&nbsp &nbsp" . $moon_type[$i] . "&nbsp &nbsp</td></tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
        } elseif (($year_id and $month_id and $data_type == 2)) {
            $sun = month_sun_time_new($year_id, $month_id);
            $twi_astr = month_twi_time_astr($year_id, $month_id);
            $twi_nav = month_twi_time_nav($year_id, $month_id);
            $twi_civil = month_twi_time_civil($year_id, $month_id);
            echo "<div align='center'>";
            echo "Год: " . $year_id . ", Месяц: " . $month_id . "<br>";
            echo "<br>";
            echo "Широта: " . $latitude . ";" . " Долгота: " . $longitude . ";" . " Часовой пояс от UTC: " . $time_zone . ";" . "<br>";
            echo "<p><b>Восход и заход Солнца, сумерки по местному времени (чч:мм:cc)</b></p>";

            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'> &nbsp Число &nbsp</th>
            <th align='center'> Начало астр. сумерек  </th>
            <th align='center'> Начало нав. сумерек </th>
            <th align='center'> Начало гражд. сумерек </th>
            <th align='center'>  Восход Солнца   </th>
            <th align='center'> Заход Солнца  </th>
            <th align='center'>  Конец гражд. сумерек  </th>
            <th align='center'>Конец нав. сумерек </th>
            <th align='center'> Конец астр. сумерек </th>

            </tr>
            </thead>
            <tbody>";
            for ($i = 0; $i < count($sun); $i++) {
                echo "<tr><td align='center'>" . ($i + 1) . "</td><td align='center'>" . $twi_astr[$i][0] . "</td><td align='center'>" . $twi_nav[$i][0] . "</td><td align='center'>" . $twi_civil[$i][0] . "</td><td align='center'>" . $sun[$i][0] . "</td><td align='center'>" . $sun[$i][1] . "</td><td align='center'>" . $twi_civil[$i][1] . "</td><td align='center'>" . $twi_nav[$i][1] . "</td><td align='center'>" . $twi_astr[$i][1] . "</td></tr>";
            }
            echo "</tbody></table>";
            echo "</div>";
        } elseif (($year_id and $month_id and $data_type == 1)) {
            $g = array();
            $g = month_sid_time($year_id, $month_id);
            echo "<div align='center'>";
            echo "Год: " . $year_id . ", Месяц: " . $month_id . "<br>";
            echo "<p> Широта: " . $latitude . ", Долгота: " . $longitude . ", Часовой пояс от UTC: " . $time_zone . "</p>";
            echo "<p><b>&nbsp &nbsp</b></p>";
            echo "<p><b>Локальное звездное время на 00:00:00 местного времени</b></p>";
            echo "<p><b>&nbsp &nbsp</b></p>";
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
            echo "<p><b>Равноденствия и солнцестояния по всемирному времени (UTC)</b></p>";
            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'>  Тип  </th>
            <th align='center'> &nbsp &nbsp Месяц &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp День &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Время &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Погрешность &nbsp &nbsp </th>
            </tr>
            </thead>
            <tbody>";
            echo "<tr><td align='center'> Весеннее равноденствие: </td><td align='center'>" . $sping[1] . "</td><td align='center'>" . $sping[2] . "</td><td align='center'>" . hours_to_sep($sping[6]) ."</td><td align='center'> +/- 45 сек". "</td></tr>";
            echo "<tr><td align='center'> Летнее солнцестояние: </td><td align='center'>" . $summer[1] . "</td><td align='center'>" . $summer[2] . "</td><td align='center'>" . hours_to_sep($summer[6]) ."</td><td align='center'> +/- 45 сек". "</td></tr>";
            echo "<tr><td align='center'> Осеннее равноденствие: </td><td align='center'>" . $fall[1] . "</td><td align='center'>" . $fall[2] . "</td><td align='center'>" . hours_to_sep($fall[6]) . "</td><td align='center'> +/- 45 сек". "</td></tr>";
            echo "<tr><td align='center'> Зимнее солнцестояние: </td><td align='center'>" . $winter[1] . "</td><td align='center'>" . $winter[2] . "</td><td align='center'>" . hours_to_sep($winter[6]) . "</td><td align='center'> +/- 45 сек". "</td></tr>";
            echo "</tbody></table>";
            echo "</div>";

            echo "<p>&nbsp &nbsp</p>";
            echo "<p align='center'><b>Равноденствия и солнцестояния по местному (UTC +" . $time_zone . ") времени </b></p>";
            echo "<table align='center'>";
            echo "<thead>
            <tr>
            <th align='center'>  Тип  </th>
            <th align='center'> &nbsp &nbsp Месяц &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp День &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Время &nbsp &nbsp </th>
             <th align='center'> &nbsp &nbsp Погрешность &nbsp &nbsp </th>
            </tr>
            </thead>
            <tbody>";
            echo "<tr><td align='center'> Весеннее равноденствие: </td><td align='center'>" . $loc_sping[1] . "</td><td align='center'>" . $loc_sping[2] . "</td><td align='center'>" . hours_to_sep($loc_sping[6]) . "</td><td align='center'> +/- 45 сек". "</td></tr>";
            echo "<tr><td align='center'> Летнее солнцестояние: </td><td align='center'>" . $loc_summer[1] . "</td><td align='center'>" . $loc_summer[2] . "</td><td align='center'>" . hours_to_sep($loc_summer[6]) . "</td><td align='center'> +/- 45 сек". "</td></tr>";
            echo "<tr><td align='center'> Осеннее равноденствие: </td><td align='center'>" . $loc_fall[1] . "</td><td align='center'>" . $loc_fall[2] . "</td><td align='center'>" . hours_to_sep($loc_fall[6]) . "</td><td align='center'> +/- 45 сек". "</td></tr>";
            echo "<tr><td align='center'> Зимнее солнцестояние: </td><td align='center'>" . $loc_winter[1] . "</td><td align='center'>" . $loc_winter[2] . "</td><td align='center'>" . hours_to_sep($loc_winter[6]) . "</td><td align='center'> +/- 45 сек". "</td></tr>";
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
