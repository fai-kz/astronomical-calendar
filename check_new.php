<?php
session_start();
if (md5($_POST['norobot']) == $_SESSION['randomnr2']){

}
else {

    echo "вы весьма надоедливый бот!";
}
?>