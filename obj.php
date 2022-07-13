<?php
require "test.php";

function azimuth_altitude($RA_sep, $DEC_sep, $year, $month, $day, $Local_time = "00:00:00"){
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