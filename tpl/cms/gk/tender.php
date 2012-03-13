<?
	$_TPL['TITLE'] [] = 'Государственные контракты';    
	include TPL_CMS."_header.php";
?>
<script>
	//Load the Tooltip & Dialog widget class
	dojo.require("dijit.Tooltip");
	dojo.require("dijit.Dialog");

	dojo.ready(function()
	{
		/*tooltips*/
        measure_title = dojo.query("#measure_title");
        var tip = new dijit.Tooltip({
        	label: '<div class="myTipType">Мероприятие не может быть изменено после объявления конкурса</div>',
            showDelay: 250,
            connectId: measure_title
        });
        work_kind_title = dojo.query("#work_kind_title");
        var tip = new dijit.Tooltip({
        	label: '<div class="myTipType">Направление расходов не может быть изменено после объявления конкурса</div>',
            showDelay: 250,
            connectId: work_kind_title
        });
        
        dijit.Tooltip.defaultPosition = ["above", "below", "before", "after"];        
		/*eo tooltips*/

		/*calendar*/
        /*eo calendar*/
		})
</script>

<h1>Данные конкурса</h1>

<form method="post">
<table>
	<tr>
		<td style="width: 300px;">Мероприятие</td> 
		<td> 
			<? if ($TPL['late']) {echo '<input type="hidden" name="measure_id" value='.$TPL['tender_data']['measure_id'].'><span id="measure_title">'.
									$TPL['tender_data']['measure_id']." ".$TPL['tender_data']['mtitle']."</span>";} else {?>
			<select name="measure_id" style="width:630px"> <?
				foreach ($TPL['measures'] as $m) {?> <option<?=$m['id'] == $TPL['tender_data']['measure_id'] ? ' selected' : ''?> value="<?=$m['id']?>"> <?=$m['id']." ".$m['title']?> </option> <? } ?>
			</select> <?
			}?> <br>
		</td>
	</tr>
	<tr>
		<td>Направление расходов</td>
		<td>		
			<? if ($TPL['late']) {echo '<input type="hidden" name="work_kind_id" value='.$TPL['tender_data']['work_kind_id'].'><span id="work_kind_title">'.
									$TPL['tender_data']['wktitle']."</span>";} else {?>
			<select name="work_kind_id"> <?
				foreach ($TPL['w_kinds'] as $wk) {?> <option<?=$wk['id'] == $TPL['tender_data']['work_kind_id'] ? ' selected' : ''?> value="<?=$wk['id']?>"> <?=$wk['title']?> </option> <? } ?>
			</select> <?
			}?> <br>
		</td>
	</tr>
	<tr>
		<td>Тип конкурса</td>
		<td>
			<select name="tender_kind_id"> <?
				foreach ($TPL['kinds'] as $tk) {?> <option<?=$tk['id'] == $TPL['tender_data']['tender_kind_id'] ? ' selected' : ''?> value="<?=$tk['id']?>"> <?=$tk['title']?> </option> <? } ?>
			</select> <br>
		</td>
	</tr>
	<tr>
		<td>№ извещения</td>
		<td>		
			<input name="notice_num" value="<?=$TPL['tender_data']['notice_num']?>"><br>
		</td>
	</tr>
	<tr>
		<td>Дата извещения</td>
		<td>		
			<input type="hidden" name="notice_date" value="<?=$TPL['tender_data']['notice_date']?>"><?=$TPL['tender_data']['notice_date']?><br>
		</td>
	</tr>
	<tr>
		<td>Название конкурса</td>
		<td>		
			<input name="title" value="<?=$TPL['tender_data']['ttitle']?>"><br>
		</td>
	</tr>
	<tr>
		<td>Дата вскрытия конвертов</td>
		<td>		
			<input type="hidden" name="envelope_opening_date" value="<?=$TPL['tender_data']['envelope_opening_date']?>"><?=$TPL['tender_data']['envelope_opening_date']?><br>
		</td>
	</tr>
	<tr>
		<td>Дата рассмотрения заявок</td>
		<td>		
			<input type="hidden" name="review_bid_date" value="<?=$TPL['tender_data']['review_bid_date']?>"><?=$TPL['tender_data']['review_bid_date']?><br>
		</td>
	</tr>
	<tr>
		<td>Дата оценки и сопоставления</td>
		<td>		
			<input type="hidden" name="estimation_date" value="<?=$TPL['tender_data']['estimation_date']?>"><?=$TPL['tender_data']['estimation_date']?><br>
		</td>
	</tr>
	<tr>
		<td>№ протокола</td>
		<td>		
			<input name="protocol_number" value="<?=$TPL['tender_data']['protocol_number']?>"><br>
		</td>
	</tr>
	<tr>
		<td>Дата протокола</td>
		<td>		
			<input type="hidden" name="protocol_date" value="<?=$TPL['tender_data']['protocol_date']?>"><?=$TPL['tender_data']['protocol_date']?><br>
		</td>
	</tr>
</table>

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

