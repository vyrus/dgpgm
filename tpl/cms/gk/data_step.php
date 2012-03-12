<?
    include TPL_CMS."_header.php";
?>

	<h1>Данные этапа</h1>

<?
    if (isset($res)) {echo '<p style="color: red;">'.$res.'</p>';}
    //echo "_GET['step_id']=".$_GET['step_id']."<br />";
    //echo "_GET['GK_id']=".$_GET['GK_id']."<br />";

    if (isset($_GET['step_id']) && isset($_GET['GK_id'])) {
    	$id = $_GET['step_id'];
    	if ($id == -1) {
    		$sql = "SELECT MAX(id) FROM stepGK";
    		$r = mysql_fetch_assoc($this->db->query($sql));
    		$id = $r['MAX(id)']+1;
    		//$sql = "SELECT DISTINCT id FROM stepGK WHERE GK_id=".$_GET['GK_id'];
    		//$r = mysql_fetch_assoc($this->db->query($sql));
    		//$stepGK_id = $r['id'];
    		$row = array("id"=>$id, "number"=>1, "start_date"=>"0000-00-00", "finish_date"=>"0000-00-00",
    			"presentation_date"=>"0000-00-00", "review_date"=>"0000-00-00", "prepayment_date"=>"0000-00-00",
                "act_financing_date"=>"0000-00-00", "integration_date"=>"0000-00-00", "plan_price"=>0,
                "price"=>0, "prepayment_percent"=>0, "prepayment"=>0, "act_number"=>null, "act_reg_date"=>"0000-00-00",
                "act_file_link"=>null, "GK_id"=>$_GET['GK_id'], "financing_act"=>0);
            $this->db->addrow(FK_STEPGK, $row);
    		//$sql = "INSERT INTO stepGK (id, number, start_date, finish_date, presentation_date, review_date,
    		//	prepayment_date, act_financing_date, integration_date, plan_price, price, prepayment_percent,
    		//	prepayment, act_number, act_reg_date, act_file_link, GK_id, financing_act)
    		//	VALUES (".$id.", 1, '0000-00-00', '0000-00-00', '0000-00-00', '0000-00-00',
    		//	'0000-00-00', '0000-00-00', '0000-00-00', 0, 0, 0,
    		//	0, null, '0000-00-00', null, ".$_GET['GK_id'].", 0)";
    		//$q = $this->db->query($sql);
    	}
    	$sql = "SELECT DISTINCT * FROM ".FK_STEPGK." WHERE id=".$id;//получить из формы редактирования ГК
        $rowDB = mysql_fetch_assoc($this->db->query($sql));
        //продолжение следует...
?>

	<form method="post" action="<?=$id?>">

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
    	<td><input type="text" value="<?=$rowDB['number']?>"
    		name="number" maxlength=11 size=11 style="width:110px;"></td>
    </tr>
    <tr>
    	<td>Дата начала этапа</td>
    	<td><input type="text" value="<? $date = explode('-', $rowDB['start_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="start_date" name="start_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Дата сдачи отчета план</td>
    	<td><input type="text" value="<? $date = explode('-', $rowDB['presentation_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="presentation_date" name="presentation_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Дата сдачи работ план</td>
    	<td><input type="text" value="<? $date = explode('-', $rowDB['finish_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="finish_date" name="finish_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Дата рассмотрения отчета план</td>
    	<td><input type="text" value="<? $date = explode('-', $rowDB['review_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="review_date" name="review_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Дата финансирования аванса план</td>
    	<td><input type="text" value="<? $date = explode('-', $rowDB['prepayment_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="prepayment_date" name="prepayment_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Дата финансирования акта план</td>
    	<td><input type="text" value="<? $date = explode('-', $rowDB['act_financing_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="act_financing_date" name="act_financing_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Плановая сумма</td>
    	<td><input type="text" value="<?=$rowDB['plan_price']?>"
    		name="plan_price" maxlength=12 size=12 style="width:120px;"> руб.</td>
    </tr>
    <tr>
    	<td>Сумма этапа по ГК</td>
    	<td><input type="text" value="<?=$rowDB['price']?>"
    		name="price" maxlength=12 size=12 style="width:120px;"> руб.</td>
    </tr>
    <tr>
    	<td>Аванс</td>
    	<td><input type="text" value="<?=$rowDB['prepayment_percent']?>"
    		name="prepayment_percent" maxlength=12 size=12 style="width:120px;"> %</td>
    </tr>
    <tr>
    	<td>Дата внедрения план</td>
    	<td><input type="text" value="<? $date = explode('-', $rowDB['integration_date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="integration_date" name="integration_date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    </table>

    <br />
    <center>
    <input type="submit" name="s_data_step" value="Сохранить данные этапа" ><br /><br />

<?
    $sql = "SELECT DISTINCT number FROM ".FK_GK." WHERE id=".$_GET['GK_id'];//получить из формы редактирования ГК
    $q = $this->db->query($sql);
    $rowDB = mysql_fetch_assoc($q);
    echo '<a href="/gk/gk/'.$_GET['GK_id'].'">Вернуться к редактированию Госконтракта №'.$rowDB['number'].'</a>'
?>

    </center>

    </form>

<?
    //...продолжение
    } else {echo '<p style="color: red;">Необходимо входить через страницу для заполнения ГК!</p>';}
?>

<?
    include TPL_CMS."_footer.php";
?>
