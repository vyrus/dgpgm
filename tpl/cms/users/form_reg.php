<?
$_TPL['TITLE'] [] = 'Регистрация нового пользователя';
include TPL_CMS."_header.php";

?>

<script>
$(document).ready(function () {
	
	function showMr(pp_id, mr_id)
    {
		// ничего не выбранно
        if (pp_id == '0') {
            $('#mr').html('<option value="0">Не выбрано</option>');
            //$('#mr').attr('disabled', true);
            return(false);
        }
        //$('#mr').attr('disabled', true);
        //$('#mr').html('<option>загрузка...</option>');
        var url = '/?mod=forms&action=mr_get';
        $.get(
            url,
            "pp_id=" + pp_id,
            function (result) {
                if (result.type == 'error') {
                    var options = '';
                    $('#mr').html(options);
                }
                else {
                    var options = '';
					//массив отдаст id и title
                    $(result.measure).each(function() {
						var num_m = $(this).attr('id');
						var s_d = '';
						if (num_m == 0) {
							num_m = '';
						}
						if (mr_id == num_m) {
							s_d = ' selected="selected"';
						}
                        options += '<option value="' + $(this).attr('id') + '"'+ s_d + '>' + num_m + ' ' + $(this).attr('title') + '</option>';
                    });
                    $('#mr').html(options);
                    //$('#mr').attr('disabled', false);
                }
            },
            "json"
        );
	}

	$('#pp').change(function () {
        var pp_id = $(this).val();
		showMr(pp_id);
	});
	
	<? if (isset($_TPL['ROW']['pp']) && isset($_TPL['ROW']['mr'])) {?>
		showMr(<?=$_TPL['ROW']['pp']?>, <?=$_TPL['ROW']['mr']?>);
	<?} elseif (isset($_TPL['ROW']['pp'])) {?>
		showMr(<?=$_TPL['ROW']['pp']?>, false);
	<?} ?>
		
});
</script>

<h1>Регистрация</h1>
<p>Уважаемый Гость!</p>
<p>Вы зашли на страницу регистрации Участников конкурса на формирование тематики на выполнение НИР (НИОКР) объявленного в рамках реализации мероприятий Государственной программы города Москвы "Градостроительная политика" на 2012-2016 г.</p>
<h3>Для регистрации заявки:</h3>
<form method="POST">
<table style="width: 100%;">
<tr>
	<td><strong>Выберите подпрограмму</strong> <span style="color: red;">*</span></td>
	<td><select name="pp" id="pp">
			<option value="0">Не выбрано</option>
			<?	foreach ($TPL['SUBPROGRAM'] as $row) { ?>
			<option value="<?=$row['id']?>"<?=($_TPL['ROW']['pp'] == $row['id'])?" selected='selected'":""?>><?=utf8_str_word($row['title'], 6, ' ')?></option>
			<? } ?>
		</select>
	</td>
</tr>
<tr>
	<td><strong>Выберите мероприятие</strong> <span style="color: red;">*</span></td>
	<td><select name="mr" id="mr">
			<option value="0">Не выбрано</option>
		</select>
	</td>
</tr>
<tr>
	<td><strong>Укажите статус заявителя</strong> <span style="color: red;">*</span></td>
	<td>
		<input type="radio" <?=($_TPL['ROW']['type-face'] == 'fiz')?'checked="checked" ':''?>value="fiz" name="type-face"> Физическое лицо
		<input type="radio" <?=(!isset($_TPL['ROW']['type-face']) || $_TPL['ROW']['type-face'] == 'yur')?'checked="checked" ':''?>value="yur" name="type-face"> Юридическое лицо
	</td>
</tr>
<tr>
	<td colspan="2" style="text-align: center;"><strong>Введите данные о контактном лице, ответственном за заполнение данной заявки:</strong></td>
</tr>
<tr>
	<td>ФИО контактного лица полностью  <span style="color: red;">*</span></td>
	<td><input type="text" name="name" value="<?=(!empty($_TPL['ROW']['name']))?$_TPL['ROW']['name']:''?>" /></td>
</tr>
<tr>
	<td>Телефон для связи <span style="color: red;">*</span></td>
	<td><input type="text" name="phone" value="<?=(!empty($_TPL['ROW']['phone']))?$_TPL['ROW']['phone']:''?>" /><br />
	<span class="smalltext">например: (495) 236-23-43; (499) 236-13-43</span></td>
</tr>
<tr>
	<td>Электронная почта для связи <span style="color: red;">*</span></td>
	<td><input type="text" name="email" value="<?=(!empty($_TPL['ROW']['email']))?$_TPL['ROW']['email']:''?>" /></td>
</tr>
<tr>
	<td>Проверочный код <span style="color: red;">*</span><br /><img src="/inc/captcha/?<?php echo session_name()?>=<?php echo session_id()?>" id="captcha"><br /><a href="javascript:;" onclick="var img = document.getElementById('captcha'); img.src = img.src+'&amp;'+(new Date().getTime());document.getElementById('field_ccode').focus();return false;">Показать другой проверочный код</a></td>
	<td><input type="text" name="keystring" id="field_ccode" /></td>
</tr>
<tr>
	<td colspan="2" style="text-align: center;"><input type="submit" value="Зарегистрироваться" /><br /><p style="color: red;">Все поля обязательны для заполнения</p></td>
</tr>
</table>

<?
    include TPL_CMS."_footer.php";
?>