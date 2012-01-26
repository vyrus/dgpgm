<?

define ('PHOTO_CATALOG_ID', 2);
define ('TPL_ADMIN_PHOTO', $_SERVER['DOCUMENT_ROOT']."/tpl/admin/photo/");
define ('TPL_PHOTO', $_SERVER['DOCUMENT_ROOT']."/tpl/cms/photo/");
define("DIR_GALLERY", $_SERVER['DOCUMENT_ROOT'].'/files/gallery/');
define("FK_PHOTO", 'cms_photo');
define("FK_PHOTO_CATEGORY", 'cms_photo_category');
define("FK_PHOTO_VOTING", 'cms_photo_voting');



$_MODCONFIG['photo']=array(
                "size_small"=>"120x120",
                "size_middle"=>"640x640",
		'name'=>'Фотогалерея',
		'pathAdmin'=>$_SERVER['DOCUMENT_ROOT'].'/inc/app/photo/ManagerPhotoAdmin.php',
		'run'=>'
		 $mod= new ManagerPhotoAdmin($db, $user);
		 $mod->work();
		',
		'sub'=>'<ul>
			<li><a href="?mod=photo">Список категорий</a></li>	
			<li><a href="?mod=photo&action=addcategory">Добавить категорию</a></li>
                       '.(!empty($_GET['id'])?'<li><a href="?mod=photo&action=addphoto&parent_id='.intval($_GET['id']).'">Добавить одно фото</a></li>':'').
					   (!empty($_GET['id'])?'<li><a href="?mod=photo&action=addphotomuch&parent_id='.intval($_GET['id']).'">Добавить несколько фото</a></li>':'').
			'</ul>'
			);

include "ManagerPhoto.php";


?>