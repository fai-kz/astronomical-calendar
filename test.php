<?php

//$year_jd = 1987;
//$month_jd = 3;
//$date_jd = 6;

//// Julian Date /////////////////// (float)
function julian_date($year_jd, $month_jd,  $date_jd ){
    if ($month_jd == 1 or $month_jd == 2){
        $year_jd = $year_jd - 1;
        $month_jd = $month_jd +12;
    }
    $a_parameter = (int)($year_jd / 100); // amount of last centuries
    $b_parameter = 2 - $a_parameter + (int)($a_parameter / 4);
    $c_parameter = (int)(365.25 * $year_jd);
    $d_parameter = (int)(30.6001*($month_jd + 1)) ;
    $jd = 1720994.5 + $b_parameter + $c_parameter + $d_parameter + $date_jd;
    return $jd;
}
///////////////////
//echo $a = julian_date($year_jd, $month_jd,  $date_jd);
//////////////////

//// Leap year checking (bool)
function leap_year_check($year){
    if($year%400 == 0 or ($year % 4 == 0 and $year % 100 != 0)){
        return true;
    }
    else {
        return false;
    }
}
//$b = leap_year_check(1700);
//echo "1700 ".$b."<br />";
//$c = leap_year_check(2000);
//echo "2000 ".$c."<br />";
//$d = leap_year_check(2004);
//echo "2004 ".$d."<br />";

///// Number of the day in year (int)

function day_numb($year_numb, $month_numb, $date_numb){
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

//$day = day_numb(1980, 4,22);
//echo $day;

///// Conversion a time into decimal system (float)

function time_dec($time_input){
    $first_position_sep = mb_strpos($time_input, ':'); // To find a place of first position ":" in $time_input
    $horus = mb_substr($time_input, 0, $first_position_sep); // Trim $time_input to find first digits of time (horus)
    $second_position_sep = strpos($time_input, ':', $first_position_sep + 1); // to find second input ":" in text time
    $minutes = mb_substr($time_input, $first_position_sep +1, $second_position_sep - $first_position_sep -1); // To find minutes in time
    $seconds = mb_substr($time_input, $second_position_sep +1, strlen($time_input) - $second_position_sep - 1);// to find seconds in time
    $total_time_decimal = $horus + ($minutes / 60) + ($seconds / 3600); // a time recalculated in decimal system
    if(is_numeric($total_time_decimal)==false or $total_time_decimal > 24){ // A check of $total_dec_time as a number
        return 0;
    }
    else{
        return $total_time_decimal;
    }
}
//$time_op = "9:46:54.4";
//$time_check = time_dec($time_op);
//echo "Time check ".$time_check."<br>";

function B_const($year_gst){
    $jd_year = julian_date($year_gst, 1, 0);
    $S = $jd_year - 2415020;
    $T = $S / 36525;
    $R = 6.6460656 + (2400.051262 * $T) + (0.00002581 * pow($T, 2));
    $U = $R - 24 * ($year_gst - 1900);
    $B = 24 - $U;
    return $B;
}

//// Greenwich siderial time (GST) (float)

function GST($year_gst, $month_gst, $day_gst, $time_ut= "00:00:00"){
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




//// Local siderial time (LST) (float)

function LST($year_lst, $month_lst, $day_lst, $time_lst= "00:00:00"){
    $A = 0.0657098;
    $C = 1.002738;
    $Time_Zone = 6;
    $longitude_assy_horus = (76.87/15);
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
    $LST_dec = $GST_dec + $longitude_assy_horus;
    if($LST_dec > 24){
        $LST_dec = $LST_dec  - 24;
    }
    elseif($LST_dec < 0){
        $LST_dec = $LST_dec  + 24;
    }
    return $LST_dec;
}

//$z = LST(2021,12,29, "13:35:40");
//echo $z;

///// United time from siderial time (float);
function UT($year_gst, $month_gst, $day_gst, $time_gst){
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
//$z = UT(2021,12,30, "13:25:00");
//echo $z;

///// Local time from local siderial time (float)

function LT_ST_time($year_lst, $month_lst, $day_lst, $time_lst = "00:00:00"){
    $A = 0.0657098;
    $D = 0.997270;
    $Time_Zone = 6;
    $longitude_assy_horus = (76.87/15);
    $loc_sid_time_dec = time_dec($time_lst);
    //$loc_sid_time_dec = 21.518;
    $ut_sid_time = $loc_sid_time_dec - $longitude_assy_horus;
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

//$z = LT_ST_time(2021,12,30, "11:09:29");
//echo "z ".$z."<br>";

/// RA transformation from AA:BB:CC to the radian system (float)
function RA_inradian($RA_obj){
    $bool_ra = true;
    if (substr_count($RA_obj, ':')!=2){ // Here we check a symbol ":" in input form. It must contain only 2
        $bool_ra = false;
    }
    else{
        $first_position_ra = mb_strpos($RA_obj, ':'); // To find a place of first position ":" in $RA_obj
        $first_digits_ra = mb_substr($RA_obj, 0, $first_position_ra); // Trim $obj_DEC to find first digits of ra (degrees)
        echo "first_digits_ra ".$first_digits_ra."<br>";
        $negative_sign_check = mb_substr($RA_obj, 0, 1); // A check of negative sign "-" in DEC
        $second_position_ra = strpos($RA_obj, ':', $first_position_ra + 1); // to find second input ":" in text RA
        $second_digits_ra = mb_substr($RA_obj, $first_position_ra +1, $second_position_ra - $first_position_ra -1); // To find minutes in RA
        echo "second_digits_ra ".$second_digits_ra."<br>";
        $third_digits_ra = mb_substr($RA_obj, $second_position_ra +1, strlen($RA_obj) - $second_position_ra - 1);// to find seconds in RA
        if($first_digits_ra > 24 or $second_digits_ra > 60 or $negative_sign_check =="-"){ // a check for correction input a RA (no more 24 horus, and minutes no more 60)
            $bool_ra = false;
        }

        $total_ra_decimal = $first_digits_ra*15 + ($second_digits_ra * 0.25) + ($third_digits_ra * 0.004); // RA recalculated in 360 degrees system
        echo "total_ra_decimal".$total_ra_decimal."<br>";
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
//$a = RA_inradian("04:35:55");
//echo "a ".$a."<br>";
//$b = DEC_inradian("16:30:29");
//echo "b ".$b."<br>";



/// Dec transformation from AA:BB:CC to the radian system (float)
function DEC_inradian($DEC_obj){
    $bool_dec = true;
    if (substr_count($DEC_obj, ':')!=2){ // Here we check a symbol ":" in input form. It must contain only 2
        $bool_dec = false;
    }
    else{
        $first_position_dec = mb_strpos($DEC_obj, ':'); // To find a place of first position ":" in $DEC_obj
        $first_digits_dec = mb_substr($DEC_obj, 0, $first_position_dec); // Trim $DEC_obj to find first digits of dec (degrees)
        $negative_sign_check = mb_substr($DEC_obj, 0, 1); // A check of negative sign "-" in DEC
        $second_position_dec = strpos($DEC_obj, ':', $first_position_dec + 1); // to find second input ":" in text dec
        $second_digits_dec = mb_substr($DEC_obj, $first_position_dec +1, $second_position_dec - $first_position_dec -1); // To find minutes in dec
        $third_digits_dec = mb_substr($DEC_obj, $second_position_dec +1, strlen($DEC_obj) - $second_position_dec - 1);// to find seconds in DEC
        if($first_digits_dec > 90 or $second_digits_dec>60){ // a check for correction input a dec (no more 90 degree, and minutes no more 60)
            $bool_dec = false;
        }
        if($negative_sign_check != "-"){ // if dec positive
            $total_dec_decimal = $first_digits_dec +($second_digits_dec / 60) + ($third_digits_dec / 3600); // Dec in decimal system
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

//// transform decimal hours to str with ":" (string)
function hours_to_sep($hours_dec){
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
    $str_coord = $int_hours.":".$minutes.":".$seconds;
    return $str_coord;
}

//// Solar coordinates (RA, DEC)

function Solar_position ($year_sol, $month_sol, $day_sol){
    $n_days = julian_date($year_sol, $month_sol, $day_sol) - 2451545;
    $mean_longitude_deg = 280.460 + 0.9856474 * $n_days;
    $mean_anomaly_deg = 357.528 + 0.98560003 * $n_days;
    $mean_anomaly_rad = deg2rad($mean_anomaly_deg);
    $ecliptic_longitude_deg = $mean_longitude_deg + 1.915 * sin($mean_anomaly_rad) + 0.020 * sin(2*$mean_anomaly_rad);
    $ecliptic_longitude_rad = deg2rad($ecliptic_longitude_deg);
    $ecliptic_inclination_deg = 23.439 - 0.0000004 * $n_days;
    $ecliptic_inclination_rad = deg2rad($ecliptic_inclination_deg);
    $RA_sun_deg = rad2deg(atan(cos($ecliptic_inclination_rad) * tan($ecliptic_longitude_rad)));
    $DEC_sun_deg = rad2deg(asin(sin($ecliptic_inclination_rad ) * sin($ecliptic_longitude_rad)));
    $solar_position_deg = array($RA_sun_deg, $DEC_sun_deg);
    return $solar_position_deg;
}
$k = Solar_position(2021, 12, 30);
echo $k[0]."<br>";
echo $k[1]."<br>";



//// Rise and set of star from (Ra, Dec, Date) (array[string])
function Set_Rise_indate($RA_obj, $Dec_obj, $year_lst, $month_lst, $day_lst){
    $latitude_assy_radian = deg2rad(43.25);
    echo "latitude_assy_radian ".$latitude_assy_radian."<br>";
    $RA_rad = RA_inradian($RA_obj);
    echo "RA_rad ".$RA_rad."<br>";
    $RA_hour_dec = rad2deg($RA_rad);
    echo "RA_hour_dec ".$RA_hour_dec."<br>";
    $DEC_rad = DEC_inradian($Dec_obj);
    echo "DEC_rad ".$DEC_rad."<br>";
    $tan_lat = tan($latitude_assy_radian);
    echo "tan_lat ".$tan_lat."<br>";
    $tan_dec = tan($DEC_rad);
    echo "tan_dec ".$tan_dec."<br>";
    $hour_angle_rise_rad = (2*M_PI)- acos(- $tan_lat * $tan_dec);
    echo "hour_angle_rise_rad ".$hour_angle_rise_rad."<br>";
    $hour_angle_set_rad = acos(- $tan_lat * $tan_dec);
    echo "hour_angle_set_rad ".$hour_angle_set_rad."<br>";
    $hour_angle_rise_hour_dec = rad2deg($hour_angle_rise_rad);
    echo "hour_angle_rise_hour_dec ".$hour_angle_rise_hour_dec."<br>";
    $hour_angle_set_hour_dec = rad2deg($hour_angle_set_rad);
    echo "hour_angle_set_hour_dec ".$hour_angle_set_hour_dec."<br>";
    $loc_sid_time_rise_dec = $hour_angle_rise_hour_dec + $RA_hour_dec;
    echo "loc_sid_time_rise_dec  ".$loc_sid_time_rise_dec."<br>";
    if($loc_sid_time_rise_dec > 360){$loc_sid_time_rise_dec = $loc_sid_time_rise_dec - 360;}
    if($loc_sid_time_rise_dec < 0){
        $loc_sid_time_rise_dec = $loc_sid_time_rise_dec + 360;
    }
    $loc_sid_time_set_dec = $hour_angle_set_hour_dec + $RA_hour_dec;
    echo "loc_sid_time_set_dec  ".$loc_sid_time_set_dec."<br>";
    if($loc_sid_time_set_dec > 360){$loc_sid_time_set_dec = $loc_sid_time_set_dec - 360;}
    if($loc_sid_time_set_dec < 0){$loc_sid_time_set_dec = $loc_sid_time_set_dec + 360;
    }
    function dec_to_str($decimal){
        echo "decimal".$decimal."<br>";
        $int_part = (int)($decimal/15);
        echo "int_part ".$int_part."<br>";
        $other_part1 = abs($decimal/15) - abs((int)($decimal/15));
        echo "other_part1 ".$other_part1."<br>";
        $other_part2 = $other_part1 * 60;
        echo "other_part2".$other_part2."<br>";
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
    echo "Dec_to_str".dec_to_str(322.781)."<br>";
    $loc_sid_time_rise_sep = dec_to_str($loc_sid_time_rise_dec);
    echo "loc_sid_time_rise_sep  ".$loc_sid_time_rise_sep."<br>";
    $loc_sid_time_set_sep = dec_to_str($loc_sid_time_set_dec);
    echo "loc_sid_time_set_sep  ".$loc_sid_time_set_sep."<br>";
    $local_time_rise_dec = LT_ST_time($year_lst, $month_lst, $day_lst, $loc_sid_time_rise_sep);
    echo "local_time_rise_dec  ".$local_time_rise_dec."<br>";
    $local_time_set_dec = LT_ST_time($year_lst, $month_lst, $day_lst, $loc_sid_time_set_sep);
    echo "local_time_set_dec  ".$local_time_set_dec."<br>";
    $local_time_rise_dec_norm = hours_to_sep($local_time_rise_dec);
    echo "local_time_rise_dec_norm ".$local_time_rise_dec_norm."<br>";
    $local_time_set_dec_norm = hours_to_sep($local_time_set_dec);
    echo "local_time_set_dec_norm ".$local_time_set_dec_norm."<br>";

    $rise_set_arr= array();
    array_push($rise_set_arr, $local_time_rise_dec_norm);
    array_push($rise_set_arr, $local_time_set_dec_norm);
    return $rise_set_arr;
}

//$m = Set_Rise_indate("21:10:13","-16:18:32",2022, 2, 4);
//echo "Rise ".$m[0]."<br>";
//echo "Set ".$m[1]."<br>";

