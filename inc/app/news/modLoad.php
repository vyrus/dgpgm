<?

define ('NEWS_CATALOG_ID', 2);
define ('TPL_ADMIN_NEWS', $_SERVER['DOCUMENT_ROOT']."/tpl/admin/news/");
define ('TPL_CMS_NEWS', $_SERVER['DOCUMENT_ROOT']."/tpl/cms/news/");
define ('NEWS_PIC', $_SERVER['DOCUMENT_ROOT'].'/files/images/news/');

$_MODCONFIG['news']=array(
		'name'=>'Новости',
	      'photo_small'=>'80x80',
		'pathAdmin'=>$_SERVER['DOCUMENT_ROOT'].'/inc/app/news/ManagerNewsAdmin.php',
		'run'=>'
		 $mod= new ManagerNewsAdmin($db, $user);
		 $mod->work();
		',
		'sub'=>'<ul>
			<li><a href="?mod=news" title="Список новостей">Список новостей</a></li>
			<li><a href="?mod=news&action=addnews" title="Добавить новость">Добавить новость</a></li></ul>'
		);

include "ManagerNews.php";


?>