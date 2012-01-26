<?=(USER_TYPE == 'yur')?"<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>":""?>
<p align="right">В Департамент градостроительной<br>
политики города Москвы<br>
первому заместителю руководителя<br>
Рындину О.В.</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p align="center"><strong>Форма 2. Сведения об организации</strong></p>
<p></p>
<p></p>
<p>Полное наименование организации: <?=(!empty($TPL['APPLICANT']['full_title']))?$TPL['APPLICANT']['full_title']:''?></p>

<p>Краткое наименование организации: <?=(!empty($TPL['APPLICANT']['short_title']))?$TPL['APPLICANT']['short_title']:''?></p>

<p>ИНН: <?=(!empty($TPL['APPLICANT']['INN']))?$TPL['APPLICANT']['INN']:''?></p>

<p>КПП: <?=(!empty($TPL['APPLICANT']['KPP']))?$TPL['APPLICANT']['KPP']:''?></p>

<p>ОГРН: <?=(!empty($TPL['APPLICANT']['OGRN']))?$TPL['APPLICANT']['OGRN']:''?></p>

<p>Дата присвоения ОГРН: <?=(!empty($TPL['APPLICANT']['OGRN_attribution']))?$TPL['APPLICANT']['OGRN_attribution']:''?></p>

<p><strong>Банковские реквизиты участника</strong></p>

<p>Наименование принятое в ОФК: <?=(!empty($TPL['APPLICANT']['title_for_bank']) && $TPL['APPLICANT']['title_for_bank'] != 'no')?$TPL['APPLICANT']['title_for_bank']:'не предусмотрено'?></p>

<p>Л/С: <?=(!empty($TPL['APPLICANT']['bank_ls']) && $TPL['APPLICANT']['bank_ls'] != 'no')?$TPL['APPLICANT']['bank_ls']:'не предусмотрено'?></p>

<p>БИК: <?=(!empty($TPL['APPLICANT']['bank_bik']))?$TPL['APPLICANT']['bank_bik']:''?></p>

<p>Расчетный счет: <?=(!empty($TPL['APPLICANT']['bank_ras']))?$TPL['APPLICANT']['bank_ras']:''?></p>

<p>Корреспондентский счет: <?=(!empty($TPL['APPLICANT']['bank_cor']) && $TPL['APPLICANT']['bank_cor'] != 'no')?$TPL['APPLICANT']['bank_cor']:'не предусмотрено'?></p>

<p>Расчетный счет (внебюджетный): <?=(!empty($TPL['APPLICANT']['bank_rasv']) && $TPL['APPLICANT']['bank_rasv'] != 'no')?$TPL['APPLICANT']['bank_rasv']:'не предусмотрено'?></p>

<p>Наименование банка получателя: <?=(!empty($TPL['APPLICANT']['bank_receiver']))?$TPL['APPLICANT']['bank_receiver']:''?></p>

<p><strong>Сведения о бюджетополучателе</strong></p>

<p>КБК: <?=(!empty($TPL['APPLICANT']['KBK']) && $TPL['APPLICANT']['KBK'] != 'no')?$TPL['APPLICANT']['KBK']:'не предусмотрено'?></p>

<p>Номер генерального разрешения: <?=(!empty($TPL['APPLICANT']['general_permission_num']) && $TPL['APPLICANT']['general_permission_num'] != 'no')?$TPL['APPLICANT']['general_permission_num']:'не предусмотрено'?></p>

<p>Дата генерального разрешения: <?=(!empty($TPL['APPLICANT']['general_permission_date']) && $TPL['APPLICANT']['general_permission_date'] != '0000-00-00')?$TPL['APPLICANT']['general_permission_date']:'не предусмотрено'?></p>

<p>Пункт генерального разрешения: <?=(!empty($TPL['APPLICANT']['general_permission_paragraph']) && $TPL['APPLICANT']['general_permission_paragraph'] != 'no')?$TPL['APPLICANT']['general_permission_paragraph']:'не предусмотрено'?></p>

<p><strong>Другие сведения</strong></p>

<p>Должность руководителя организации <?=(!empty($TPL['APPLICANT']['director_duty']))?$TPL['APPLICANT']['director_duty']:''?></p>

<p>ФИО руководителя организации: <?=(!empty($TPL['APPLICANT']['director_fio']))?$TPL['APPLICANT']['director_fio']:''?></p>

<p>Действующего на основании (указать: Устава или Положения, а также доверенности, если Государственный контракт подписывается не руководителем организации): <?=(!empty($TPL['APPLICANT']['based_on_doc_genitive']))?$TPL['APPLICANT']['based_on_doc_genitive']:''?></p>

<p>ОКАТО: <?=(!empty($TPL['APPLICANT']['OKATO']))?$TPL['APPLICANT']['OKATO']:''?></p>

<p>ОКПО: <?=(!empty($TPL['APPLICANT']['OKPO']))?$TPL['APPLICANT']['OKPO']:''?></p>

<p>ОКВЭД: <?=(!empty($TPL['APPLICANT']['OKVED']))?$TPL['APPLICANT']['OKVED']:''?></p>

<p>ОКОГУ: <?=(!empty($TPL['APPLICANT']['OKOGU']))?$TPL['APPLICANT']['OKOGU']:''?></p>

<p>ОКОПФ: <?=(!empty($TPL['APPLICANT']['OKOPF']))?$TPL['APPLICANT']['OKOPF']:''?></p>

<p><strong>Контактные данные</strong></p>

<p><strong>Местонахождение: (юридический адрес)</strong> <?=(!empty($TPL['APPLICANT']['legal_post_index']))?$TPL['APPLICANT']['legal_post_index']:''?>, <?=(!empty($TPL['APPLICANT']['legal_type_settlement']))?$TPL['APPLICANT']['legal_type_settlement']:''?>  <?=(!empty($TPL['APPLICANT']['legal_name_settlement']))?$TPL['APPLICANT']['legal_name_settlement']:''?>, <?=(!empty($TPL['APPLICANT']['legal_type_street']))?$TPL['APPLICANT']['legal_type_street']:''?> <?=(!empty($TPL['APPLICANT']['legal_name_street']))?$TPL['APPLICANT']['legal_name_street']:''?><?=(!empty($TPL['APPLICANT']['legal_number_house']))?", дом ".$TPL['APPLICANT']['legal_number_house']:''?><?=(!empty($TPL['APPLICANT']['legal_number_housing']))?", корпус ".$TPL['APPLICANT']['legal_number_housing']:''?><?=(!empty($TPL['APPLICANT']['legal_number_structure']))?", строение ".$TPL['APPLICANT']['legal_number_structure']:''?><?=(!empty($TPL['APPLICANT']['legal_number_office']))?", офис ".$TPL['APPLICANT']['legal_number_office']:''?></p>

<p><strong>Фактическое местонахождение:</strong>  <?=(!empty($TPL['APPLICANT']['fact_post_index']))?$TPL['APPLICANT']['fact_post_index']:''?>, <?=(!empty($TPL['APPLICANT']['fact_type_settlement']))?$TPL['APPLICANT']['fact_type_settlement']:''?>  <?=(!empty($TPL['APPLICANT']['fact_name_settlement']))?$TPL['APPLICANT']['fact_name_settlement']:''?>, <?=(!empty($TPL['APPLICANT']['fact_type_street']))?$TPL['APPLICANT']['fact_type_street']:''?> <?=(!empty($TPL['APPLICANT']['fact_name_street']))?$TPL['APPLICANT']['fact_name_street']:''?><?=(!empty($TPL['APPLICANT']['fact_number_house']))?", дом ".$TPL['APPLICANT']['fact_number_house']:''?><?=(!empty($TPL['APPLICANT']['fact_number_housing']))?", корпус ".$TPL['APPLICANT']['fact_number_housing']:''?><?=(!empty($TPL['APPLICANT']['fact_number_structure']))?", строение ".$TPL['APPLICANT']['fact_number_structure']:''?><?=(!empty($TPL['APPLICANT']['fact_number_office']))?", офис ".$TPL['APPLICANT']['fact_number_office']:''?></p>


<p>Телефон руководителя организации <?=(!empty($TPL['APPLICANT']['director_phone']))?$TPL['APPLICANT']['director_phone']:''?></p>

<p>Телефон гл. бухгалтера: <?=(!empty($TPL['APPLICANT']['accountant_phone']))?$TPL['APPLICANT']['accountant_phone']:''?></p>

<p>Телефон для связи с участником: <?=(!empty($TPL['APPLICANT']['perfomer_phone']))?$TPL['APPLICANT']['perfomer_phone']:''?></p>

<p>Электронная почта для связи: <?=(!empty($TPL['APPLICANT']['e_mail']))?$TPL['APPLICANT']['e_mail']:''?></p>

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