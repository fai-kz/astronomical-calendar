<link rel='stylesheet' href='css/styles.css'>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // наш код
}

date_default_timezone_set(UTC);// Устанавливаем часовой пояс UTC

$co_researhers = $_POST['corr']; //Co-authors
$chose_inst ='';

$institute = $_POST['institute'];
switch ($institute){  // Chose of institute //
    case '0': $chose_inst ='none';
    break;
    case 'fai': $chose_inst ='FAI';
    break;
    case 'kaznu': $chose_inst ='KazNU';
    break;
    case 'iki': $chose_inst ='SRI RAS';
        break;
}

$conditions = $_POST['conditions']; // some conditions
$telescope = $_POST['telescope']; // telescope
$message1 = $_POST['type_obs1'];
$choice_obs=''; // type of observations
switch ($message1){
    case 'phot_obs': $choice_obs ='photometry';
        break;
    case 'spec_obs': $choice_obs ='spectroscopy';
        break;
    case 'both': $choice_obs ='photometry and spectroscopy';
        break;
    default: $choice_obs ='none';
}
$message = 'none';
$date1 = $_POST['calend_start'];
$date2 = $_POST['calend_end'];


// RA

$obj_RA = $_POST['obj_RA'];
$bool_ra = true; // flag of valid RA

////// A check of right ascension (correct input RA)
if (substr_count($obj_RA, ':')!=2){ // Here we check a symbol ":" in input form. It must contain only 2
    $bool_ra = false;
}
else{
    $first_position_ra = mb_strpos($obj_RA, ':'); // To find a place of first position ":" in $obj_RA
    $first_digits_ra = mb_substr($obj_RA, 0, $first_position_ra); // Trim $obj_DEC to find first digits of ra (degrees)
    $negative_sign_check = mb_substr($obj_RA, 0, 1); // A check of negative sign "-" in DEC
    $second_position_ra = strpos($obj_RA, ':', $first_position_ra + 1); // to find second input ":" in text RA
    $second_digits_ra = mb_substr($obj_RA, $first_position_ra +1, $second_position_ra - $first_position_ra -1); // To find minutes in RA
    $third_digits_ra = mb_substr($obj_RA, $second_position_ra +1, strlen($obj_RA) - $second_position_ra - 1);// to find seconds in RA
    if($first_digits_ra > 24 or $second_digits_ra > 60 or $negative_sign_check =="-"){ // a check for correction input a RA (no more 24 horus, and minutes no more 60)
        $bool_ra = false;
    }

    $total_ra_decimal = $first_digits_ra*15 + ($second_digits_ra / 60) + ($third_digits_ra / 3600); // RA recalculated in 360 degrees system
    if(is_numeric($total_ra_decimal)==false or $total_ra_decimal > 360){ // A check of $total_dec_decimal as a number
        $bool_ra = false;
    }
}


// DEC

$obj_DEC = $_POST['obj_DEC'];
$bool_dec = true; // flag of valid dec

////// A check of declination (correct input DEC)
if (substr_count($obj_DEC, ':')!=2){ // Here we check a symbol ":" in input form. It must contain only 2
    $bool_dec = false;
}
else{
    $first_position_dec = mb_strpos($obj_DEC, ':'); // To find a place of first position ":" in $obj_DEC
    $first_digits_dec = mb_substr($obj_DEC, 0, $first_position_dec); // Trim $obj_DEC to find first digits of dec (degrees)
    $negative_sign_check = mb_substr($obj_DEC, 0, 1); // A check of negative sign "-" in DEC
    $second_position_dec = strpos($obj_DEC, ':', $first_position_dec + 1); // to find second input ":" in text dec
    $second_digits_dec = mb_substr($obj_DEC, $first_position_dec +1, $second_position_dec - $first_position_dec -1); // To find minutes in dec
    $third_digits_dec = mb_substr($obj_DEC, $second_position_dec +1, strlen($obj_DEC) - $second_position_dec - 1);// to find seconds in DEC
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

///// A check of visibility in Assy (by analyzing DEC)

$latitude_assy = 43.2; /// latitude of Assy-Turgen observatory
$culmination_alt_obj=''; // declaration of variable for maximum altitude for the object
$dec_visibility_obj = false; // flag showing a correct visibility
$good_dec_culmination = false; // flag showing that culmination more than 15 degree over horizon

if($total_dec_decimal > $latitude_assy ){ // calculation of max altitude
    $culmination_alt_obj = 90 - $total_dec_decimal + $latitude_assy;
}
else{
    $culmination_alt_obj = 90 + $total_dec_decimal - $latitude_assy;
}

if($culmination_alt_obj > 0){ // if $culmination_alt_obj > 0 the object is visible in Assy
    $dec_visibility_obj = true;
}

if($culmination_alt_obj > 17){
    $good_dec_culmination = true;
}



echo '<p class="title"><b>Fesenkov Astrophysical Institute</b></p>';
echo '<p class="title"><b>Submited form for observations</b></p>';

echo '<p class="user">Kim Vitaliy Yurevich</p>';
echo '<p class="user">ursa-majoris@yandex.ru</p>';

echo "Submitted date (dd-mm-yy) and time (UTC): " . date('d-m-Y H:i:s') . "<br>";
echo "Your institute: ".$chose_inst."<br />";
echo "Co-researchers: ".$co_researhers."<br />";
echo "Some conditions: ".$conditions ."<br />";
echo "Chosen telescope: ".$telescope."<br />";
echo "Type of observations: ".$choice_obs."<br />";
echo "Start date for observations: ".$date1."<br />";
echo "End date of observations: ".$date2."<br />";
echo "First position of : ".$first_position_dec."<br />";
echo "Second position of : ".$second_position_dec."<br />";
echo "First digits of DEC: ".$first_digits_dec."<br />";
echo "Second digits of DEC: ".$second_digits_dec."<br />";
echo "Third digits of DEC: ".$third_digits_dec."<br />";
if ($bool_dec == false){
    echo "You input incorrect value of DEC"."<br />";
}
else {
    echo "Total DEC in decimal: ".$total_dec_decimal ."<br />";
}

if($dec_visibility_obj == false){
    echo "Incorrect data"."<br />";
    echo "you input coordinates (DEC) of object which invisible"."<br />";
}
else if($good_dec_culmination == false){
    echo "Incorrect data"."<br />";
    echo "you input coordinates (DEC) of object which very low over horizon"."<br />";
}

if($bool_ra == true){
    echo "Input RA in 360 degrees system: ".$total_ra_decimal."<br />";
}

if(isset($_POST['submit'])){
    if ($bool_ra == true and $bool_dec == true) {
        mail("ursa-majoris@yandex.ru", "Заголовок", "Текст письма \n 1-ая строчка \n 2-ая строчка \n 3-ая строчка");
    }
}
if(isset($_SERVER['HTTP_REFERER'])) {
    $urlback = htmlspecialchars($_SERVER['HTTP_REFERER']);
    echo "<a href='$urlback' class='history-back'>Back</a>";
}

