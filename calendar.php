<link rel='stylesheet' href='css/styles.css'>
<p class="title"><b>Астрофизический институт им. Фесенкова</b></p>
<p class="title"><b>Астрономический календарь г. Алматы</b></p>
<form action="calend_output.php" method="post">

    <p class="tag"><b> Координаты места </b></p>
    <div id="menu_inst">
    Широта &nbsp <input type="text" name="alt" size="10" value="43.25">&nbsp Долгота &nbsp <input type="text" name="long" size="10" value="76.87"> Час. пояс &nbsp <input type="text" name="zone" size="10" value="6">
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
        <p > &nbsp &nbsp </p>
        <input type="radio" name="type_data" value=1> Звёздное время (локальное)  &nbsp
        <input type="radio" name="type_data" value=2> Восход, заход Солнца, сумерки &nbsp
        <input type="radio" name="type_data" value=3> Восход, заход Луны, фазы &nbsp <br><br>
        <input type="radio" name="type_data" value=5> Равноденствия и солнцестояния &nbsp
<!--        <input type="radio" name="type_data" value=4> Все данные &nbsp-->
    </div>
    <p class="tag"><button type="submit" name="submission">Рассчитать</button></p>
</form>

<!--<p> Программу для расчетов календаря подготовил старший научный сотрудник АФИФ, к.ф.-м.н. Виталий Ким </b></p>-->
<!--<p> kim@fai.kz </b></p>-->
