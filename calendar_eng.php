<link rel='stylesheet' href='css/styles.css'>
<p align="right"> <a href=calendar_kaz.php>  Қаз </a> &nbsp <a href=calendar.php> Рус </a> &nbsp <b> Eng </b> </p>
<p class="title"><b>Fesenkov Astrophysical Institute</b></p>
<p class="title"><b>Astronomical calendar </b></p>
<form action="calend_output_eng.php" method="post">

    <!--    <p class="tag"><b> Координаты места </b></p>-->
    <!--    <p class="tag"><b> (По умолчанию координаты  центра Алматы) </b></p>-->
    <!--    <div id="menu_inst">-->
    <!--    Широта &nbsp <input type="text" name="lat" size="5" value="43.25" >&nbsp &nbsp Долгота &nbsp <input type="text" name="long" size="5" value="76.95"><br><br>-->
    <!--        Высота над ур. м. &nbsp <input type="text" name="altitude" size="5" value="0"> (м)&nbsp &nbsp Час. пояс &nbsp <input type="text" name="zone" size="5" value="6"> от UTC-->
    <!--    </div>-->
    <p class="tag"><b> Coordinates of a place </b></p> <!--ряд с ячейками заголовков-->
    <div class="mytable">
        <table>
            <tr><th colspan="11"><b> (Default the center of Almaty) </b></th></tr>
            <tr><td> &nbsp </td> <td align="center"> Deg </td> <td align="center"> Min </td> <td align="center"> Sec </td> <td> &nbsp </td> <td> &nbsp  </td><td> &nbsp  </td><td align="center"> Deg </td> <td align="center"> Min </td> <td align="center"> Sec </td><td> &nbsp  </td></tr>
            <tr><td><b> Latitude: </b></td> <td> <input type="text" name="lat" size="5" value="43" > </td> <td> <input type="text" name="lat1" size="5" value="15" > </td>  <td> <input type="text" name="lat2" size="5" value="0" > <td> <select name="lat_type">
                        <option value="0">North</option> <option value="1">South</option> </select></td><td> &nbsp &nbsp &nbsp </td>  <td> <b>Longitude:</b></td> <td> <input type="text" name="long" size="5" value="76"> </td><td> <input type="text" name="long1" size="5" value="57"> </td><td> <input type="text" name="long2" size="5" value="0"> </td><td> <select name="long_type">
                        <option value="0">East</option> <option value="1">West</option> </select>  </td></tr>
        </table>

        <table>
            <tr> <td> Time zone from UTC: </td> <td> <select name="utc_sign">
                        <option value="0">+</option> <option value="1">-</option> </select></td>
                <td><input type="text" name="zone" size="5" value="6"></td></tr>
            <tr><td> Height above sea leve: </td><td colspan="2"> <input type="text" name="altitude" size="5" value="0"> </td></tr>
        </table>
    </div>
    <p class="tag"><b> Input a year and choose a month </b></p>
    <div id="menu_inst">
        Year &nbsp <input type="text" name="year" size="10">&nbsp &nbsp
        <select name="month">
            <option value="0">-----Month--------</option>
            <option value="1"> January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
    </div>
    <p class="tag" ><b> Interested data </b></p>
    <div class="additional">
        <!--        <p > &nbsp &nbsp </p>-->
        <input type="radio" name="type_data" value=1> Siderial time (local)  &nbsp
        <input type="radio" name="type_data" value=2> Rise, set of the Sun, twilights &nbsp
        <input type="radio" name="type_data" value=3> Rise, set of the Moon, phases &nbsp <br><br>
        <input type="radio" name="type_data" value=5> Equinoxes and solstices &nbsp
        <!--        <input type="radio" name="type_data" value=4> Все данные &nbsp-->
    </div>
    <div class="tag">
        <p class="additional2" ><b> Input a number as in the picture </b></p>
        <p><img src="captcha.php"></p>
        <input class="additional2" type="text" name="norobot">

    </div>
    <p class="tag"><button type="submit" name="submission">Calculate</button></p>
</form>

<div class="additional1">
    <p > The program for calculation of the calendar was prepared by Vitaliy Kim (FAI senior researcher, PhD)</b></p>
    <p> kim@aphi.kz </b></p>
</div>
