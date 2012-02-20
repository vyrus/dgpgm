<h3>Сведения о физическом лице <?=(isset($TPL['ROW']['full_title']))?htmlspecialchars($TPL['ROW']['full_title']):''?></h3>


<table width="100%" class="table">
  <tr>
    <td style="width: 250px;">Фамилия участника в именительном падеже:</td>
    <td><?=(isset($TPL['ROW']['last_name']))?$TPL['ROW']['last_name']:''?></td>
  </tr>
  <tr>
    <td>Имя участника в именительном падеже</td>
    <td><?=(isset($TPL['ROW']['first_name']))?$TPL['ROW']['first_name']:''?></td>
  </tr>
  <tr>
    <td>Отчество участника в именительном падеже</td>
    <td><?=(isset($TPL['ROW']['middle_name']))?$TPL['ROW']['middle_name']:''?></td>
  </tr>
  <tr>
    <td>Статус</td>
    <td><?=(isset($TPL['ROW']['org_form']))?$TPL['ROW']['org_form']:''?></td>
  </tr>
  <tr>
    <td>ИНН</td>
    <td><?=(isset($TPL['ROW']['INN']))?$TPL['ROW']['INN']:''?></td>
  </tr>
  <tr>
    <td>Номер паспорта:</td>
    <td><?=(isset($TPL['ROW']['pasport_number']))?$TPL['ROW']['pasport_number']:''?></td>
  </tr>
  <tr>
    <td>Серия паспорта:</td>
    <td><?=(isset($TPL['ROW']['pasport_ser']))?$TPL['ROW']['pasport_ser']:''?></td>
  </tr>
  <tr>
    <td>Дата выдачи паспорта:</td>
    <td><?=(isset($TPL['ROW']['pasport_date']) && $TPL['ROW']['pasport_date'] != '0000-00-00')?$TPL['ROW']['pasport_date']:''?></td>
  </tr>
  <tr>
    <td>Кем выдан паспорт:</td>
    <td><?=(isset($TPL['ROW']['pasport_issued_by']))?$TPL['ROW']['pasport_issued_by']:''?></td>
  </tr>
  <tr>
    <td>Код подразделения:</td>
    <td><?=(isset($TPL['ROW']['compartment_code']))?$TPL['ROW']['compartment_code']:''?></td>
  </tr>
  <tr>
    <td><strong>Адрес по прописке:</strong><br />
	Тип населенного пункта</td>
    <td><?=(isset($TPL['ROW']['legal_type_settlement']))?$TPL['ROW']['legal_type_settlement']:''?></td>
  </tr>
  <tr>
    <td>Название населенного пункта:</td>
    <td><?=(isset($TPL['ROW']['legal_name_settlement']))?$TPL['ROW']['legal_name_settlement']:''?></td>
  </tr>
  <tr>
    <td>Почтовый индекс:</td>
    <td><?=(isset($TPL['ROW']['legal_post_index']))?$TPL['ROW']['legal_post_index']:''?></td>
  </tr>
  <tr>
    <td></td>
    <td><?=(isset($TPL['ROW']['legal_type_street']))?$TPL['ROW']['legal_type_street']:''?> <?=(isset($TPL['ROW']['legal_name_street']))?$TPL['ROW']['legal_name_street']:''?> дом: <?=(isset($TPL['ROW']['legal_number_house']))?$TPL['ROW']['legal_number_house']:''?> корпус: <?=(isset($TPL['ROW']['legal_number_housing']))?$TPL['ROW']['legal_number_housing']:''?>
	строение: <?=(isset($TPL['ROW']['legal_number_structure']))?$TPL['ROW']['legal_number_structure']:''?>
	квартира: <?=(isset($TPL['ROW']['legal_number_office']))?$TPL['ROW']['legal_number_office']:''?>
	</td>
  </tr>
  <tr>
    <td><strong>Фактический адрес:</strong></td>
    <td>Совпадает с юридическим адресом: <?=(isset($TPL['ROW']['checkfactadress']) && $TPL['ROW']['checkfactadress'] == 'no')?'Нет':''?>
	<?=(isset($TPL['ROW']['checkfactadress']) && $TPL['ROW']['checkfactadress'] == 'yes')?'Да':''?>
	</td>
  </tr>
  <? if (isset($TPL['ROW']['checkfactadress']) && $TPL['ROW']['checkfactadress'] == 'no') {?>
	<tr>
    <td>Тип населенного пункта</td>
     <td><?=(isset($TPL['ROW']['fact_type_settlement']))?$TPL['ROW']['fact_type_settlement']:''?></td>
  </tr>
  <tr>
    <td>Название населенного пункта:</td>
    <td><?=(isset($TPL['ROW']['fact_name_settlement']))?$TPL['ROW']['fact_name_settlement']:''?></td>
  </tr>
  <tr>
    <td>Почтовый индекс:</td>
    <td><?=(isset($TPL['ROW']['fact_post_index']))?$TPL['ROW']['fact_post_index']:''?></td>
  </tr>
  <tr>
    <td></td>
    <td>
	<?=(isset($TPL['ROW']['fact_type_street']))?$TPL['ROW']['fact_type_street']:''?>  <?=(isset($TPL['ROW']['fact_name_street']))?$TPL['ROW']['fact_name_street']:''?> дом: <?=(isset($TPL['ROW']['fact_number_house']))?$TPL['ROW']['fact_number_house']:''?>
	корпус: <?=(isset($TPL['ROW']['fact_number_housing']))?$TPL['ROW']['fact_number_housing']:''?>
	строение: <?=(isset($TPL['ROW']['fact_number_structure']))?$TPL['ROW']['fact_number_structure']:''?>
	квартира: <?=(isset($TPL['ROW']['fact_number_office']))?$TPL['ROW']['fact_number_office']:''?>
	</td>
  </tr>
  <? } ?>
	<tr>
    <td>Номер свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя</td>
    <td><?=(isset($TPL['ROW']['licence_num']) && $TPL['ROW']['licence_num'] != 'no')?$TPL['ROW']['licence_num']:''?><?=(isset($TPL['ROW']['licence_num']) && $TPL['ROW']['licence_num'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Серия свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя</td>
    <td><?=(isset($TPL['ROW']['licence_ser']) && $TPL['ROW']['licence_ser'] != 'no')?$TPL['ROW']['licence_ser']:''?> <?=(isset($TPL['ROW']['licence_ser']) && $TPL['ROW']['licence_ser'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Дата выдачи свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя</td>
    <td><?=(isset($TPL['ROW']['licence_date']) && $TPL['ROW']['licence_date'] != '0000-00-00')?$TPL['ROW']['licence_date']:''?> <?=(isset($TPL['ROW']['licence_date']) && $TPL['ROW']['licence_date'] == '0000-00-00')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Номер свидетельства о внесении в ЕГРИП</td>
    <td><?=(isset($TPL['ROW']['EGRIP_num']) && $TPL['ROW']['EGRIP_num'] != 'no')?$TPL['ROW']['EGRIP_num']:''?> <?=(isset($TPL['ROW']['EGRIP_num']) && $TPL['ROW']['EGRIP_num'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Серия свидетельства о внесении в ЕГРИП</td>
    <td><?=(isset($TPL['ROW']['EGRIP_ser']) && $TPL['ROW']['EGRIP_ser'] != 'no')?$TPL['ROW']['EGRIP_ser']:''?> <?=(isset($TPL['ROW']['EGRIP_ser']) && $TPL['ROW']['EGRIP_ser'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Дата регистрации в регистрирующем органе</td>
    <td><?=(isset($TPL['ROW']['EGRIP_date']) && $TPL['ROW']['EGRIP_date'] != '0000-00-00')?$TPL['ROW']['EGRIP_date']:''?> <?=(isset($TPL['ROW']['EGRIP_date']) && $TPL['ROW']['EGRIP_date'] == '0000-00-00')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Наименование регистрирующего органа</td>
    <td><?=(isset($TPL['ROW']['registrator']) && $TPL['ROW']['registrator'] != 'no')?$TPL['ROW']['registrator']:''?> <?=(isset($TPL['ROW']['registrator']) && $TPL['ROW']['registrator'] == 'no')?'не предусмотрено':''?></td>
  </tr>  
  <tr>
    <td>Государственный регистрационный номер записи о государственной	регистрации</td>
    <td><?=(isset($TPL['ROW']['registr_num']) && $TPL['ROW']['registr_num'] != 'no')?$TPL['ROW']['registr_num']:''?> <?=(isset($TPL['ROW']['registr_num']) && $TPL['ROW']['registr_num'] == 'no')?'не предусмотрено':''?></td>
  </tr>  
  <tr>
    <td>Дата внесения записи</td>
    <td><?=(isset($TPL['ROW']['registr_date']) && $TPL['ROW']['registr_date'] != '0000-00-00')?$TPL['ROW']['registr_date']:''?> <?=(isset($TPL['ROW']['registr_date']) && $TPL['ROW']['registr_date'] == '0000-00-00')?'не предусмотрено':''?></td>
  </tr>
   <tr>
    <td>КПП:</td>
    <td><?=(isset($TPL['ROW']['KPP']) && $TPL['ROW']['KPP'] != 'no')?$TPL['ROW']['KPP']:''?> <?=(isset($TPL['ROW']['KPP']) && $TPL['ROW']['KPP'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>ОГРН:</td>
    <td><?=(isset($TPL['ROW']['OGRN']) && $TPL['ROW']['OGRN'] != 'no')?$TPL['ROW']['OGRN']:''?> <?=(isset($TPL['ROW']['OGRN']) && $TPL['ROW']['OGRN'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Дата присвоения ОГРН:</td>
    <td><?=(isset($TPL['ROW']['OGRN_attribution']) && $TPL['ROW']['OGRN_attribution'] != '0000-00-00')?$TPL['ROW']['OGRN_attribution']:''?> <?=(isset($TPL['ROW']['OGRN_attribution']) && $TPL['ROW']['OGRN_attribution'] == '0000-00-00')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Л/С:</td>
    <td><?=(isset($TPL['ROW']['bank_ls']) && $TPL['ROW']['bank_ls'] != 'no')?$TPL['ROW']['bank_ls']:''?> <?=(isset($TPL['ROW']['bank_ls']) && $TPL['ROW']['bank_ls'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>БИК:</td>
    <td><?=(isset($TPL['ROW']['bank_bik']))?$TPL['ROW']['bank_bik']:''?></td>
  </tr>
  <tr>
    <td>Расчетный счет:</td>
    <td><?=(isset($TPL['ROW']['bank_ras']))?$TPL['ROW']['bank_ras']:''?></td>
  </tr>
  <tr>
    <td>Корреспондентский счет:</td>
    <td><?=(isset($TPL['ROW']['bank_cor']))?$TPL['ROW']['bank_cor']:''?></td>
  </tr>
  <tr>
    <td>Наименование банка получателя:</td>
    <td><?=(isset($TPL['ROW']['bank_receiver']))?$TPL['ROW']['bank_receiver']:''?></td>
  </tr>
  <tr>
    <td>ИНН банка получателя:</td>
    <td><?=(isset($TPL['ROW']['bank_INN']))?$TPL['ROW']['bank_INN']:''?></td>
  </tr>
  <tr>
    <td>КПП:</td>
    <td><?=(isset($TPL['ROW']['bank_KPP']))?$TPL['ROW']['bank_KPP']:''?></td>
  </tr>
  <tr>
    <td><strong>Адрес банка получателя:</strong><br />
	Тип населенного пункта</td>
    <td><?=(isset($TPL['ROW']['bank_type_settlement']))?$TPL['ROW']['bank_type_settlement']:''?></td>
  </tr>
  <tr>
    <td>Название населенного пункта:</td>
    <td><?=(isset($TPL['ROW']['bank_name_settlement']))?$TPL['ROW']['bank_name_settlement']:''?></td>
  </tr>
  <tr>
    <td>Почтовый индекс:</td>
    <td><?=(isset($TPL['ROW']['bank_post_index']))?$TPL['ROW']['bank_post_index']:''?></td>
  </tr>
  <tr>
    <td></td>
    <td><?=(isset($TPL['ROW']['bank_type_street']))?$TPL['ROW']['bank_type_street']:''?> <?=(isset($TPL['ROW']['bank_name_street']))?$TPL['ROW']['bank_name_street']:''?> дом: <?=(isset($TPL['ROW']['bank_number_house']))?$TPL['ROW']['bank_number_house']:''?> корпус: <?=(isset($TPL['ROW']['bank_number_housing']))?$TPL['ROW']['bank_number_housing']:''?>
	строение: <?=(isset($TPL['ROW']['bank_number_structure']))?$TPL['ROW']['bank_number_structure']:''?>
	офис: <?=(isset($TPL['ROW']['bank_number_office']))?$TPL['ROW']['bank_number_office']:''?>
	</td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">
	
		<div style="float: left; margin-left: 100px;"><input type="button" value="Использовать существующие данные" onclick="document.location='/?mod=forms&action=duplicate-fiz&id=<?=$fiz?>'"></div>
		<div style="float: right; margin-right: 150px;"><form method="post" action="/forms/bid/<?=$_TPL['USERDATA']['bid_id']?>/infofiz">
		<? foreach ($TPL['ROW'] as $key=>$row) { ?>
			<input type="hidden" value="<?=$row?>" name="<?=$key?>">
		<? } ?>
		<input type="submit" value="Редактировать данные" name="edit-fiz">
		</form></div>
	</td>
    </tr>
</table>