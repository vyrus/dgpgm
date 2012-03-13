<?
/*	if (USER_GROUP == 5) {*/


	if ($_POST) {		// Попытка установить соединение с MySQL:
		//mysql_select_db("dgpgm_kamenev_temp");

        $row['id'] = $_GET['step_id'];
        $row['number'] = $_POST['number'];
        $date = explode(".", $_POST['start_date']);
        $row['start_date'] = $date[2]."-".$date[1]."-".$date[0];
        $date = explode(".", $_POST['finish_date']);
        $row['finish_date'] = $date[2]."-".$date[1]."-".$date[0];
        $date = explode(".", $_POST['presentation_date']);
        $row['presentation_date'] = $date[2]."-".$date[1]."-".$date[0];
        $date = explode(".", $_POST['review_date']);
        $row['review_date'] = $date[2]."-".$date[1]."-".$date[0];
        $date = explode(".", $_POST['prepayment_date']);
        $row['prepayment_date'] = $date[2]."-".$date[1]."-".$date[0];
        $date = explode(".", $_POST['act_financing_date']);
        $row['act_financing_date'] = $date[2]."-".$date[1]."-".$date[0];
        $date = explode(".", $_POST['integration_date']);
        $row['integration_date'] = $date[2]."-".$date[1]."-".$date[0];
        $row['plan_price'] = $_POST['plan_price'];
        $row['price'] = $_POST['price'];
        if ($_POST['prepayment_percent'] == "") {        	$row['prepayment_percent'] = "0";        } else {        	$row['prepayment_percent'] = $_POST['prepayment_percent'];        }
        $row['prepayment'] = $_POST['price']*$row['prepayment_percent']/100; //Сумма этапа по ГК*Аванс/100
        $row['act_number'] =  "null"; //???
        $row['act_reg_date'] =  "0000-00-00"; //???
        $row['act_file_link'] =  "null"; //???
        $row['GK_id'] = $_GET['GK_id'];
        $row['financing_act'] = $_POST['price']- $row['prepayment']; //Сумма этапа по ГК – Финансирование аванс
        $sql = sql_placeholder("UPDATE ".FK_STEPGK." SET number=".$row['number'].", start_date='".$row['start_date'].
        	"', finish_date='".$row['finish_date']."', presentation_date='".$row['presentation_date'].
            "', review_date='".$row['review_date']."', prepayment_date='".$row['prepayment_date'].
            "', act_financing_date='".$row['act_financing_date']."', integration_date='".$row['integration_date'].
            "', plan_price=".$row['plan_price'].", price=".$row['price'].
            ", prepayment_percent=".$row['prepayment_percent'].", prepayment=".$row['prepayment'].
            ", act_number=".$row['act_number'].", act_reg_date='".$row['act_reg_date'].
            "', act_file_link=".$row['act_file_link'].", GK_id=".$row['GK_id'].
            ", financing_act=".$row['financing_act']." WHERE id=".$row['id']);
        if ($this->db->query($sql)) {
        	$res = "Запись успешно добавлена!";//echo "Record added successfully!";
        } else {
        	$res = "Ошибка записи данных!";//echo "Error writing data!";
        }

		//mysql_select_db("dgpgm");//_kamenev_temp");
		include TPL_CMS_GK."data_step.php";
	} else {
		include TPL_CMS_GK."data_step.php";
	}
	/*} else {
    		include TPL_CMS_STATS."no-rights.php";
    }
    */
?>