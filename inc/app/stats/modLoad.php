<?

define ('TPL_CMS_STATS', $_SERVER['DOCUMENT_ROOT'].'/tpl/cms/stats/');
define ('ACTIONS_STATS', $_SERVER['DOCUMENT_ROOT'].'/inc/app/stats/actions/');

define ('FK_ADDITIONAL_AGREEMENT', 'additional_agreement');
define ('FK_BIDGK', 'bidGK');
define ('FK_GK', 'GK');
define ('FK_LOT', 'lot');
define ('FK_LOT_PRICE', 'lot_price');
define ('FK_MEASURE_PLAN', 'measure_plan');
define ('FK_PAYMENT_ORDER', 'payment_order');
define ('FK_STATUS', 'status');
define ('FK_STEPGK', 'stepGK');
define ('FK_STEP_NOTE', 'step_note');
define ('FK_TENDER', 'tender');
define ('FK_TENDER_SUM_PLAN', 'tender_sum_plan');
define ('FK_WORK_KIND', 'work_kind');


$_MODCONFIG['stats']=array();

include "ManagerStats.php";

?>