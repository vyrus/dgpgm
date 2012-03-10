<?
/*	if (USER_GROUP == 5) {*/
	setcookie("refer", $_SERVER['REQUEST_URI']); 
	$TPL['NOTICE'] = self::listNoticeNum();
	$TPL['GK'] = self::viewGk($id);
	if ($_POST) {
		$_TPL['ROW']=$_POST;
		$id = self::addrow(FK_BIDGK, $_TPL['ROW']);
		if ($id) {
			$_TPL['ERROR'][] = "Данные сохранены";
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