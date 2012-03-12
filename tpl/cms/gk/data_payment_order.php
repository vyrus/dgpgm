<?
    include TPL_CMS."_header.php";
?>

	<h1>Данные платежного поручения</h1>

<?
    if (isset($res)) {echo '<p style="color: red;">'.$res.'</p>';}
    //echo "_GET['payment_id']=".$_GET['payment_id']."<br />";
    //echo "_GET['GK_id']=".$_GET['GK_id']."<br />";
    if (isset($_GET['payment_id']) && isset($_GET['GK_id'])) {
    	$id = $_GET['payment_id'];    	if ($id == -1) {    		$sql = "SELECT MAX(id) FROM ".FK_PAYMENT_ORDER;
    		$r = mysql_fetch_assoc($this->db->query($sql));
    		$id = $r['MAX(id)']+1;
    		$sql = "SELECT DISTINCT id FROM ".FK_STEPGK." WHERE GK_id=".$_GET['GK_id'];
    		$r = mysql_fetch_assoc($this->db->query($sql));
    		$stepGK_id = $r['id'];
    		$row = array("id"=>$id, "number"=>"0", "type"=>1, "date"=>"0000-00-00", "sum"=>0, "status"=>1, "stepGK_id"=>$stepGK_id);
            $this->db->addrow(FK_PAYMENT_ORDER, $row);    		//$sql = "INSERT INTO payment_order(id, number, type, date, sum, status, stepGK_id)
    		//	VALUES (".$id.", '0', 1, '0000-00-00', 0, 1, ".$stepGK_id.")";
    		//$q = $this->db->query($sql);    	}    	$sql = "SELECT DISTINCT * FROM ".FK_PAYMENT_ORDER." WHERE id=".$id;//получить из формы редактирования ГК        $rowDB = mysql_fetch_assoc($this->db->query($sql));
        //продолжение следует...
?>

	<form method="post" action="<?=$id?>">

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
            	$sql = "SELECT DISTINCT id,number FROM ".FK_STEPGK." WHERE GK_id=".$_GET['GK_id']." ORDER BY number ASC";//получить из формы редактирования ГК
        		$q = $this->db->query($sql);
        		while($r=mysql_fetch_assoc($q)){        			$selected = '';
                    if ($rowDB['stepGK_id'] == $r['id']) {$selected = 'SELECTED';}        			echo '<option value="'.$r['number'].'" '.$selected.'>'.$r['number'].'</option>';
        		}
    		?>
    	</select></td>
    </tr>
    <tr>
    	<td>Номер платежного поручения</td>
    	<td><input type="text" value="<?=$rowDB['number']?>"
    		name="number" maxlength=45 size=45 style="width:110px;"></td>
    </tr>
    <tr>
    	<td>Дата</td>
    	<td><input type="text" value="<? $date = explode('-', $rowDB['date']); echo $date[2].'.'.$date[1].'.'.$date[0]; ?>"
    		id="date" name="date" maxlength=10 size=10 style="width:100px;"></td>
    </tr>
    <tr>
    	<td>Сумма</td>
    	<td><input type="text" value="<?=$rowDB['sum']?>"
    		name="sum" maxlength=12 size=12 style="width:120px;"> руб.</td>
    </tr>
    <tr>
    	<td>Тип</td>
    	<td>
    		<input type="radio" <? if ($rowDB['type'] == 'аванс') {echo 'checked="checked" ';}?> value="1" name="type"> Аванс
    		<input type="radio" <? if ($rowDB['type'] == 'акт') {echo 'checked="checked" ';}?> value="2" name="type"> Акт
    	</td>
    </tr>
    <tr>
    	<td>Статус</td>
    	<td>
    	    <input type="radio" <? if ($rowDB['status'] == 'действует') {echo 'checked="checked" ';}?> value="1" name="status"> Действует
    		<input type="radio" <? if ($rowDB['status'] == 'отменено') {echo 'checked="checked" ';}?> value="2" name="status"> Отменено
    	</td>
    </tr>
    </table>

    <!--//<input type="hidden" value="<?=$_POST['id']?>" name="id">
    <input type="hidden" value="<?=$_POST['GK_id']?>" name="GK_id">//-->

    <br />
    <center>
    <input type="submit" name="s_data_payment_order" value="Сохранить данные поручения" ><br /><br />

<?
    $sql = "SELECT DISTINCT number FROM ".FK_GK." WHERE id=".$_GET['GK_id'];//получить из формы редактирования ГК
    $q = $this->db->query($sql);
    $rowDB = mysql_fetch_assoc($q);
    echo '<a href="/gk/gk/'.$_GET['GK_id'].'">Вернуться к редактированию Госконтракта №'.$rowDB['number'].'</a>';
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
