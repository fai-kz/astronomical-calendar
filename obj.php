<?php
require "test.php";

///// Azimuth and altitude of object for local time
function azimuth_altitude($RA_sep, $DEC_sep, $year, $month, $day, $Local_time = "00:00:00"):array{
    global $latitude_place_decimal_deg;
    global $time_zone;
    //$Local_time_corr = hours_to_sep((time_dec($Local_time) - $time_zone));

    $RA_dec = time_dec($RA_sep);
    $DEC_dec = time_dec($DEC_sep);
    $local_sid_time_dec = LST($year, $month, $day, $Local_time);
    $hour_ang_dec = ($local_sid_time_dec - $RA_dec) * 15;
    if ($hour_ang_dec < 0){
        $hour_ang_dec = $hour_ang_dec + 360;
    }
    elseif ($hour_ang_dec > 360){
        $hour_ang_dec = $hour_ang_dec - 360;
    }
    $altitude_rad = asin(sin(deg2rad($DEC_dec)) * sin(deg2rad($latitude_place_decimal_deg)) + cos(deg2rad($DEC_dec)) * cos(deg2rad($latitude_place_decimal_deg)) * cos(deg2rad($hour_ang_dec)));
    $altitude_deg = rad2deg($altitude_rad);
    /// Checking step
    if (sin(deg2rad($hour_ang_dec)) < 0){
        $bool_check = False;
    }
    else{
        $bool_check = True;
    }

    $azimuth_rad = acos((sin(deg2rad($DEC_dec)) - sin(deg2rad($latitude_place_decimal_deg)) * sin($altitude_rad)) / (cos(deg2rad($latitude_place_decimal_deg)) * cos($altitude_rad)));
    $azimuth_deg = rad2deg($azimuth_rad);
    if ($bool_check){
        $azimuth_deg = 360 - $azimuth_deg;
    }
    if ($azimuth_deg > 360){
        $azimuth_deg = $azimuth_deg - 360;
    }
    elseif ($azimuth_deg < 0){
        $azimuth_deg = $azimuth_deg + 360;
    }

    $array = array($azimuth_deg, $altitude_deg);
    return $array;
}

///// Local time of culmination of object
function obj_culmination($RA_sep, $year, $month, $day){
    global $Time_Zone;
    $culm_dec = LST_to_loc_CT_calc($year, $month, $day, $RA_sep) + $Time_Zone;
    if ($culm_dec > 24){
        $culm_dec = $culm_dec - 24;
    }
    elseif ($culm_dec < 0){
        $culm_dec = $culm_dec + 24;
    }
    return $culm_dec;
}

//$a = azimuth_altitude("19:51:53","08:55:42",2022,5,20, "21:20:00");
//$b = obj_culmination("19:51:53", 2022,5,20);
//echo "azimuth_deg: ".$a[0]."<br>";
//echo "alt: ".$a[1]."<br>";
//echo "culm: ".$b."<br>";

function angular_dist($RA1_sep, $DEC1_sep, $RA2_sep, $DEC2_sep){
    $RA1_dec = time_dec($RA1_sep) * 15;
    $DEC1_dec = time_dec($DEC1_sep);
    $RA2_dec = time_dec($RA2_sep) * 15;
    $DEC2_dec = time_dec($DEC2_sep);
    $ang_dist_rad = acos(sin(deg2rad($DEC1_dec)) * sin(deg2rad($DEC2_dec)) + cos(deg2rad($DEC1_dec)) * cos(deg2rad($DEC2_dec)) * cos(deg2rad($RA1_dec - $RA2_dec)));
    $ang_dist_deg = rad2deg($ang_dist_rad);
    return $ang_dist_deg;
}

function angular_dist2($RA1_hour_dec, $DEC1_dec, $RA2_hour_dec, $DEC2_dec){
    $RA1_dec = $RA1_hour_dec * 15;
    $DEC1_dec = $DEC1_dec;
    $RA2_dec = $RA2_hour_dec * 15;
    $DEC2_dec = $DEC2_dec;
    $ang_dist_rad = acos(sin(deg2rad($DEC1_dec)) * sin(deg2rad($DEC2_dec)) + cos(deg2rad($DEC1_dec)) * cos(deg2rad($DEC2_dec)) * cos(deg2rad($RA1_dec - $RA2_dec)));
    $ang_dist_deg = rad2deg($ang_dist_rad);
    return $ang_dist_deg;
}

/// The function calculates arctangent atan $A/$B in decimal degrees
function atan_quadrant($A, $B){
    $quadrant_AB = 0;
    if($A > 0 and $B > 0){
        $quadrant_AB = 1;
    }
    if($A > 0 and $B < 0){
        $quadrant_AB = 2;
    }
    if($A < 0 and $B < 0){
        $quadrant_AB = 3;
    }
    if($A < 0 and $B > 0){
        $quadrant_AB = 4;
    }
    $RA_z_dec = rad2deg(atan($A / $B));
    $quadrant_ra = 0;
    if($RA_z_dec > 0){
        if($RA_z_dec > 0 and $RA_z_dec < 90 ){
            $quadrant_ra = 1;
        }
        if($RA_z_dec > 90 and $RA_z_dec < 180){
            $quadrant_ra = 2;
        }
        if(($RA_z_dec > 180 and $RA_z_dec < 270)){
            $quadrant_ra = 3;
        }
        if(($RA_z_dec > 270 and $RA_z_dec < 360)){
            $quadrant_ra = 4;
        }
    }
    else{
        if(abs($RA_z_dec) > 0 and abs($RA_z_dec) < 90 ){
            $quadrant_ra = 4;
        }
        if(abs($RA_z_dec) > 90 and abs($RA_z_dec) < 180){
            $quadrant_ra = 3;
        }
        if((abs($RA_z_dec) > 180 and abs($RA_z_dec) < 270)){
            $quadrant_ra = 2;
        }
        if((abs($RA_z_dec) > 270 and abs($RA_z_dec) < 360)){
            $quadrant_ra = 1;
        }
    }

    if($RA_z_dec > 0 and ($quadrant_ra != $quadrant_AB)){
        if($quadrant_ra == 1 and $quadrant_AB == 3){
            $RA_z_dec = $RA_z_dec + 180;
        }
        if($quadrant_ra == 2 and $quadrant_AB == 4){
            $RA_z_dec = $RA_z_dec + 180;
        }
        if($quadrant_ra == 3 and $quadrant_AB == 1){
            $RA_z_dec = $RA_z_dec - 180;
        }
        if($quadrant_ra == 4 and $quadrant_AB == 2){
            $RA_z_dec = $RA_z_dec - 180;
        }
    }
    elseif($RA_z_dec < 0 and ($quadrant_ra != $quadrant_AB)){
        if($quadrant_ra == 1 and $quadrant_AB == 3){
            $RA_z_dec = $RA_z_dec - 180;
        }
        if($quadrant_ra == 2 and $quadrant_AB == 4){
            $RA_z_dec = $RA_z_dec - 180;
        }
        if($quadrant_ra == 3 and $quadrant_AB == 1){
            $RA_z_dec = $RA_z_dec + 180;
        }
        if($quadrant_ra == 4 and $quadrant_AB == 2){
            $RA_z_dec = $RA_z_dec + 180;

        }
    }
    elseif ($RA_z_dec < 0 and ($quadrant_ra == $quadrant_AB) and (($A / $B) > 0)){
        $RA_z_dec = $RA_z_dec + 360;
    }
    elseif ($RA_z_dec > 0 and ($quadrant_ra == $quadrant_AB) and (($A / $B) < 0)){
        $RA_z_dec = $RA_z_dec - 360;
    }
    return $RA_z_dec;
}

function coord_transform_jd2000_to_date ($year, $month, $day, $RA_start_hour_dec, $DEC_start_dec){
    $jd = julian_date($year, $month, $day);
    $t = ($jd - 2451545) / 36525;
    $RA_start_dec = $RA_start_hour_dec * 15;
    $dzeta = (2306.2181 * $t + 0.30188 * pow($t, 2) + 0.017998 * pow($t, 3)) / 3600;
    $z = (2306.2181 * $t + 1.09468 * pow($t, 2) + 0.018203 * pow($t, 3)) / 3600;
    $theta = (2004.3109 * $t - 0.42665 * pow($t, 2) - 0.041833 * pow($t, 3)) / 3600;
    $A = cos(deg2rad($DEC_start_dec)) * sin(deg2rad( $RA_start_dec + $dzeta));
    $B = cos(deg2rad($theta)) * cos(deg2rad($DEC_start_dec)) * cos(deg2rad($RA_start_dec + $dzeta)) - sin(deg2rad($theta)) * sin(deg2rad($DEC_start_dec));
    $C = sin(deg2rad($theta)) * cos(deg2rad($DEC_start_dec)) * cos(deg2rad($RA_start_dec + $dzeta)) + cos(deg2rad($theta)) * sin(deg2rad($DEC_start_dec));

    $RA_z_dec = atan_quadrant($A, $B);
    $RA_new_dec = ($RA_z_dec + $z) / 15;
    $DEC_new_dec = rad2deg(asin($C));
    $new_coord_dec =  array( $RA_new_dec, $DEC_new_dec);
    return $new_coord_dec;
}
//
//$RA = time_dec("16:29:25");
//$DEC = time_dec("-26:25:56");
//$temp = coord_transform_jd2000_to_date(2022,6,19, $RA, $DEC);
//echo "RA ".hours_to_sep($temp[0])."<br>";
//echo "DEC ".deg_to_sep($temp[1])."<br>";

//// Rise and set of star from (Ra, Dec, Date) (array[string])
function Set_Rise_indate($RA_dec_hour, $Dec_dec_deg, $year_lst, $month_lst, $day_lst):array{
    global $latitude_place_decimal_deg;
    global $Time_Zone;
    $latitude_place_radian = deg2rad($latitude_place_decimal_deg);
    $RA_rad = deg2rad($RA_dec_hour * 15);
    //$RA_hour_dec = rad2deg($RA_rad);
    $DEC_rad = deg2rad($Dec_dec_deg);
    $tan_lat = tan($latitude_place_radian);
    $tan_dec = tan($DEC_rad);
    $hour_angle_rise_rad = - acos(- $tan_lat * $tan_dec);
    //echo ($hour_angle_rise_rad *  180/M_PI)."<br>";
    $hour_angle_set_rad = acos(- $tan_lat * $tan_dec);
    //echo ($hour_angle_set_rad* 180/M_PI) ."<br>";
    $hour_angle_rise_deg_dec = rad2deg($hour_angle_rise_rad);
    $hour_angle_set_deg_dec = rad2deg($hour_angle_set_rad);
    $loc_sid_time_rise_dec = $hour_angle_rise_deg_dec  + ($RA_dec_hour * 15);
    if($loc_sid_time_rise_dec > 360){$loc_sid_time_rise_dec = $loc_sid_time_rise_dec - 360;}
    if($loc_sid_time_rise_dec < 0){
        $loc_sid_time_rise_dec = $loc_sid_time_rise_dec + 360;
    }
    $loc_sid_time_set_dec = $hour_angle_set_deg_dec + ($RA_dec_hour * 15);
    if($loc_sid_time_set_dec > 360){$loc_sid_time_set_dec = $loc_sid_time_set_dec - 360;}
    if($loc_sid_time_set_dec < 0){$loc_sid_time_set_dec = $loc_sid_time_set_dec + 360;
    }
    $loc_sid_time_rise_sep = hours_to_sep($loc_sid_time_rise_dec / 15);
    $loc_sid_time_set_sep = hours_to_sep($loc_sid_time_set_dec / 15);
    $local_time_rise_dec = LST_to_loc_CT_calc($year_lst, $month_lst, $day_lst, $loc_sid_time_rise_sep) + $Time_Zone;
    $local_time_set_dec = LST_to_loc_CT_calc($year_lst, $month_lst, $day_lst, $loc_sid_time_set_sep) + $Time_Zone;

    if($local_time_rise_dec > 24){
        $local_time_rise_dec = $local_time_rise_dec - 24;
    }
    elseif ($local_time_rise_dec < 0){
        $local_time_rise_dec = $local_time_rise_dec + 24;
    }

    if($local_time_set_dec > 24){
        $local_time_set_dec = $local_time_set_dec - 24;
    }
    elseif ($local_time_set_dec < 0){
        $local_time_set_dec = $local_time_set_dec + 24;
    }
    $rise_set_arr= array();
    array_push($rise_set_arr, $local_time_rise_dec);
    array_push($rise_set_arr, $local_time_set_dec);
    //echo "latitude_place_decimal_deg".$latitude_place_decimal_deg. "<br>";
    //echo "Time_zone".$Time_Zone. "<br>";
    return $rise_set_arr;
}

//$a = Set_Rise_indate(10.7199, 8.11, 2022, 9, 2);
//echo $a[0]."<br>";
//echo $a[1]."<br>";
//$temp_array = coord_transform_jd2000_to_date(2022,7,31, time_dec("14:15:38"), time_dec("19:10:07"));
//echo hours_to_sep($temp_array[0])."<br>";
//echo hours_to_sep($temp_array[1])."<br>";
//
//$RA = time_dec("14:16:42");
//$DEC = time_dec("19:03:44");
//
//echo "$RA <br>";
//echo "$DEC <br>";
//
//$k = Set_Rise_indate($RA, $DEC, 2022,7, 31);
//echo "Rise: ".$k[0]."<br>";
//echo "Set: ".$k[1]."<br>";
//echo "Rise: ".lst_to_sep($k[0])."<br>";
//echo "Set: ".lst_to_sep($k[1])."<br>";
//
//$m = Set_Rise_indate($k[0],$k[1],date("Y"), date("m"), date("d"));
//echo "Rise of the Sun: ".$m[0]."<br>";
//echo "Set of the Sun: ".$m[1]."<br>";

function obj_coord_dec($time_input):array{
    $first_position_sep = mb_strpos($time_input, ':'); // To find a place of first position ":" in $time_input
    $first = mb_substr($time_input, 0, $first_position_sep); // Trim $time_input to find first digits of time (horus)
    $flag_first = 0;
    if (is_numeric($first)){
        $first = (int)$first;
        $flag_first = 1;
    }

    $second_position_sep = strpos($time_input, ':', $first_position_sep + 1); // to find second input ":" in text time
    $minutes = mb_substr($time_input, $first_position_sep +1, $second_position_sep - $first_position_sep -1); // To find minutes in time
    $flag_min = 0;
    if (is_numeric($minutes)){
        $minutes = (int)$minutes;
        $flag_min = 1;
    }
    $seconds = mb_substr($time_input, $second_position_sep +1, strlen($time_input) - $second_position_sep - 1);// to find seconds in time
    $flag_sec = 0;
    if (is_numeric($seconds)){
        $seconds = (float)$seconds;
        $flag_sec = 1;
    }
    if($first >= 0){
        $total_coord_decimal = $first + ($minutes / 60) + ($seconds / 3600); // a time recalculated in decimal system
    }
    else{
        $total_coord_decimal = (abs($first)+ ($minutes / 60) + ($seconds / 3600)) * (-1);
    }
    if ($flag_first and $flag_min and $flag_sec){
        return array(1, $total_coord_decimal);

    }
    else{
        return array(0, 0);
    }
}

//Example of using time_dec function
//$time_op = "21:13:46";
//$time_check = obj_coord_dec($time_op);
//echo "Time check ".$time_check[1]."<br>";

