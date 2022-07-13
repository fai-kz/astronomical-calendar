<link rel='stylesheet' href='css/styles.css'>
<p align="right">   <b> </b>Қаз </b> &nbsp <a href=calendar.php> Рус </a> &nbsp  <a href=calendar_eng.php> Eng </a> </p>
<p class="title"><b>Фесенков атындағы астрофизика институты</b></p>
<p class="title"><b>Астрономиялық күнтізбе </b></p>
<form action="calendar_output_kaz.php" method="post">

    <!--    <p class="tag"><b> Координаты места </b></p>-->
    <!--    <p class="tag"><b> (По умолчанию координаты  центра Алматы) </b></p>-->
    <!--    <div id="menu_inst">-->
    <!--    Широта &nbsp <input type="text" name="lat" size="5" value="43.25" >&nbsp &nbsp Долгота &nbsp <input type="text" name="long" size="5" value="76.95"><br><br>-->
    <!--        Высота над ур. м. &nbsp <input type="text" name="altitude" size="5" value="0"> (м)&nbsp &nbsp Час. пояс &nbsp <input type="text" name="zone" size="5" value="6"> от UTC-->
    <!--    </div>-->
    <p class="tag"><b> Жергілікті орынның координаттары </b></p> <!--ряд с ячейками заголовков-->
    <div class="mytable">
        <table>
            <tr><th colspan="11"><b> (Алматы орталығының стандартты координаттары) </b></th></tr>
            <tr><td> &nbsp </td> <td> Градус </td> <td> Минут </td> <td> Секунд </td> <td> &nbsp </td> <td> &nbsp  </td><td> &nbsp  </td><td> Градус </td> <td> Минут </td> <td> Секунд </td><td> &nbsp  </td></tr>
            <tr><td><b>  Ендік: </b></td> <td> <input type="text" name="lat" size="5" value="43" > </td> <td> <input type="text" name="lat1" size="5" value="15" > </td>  <td> <input type="text" name="lat2" size="5" value="0" > <td> <select name="lat_type">
                        <option value="0">Солтүстік</option> <option value="1">Оңтүстік</option> </select></td><td> &nbsp &nbsp &nbsp </td>  <td> <b> Бойлық:</b></td> <td> <input type="text" name="long" size="5" value="76"> </td><td> <input type="text" name="long1" size="5" value="57"> </td><td> <input type="text" name="long2" size="5" value="0"> </td><td> <select name="long_type">
                        <option value="0">Шығыс</option> <option value="1">Батыс</option> </select>  </td></tr>
        </table>

        <table>
            <tr> <td> UTC уақыт белдеуі: </td> <td> <select name="utc_sign">
                        <option value="0">+</option> <option value="1">-</option> </select></td>
                <td><input type="text" name="zone" size="5" value="6"></td></tr>
            <tr><td>  Теңіз деңгейіндегі биіктік: </td><td colspan="2"> <input type="text" name="altitude" size="5" value="0"> </td></tr>
        </table>
    </div>
    <p class="tag"><b> Жылды, айды еңгізіңіз </b></p>
    <div id="menu_inst">
        Жыл &nbsp <input type="text" name="year" size="10">&nbsp &nbsp
        <select name="month">
            <option value="0">-----Ай--------</option>
            <option value="1">қаңтар</option>
            <option value="2">ақпан</option>
            <option value="3">наурыз</option>
            <option value="4">сәуір</option>
            <option value="5">мамыр</option>
            <option value="6">маусым</option>
            <option value="7">шілде</option>
            <option value="8">тамыз</option>
            <option value="9">қыркүйек</option>
            <option value="10">қазан</option>
            <option value="11">қараша</option>
            <option value="12">желтоқсан</option>
        </select>
    </div>
    <p class="tag" ><b> Қызықты мәліметтер </b></p>
    <div class="additional">
        <!--        <p > &nbsp &nbsp </p>-->
        <input type="radio" name="type_data" value=1> Жергілікті жұлдыздық уақыт  &nbsp
        <input type="radio" name="type_data" value=2> Күннің шығуы, батуы, ымырт &nbsp
        <input type="radio" name="type_data" value=3> Айдың шығуы, батуы, фазалары &nbsp <br><br>
        <input type="radio" name="type_data" value=5> Күн мен түннің теңелуі және күн тоқырауы &nbsp
        <!--        <input type="radio" name="type_data" value=4> Все данные &nbsp-->
    </div>
    <div class="tag">
        <p class="additional2" ><b> Суреттегі сандарды енгізіңізе </b></p>
        <p><img src="captcha.php"></p>
        <input class="additional2" type="text" name="norobot">

    </div>
    <p class="tag"><button type="submit" name="submission">Есептеу</button></p>
</form>

<div class="additional1">
    <p > Күнтізбелік есептеулер бағдарламасын ФАФИ аға ғылыми қызметкері, ф.-м.ғ.к. Виталий Ким дайындады </b></p>
    <p> kim@aphi.kz </b></p>
</div>
