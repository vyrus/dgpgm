<?

define ('ARTICLE_CATALOG_ID', 1);
define ('MAIN_PAGE_ID', 2); //главная страница

define ('TPL_ADMIN_ARTICLE', $_SERVER['DOCUMENT_ROOT']."/tpl/admin/article/");
define ('TPL_CMS_ARTICLE', $_SERVER['DOCUMENT_ROOT']."/tpl/cms/article/");

define ('ARTICLE_TOPMENU', 3); //Это навигация сайта

define ('FK_CONTENT', 'cms_article');

if (!empty($_GET['mod']) && $_GET['mod'] == "article") {$article_id = $_GET['id'];}
else {$article_id = "";}

$_MODCONFIG['article']=array(
		'name'=>'Контент',
		'pathAdmin'=>$_SERVER['DOCUMENT_ROOT'].'/inc/app/article/ManagerArticleAdmin.php',
		'run'=>'
		 $mod= new ManagerArticleAdmin($db, $user);
		 $mod->work();
		',
		'sub'=>'<ul>
			<li><a href="?mod=article" title="Структура сайта">Структура сайта</a></li>
			<li><a href="?mod=article&action=add&id='.$article_id.'" title="Создать раздел/страницу">Создать раздел/страницу</a></li></ul>'
		);

include "ManagerArticle.php";


?>