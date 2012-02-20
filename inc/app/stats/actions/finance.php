<?

//	if (USER_GROUP == 5) {
	if (true) {		
		if ($_POST) {
			echo "������ ����";
		} else {
			$TPL['SUBPROGRAM'] = ManagerForms::listSubprogram();
			include TPL_CMS_STATS."finance.php";
		}
	} else {
		include TPL_CMS_STATS."no-rights.php";
	}
	

?>