<?
/*	if (USER_GROUP == 5) {*/
	

	if ($_POST) {
				
		if (preg_match("#^(\d{10})$#is", $_POST['search'])) {
			$TPL['ORG'] = ManagerForms::listOrgInn($_POST['search']);
		} else {
			$TPL['ORG'] = self::listOrgName($_POST['search']);
		}
		include TPL_CMS_GK."search_organization_result.php";
	} else {
	
		include TPL_CMS_GK."search_organization.php";
	}
	/*} else {
    		include TPL_CMS_STATS."no-rights.php";
    }
    */
?>