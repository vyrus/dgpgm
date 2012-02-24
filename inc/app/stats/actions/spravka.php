<?

//	if (USER_GROUP == 5) {
	if (true) {		
		if ($_POST) {
			//
		} else {
			include TPL_CMS_STATS."spravka.php";
		}
	} else {
		include TPL_CMS_STATS."no-rights.php";
	}
	

?>