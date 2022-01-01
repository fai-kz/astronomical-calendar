
<link rel='stylesheet' href='css/styles.css'>
<p class="title"><b>Fesenkov Astrophysical Institute</b></p>
<p class="title"><b>Submission form for observations</b></p>

<p class="tag"><b>1. Your registered full name and e-mail</b></p>

<p class="user">Kim Vitaliy Yur'evich</p>
<p class="user">ursa-majoris@yandex.ru</p>

<form action="check.php" method="post">
    <fieldset style="border:0 none">
        <p class="tag"><b>2. Choose your institute</b></p>
        <div id="menu_inst">
        <select name="institute">
            <option value="0">-----Choose from list--------</option>
            <option value="fai">Fesenkov Astrophysical Institute (Kazakhstan)</option>
            <option value="kaznu">Al-Farabi Kazakh national university (Kazakhstan)</option>
            <option value="nu">Nazarbaev University (Kazakhstan)</option>
            <option value="iki">Space Research Institute (Russia)</option>
            <option value="po">Pulkovo observatory (Russia)</option>
        </select>
        </div>
        <div class="additional">
                <p class="addition">*If there is no you institute into the list, add it bellow</p>
                <input type="text" name="num" size="37">
                <button type="reset" name="Reset">Clear</button>
        <!--        --><?php
        //        $n1 = $_GET['num'];
        //        echo "<p>".$n1."</p>";
        //        ?>



        </div>

        <p class="tag"><b>3. Corresponding researches </b></p>
        <div class="additional">
                <p class="additional"><b>(for example: 1. Petrov P.A., 2. Sidorov A.A. ...) </b></p>
                <p><textarea name="corr" cols="50" rows="3"></textarea></p>
<!--            <input type="text" name="corr" size="50" style="height: 100px">-->
        </div>

        <p class="tag"><b>4. Task name </b></p>
        <div class="additional">
                <p class="additional"><b>(for example: A study of Wolf-Rayet star phenomenon) </b></p>
                <input type="text" name="num1" size="50">
        </div>


        <p class="tag"><b>5. Choose a telescope</b></p>

        <div id="menu_inst">
            <select multiple name="telescope">
                <option disabled>-----Choose from list------</option>
                <option value="AZT-20">AZT-20 (D=1.5m, F=5.7m)</option>
                <option value="RC-500">RC-500 (D=0.5m, F=1.4m)</option>
                <option value="Zeiss-1000">Zeiss-1000 (D=1m, F=13.3)</option>
            </select>
            <p class="user"><button type="reset" name="telescope">Clear</button></p>
        </div>

        <p class="tag"><b>6. Select a type of observations </b></p>
        <div class="additional">
                <input type="radio" name="type_obs1" value="phot_obs">Photometric &nbsp
                <input type="radio" name="type_obs1" value="spec_obs">Spectral &nbsp
                <input type="radio" name="type_obs1" value="both">Both &nbsp
        </div>

        <p class="tag"><b>7. Observational period </b></p>
        <div class="additional">
                <p>Start date &nbsp <input type="date" name="calend_start" value="start">&nbsp &nbsp End date &nbsp <input type="date" name="calend_end" value="end">
                &nbsp <button type="reset" name="Reset">Clear</button></p>
        </div>

        <p class="tag"><b>8. Object(s) for observation (name [or id], RA, DEC)</b></p>
        <div class="additional">
                <p class="additional"><b>(attention: input RA and DEC in form AA:BB:CC [using ":"-separator]) </b></p>
                Name (or id) &nbsp <input type="text" name="obj_name" size="20">&nbsp &nbsp
                RA &nbsp <input type="text" name="obj_RA" size="10">&nbsp &nbsp
                DEC &nbsp <input type="text" name="obj_DEC" size="10">&nbsp
        </div>

        <p class="tag"><b>9. Especial  conditions (technical comments) for observations</b></p>
        <div class="additional">
            <p><textarea name="conditions" cols="50" rows="5"></textarea></p>
<!--            <input type="text" name="conditons" size="50" style="height: 100px">-->
        </div>

        <p class="tag"><b>10. Other comments</b></p>
        <div class="additional">
                <p><textarea name="other_comp" cols="50" rows="5"></textarea></p>
                <!--<input type="text" name="other_comp" size="50" style="height: 100px">-->
        </div>
            <p class="tag"><button type="submit" name="submission">Confirm</button></p>

    </fieldset>
</form>
<?php
//    require('/home/vitaly/Documents/project/fpdf/fpdf.php');
//    $pdf = new FPDF('P', 'pt', 'Letter');
//    $pdf -> AddPage();
//    $pdf -> SetFont('Arial', '', 12);
//    $pdf -> Cell(100, 16, "Hello world");
//    $pdf -> Output();
//?>

<!--<form action="#" method="GET" >-->
<!---->
<!--    <input type="text" name="num1">-->
<!--    <input type="text" name="num2">-->
<!--    <button type="submit" name="submit">Send</button>-->
<!--</form>-->
