<?
    include TPL_CMS."_header.php";
?>

	<h1>Данные этапа</h1>

	<form method="post" action="<?=$TPL['id']?>">

	<script>
	$(document).ready(function () {		$('#start_date').attachDatepicker({			rangeSelect: false,
			yearRange: '2010:2099',
			firstDay: 1
		});

		$('#presentation_date').attachDatepicker({			rangeSelect: false,
			yearRange: '2010:2099',
			firstDay: 1
		});
		$('#finish_date').attachDatepicker({			rangeSelect: false,
			yearRange: '2010:2099',
			firstDay: 1
		});

		$('#review_date').attachDatepicker({			rangeSelect: false,
			yearRange: '2010:2099',
			firstDay: 1
		});

		$('#prepayment_date').attachDatepicker({			rangeSelect: false,
			yearRange: '2010:2099',
			firstDay: 1
		});
		$('#act_financing_date').attachDatepicker({			rangeSelect: false,
			yearRange: '2010:2099',
			firstDay: 1
		});

		$('#integration_date').attachDatepicker({			rangeSelect: false,
			yearRange: '2010:2099',
			firstDay: 1
		});
	});
	</script>

    <table style="width: 100%;">
    <tr>
    	<td style="width: 25%;">Номер этапа</td>
    	<td><input type="text" value="<?=$TPL['rowDB']['number']?>"
    		name="number" maxlength=11 size=11 style="width:110px;"></td>
    </tr>
    <tr>
    	<td>Дата начала этапа</td>
    	<td><input type="text" value="<? $date = explode('-', $TPL['rowDB']['start_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="start_date" name="start_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Дата сдачи отчета план</td>
    	<td><input type="text" value="<? $date = explode('-', $TPL['rowDB']['presentation_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="presentation_date" name="presentation_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Дата сдачи работ план</td>
    	<td><input type="text" value="<? $date = explode('-', $TPL['rowDB']['finish_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="finish_date" name="finish_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Дата рассмотрения отчета план</td>
    	<td><input type="text" value="<? $date = explode('-', $TPL['rowDB']['review_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="review_date" name="review_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Дата финансирования аванса план</td>
    	<td><input type="text" value="<? $date = explode('-', $TPL['rowDB']['prepayment_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="prepayment_date" name="prepayment_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Дата финансирования акта план</td>
    	<td><input type="text" value="<? $date = explode('-', $TPL['rowDB']['act_financing_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="act_financing_date" name="act_financing_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Плановая сумма</td>
    	<td><input type="text" value="<?=$TPL['rowDB']['plan_price']?>"
    		name="plan_price" maxlength=12 size=12 style="width:120px;"> руб.</td>
    </tr>
    <tr>
    	<td>Сумма этапа по ГК</td>
    	<td><input type="text" value="<?=$TPL['rowDB']['price']?>"
    		name="price" maxlength=12 size=12 style="width:120px;"> руб.</td>
    </tr>
    <tr>
    	<td>Аванс</td>
    	<td><input type="text" value="<?=$TPL['rowDB']['prepayment_percent']?>"
    		name="prepayment_percent" maxlength=12 size=12 style="width:120px;"> %</td>
    </tr>
    <tr>
    	<td>Дата внедрения план</td>
    	<td><input type="text" value="<? $date = explode('-', $TPL['rowDB']['integration_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="integration_date" name="integration_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    </table>

    <br />
    <center>
    <input type="submit" name="s_data_step" value="Сохранить данные этапа" ><br /><br />


    <a href="/gk/gk/<?=$_GET['GK_id']?>">Вернуться к редактированию Госконтракта №<?=$TPL['GKnumber']?></a>

    </center>

    </form>

<?
    include TPL_CMS."_footer.php";
?>
