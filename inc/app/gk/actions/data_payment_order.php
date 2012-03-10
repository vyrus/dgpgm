<?
/*	if (USER_GROUP == 5) {*/


	if ($_POST) {
		// Попытка установить соединение с MySQL:
		//mysql_select_db("dgpgm_kamenev_temp");

        $q = mysql_query("SELECT MAX(id) FROM payment_order");
        $row['id'] = mysql_result($q, 0, 0)+1;
        $row['number'] = $_POST['number'];
        $row['type'] = $_POST['type'];
        $date = explode(".", $_POST['date']);
        $row['date'] = $date[2]."-".$date[1]."-".$date[0];
        $row['sum'] = $_POST['sum'];
        $row['status'] = $_POST['status'];
        $row['stepGK_id'] = $_POST['stepGK_id'];

        if (is_int($this->db->addrow(FK_PAYMENT_ORDER, $row))) {
        	echo "Record added successfully!";
        } else {
        	echo "Error writing data!";
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