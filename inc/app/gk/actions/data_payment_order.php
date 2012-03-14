<?
/*	if (USER_GROUP == 5) {*/

    if (isset($_GET['payment_id']) && isset($_GET['GK_id'])) {
		if ($_POST) {			$row['id'] = $_GET['payment_id'];
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
        	if ($this->db->query($sql)) {        		$_TPL['ERROR'][] = "Запись успешно добавлена!";//echo "Record added successfully!";
        	} else {        		$_TPL['ERROR'][] = "Ошибка записи данных!";//echo "Error writing data!";
        	}
		}

		$TPL['id'] = $_GET['payment_id'];
    	//если добавляется новая платежка, то вставляем пустую строку с новым идентификатором и заполненным идентификатором ГК
    	if ($TPL['id'] == -1) {
    		$sql = sql_placeholder("SELECT MAX(id) FROM ".FK_PAYMENT_ORDER);
    		$r = mysql_fetch_assoc($this->db->query($sql));
    		$TPL['id'] = $r['MAX(id)']+1;
    		$sql = sql_placeholder("SELECT DISTINCT id FROM ".FK_STEPGK." WHERE GK_id=".$_GET['GK_id']);
    		$r = mysql_fetch_assoc($this->db->query($sql));
    		$stepGK_id = $r['id'];
    		$row = array("id"=>$TPL['id'], "number"=>"0", "type"=>1, "date"=>"0000-00-00", "sum"=>0, "status"=>1, "stepGK_id"=>$stepGK_id);
            $this->db->addrow(FK_PAYMENT_ORDER, $row);
    	}
    	// определяем данные для заполнения полей
    	$sql = sql_placeholder("SELECT DISTINCT * FROM ".FK_PAYMENT_ORDER." WHERE id=".$TPL['id']);
        $TPL['rowDB'] = mysql_fetch_assoc($this->db->query($sql));

        //определяем номера всех этапов по идентификатору ГК для формирования из них раскрывающегося списка
        $sql = sql_placeholder("SELECT DISTINCT id,number FROM ".FK_STEPGK." WHERE GK_id=".$_GET['GK_id']." ORDER BY number ASC");        $TPL['rowSelect'] = $this->db->_array_data($sql);//mysql_fetch_assoc($this->db->query($sql));

        // определяем номер ГК для ссылки на возврат
        $sql = sql_placeholder("SELECT DISTINCT number FROM ".FK_GK." WHERE id=".$_GET['GK_id']);
        $row = mysql_fetch_assoc($this->db->query($sql));
        $TPL['GKnumber'] = $row['number'];

        include TPL_CMS_GK."data_payment_order.php";
	} else {$_TPL['ERROR'][] = "Необходимо входить через страницу для заполнения ГК!";}
	/*} else {
    		include TPL_CMS_STATS."no-rights.php";
    }
    */
?>