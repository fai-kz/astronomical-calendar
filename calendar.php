<link rel='stylesheet' href='css/styles.css'>
<p align="right"> <a href=calendar_kaz.php>  Қаз </a> &nbsp <b>Рус &nbsp </b> <a href=calendar_eng.php>Eng </a></p>
<p class="title"><b>Астрофизический институт им. Фесенкова</b></p>
<p class="title"><b>Астрономический календарь </b></p>
<form action="calend_output.php" method="post">

<!--    <p class="tag"><b> Координаты места </b></p>-->
<!--    <p class="tag"><b> (По умолчанию координаты  центра Алматы) </b></p>-->
<!--    <div id="menu_inst">-->
<!--    Широта &nbsp <input type="text" name="lat" size="5" value="43.25" >&nbsp &nbsp Долгота &nbsp <input type="text" name="long" size="5" value="76.95"><br><br>-->
<!--        Высота над ур. м. &nbsp <input type="text" name="altitude" size="5" value="0"> (м)&nbsp &nbsp Час. пояс &nbsp <input type="text" name="zone" size="5" value="6"> от UTC-->
<!--    </div>-->
    <p class="tag"><b> Координаты места </b></p> <!--ряд с ячейками заголовков-->
    <div class="mytable">
    <table>
        <tr><th colspan="11"><b> (По умолчанию координаты  центра Алматы) </b></th></tr>
        <tr><td> &nbsp </td> <td> Градусы </td> <td> Минуты </td> <td> Секунды </td> <td> &nbsp </td> <td> &nbsp  </td><td> &nbsp  </td><td> Градусы </td> <td> Минуты </td> <td> Секунды </td><td> &nbsp  </td></tr>
        <tr><td><b> Широта: </b></td> <td> <input type="text" name="lat" size="5" value="43" > </td> <td> <input type="text" name="lat1" size="5" value="15" > </td>  <td> <input type="text" name="lat2" size="5" value="0" > <td> <select name="lat_type">
                    <option value="0">Северная</option> <option value="1">Южная</option> </select></td><td> &nbsp &nbsp &nbsp </td>  <td> <b>Долгота:</b></td> <td> <input type="text" name="long" size="5" value="76"> </td><td> <input type="text" name="long1" size="5" value="57"> </td><td> <input type="text" name="long2" size="5" value="0"> </td><td> <select name="long_type">
                    <option value="0">Восточная</option> <option value="1">Западня</option> </select>  </td></tr>
    </table>

        <table>
            <tr> <td> Часовой пояс от UTC: </td> <td> <select name="utc_sign">
                        <option value="0">+</option> <option value="1">-</option> </select></td>
                <td><input type="text" name="zone" size="5" value="6"></td></tr>
                <tr><td> Высота над ур. моря: </td><td colspan="2"> <input type="text" name="altitude" size="5" value="0"> </td></tr>
        </table>
    </div>
        <p class="tag"><b> Введите год и выберите месяц </b></p>
    <div id="menu_inst">
        Год &nbsp <input type="text" name="year" size="10">&nbsp &nbsp
        <select name="month">
            <option value="0">-----Месяц--------</option>
            <option value="1">Январь</option>
            <option value="2">Февраль</option>
            <option value="3">Март</option>
            <option value="4">Апрель</option>
            <option value="5">Май</option>
            <option value="6">Июнь</option>
            <option value="7">Июль</option>
            <option value="8">Август</option>
            <option value="9">Сентябрь</option>
            <option value="10">Октябрь</option>
            <option value="11">Ноябрь</option>
            <option value="12">Декабрь</option>
        </select>
    </div>
    <p class="tag" ><b> Интересующие данные </b></p>
    <div class="additional">
<!--        <p > &nbsp &nbsp </p>-->
        <input type="radio" name="type_data" value=1> Звёздное время (локальное)  &nbsp
        <input type="radio" name="type_data" value=2> Восход, заход Солнца, сумерки &nbsp
        <input type="radio" name="type_data" value=3> Восход, заход Луны, фазы &nbsp <br><br>
        <input type="radio" name="type_data" value=5> Равноденствия и солнцестояния &nbsp
<!--        <input type="radio" name="type_data" value=4> Все данные &nbsp-->
    </div>
    <div class="tag">
    <p class="additional2" ><b> Введите цифры на картинке </b></p>
        <p><img src="captcha.php"></p>
    <input class="additional2" type="text" name="norobot">

    </div>
    <p class="tag"><button type="submit" name="submission">Рассчитать</button></p>
</form>

<div class="additional1">
<p > Программу для расчетов календаря подготовил старший научный сотрудник АФИФ, к.ф.-м.н. Виталий Ким </b></p>
<p> kim@aphi.kz </b></p>
</div>
