<p align="right">Проект<br>
в Департамент градостроительной<br>
политики города Москвы<br>
первому заместителю руководителя<br>
Рындину О.В.</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p align="center"><strong>Сведения о физическом лице</strong></p>
<p></p>
<p></p>
<p>Фамилия, имя, отчество: <?=(!empty($TPL['APPLICANT']['last_name']))?$TPL['APPLICANT']['last_name']:''?> <?=(!empty($TPL['APPLICANT']['first_name']))?$TPL['APPLICANT']['first_name']:''?> <?=(!empty($TPL['APPLICANT']['middle_name']))?$TPL['APPLICANT']['middle_name']:''?></p>

<p>Выберите статус, к которому Вы относитесь (физическое лицо, ИП, ИЧП, ПБОЮЛ): <?=(!empty($TPL['APPLICANT']['org_form']))?$TPL['APPLICANT']['org_form']:''?></p>

<p>ИНН: <?=(!empty($TPL['APPLICANT']['INN']))?$TPL['APPLICANT']['INN']:''?></p>

<p>Номер паспорта: <?=(!empty($TPL['APPLICANT']['pasport_number']))?$TPL['APPLICANT']['pasport_number']:''?></p>

<p>Серия паспорта: <?=(!empty($TPL['APPLICANT']['pasport_ser']))?$TPL['APPLICANT']['pasport_ser']:''?></p>

<p>Дата выдачи паспорта: <?=(!empty($TPL['APPLICANT']['pasport_date']) && $TPL['APPLICANT']['pasport_date'] != '0000-00-00')?$TPL['APPLICANT']['pasport_date']:''?></p>

<p>Кем выдан паспорт: <?=(!empty($TPL['APPLICANT']['pasport_issued_by']))?$TPL['APPLICANT']['pasport_issued_by']:''?></p>

<p>Код подразделения: <?=(!empty($TPL['APPLICANT']['compartment_code']))?$TPL['APPLICANT']['compartment_code']:''?></p>

<p><strong>Адрес по прописке:</strong> <?=(!empty($TPL['APPLICANT']['legal_post_index']))?$TPL['APPLICANT']['legal_post_index']:''?>, <?=(!empty($TPL['APPLICANT']['legal_type_settlement']))?$TPL['APPLICANT']['legal_type_settlement']:''?>  <?=(!empty($TPL['APPLICANT']['legal_name_settlement']))?$TPL['APPLICANT']['legal_name_settlement']:''?>, <?=(!empty($TPL['APPLICANT']['legal_type_street']))?$TPL['APPLICANT']['legal_type_street']:''?> <?=(!empty($TPL['APPLICANT']['legal_name_street']))?$TPL['APPLICANT']['legal_name_street']:''?><?=(!empty($TPL['APPLICANT']['legal_number_house']))?", дом ".$TPL['APPLICANT']['legal_number_house']:''?><?=(!empty($TPL['APPLICANT']['legal_number_housing']))?", корпус ".$TPL['APPLICANT']['legal_number_housing']:''?><?=(!empty($TPL['APPLICANT']['legal_number_structure']))?", строение ".$TPL['APPLICANT']['legal_number_structure']:''?><?=(!empty($TPL['APPLICANT']['legal_number_office']))?", квартира ".$TPL['APPLICANT']['legal_number_office']:''?></p>

<p><strong>Фактический адрес:</strong> <?=(!empty($TPL['APPLICANT']['fact_post_index']))?$TPL['APPLICANT']['fact_post_index']:''?>, <?=(!empty($TPL['APPLICANT']['fact_type_settlement']))?$TPL['APPLICANT']['fact_type_settlement']:''?>  <?=(!empty($TPL['APPLICANT']['fact_name_settlement']))?$TPL['APPLICANT']['fact_name_settlement']:''?>, <?=(!empty($TPL['APPLICANT']['fact_type_street']))?$TPL['APPLICANT']['fact_type_street']:''?> <?=(!empty($TPL['APPLICANT']['fact_name_street']))?$TPL['APPLICANT']['fact_name_street']:''?><?=(!empty($TPL['APPLICANT']['fact_number_house']))?", дом ".$TPL['APPLICANT']['fact_number_house']:''?><?=(!empty($TPL['APPLICANT']['fact_number_housing']))?", корпус ".$TPL['APPLICANT']['fact_number_housing']:''?><?=(!empty($TPL['APPLICANT']['fact_number_structure']))?", строение ".$TPL['APPLICANT']['fact_number_structure']:''?><?=(!empty($TPL['APPLICANT']['fact_number_office']))?", квартира ".$TPL['APPLICANT']['fact_number_office']:''?></p>

<p>Номер свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя: <?=(!empty($TPL['APPLICANT']['licence_num']) && $TPL['APPLICANT']['licence_num'] != 'no')?$TPL['APPLICANT']['licence_num']:'не предусмотрено'?></p>

<p>Серия свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя: <?=(!empty($TPL['APPLICANT']['licence_ser']) && $TPL['APPLICANT']['licence_ser'] != 'no')?$TPL['APPLICANT']['licence_ser']:'не предусмотрено'?></p>

<p>Дата выдачи свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя: <?=(!empty($TPL['APPLICANT']['licence_date']) && $TPL['APPLICANT']['licence_date'] != '0000-00-00')?$TPL['APPLICANT']['licence_date']:'не предусмотрено'?></p>

<p>Номер свидетельства о внесении в ЕГРИП: <?=(!empty($TPL['APPLICANT']['EGRIP_num']) && $TPL['APPLICANT']['EGRIP_num'] != 'no')?$TPL['APPLICANT']['EGRIP_num']:'не предусмотрено'?></p>

<p>Серия свидетельства о внесении в ЕГРИП: <?=(!empty($TPL['APPLICANT']['EGRIP_ser']) && $TPL['APPLICANT']['EGRIP_ser'] != 'no')?$TPL['APPLICANT']['EGRIP_ser']:'не предусмотрено'?></p>

<p>Дата регистрации в регистрирующем органе: <?=(!empty($TPL['APPLICANT']['EGRIP_date']) && $TPL['APPLICANT']['EGRIP_date'] != '0000-00-00')?$TPL['APPLICANT']['EGRIP_date']:'не предусмотрено'?></p>

<p>Наименование регистрирующего органа: <?=(!empty($TPL['APPLICANT']['registrator']) && $TPL['APPLICANT']['registrator'] != 'no')?$TPL['APPLICANT']['registrator']:'не предусмотрено'?></p>

<p>Государственный регистрационный номер записи о государственной регистрации: <?=(!empty($TPL['APPLICANT']['registr_num']) && $TPL['APPLICANT']['registr_num'] != 'no')?$TPL['APPLICANT']['registr_num']:'не предусмотрено'?></p>

<p>Дата внесения записи: <?=(!empty($TPL['APPLICANT']['registr_date']) && $TPL['APPLICANT']['registr_date'] != '0000-00-00')?$TPL['APPLICANT']['registr_date']:'не предусмотрено'?></p>

<p>КПП: <?=(!empty($TPL['APPLICANT']['KPP']) && $TPL['APPLICANT']['KPP'] != 'no')?$TPL['APPLICANT']['KPP']:'не предусмотрено'?></p>

<p>ОГРН: <?=(!empty($TPL['APPLICANT']['OGRN']) && $TPL['APPLICANT']['OGRN'] != 'no')?$TPL['APPLICANT']['OGRN']:'не предусмотрено'?></p>

<p>Дата присвоения ОГРН: <?=(!empty($TPL['APPLICANT']['OGRN_attribution']) && $TPL['APPLICANT']['OGRN_attribution'] != '0000-00-00')?$TPL['APPLICANT']['OGRN_attribution']:'не предусмотрено'?></p>

<p><strong>Банковские реквизиты участника, данные о банке получателе</strong></p>

<p>Л/С: <?=(!empty($TPL['APPLICANT']['bank_ls']) && $TPL['APPLICANT']['bank_ls'] != 'no')?$TPL['APPLICANT']['bank_ls']:'не предусмотрено'?></p>

<p>БИК: <?=(!empty($TPL['APPLICANT']['bank_bik']))?$TPL['APPLICANT']['bank_bik']:''?></p>

<p>Расчетный счет: <?=(!empty($TPL['APPLICANT']['bank_ras']))?$TPL['APPLICANT']['bank_ras']:''?></p>

<p>Корреспондентский счет: <?=(!empty($TPL['APPLICANT']['bank_cor']))?$TPL['APPLICANT']['bank_cor']:''?></p>

<p>Наименование банка получателя: <?=(!empty($TPL['APPLICANT']['bank_receiver']))?$TPL['APPLICANT']['bank_receiver']:''?></p>

<p>ИНН банка получателя: <?=(!empty($TPL['APPLICANT']['bank_INN']))?$TPL['APPLICANT']['bank_INN']:''?></p>

<p>КПП: <?=(!empty($TPL['APPLICANT']['bank_KPP']))?$TPL['APPLICANT']['bank_KPP']:''?></p>

<p>Адрес банка получателя: <?=(!empty($TPL['APPLICANT']['bank_post_index']))?$TPL['APPLICANT']['bank_post_index']:''?>, <?=(!empty($TPL['APPLICANT']['bank_type_settlement']))?$TPL['APPLICANT']['bank_type_settlement']:''?>  <?=(!empty($TPL['APPLICANT']['bank_name_settlement']))?$TPL['APPLICANT']['bank_name_settlement']:''?>, <?=(!empty($TPL['APPLICANT']['bank_type_street']))?$TPL['APPLICANT']['bank_type_street']:''?> <?=(!empty($TPL['APPLICANT']['bank_name_street']))?$TPL['APPLICANT']['bank_name_street']:''?><?=(!empty($TPL['APPLICANT']['bank_number_house']))?", дом ".$TPL['APPLICANT']['bank_number_house']:''?><?=(!empty($TPL['APPLICANT']['bank_number_housing']))?", корпус ".$TPL['APPLICANT']['bank_number_housing']:''?><?=(!empty($TPL['APPLICANT']['bank_number_structure']))?", строение ".$TPL['APPLICANT']['bank_number_structure']:''?><?=(!empty($TPL['APPLICANT']['bank_number_office']))?", офис ".$TPL['APPLICANT']['bank_number_office']:''?></p>

<p></p>
<p></p>

<table style="width:100%;">
  <tr nobr="true">
    <td style="width:40%; text-align: center; vertical-align: middle;"><?=$applicant_form?></td>
    <td style="width:20%; text-align: center; vertical-align: middle;">___________________<br>
        М.П.</td>
    <td style="width:40%; text-align: center; vertical-align: middle;"><?=$applicant_name?></td>
  </tr>
</table>