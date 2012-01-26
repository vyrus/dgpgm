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
<p align="center"><strong>ОБОСНОВАНИЕ ЦЕНЫ</strong><br>
  выполняемых работ  (услуг) по теме:</p>
<p align="center">«<?=$TPL['INFO']['work_topic']?>»</p>
<p></p>
<p align="center">Расчет себестоимости проводимых работ (услуг) в базовом уровне цен</p>
<table class="table" style="width:100%;">
  <tr>
    <td nowrap>№ п/п</td>
    <td>Наименование должностей исполнителей</td>
    <td>Фактическое время участия исполнителя в работе, день</td>
    <td>Плановая продолжительность выполнения работ, день</td>
    <td>Численность исполнителей одной квалификации, чел.</td>
    <td>Индекс уровня зарплаты специалистов исполнителей работы</td>
    <td>Коэффициент квалификации (участия) специалистов</td>
  </tr>
<? if ($TPL['PERFORMERS']) {
	$i = 1;
	$performers = 0;
	$summa_koefitsent_specialists = 0;
	foreach ($TPL['PERFORMERS'] as $perform) { ?>
	<tr id="tr_row_<?=$i?>">
		<td style="text-align: center;"><?=$i?></td>
		<td><?=$perform['position']?></td>
		<td><?=$perform['fact_time_job']?></td>
		<td></td>
		<td><?=$perform['number_performers']?></td>
		<td><?=$perform['coef']?></td>
		<? $cfq=$this->calculateCoefQualif($perform['fact_time_job'], $TPL['INFO']['duration'], $perform['number_performers'], $perform['coef']);?>
		<td><?=round($cfq, 3)?></td>
	</tr>
<?	$performers = $performers + $perform['number_performers'];
	$summa_koefitsent_specialists = $summa_koefitsent_specialists + $cfq;
	$chp = $chp + $perform['number_performers'];//Чп
	$i++;
	}
} ?>
  <tr>
    <td>&nbsp;</td>
    <td>Итого:</td>
    <td>&nbsp;</td>
    <td><?=$TPL['INFO']['duration']?></td>
    <td></td>
    <td>&nbsp;</td>
	<? $cfqe = $this->calculateCoefQualifEnd($summa_koefitsent_specialists, $chp); ?>
    <td><?=round($cfqe, 3)?></td>
  </tr>
</table>
<p></p>
<p align="center">Определение стоимости работ (услуг) в текущем уровне цен,<br>
  с учетом сложности и объемности работ (услуг)</p>
<table class="table" style="width:100%;">
  <tr>
    <td><?=$TPL['COST'][0]['name']?></td>
    <td><?=$TPL['COST'][1]['name']?></td>
    <td>Среднедневная зарплата исполнителей (руб.)</td>
    <td><?=$TPL['COST'][2]['name']?></td>
    <td>Единичная себестоимость (руб.)</td>
    <td>Продолжительность разработки (дн.)</td>
    <td>Численность разработчиков</td>
    <td>Коэффициент квалификации</td>
    <td>Общая себестоимость выполняемых работ (тыс. руб.)</td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][0]['value']?></td>
    <td><?=$TPL['COST'][1]['value']?></td>
	<? $w_d = $this->calculateAverageDailyWages($TPL['COST'][0]['value'], $TPL['COST'][1]['value']); ?>
    <td><?=round($w_d, 2)?></td>
    <td><?=($TPL['COST'][2]['value']*100)?></td>
	<? $ss1 = $this->calculateUnitCost($w_d, $TPL['COST'][2]['value']);?>
    <td><?=round($ss1, 2)?></td>
    <td><?=$TPL['INFO']['duration']?></td>
    <td><?=$performers?></td>
    <td><?=round($cfqe, 3)?></td>
	<? $ssob = $this->calculateAllCost($ss1,$TPL['INFO']['duration'],$chp,$cfqe); ?>
    <td><?=round($ssob/1000, 2)?></td>
  </tr>
</table>
<p></p>
<table class="table" style="width:100%;">
  <tr>
    <td>Общая себестоимость выполняемых работ (тыс. руб.)</td>
    <td><?=$TPL['COST'][3]['name']?></td>
    <td>Стоимость работ (услуг) (тыс. руб.)</td>
    <td><?=$TPL['COST'][4]['name']?></td>
    <td>Стоимость выполняемых работ (услуг) в текущих ценах (тыс. руб.)</td>
	<?
	if (!isset($TPL['INFO']['user_nds'])) {
		$user_nds = $TPL['COST'][5]['value'];
	} else {
		$user_nds = $TPL['INFO']['user_nds'];
	}
	?>
    <td>В том числе НДС <?=$user_nds*100?>% (тыс. руб.)</td>
  </tr>
  <tr>
    <td><?=round($ssob/1000, 2)?></td>
    <td><?=($TPL['COST'][3]['value']*100)?></td>
	<? $ss2000 = $this->calculatePriceWorks($ssob, $TPL['COST'][3]['value']); ?>
    <td><?=round($ss2000/1000, 2)?></td>
    <td><?=$TPL['COST'][4]['value']?></td>
	<? $stc = $this->calculatePriceWorksActual($ss2000, $TPL['COST'][4]['value']); ?>
    <td><?=round($stc/1000, 2)?></td>
	<? $nds = $this->calculateNDS($stc, $user_nds); ?>
    <td><?=round($nds/1000, 2)?></td>
  </tr>
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