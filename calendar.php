<link rel='stylesheet' href='css/styles.css'>
<p class="title"><b>Астрофизический институт им. Фесенкова</b></p>
<p class="title"><b>Астрономический календарь г. Алматы</b></p>
<form action="calend_output.php" method="post">
    <p class="tag"><b> Введите год и выбирете месяц </b></p>
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
        <input type="radio" name="type_data" value=1> Звездное время (локальное)  &nbsp
        <input type="radio" name="type_data" value=2> Восход, заход Солнца, суммерки &nbsp
        <input type="radio" name="type_data" value=3> Все данные &nbsp
    </div>
    <p class="tag"><button type="submit" name="submission">Рассчитать</button></p>
</form>

<!--<p> Программу для расчетов календаря подготовил старший научный сотрудник АФИФ, к.ф.-м.н. Виталий Ким </b></p>-->
<!--<p> kim@fai.kz </b></p>-->