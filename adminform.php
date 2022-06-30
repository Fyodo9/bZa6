<form action="admin.php"
        method="POST">
<input type="hidden" name="uid" value="<?php print $row['form_id']; ?>" />
<label>
    Имя<br />
    <input name="fio" value="<?php print $row['fio']; ?>"/>
</label><br />
<br />

<label>
    E-mail:<br />
    <input name="email" value="<?php print $row['email']; ?>" type="email" />
</label><br />
<br />

 <p>год рождения</p>
    <select name="bd" >
        <?php for ($i = 1900; $i < 2021;$i++) {
            print('<option value="');
            print($i);
            if ($row['bd'] == $i) print('" selected="selected">');
            else print('">');
            print($i);
            print('</option>');}?>
    </select>
<br />



Пол:<br />
<label><input type="radio"
    name="sex" value="M" <?php if ($row['sex'] == 'M') {print 'checked';} ?>/>
    мужской
</label>
<label><input type="radio"
    name="sex" value="F" <?php if ($row['sex'] == 'F') {print 'checked';} ?> />
    женский
</label> 
<br />


 <p>Количество конечностей</p>
    <label class="radio">
        <input type="radio" name="limbs" value="2" <?php if ($row['limbs'] == '2') {print 'checked';} ?>>
        2
    </label>
    <label class="radio">
        <input type="radio" name="limbs" value="4" <?php if ($row['limbs'] == '4') {print 'checked';} ?>>
        4
    </label>
    <label class="radio">
        <input type="radio" name="limbs" value="6" <?php if ($row['limbs'] == '6') {print 'checked';} ?>>
        6
    </label>
    <label class="radio">
        <input type="radio" name="limbs" value="8" <?php if ($row['limbs'] == '8') {print 'checked';} ?>>
        8
    </label>
    <label class="radio">
        <input type="radio" name="limbs" value="10" <?php if ($row['limbs'] == '10') {print 'checked';} ?>>
        10
    </label>
    <br>

<br />

<label>
    Сверхспособности:
    <br />
    <select name="abilities[]"
    multiple="multiple">

        <option value="0" <?php if ($abilities[0]) {print 'selected';} ?>>Бессмертие</option>
        <option value="1" <?php if ($abilities[1]) {print 'selected';} ?>>Прохождение сквозь стен</option>
        <option value="2" <?php if ($abilities[2]) {print 'selected';} ?>>Левитация</option>
        <option value="3" <?php if ($abilities[3]) {print 'selected';} ?>>Невидимость</option>

    </select>
</label><br />
<br />

<label>
    Биография:<br />
    <textarea name="bio"><?php print $row['bio']; ?></textarea>
</label><br />

<input id="submit" type="submit" value="Изменить" name="update" />
<input id="submit" type="submit" value="Удалить" name="delete"/>
</form>

