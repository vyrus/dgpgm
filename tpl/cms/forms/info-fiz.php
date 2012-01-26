<?

include TPL_CMS."_header.php";

?>
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
	
	$('input[name="pasport_date"]').attachDatepicker({
		rangeSelect: false,
		yearRange: '1990:<?=date('Y')?>',
		firstDay: 1
	});
	$('input[name="licence_date"]').attachDatepicker({
		rangeSelect: false,
		yearRange: '1990:<?=date('Y')?>',
		firstDay: 1
	});
	$('input[name="EGRIP_date"]').attachDatepicker({
		rangeSelect: false,
		yearRange: '1990:<?=date('Y')?>',
		firstDay: 1
	});
	$('input[name="registr_date"]').attachDatepicker({
		rangeSelect: false,
		yearRange: '1990:<?=date('Y')?>',
		firstDay: 1
	});
	$('input[name="OGRN_attribution"]').attachDatepicker({
		rangeSelect: false,
		yearRange: '1990:<?=date('Y')?>',
		firstDay: 1
	});
	
});
</script>	

<?=$TPL['BIDMENU']?>

<h1>Сведения о физическом лице</h1>

<p>Заполните поля формы. В любой момент Вы можете сохранить введенные данные нажатием на кнопку внизу формы. Также Вы можете перемещаться между формами используя ссылки на соответствующие шаги в меню слева или ссылки над заголовком формы.</p>
<p>При вводе данных пользуйтесь примерами, указанными рядом с полем ввода</p>

<form method="post">

<table width="100%">
  <tr>
    <td colspan="2"><h3>Сведения об участнике</h3></td>
    </tr>
  <tr>
    <td style="width: 300px;">Фамилия участника в именительном падеже:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['last_name']))?$_TPL['ROW']['last_name']:''?>" name="last_name"><br />
    <span class="smalltext">например: Иванов</span>
</td>
  </tr>
  <tr>
    <td>Имя участника в именительном падеже</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['first_name']))?$_TPL['ROW']['first_name']:''?>" name="first_name"><br />
    <span class="smalltext">например: Иван</span></td>
  </tr>
  <tr>
    <td>Отчество участника в именительном падеже</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['middle_name']))?$_TPL['ROW']['middle_name']:''?>" name="middle_name"><br />
	<span class="smalltext">например: Иванович</span></td>
  </tr>
  <tr>
    <td>Выберите статус, к которому	Вы относитесь</td>
    <td><select name="org_form">
		<option value="физическое лицо" <?=(isset($_TPL['ROW']['org_form']) && $_TPL['ROW']['org_form'] == 'физическое лицо')?'selected':''?>>физическое лицо</option>
		<option value="ИП" <?=(isset($_TPL['ROW']['org_form']) && $_TPL['ROW']['org_form'] == 'ИП')?'selected':''?>>ИП</option>
		<option value="ИЧП" <?=(isset($_TPL['ROW']['org_form']) && $_TPL['ROW']['org_form'] == 'ИЧП')?'selected':''?>>ИЧП</option>
		<option value="ПБОЮЛ" <?=(isset($_TPL['ROW']['org_form']) && $_TPL['ROW']['org_form'] == 'ПБОЮЛ')?'selected':''?>>ПБОЮЛ</option>
	</select></td>
  </tr>
  <tr>
    <td>ИНН</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['INN']))?$_TPL['ROW']['INN']:''?>" name="INN"></td>
  </tr>
  <tr>
    <td>Номер паспорта:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['pasport_number']))?$_TPL['ROW']['pasport_number']:''?>" name="pasport_number"><br />
	<span class="smalltext">например:133466</span></td>
  </tr>
  <tr>
    <td>Серия паспорта:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['pasport_ser']))?$_TPL['ROW']['pasport_ser']:''?>" name="pasport_ser"><br />
	<span class="smalltext">например:4507</span></td>
  </tr>
  <tr>
    <td>Дата выдачи паспорта:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['pasport_date']) && $_TPL['ROW']['pasport_date'] != '0000-00-00')?$_TPL['ROW']['pasport_date']:''?>" name="pasport_date"></td>
  </tr>
  <tr>
    <td>Кем выдан паспорт:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['pasport_issued_by']))?$_TPL['ROW']['pasport_issued_by']:''?>" name="pasport_issued_by"></td>
  </tr>
  <tr>
    <td>Код подразделения:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['compartment_code']))?$_TPL['ROW']['compartment_code']:''?>" name="compartment_code"></td>
  </tr>
  <tr>
    <td><strong>Адрес по прописке:</strong><br />
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
	квартира: <input type="text" value="<?=(isset($_TPL['ROW']['legal_number_office']))?$_TPL['ROW']['legal_number_office']:''?>" name="legal_number_office" style="width: 25px;"><br />
	<span class="smalltext">Примечание: заполнить поля в соответствии с отметкой в паспорте</span>
	</td>
  </tr>
  <tr>
    <td><strong>Фактический адрес:</strong></td>
    <td>Совпадает с адресом по прописке: <select name="checkfactadress">
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
	квартира: <input type="text" value="<?=(isset($_TPL['ROW']['fact_number_office']))?$_TPL['ROW']['fact_number_office']:''?>" name="fact_number_office" style="width: 25px;">
	</td>
  </tr>
	<tr>
    <td>Номер свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['licence_num']) && $_TPL['ROW']['licence_num'] != 'no')?$_TPL['ROW']['licence_num']:''?>" name="licence_num"> <input type="checkbox" name="licence_num_check" value="no" <?=(isset($_TPL['ROW']['licence_num']) && $_TPL['ROW']['licence_num'] == 'no')?'checked':''?>> не предусмотрено</td>
  </tr>
  <tr>
    <td>Серия свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['licence_ser']) && $_TPL['ROW']['licence_ser'] != 'no')?$_TPL['ROW']['licence_ser']:''?>" name="licence_ser"> <input type="checkbox" name="licence_ser_check" value="no" <?=(isset($_TPL['ROW']['licence_ser']) && $_TPL['ROW']['licence_ser'] == 'no')?'checked':''?>> не предусмотрено</td>
  </tr>
  <tr>
    <td>Дата выдачи свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['licence_date']) && $_TPL['ROW']['licence_date'] != '0000-00-00')?$_TPL['ROW']['licence_date']:''?>" name="licence_date"> <input type="checkbox" name="licence_date_check" value="0000-00-00" <?=(isset($_TPL['ROW']['licence_date']) && $_TPL['ROW']['licence_date'] == '0000-00-00')?'checked':''?>> не предусмотрено</td>
  </tr>
  <tr>
    <td>Номер свидетельства о внесении в ЕГРИП</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['EGRIP_num']) && $_TPL['ROW']['EGRIP_num'] != 'no')?$_TPL['ROW']['EGRIP_num']:''?>" name="EGRIP_num"> <input type="checkbox" name="EGRIP_num_check" value="no" <?=(isset($_TPL['ROW']['EGRIP_num']) && $_TPL['ROW']['EGRIP_num'] == 'no')?'checked':''?>> не предусмотрено<br />
	<span class="smalltext">например: 006355014</span></td>
  </tr>
  <tr>
    <td>Серия свидетельства о внесении в ЕГРИП</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['EGRIP_ser']) && $_TPL['ROW']['EGRIP_ser'] != 'no')?$_TPL['ROW']['EGRIP_ser']:''?>" name="EGRIP_ser"> <input type="checkbox" name="EGRIP_ser_check" value="no" <?=(isset($_TPL['ROW']['EGRIP_ser']) && $_TPL['ROW']['EGRIP_ser'] == 'no')?'checked':''?>> не предусмотрено<br />
	<span class="smalltext">например: 77</span></td>
  </tr>
  <tr>
    <td>Дата регистрации в регистрирующем органе</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['EGRIP_date']) && $_TPL['ROW']['EGRIP_date'] != '0000-00-00')?$_TPL['ROW']['EGRIP_date']:''?>" name="EGRIP_date"> <input type="checkbox" name="EGRIP_date_check" value="0000-00-00" <?=(isset($_TPL['ROW']['EGRIP_date']) && $_TPL['ROW']['EGRIP_date'] == '0000-00-00')?'checked':''?>> не предусмотрено</td>
  </tr>
  <tr>
    <td>Наименование регистрирующего органа</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['registrator']) && $_TPL['ROW']['registrator'] != 'no')?$_TPL['ROW']['registrator']:''?>" name="registrator"> <input type="checkbox" name="registrator_check" value="no" <?=(isset($_TPL['ROW']['registrator']) && $_TPL['ROW']['registrator'] == 'no')?'checked':''?>> не предусмотрено<br />
	<span class="smalltext">например: Московская регистрационная палата</span></td>
  </tr>  
  <tr>
    <td>Государственный регистрационный номер записи о государственной	регистрации</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['registr_num']) && $_TPL['ROW']['registr_num'] != 'no')?$_TPL['ROW']['registr_num']:''?>" name="registr_num"> <input type="checkbox" name="registr_num_check" value="no" <?=(isset($_TPL['ROW']['registr_num']) && $_TPL['ROW']['registr_num'] == 'no')?'checked':''?>> не предусмотрено<br />
	<span class="smalltext">например: 305770000013329</span></td>
  </tr>  
  <tr>
    <td>Дата внесения записи</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['registr_date']) && $_TPL['ROW']['registr_date'] != '0000-00-00')?$_TPL['ROW']['registr_date']:''?>" name="registr_date"> <input type="checkbox" name="registr_date_check" value="0000-00-00" <?=(isset($_TPL['ROW']['registr_date']) && $_TPL['ROW']['registr_date'] == '0000-00-00')?'checked':''?>> не предусмотрено</td>
  </tr>
   <tr>
    <td>КПП:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['KPP']) && $_TPL['ROW']['KPP'] != 'no')?$_TPL['ROW']['KPP']:''?>" name="KPP"> <input type="checkbox" name="KPP_check" value="no" <?=(isset($_TPL['ROW']['KPP']) && $_TPL['ROW']['KPP'] == 'no')?'checked':''?>> не предусмотрено</td>
  </tr>
  <tr>
    <td>ОГРН:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['OGRN']) && $_TPL['ROW']['OGRN'] != 'no')?$_TPL['ROW']['OGRN']:''?>" name="OGRN"> <input type="checkbox" name="OGRN_check" value="no" <?=(isset($_TPL['ROW']['OGRN']) && $_TPL['ROW']['OGRN'] == 'no')?'checked':''?>> не предусмотрено</td>
  </tr>
  <tr>
    <td>Дата присвоения ОГРН:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['OGRN_attribution']) && $_TPL['ROW']['OGRN_attribution'] != '0000-00-00')?$_TPL['ROW']['OGRN_attribution']:''?>" name="OGRN_attribution"> <input type="checkbox" name="OGRN_attribution_check" value="0000-00-00" <?=(isset($_TPL['ROW']['OGRN_attribution']) && $_TPL['ROW']['OGRN_attribution'] == '0000-00-00')?'checked':''?>> не предусмотрено</td>
  </tr> 
  <tr>
    <td colspan="2"><h3>Банковские реквизиты участника, данные о банке получателе</h3></td>
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
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_cor']))?$_TPL['ROW']['bank_cor']:''?>" name="bank_cor"><br />
<span class="smalltext">например: 30101814500000000225</span></td>
  </tr>
  <tr>
    <td>Наименование банка получателя:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_receiver']))?$_TPL['ROW']['bank_receiver']:''?>" name="bank_receiver"><br>
<span class="smalltext">например: Отделение 1 Московского ГТУ Банка России г. Москва</span></td>
  </tr>
  <tr>
    <td>ИНН банка получателя:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_INN']))?$_TPL['ROW']['bank_INN']:''?>" name="bank_INN"></td>
  </tr>
  <tr>
    <td>КПП:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_KPP']))?$_TPL['ROW']['bank_KPP']:''?>" name="bank_KPP"></td>
  </tr>
  <tr>
    <td><strong>Адрес банка получателя:</strong><br />
	Тип населенного пункта</td>
	<td><select name="bank_type_settlement">
		<option value="город" <?=(isset($_TPL['ROW']['bank_type_settlement']) && $_TPL['ROW']['bank_type_settlement'] == 'город')?'selected':''?>>город</option>
		<option value="ПГТ" <?=(isset($_TPL['ROW']['bank_type_settlement']) && $_TPL['ROW']['bank_type_settlement'] == 'ПГТ')?'selected':''?>>ПГТ</option>
		<option value="посёлок" <?=(isset($_TPL['ROW']['bank_type_settlement']) && $_TPL['ROW']['bank_type_settlement'] == 'посёлок')?'selected':''?>>посёлок</option>
		<option value="село" <?=(isset($_TPL['ROW']['bank_type_settlement']) && $_TPL['ROW']['bank_type_settlement'] == 'село')?'selected':''?>>село</option>
		<option value="деревня" <?=(isset($_TPL['ROW']['bank_type_settlement']) && $_TPL['ROW']['bank_type_settlement'] == 'деревня')?'selected':''?>>деревня</option>
		<option value="хутор" <?=(isset($_TPL['ROW']['bank_type_settlement']) && $_TPL['ROW']['bank_type_settlement'] == 'хутор')?'selected':''?>>хутор</option>
		</select></td>
  </tr>
  <tr>
    <td>Название населенного пункта:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_name_settlement']))?$_TPL['ROW']['bank_name_settlement']:''?>" name="bank_name_settlement"><br />
	<span class="smalltext">например: Рыбинск</span></td>
  </tr>
  <tr>
    <td>Почтовый индекс:</td>
    <td><input type="text" value="<?=(isset($_TPL['ROW']['bank_post_index']))?$_TPL['ROW']['bank_post_index']:''?>" name="bank_post_index"><br />
	<span class="smalltext">например: 113356</span></td>
  </tr>
  <tr>
    <td></td>
    <td>
	<select name="bank_type_street">
		<option value="улица" <?=(isset($_TPL['ROW']['bank_type_street']) && $_TPL['ROW']['bank_type_street'] == 'улица')?'selected="selected"':''?>>улица</option>
		<option value="бульвар" <?=(isset($_TPL['ROW']['bank_type_street']) && $_TPL['ROW']['bank_type_street'] == 'бульвар')?'selected="selected"':''?>>бульвар</option>
		<option value="проспект" <?=(isset($_TPL['ROW']['bank_type_street']) && $_TPL['ROW']['bank_type_street'] == 'проспект')?'selected="selected"':''?>>проспект</option>
		<option value="проезд" <?=(isset($_TPL['ROW']['bank_type_street']) && $_TPL['ROW']['bank_type_street'] == 'проезд')?'selected="selected"':''?>>проезд</option>
		<option value="тупик" <?=(isset($_TPL['ROW']['bank_type_street']) && $_TPL['ROW']['bank_type_street'] == 'тупик')?'selected="selected"':''?>>тупик</option>
		<option value="шоссе" <?=(isset($_TPL['ROW']['bank_type_street']) && $_TPL['ROW']['bank_type_street'] == 'шоссе')?'selected="selected"':''?>>шоссе</option>
		<option value="площадь" <?=(isset($_TPL['ROW']['bank_type_street']) && $_TPL['ROW']['bank_type_street'] == 'площадь')?'selected="selected"':''?>>площадь</option>
		<option value="переулок" <?=(isset($_TPL['ROW']['bank_type_street']) && $_TPL['ROW']['bank_type_street'] == 'переулок')?'selected="selected"':''?>>переулок</option>
		<option value="набережная" <?=(isset($_TPL['ROW']['bank_type_street']) && $_TPL['ROW']['bank_type_street'] == 'набережная')?'selected="selected"':''?>>набережная</option>		
	</select>
	Название: <input type="text" value="<?=(isset($_TPL['ROW']['bank_name_street']))?$_TPL['ROW']['bank_name_street']:''?>" name="bank_name_street" style="width: 150px;"><br />
	дом: <input type="text" value="<?=(isset($_TPL['ROW']['bank_number_house']))?$_TPL['ROW']['bank_number_house']:''?>" name="bank_number_house" style="width: 25px;"> 
	корпус: <input type="text" value="<?=(isset($_TPL['ROW']['bank_number_housing']))?$_TPL['ROW']['bank_number_housing']:''?>" name="bank_number_housing" style="width: 25px;"> 
	строение: <input type="text" value="<?=(isset($_TPL['ROW']['bank_number_structure']))?$_TPL['ROW']['bank_number_structure']:''?>" name="bank_number_structure" style="width: 25px;"> 
	офис: <input type="text" value="<?=(isset($_TPL['ROW']['bank_number_office']))?$_TPL['ROW']['bank_number_office']:''?>" name="bank_number_office" style="width: 25px;"><br />
	<span class="smalltext">Примечание: заполнить необходимые поля</span>
	</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align: center;"><input type="submit" value="Сохранить данные"></td>
  </tr>
</table>
</form>

<?
    include TPL_CMS."_footer.php";
?>