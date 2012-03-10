<?
    include TPL_CMS."_header.php";
?>

	<h1>Данные платежного поручения</h1>

	<form method="post">

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
    	<td><input type="text" value="185" name="stepGK_id" maxlength=11 size=11 style="width:110px;"></td>
    </tr>
    <tr>
    	<td>Номер платежного поручения</td>
    	<td><input type="text" value="1" name="number" maxlength=45 size=45 style="width:110px;"></td>
    </tr>
    <tr>
    	<td>Дата</td>
    	<td><input type="text" value="<?=date("d.m.Y")?>" id="date" name="date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Сумма</td>
    	<td><input type="text" value="0" name="sum" maxlength=12 size=12 style="width:120px;"> руб.</td>
    </tr>
    <tr>
    	<td>Тип</td>
    	<td>
    		<input type="radio" checked="checked" value="1" name="type"> Аванс
			<input type="radio" value="2" name="type"> Акт
    	</td>
    </tr>
    <tr>
    	<td>Статус</td>
    	<td>
    		<input type="radio" checked="checked" value="1" name="status"> Действует
			<input type="radio" value="2" name="status"> Отменено
    	</td>
    </tr>
    </table>
    <br />
    <center>
    <input type="submit" name="s_data_payment_order" value="Сохранить данные поручения" >
    </center>

	</form>

<?
    include TPL_CMS."_footer.php";
?>
