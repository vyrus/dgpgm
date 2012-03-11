<?
/*	if (USER_GROUP == 5) {*/
/*	if (isset($_GET['id_org'])) {
		$_TPL['ROW']['id_org_ind'] = $_GET['id_org'];
	}*/
	
	
	$TPL['NOTICE'] = self::listNoticeNum();
	$TPL['GK'] = self::viewGk($id);
	if (!empty($TPL['GK']['bidGK_id'])) {
		$_TPL['ROW']=self::viewbidGK($TPL['GK']['bidGK_id']);
	}
	if ($_POST) {
		$_TPL['ROW']=$_POST;
		$sql=sql_placeholder('update ?#FK_BIDGK set ?% where id=? ', $_TPL['ROW'], $TPL['GK']['bidGK_id']);
		if ($this->db->query($sql)) {
			$_TPL['ERROR'][] = "Данные изменены";
		}
		include TPL_CMS_GK."data_bid.php";
	} else {
		include TPL_CMS_GK."data_bid.php";
	}
	/*} else {
    		include TPL_CMS_STATS."no-rights.php";
    }
    */
?>