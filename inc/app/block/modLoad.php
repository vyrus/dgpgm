<?


define ('TPL_ADMIN_BLOCK', $_SERVER['DOCUMENT_ROOT']."/tpl/admin/block/");
define ('TPL_CMS_BLOCK', $_SERVER['DOCUMENT_ROOT']."/files/block/");



$_MODCONFIG['block']=array(
		'name'=>'Блоки',
		'pathAdmin'=>$_SERVER['DOCUMENT_ROOT'].'/inc/app/block/ManagerBlockAdmin.php',
		'run'=>'
		 $mod= new ManagerBlockAdmin($user);
		 $mod->work();
		',
		);
		
include "ManagerBlock.php";

?>