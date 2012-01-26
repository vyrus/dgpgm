<h3>Сведения об организации <?=(isset($TPL['ROW']['full_title']))?htmlspecialchars($TPL['ROW']['full_title']):''?></h3>


<table width="100%" class="table">
  <tr>
    <td style="width: 250px;">Полное наименование организации:</td>
    <td><?=(isset($TPL['ROW']['full_title']))?htmlspecialchars($TPL['ROW']['full_title']):''?></td>
  </tr>
  <tr>
    <td>Полное наименование организации в родительном падеже:</td>
    <td><?=(isset($TPL['ROW']['full_title_genitive']))?htmlspecialchars($TPL['ROW']['full_title_genitive']):''?></td>
  </tr>
  <tr>
    <td>Полное наименование организации в творительном падеже:</td>
    <td><?=(isset($TPL['ROW']['full_title_instrumental']))?htmlspecialchars($TPL['ROW']['full_title_instrumental']):''?></td>
  </tr>
  <tr>
    <td>Краткое наименование организации:</td>
    <td><?=(isset($TPL['ROW']['short_title']))?htmlspecialchars($TPL['ROW']['short_title']):''?></td>
  </tr>
  <tr>
    <td>ИНН</td>
    <td><?=(isset($TPL['ROW']['INN']))?$TPL['ROW']['INN']:''?></td>
  </tr>
  <tr>
    <td>КПП</td>
    <td><?=(isset($TPL['ROW']['KPP']))?$TPL['ROW']['KPP']:''?></td>
  </tr>
  <tr>
    <td>ОГРН</td>
    <td><?=(isset($TPL['ROW']['OGRN']))?$TPL['ROW']['OGRN']:''?></td>
  </tr>
  <tr>
    <td>Дата присвоения ОГРН:</td>
    <td><?=(isset($TPL['ROW']['OGRN_attribution']))?$TPL['ROW']['OGRN_attribution']:''?></td>
  </tr>
  <tr>
    <td>Наименование принятое в ОФК:</td>
    <td><?=(isset($TPL['ROW']['title_for_bank']) && $TPL['ROW']['title_for_bank'] != 'no')?$TPL['ROW']['title_for_bank']:''?> <?=(isset($TPL['ROW']['title_for_bank']) && $TPL['ROW']['title_for_bank'] == 'no')?'не предусмотрено':''?></td>
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
    <td><?=(isset($TPL['ROW']['bank_cor']) && $TPL['ROW']['bank_cor'] != 'no')?$TPL['ROW']['bank_cor']:''?> <?=(isset($TPL['ROW']['bank_cor']) && $TPL['ROW']['bank_cor'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Расчетный счет (внебюджетный):</td>
    <td><?=(isset($TPL['ROW']['bank_rasv']) && $TPL['ROW']['bank_rasv'] != 'no')?$TPL['ROW']['bank_rasv']:''?><?=(isset($TPL['ROW']['bank_rasv']) && $TPL['ROW']['bank_rasv'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Наименование банка получателя:</td>
    <td><?=(isset($TPL['ROW']['bank_receiver']))?$TPL['ROW']['bank_receiver']:''?></td>
  </tr>
  <tr>
    <td>КБК:</td>
    <td><?=(isset($TPL['ROW']['KBK']) && $TPL['ROW']['KBK'] != 'no')?$TPL['ROW']['KBK']:''?><?=(isset($TPL['ROW']['KBK']) && $TPL['ROW']['KBK'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Номер генерального разрешения:</td>
    <td><?=(isset($TPL['ROW']['general_permission_num']) && $TPL['ROW']['general_permission_num'] != 'no')?$TPL['ROW']['general_permission_num']:''?><?=(isset($TPL['ROW']['general_permission_num']) && $TPL['ROW']['general_permission_num'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Дата генерального разрешения:</td>
    <td><?=(isset($TPL['ROW']['general_permission_date']) && $TPL['ROW']['general_permission_date'] != '0000-00-00')?$TPL['ROW']['general_permission_date']:''?><?=(isset($TPL['ROW']['general_permission_date']) && $TPL['ROW']['general_permission_date'] == '0000-00-00')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Пункт генерального разрешения:</td>
    <td><?=(isset($TPL['ROW']['general_permission_paragraph']) && $TPL['ROW']['general_permission_paragraph'] != 'no')?$TPL['ROW']['general_permission_paragraph']:''?><?=(isset($TPL['ROW']['general_permission_paragraph']) && $TPL['ROW']['general_permission_paragraph'] == 'no')?'не предусмотрено':''?></td>
  </tr>
  <tr>
    <td>Должность руководителя организации<br>
(в именительном падеже)</td>
    <td><?=(isset($TPL['ROW']['director_duty']))?$TPL['ROW']['director_duty']:''?></td>
  </tr>
  <tr>
    <td>Должность руководителя организации<br>
(в родительном падеже)</td>
    <td><?=(isset($TPL['ROW']['director_duty_genitive']))?$TPL['ROW']['director_duty_genitive']:''?></td>
  </tr>
  <tr>
    <td>Фамилия и инициалы руководителя организации:<br>
(в именительном падеже)</td>
    <td><?=(isset($TPL['ROW']['director_lastname_initials']))?$TPL['ROW']['director_lastname_initials']:''?></td>
  </tr>
  <tr>
    <td>ФИО руководителя организации:<br>
(полностью, в именительном падеже)</td>
    <td><?=(isset($TPL['ROW']['director_fio']))?$TPL['ROW']['director_fio']:''?></td>
  </tr>
  <tr>
    <td>ФИО руководителя организации:<br>
(полностью, в родительном падеже)</td>
    <td><?=(isset($TPL['ROW']['director_fio_genitive']))?$TPL['ROW']['director_fio_genitive']:''?></td>
  </tr>
  <tr>
    <td>Действующего на основании:<br>
(указать: Устава или Положения (в родительном падеже),<br>
а также доверенности, если Государственный контракт подписывается не руководителем организации)</td>
    <td><?=(isset($TPL['ROW']['based_on_doc_genitive']))?$TPL['ROW']['based_on_doc_genitive']:''?></td>
  </tr>
  <tr>
    <td>ОКАТО:</td>
    <td><?=(isset($TPL['ROW']['OKATO']))?$TPL['ROW']['OKATO']:''?></td>
  </tr>
  <tr>
    <td>ОКПО:</td>
    <td><?=(isset($TPL['ROW']['OKPO']))?$TPL['ROW']['OKPO']:''?></td>
  </tr>
  <tr>
    <td>ОКВЭД:</td>
    <td><?=(isset($TPL['ROW']['OKVED']))?$TPL['ROW']['OKVED']:''?></td>
  </tr>
  <tr>
    <td>ОКОГУ:</td>
    <td><?=(isset($TPL['ROW']['OKOGU']))?$TPL['ROW']['OKOGU']:''?></td>
  </tr>
  <tr>
    <td>ОКОПФ:</td>
    <td><?=(isset($TPL['ROW']['OKOPF']))?$TPL['ROW']['OKOPF']:''?></td>
  </tr>
  <tr>
    <td><strong>Местонахождение: (юридический адрес)</strong><br />
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
	офис: <?=(isset($TPL['ROW']['legal_number_office']))?$TPL['ROW']['legal_number_office']:''?>
	</td>
  </tr>
  <tr>
    <td><strong>Фактическое местонахождение:</strong></td>
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
	офис: <?=(isset($TPL['ROW']['fact_number_office']))?$TPL['ROW']['fact_number_office']:''?>
	</td>
  </tr>
  <? } ?>
  <tr>
    <td>Телефон руководителя организации<br />
и телефон гл. бухгалтера:</td>
    <td><?=(isset($TPL['ROW']['director_phone']))?$TPL['ROW']['director_phone']:''?><br />
<?=(isset($TPL['ROW']['accountant_phone']))?$TPL['ROW']['accountant_phone']:''?></td>
  </tr>
  <tr>
    <td>Телефон для связи с участником:</td>
    <td><?=(isset($TPL['ROW']['perfomer_phone']))?$TPL['ROW']['perfomer_phone']:''?></td>
  </tr>
  <tr>
    <td>Электронная почта для связи:</td>
    <td><?=(isset($TPL['ROW']['e_mail']))?$TPL['ROW']['e_mail']:''?></td>
  </tr>
  <tr>
    <td colspan="2" style="text-align:center;">
	
		<div style="float: left; margin-left: 100px;"><input type="button" value="Использовать существующие данные" onclick="document.location='/?mod=forms&action=duplicate-org&id=<?=$org?>'"></div>
		<div style="float: right; margin-right: 150px;"><form method="post" action="/forms/bid/<?=$_TPL['USERDATA']['bid_id']?>/infoyur">
		<? foreach ($TPL['ROW'] as $key=>$row) { ?>
			<input type="hidden" value="<?=$row?>" name="<?=$key?>">
		<? } ?>
		<input type="submit" value="Редактировать данные" name="edit-org">
		</form></div>
	</td>
    </tr>
</table>