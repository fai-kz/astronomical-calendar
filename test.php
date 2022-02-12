<?php

////////// Prepared by Vitaliy Kim (PhD, Senior researcher at Fesenkov Astrophysical Institute, Almaty, Kazakhstan)
/// E-mail: ursa-majoris@yandex.ru

//require "sun.php";

$longitude_place_decimal_deg = $_POST['long']; // longitude of some place in decimal degrees (in this case Almaty longitude)
$latitude_place_decimal_deg = $_POST['alt']; // latitude of some place in decimal degrees (in this case Almaty longitude)
$Time_Zone = $_POST['zone']; // time zone from UTC (in this case Almaty time zone)
$altitude_place = 0; // Altitude (in meters) over the sea level (in this case 0 meters)
//$longitude_place_decimal_deg = 76.95; // longitude of some place in decimal degrees (in this case Almaty longitude)
//$longitude_place_decimal_deg = 51.76; // longitude of some place in decimal degrees (in this case Almaty longitude)
//$latitude_place_decimal_deg = 46.95; // latitude of some place in decimal degrees (in this case Almaty longitude)
//$Time_Zone = 5; // time zone from UTC (in this case Almaty time zone)

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

/// Caledar gregorian date and time from Julian date
function Date_time_from_jd($jd_date){
    $jd_date = $jd_date + 0.5;
    $int_part_jd = (int)$jd_date;
    $fract_part_jd = $jd_date - $int_part_jd;
    $A = 1;
    if($int_part_jd > 2299160){
        $A = (int)(($int_part_jd - 1867216.25) / 36524.25);
        $B = $int_part_jd + 1 + $A - (int)($A / 4);
    }
    else{
        $B = $int_part_jd + 1 + $A - (int)($A / 4);
    }
    $C = $B + 1524;
    $D = (int)(($C - 122.1) / 365.25);
    $E = (int)(365.25 * $D);
    $G = (int)(($C - $E) / 30.6001);
    $day = $C - $E + $fract_part_jd - (int)(30.6001 * $G);
    $month = $G - 1;
    if($G > 13.5){
        $month = $G - 13;
    }
    $year = $D - 4716;
    if($month < 2.5){
        $year = $D - 4715;
    }
    $hours_with_m_s = ($day - (int)$day) * 24;
    $hours = (int)$hours_with_m_s;
    $minutes_with_s = ($hours_with_m_s - $hours) * 60;
    $minutes = (int)$minutes_with_s;
    $seconds = (int)(($minutes_with_s - $minutes) * 60);

    $date = array($year, $month, (int)$day, $hours, $minutes, $seconds, $hours_with_m_s);
    return $date;
}
//$date_jd = Date_time_from_jd(2446113.75);
//echo "year: ".$date_jd[0]."<br>";
//echo "month: ".$date_jd[1]."<br>";
//echo "day: ".$date_jd[2]."<br>";
//echo "hours: ".$date_jd[3]."<br>";
//echo "min: ".$date_jd[4]."<br>";
//echo "s: ".$date_jd[5]."<br>";

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
    if($horus >= 0){
        $total_time_decimal = $horus + ($minutes / 60) + ($seconds / 3600); // a time recalculated in decimal system
    }
    else{
        $total_time_decimal = (abs($horus)+ ($minutes / 60) + ($seconds / 3600)) * (-1);
    }
    if(is_numeric($total_time_decimal) == false){ // A check of $total_dec_time as a number
        return 0;
    }
    else{
        return $total_time_decimal;
    }
}
//Example of using time_dec function
//$time_op = "00:57:46";
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
    //echo "loc_sid_time_dec: ".$loc_sid_time_dec."<br>";
    //$loc_sid_time_dec = 21.518;
    $ut_sid_time = $loc_sid_time_dec - $longitude_place_horus;
    //echo "ut_sid_time: ".$ut_sid_time."<br>";
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
                $month_ut = 12;
            }
            $temp_array1 = array(1,3,5,7,8,10,12);
            $temp_array2 = array(4,6,9,11);
            if(in_array($month_ut, $temp_array1)){
                $day_ut = 31;
            }
            elseif (in_array($month_ut, $temp_array2)){
                $day_ut = 30;
            }
            elseif ($month_ut == 2){
                if(leap_year_check($year_ut)){
                    $day_ut = 29;
                }
                else{
                    $day_ut = 28;
                }
            }
        }
    }
    elseif ($ut_sid_time > 24){
        $ut_sid_time = $ut_sid_time - 24;
        $day_ut = $day_ut + 1;
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
 //Example of using LT_ST_time function
//$time_lt = LT_ST_time(2022,3,19, "29:55:35");
//echo "time_lt: ".hours_to_sep($time_lt)."<br>";

function UT_to_GST($year_ut, $month_ut, $day_ut, $time_ut = "00:00:00"):float{
    $time_ut_dec = time_dec($time_ut);
    //echo "time_ut_dec ".$time_ut_dec."<br>";
    if($time_ut_dec > 24){
        $time_ut_dec = $time_ut_dec - 24;
        //$day_ut = $day_ut + 1;
    }
    elseif ($time_ut_dec < 0){
        $time_ut_dec = $time_ut_dec + 24;
        //$day_ut = $day_ut - 1;
    }
    $date_jd = julian_date($year_ut, $month_ut, $day_ut);
    //echo "date_jd ".$date_jd."<br>";
    $rel_jd = $date_jd - 2451545;
    $centuries = $rel_jd / 36525;
    $T_0 = 6.697374558 + (2400.051336 * $centuries) + (0.000025862 * pow($centuries, 2));
    //echo "T_0 ".$T_0."<br>";
    $reduce_T = fmod($T_0, 24);
    if ($reduce_T < 0){
        $reduce_T = $reduce_T + 24;
    }
    //echo "reduce_T ".$reduce_T."<br>";
    $ut_correct_st = $time_ut_dec * 1.002737909;

    $sid_time_dec = $reduce_T + $ut_correct_st;
    if($sid_time_dec > 24){
        $sid_time_dec = $sid_time_dec - 24;
    }
    return $sid_time_dec;
}
//$st = UT_to_GST(1980,4,22,"14:36:51.67");
//echo "time ".hours_to_sep2($st);

function GST_to_UT($year_gst, $month_gst, $day_gst, $time_gst = "00:00:00"){
    $time_gst_dec = time_dec($time_gst);
    if($time_gst_dec > 24){
        $time_gst_dec = $time_gst_dec - 24;
        //$day_gst = $day_gst + 1;
    }
    elseif ($time_gst_dec < 0){
        $time_gst_dec = $time_gst_dec + 24;
        //$day_gst = $day_gst - 1;
    }
    $date_jd = julian_date($year_gst, $month_gst, $day_gst);
    $rel_jd = $date_jd - 2451545;
    $centuries = $rel_jd / 36525;
    $T_0 = 6.697374558 + (2400.051336 * $centuries) + (0.000025862 * pow($centuries, 2));
    $reduce_T = fmod($T_0, 24);
    if ($reduce_T < 0){
        $reduce_T = $reduce_T + 24;
    }
    $ut_1 = $time_gst_dec - $reduce_T;
    if ($ut_1 > 24){
        $ut_1 = $ut_1 -24;
    }
    elseif ($ut_1 < 0){
        $ut_1 = $ut_1 + 24;
    }
    $ut_dec = $ut_1 * 0.9972695663;
    return $ut_dec;
}

//$b = GST_to_UT(1980,4,22,"04:40:5.23");
//echo "UT: ".hours_to_sep2($b);

function Local_time_to_LST($year_ct, $month_ct, $day_ct, $time_ct = "00:00:00"){
    global $longitude_place_decimal_deg;
    global $Time_Zone;
    $time_ct_dec = time_dec($time_ct);
    $time_ut_dec = $time_ct_dec - $Time_Zone;
    $time_ut_sep = hours_to_sep2($time_ut_dec);
    //echo "time_ut_sep: ".$time_ut_sep."<br>";
    $glob_sid_time_dec = UT_to_GST($year_ct, $month_ct, $day_ct, $time_ut_sep);
    $loc_sid_time_dec = $glob_sid_time_dec + ($longitude_place_decimal_deg / 15);
    return $loc_sid_time_dec;
}
//$temp = Local_time_to_LST(2021,1,2,"00:00:00");
//echo "lst: ".hours_to_sep($temp)."<br>";

function LST_to_loc_CT($year_lst, $month_lst, $day_lst, $time_lst = "00:00:00"){
    global $longitude_place_decimal_deg;
    global $Time_Zone;
    $time_lst_dec = time_dec($time_lst);
    $time_gst_dec = $time_lst_dec - ($longitude_place_decimal_deg / 15);
    $time_gst_sep = hours_to_sep2($time_gst_dec);
    $ut_dec = GST_to_UT($year_lst, $month_lst, $day_lst,$time_gst_sep);
    $civil_time_dec = $ut_dec + $Time_Zone;
    //echo "day_lst: ".$day_lst."<br>";
    return $civil_time_dec;
}
//$temp2 = LST_to_loc_CT(2021,1,1,"00:00:00");
//echo "temp2 ".hours_to_sep($temp2 )."<br>";

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
//echo "DEC_inrad ".$dec."<br>";

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

function hours_to_sep2($hours_dec):string{
//    if($hours_dec < 0){
//        $hours_dec = $hours_dec + 24;
//    }
    $int_hours = (int)$hours_dec;
    $other_part1 = abs($hours_dec) - abs($int_hours);
    $other_part2 = $other_part1 * 60;
    $minutes = (int)$other_part2;
    $seconds = ((($other_part2 - (int)$other_part2)*60));
    if($int_hours < 10 and $int_hours > 0){
        $int_hours ="0".$int_hours;
    }
    elseif ($int_hours < 0){
        $int_hours = "-0".abs($int_hours);
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

function Kepler_equation_solar($eccentricity,$mean_anomaly_rad){
    $Eccentric_anomaly_rad = $mean_anomaly_rad;
    $error_calc = 1;
    while (abs($error_calc) > 0.000001){
        $error_calc = $Eccentric_anomaly_rad - $eccentricity * sin($Eccentric_anomaly_rad) - $mean_anomaly_rad;
        $delta_Eccentric_anomaly_rad = $error_calc / (1 - $eccentricity * cos($Eccentric_anomaly_rad));
        $Eccentric_anomaly_rad = $Eccentric_anomaly_rad - $delta_Eccentric_anomaly_rad;
    }

    //true anomaly calculation
    $temp_1 = (1 + $eccentricity) / (1 - $eccentricity);
    $temp_2 = pow($temp_1, 0.5);
    $temp_3= tan($Eccentric_anomaly_rad / 2);
    $temp_4= $temp_2 * $temp_3;
    $true_anomaly_rad = 2 * atan($temp_4);
    return $true_anomaly_rad;

}

function nutation_obliquity_correction ($T_2000){
    $square = pow($T_2000, 2);
    $cube = pow($T_2000, 3);
    $L = 280.4665 + 36000.7698 * $T_2000; // Mean longitude of the Sun
    $L_str = 218.3165 + 481267.8813 * $T_2000; // Mean longitude of the Moon
    $omega = 125.04452 - 1934.136261 * $T_2000 + 0.0020708 * $square + ($cube/450000); // longitude of the ascending node of the mean Moon's orbit
    $delta_psi_nutation_arc_sec = - 17.2 * sin(deg2rad($omega)) - 1.32 * sin(deg2rad(2 * $L)) - 0.23 * sin(deg2rad(2 * $L_str)) + 0.21 * sin(deg2rad(2 * $omega));
    $delta_epsion_obliq_arc_sec = 9.2 * cos(deg2rad($omega)) + 0.57 *cos(deg2rad(2 * $L)) + 0.1 * cos(deg2rad(2 * $L_str)) - 0.09 * cos(deg2rad(2 * $omega));
    $delta_psi_nutation_deg = $delta_psi_nutation_arc_sec / 3600;
    $delta_epsion_obliq_deg = $delta_epsion_obliq_arc_sec / 3600;
    $return_arr = array($delta_psi_nutation_deg, $delta_epsion_obliq_deg);
    return $return_arr;
}



//// Solar coordinates (RA, DEC)
function Solar_position ($year_sol, $month_sol, $day_sol, $loc_time = "00:00:00"):array{
    $n_days = julian_date($year_sol, $month_sol, $day_sol, $loc_time) - 2451545;
    $T_2000 = (julian_date($year_sol, $month_sol, $day_sol) - 2451545) / 36525;
    $n_centuries = $n_days / 36525;
    $ecliptic_longitude_2010_deg = 279.557208;
    $eccentricity_2010 = 0.016708634 - 0.000042037 * $T_2000 - 0.0000001267 * pow($T_2000,2);
    $ecliptic_longitude_2010_peregee_deg = 283.112438;
    $days_from_2010 =  julian_date($year_sol, $month_sol, $day_sol, $loc_time) - julian_date(2010,1,0);
    //$mean_longitude_deg = 280.460 + 0.9856474 * $n_days;
    //$mean_anomaly_deg = 357.528 + 0.98560003 * $n_days;
    $mean_anomaly_deg = (360 / 365.242191) * $days_from_2010 + $ecliptic_longitude_2010_deg - $ecliptic_longitude_2010_peregee_deg;
    $mean_anomaly_rad = deg2rad($mean_anomaly_deg);
    ////$equation_center = $mean_anomaly_deg + (360 / M_PI) * $eccentricity_2010 * sin($mean_anomaly_rad);
    //$ecliptic_longitude_deg = $mean_longitude_deg + 1.915 * sin($mean_anomaly_rad) + 0.020 * sin(2*$mean_anomaly_rad);
    $true_anomaly_rad = Kepler_equation_solar($eccentricity_2010, $mean_anomaly_rad);
    $true_anomaly_deg = rad2deg($true_anomaly_rad);
    //$ecliptic_longitude_deg = $equation_center + $ecliptic_longitude_2010_peregee_deg;
    $Omega_rad = deg2rad(125.04  - 1934.136 * $T_2000);
    $ecliptic_longitude_deg = $true_anomaly_deg + $ecliptic_longitude_2010_peregee_deg - 0.00569 - 0.00478 * sin($Omega_rad) + nutation_obliquity_correction ($T_2000)[0];
    //echo "ecliptic_longitude_deg".$ecliptic_longitude_deg."<br>";
    $ecliptic_longitude_rad = deg2rad($ecliptic_longitude_deg);
    $ecliptic_inclination_deg = 23.43929111 - (46.8150 / 3600) * $n_centuries - ((0.00059 / 3600) * pow($n_centuries, 2)) + ((0.001813 / 3600)*pow($n_centuries, 3)) + nutation_obliquity_correction ($T_2000)[1];
    //echo "ecliptic_inclination_deg".$ecliptic_inclination_deg."<br>";
    $ecliptic_inclination_rad = deg2rad($ecliptic_inclination_deg);
//  //$RA_sun_deg = rad2deg(atan(cos($ecliptic_inclination_rad) * tan($ecliptic_longitude_rad)));
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

//  if($RA_sun_deg < 0){
//        $RA_sun_deg = $RA_sun_deg + 360;
//  }

    $DEC_sun_deg = rad2deg(asin(sin($ecliptic_inclination_rad ) * sin($ecliptic_longitude_rad)));
    //echo "DEC_sun_deg: ".$DEC_sun_deg."<br>";
    $RA_sun_deg_sep = hours_to_sep($RA_sun_deg / 15);
    //echo "RA_sun_deg_sep".$RA_sun_deg_sep."<br>";
    $DEC_sun_deg_sep = deg_to_sep($DEC_sun_deg);
    //echo "DEC_sun_deg_sep".$DEC_sun_deg_sep."<br>";
//    $solar_position_deg = array($RA_sun_deg_sep, $DEC_sun_deg_sep);
//    return $solar_position_deg;
    $RA_sun_hour_dec = $RA_sun_deg / 15;
    if($RA_sun_hour_dec < 0){
        $RA_sun_hour_dec = $RA_sun_hour_dec + 24;
    }
    $solar_position_dec = array($RA_sun_hour_dec, $DEC_sun_deg);
    return $solar_position_dec;

}
//$p = Solar_position(2022, 1, 2);
//echo "RA ".$p[0]."<br>";
//echo "DEC ".$p[1]."<br>";
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




function twilights_astr($year_twi, $month_twi, $day_twi):array{
    global $latitude_place_decimal_deg;
    $latitude_place_radian = deg2rad($latitude_place_decimal_deg);
    $tan_lat = tan($latitude_place_radian);
    $temp_array1 = Solar_position($year_twi, $month_twi, $day_twi);
    $temp_array2 = Solar_position($year_twi, $month_twi, $day_twi + 1);
    $DEC_rise_str = deg_to_sep($temp_array1[1]);
    $DEC_set_str = deg_to_sep($temp_array2[1]);
    $DEC_rise_rad = DEC_inradian($DEC_rise_str);
    $DEC_set_rad = DEC_inradian($DEC_set_str);
    $hour_angle_rise_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_rise_rad)));
    $hour_angle_set_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_set_rad)));
    $hour_angle_rise_finish_deg = rad2deg(acos((cos(deg2rad(108)) - sin($latitude_place_radian) * sin($DEC_rise_rad)) / (cos($latitude_place_radian) * cos($DEC_rise_rad))));
    $hour_angle_set_finish_deg = rad2deg(acos((cos(deg2rad(108)) - sin($latitude_place_radian) * sin($DEC_set_rad)) / (cos($latitude_place_radian) * cos($DEC_set_rad))));
    $continuum_tw_rise_hour = ($hour_angle_rise_finish_deg - $hour_angle_rise_start_deg) / 15;
    $continuum_tw_set_hour = ($hour_angle_set_finish_deg - $hour_angle_set_start_deg) / 15;
    $temp_array3 = Set_rise_sun_new($year_twi, $month_twi, $day_twi);
    $twi_before_rise_dec = $temp_array3[0] - $continuum_tw_rise_hour;
    $twi_after_set_dec = $temp_array3[1] + $continuum_tw_set_hour;
    $twi_before_rise_sep = hours_to_sep($twi_before_rise_dec);
    $twi_after_set_sep = hours_to_sep($twi_after_set_dec);
    $twilights_astr_arr = array($twi_before_rise_sep, $twi_after_set_sep);
    return $twilights_astr_arr;
}
//
//$temp = twinlights(2022,1,6);
//echo "Rise twinlights: ".$temp[0]."<br>";
//echo "Set twinlights: ".$temp[1]."<br>";



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
    $DEC_rise_str = deg_to_sep($temp_array1[1]);
    $DEC_set_str = deg_to_sep($temp_array2[1]);
    $DEC_rise_rad = DEC_inradian($DEC_rise_str);
    $DEC_set_rad = DEC_inradian($DEC_set_str);
    $hour_angle_rise_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_rise_rad)));
    $hour_angle_set_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_set_rad)));
    $hour_angle_rise_finish_deg = rad2deg(acos((cos(deg2rad(102)) - sin($latitude_place_radian) * sin($DEC_rise_rad)) / (cos($latitude_place_radian) * cos($DEC_rise_rad))));
    $hour_angle_set_finish_deg = rad2deg(acos((cos(deg2rad(102)) - sin($latitude_place_radian) * sin($DEC_set_rad)) / (cos($latitude_place_radian) * cos($DEC_set_rad))));
    $continuum_tw_rise_hour = ($hour_angle_rise_finish_deg - $hour_angle_rise_start_deg) / 15;
    $continuum_tw_set_hour = ($hour_angle_set_finish_deg - $hour_angle_set_start_deg) / 15;
    $temp_array3 = Set_rise_sun_new($year_nav, $month_nav, $day_nav);
    $twi_before_rise_dec = $temp_array3[0] - $continuum_tw_rise_hour;
    $twi_after_set_dec = $temp_array3[1] + $continuum_tw_set_hour;
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
    $DEC_rise_str = deg_to_sep($temp_array1[1]);
    $DEC_set_str = deg_to_sep($temp_array2[1]);
    $DEC_rise_rad = DEC_inradian($DEC_rise_str);
    $DEC_set_rad = DEC_inradian($DEC_set_str);
    $hour_angle_rise_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_rise_rad)));
    $hour_angle_set_start_deg = rad2deg(acos(-$tan_lat * tan($DEC_set_rad)));
    $hour_angle_rise_finish_deg = rad2deg(acos((cos(deg2rad(96)) - sin($latitude_place_radian) * sin($DEC_rise_rad)) / (cos($latitude_place_radian) * cos($DEC_rise_rad))));
    $hour_angle_set_finish_deg = rad2deg(acos((cos(deg2rad(96)) - sin($latitude_place_radian) * sin($DEC_set_rad)) / (cos($latitude_place_radian) * cos($DEC_set_rad))));
    $continuum_tw_rise_hour = ($hour_angle_rise_finish_deg - $hour_angle_rise_start_deg) / 15;
    $continuum_tw_set_hour = ($hour_angle_set_finish_deg - $hour_angle_set_start_deg) / 15;
    $temp_array3 = Set_rise_sun_new($year_civil, $month_civil, $day_civil);
    $twi_before_rise_dec = $temp_array3[0] - $continuum_tw_rise_hour;
    $twi_after_set_dec = $temp_array3[1] + $continuum_tw_set_hour;
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

function Moon_position_new($year_moon, $month_moon, $day_moon, $time_ut = "00:00:00"){
    $JD_indate = julian_date($year_moon, $month_moon, $day_moon, $time_ut);
    $T = ($JD_indate - 2451545) / 36525;
    $L_shtr_mean_moon_longit = 218.3164477 + 481267.88123421 * $T - 0.0015786 * pow($T, 2) + (pow($T,3) / 538841) - (pow($T,4) / 65194000);
    $D_mean_elong_moon = 297.8501921 + 445267.1114034 * $T - 0.0018819 * pow($T, 2) + (pow($T,3) / 545868) - (pow($T,4) / 113065000);
    $M_mean_sun_anomaly = 357.5291092 + 35999.0502909 * $T - 0.0001536 * pow($T, 2) - (pow($T,3) / 24490000);
    $M_shtr_moon_mean_anom = 134.9633964 + 477198.8675055 * $T + 0.0087414 * pow($T, 2) + (pow($T,3) / 69699) - (pow($T,4) / 14712000);
    $F_moon_arg_latitude = 93.2720950 + 483202.0175233 * $T - 0.0036539 * pow($T, 2)  - (pow($T,3) / 3526000) + (pow($T,4) / 863310000);
    $L_shtr_mean_moon_longit_rad = deg2rad($L_shtr_mean_moon_longit);
    $D_mean_elong_moon_rad = deg2rad($D_mean_elong_moon);
    $M_mean_sun_anomaly_rad = deg2rad($M_mean_sun_anomaly);
    $M_shtr_moon_mean_anom_rad = deg2rad($M_shtr_moon_mean_anom);
    $F_moon_arg_latitude_rad = deg2rad($F_moon_arg_latitude);
    $A1_rad = deg2rad(119.75 + 131.849 * $T);
    $A2_rad = deg2rad(53.09 + 479264.290 * $T);
    $A3_rad = deg2rad(313.45 + 481266.484 * $T);
    $E = 1 - 0.002516 * $T - 0.0000074 * pow($T, 2);

    ////// coefficients of sum array
    $c1 = sin($M_shtr_moon_mean_anom_rad);
    $c2 = sin(2* $D_mean_elong_moon_rad - $M_shtr_moon_mean_anom_rad);
    $c3 = sin(2 * $D_mean_elong_moon_rad);
    $c4 = sin(2 * $M_shtr_moon_mean_anom_rad);
    $c5 = $E * sin($M_mean_sun_anomaly_rad);
    $c6 = sin(2 * $F_moon_arg_latitude_rad);;
    $c7 = sin(2 * $D_mean_elong_moon_rad - 2 * $M_shtr_moon_mean_anom_rad);
    $c8 = $E * sin(2 * $D_mean_elong_moon_rad - $M_mean_sun_anomaly_rad - $M_shtr_moon_mean_anom_rad);
    $c9 = sin(2  * $D_mean_elong_moon_rad + $M_shtr_moon_mean_anom_rad);
    $c10 = $E * sin(2  * $D_mean_elong_moon_rad - $M_mean_sun_anomaly_rad);
    $c11 = $E * sin($M_mean_sun_anomaly_rad - $M_shtr_moon_mean_anom_rad);
    $c12 = sin($D_mean_elong_moon_rad);
    $c13 = $E * sin($M_mean_sun_anomaly_rad + $M_shtr_moon_mean_anom_rad);
    $c14 = sin(2 * $D_mean_elong_moon_rad - 2 * $F_moon_arg_latitude_rad);
    $c15 = sin($M_shtr_moon_mean_anom_rad - 2 * $F_moon_arg_latitude_rad);
    $c16 = sin($M_shtr_moon_mean_anom_rad  - 2 * $F_moon_arg_latitude_rad);
    $c17 = sin(4 * $D_mean_elong_moon_rad - $M_shtr_moon_mean_anom_rad);
    $c18 = sin(3 * $M_shtr_moon_mean_anom_rad);
    $c19 = sin(4 * $D_mean_elong_moon_rad - 2 * $M_shtr_moon_mean_anom_rad);;
    $c20 = $E * sin(2 * $D_mean_elong_moon_rad + $M_mean_sun_anomaly_rad - $M_shtr_moon_mean_anom_rad);
    $c21 = $E * sin(2 * $D_mean_elong_moon_rad + $M_mean_sun_anomaly_rad);
    $c22 = sin($D_mean_elong_moon_rad - $M_shtr_moon_mean_anom_rad);
    $c23 = $E * sin($D_mean_elong_moon_rad + $M_mean_sun_anomaly_rad);
    $c24 = $E * sin(2 * $D_mean_elong_moon_rad - $M_mean_sun_anomaly_rad + $M_shtr_moon_mean_anom_rad);
    $c25 = sin(2 * $D_mean_elong_moon_rad - 2 * $M_shtr_moon_mean_anom_rad);
    $c26 = sin(4 * $D_mean_elong_moon_rad);
    $c27 = sin(2 * $D_mean_elong_moon_rad - 3 * $M_shtr_moon_mean_anom_rad);
    $c28 = $E * sin($M_mean_sun_anomaly_rad - 2 * $M_shtr_moon_mean_anom_rad);
    $c29 = sin(2 * $D_mean_elong_moon_rad - $M_shtr_moon_mean_anom_rad + 2 * $F_moon_arg_latitude_rad);
    $c30 = $E * sin(2 * $D_mean_elong_moon_rad - $M_mean_sun_anomaly_rad - 2 * $M_shtr_moon_mean_anom_rad);
    $c31 = sin($D_mean_elong_moon_rad + $M_shtr_moon_mean_anom_rad);
    $c32 = pow($E,2) * sin(2 * $D_mean_elong_moon_rad - 2 * $M_mean_sun_anomaly_rad);
    $c33 = $E * sin($M_mean_sun_anomaly_rad + 2 * $M_shtr_moon_mean_anom_rad);
    $c34 = pow($E,2) * sin(2 * $M_mean_sun_anomaly_rad);
    $c35 = pow($E,2) * sin(2 * $D_mean_elong_moon_rad - 2 * $M_mean_sun_anomaly_rad - $M_shtr_moon_mean_anom_rad);
    $c36 = sin(2 * $D_mean_elong_moon_rad - $M_shtr_moon_mean_anom_rad - 2 * $F_moon_arg_latitude_rad);
    $c37 = sin(2 * $D_mean_elong_moon_rad + 2 * $F_moon_arg_latitude_rad);
    $c38 = $E * sin(4 * $D_mean_elong_moon_rad - $M_mean_sun_anomaly_rad - $M_shtr_moon_mean_anom_rad);
    $c39 = sin(2 * $M_shtr_moon_mean_anom_rad + 2 * $F_moon_arg_latitude_rad);
    $c40 = sin(3 * $D_mean_elong_moon_rad - $M_shtr_moon_mean_anom_rad);
    $c41 = $E * sin(2 * $D_mean_elong_moon_rad + $M_mean_sun_anomaly_rad + $M_shtr_moon_mean_anom_rad);
    $c42 = $E * sin(4 * $D_mean_elong_moon_rad - $M_mean_sun_anomaly_rad - 2 * $M_shtr_moon_mean_anom_rad);
    $c43 = pow($E,2) * sin(2 * $M_mean_sun_anomaly_rad - $M_shtr_moon_mean_anom_rad);
    $c44 = pow($E,2) * sin(2 * $D_mean_elong_moon_rad + 2 * $M_mean_sun_anomaly_rad - $M_shtr_moon_mean_anom_rad);
    $c45 = $E * sin(2 * $D_mean_elong_moon_rad + $M_mean_sun_anomaly_rad - 2 * $M_shtr_moon_mean_anom_rad);
    $c46 = $E * sin(2 * $D_mean_elong_moon_rad - $M_mean_sun_anomaly_rad - 2 * $F_moon_arg_latitude_rad);
    $c47 = sin(4 * $D_mean_elong_moon_rad + $M_shtr_moon_mean_anom_rad);
    $c48 = sin(4 * $M_shtr_moon_mean_anom_rad);
    $c49 = $E * sin(4 * $D_mean_elong_moon_rad - $M_mean_sun_anomaly_rad);
    $c50 = sin($D_mean_elong_moon_rad - 2 * $M_shtr_moon_mean_anom_rad);
    $c51 = $E * sin(2 * $D_mean_elong_moon_rad + $M_mean_sun_anomaly_rad - 2 * $F_moon_arg_latitude_rad);
    $c52 = sin(2 * $M_shtr_moon_mean_anom_rad - 2 * $F_moon_arg_latitude_rad);
    $c53 = $E * sin($D_mean_elong_moon_rad + $M_mean_sun_anomaly_rad + $M_shtr_moon_mean_anom_rad);
    $c54 = sin(3 * $D_mean_elong_moon_rad - 2 * $M_shtr_moon_mean_anom_rad);
    $c55 = sin(4 * $D_mean_elong_moon_rad - 3 * $M_shtr_moon_mean_anom_rad);
    $c56 = $E * sin(2 * $D_mean_elong_moon_rad - $M_mean_sun_anomaly_rad + 2 * $M_shtr_moon_mean_anom_rad);
    $c57 = pow($E,2) * sin(2 * $M_mean_sun_anomaly_rad + $M_shtr_moon_mean_anom_rad);
    $c58 = $E * sin($D_mean_elong_moon_rad + $M_mean_sun_anomaly_rad - $M_shtr_moon_mean_anom_rad);
    $c59 = sin(2 * $D_mean_elong_moon_rad  - 3 * $M_shtr_moon_mean_anom_rad);
    $c60 = sin(2 * $D_mean_elong_moon_rad - $M_shtr_moon_mean_anom_rad - 2 * $F_moon_arg_latitude_rad);


    ////// sum of longitude's elements
    $longitude_sum_arr = array();
    $l1 = 6288774 * $c1;
    array_push($longitude_sum_arr, $l1);
    $l2 = 1274027 * $c2;
    array_push($longitude_sum_arr, $l2);
    $l3 = 658314 * $c3;
    array_push($longitude_sum_arr, $l3);
    $l4 = 213618 * $c4;
    array_push($longitude_sum_arr, $l4);
    $l5 = -185116 * $c5;
    array_push($longitude_sum_arr, $l5);
    $l6 = -114332 * $c6;
    array_push($longitude_sum_arr, $l6);
    $l7 = 58793 * $c7;
    array_push($longitude_sum_arr, $l7);
    $l8 = 57066 * $c8;
    array_push($longitude_sum_arr, $l8);
    $l9 = 53332 * $c9;
    array_push($longitude_sum_arr, $l9);
    $l10 = 45758 * $c10;
    array_push($longitude_sum_arr, $l10);
    $l11 = -40923 * $c11;
    array_push($longitude_sum_arr, $l11);
    $l12 = -34720 * $c12;
    array_push($longitude_sum_arr, $l12);
    $l13 = -30383 * $E * $c13;
    array_push($longitude_sum_arr, $l13);
    $l14 = 15327 * $c14;
    array_push($longitude_sum_arr, $l14);
    $l15 = -12528 * $c15;
    array_push($longitude_sum_arr, $l15);
    $l16 = 10980 * $c16;
    array_push($longitude_sum_arr, $l16);
    $l17 = 10675 * $c17;
    array_push($longitude_sum_arr, $l17);
    $l18 = 10034 * $c18;
    array_push($longitude_sum_arr, $l18);
    $l19 = 8548 * $c19;
    array_push($longitude_sum_arr, $l19);
    $l20 = -7888 * $c20;
    array_push($longitude_sum_arr, $l20);
    $l21 = -6766 * $c21;
    array_push($longitude_sum_arr, $l21);
    $l22 = -5163 * $c22;
    array_push($longitude_sum_arr, $l22);
    $l23 = 4987 * $c23;
    array_push($longitude_sum_arr, $l23);
    $l24 = 4036 * $c24;
    array_push($longitude_sum_arr, $l24);
    $l25 = 3994 * $c25;
    array_push($longitude_sum_arr, $l25);
    $l26 = 3861 * $c26;
    array_push($longitude_sum_arr, $l26);
    $l27 = 3665 * $c27;
    array_push($longitude_sum_arr, $l27);
    $l28 = -2689 * $c28;
    array_push($longitude_sum_arr, $l28);
    $l29 = -2602 * $c29;
    array_push($longitude_sum_arr, $l29);
    $l30 = 2390 * $c30;
    array_push($longitude_sum_arr, $l30);
    $l31 = -2348 * $c31;
    array_push($longitude_sum_arr, $l31);
    $l32 = 2236 * $c32;
    array_push($longitude_sum_arr, $l32);
    $l33 = -2120 * $c33;
    array_push($longitude_sum_arr, $l33);
    $l34 = -2069 * $c34;
    array_push($longitude_sum_arr, $l34);
    $l35 = 2048 * $c35;
    array_push($longitude_sum_arr, $l35);
    $l36 = -1773 * $c36;
    array_push($longitude_sum_arr, $l36);
    $l37 = -1595 * $c37;
    array_push($longitude_sum_arr, $l37);
    $l38 = 1215 * $c38;
    array_push($longitude_sum_arr, $l38);
    $l39 = -1110 * $c39;
    array_push($longitude_sum_arr, $l39);
    $l40 = -892 * $c40;
    array_push($longitude_sum_arr, $l40);
    $l41 = -810 * $c41;
    array_push($longitude_sum_arr, $l41);
    $l42 = 759 * $c42;
    array_push($longitude_sum_arr, $l42);
    $l43 = -713 * $c43;
    array_push($longitude_sum_arr, $l43);
    $l44 = -700 * $c44;
    array_push($longitude_sum_arr, $l44);
    $l45 = 691 * $c45;
    array_push($longitude_sum_arr, $l45);
    $l46 = 596 * $c46;
    array_push($longitude_sum_arr, $l46);
    $l47 = 549 * $c47;
    array_push($longitude_sum_arr, $l47);
    $l48 = 537 * $c48;
    array_push($longitude_sum_arr, $l48);
    $l49 = 520 * $c49;
    array_push($longitude_sum_arr, $l49);
    $l50 = -487 * $c50;
    array_push($longitude_sum_arr, $l50);
    $l51 = - 399 * $c51;
    array_push($longitude_sum_arr, $l51);
    $l52 = -381 * $c52;
    array_push($longitude_sum_arr, $l52);
    $l53 = 351 * $c53;
    array_push($longitude_sum_arr, $l53);
    $l54 = -340 * $c54;
    array_push($longitude_sum_arr, $l54);
    $l55 = 330 * $c55;
    array_push($longitude_sum_arr, $l55);
    $l56 = 330 * $c56;
    array_push($longitude_sum_arr, $l56);
    $l57 = -323 * $c57;
    array_push($longitude_sum_arr, $l57);
    $l58 = 299 * $c58;
    array_push($longitude_sum_arr, $l58);
    $l59 = 294 * $c59;
    array_push($longitude_sum_arr, $l59);
    $longitude_sum_deg = array_sum($longitude_sum_arr);
    $longitude_sum_add_deg = $longitude_sum_deg + 3958 * sin($A1_rad) + 1962 * sin($L_shtr_mean_moon_longit_rad - $F_moon_arg_latitude) + 318 * sin($A2_rad);
}


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
//$temp_11 = Moon_position(2022,3,30,"05:00:00");
//echo "lon: ".$temp_11[0]."<br>";
//echo "lat: ".$temp_11[1]."<br>";
//echo "RA: ".$temp_11[6]."<br>";
//echo "DEC: ".$temp_11[7]."<br>";
//$temp_21 = geocentric_parallax_moon($temp_11[6], $temp_11[7], 2022,1,14);
//echo "RA_corr: ".$temp_21[0]."<br>";
//echo "DEC_corr: ".$temp_21[1]."<br>";


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

function additional_eq($jd){
    $T = ($jd - 2451545) / 36525;
    $W = 35999.373 * $T - 2.47;
    $d_lambda = 1 + 0.0334 * cos(deg2rad($W)) + 0.0007 * cos(deg2rad(2 * $W));
    $S_array = array();
    $s1 = 485 * cos(deg2rad(324.96 + 1934.136 * $T));
    array_push($S_array, $s1);
    $s2 = 203 * cos(deg2rad(337.23 + 32964.467 * $T));
    array_push($S_array, $s2);
    $s3 = 199 * cos(deg2rad(342.08 + 20.186 * $T));
    array_push($S_array, $s3);
    $s4 = 182 * cos(deg2rad(27.85 + 445267.112 * $T));
    array_push($S_array, $s4);
    $s5 = 156 * cos(deg2rad(73.14 + 45036.886 * $T));
    array_push($S_array, $s5);
    $s6 = 136 * cos(deg2rad(171.52 + 22518.443 * $T));
    array_push($S_array, $s6);
    $s7 = 77 * cos(deg2rad(222.54 + 65928.934 * $T));
    array_push($S_array, $s7);
    $s8 = 74 * cos(deg2rad(296.72 + 3034.906 * $T));
    array_push($S_array, $s8);
    $s9 = 70 * cos(deg2rad(243.58 + 9037.513 * $T));
    array_push($S_array, $s9);
    $s10 = 58 * cos(deg2rad(119.81 + 33718.147 * $T));
    array_push($S_array, $s10);
    $s11 = 52 * cos(deg2rad(297.17 + 150.678 * $T));
    array_push($S_array, $s11);
    $s12 = 50 * cos(deg2rad(21.02 + 2281.226 * $T));
    array_push($S_array, $s12);
    $s13 = 45 * cos(deg2rad(247.54 + 29929.562 * $T));
    array_push($S_array, $s13);
    $s14 = 44 * cos(deg2rad(325.15 + 31555.956 * $T));
    array_push($S_array, $s14);
    $s15 = 29 * cos(deg2rad(60.93 + 4443.417 * $T));
    array_push($S_array, $s15);
    $s16 = 18 * cos(deg2rad(155.12 + 67555.328 * $T));
    array_push($S_array, $s16);
    $s17 = 17 * cos(deg2rad(288.79 + 4562.452 * $T));
    array_push($S_array, $s17);
    $s18 = 16 * cos(deg2rad(198.04 + 62894.029 * $T));
    array_push($S_array, $s18);
    $s19 = 14 * cos(deg2rad(199.76 + 31436.921 * $T));
    array_push($S_array, $s19);
    $s20 = 12 * cos(deg2rad(95.39 + 14577.848 * $T));
    array_push($S_array, $s20);
    $s21 = 12 * cos(deg2rad(287.11 + 31931.756 * $T));
    array_push($S_array, $s21);
    $s22 = 12 * cos(deg2rad(320.81 + 34777.259 * $T));
    array_push($S_array, $s22);
    $s23 = 9 * cos(deg2rad(227.73 + 1222.114 * $T));
    array_push($S_array, $s23);
    $s24 = 8 * cos(deg2rad(15.45 + 16859.074 * $T));
    array_push($S_array, $s24);
    $sum_s = array_sum($S_array );
    $correct_jd = $jd + (0.00001 * $sum_s) / $d_lambda;
    return $correct_jd;

}

//// spring\fall equinox, summer\winter solstice,
function equinox_solstice_year_jd ($year_eq){
//    $year_eq = (int)$year_eq;
//    $thous_yr = $year_eq / 1000;
//    $spring_eq_jd = 1721139.2855 + 365.2421376 *  $year_eq + 0.0679190 * pow($thous_yr, 2) - 0.0027879 * pow($thous_yr, 3);
//    $summer_sols_jd = 1721233.2486 + 365.2417284 * $year_eq - 0.0530180 * pow($thous_yr, 2) + 0.0093320 * pow($thous_yr, 3);
//    $fall_eq_jd = 1721325.6978 + 365.2425055 * $year_eq - 0.1266890 * pow($thous_yr, 2) + 0.0019401 * pow($thous_yr, 3);
//    $winter_sols_jd = 1721414.3920 + 365.2428898 * $year_eq - 0.0109650 * pow($thous_yr, 2) - 0.0084885 * pow($thous_yr, 3);
//    $eq_sol_arr = array($spring_eq_jd, $summer_sols_jd, $fall_eq_jd, $winter_sols_jd);
//    return $eq_sol_arr;
    $year_eq = (int)$year_eq;
    $thous_yr_ep = ($year_eq - 2000) / 1000;
    $spring_eq_jd = 2451623.80984 + (365242.37404 * $thous_yr_ep) + (0.05169 * pow($thous_yr_ep ,2)) - (0.00411 * pow($thous_yr_ep, 3)) - (0.00057 * pow($thous_yr_ep, 4));
    $summer_sols_jd = 2451716.56767 + (365241.62603 * $thous_yr_ep) + (0.00325 * pow($thous_yr_ep ,2)) + (0.00888 * pow($thous_yr_ep ,3)) - (0.00030 * pow($thous_yr_ep ,4));
    //echo "summer_sols_jd".$summer_sols_jd."<br>";
    $fall_eq_jd = 2451810.21715 + (365242.01767 * $thous_yr_ep) - (0.11575 * pow($thous_yr_ep ,2)) + (0.00337 * pow($thous_yr_ep ,3)) + (0.00078 * pow($thous_yr_ep ,4));
    $winter_sols_jd = 2451900.05952 + (365242.74049 * $thous_yr_ep) - (0.06223 * pow($thous_yr_ep ,2)) - (0.00823 * pow($thous_yr_ep ,3)) + (0.00032 * pow($thous_yr_ep ,4));
    $mod_spring_eq_jd = additional_eq($spring_eq_jd);
    $mod_summer_sols_jd = additional_eq($summer_sols_jd);
    $mod_fall_eq_jd = additional_eq($fall_eq_jd);
    $mod_winter_sols_jd = additional_eq($winter_sols_jd);
    $eq_sol_arr = array($mod_spring_eq_jd, $mod_summer_sols_jd, $mod_fall_eq_jd, $mod_winter_sols_jd);
    return $eq_sol_arr;
}

//$tre = equinox_solstice_year_jd(2022);
//echo "spring_eq_jd: ".$tre[0]."<br>";
//echo "summer_sols_jd: ".$tre[1]."<br>";
//echo "fall_eq_jd: ".$tre[2]."<br>";
//echo "winter_sols_jd: ".$tre[3]."<br>";
//$date_jd = Date_time_from_jd($tre[2]);
//echo "year: ".$date_jd[0]."<br>";
//echo "month: ".$date_jd[1]."<br>";
//echo "day: ".$date_jd[2]."<br>";
//echo "hours: ".$date_jd[3]."<br>";
//echo "min: ".$date_jd[4]."<br>";
//echo "s: ".$date_jd[5]."<br>";

function lst_to_sep($hours_dec):string{
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

///// Corrections (refraction, disk, parallax)
function sun_corrections($DEC_dec){
    global $latitude_place_decimal_deg;
    //echo "DEC_dec: ".$DEC_dec."<br>";
    //$DEC_dec = $DEC_dec;
    $latitude_place_radian = deg2rad($latitude_place_decimal_deg);
    $sin_lat = sin($latitude_place_radian);
    $DEC_rad = deg2rad($DEC_dec);
    $psi_refraction_rad = acos(($sin_lat / cos($DEC_rad)));
    $x_disc_refr_sin = sin(deg2rad(0.833333));
    $y_way_rad = asin($x_disc_refr_sin / sin($psi_refraction_rad));
    $delta_t_sec = 240 * rad2deg($y_way_rad) / cos($DEC_rad);
    $delta_t_hours = $delta_t_sec / 3600;
    //echo "delta_t_hours: ".$delta_t_hours."<br>";
    return $delta_t_hours;
}

function Set_rise_sun_new($year_sun, $month_sun, $day_sun):array{
    global $latitude_place_decimal_deg;
    $latitude_place_radian = deg2rad($latitude_place_decimal_deg);
    $tan_lat = tan($latitude_place_radian);
    $temp_array1 = Solar_position($year_sun, $month_sun, $day_sun);
    $RA_sun_dec1 = $temp_array1[0];
    //echo "RA_sun_dec1: ".$RA_sun_dec1."<br>";
    //$RA_sun_str1 = hours_to_sep($temp_array1[0]);
    //echo "RA_sun_str1: ".$RA_sun_str1."<br>";
    //$DEC_sun_str1 = deg_to_sep($temp_array1[1]);
    //echo "DEC_sun_str1: ".$DEC_sun_str1."<br>";
    //$RA_sun_dec1 = time_dec($RA_sun_str1);
    //echo "RA_sun_dec1: ".$RA_sun_dec1."<br>";
    $RA_sun_dec_deg1 = $RA_sun_dec1 * 15;
    //echo "RA_sun_dec_deg1: ".$RA_sun_dec_deg1."<br>";
    //$DEC_sun_rad1 = Dec_inradian($DEC_sun_str1);
    $DEC_sun_rad1 = deg2rad($temp_array1[1]);
    //echo "DEC_sun_rad1: ".$DEC_sun_rad1."<br>";
    $temp_array2 = Solar_position($year_sun, $month_sun, $day_sun, "12:00:00");
    //$RA_sun_str2 = hours_to_sep($temp_array2[0]);
    //echo "RA_sun_str2: ".$RA_sun_str2."<br>";
    //$DEC_sun_str2 = deg_to_sep($temp_array2[1]);
    //echo "DEC_sun_str2: ".$DEC_sun_str2."<br>";
    $RA_sun_dec2 = $temp_array2[0];
    //echo "RA_sun_dec2: ".$RA_sun_dec2."<br>";
    $RA_sun_dec_deg2 = $RA_sun_dec2 * 15;
    //echo "RA_sun_dec_deg2: ".$RA_sun_dec_deg2."<br>";
    //$DEC_sun_rad2 = Dec_inradian($DEC_sun_str2);
    $DEC_sun_rad2 = deg2rad($temp_array2[1]);
    //echo "DEC_sun_rad2: ".$DEC_sun_rad2."<br>";
    $tan_dec1 = tan($DEC_sun_rad1);
    //echo "tan_dec1: ".$tan_dec1."<br>";
    $tan_dec2 = tan($DEC_sun_rad2);
    //echo "tan_dec2: ".$tan_dec2."<br>";
    $hour_angle_rise_rad1 = - acos(- $tan_lat * $tan_dec1);
    //echo "hour_angle_rise_rad1: ".$hour_angle_rise_rad1."<br>";
    $hour_angle_set_rad1 = acos(- $tan_lat * $tan_dec2);
    //echo "hour_angle_set_rad1: ".$hour_angle_set_rad1."<br>";


    $day_rise_temp = $day_sun;
    $day_set_temp = $day_sun;
    $loc_sid_time_rise_dec = rad2deg($hour_angle_rise_rad1) +  $RA_sun_dec_deg1;
//    if($loc_sid_time_rise_dec > 360){
//        $loc_sid_time_rise_dec = $loc_sid_time_rise_dec - 360;
//        $day_rise_temp = $day_rise_temp + 1;
//    }
    //echo "loc_sid_time_rise_dec: ".$loc_sid_time_rise_dec."<br>";
    $loc_sid_time_set_dec = rad2deg($hour_angle_set_rad1) + $RA_sun_dec_deg2;
//    if($loc_sid_time_set_dec > 360){
//        $loc_sid_time_set_dec = $loc_sid_time_set_dec - 360;
//        $day_set_temp = $day_set_temp + 1;
//    }
    //echo "loc_sid_time_set_dec: ".$loc_sid_time_set_dec."<br>";
    $loc_sid_time_rise_hr_str = hours_to_sep(($loc_sid_time_rise_dec / 15));
    //echo "loc_sid_time_rise_hr_str: ".$loc_sid_time_rise_hr_str."<br>";
    $loc_sid_time_set_hr_str = hours_to_sep($loc_sid_time_set_dec / 15);
    //echo "loc_sid_time_set_hr_str: ".$loc_sid_time_set_hr_str."<br>";

    for($i = 1; $i < 5; $i++){

        $temp_loc_civil_time_rise = LST_to_loc_CT($year_sun, $month_sun,  $day_rise_temp, $loc_sid_time_rise_hr_str);
        //echo "temp_loc_civil_time_rise: ".$temp_loc_civil_time_rise."<br>";
        $temp_loc_civil_time_rise_sep = lst_to_sep($temp_loc_civil_time_rise);
        //echo "temp_loc_civil_time_rise_sep: ".$temp_loc_civil_time_rise_sep."<br>";
        $temp_array_sun_coord = Solar_position($year_sun, $month_sun, $day_sun, $temp_loc_civil_time_rise_sep);
        $temp_RA_deg = $temp_array_sun_coord[0] * 15;
        $temp_DEC_rad = deg2rad($temp_array_sun_coord[1]);
        $tan_DEC_temp = tan($temp_DEC_rad);
        $temp_hour_angle_rise_rad = - acos(- $tan_lat * $tan_DEC_temp);
        $temp_loc_sid_time_rise_deg = rad2deg($temp_hour_angle_rise_rad) +  $temp_RA_deg;
//        if($temp_loc_sid_time_rise_deg > 360){
//            $temp_loc_sid_time_rise_deg = $temp_loc_sid_time_rise_deg - 360;
//        }
        $loc_sid_time_rise_hr_str = hours_to_sep($temp_loc_sid_time_rise_deg / 15);
        //echo "loc_sid_time_rise_hr_str: ".$loc_sid_time_rise_hr_str."<br>";
    }

    for($g = 1; $g < 5; $g++){

        $temp_loc_civil_time_set = LST_to_loc_CT($year_sun, $month_sun,  $day_set_temp, $loc_sid_time_set_hr_str);
        //echo "temp_loc_civil_time_set: ".$temp_loc_civil_time_set."<br>";
        $temp_loc_civil_time_set_sep = hours_to_sep($temp_loc_civil_time_set);

        $temp_array_sun_coord_set = Solar_position($year_sun, $month_sun, $day_sun, $temp_loc_civil_time_set_sep);
        $temp_RA_deg_set = $temp_array_sun_coord_set[0] * 15;
        $temp_DEC_rad_set = deg2rad($temp_array_sun_coord_set[1]);
        $tan_DEC_temp_set = tan($temp_DEC_rad_set);
        $temp_hour_angle_set_rad = acos(- $tan_lat * $tan_DEC_temp_set);
        //echo "temp_hour_angle_set_rad: ".$temp_hour_angle_set_rad."<br>";
        $temp_loc_sid_time_set_deg = rad2deg($temp_hour_angle_set_rad) +  $temp_RA_deg_set;
        //echo "temp_loc_sid_time_set_deg: ".$temp_loc_sid_time_set_deg."<br>";
//        if($temp_loc_sid_time_set_deg > 360){
//            //$temp_loc_sid_time_set_deg = $temp_loc_sid_time_set_deg - 360;
//            //$day_set_temp = $day_set_temp + 1;
//        }
        $loc_sid_time_set_hr_str = lst_to_sep($temp_loc_sid_time_set_deg / 15);
        //echo "loc_sid_time_set_hr_str: ".$loc_sid_time_set_hr_str."<br>";
    }

    $local_civil_time_rise = $temp_loc_civil_time_rise - sun_corrections($temp_array_sun_coord[1]);
    //echo "local_civil_time_rise: ".$local_civil_time_rise."<br>";
    if ($local_civil_time_rise > 24){
        $local_civil_time_rise = $local_civil_time_rise - 24;
    }
    elseif ($local_civil_time_rise < 0){
        $local_civil_time_rise = $local_civil_time_rise + 24;
    }
    $local_civil_time_set = $temp_loc_civil_time_set + sun_corrections($temp_array_sun_coord_set[1]);
    //echo "local_civil_time_set: ".$local_civil_time_set."<br>";
    if ($local_civil_time_set > 24){
        $local_civil_time_set = $local_civil_time_set - 24;
    }
    elseif ($local_civil_time_set < 0){
        $local_civil_time_set = $local_civil_time_set + 24;
    }
    $rise_set_arr = array($local_civil_time_rise, $local_civil_time_set);

    return $rise_set_arr;
}
//
//$temp = Set_rise_sun_new(2022,1,2);
//$a = lst_to_sep($temp[0]);
//$b = lst_to_sep($temp[1]);
//echo "Rise: ".$a."<br>";
//echo "Set: ".$b."<br>";

function month_sun_time_new($year_sun1, $month_sun1){
    $month_array_sun_time = array();
    $month_31 = array(1,3,5,7,8,10,12);
    $month_30 = array(4,6,9,11);
    $counter_days = 0;
    if(in_array($month_sun1, $month_31)){
        $counter_days = 31;
    }
    elseif(in_array($month_sun1, $month_30)){
        $counter_days = 30;
    }
    elseif($month_sun1 == 2){
        if(leap_year_check($year_sun1)){
            $counter_days = 29;
        }
        else{
            $counter_days = 28;
        }
    }
    $i = 0;
    while($i < $counter_days){
        $temp_array_new = Set_rise_sun_new($year_sun1, $month_sun1,($i+1));
        $temp1 = lst_to_sep($temp_array_new[0]);
        $temp2 = lst_to_sep($temp_array_new[1]);
        $temp_arr_tot = array($temp1, $temp2);
        array_push($month_array_sun_time, $temp_arr_tot);
        $i  = $i  + 1;
    }

    return $month_array_sun_time;
}
//$temp_array_new = Set_rise_sun_new(2022, 1,1);
////$a = lst_to_sep($temp_array_new[0]);
////$b = lst_to_sep($temp_array_new[1]);
////echo "Rise: ".$a."<br>";
////echo "Set: ".$b."<br>";
//for($i = 0; $i < 30; $i++){
//   $temp_array_new = Set_rise_sun_new(2022, 1, 1);
//}

//$temp2 = month_sun_time_new(2022,1);
//echo "Rise: ".$temp2[0][0]."<br>";
//echo "Set: ".$temp2[0][1]."<br>";
