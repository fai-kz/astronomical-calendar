<link rel='stylesheet' href='css/styles.css'>
<p class="title"><b>Астрономический календарь г. Алматы</b></p>
<?php
require "test.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $year_id = (int)trim($_POST["year"]);
    if($year_id < 1800 or $year_id > 2200){
        $year_id =  0;
    }
    $month_id = $_POST["month"];
    switch ($month_id){
        case '1': $month_id = 1;
            break;
        case '2': $month_id = 2;
            break;
        case '3': $month_id = 3;
            break;
        case '4': $month_id = 4;
            break;
        case '5': $month_id = 5;
            break;
        case '6': $month_id = 6;
            break;
        case '7': $month_id = 7;
            break;
        case '8': $month_id = 8;
            break;
        case '9': $month_id = 9;
            break;
        case '10': $month_id = 10;
            break;
        case '11': $month_id = 11;
            break;
        case '12': $month_id = 12;
            break;
        default: $month_id = 0;
            break;
    }

    $data_type = $_POST['type_data'];

    if($year_id and $month_id and $data_type == 3){
        $g = array();
        $g = month_sid_time($year_id,$month_id);
        echo "<div align='center'>";
        echo "Год: ".$year_id.", Месяц: ".$month_id;
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
        for($i = 0; $i < count($g); $i++) {
            echo "<tr><td align='center'>".($i + 1)."</td><td align='center'>".$g[$i]."</td></tr>";
        }
        echo "</tbody></table>";

        $sun = month_sun_time($year_id, $month_id);
        $twi = month_twi_time($year_id, $month_id);
        echo "<p><b>&nbsp &nbsp</b></p>";
        echo "Год: ".$year_id.", Месяц: ".$month_id;
        echo "<p><b>&nbsp &nbsp</b></p>";
        echo "<p><b>Восход и заход Солнца, сумерки по местному времени (UTC +6 ч.)</b></p>";
        echo "<p><b>&nbsp &nbsp</b></p>";
        echo "<table align='center'>";
        echo "<thead>
            <tr>
            <th align='center'>&nbsp &nbsp Число &nbsp &nbsp </th>
            <th align='center'>Начало сумерек </th>
            <th align='center'> &nbsp &nbsp Восход &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Заход &nbsp &nbsp </th>
            <th align='center'>Конец сумерек </th>
            
            </tr>
            </thead>
            <tbody>";
        for($i = 0; $i < count($sun); $i++) {
            echo "<tr><td align='center'>".($i + 1)."</td><td align='center'>".$twi[$i][0]."</td><td align='center'>".$sun[$i][0]."</td><td align='center'>".$sun[$i][1]."</td><td align='center'>".$twi[$i][1]."</td></tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    }
    elseif (($year_id and $month_id and $data_type == 2)){
        $sun = month_sun_time($year_id, $month_id);
        $twi = month_twi_time($year_id, $month_id);
        echo "<div align='center'>";
        echo "Год: ".$year_id.", Месяц: ".$month_id;
        echo "<p><b>&nbsp &nbsp</b></p>";
        echo "<p><b>Восход и заход Солнца, сумерки по местному времени (UTC +6 ч.)</b></p>";
        echo "<p><b>&nbsp &nbsp</b></p>";
        echo "<table align='center'>";
        echo "<thead>
            <tr>
            <th align='center'>&nbsp &nbsp Число &nbsp &nbsp </th>
            <th align='center'>Начало сумерек </th>
            <th align='center'> &nbsp &nbsp Восход &nbsp &nbsp </th>
            <th align='center'> &nbsp &nbsp Заход &nbsp &nbsp </th>
            <th align='center'>Конец сумерек </th>
            
            </tr>
            </thead>
            <tbody>";
        for($i = 0; $i < count($sun); $i++) {
            echo "<tr><td align='center'>".($i + 1)."</td><td align='center'>".$twi[$i][0]."</td><td align='center'>".$sun[$i][0]."</td><td align='center'>".$sun[$i][1]."</td><td align='center'>".$twi[$i][1]."</td></tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    }
    elseif (($year_id and $month_id and $data_type == 1)){
        $g = array();
        $g = month_sid_time($year_id,$month_id);
        echo "<div align='center'>";
        echo "Год: ".$year_id.", Месяц: ".$month_id;
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
        for($i = 0; $i < count($g); $i++) {
            echo "<tr><td align='center'>".($i + 1)."</td><td align='center'>".$g[$i]."</td></tr>";
        }
        echo "</tbody></table>";
        echo "</div>";
    }
    else{
        echo "Incorrect date"."<br>";
    }

    if(isset($_SERVER['HTTP_REFERER'])) {
        $urlback = htmlspecialchars($_SERVER['HTTP_REFERER']);
        echo "<a href='$urlback' class='history-back'>Back</a>";
    }


}