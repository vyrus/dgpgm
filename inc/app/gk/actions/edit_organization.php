<?
/*	if (USER_GROUP == 5) {*/
	$_TPL['ROW']=ManagerForms::viewOrganization($_GET['id']);

		if ($_POST) {
			$tmp=ManagerForms::prepareInfoorgData($_POST);
			$_TPL['ERROR'] = $tmp['error'];
			$_TPL['ROW'] = $tmp['data'];

				if (!count($tmp['error'])){
					$sql=sql_placeholder('update ?#FK_APP_ORG set ?% where id=? ', $_TPL['ROW'], $_GET['id']);
					$this->db->query($sql);
					$_TPL['ERROR'][] = 'Данные сохранены. <a href="'.$_COOKIE["refer"].'">Вернуться</a>';
					
				}
		
		}
		
		$_TPL['BIDMENU']="";
		
		include TPL_CMS_GK."edit_organization.php";

	/*} else {
    		include TPL_CMS_STATS."no-rights.php";
    }
    */
?>