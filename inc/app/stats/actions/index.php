<?
	if (USER_GROUP == 5) {
		include TPL_CMS_STATS."index.php";
	} else {
		include TPL_CMS_STATS."no-rights.php";
	}
?>