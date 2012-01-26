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
<p align="center"><strong>ТЕХНИЧЕСКОЕ ЗАДАНИЕ</strong><br>
 на выполнение работ (оказание услуг) по теме:</p>
<p align="center">«<?=$TPL['INFO']['work_topic']?>»</p>
<p></p>
<h3>Общие сведения</h3>
<p><strong>Основание для проведения работ (оказания услуг):</strong></p>
<?=$TPL['LISTFIELDS']['basis']?>

<p><strong>Государственный заказчик:</strong></p>
<?=$TPL['INFO']['departament_name']?>

<p><strong>Исполнитель:</strong><br />
<p>определяется по результатам открытого конкурса.</p>

<p><strong>Источник и порядок финансирования:</strong></p>
<?=$TPL['LISTFIELDS']['funding']?>

<p><strong>1. Наименование выполняемых работ (оказываемых услуг)</strong></p>
<p><?=$TPL['INFO']['work_topic']?></p>

<p><strong>2. Место выполнения работ (оказания услуг)</strong></p>
<? if ($TPL['INFO']['place_name']==1) {
		$place_name = 'город';
	} elseif ($TPL['INFO']['place_name']==2) {
		$place_name = 'поселок';
	} elseif ($TPL['INFO']['place_name']==3) {
		$place_name = 'село';
	} elseif ($TPL['INFO']['place_name']==4) {
		$place_name = 'поселок городского типа';
	} elseif ($TPL['INFO']['place_name']==5) {
		$place_name = 'деревня';
	}
?>

<?=$place_name?> <?=$TPL['INFO']['place_type_id']?>

<p><strong>3. Сроки выполнения работ (оказания услуг)</strong></p>
<? $startDateArr = split("-",$TPL['INFO']['start_date']);?>
<p>3.1. Начало выполнения работ (оказания услуг) – <?=MonthsName($startDateArr[1])?> <?=$startDateArr[0]?>.<br />
<? $finishDateArr = split("-",$TPL['INFO']['finish_date']);?>
3.2. Окончание выполнения работ (оказания услуг) – <?=MonthsName($finishDateArr[1])?> <?=$finishDateArr[0]?>.</p>

<p><strong>4. Цели выполнения работ (оказания услуг)</strong></p>
4.1. Целью работ является:
<p>
<? if (!empty($_TPL['WORKPURPOSE']))
    {
        foreach($_TPL['WORKPURPOSE'] as $i=>$wp)
        { ?>
		<?=$wp['title']?><br /> <?
        }
    }
?>
</p>

<p><strong>5. Требования к выполнению работ (оказанию услуг)</strong></p>
<?
    if (!empty($_TPL['WORKREQUIREMENT']))
    {	
		$n=1;
        foreach($_TPL['WORKREQUIREMENT'] as $i=>$wr)
        { ?>
		5.<?=$n?> <?=$wr['work_requirement_title']?><br /> <?
        $n++;
		}
    }
?>

<p><strong>6. График выполнения работ (оказания услуг)</strong></p>

<table class="table">
  <tr>
    <td>№ п/п</td>
    <td>Наименование    основных видов работ</td>
    <td>Сроки выполнения</td>
    <td>Отчетная    документация</td>
  </tr>
<?	$nn=1;
foreach ($TPL['STEPSDATA'] as $step_data)
{
    $year_arr = split("-",$step_data['year']);
    $year = $year_arr[0];
?>
  <tr>
    <td><?=$nn?></td>
    <td>
	<?
        if (isset($step_data['works']))
        {
            foreach ($step_data['works'] as $work_data)
            { ?>
                <?=$work_data['title']?><br />
			<? }
        }
	?>
	</td>
	<? /*start_month Месяц начала работ 	finish_month*/?>
    <td><?=MonthsName($step_data['start_month'])?> <?=$year?> г. - <?=MonthsName($step_data['finish_month'])?> <?=$year?> г.</td>
    <td><?=$step_data['report_documentation_composition']?></td>
  </tr>
<? $nn++;
} ?>
</table>

<p><strong>7. Условия выполнения работ (оказания услуг)</strong></p>
<?=$TPL['LISTFIELDS']['conditions']?>

<p><strong>8. Требования к качеству, безопасности выполнения работ (оказания услуг)</strong></p>
<p>8.1. Исполнитель должен обеспечить:</p>

<? 
/*if (!empty($_TPL['WORKCONDITION']))
    {
		$nnn=1;
        foreach($_TPL['WORKCONDITION'] as $i=>$wc)
        { ?>
		8.1.<?=$nnn?> <?=$wc['work_condition_title']?><br />
		<?
        $nnn++;
		}
    }*/
if (!empty($_TPL['SAFETYREQUIREMENT']))
    {
		$nnn=1;
        foreach($_TPL['SAFETYREQUIREMENT'] as $i=>$sr)
        { ?>
		8.1.<?=$nnn?> <?=$sr['safety_requirements_title']?><br />
		<?
        $nnn++;
		}
    }

?>

<p><strong>9. Порядок сдачи и приемки результатов работ (услуг)</strong></p>
<p>9.1. Работа выполняется и сдается в <?=count($TPL['STEPSDATA'])?> этапа(ов) согласно Графику выполнения работ.<br />
<?=$TPL['LISTFIELDS']['order_delivery']?></p>
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