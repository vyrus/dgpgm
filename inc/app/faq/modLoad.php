<?

define ('TPL_ADMIN_FAQ', $_SERVER['DOCUMENT_ROOT']."/tpl/admin/faq/");
define ('TPL_CMS_FAQ', $_SERVER['DOCUMENT_ROOT']."/tpl/cms/faq/");
define ('FAQ_ZIP', 'files/faq/');

$_MODCONFIG['faq']=array(
		'name'=>'��������-��������',
		'pathAdmin'=>$_SERVER['DOCUMENT_ROOT'].'/inc/app/faq/ManagerFAQAdmin.php',
		'run'=>'
		 $mod= new ManagerFAQAdmin($db, $user);
		 $mod->work();
		',
		'sub'=>'<ul>
			<li><a href="?mod=faq">������ ���������</a></li>
			<li><a href="?mod=faq&action=cat">���������</a></li>
			<li><a href="?mod=faq&action=catadd">�������� ���������</a></li>
			</ul>'
		);

include "ManagerFAQ.php";


?>