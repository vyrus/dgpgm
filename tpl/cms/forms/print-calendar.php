<style>
table.table {
	border-collapse: collapse;
	font-size: 70%;
}	
table.table td {
	border: 1px solid #000;
	padding: 5px;
	margin: 5px;
}
</style>
<p align="center"><strong>КАЛЕНДАРНЫЙ ПЛАН</strong><br>
 предложение о сроке выполнения работ (оказания услуг) по теме:</p>
<p align="center">«<?=$TPL['INFO']['work_topic']?>»</p>
<p></p>

<table class="table">
  <tr>
    <td>№ этапа</td>
    <td>Наименование работ/услуг</td>
    <td>Краткое описание выполняемых работ/услуг</td>
    <td>Срок выполнения работ/услуг (количество месяцев со дня заключения госконтракта)</td>
	<td>Стоимость работ, тыс. руб.</td>
  </tr>
<?	$nn=1;
foreach ($array as $year=>$data)
{?>
	<tr>
		<td colspan="5" align="center"><?=$year?> год</td>
	</tr>
	<? foreach ($data as $step=>$val) { ?>
	<?
	$count_works = count($val);
	if ($count_works>1) {
		$rowspan=' rowspan="'.$count_works.'"';
	} else {
		$rowspan="";
	}
	?>
	<?	$i=0;
		foreach ($val as $work_data) { ?>
		<tr>
			<? if ($i==0) { ?><td<?=$rowspan?>><?=$step?></td><? } ?>
			<td><?=$work_data['title']?></td>
			<td><?=$work_data['description']?></td>
			<? if ($i==0) { ?><td<?=$rowspan?>><?=MonthsName($work_data['start_month'])?> <?=$year?> г. - <?=MonthsName($work_data['finish_month'])?> <?=$year?> г.</td><? } ?>
			<? if ($i==0) { ?><td<?=$rowspan?>><?=round((($TPL['INFO']['price_works_actual']*$work_data['cost'])/100)/1000, 2)?></td><? } ?>
		</tr>
		<?	$i++;
			} ?>
	<? } ?>
<? } ?>
</table>
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