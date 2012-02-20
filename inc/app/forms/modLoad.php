<?

define ('TPL_CMS_FORMS', 'tpl/cms/forms/');
define ('TPL_ADMIN_FORMS', "tpl/admin/forms/");

define ('FK_SUBPROGRAM', 'subprogram');
define ('FK_NOTICE', 'notice');
define ('FK_MEASURE_HAS_NOTICE', 'measure_has_notice');
define ('FK_WORK_STEP', 'work_step');
define ('FK_WORK_PURPOSE', 'work_purpose');
define ('FK_WORK_REQUIREMENT', 'work_requirement');
define ('FK_WORK_CONDITION', 'work_condition');
define ('FK_SAFETY_REQUIREMENTS', 'safety_requirements');
define ('FK_WORK', 'work');
define ('FK_REPORT', 'report');
define ('FK_PRIME_COST', 'prime_cost');
define ('FK_PLACE_TYPE', 'place_type');
define ('FK_PLACE_DISTRICT', 'place_district');
define ('FK_PLACE_OKRUG', 'place_okrug');
define ('FK_DEPARTAMENT', 'department');
define ('FK_COMMENT', 'comment');
define ('FK_FORM', 'form');
define ('FK_WAGE', 'wage_index');
define ('FK_COST', 'constants_cost');
define ('FK_PERFORMER', 'bid_performer');
define ('FK_FIELDS_TZ', 'common_fields_tz');

define ('PRICE_PDF', $_SERVER['DOCUMENT_ROOT'].'/files/price/');
define ('DIR_FORM_PDF', $_SERVER['DOCUMENT_ROOT'].'/files/print-form/');



$_MODCONFIG['forms']=array(
		'name'=>'Заявки',
		'pathAdmin'=>$_SERVER['DOCUMENT_ROOT'].'/inc/app/forms/ManagerFormsAdmin.php',
		'run'=>'
		 $mod= new ManagerFormsAdmin($db, $user);
		 $mod->work();
		',
		'sub'=>'<ul>
			<li><a href="?mod=forms&action=regpaper">Регистрация заявок в бумажном виде</a></li>
			<li><a href="?mod=forms&action=evaluation">Оценка заявок</a></li>
			<li><a href="?mod=forms&action=spforoper1">Формирование тематики</a></li>            
			</ul>'
);

include "ManagerForms.php";
?>