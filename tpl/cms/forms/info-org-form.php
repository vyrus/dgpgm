<script>
$(document).ready(function () {
    
	$('select[name=checkfactadress]').change(function () {
		var a = $('select[name=checkfactadress] option[value]:selected').val();
		if (a == 'no') {
			$('tr.factaddress').removeClass('display-none');
		} else if (a == 'yes') {
			$('tr.factaddress').addClass('display-none');
		}
	});
	
	$('input[name="OGRN_attribution"]').attachDatepicker({
		rangeSelect: false,
		yearRange: '1990:<?=date('Y')?>',
		firstDay: 1
	});
	
	$('input[name="general_permission_date"]').attachDatepicker({
		rangeSelect: false,
		yearRange: '1990:<?=date('Y')?>',
		firstDay: 1
	});
	
	
});
</script>

<?=$TPL['BIDMENU']?>

<h1>Сведения об организации</h1>

<div id="org-info-descr"><p>Заполните поля формы. В любой момент Вы можете сохранить введенные данные нажатием на кнопку внизу формы. Также Вы можете перемещаться между формами используя ссылки на соответствующие шаги в меню слева или ссылки над заголовком формы.</p>
<p>При вводе данных пользуйтесь примерами, указанными рядом с полем ввода</p></div>

<form method="post">

<table width="100%">
  <tr>
    <td colspan="2"><h3>Сведения об участнике</h3></td>
    </tr>
  <tr>
    <td>Полное наименование организации:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['full_title']))?htmlspecialchars($_TPL['ROW']['full_title']):''?>" name="full_title"></td>
  </tr>
  <tr>
    <td>Полное наименование организации в родительном падеже:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['full_title_genitive']))?htmlspecialchars($_TPL['ROW']['full_title_genitive']):''?>" name="full_title_genitive"><br />
    <span class="smalltext">например: Государственного образовательного учреждения высшего профессионального уровня "Белгородский государственный университет"</span></td>
  </tr>
  <tr>
    <td>Полное наименование организации в творительном падеже:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['full_title_instrumental']))?htmlspecialchars($_TPL['ROW']['full_title_instrumental']):''?>" name="full_title_instrumental"><br />
<span class="smalltext">например: Государственным образовательным учреждением высшего профессионального уровня "Белгородский государственный университет"</span></td>
  </tr>
  <tr>
    <td>Краткое наименование организации:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['short_title']))?htmlspecialchars($_TPL['ROW']['short_title']):''?>" name="short_title"><br>
<span class="smalltext">например: ГОУ ВПО БелГУ</span></td>
  </tr>
  <tr>
    <td>ИНН</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['INN']))?$_TPL['ROW']['INN']:''?>" name="INN"></td>
  </tr>
  <tr>
    <td>КПП</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['KPP']))?$_TPL['ROW']['KPP']:''?>" name="KPP"></td>
  </tr>
  <tr>
    <td>ОГРН</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['OGRN']))?$_TPL['ROW']['OGRN']:''?>" name="OGRN"></td>
  </tr>
  <tr>
    <td>Дата присвоения ОГРН:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['OGRN_attribution']))?$_TPL['ROW']['OGRN_attribution']:''?>" name="OGRN_attribution"></td>
  </tr>
  <tr>
    <td colspan="2"><h3>Банковские реквизиты участника</h3></td>
    </tr>
  <tr>
    <td>Наименование принятое в ОФК:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['title_for_bank']) && $_TPL['ROW']['title_for_bank'] != 'no')?$_TPL['ROW']['title_for_bank']:''?>" name="title_for_bank"> <input type="checkbox" name="title_for_bank_check" value="no" <?=(isset($_TPL['ROW']['title_for_bank']) && $_TPL['ROW']['title_for_bank'] == 'no')?'checked':''?>> не предусмотрено<br />
<span class="smalltext">например: УФК по Иркутской области (ФГБОУ ВПО "ИГУ")</span></td>
  </tr>
  <tr>
    <td>Л/С:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_ls']) && $_TPL['ROW']['bank_ls'] != 'no')?$_TPL['ROW']['bank_ls']:''?>" name="bank_ls"> <input type="checkbox" name="bank_ls_check" value="no" <?=(isset($_TPL['ROW']['bank_ls']) && $_TPL['ROW']['bank_ls'] == 'no')?'checked':''?>> не предусмотрено<br />
<span class="smalltext">например:9823А230939</span></td>
  </tr>
  <tr>
    <td>БИК:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_bik']))?$_TPL['ROW']['bank_bik']:''?>" name="bank_bik"><br>
<span class="smalltext">например: 044535226</span></td>
  </tr>
  <tr>
    <td>Расчетный счет:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_ras']))?$_TPL['ROW']['bank_ras']:''?>" name="bank_ras"><br />
<span class="smalltext">например: 40105810400000010001</span></td>
  </tr>
  <tr>
    <td>Корреспондентский счет:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_cor']) && $_TPL['ROW']['bank_cor'] != 'no')?$_TPL['ROW']['bank_cor']:''?>" name="bank_cor"> <input type="checkbox" name="bank_cor_check" value="no" <?=(isset($_TPL['ROW']['bank_cor']) && $_TPL['ROW']['bank_cor'] == 'no')?'checked':''?>> не предусмотрено<br />
<span class="smalltext">например: 30101814500000000225</span></td>
  </tr>
  <tr>
    <td>Расчетный счет (внебюджетный):</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_rasv']) && $_TPL['ROW']['bank_rasv'] != 'no')?$_TPL['ROW']['bank_rasv']:''?>" name="bank_rasv"> <input type="checkbox" name="bank_rasv_check" value="no" <?=(isset($_TPL['ROW']['bank_rasv']) && $_TPL['ROW']['bank_rasv'] == 'no')?'checked':''?>> не предусмотрено<br />
<span class="smalltext">например: 40503810600001000001</span></td>
  </tr>
  <tr>
    <td>Наименование банка получателя:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_receiver']))?$_TPL['ROW']['bank_receiver']:''?>" name="bank_receiver"><br>
<span class="smalltext">например: Отделение 1 Московского ГТУ Банка России г. Москва</span></td>
  </tr>
  <tr>
    <td colspan="2"><h3>Сведения о бюджетополучателе</h3></td>
    </tr>
  <tr>
    <td>КБК:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['KBK']) && $_TPL['ROW']['KBK'] != 'no')?$_TPL['ROW']['KBK']:''?>" name="KBK"> <input type="checkbox" name="KBK_check" value="no" <?=(isset($_TPL['ROW']['KBK']) && $_TPL['ROW']['KBK'] == 'no')?'checked':''?>> не предусмотрено<br />
<span class="smalltext">например: 07340201111010000130</span></td>
  </tr>
  <tr>
    <td>Номер генерального разрешения:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['general_permission_num']) && $_TPL['ROW']['general_permission_num'] != 'no')?$_TPL['ROW']['general_permission_num']:''?>" name="general_permission_num"> <input type="checkbox" name="general_permission_num_check" value="no" <?=(isset($_TPL['ROW']['general_permission_num']) && $_TPL['ROW']['general_permission_num'] == 'no')?'checked':''?>> не предусмотрено<br />
<span class="smalltext">например: 0739178439</span></td>
  </tr>
  <tr>
    <td>Дата генерального разрешения:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['general_permission_date']) && $_TPL['ROW']['general_permission_date'] != '0000-00-00')?$_TPL['ROW']['general_permission_date']:''?>" name="general_permission_date"> <input type="checkbox" name="general_permission_date_check" value="0000-00-00" <?=(isset($_TPL['ROW']['general_permission_date']) && $_TPL['ROW']['general_permission_date'] == '0000-00-00')?'checked':''?>> не предусмотрено</td>
  </tr>
  <tr>
    <td>Пункт генерального разрешения:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['general_permission_paragraph']) && $_TPL['ROW']['general_permission_paragraph'] != 'no')?$_TPL['ROW']['general_permission_paragraph']:''?>" name="general_permission_paragraph"> <input type="checkbox" name="general_permission_paragraph_check" value="no" <?=(isset($_TPL['ROW']['general_permission_paragraph']) && $_TPL['ROW']['general_permission_paragraph'] == 'no')?'checked':''?>> не предусмотрено<br />
<span class="smalltext">например: 9.3</span></td>
  </tr>
  <tr>
    <td colspan="2"><h3>Другие сведения</h3></td>
    </tr>
  <tr>
    <td>Должность руководителя организации<br>
(в именительном падеже)</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['director_duty']))?$_TPL['ROW']['director_duty']:''?>" name="director_duty"><br />
<span class="smalltext">например: Директор</span></td>
  </tr>
  <tr>
    <td>Должность руководителя организации<br>
(в родительном падеже)</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['director_duty_genitive']))?$_TPL['ROW']['director_duty_genitive']:''?>" name="director_duty_genitive"><br />
<span class="smalltext">например: Директора</span></td>
  </tr>
  <tr>
    <td>Фамилия и инициалы руководителя организации:<br>
(в именительном падеже)</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['director_lastname_initials']))?$_TPL['ROW']['director_lastname_initials']:''?>" name="director_lastname_initials"><br />
<span class="smalltext">например: Иванов И.И.</span></td>
  </tr>
  <tr>
    <td>ФИО руководителя организации:<br>
(полностью, в именительном падеже)</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['director_fio']))?$_TPL['ROW']['director_fio']:''?>" name="director_fio"><br />
<span class="smalltext">например: Иванов Иван Иванович</span></td>
  </tr>
  <tr>
    <td>ФИО руководителя организации:<br>
(полностью, в родительном падеже)</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['director_fio_genitive']))?$_TPL['ROW']['director_fio_genitive']:''?>" name="director_fio_genitive"><br />
<span class="smalltext">например: Иванова Ивана Ивановича</span></td>
  </tr>
  <tr>
    <td>Действующего на основании:<br>
(указать: Устава или Положения (в родительном падеже),<br>
а также доверенности, если Государственный контракт подписывается не руководителем организации)</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['based_on_doc_genitive']))?$_TPL['ROW']['based_on_doc_genitive']:''?>" name="based_on_doc_genitive"><br />
<span class="smalltext">например: Устава</span></td>
  </tr>
  <tr>
    <td>ОКАТО:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['OKATO']))?$_TPL['ROW']['OKATO']:''?>" name="OKATO"><br />
<span class="smalltext">например: 45286585000</span></td>
  </tr>
  <tr>
    <td>ОКПО:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['OKPO']))?$_TPL['ROW']['OKPO']:''?>" name="OKPO"><br />
<span class="smalltext">например: 02068322</span></td>
  </tr>
  <tr>
    <td>ОКВЭД:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['OKVED']))?$_TPL['ROW']['OKVED']:''?>" name="OKVED"><br />
<span class="smalltext">например: 80.30.1</span></td>
  </tr>
  <tr>
    <td>ОКОГУ:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['OKOGU']))?$_TPL['ROW']['OKOGU']:''?>" name="OKOGU"><br />
<span class="smalltext">например: 13240</span></td>
  </tr>
  <tr>
    <td>ОКОПФ:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['OKOPF']))?$_TPL['ROW']['OKOPF']:''?>" name="OKOPF"><br />
<span class="smalltext">например: 81</span></td>
  </tr>
  <tr>
    <td colspan="2"><h3>Контактные данные</h3></td>
    </tr>
<!--<tr>
    <td>Местонахождение:<br>
(юридический адрес)</td>
    <td><textarea name="legal_address"></textarea><br />
<span class="smalltext">например: 105024, г.Новокузнецк Кемеровской области, ул.Кирова, д.32</span></td>
  </tr>
  <tr>
    <td>Фактическое местонахождение:</td>
    <td><textarea name="fact_address"></textarea><br />
<span class="smalltext">например: 105024, г.Новокузнецк Кемеровской области, ул.Кирова, д.32</span></td>
  </tr>-->
  <tr>
    <td><strong>Местонахождение: (юридический адрес)</strong><br />
	Тип населенного пункта</td>
    <td><select name="legal_type_settlement">
		<option value="город" <?=(isset($_TPL['ROW']['legal_type_settlement']) && $_TPL['ROW']['legal_type_settlement'] == 'город')?'selected':''?>>город</option>
		<option value="ПГТ" <?=(isset($_TPL['ROW']['legal_type_settlement']) && $_TPL['ROW']['legal_type_settlement'] == 'ПГТ')?'selected':''?>>ПГТ</option>
		<option value="посёлок" <?=(isset($_TPL['ROW']['legal_type_settlement']) && $_TPL['ROW']['legal_type_settlement'] == 'посёлок')?'selected':''?>>посёлок</option>
		<option value="село" <?=(isset($_TPL['ROW']['legal_type_settlement']) && $_TPL['ROW']['legal_type_settlement'] == 'село')?'selected':''?>>село</option>
		<option value="деревня" <?=(isset($_TPL['ROW']['legal_type_settlement']) && $_TPL['ROW']['legal_type_settlement'] == 'деревня')?'selected':''?>>деревня</option>
		<option value="хутор" <?=(isset($_TPL['ROW']['legal_type_settlement']) && $_TPL['ROW']['legal_type_settlement'] == 'хутор')?'selected':''?>>хутор</option>
		</select></td>
  </tr>
  <tr>
    <td>Название населенного пункта:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['legal_name_settlement']))?$_TPL['ROW']['legal_name_settlement']:''?>" name="legal_name_settlement"><br />
	<span class="smalltext">например: Рыбинск</span></td>
  </tr>
  <tr>
    <td>Почтовый индекс:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['legal_post_index']))?$_TPL['ROW']['legal_post_index']:''?>" name="legal_post_index"><br />
	<span class="smalltext">например: 113356</span></td>
  </tr>
  <tr>
    <td></td>
    <td>
	<select name="legal_type_street">
		<option value="улица" <?=(isset($_TPL['ROW']['legal_type_street']) && $_TPL['ROW']['legal_type_street'] == 'улица')?'selected="selected"':''?>>улица</option>
		<option value="бульвар" <?=(isset($_TPL['ROW']['legal_type_street']) && $_TPL['ROW']['legal_type_street'] == 'бульвар')?'selected="selected"':''?>>бульвар</option>
		<option value="проспект" <?=(isset($_TPL['ROW']['legal_type_street']) && $_TPL['ROW']['legal_type_street'] == 'проспект')?'selected="selected"':''?>>проспект</option>
		<option value="проезд" <?=(isset($_TPL['ROW']['legal_type_street']) && $_TPL['ROW']['legal_type_street'] == 'проезд')?'selected="selected"':''?>>проезд</option>
		<option value="тупик" <?=(isset($_TPL['ROW']['legal_type_street']) && $_TPL['ROW']['legal_type_street'] == 'тупик')?'selected="selected"':''?>>тупик</option>
		<option value="шоссе" <?=(isset($_TPL['ROW']['legal_type_street']) && $_TPL['ROW']['legal_type_street'] == 'шоссе')?'selected="selected"':''?>>шоссе</option>
		<option value="площадь" <?=(isset($_TPL['ROW']['legal_type_street']) && $_TPL['ROW']['legal_type_street'] == 'площадь')?'selected="selected"':''?>>площадь</option>
		<option value="переулок" <?=(isset($_TPL['ROW']['legal_type_street']) && $_TPL['ROW']['legal_type_street'] == 'переулок')?'selected="selected"':''?>>переулок</option>
		<option value="набережная" <?=(isset($_TPL['ROW']['legal_type_street']) && $_TPL['ROW']['legal_type_street'] == 'набережная')?'selected="selected"':''?>>набережная</option>		
	</select>
	Название: <input type="text" value="<?=(isset($_TPL['ROW']['legal_name_street']))?$_TPL['ROW']['legal_name_street']:''?>" name="legal_name_street" style="width: 150px;"><br />
	дом: <input type="text" value="<?=(isset($_TPL['ROW']['legal_number_house']))?$_TPL['ROW']['legal_number_house']:''?>" name="legal_number_house" style="width: 25px;"> 
	корпус: <input type="text" value="<?=(isset($_TPL['ROW']['legal_number_housing']))?$_TPL['ROW']['legal_number_housing']:''?>" name="legal_number_housing" style="width: 25px;"> 
	строение: <input type="text" value="<?=(isset($_TPL['ROW']['legal_number_structure']))?$_TPL['ROW']['legal_number_structure']:''?>" name="legal_number_structure" style="width: 25px;"> 
	офис: <input type="text" value="<?=(isset($_TPL['ROW']['legal_number_office']))?$_TPL['ROW']['legal_number_office']:''?>" name="legal_number_office" style="width: 25px;">
	</td>
  </tr>
  <tr>
    <td><strong>Фактическое местонахождение:</strong></td>
    <td>Совпадает с юридическим адресом: <select name="checkfactadress">
		<option value="no" <?=(isset($_TPL['ROW']['checkfactadress']) && $_TPL['ROW']['checkfactadress'] == 'no')?'selected="selected"':''?>>Нет</option>
		<option value="yes" <?=(isset($_TPL['ROW']['checkfactadress']) && $_TPL['ROW']['checkfactadress'] == 'yes')?'selected="selected"':''?>>Да</option>
	</select></td>
  </tr>
	<tr class="factaddress<?=(isset($_TPL['ROW']['checkfactadress']) && $_TPL['ROW']['checkfactadress'] == 'yes')?' display-none"':'"'?>>
    <td>Тип населенного пункта</td>
     <td><select name="fact_type_settlement">
		<option value="город" <?=(isset($_TPL['ROW']['fact_type_settlement']) && $_TPL['ROW']['fact_type_settlement'] == 'город')?'selected':''?>>город</option>
		<option value="ПГТ" <?=(isset($_TPL['ROW']['fact_type_settlement']) && $_TPL['ROW']['fact_type_settlement'] == 'ПГТ')?'selected':''?>>ПГТ</option>
		<option value="посёлок" <?=(isset($_TPL['ROW']['fact_type_settlement']) && $_TPL['ROW']['fact_type_settlement'] == 'посёлок')?'selected':''?>>посёлок</option>
		<option value="село" <?=(isset($_TPL['ROW']['fact_type_settlement']) && $_TPL['ROW']['fact_type_settlement'] == 'село')?'selected':''?>>село</option>
		<option value="деревня" <?=(isset($_TPL['ROW']['fact_type_settlement']) && $_TPL['ROW']['fact_type_settlement'] == 'деревня')?'selected':''?>>деревня</option>
		<option value="хутор" <?=(isset($_TPL['ROW']['fact_type_settlement']) && $_TPL['ROW']['fact_type_settlement'] == 'хутор')?'selected':''?>>хутор</option>
		</select></td>
  </tr>
  <tr class="factaddress<?=(isset($_TPL['ROW']['checkfactadress']) && $_TPL['ROW']['checkfactadress'] == 'yes')?' display-none"':'"'?>>
    <td>Название населенного пункта:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['fact_name_settlement']))?$_TPL['ROW']['fact_name_settlement']:''?>" name="fact_name_settlement"><br />
	<span class="smalltext">например: Рыбинск</span></td>
  </tr>
  <tr class="factaddress<?=(isset($_TPL['ROW']['checkfactadress']) && $_TPL['ROW']['checkfactadress'] == 'yes')?' display-none"':'"'?>>
    <td>Почтовый индекс:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['fact_post_index']))?$_TPL['ROW']['fact_post_index']:''?>" name="fact_post_index"><br />
	<span class="smalltext">например: 113356</span></td>
  </tr>
  <tr class="factaddress<?=(isset($_TPL['ROW']['checkfactadress']) && $_TPL['ROW']['checkfactadress'] == 'yes')?' display-none"':'"'?>>
    <td></td>
    <td>
	<select name="fact_type_street">
		<option value="улица" <?=(isset($_TPL['ROW']['fact_type_street']) && $_TPL['ROW']['fact_type_street'] == 'улица')?'selected="selected"':''?>>улица</option>
		<option value="бульвар" <?=(isset($_TPL['ROW']['fact_type_street']) && $_TPL['ROW']['fact_type_street'] == 'бульвар')?'selected="selected"':''?>>бульвар</option>
		<option value="проспект" <?=(isset($_TPL['ROW']['fact_type_street']) && $_TPL['ROW']['fact_type_street'] == 'проспект')?'selected="selected"':''?>>проспект</option>
		<option value="проезд" <?=(isset($_TPL['ROW']['fact_type_street']) && $_TPL['ROW']['fact_type_street'] == 'проезд')?'selected="selected"':''?>>проезд</option>
		<option value="тупик" <?=(isset($_TPL['ROW']['fact_type_street']) && $_TPL['ROW']['fact_type_street'] == 'тупик')?'selected="selected"':''?>>тупик</option>
		<option value="шоссе" <?=(isset($_TPL['ROW']['fact_type_street']) && $_TPL['ROW']['fact_type_street'] == 'шоссе')?'selected="selected"':''?>>шоссе</option>
		<option value="площадь" <?=(isset($_TPL['ROW']['fact_type_street']) && $_TPL['ROW']['fact_type_street'] == 'площадь')?'selected="selected"':''?>>площадь</option>
		<option value="переулок" <?=(isset($_TPL['ROW']['fact_type_street']) && $_TPL['ROW']['fact_type_street'] == 'переулок')?'selected="selected"':''?>>переулок</option>
		<option value="набережная" <?=(isset($_TPL['ROW']['fact_type_street']) && $_TPL['ROW']['fact_type_street'] == 'набережная')?'selected="selected"':''?>>набережная</option>		
	</select>
	Название: <input type="text" value="<?=(isset($_TPL['ROW']['fact_name_street']))?$_TPL['ROW']['fact_name_street']:''?>" name="fact_name_street" style="width: 150px;"><br />
	дом: <input type="text" value="<?=(isset($_TPL['ROW']['fact_number_house']))?$_TPL['ROW']['fact_number_house']:''?>" name="fact_number_house" style="width: 25px;"> 
	корпус: <input type="text" value="<?=(isset($_TPL['ROW']['fact_number_housing']))?$_TPL['ROW']['fact_number_housing']:''?>" name="fact_number_housing" style="width: 25px;"> 
	строение: <input type="text" value="<?=(isset($_TPL['ROW']['fact_number_structure']))?$_TPL['ROW']['fact_number_structure']:''?>" name="fact_number_structure" style="width: 25px;"> 
	офис: <input type="text" value="<?=(isset($_TPL['ROW']['fact_number_office']))?$_TPL['ROW']['fact_number_office']:''?>" name="fact_number_office" style="width: 25px;">
	</td>
  </tr>
  <tr>
    <td>Телефон руководителя организации<br />
и телефон гл. бухгалтера:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['director_phone']))?$_TPL['ROW']['director_phone']:''?>" name="director_phone"><br />
<input type="text" value="<?=(isset($_TPL['ROW']['accountant_phone']))?$_TPL['ROW']['accountant_phone']:''?>" name="accountant_phone"><br />
<span class="smalltext">например: (495) 236-23-43; (499) 236-13-43</span></td>
  </tr>
  <tr>
    <td>Телефон для связи с участником:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['perfomer_phone']))?$_TPL['ROW']['perfomer_phone']:''?>" name="perfomer_phone"><br />
<span class="smalltext">например: (495) 236-23-43; (499) 236-13-43</span></td>
  </tr>
  <tr>
    <td>Электронная почта для связи:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['e_mail']))?$_TPL['ROW']['e_mail']:''?>" name="e_mail"></td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;"><input type="submit" value="Сохранить данные"></td>
    </tr>
</table>
</form>