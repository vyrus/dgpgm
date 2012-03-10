<?
	if (USER_GROUP == 5) {
		include TPL_CMS_GK."index.php";
	} else {
		include TPL_CMS_GK."no-rights.php";
	}
?>