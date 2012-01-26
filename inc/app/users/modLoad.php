<?

define ('USERS_GROUP_ID', '2');
define ('TPL_CMS_USER', 'tpl/cms/users/');
define ('TPL_ADMIN_USERS', "tpl/admin/users/");
define ('USERS_LP', "files/users/");

define ('FK_SID', 'session_test');


$_MODCONFIG['users']=array(
		'name'=>'Пользователи',
		'pathAdmin'=>'inc/app/users/ManagerUserAdmin.php',
		'run'=>'
		 $mod= new ManagerUserAdmin($db, $user);
		 $mod->work();
		',
		'sub'=>'<ul>
			<li><a href="?mod=users&action=listusers" title="Список пользователей">Пользователи</a></li>
			<li><a href="?mod=users&action=listgroup" title="Группы">Группы пользователей</a></li>
			<li><a href="?mod=users&action=adduser" title="Добавить пользователя">Добавить пользователя</a></li>
			</ul>'
);
include "ManagerUser.php";
?>