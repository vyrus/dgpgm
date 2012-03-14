<?
/*	if (USER_GROUP == 5) {*/


	if ($_POST) {
		// Попытка установить соединение с MySQL:
		//mysql_select_db("dgpgm_kamenev_temp");

        $row['id'] = $_GET['payment_id'];
        $row['number'] = $_POST['number'];
        $row['type'] = $_POST['type'];
        $date = explode(".", $_POST['date']);
        $row['date'] = $date[2]."-".$date[1]."-".$date[0];
        $row['sum'] = $_POST['sum'];
        $row['status'] = $_POST['status'];
        $sql = sql_placeholder("SELECT DISTINCT id FROM ".FK_STEPGK." WHERE GK_id='".$_GET['GK_id']."' AND number='".$_POST['stepGKnumber'].
        	"' ORDER BY id DESC");
        $q = $this->db->query($sql);
        $row['stepGK_id'] = mysql_result($q, 0, 0);
        $sql = sql_placeholder("UPDATE ".FK_PAYMENT_ORDER." SET number='".$row['number']."', type=".$row['type'].
        	", date='".$row['date']."', sum=".$row['sum'].", status=".$row['status'].", stepGK_id=".$row['stepGK_id'].
        	" WHERE id=".$row['id']);
        if ($this->db->query($sql)) {
        	$_TPL['ERROR'][] = "Запись успешно добавлена!";//echo "Record added successfully!";
        } else {
        	$_TPL['ERROR'][] = "Ошибка записи данных!";//echo "Error writing data!";
        }

		//mysql_select_db("dgpgm");//_kamenev_temp");
		include TPL_CMS_GK."data_payment_order.php";
	} else {
        include TPL_CMS_GK."data_payment_order.php";
	}
	/*} else {
    		include TPL_CMS_STATS."no-rights.php";
    }
    */
?>