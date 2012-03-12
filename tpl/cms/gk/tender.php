<?
	$_TPL['TITLE'] [] = 'Государственные контракты';    
	include TPL_CMS."_header.php";
?>

	<h1>Данные конкурса</h1>

<form method="post">
Мероприятие 
<? if ($TPL['late']) {echo '<input type="hidden" name="measure_id" value='.$TPL['tender_data']['measure_id'].'>'.
							$TPL['tender_data']['measure_id']." ".$TPL['tender_data']['mtitle'];} else {?>
	<select name="measure_id" style="width:1030px"> <?
		foreach ($TPL['measures'] as $m) {?> <option<?=$m['id'] == $TPL['tender_data']['measure_id'] ? ' selected' : ''?> value="<?=$m['id']?>"> <?=$m['id']." ".$m['title']?> </option> <? } ?>
	</select> <?
	}?> <br>

Направление расходов
<? if ($TPL['late']) {echo '<input type="hidden" name="work_kind_id" value='.$TPL['tender_data']['work_kind_id'].'>'.
							$TPL['tender_data']['wktitle'];} else {?>
	<select name="work_kind_id"> <?
		foreach ($TPL['w_kinds'] as $wk) {?> <option<?=$wk['id'] == $TPL['tender_data']['work_kind_id'] ? ' selected' : ''?> value="<?=$wk['id']?>"> <?=$wk['title']?> </option> <? } ?>
	</select> <?
	}?> <br>

Тип конкурса
<?=$TPL['tender_data']['tktitle']?><br>

№ извещения
<input name="notice_num" value="<?=$TPL['tender_data']['notice_num']?>"><br>

Дата извещения
<input type="hidden" name="notice_date" value="<?=$TPL['tender_data']['notice_date']?>"><?=$TPL['tender_data']['notice_date']?><br>

Название конкурса
<input name="title" value="<?=$TPL['tender_data']['ttitle']?>"><br>

Дата вскрытия конвертов
<input type="hidden" name="envelope_opening_date" value="<?=$TPL['tender_data']['envelope_opening_date']?>"><?=$TPL['tender_data']['envelope_opening_date']?><br>

Дата рассмотрения заявок
<input type="hidden" name="review_bid_date" value="<?=$TPL['tender_data']['review_bid_date']?>"><?=$TPL['tender_data']['review_bid_date']?><br>

Дата оценки и сопоставления
<input type="hidden" name="estimation_date" value="<?=$TPL['tender_data']['estimation_date']?>"><?=$TPL['tender_data']['estimation_date']?><br>

№ протокола
<input name="protocol_number" value="<?=$TPL['tender_data']['protocol_number']?>"><br>

Дата протокола
<input type="hidden" name="protocol_date" value="<?=$TPL['tender_data']['protocol_date']?>"><?=$TPL['tender_data']['protocol_date']?><br>

<style>
	#lot-steps {
		border: 1px solid #000;
		border-collapse: collapse;
		font-size: 11px;
		
	}
	
	#lot-steps td {
		border: 1px solid #000;
		padding: 5px;
	}

	#lot-steps th {
		border: 1px solid #000;
		padding: 10px;
	}
	
</style>

<script>
/*calendar*/
/*
$(document).ready(function () {
	$('input[name="handing_over_date"]').attachDatepicker({
		rangeSelect: false,
		yearRange: '2000:2050',
		firstDay: 1
	});
})*/
</script>

<h3>Этапы</h3>
<table id="lot-steps">
	<tr>
		<th>№ п/п</th>
		<th>Год</th>
		<th>Цена план, руб.</th>
		<th>Действия</th>
	</tr>
	
<? foreach ($TPL['step_data'] as $s) { ?>
	<tr>
		<td><input type="hidden" name="step_number[]" value="<?=$s['step_number']?>"><?=$s['step_number']?></td>
		<td><input type="hidden" name="year[]" value="<?=$s['year']?>"><?=$s['year']?></td>
		<td><input type="hidden" name="price[]" value="<?=$s['price']?>"><?=$s['price']?></td>
		<td><a href="#" onclick="dojo.destroy(this.parentNode.parentNode);">Удалить</a></td>
	</tr>
<? } ?>
	<tr><td colspan="4" style="text-align: right"><a href="#" onclick="javascript: alert('New step');">Добавить этап</a></td>
	</tr>
</table>


<input type="button" value="Вернуться" onclick="location.href='/gk/gk/<?=$TPL['gk_id']?>'">
<input type="submit" name="send" value="Сохранить данные конкурса">
</form>
<?
    include TPL_CMS."_footer.php";
?>

