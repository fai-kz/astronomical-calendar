<?php

////////// Prepared by Vitaliy Kim (PhD, Senior researcher at Fesenkov Astrophysical Institute, Almaty, Kazakhstan)
/// E-mail: ursa-majoris@yandex.ru

$longitude_place_decimal_deg = 76.87; // longitude of some place in decimal degrees (in this case Almaty longitude)
$latitude_place_decimal_deg = 43.25; // latitude of some place in decimal degrees (in this case Almaty longitude)
$Time_Zone = 6; // time zone from UTC (in this case Almaty time zone)
$altitude_place = 0; // Altitude (in meters) over the sea level (in this case 0 meters)


//// Julian Date calculation /////////////////// (float)
/* This function accepts: year, month and day in integer form. It returns Julian date for an input date. */
function julian_date($year_jd, $month_jd,  $date_jd, $time_jd = "00:00:00" ):float{
    if ($month_jd == 1 or $month_jd == 2){
        $year_jd = $year_jd - 1;
        $month_jd = $month_jd +12;
    }
    $a_parameter = (int)($year_jd / 100); // amount of last centuries
    $b_parameter = 2 - $a_parameter + (int)($a_parameter / 4);
    $c_parameter = (int)(365.25 * $year_jd);
    $d_parameter = (int)(30.6001*($month_jd + 1)) ;
    $time_dec = time_dec($time_jd);
    $date_jd = $date_jd + ($time_dec / 24);
    $jd = 1720994.5 + $b_parameter + $c_parameter + $d_parameter + $date_jd;
    return $jd;
}
// Example of using julian_date function.
// Let's find Julian Date on 25 November 1987 yr.
//echo $a = julian_date(1987,11,25);


//// Leap year checking (bool)
/* This function accepts some year and returns boolean "true" if a year is leap, and boolean "false" if not */
function leap_year_check($year):bool{
    if($year % 400 == 0 or ($year % 4 == 0 and $year % 100 != 0)){
        return true;
    }
    else {
        return false;
    }
}
// Example of using leap_year_check function. Let's check 1700, 2000, 2004 years
//$b = leap_year_check(1700);
//echo "1700 ".$b."<br />";
//$c = leap_year_check(2000);
//echo "2000 ".$c."<br />";
//$d = leap_year_check(2004);
//echo "2004 ".$d."<br />";


///// Number of the day from beginning of the year (int)
/// This function calculates amount of days from beginning of the year to the concrete date.
function day_numb($year_numb, $month_numb, $date_numb):int{
    $count_day = 0;
    $year_control = leap_year_check($year_numb);
    if($year_control == true){
        $month_array = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        for ($i = 0; $i < $month_numb-1; $i++){
            $count_day = $count_day + $month_array[$i];
        }
    }
    else{
        $month_array = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        for ($i = 0; $i < $month_numb-1; $i++){
            $count_day = $count_day + $month_array[$i];
        }
    }
    $count_day = $count_day + $date_numb;
    return $count_day;
}
// Example of using day_numb function. Let's find a number of 22 april day 1980 yr. from beginning of 1980 year.
//$n_day = day_numb(1980, 4,22);
//echo $n_day;

///// Conversion a time into decimal system (float)
/* this function accepts a time in string type with ":"- separator "hh:mm:ss" and returns this time in decimal float form. */
function time_dec($time_input):float{
    $first_position_sep = mb_strpos($time_input, ':'); // To find a place of first position ":" in $time_input
    $horus = (int)mb_substr($time_input, 0, $first_position_sep); // Trim $time_input to find first digits of time (horus)
    $second_position_sep = strpos($time_input, ':', $first_position_sep + 1); // to find second input ":" in text time
    $minutes = (int)mb_substr($time_input, $first_position_sep +1, $second_position_sep - $first_position_sep -1); // To find minutes in time
    $seconds = (float)mb_substr($time_input, $second_position_sep +1, strlen($time_input) - $second_position_sep - 1);// to find seconds in time
    $total_time_decimal = $horus + ($minutes / 60) + ($seconds / 3600); // a time recalculated in decimal system
    if(is_numeric($total_time_decimal) == false){ // A check of $total_dec_time as a number
        return 0;
    }
    else{
        return $total_time_decimal;
    }
}
//Example of using time_dec function
//$time_op = "09:46:54.4";
//$time_check = time_dec($time_op);
//echo "Time check ".$time_check."<br>";

/////////////// B_const function calculates constant B, which needed for calculating a siderial time
///
function B_const($year_gst):float{
    $jd_year = julian_date($year_gst, 1, 0);
    $S = $jd_year - 2415020;
    $T = $S / 36525;
    $R = 6.6460656 + (2400.051262 * $T) + (0.00002581 * pow($T, 2));
    $U = $R - 24 * ($year_gst - 1900);
    $B = 24 - $U;
    return $B;
}

//// Greenwich (Global) siderial time (GST) (float) from a year, month, day and UTC-time
function GST($year_gst, $month_gst, $day_gst, $time_ut= "00:00:00"):float{
    $A = 0.0657098;
    $C = 1.002738;
    $days_from_yr = day_numb($year_gst, $month_gst, $day_gst);
    $T_0 = ($A * $days_from_yr) - B_const($year_gst);
    $UT_dec = time_dec($time_ut);
    $GST_dec = ($UT_dec * $C) + $T_0;
    if($GST_dec > 24){
        $GST_dec = $GST_dec  - 24;
    }
    elseif ($GST_dec < 0){
        $GST_dec = $GST_dec  + 24;
    }

    return $GST_dec;
}
// Example of using GST function
//$w = GST(2020,12,29, "13:35:40");
//echo $w;


//// Local siderial time (LST) (float) from local data: year, month, day and local time
function LST($year_lst, $month_lst, $day_lst, $time_lst= "00:00:00"):float{
    $A = 0.0657098;
    $C = 1.002738;
    global $Time_Zone;
    global $longitude_place_decimal_deg;
    $longitude_place_horus = ($longitude_place_decimal_deg/15);
    $loc_time_dec = time_dec($time_lst);
    $ut_loc_time = $loc_time_dec - $Time_Zone;
    $year_ut = $year_lst;
    $month_ut = $month_lst;
    $day_ut = $day_lst;
    $month_day_amount = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $month_day_amount_leap = array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    if($ut_loc_time < 0){
        $ut_loc_time = $ut_loc_time + 24;
        $day_ut = $day_ut - 1;
            if($day_ut < 1){
                $month_ut = $month_ut - 1;
                if(leap_year_check($year_lst)){
                    $day_ut = $month_day_amount_leap[$month_ut - 1];}
                else{$day_ut = $month_day_amount[$month_ut - 1];}

                if ($month_ut < 1){
                    $year_ut = $year_ut -1;
                    $month_ut = 12;
                    $day_ut = 31;
                }
            }
    }

    $days_from_yr = day_numb($year_ut, $month_ut, $day_ut);
    $T_0 = ($A * $days_from_yr) - B_const($year_ut);
    $UT_dec = $ut_loc_time;
    $GST_dec = ($UT_dec * $C) + $T_0;
    $LST_dec = $GST_dec + $longitude_place_horus;
    if($LST_dec > 24){
        $LST_dec = $LST_dec  - 24;
    }
    elseif($LST_dec < 0){
        $LST_dec = $LST_dec  + 24;
    }
    return $LST_dec;
}
// Example of using LST function
//$z = LST(2021,7,29, "11:33:40");
//echo $z;


///// United time (UTC) from global siderial (GST) time (float);
function UT($year_gst, $month_gst, $day_gst, $time_gst):float{
    $A = 0.0657098;
    $D = 0.997270;
    $days_from_yr = day_numb($year_gst, $month_gst, $day_gst);
    $A_days_from_yr = $A *    $days_from_yr;
    $T_0 = $A_days_from_yr - B_const($year_gst);
    if($T_0  < 0){
        $T_0 = $T_0  + 24;
    }
    $gst_dec = time_dec($time_gst);
    $gst_dec_t_0 = $gst_dec - $T_0;
    if($gst_dec_t_0 < 0){
        $gst_dec_t_0 = $gst_dec_t_0 + 24;
    }
    $UT_gst_dec =  $gst_dec_t_0 * $D;
    return $UT_gst_dec;
}

// Example of using UT function
//$ut_time = UT(2021,12,30, "13:25:00");
//echo $ut_time;


///// Local time (LT) from local siderial time (LST) (float)
function LT_ST_time($year_lst, $month_lst, $day_lst, $time_lst = "00:00:00"):float{
    $A = 0.0657098;
    $D = 0.997270;
    global $Time_Zone;
    global $longitude_place_decimal_deg;
    $longitude_place_horus = $longitude_place_decimal_deg / 15;
    $loc_sid_time_dec = time_dec($time_lst);
    //$loc_sid_time_dec = 21.518;
    $ut_sid_time = $loc_sid_time_dec - $longitude_place_horus;
    $year_ut = $year_lst;
    $month_ut = $month_lst;
    $day_ut = $day_lst;
    if($ut_sid_time < 0){
        $ut_sid_time = $ut_sid_time + 24;
        $day_ut = $day_ut - 1;
        if($day_ut < 1){
            $month_ut = $month_ut - 1;
            if ($month_ut < 1){
                $year_ut = $year_ut -1;
            }
        }
    }

    $days_from_yr = day_numb($year_ut, $month_ut, $day_ut);
    $A_days_from_yr = $A * $days_from_yr;
    $T_0 = $A_days_from_yr - B_const($year_ut);
    if($T_0  < 0){
        $T_0 = $T_0  + 24;
    }
    $gst_dec_t_0 = $ut_sid_time - $T_0;
    if($gst_dec_t_0 < 0){
        $gst_dec_t_0 = $gst_dec_t_0 + 24;
    }
    $UT_gst_dec =  $gst_dec_t_0 * $D;
    $LT_ut_gst_dec = $UT_gst_dec + $Time_Zone;
    if($LT_ut_gst_dec > 24){$LT_ut_gst_dec = $LT_ut_gst_dec -24;}
    return $LT_ut_gst_dec;
}
// Example of using LT_ST_time function
//$time_lt = LT_ST_time(2021,12,30, "11:09:29");
//echo "time_lt: ".$time_lt."<br>";


///// Local siderial time for a month as an array
function month_sid_time($year_sid, $month_sid): array
{
    $month_array_sid_time = array();
    $month_31 = array(1,3,5,7,8,10,12);
    $month_30 = array(4,6,9,11);
    $counter_days = NULL;
    if(in_array($month_sid, $month_31)){
        $counter_days = 31;
    }
    elseif(in_array($month_sid, $month_30)){
        $counter_days = 30;
    }
    elseif($month_sid == 2){
        if(leap_year_check($year_sid)){
            $counter_days = 29;
        }
        else{
            $counter_days = 28;
        }
    }
    for($i = 0; $i < $counter_days; $i++){
        $temp_sep_time = hours_to_sep(LST($year_sid, $month_sid,$i+1));
        array_push($month_array_sid_time, $temp_sep_time);
    }
    return $month_array_sid_time;
}
// Example of using month_sid_time function
//$g = array();
//$g = month_sid_time(2022,1);
//echo "day"."      "."Siderial time"."<br>";
//for($i = 0; $i < count($g); $i++){
//    echo ($i+1)."  ".$g[$i]."<br>";
//}



/// RA (right ascention) transformation from string (AA:BB:CC) to the radian system (float)
function RA_inradian($RA_obj):float{
    $bool_ra = true;
    if (substr_count($RA_obj, ':')!=2){ // Here we check a symbol ":" in input form. It must contain only 2
        $bool_ra = false;
    }
    else{
        $first_position_ra = mb_strpos($RA_obj, ':'); // To find a place of first position ":" in $RA_obj
        $first_digits_ra = mb_substr($RA_obj, 0, $first_position_ra); // Trim $obj_DEC to find first digits of ra (degrees)
        $negative_sign_check = mb_substr($RA_obj, 0, 1); // A check of negative sign "-" in DEC
        $second_position_ra = strpos($RA_obj, ':', $first_position_ra + 1); // to find second input ":" in text RA
        $second_digits_ra = mb_substr($RA_obj, $first_position_ra +1, $second_position_ra - $first_position_ra -1); // To find minutes in RA
        $third_digits_ra = mb_substr($RA_obj, $second_position_ra +1, strlen($RA_obj) - $second_position_ra - 1);// to find seconds in RA
        if($first_digits_ra > 24 or $second_digits_ra > 60 or $negative_sign_check =="-"){ // a check for correction input a RA (no more 24 horus, and minutes no more 60)
            $bool_ra = false;
        }

        $total_ra_decimal = $first_digits_ra*15 + ($second_digits_ra * 0.25) + ($third_digits_ra * 0.004); // RA recalculated in 360 degrees system
        if(is_numeric($total_ra_decimal)==false or $total_ra_decimal > 360){ // A check of $total_dec_decimal as a number
            $bool_ra = false;
        }
    }
    if($bool_ra == true){
        $ra_inradian = deg2rad($total_ra_decimal);
        return $ra_inradian;
    }
    else{
        return 0;
    }

}
// Example of using RA_inradian function
//$ra_radian = RA_inradian("04:35:55");
//echo "ra_radian: ".$ra_radian."<br>";




/// Dec transformation from string (AA:BB:CC) to the radian system (float)
function DEC_inradian($DEC_obj):float{
    $bool_dec = true;
    if (substr_count($DEC_obj, ':')!=2){ // Here we check a symbol ":" in input form. It must contain only 2
        $bool_dec = false;
    }
    else{
        $first_position_dec = mb_strpos($DEC_obj, ':'); // To find a place of first position ":" in $DEC_obj
        $first_digits_dec = (int)mb_substr($DEC_obj, 0, $first_position_dec); // Trim $DEC_obj to find first digits of dec (degrees)
        $negative_sign_check = mb_substr($DEC_obj, 0, 1); // A check of negative sign "-" in DEC
        $second_position_dec = strpos($DEC_obj, ':', $first_position_dec + 1); // to find second input ":" in text dec
        $second_digits_dec = (int)mb_substr($DEC_obj, $first_position_dec +1, $second_position_dec - $first_position_dec -1); // To find minutes in dec
        $third_digits_dec = (float)mb_substr($DEC_obj, $second_position_dec +1, strlen($DEC_obj) - $second_position_dec - 1);// to find seconds in DEC
        if($first_digits_dec > 90 or $second_digits_dec>60){ // a check for correction input a dec (no more 90 degree, and minutes no more 60)
            $bool_dec = false;
        }
        if($negative_sign_check != "-"){ // if dec is positive
            $total_dec_decimal = $first_digits_dec + ($second_digits_dec / 60) + ($third_digits_dec / 3600); // Dec in decimal system
        }
        else{ // if dec negative
            $total_dec_decimal = $first_digits_dec -($second_digits_dec / 60) - ($third_digits_dec / 3600); // Dec in decimal system
        }
        if(is_numeric($total_dec_decimal)==false){ // A check of $total_dec_decimal as a number
            $bool_dec = false;
        }
    }
    if($bool_dec == true){
        $dec_inradian = deg2rad($total_dec_decimal);
        return $dec_inradian;
    }
    else{
        return 0;
    }
}

//$dec = DEC_inradian("-09:13:24.567");
//echo "DEC_inrad".$dec."<br>";

//// transform decimal hours (float) to string type as "AA:BB:CC" (string)
function hours_to_sep($hours_dec):string{
    if($hours_dec < 0){
        $hours_dec = $hours_dec + 24;
    }
    $int_hours = (int)$hours_dec;
    $other_part1 = abs($hours_dec) - abs($int_hours);
    $other_part2 = $other_part1 * 60;
    $minutes = (int)$other_part2;
    $seconds = (int)((($other_part2 - (int)$other_part2)*60));
    if($int_hours < 10){
        $int_hours ="0".$int_hours;
    }
    if($minutes < 10){
        $minutes ="0".$minutes;
    }
    if($seconds < 10){
        $seconds ="0".$seconds;
    }
    $str_coord = $int_hours.":".$minutes.":".$seconds;
    return $str_coord;
}


//// transform degrees to str with ":" (string)
function deg_to_sep($deg_dec):string{
    $sign_null = false;
    if($deg_dec < 0 and (int)$deg_dec == 0){
        $sign_null = true;
    }
    $int_deg = (int)$deg_dec;
    $other_part1 = abs($deg_dec) - abs($int_deg);
    $other_part2 = $other_part1 * 60;
    $minutes = (int)$other_part2;
    $seconds = (int)((($other_part2 - (int)$other_part2)*60));
    if($int_deg < 10 and $int_deg  > 0){
        $int_deg ="0".$int_deg;
    }
    elseif (abs($int_deg) < 10 and $int_deg  < 0){
        $int_deg ="-0".abs($int_deg);
    }
    if($minutes < 10){
        $minutes ="0".$minutes;
    }
    if($seconds < 10){
        $seconds ="0".$seconds;
    }
    if($sign_null){
        $str_coord = "-".$int_deg.":".$minutes.":".$seconds;
    }
    else{
    $str_coord = $int_deg.":".$minutes.":".$seconds;
    }
    return $str_coord;
}
//echo "deg_to_sep ".deg_to_sep(-0.64863149912021);


//// Solar coordinates (RA, DEC)
function Solar_position ($year_sol, $month_sol, $day_sol):array{
    $n_days = julian_date($year_sol, $month_sol, $day_sol) - 2451545;
    $n_centuries = $n_days / 36525;
    $mean_longitude_deg = 280.460 + 0.9856474 * $n_days;
    $mean_anomaly_deg = 357.528 + 0.98560003 * $n_days;
    $mean_anomaly_rad = deg2rad($mean_anomaly_deg);
    $ecliptic_longitude_deg = $mean_longitude_deg + 1.915 * sin($mean_anomaly_rad) + 0.020 * sin(2*$mean_anomaly_rad);
    //echo "ecliptic_longitude_deg".$ecliptic_longitude_deg."<br>";
    $ecliptic_longitude_rad = deg2rad($ecliptic_longitude_deg);
    $ecliptic_inclination_deg = 23.43929111 - (46.8150 / 3600) * $n_centuries - ((0.00059 / 3600) * pow($n_centuries, 2)) + ((0.001813 / 3600)*pow($n_centuries, 3));
    //echo "ecliptic_inclination_deg".$ecliptic_inclination_deg."<br>";
    $ecliptic_inclination_rad = deg2rad($ecliptic_inclination_deg);
//    $RA_sun_deg = rad2deg(atan(cos($ecliptic_inclination_rad) * tan($ecliptic_longitude_rad)));
    $y = sin($ecliptic_longitude_rad) * cos($ecliptic_inclination_rad);
    $x = cos($ecliptic_longitude_rad);
    $quadrant_xy = 0;
    if($y > 0 and $x > 0){
        $quadrant_xy = 1;
    }
    if($y > 0 and $x < 0){
        $quadrant_xy = 2;
    }
    if($y < 0 and $x < 0){
        $quadrant_xy = 3;
    }
    if($y < 0 and $x > 0){
        $quadrant_xy = 4;
    }
    $RA_sun_deg = rad2deg(atan($y / $x));
    //echo "RA_sun_deg".$RA_sun_deg."<br>";
    $quadrant_ra = 0;
    if($RA_sun_deg > 0){
        if($RA_sun_deg > 0 and $RA_sun_deg < 90 ){
            $quadrant_ra = 1;
        }
        if($RA_sun_deg > 90 and $RA_sun_deg < 180){
            $quadrant_ra = 2;
        }
        if(($RA_sun_deg > 180 and $RA_sun_deg < 270)){
            $quadrant_ra = 3;
        }
        if(($RA_sun_deg > 270 and $RA_sun_deg < 360)){
            $quadrant_ra = 4;
        }
    }
    else{
        if(abs($RA_sun_deg) > 0 and abs($RA_sun_deg) < 90 ){
            $quadrant_ra = 4;
        }
        if(abs($RA_sun_deg) > 90 and abs($RA_sun_deg) < 180){
            $quadrant_ra = 3;
        }
        if((abs($RA_sun_deg) > 180 and abs($RA_sun_deg) < 270)){
            $quadrant_ra = 2;
        }
        if((abs($RA_sun_deg) > 270 and abs($RA_sun_deg) < 360)){
            $quadrant_ra = 1;
        }
    }
    //echo "quadrant_ra".$quadrant_ra."<br>";
    //echo "quadrant_xy".$quadrant_xy."<br>";
    if($RA_sun_deg > 0 and ($quadrant_ra != $quadrant_xy)){
        if($quadrant_ra == 1 and $quadrant_xy == 3){
            $RA_sun_deg = $RA_sun_deg + 180;
        }
        if($quadrant_ra == 2 and $quadrant_xy == 4){
            $RA_sun_deg = $RA_sun_deg + 180;
        }
        if($quadrant_ra == 3 and $quadrant_xy == 1){
            $RA_sun_deg = $RA_sun_deg - 180;
        }
        if($quadrant_ra == 4 and $quadrant_xy == 2){
            $RA_sun_deg = $RA_sun_deg - 180;
        }
        }
    elseif($RA_sun_deg < 0 and ($quadrant_ra != $quadrant_xy)){
        if($quadrant_ra == 1 and $quadrant_xy == 3){
            $RA_sun_deg = $RA_sun_deg - 180;
        }
        if($quadrant_ra == 2 and $quadrant_xy == 4){
            $RA_sun_deg = $RA_sun_deg - 180;
        }
        if($quadrant_ra == 3 and $quadrant_xy == 1){
            $RA_sun_deg = $RA_sun_deg + 180;
        }
        if($quadrant_ra == 4 and $quadrant_xy == 2){
            $RA_sun_deg = $RA_sun_deg + 180;
//            $test = $RA_sun_deg;
//            echo "test: ".$test."<br>";
        }
    }
    elseif ($RA_sun_deg < 0 and ($quadrant_ra == $quadrant_xy) and (($y / $x) > 0)){
        $RA_sun_deg = $RA_sun_deg + 360;
    }
    elseif ($RA_sun_deg > 0 and ($quadrant_ra == $quadrant_xy) and (($y / $x) < 0)){
        $RA_sun_deg = $RA_sun_deg - 360;
    }

//    if($RA_sun_deg < 0){
//        $RA_sun_deg = $RA_sun_deg + 360;
//    }

    $DEC_sun_deg = rad2deg(asin(sin($ecliptic_inclination_rad ) * sin($ecliptic_longitude_rad)));
    //echo "DEC_sun_deg: ".$DEC_sun_deg."<br>";
    $RA_sun_deg_sep = hours_to_sep($RA_sun_deg / 15);
    $DEC_sun_deg_sep = deg_to_sep($DEC_sun_deg);
    $solar_position_deg = array($RA_sun_deg_sep, $DEC_sun_deg_sep);
    return $solar_position_deg;
}
//$p = Solar_position(2022, 3, 16);
//echo "RA16mar ".$p[0]."<br>";
////echo "DEC16mar ".$p[1]."<br>";
//$k = Solar_position(2022, 3, 17);
//echo "RA17mar ".$k[0]."<br>";
////echo "DEC17mar ".$k[1]."<br>";
//$m = Solar_position(2022, 3, 18);
//echo "RA18mar ".$m[0]."<br>";
////echo "DEC18mar ".$m[1]."<br>";
//$k = Solar_position(2022, 3, 19);
//echo "RA19mar ".$k[0]."<br>";
////echo "DEC19mar ".$k[1]."<br>";
//$l = Solar_position(2022, 3, 20);
//echo "RA20mar ".$l[0]."<br>";
////echo "DEC20mar ".$l[1]."<br>";
//$t = Solar_position(2022, 3, 21);
//echo "RA21mar ".$t[0]."<br>";
////echo "DEC21mar ".$t[1]."<br>";
//$e = Solar_position(2022, 3, 22);
//echo "RA22mar ".$e[0]."<br>";
//echo "DEC22mar ".$e[1]."<br>";

//for($i = 0; $i < 30; $i++){
//    echo ($i+1)."    ".Solar_position(2022, 6, $i)."<br>";
//}



//// Rise and set of star from (Ra, Dec, Date) (array[string])
function Set_Rise_indate($RA_obj, $Dec_obj, $year_lst, $month_lst, $day_lst):array{
    global $latitude_place_decimal_deg;
    $latitude_place_radian = deg2rad($latitude_place_decimal_deg);
    $RA_rad = RA_inradian($RA_obj);
    $RA_hour_dec = rad2deg($RA_rad);
    $DEC_rad = DEC_inradian($Dec_obj);
    $tan_lat = tan($latitude_place_radian);
    $tan_dec = tan($DEC_rad);
    $hour_angle_rise_rad = (2*M_PI)- acos(- $tan_lat * $tan_dec);
    $hour_angle_set_rad = acos(- $tan_lat * $tan_dec);
    $hour_angle_rise_hour_dec = rad2deg($hour_angle_rise_rad);
    $hour_angle_set_hour_dec = rad2deg($hour_angle_set_rad);
    $loc_sid_time_rise_dec = $hour_angle_rise_hour_dec + $RA_hour_dec;
    if($loc_sid_time_rise_dec > 360){$loc_sid_time_rise_dec = $loc_sid_time_rise_dec - 360;}
    if($loc_sid_time_rise_dec < 0){
        $loc_sid_time_rise_dec = $loc_sid_time_rise_dec + 360;
    }
    $loc_sid_time_set_dec = $hour_angle_set_hour_dec + $RA_hour_dec;
    if($loc_sid_time_set_dec > 360){$loc_sid_time_set_dec = $loc_sid_time_set_dec - 360;}
    if($loc_sid_time_set_dec < 0){$loc_sid_time_set_dec = $loc_sid_time_set_dec + 360;
    }
    function dec_to_str($decimal){
        $int_part = (int)($decimal/15);
        $other_part1 = abs($decimal/15) - abs((int)($decimal/15));
        $other_part2 = $other_part1 * 60;
        $minutes = (int)$other_part2;
        $seconds = round((($other_part2 - (int)$other_part2)*60), 2);
        if(abs($int_part) < 10 and strpos($decimal, "-") === false){
            $int_part ="0".$int_part;
        }
        if($minutes < 10){
            $minutes ="0".$minutes;
        }
        $str_coord = $int_part.":".$minutes.":".$seconds;
        return $str_coord;
    }
    $loc_sid_time_rise_sep = dec_to_str($loc_sid_time_rise_dec);
    $loc_sid_time_set_sep = dec_to_str($loc_sid_time_set_dec);
    $local_time_rise_dec = LT_ST_time($year_lst, $month_lst, $day_lst, $loc_sid_time_rise_sep);
    $local_time_set_dec = LT_ST_time($year_lst, $month_lst, $day_lst, $loc_sid_time_set_sep);
    $local_time_rise_dec_norm = hours_to_sep($local_time_rise_dec);
    $local_time_set_dec_norm = hours_to_sep($local_time_set_dec);

    $rise_set_arr= array();
    array_push($rise_set_arr, $local_time_rise_dec_norm);
    array_push($rise_set_arr, $local_time_set_dec_norm);
    return $rise_set_arr;
}



//echo "Today: ".date('d-m-Y') . "<br>";
//echo "Position of the Sun: ". "<br>";
//$k = Solar_position(2022, 6,22);
//echo "RA: ".$k[0]."<br>";
//echo "Dec: ".$k[1]."<br>";
//
//$m = Set_Rise_indate($k[0],$k[1],date("Y"), date("m"), date("d"));
//echo "Rise of the Sun: ".$m[0]."<br>";
//echo "Set of the Sun: ".$m[1]."<br>";


//// Set and rise time of the Sun concidering disk radius, refraction and parallax
function Set_rise_sun($year_sun, $month_sun, $day_sun):array{
    global $latitude_place_decimal_deg;
    $latitude_place_radian = deg2rad($latitude_place_decimal_deg);
    $tan_lat = tan($latitude_place_radian);
    $sin_lat = sin($latitude_place_radian);
    $temp_array1 = Solar_position($year_sun, $month_sun, $day_sun);
    $RA_sun_str1 = $temp_array1[0];
    //echo "RA_sun_str1: ".$RA_sun_str1."<br>";
    $DEC_sun_str1 = $temp_array1[1];
    //echo "DEC_sun_str1: ".$DEC_sun_str1."<br>";
    $RA_sun_dec1 = time_dec($RA_sun_str1);
    //echo "RA_sun_dec1: ".$RA_sun_dec1."<br>";
    $RA_sun_dec_deg1 = $RA_sun_dec1 * 15;
    //echo "RA_sun_dec_deg1: ".$RA_sun_dec_deg1."<br>";
    $DEC_sun_rad1 = Dec_inradian($DEC_sun_str1);
    //echo "DEC_sun_rad1: ".$DEC_sun_rad1."<br>";
    $day_sun2 = $day_sun + 1;
    $temp_array2 = Solar_position($year_sun, $month_sun, $day_sun2);
    $RA_sun_str2 = $temp_array2[0];
    //echo "RA_sun_str2: ".$RA_sun_str2."<br>";
    $DEC_sun_str2 = $temp_array2[1];
    //echo "DEC_sun_str2: ".$DEC_sun_str2."<br>";
    $RA_sun_dec2 = time_dec($RA_sun_str2);
    //echo "RA_sun_dec2: ".$RA_sun_dec2."<br>";
    $RA_sun_dec_deg2 = $RA_sun_dec2 * 15;
    //echo "RA_sun_dec_deg2: ".$RA_sun_dec_deg2."<br>";
    $DEC_sun_rad2 = Dec_inradian($DEC_sun_str2);
    //echo "DEC_sun_rad2: ".$DEC_sun_rad2."<br>";
    $tan_dec1 = tan($DEC_sun_rad1);
    //echo "tan_dec1: ".$tan_dec1."<br>";
    $tan_dec2 = tan($DEC_sun_rad2);
    //echo "tan_dec2: ".$tan_dec2."<br>";
    $hour_angle_rise_rad1 = (2*M_PI)- acos(- $tan_lat * $tan_dec1);
    //echo "hour_angle_rise_rad1: ".$hour_angle_rise_rad1."<br>";
    $hour_angle_set_rad1 = acos(- $tan_lat * $tan_dec1);
    //echo "hour_angle_set_rad1: ".$hour_angle_set_rad1."<br>";
    $hour_angle_rise_rad2 = (2*M_PI)- acos(- $tan_lat * $tan_dec2);
    $hour_angle_set_rad2 = acos(- $tan_lat * $tan_dec2);
    $loc_sid_time_rise_dec1 = rad2deg($hour_angle_rise_rad1) +  $RA_sun_dec_deg1;
//    if($loc_sid_time_rise_dec1 > 360){
//        $loc_sid_time_rise_dec1  = $loc_sid_time_rise_dec1  - 360;
//    }
//    if($loc_sid_time_rise_dec1 <  0){
//        $loc_sid_time_rise_dec1  = $loc_sid_time_rise_dec1  + 360;
//    }
    //echo "loc_sid_time_rise_dec1: ".$loc_sid_time_rise_dec1."<br>";
    $loc_sid_time_rise_dec2 = rad2deg($hour_angle_rise_rad2) +  $RA_sun_dec_deg2;
//    if($loc_sid_time_rise_dec2 > 360){
//        $loc_sid_time_rise_dec2  = $loc_sid_time_rise_dec2  - 360;
//    }
//    if($loc_sid_time_rise_dec2 <  0){
//        $loc_sid_time_rise_dec2  = $loc_sid_time_rise_dec2  + 360;
//    }
    //echo "loc_sid_time_rise_dec2: ".$loc_sid_time_rise_dec2."<br>";
    $loc_sid_time_set_dec1 = rad2deg($hour_angle_set_rad1) + $RA_sun_dec_deg1;
//    if($loc_sid_time_set_dec1 > 360){
//        $loc_sid_time_set_dec1  = $loc_sid_time_set_dec1  - 360;
//    }
//    if($loc_sid_time_set_dec1 <  0){
//        $loc_sid_time_set_dec1  = $loc_sid_time_set_dec1  + 360;
//    }
    //echo "loc_sid_time_set_dec1: ".$loc_sid_time_set_dec1."<br>";
    //echo "loc_sid_time_set_dec1: ".$loc_sid_time_set_dec1."<br>";
    $loc_sid_time_set_dec2 = rad2deg($hour_angle_set_rad2) + $RA_sun_dec_deg2;
//    if($loc_sid_time_set_dec2 > 360){
//        $loc_sid_time_set_dec2  = $loc_sid_time_set_dec2  - 360;
//    }
//    if($loc_sid_time_set_dec2 <  0){
//        $loc_sid_time_set_dec2  = $loc_sid_time_set_dec2  + 360;
//    }
    //echo "loc_sid_time_set_dec2: ".$loc_sid_time_set_dec2."<br>";
    //echo "loc_sid_time_set_dec2: ".$loc_sid_time_set_dec2."<br>";
    ///// Corrections (refraction, disk, parallax)
    $mid_DEC_rad = ($DEC_sun_rad1 + $DEC_sun_rad2) / 2;
    $psi_refraction_rad = acos(($sin_lat / cos($mid_DEC_rad)));
    $x_disc_refr_sin = sin(deg2rad(0.835608));
    $y_way_rad = asin($x_disc_refr_sin / sin($psi_refraction_rad));
    $delta_t_sec = 240 * rad2deg($y_way_rad) / cos($mid_DEC_rad);
    $delta_t_hours = $delta_t_sec / 3600;
    ///////////////////////////////

    $loc_sid_time_rise1 = ($loc_sid_time_rise_dec1 / 15) - $delta_t_hours;
    //echo "loc_sid_time_rise1: ".$loc_sid_time_rise1."<br>";
    $loc_sid_time_rise2 = ($loc_sid_time_rise_dec2 / 15) - $delta_t_hours;
    //echo "loc_sid_time_rise2: ".$loc_sid_time_rise2."<br>";
    $loc_sid_time_set1 = ($loc_sid_time_set_dec1 / 15) + $delta_t_hours;
    //echo "loc_sid_time_set1 : ".$loc_sid_time_set1."<br>";
    $loc_sid_time_set2 = ($loc_sid_time_set_dec2 / 15) + $delta_t_hours;
    //echo "loc_sid_time_set2 : ".$loc_sid_time_set2."<br>";
    if(abs($loc_sid_time_rise1 - $loc_sid_time_rise2) > 2 ){
        $loc_sid_time_rise2 = $loc_sid_time_rise1;
    }
    if(abs( $loc_sid_time_set1 -  $loc_sid_time_set2)>2){
        $loc_sid_time_set1 = $loc_sid_time_set2;
    }
    $local_sid_time_rise_interp = (24.07 * $loc_sid_time_rise1) / (24.07 + $loc_sid_time_rise1 - $loc_sid_time_rise2);
    $day_sun_rise = $day_sun;
    if($local_sid_time_rise_interp > 24){
        $local_sid_time_rise_interp = $local_sid_time_rise_interp - 24;
        $day_sun_rise = $day_sun + 1;
    }
    //echo "local_sid_time_rise_interp: ".$local_sid_time_rise_interp."<br>";
    $local_sid_time_set_interp = (24.07 * $loc_sid_time_set1) / (24.07 + $loc_sid_time_set1 - $loc_sid_time_set2);
    //echo "local_sid_time_set_interp : ".$local_sid_time_set_interp."<br>";
    $day_sun_set =  $day_sun;
    if($local_sid_time_set_interp > 24){
        $local_sid_time_set_interp = $local_sid_time_set_interp - 24;
        $day_sun_set =  $day_sun + 1;
    }

    //echo "local_sid_time_set_interp: ".$local_sid_time_set_interp."<br>";
    $local_sid_time_rise_interp_sep = hours_to_sep($local_sid_time_rise_interp);
    //echo "local_sid_time_rise_interp_sep: ".$local_sid_time_rise_interp_sep."<br>";
    $local_sid_time_set_interp_sep = hours_to_sep($local_sid_time_set_interp);
    //echo "local_sid_time_set_interp_sep: ".$local_sid_time_set_interp_sep."<br>";
    //echo "local_sid_time_set_interp_sep: ".$local_sid_time_set_interp_sep."<br>";
    $local_time_rise = hours_to_sep(LT_ST_time($year_sun, $month_sun, $day_sun_rise, $local_sid_time_rise_interp_sep));
    //echo "local_time_rise: ".$local_time_rise."<br>";
    $local_time_set = hours_to_sep(LT_ST_time($year_sun, $month_sun, $day_sun_set, $local_sid_time_set_interp_sep));
    //echo "local_time_set: ".$local_time_set."<br>";


    $rise_set_arr = array($local_time_rise, $local_time_set);

    return $rise_set_arr;
}

//$temp1 = Set_rise_sun(2023,3,16);
//echo "Rise of the Sun: ".$temp1[0]."<br>";
//echo "Set of the Sun: ".$temp1[1]."<br>";

function twilights_astr($year_twi, $month_twi, $day_twi):array{
    global $latitude_place_decimal_deg;
    $latitude_place_radian = deg2rad($latitude_place_decimal_deg);
    $tan_lat = tan($latitude_place_radian);
    $temp_array1 = Solar_position($year_twi, $month_twi, $day_twi);
    $temp_array2 = Solar_position($year_twi, $month_twi, $day_twi + 1);
    $DEC_rise_str = $temp_array1[1];
    $DEC_set_str = $temp_array2[1];
    $DEC_rise_rad = DEC_inradian($DEC_rise_str);
    $DEC_set_rad = DEC_inradian($DEC_set_str);
    $hour_angle_rise_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_rise_rad)));
    $hour_angle_set_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_set_rad)));
    $hour_angle_rise_finish_deg = rad2deg(acos((cos(deg2rad(108)) - sin($latitude_place_radian) * sin($DEC_rise_rad)) / (cos($latitude_place_radian) * cos($DEC_rise_rad))));
    $hour_angle_set_finish_deg = rad2deg(acos((cos(deg2rad(108)) - sin($latitude_place_radian) * sin($DEC_set_rad)) / (cos($latitude_place_radian) * cos($DEC_set_rad))));
    $continuum_tw_rise_hour = ($hour_angle_rise_finish_deg - $hour_angle_rise_start_deg) / 15;
    $continuum_tw_set_hour = ($hour_angle_set_finish_deg - $hour_angle_set_start_deg) / 15;
    $temp_array3 = Set_rise_sun($year_twi, $month_twi, $day_twi);
    $twi_before_rise_dec = time_dec($temp_array3[0]) - $continuum_tw_rise_hour;
    $twi_after_set_dec = time_dec($temp_array3[1]) + $continuum_tw_set_hour;
    $twi_before_rise_sep = hours_to_sep($twi_before_rise_dec);
    $twi_after_set_sep = hours_to_sep($twi_after_set_dec);
    $twilights_astr_arr = array($twi_before_rise_sep, $twi_after_set_sep);
    return $twilights_astr_arr;
}
//
//$temp = twinlights(2022,1,6);
//echo "Rise twinlights: ".$temp[0]."<br>";
//echo "Set twinlights: ".$temp[1]."<br>";

function month_sun_time($year_sun, $month_sun): array
{
    $month_array_sun_time = array();
    $month_31 = array(1,3,5,7,8,10,12);
    $month_30 = array(4,6,9,11);
    $counter_days = 0;
    if(in_array($month_sun, $month_31)){
        $counter_days = 31;
    }
    elseif(in_array($month_sun, $month_30)){
        $counter_days = 30;
    }
    elseif($month_sun == 2){
        if(leap_year_check($year_sun)){
            $counter_days = 29;
        }
        else{
            $counter_days = 28;
        }
    }
    for($i = 0; $i < $counter_days; $i++){
        $temp_sun_time = Set_rise_sun($year_sun, $month_sun, $i+1);
        array_push($month_array_sun_time, $temp_sun_time);
    }
    return $month_array_sun_time;
}


//$sun_temp = month_sun_time(2021,1);
//echo "Rise: ".$sun_temp[0][0]."<br>";
//echo "Set: ".$sun_temp[0][1]."<br>";

function month_twi_time_astr($year_twi, $month_twi): array
{
    $month_array_twi_time = array();
    $month_31 = array(1,3,5,7,8,10,12);
    $month_30 = array(4,6,9,11);
    $counter_days = 0;
    if(in_array($month_twi, $month_31)){
        $counter_days = 31;
    }
    elseif(in_array($month_twi, $month_30)){
        $counter_days = 30;
    }
    elseif($month_twi == 2){
        if(leap_year_check($year_twi)){
            $counter_days = 29;
        }
        else{
            $counter_days = 28;
        }
    }
    for($i = 0; $i < $counter_days; $i++){
        $temp_twi_time = twilights_astr($year_twi, $month_twi, $i+1);
        array_push($month_array_twi_time, $temp_twi_time);
    }
    return $month_array_twi_time;
}

function twilights_nav($year_nav, $month_nav, $day_nav):array{
    global $latitude_place_decimal_deg;
    $latitude_place_radian = deg2rad($latitude_place_decimal_deg);
    $tan_lat = tan($latitude_place_radian);
    $temp_array1 = Solar_position($year_nav, $month_nav, $day_nav);
    $temp_array2 = Solar_position($year_nav, $month_nav, $day_nav + 1);
    $DEC_rise_str = $temp_array1[1];
    $DEC_set_str = $temp_array2[1];
    $DEC_rise_rad = DEC_inradian($DEC_rise_str);
    $DEC_set_rad = DEC_inradian($DEC_set_str);
    $hour_angle_rise_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_rise_rad)));
    $hour_angle_set_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_set_rad)));
    $hour_angle_rise_finish_deg = rad2deg(acos((cos(deg2rad(102)) - sin($latitude_place_radian) * sin($DEC_rise_rad)) / (cos($latitude_place_radian) * cos($DEC_rise_rad))));
    $hour_angle_set_finish_deg = rad2deg(acos((cos(deg2rad(102)) - sin($latitude_place_radian) * sin($DEC_set_rad)) / (cos($latitude_place_radian) * cos($DEC_set_rad))));
    $continuum_tw_rise_hour = ($hour_angle_rise_finish_deg - $hour_angle_rise_start_deg) / 15;
    $continuum_tw_set_hour = ($hour_angle_set_finish_deg - $hour_angle_set_start_deg) / 15;
    $temp_array3 = Set_rise_sun($year_nav, $month_nav, $day_nav);
    $twi_before_rise_dec = time_dec($temp_array3[0]) - $continuum_tw_rise_hour;
    $twi_after_set_dec = time_dec($temp_array3[1]) + $continuum_tw_set_hour;
    $twi_before_rise_sep = hours_to_sep($twi_before_rise_dec);
    $twi_after_set_sep = hours_to_sep($twi_after_set_dec);
    $twilights_nav_arr = array($twi_before_rise_sep, $twi_after_set_sep);
    return $twilights_nav_arr;
}

function twilights_civil($year_civil, $month_civil, $day_civil):array{
    global $latitude_place_decimal_deg;
    $latitude_place_radian = deg2rad($latitude_place_decimal_deg);
    $tan_lat = tan($latitude_place_radian);
    $temp_array1 = Solar_position($year_civil, $month_civil, $day_civil);
    $temp_array2 = Solar_position($year_civil, $month_civil, $day_civil + 1);
    $DEC_rise_str = $temp_array1[1];
    $DEC_set_str = $temp_array2[1];
    $DEC_rise_rad = DEC_inradian($DEC_rise_str);
    $DEC_set_rad = DEC_inradian($DEC_set_str);
    $hour_angle_rise_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_rise_rad)));
    $hour_angle_set_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_set_rad)));
    $hour_angle_rise_finish_deg = rad2deg(acos((cos(deg2rad(96)) - sin($latitude_place_radian) * sin($DEC_rise_rad)) / (cos($latitude_place_radian) * cos($DEC_rise_rad))));
    $hour_angle_set_finish_deg = rad2deg(acos((cos(deg2rad(96)) - sin($latitude_place_radian) * sin($DEC_set_rad)) / (cos($latitude_place_radian) * cos($DEC_set_rad))));
    $continuum_tw_rise_hour = ($hour_angle_rise_finish_deg - $hour_angle_rise_start_deg) / 15;
    $continuum_tw_set_hour = ($hour_angle_set_finish_deg - $hour_angle_set_start_deg) / 15;
    $temp_array3 = Set_rise_sun($year_civil, $month_civil, $day_civil);
    $twi_before_rise_dec = time_dec($temp_array3[0]) - $continuum_tw_rise_hour;
    $twi_after_set_dec = time_dec($temp_array3[1]) + $continuum_tw_set_hour;
    $twi_before_rise_sep = hours_to_sep($twi_before_rise_dec);
    $twi_after_set_sep = hours_to_sep($twi_after_set_dec);
    $twilights_civil_arr = array($twi_before_rise_sep, $twi_after_set_sep);
    return $twilights_civil_arr;
}

function month_twi_time_nav($year_twi, $month_twi): array
{
    $month_array_twi_time_nav = array();
    $month_31 = array(1,3,5,7,8,10,12);
    $month_30 = array(4,6,9,11);
    $counter_days = 0;
    if(in_array($month_twi, $month_31)){
        $counter_days = 31;
    }
    elseif(in_array($month_twi, $month_30)){
        $counter_days = 30;
    }
    elseif($month_twi == 2){
        if(leap_year_check($year_twi)){
            $counter_days = 29;
        }
        else{
            $counter_days = 28;
        }
    }
    for($i = 0; $i < $counter_days; $i++){
        $temp_twi_time = twilights_nav($year_twi, $month_twi, $i+1);
        array_push($month_array_twi_time_nav, $temp_twi_time);
    }
    return $month_array_twi_time_nav;
}

function month_twi_time_civil($year_twi, $month_twi): array
{
    $month_array_twi_time_civil = array();
    $month_31 = array(1,3,5,7,8,10,12);
    $month_30 = array(4,6,9,11);
    $counter_days = 0;
    if(in_array($month_twi, $month_31)){
        $counter_days = 31;
    }
    elseif(in_array($month_twi, $month_30)){
        $counter_days = 30;
    }
    elseif($month_twi == 2){
        if(leap_year_check($year_twi)){
            $counter_days = 29;
        }
        else{
            $counter_days = 28;
        }
    }
    for($i = 0; $i < $counter_days; $i++){
        $temp_twi_time = twilights_civil($year_twi, $month_twi, $i+1);
        array_push($month_array_twi_time_civil, $temp_twi_time);
    }
    return $month_array_twi_time_civil;
}

////// Moon position

function Moon_position($year_moon, $month_moon, $day_moon, $time_ut = "00:00:00"){
    /// Constants on 00 Jan 2010///
    $basis_jd_date = 2455196.5;
    $mean_longitude_moon_basis_deg =  91.929336;
    $mean_longitude_peregee_basis_deg = 130.143076;
    $mean_longitude_asc_node_basis_deg = 291.682547;
    $orbit_inclinat_deg = 5.145396;

    //// longitudes calculation
    $delta_days = julian_date($year_moon, $month_moon, $day_moon, $time_ut)  - $basis_jd_date;
    //echo julian_date($year_moon, $month_moon, $day_moon)."<br>";
    //echo time_dec($time_ut)."<br>";
    $mean_longitude_moon_indate_deg = 13.1763966 * $delta_days + $mean_longitude_moon_basis_deg;
    $mean_moon_anomaly_indate_deg = $mean_longitude_moon_indate_deg - 0.1114041 * $delta_days - $mean_longitude_peregee_basis_deg;
    $mean_asc_node_longitude_indate_deg = $mean_longitude_asc_node_basis_deg - 0.0529539 * $delta_days;

    ///// Corrections

    /// Solar parameters
    $n_days = julian_date($year_moon, $month_moon, $day_moon, $time_ut) - 2451545;
    $n_centuries = $n_days / 36525;
    $mean_longitude_sun_deg = 280.460 + 0.9856474 * $n_days;
    $mean_anomaly_sun_deg = 357.528 + 0.98560003 * $n_days;
    $mean_anomaly_sun_rad = deg2rad($mean_anomaly_sun_deg);
    $ecliptic_longitude_sun_deg = $mean_longitude_sun_deg + 1.915 * sin($mean_anomaly_sun_rad) + 0.020 * sin(2*$mean_anomaly_sun_rad);

    //// Correction 1: evection calculation
    $c = $mean_longitude_moon_indate_deg - $ecliptic_longitude_sun_deg;
    $evect = 1.2739 * sin(deg2rad(2 * $c - $mean_moon_anomaly_indate_deg));

    //// Correction 2 and 3: annual equation and third correction
    $ann_eq = 0.1858 * sin($mean_anomaly_sun_rad);
    $ann_3 = 0.37 * sin($mean_anomaly_sun_rad);

    //// Corrected moon anomaly
    $mean_moon_anomaly_indate_deg_corr = $mean_moon_anomaly_indate_deg + $evect - $ann_eq - $ann_3;

    //// Center correction and fourth correction
    $cent_corr = 6.2886 * sin(deg2rad($mean_moon_anomaly_indate_deg_corr));
    $ann_4 = 0.214 * sin(deg2rad(2 * $mean_moon_anomaly_indate_deg_corr));

    //// Corrected mean longitude of the Moon
    $mean_longitude_moon_deg_cor = $mean_longitude_moon_indate_deg + $evect + $cent_corr - $ann_eq + $ann_4;

    //// Variation
    $variation = 0.6583 * sin(deg2rad(2*($mean_longitude_moon_deg_cor - $ecliptic_longitude_sun_deg )));

    //// Corrected ascending node longitude
    $mean_asc_node_longitude_cor_deg = $mean_asc_node_longitude_indate_deg - 0.16 * sin($mean_anomaly_sun_rad);

    //// True Moon orbital longitude
    $true_moon_orb_longitude_deg = $mean_longitude_moon_deg_cor + $variation;

    //// Ecliptic coordinates calculation for the Moon
    $y = sin(deg2rad($true_moon_orb_longitude_deg - $mean_asc_node_longitude_cor_deg)) * cos(deg2rad($orbit_inclinat_deg));
    $x = cos(deg2rad($true_moon_orb_longitude_deg - $mean_asc_node_longitude_cor_deg));
    $quadrant_xy = 0;
    if($y > 0 and $x > 0){
        $quadrant_xy = 1;
    }
    if($y > 0 and $x < 0){
        $quadrant_xy = 2;
    }
    if($y < 0 and $x < 0){
        $quadrant_xy = 3;
    }
    if($y < 0 and $x > 0){
        $quadrant_xy = 4;
    }

    $moon_ecl_longitude_deg = rad2deg(atan($y / $x));

    $quadrant_ecl = 0;
    if($moon_ecl_longitude_deg > 0){
        if($moon_ecl_longitude_deg > 0 and $moon_ecl_longitude_deg < 90 ){
            $quadrant_ecl = 1;
        }
        if($moon_ecl_longitude_deg > 90 and $moon_ecl_longitude_deg < 180){
            $quadrant_ecl = 2;
        }
        if(($moon_ecl_longitude_deg > 180 and $moon_ecl_longitude_deg < 270)){
            $quadrant_ecl = 3;
        }
        if(($moon_ecl_longitude_deg > 270 and $moon_ecl_longitude_deg < 360)){
            $quadrant_ecl = 4;
        }
    }
    else{
        if(abs($moon_ecl_longitude_deg) > 0 and abs($moon_ecl_longitude_deg) < 90 ){
            $quadrant_ecl = 4;
        }
        if(abs($moon_ecl_longitude_deg) > 90 and abs($moon_ecl_longitude_deg) < 180){
            $quadrant_ecl = 3;
        }
        if((abs($moon_ecl_longitude_deg) > 180 and abs($moon_ecl_longitude_deg) < 270)){
            $quadrant_ecl = 2;
        }
        if((abs($moon_ecl_longitude_deg) > 270 and abs($moon_ecl_longitude_deg) < 360)){
            $quadrant_ecl = 1;
        }
    }

    if($moon_ecl_longitude_deg > 0 and ($quadrant_ecl != $quadrant_xy)){
        if($quadrant_ecl == 1 and $quadrant_xy == 3){
            $moon_ecl_longitude_deg = $moon_ecl_longitude_deg + 180;
        }
        if($quadrant_ecl == 2 and $quadrant_xy == 4){
            $moon_ecl_longitude_deg = $moon_ecl_longitude_deg + 180;
        }
        if($quadrant_ecl == 3 and $quadrant_xy == 1){
            $moon_ecl_longitude_deg = $moon_ecl_longitude_deg - 180;
        }
        if($quadrant_ecl == 4 and $quadrant_xy == 2){
            $moon_ecl_longitude_deg = $moon_ecl_longitude_deg - 180;
        }
    }
    elseif($moon_ecl_longitude_deg < 0 and ($quadrant_ecl != $quadrant_xy)){
        if($quadrant_ecl == 1 and $quadrant_xy == 3){
            $moon_ecl_longitude_deg = $moon_ecl_longitude_deg - 180;
        }
        if($quadrant_ecl == 2 and $quadrant_xy == 4){
            $moon_ecl_longitude_deg = $moon_ecl_longitude_deg - 180;
        }
        if($quadrant_ecl == 3 and $quadrant_xy == 1){
            $moon_ecl_longitude_deg = $moon_ecl_longitude_deg + 180;
        }
        if($quadrant_ecl == 4 and $quadrant_xy == 2){
            $moon_ecl_longitude_deg = $moon_ecl_longitude_deg + 180;
        }
    }
    elseif ($moon_ecl_longitude_deg < 0 and ($quadrant_ecl == $quadrant_xy) and (($y / $x) > 0)){
        $moon_ecl_longitude_deg = $moon_ecl_longitude_deg + 360;
    }
    elseif ($moon_ecl_longitude_deg > 0 and ($quadrant_ecl == $quadrant_xy) and (($y / $x) < 0)){
        $moon_ecl_longitude_deg = $moon_ecl_longitude_deg - 360;
    }
    ////// Moon ecliptic coordinates
    $moon_ecl_longitude_cor_deg = $moon_ecl_longitude_deg + $mean_asc_node_longitude_cor_deg;
    $moon_ecl_latitude_deg = rad2deg(asin(sin(deg2rad($true_moon_orb_longitude_deg - $mean_asc_node_longitude_cor_deg)) * sin(deg2rad($orbit_inclinat_deg))));

    ///// Moon RA and DEC coordinates from ecliptic coordinates
    /// Right ascention
    $ecliptic_inclination_deg = 23.43929111 - (46.8150 / 3600) * $n_centuries - ((0.00059 / 3600) * pow($n_centuries, 2)) + ((0.001813 / 3600)*pow($n_centuries, 3));
    $y1 = sin(deg2rad($moon_ecl_longitude_cor_deg)) * cos(deg2rad($ecliptic_inclination_deg)) - tan(deg2rad($moon_ecl_latitude_deg)) * sin(deg2rad($ecliptic_inclination_deg));
    $x1 = cos(deg2rad($moon_ecl_longitude_cor_deg));
    //echo "y1: ".$y1."<br>";
    //echo "x1: ".$x1."<br>";
    $quadrant_xy1 = 0;
    if($y1 > 0 and $x1 > 0){
        $quadrant_xy1 = 1;
    }
    if($y1 > 0 and $x1 < 0){
        $quadrant_xy1 = 2;
    }
    if($y1 < 0 and $x1 < 0){
        $quadrant_xy1 = 3;
    }
    if($y1 < 0 and $x1 > 0){
        $quadrant_xy1 = 4;
    }

    $RA_moon_deg = rad2deg(atan(($y1 / $x1)));
    $quadrant_ra = 0;

    if($RA_moon_deg > 0){
        if($RA_moon_deg > 0 and $RA_moon_deg < 90 ){
            $quadrant_ra = 1;
        }
        if($RA_moon_deg > 90 and $RA_moon_deg < 180){
            $quadrant_ra = 2;
        }
        if(($RA_moon_deg > 180 and $RA_moon_deg < 270)){
            $quadrant_ra = 3;
        }
        if(($RA_moon_deg > 270 and $RA_moon_deg < 360)){
            $quadrant_ra = 4;
        }
    }
    else{
        if(abs($RA_moon_deg) > 0 and abs($RA_moon_deg) < 90 ){
            $quadrant_ra = 4;
        }
        if(abs($RA_moon_deg) > 90 and abs($RA_moon_deg) < 180){
            $quadrant_ra = 3;
        }
        if((abs($RA_moon_deg) > 180 and abs($RA_moon_deg) < 270)){
            $quadrant_ra = 2;
        }
        if((abs($RA_moon_deg) > 270 and abs($RA_moon_deg) < 360)){
            $quadrant_ra = 1;
        }
    }

    if($RA_moon_deg > 0 and ($quadrant_ra != $quadrant_xy1)){
        if($quadrant_ra == 1 and $quadrant_xy1 == 3){
            $RA_moon_deg = $RA_moon_deg + 180;
        }
        if($quadrant_ra == 2 and $quadrant_xy1 == 4){
            $RA_moon_deg = $RA_moon_deg + 180;
        }
        if($quadrant_ra == 3 and $quadrant_xy1 == 1){
            $RA_moon_deg = $RA_moon_deg - 180;
        }
        if($quadrant_ra == 4 and $quadrant_xy1 == 2){
            $RA_moon_deg = $RA_moon_deg - 180;
        }
    }
    elseif($RA_moon_deg < 0 and ($quadrant_ra != $quadrant_xy1)){
        if($quadrant_ra == 1 and $quadrant_xy1 == 3){
            $RA_moon_deg = $RA_moon_deg - 180;
        }
        if($quadrant_ra == 2 and $quadrant_xy1 == 4){
            $RA_moon_deg = $RA_moon_deg - 180;
        }
        if($quadrant_ra == 3 and $quadrant_xy1 == 1){
            $RA_moon_deg = $RA_moon_deg + 180;
        }
        if($quadrant_ra == 4 and $quadrant_xy1 == 2){
            $RA_moon_deg = $RA_moon_deg + 180;
        }
    }
    elseif ($RA_moon_deg < 0 and ($quadrant_ra == $quadrant_xy1) and (($y1 / $x1) > 0)){
        $RA_moon_deg = $RA_moon_deg + 360;
    }
    elseif ($RA_moon_deg > 0 and ($quadrant_ra == $quadrant_xy1) and (($y1 / $x1) < 0)){
        $RA_moon_deg = $RA_moon_deg - 360;
    }
    //echo "quadrant_ra ".$quadrant_ra."<br>";
    //echo " quadrant_xy1 ".$quadrant_xy1."<br>";

    $RA_moon_hour_dec = $RA_moon_deg / 15;
    if($RA_moon_hour_dec > 24){
        $RA_moon_hour_dec = $RA_moon_hour_dec - 24;
    }
    elseif ($RA_moon_hour_dec < 0){
        $RA_moon_hour_dec = $RA_moon_hour_dec + 24;
    }

    //// Declination
    $Dec_moon_deg = rad2deg(asin(sin(deg2rad($moon_ecl_latitude_deg)) * cos(deg2rad($ecliptic_inclination_deg)) + cos(deg2rad($moon_ecl_latitude_deg)) * sin(deg2rad($ecliptic_inclination_deg)) * sin(deg2rad($moon_ecl_longitude_cor_deg))));

    //echo "moon_ecl_longitude_cor_deg: ".$moon_ecl_longitude_cor_deg."<br>";
    //echo "moon_ecl_latitude_deg: ".$moon_ecl_latitude_deg."<br>";
    $moon_ecl_coord_deg_dec = array($moon_ecl_longitude_cor_deg, $moon_ecl_latitude_deg, $ecliptic_longitude_sun_deg, $true_moon_orb_longitude_deg, $mean_moon_anomaly_indate_deg_corr, $cent_corr, $RA_moon_hour_dec, $Dec_moon_deg);

    return $moon_ecl_coord_deg_dec;
}
/*
 the function returns an array
[0] - Moon ecliptic longitude in degrees ($moon_ecl_longitude_cor_deg)
[1] - Moon ecliptic latitude in degrees ($moon_ecl_latitude_deg)
[2] - Sun ecliptic longitude in degrees ($ecliptic_longitude_sun_deg)
[3] - Moon true orbital longitude in degrees ($true_moon_orb_longitude_deg)
[4] - Moon mean anomaly in degrees ($mean_moon_anomaly_indate_deg_corr)
[5] - Equation of the center ($cent_corr)
[6] - Right ascension of the Moon in decimal hours ($RA_moon_hour_dec)
[7] - Moon declination in degrees ($Dec_moon_deg)
*/
//$moon_pos = Moon_position(2003,9,1);
//echo "RA: ".$moon_pos[6]."<br>";
//echo "DEC: ".$moon_pos[7]."<br>";
//echo "lambda: ".($moon_pos[0] - 360)."<br>";
//echo "betta: ".$moon_pos[1]."<br>";



//// Moon phases
function Moon_phase($year_ph, $month_ph, $day_ph, $time_ut = "00:00:00"):float{
    $temp_array1 = Moon_position($year_ph, $month_ph, $day_ph, $time_ut);
    $Moon_age_deg = $temp_array1[3] - $temp_array1[2];
    $Phase_moon = round(((1 - cos(deg2rad($Moon_age_deg))) / 2), 2);
    return $Phase_moon;
}

//$a = Moon_phase(2003,9,1);
//echo "Phase of Moon: ".$a."<br>";

// Distance to the Moon in semi-major axis units, angular size in degrees and horizontal parallax in degrees
function Moon_parameters($year_dist, $month_dist, $day_dist, $ut_time = "00:00:00"):array{
    $temp_array = Moon_position($year_dist, $month_dist, $day_dist, $ut_time);
    $eccentricity_moon_orb = 0.0549006;
    $angular_size_moon_a_deg = 0.5181;
    $parallax_moon_a_deg = 0.9507;
    $moon_dist = (1 - pow($eccentricity_moon_orb, 2))/(1 + $eccentricity_moon_orb * cos(deg2rad($temp_array[4]) + deg2rad($temp_array[5])));
    $angular_size_moon_indate_deg = $angular_size_moon_a_deg / $moon_dist;
    $parallax_moon_indate_deg = $parallax_moon_a_deg / $moon_dist;
    $moon_param_array = array($moon_dist, $angular_size_moon_indate_deg, $parallax_moon_indate_deg);
    return $moon_param_array;
}

//$temp = Moon_parameters(1979, 9, 6);
//echo "moon_dist: ".$temp[0]."<br>";
//echo "angular_size_moon_indate_deg: ".$temp[1]."<br>";
//echo "parallax_moon_indate_deg: ".$temp[2]."<br>";

function geocentric_parallax_moon ($RA_dec, $DEC_dec, $year_p, $month_p, $day_p, $gmt_time = "00:00:00"):array{
    global $altitude_place;
    global $latitude_place_decimal_deg;
    $moon_parameters = Moon_parameters($year_p, $month_p, $day_p, $gmt_time);
    $horiz_parallax_moon_deg = $moon_parameters[2];
    $r_dist = 1 / sin(deg2rad($horiz_parallax_moon_deg));
    $loc_sid_time = LST($year_p, $month_p, $day_p, $gmt_time);
    $hour_angle_hour = $loc_sid_time - $RA_dec;
    $hour_angle_deg = $hour_angle_hour * 15;
    $u_rad = atan(0.996647 * tan(deg2rad($latitude_place_decimal_deg)));
    $related_altitude = $altitude_place / 6378140;
    $ro_sin_fi_cor = 0.996647 * sin($u_rad) + $related_altitude * sin(deg2rad($latitude_place_decimal_deg));
    $ro_cos_fi_cor = cos($u_rad) + $related_altitude * cos(deg2rad($latitude_place_decimal_deg));
    $y1 = $ro_cos_fi_cor * sin(deg2rad($hour_angle_deg));
    $x1 = $r_dist * cos(deg2rad($DEC_dec)) - $ro_cos_fi_cor * cos(deg2rad($hour_angle_deg));
    $correction_RA_deg = rad2deg(atan($y1 / $x1));
    $hour_angl_corr_deg = $hour_angle_deg + $correction_RA_deg;
    $RA_corr_hour = $RA_dec - ($correction_RA_deg / 15);
    if($RA_corr_hour > 24){
        $RA_corr_hour = $RA_corr_hour - 24;
    }
    elseif ($RA_corr_hour < 0){
        $RA_corr_hour = $RA_corr_hour + 24;
    }
    $y2 = cos(deg2rad($hour_angl_corr_deg)) * ($r_dist * sin(deg2rad($DEC_dec)) - $ro_sin_fi_cor);
    $x2 = $r_dist * cos(deg2rad($DEC_dec)) * cos(deg2rad($hour_angle_deg)) - $ro_cos_fi_cor;
    $DEC_corr_deg = rad2deg(atan($y2 / $x2));
    $corrected_ra_dec_moon = array($RA_corr_hour, $DEC_corr_deg);
    return $corrected_ra_dec_moon;
}
$temp_11 = Moon_position(2022,3,30,"05:00:00");
//echo "lon: ".$temp_11[0]."<br>";
//echo "lat: ".$temp_11[1]."<br>";
//echo "RA: ".$temp_11[6]."<br>";
//echo "DEC: ".$temp_11[7]."<br>";
//$temp_21 = geocentric_parallax_moon($temp_11[6], $temp_11[7], 2022,1,14);
//echo "RA_corr: ".$temp_21[0]."<br>";
//echo "DEC_corr: ".$temp_21[1]."<br>";

//// Set and rise time of the Moon
function Set_rise_moon($year_m, $month_m, $day_m){
    global $latitude_place_decimal_deg;
    $temp_array1 = Moon_position($year_m, $month_m, $day_m);
    $temp_array2 = Moon_position($year_m, $month_m, $day_m, "12:00:00");
    $RA_hour_1 = $temp_array1[6];
    $DEC_deg_1 = $temp_array1[7];
    $RA_hour_2 = $temp_array2[6];
    $DEC_deg_2 = $temp_array2[7];
    $temp_array3_1 = geocentric_parallax_moon($RA_hour_1, $DEC_deg_1, $year_m, $month_m, $day_m);
    $temp_array3_2 = geocentric_parallax_moon($RA_hour_2, $DEC_deg_2, $year_m, $month_m, $day_m, "12:00:00");
    $RA_hour_corr_1 = $temp_array3_1[0];
    $DEC_deg_corr_1 = $temp_array3_1[1];
    $RA_hour_corr_2 = $temp_array3_2[0];
    $DEC_deg_corr_2 = $temp_array3_2[1];
    $average_DEC_deg = ($DEC_deg_corr_1 + $DEC_deg_corr_2) / 2;
    $hour_ang_set_deg = rad2deg(acos(-tan(deg2rad($latitude_place_decimal_deg)) * tan(deg2rad($average_DEC_deg))));
    $hour_ang_rise_deg = 2 * M_PI - rad2deg(acos(-tan(deg2rad($latitude_place_decimal_deg)) * tan(deg2rad($average_DEC_deg))));
    $hour_ang_rise_hour = $hour_ang_rise_deg / 15;
    $hour_ang_set_hour = $hour_ang_set_deg / 15;
    $local_sid_time_rise1 = $hour_ang_rise_hour + $RA_hour_corr_1;
    $local_sid_time_rise2 = $hour_ang_rise_hour + $RA_hour_corr_2;
    $local_sid_time_set1 = $hour_ang_set_hour + $RA_hour_corr_1;
    $local_sid_time_set2 = $hour_ang_set_hour + $RA_hour_corr_2;
    $interpolation_lst_rise = (12.03 * $local_sid_time_rise1) / (12.03 + $local_sid_time_rise1 - $local_sid_time_rise2);
    $interpolation_lst_set = (12.03 * $local_sid_time_set1) / (12.03 + $local_sid_time_set1 - $local_sid_time_set2);

    //// refraction and disk size corrections
    $refraction_horiz_deg = 0.567;
    $moon_param_temp = Moon_parameters($year_m, $month_m, $day_m, "12:00:00");
    $angular_size_moon_deg = $moon_param_temp[1];
    $x_ref = $refraction_horiz_deg + ($angular_size_moon_deg / 2);
    $psi_ref = acos(sin(deg2rad($latitude_place_decimal_deg)) / cos(deg2rad($average_DEC_deg)));
    $y_ref = rad2deg(asin(sin(deg2rad($x_ref)) / sin($psi_ref)));
    $lst_correcton_hour = (240 / 3600) * ($y_ref / cos(deg2rad($average_DEC_deg)));

    //////

    $interpolation_lst_rise_corr_hr = $interpolation_lst_rise - $lst_correcton_hour;
    $day_rise = $day_m;
    if($interpolation_lst_rise_corr_hr > 24){
        $interpolation_lst_rise_corr_hr = $interpolation_lst_rise_corr_hr - 24;
        $day_rise = $day_rise + 1;
    }
    elseif ($interpolation_lst_rise_corr_hr < 0){
        $interpolation_lst_rise_corr_hr = $interpolation_lst_rise_corr_hr + 24;
        $day_rise = $day_rise - 1;
    }
    $interpolation_lst_set_corr_hr = $interpolation_lst_set + $lst_correcton_hour;
    $day_set = $day_m;
    if($interpolation_lst_set_corr_hr > 24){
        $interpolation_lst_set_corr_hr = $interpolation_lst_set_corr_hr -24;
        $day_set = $day_set + 1;
    }
    elseif ($interpolation_lst_set_corr_hr < 0){
        $interpolation_lst_set_corr_hr = $interpolation_lst_set_corr_hr + 24;
        $day_set = $day_set - 1;
    }

    $interpolation_lst_rise_corr_sep = hours_to_sep($interpolation_lst_rise_corr_hr);
    $interpolation_lst_set_corr_sep = hours_to_sep($interpolation_lst_set_corr_hr);
    $local_time_rise_dec = LT_ST_time($year_m, $month_m, $day_rise, $interpolation_lst_rise_corr_sep);
    $local_time_set_dec = LT_ST_time($year_m, $month_m, $day_set, $interpolation_lst_set_corr_sep);
    $set_rise_time_dec = array($local_time_rise_dec, $local_time_set_dec);
    return $set_rise_time_dec;
}

//$set_rise = Set_rise_moon(2022,3,30);
//echo "Rise: ".$set_rise[0]."<br>";
//echo "Set: ".$set_rise[1]."<br>";



function set_rise_moon_new($year_m, $month_m, $day_m){
    global $latitude_place_decimal_deg;

    $time_start_set = "00:00:00";
    for($i = 1; $i < 5; $i++){
        $temp_array1 = Moon_position($year_m, $month_m, $day_m, $time_start_set);
        $ra_itter_hr_set = $temp_array1[6];
        //echo "ra_itter_hr_set: ".$i." - ".$ra_itter_hr_set."<br>";
        $dec_itter_deg_set = $temp_array1[7];
        //echo "dec_itter_deg_set: ".$i." - ".$dec_itter_deg_set."<br>";
        $temp_array2 = geocentric_parallax_moon($ra_itter_hr_set, $dec_itter_deg_set, $year_m, $month_m, $day_m, $time_start_set);
        $ra_corr_set = $temp_array2[0];
        //echo "ra_corr_set: ".$i." - ".$ra_corr_set."<br>";
        $dec_corr_set = $temp_array2[1];
        //echo "dec_corr_set: ".$i." - ".$dec_corr_set."<br>";
        $hour_ang_set_deg = rad2deg(acos(-tan(deg2rad($latitude_place_decimal_deg)) * tan(deg2rad($dec_corr_set))));
        //echo "hour_ang_set_deg: ".$i." - ".$hour_ang_set_deg."<br>";
        $hour_ang_set_hour = $hour_ang_set_deg / 15;
        //echo "hour_ang_set_hour: ".$i." - ".$hour_ang_set_hour."<br>";
        $local_sid_time_set_dec = $hour_ang_set_hour + $ra_corr_set;
        //echo "local_sid_time_set_dec: ".$i." - ".$local_sid_time_set_dec."<br>";
        $day_set = $day_m;
        if($local_sid_time_set_dec > 24){
            $local_sid_time_set_dec = $local_sid_time_set_dec - 24;
            $day_set = $day_set + 1;
        }
        elseif ($local_sid_time_set_dec < 0){
            $local_sid_time_set_dec = $local_sid_time_set_dec + 24;
            $day_set = $day_set - 1;
        }
        $local_sid_time_set_sep = hours_to_sep($local_sid_time_set_dec);
        //echo "local_sid_time_set_sep: ".$i." - ".$local_sid_time_set_sep."<br>";
        $local_civil_time_set = LT_ST_time($year_m, $month_m, $day_set, $local_sid_time_set_sep);
        $time_start_set = hours_to_sep($local_civil_time_set);
        //echo "time_start_set: ".$i." - ".$time_start_set."<br>";
    }

    $time_start_rise = "00:00:00";
    for($g = 1; $g < 5; $g++){
        $temp_array11 = Moon_position($year_m, $month_m, $day_m, $time_start_rise);
        $ra_itter_hr_rise = $temp_array11[6];
        $dec_itter_deg_rise = $temp_array11[7];
        $temp_array3 = geocentric_parallax_moon($ra_itter_hr_rise, $dec_itter_deg_rise, $year_m, $month_m, $day_m, $time_start_rise);
        $ra_corr_rise = $temp_array3[0];
        $dec_corr_rise = $temp_array3[1];
        $hour_ang_rise_deg = 2*M_PI - rad2deg(acos(-tan(deg2rad($latitude_place_decimal_deg)) * tan(deg2rad($dec_corr_rise))));
        $hour_ang_rise_hour = $hour_ang_rise_deg / 15;
        $local_sid_time_rise_dec = $hour_ang_rise_hour + $ra_corr_rise;
        $day_rise = $day_m;
        if($local_sid_time_rise_dec > 24){
            $local_sid_time_rise_dec = $local_sid_time_rise_dec - 24;
            $day_rise = $day_rise + 1;
        }
        elseif ($local_sid_time_rise_dec < 0){
            $local_sid_time_rise_dec = $local_sid_time_rise_dec + 24;
            $day_rise = $day_rise - 1;
        }
        $local_sid_time_rise_sep = hours_to_sep($local_sid_time_rise_dec);
        $local_civil_time_rise = LT_ST_time($year_m, $month_m, $day_rise, $local_sid_time_rise_sep);
        $time_start_rise = hours_to_sep($local_civil_time_rise);
    }

    //// refraction and disk size corrections
    $refraction_horiz_deg = 0.567;
    $moon_param_temp_set = Moon_parameters($year_m, $month_m, $day_m, $time_start_set);
    $moon_param_temp_rise = Moon_parameters($year_m, $month_m, $day_m, $time_start_rise);
    $angular_size_moon_set_deg = $moon_param_temp_set[1];
    $angular_size_moon_rise_deg = $moon_param_temp_rise[1];
    $x_ref_set = $refraction_horiz_deg + ($angular_size_moon_set_deg / 2);
    $x_ref_rise = $refraction_horiz_deg + ($angular_size_moon_rise_deg / 2);
    $psi_ref_set = acos(sin(deg2rad($latitude_place_decimal_deg)) / cos(deg2rad($dec_itter_deg_set)));
    $psi_ref_rise = acos(sin(deg2rad($latitude_place_decimal_deg)) / cos(deg2rad($dec_itter_deg_rise)));
    $y_ref_set = rad2deg(asin(sin(deg2rad($x_ref_set)) / sin($psi_ref_set)));
    $y_ref_rise = rad2deg(asin(sin(deg2rad($x_ref_rise)) / sin($psi_ref_rise)));
    $lst_correcton_hour_set = (240 / 3600) * ($y_ref_set / cos(deg2rad($dec_itter_deg_set)));
    $lst_correcton_hour_rise = (240 / 3600) * ($y_ref_rise / cos(deg2rad($dec_itter_deg_rise)));
    //////

    $local_sid_time_rise_dec_cor = $local_sid_time_rise_dec - $lst_correcton_hour_rise;
    if($local_sid_time_rise_dec_cor > 24){
        $local_sid_time_rise_dec_cor = $local_sid_time_rise_dec_cor - 24;
        $day_rise = $day_rise + 1;
    }
    elseif ($local_sid_time_rise_dec_cor < 0){
        $local_sid_time_rise_dec_cor = $local_sid_time_rise_dec_cor + 24;
        $day_rise = $day_rise - 1;
    }

    $local_sid_time_set_dec_cor = $local_sid_time_set_dec + $lst_correcton_hour_set;
    if($local_sid_time_set_dec_cor > 24){
        $local_sid_time_set_dec_cor = $local_sid_time_set_dec_cor - 24;
        $day_set = $day_set + 1;
    }
    elseif ($local_sid_time_set_dec_cor < 0){
        $local_sid_time_set_dec_cor = $local_sid_time_set_dec_cor + 24;
        $day_set = $day_set - 1;
    }
    $local_sid_time_set_sep_cor = hours_to_sep($local_sid_time_set_dec_cor);
    $local_sid_time_rise_sep_cor = hours_to_sep($local_sid_time_rise_dec_cor);
    $civil_time_dec_rise = LT_ST_time($year_m, $month_m, $day_rise, $local_sid_time_rise_sep_cor);
    $civil_time_dec_set = LT_ST_time($year_m, $month_m, $day_set, $local_sid_time_set_sep_cor);
    $set_rise_arr = array($civil_time_dec_rise, $civil_time_dec_set);
    return $set_rise_arr;
}

function set_rise_moon_sep($year_m, $month_m, $day_m):array{
    $temp_arr = Set_rise_moon_new($year_m, $month_m, $day_m);
    $rise_dec = $temp_arr[0];
    $set_dec =  $temp_arr[1];
    $rise_sep = substr(hours_to_sep($rise_dec), 0, -3);
    $set_sep = substr(hours_to_sep( $set_dec), 0, -3);
    $sep_array = array($rise_sep, $set_sep);
    return $sep_array;

}
//
//$set_rise = set_rise_moon_sep(2022,3,30);
//echo "Rise: ".$set_rise[0]."<br>";
//echo "Set: ".$set_rise[1]."<br>";

/////
function month_moon($year_moon, $month_moon): array
{
    $month_array_moon = array();
    $month_31 = array(1,3,5,7,8,10,12);
    $month_30 = array(4,6,9,11);
    $counter_days = 0;
    if(in_array($month_moon, $month_31)){
        $counter_days = 31;
    }
    elseif(in_array($month_moon, $month_30)){
        $counter_days = 30;
    }
    elseif($month_moon == 2){
        if(leap_year_check($year_moon)){
            $counter_days = 29;
        }
        else{
            $counter_days = 28;
        }
    }
    for($i = 0; $i < $counter_days; $i++){
        $temp_moon_time = set_rise_moon_sep($year_moon, $month_moon, $i+1);
        array_push($month_array_moon, $temp_moon_time);
    }
    return $month_array_moon;
}

//$temp = month_moon(2021,8);
//for ($i = 0; $i < count($temp ); $i++){
//    echo ($i+1)." ".$temp[$i][0]."     ".$temp[$i][1]."<br>";
//}

function Moon_phase_month($year_moon, $month_moon){
    $month_array_moon = array();
    $month_31 = array(1,3,5,7,8,10,12);
    $month_30 = array(4,6,9,11);
    $counter_days = 0;
    if(in_array($month_moon, $month_31)){
        $counter_days = 31;
    }
    elseif(in_array($month_moon, $month_30)){
        $counter_days = 30;
    }
    elseif($month_moon == 2){
        if(leap_year_check($year_moon)){
            $counter_days = 29;
        }
        else{
            $counter_days = 28;
        }
    }
    for($i = 0; $i < $counter_days; $i++){
        $temp_moon_time =Moon_phase($year_moon, $month_moon, $i+1);
        array_push($month_array_moon, $temp_moon_time);
    }
    return $month_array_moon;
}

function moon_type($year_moon, $month_moon){
    $temp_array1 = Moon_phase_month($year_moon, $month_moon);
    $moon_type = array();
    $symb_asc = "Р";
    $symb_des = "У";
    $symb_new = "Н";
    $symb_full = "П";
    for($i = 0; $i < count($temp_array1); $i++){
        if($i == 0){
            if((Moon_phase($year_moon, $month_moon,0) > $temp_array1[($i)]) and ($temp_array1[($i)] != 0) and ($temp_array1[($i)] != 1)){
                array_push($moon_type, $symb_des);
            }
            elseif ((Moon_phase($year_moon, $month_moon,0) < $temp_array1[($i)]) and ($temp_array1[($i)] != 0) and ($temp_array1[($i)] != 1)){
                array_push($moon_type, $symb_asc);
            }
            elseif($temp_array1[($i)] == 0){
                array_push($moon_type, $symb_new);
            }
            elseif($temp_array1[($i)] == 1){
                array_push($moon_type, $symb_full);
            }
            else{
                array_push($moon_type, "none");
            }
        }
        else{
            if(($temp_array1[($i -1)] > $temp_array1[($i)]) and ($temp_array1[($i)] != 0) and ($temp_array1[($i)] != 1)){
                array_push($moon_type, $symb_des);
            }
            elseif (($temp_array1[($i -1)] < $temp_array1[($i)]) and ($temp_array1[($i)] != 0) and ($temp_array1[($i)] != 1)){
                array_push($moon_type, $symb_asc);
            }
            elseif($temp_array1[($i)] == 0){
                array_push($moon_type, $symb_new);
            }
            elseif($temp_array1[($i)] == 1){
                array_push($moon_type, $symb_full);
            }
            else{
                array_push($moon_type, "none");
            }
        }
    }
    return $moon_type;
}
