<?
    include TPL_CMS."_header.php";
?>

	<h1>Данные платежного поручения</h1>

	<form method="post" action="<?=$TPL['id']?>">

	<script>
	$(document).ready(function () {
		$('#date').attachDatepicker({
			rangeSelect: false,
			yearRange: '2010:2099',
			firstDay: 1
		});
	});
	</script>

    <table style="width: 100%;">
    <tr>
    	<td style="width: 25%;">Номер этапа</td>
    	<td><select name="stepGKnumber">
    		<?
    			//формируем из номеров всех этапов раскрывающийся список
    			foreach ($TPL['rowSelect'] as $rowSelect) {    				$selected = '';
                    if ($TPL['rowDB']['stepGK_id'] == $rowSelect['id']) {$selected = 'SELECTED';}        			echo '<option value="'.$rowSelect['number'].'" '.$selected.'>'.$rowSelect['number'].'</option>';
    			}
    		?>
    	</select></td>
    </tr>
    <tr>
    	<td>Номер платежного поручения</td>
    	<td><input type="text" value="<?=$TPL['rowDB']['number']?>"
    		name="number" maxlength=45 size=45 style="width:110px;"></td>
    </tr>
    <tr>
    	<td>Дата</td>
    	<td><input type="text" value="<? $date = explode('-', $TPL['rowDB']['date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="date" name="date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Сумма</td>
    	<td><input type="text" value="<?=$TPL['rowDB']['sum']?>"
    		name="sum" maxlength=12 size=12 style="width:120px;"> руб.</td>
    </tr>
    <tr>
    	<td>Тип</td>
    	<td>
    		<input type="radio" <? if ($TPL['rowDB']['type'] == 'аванс') {echo 'checked="checked" ';}?> value="1" name="type"> Аванс
    		<input type="radio" <? if ($TPL['rowDB']['type'] == 'акт') {echo 'checked="checked" ';}?> value="2" name="type"> Акт
    	</td>
    </tr>
    <tr>
    	<td>Статус</td>
    	<td>
    	    <input type="radio" <? if ($TPL['rowDB']['status'] == 'действует') {echo 'checked="checked" ';}?> value="1" name="status"> Действует
    		<input type="radio" <? if ($TPL['rowDB']['status'] == 'отменено') {echo 'checked="checked" ';}?> value="2" name="status"> Отменено
    	</td>
    </tr>
    </table>

    <br />
    <center>
    <input type="submit" name="s_data_payment_order" value="Сохранить данные поручения" ><br /><br />

    <a href="/gk/gk/<?=$_GET['GK_id']?>">Вернуться к редактированию Госконтракта №<?=$TPL['GKnumber']?></a>

    </center>

	</form>

<?
    include TPL_CMS."_footer.php";
?>
