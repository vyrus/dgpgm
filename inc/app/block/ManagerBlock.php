<?
class ManagerBlock{
 function init(){
  global $_TPL;
        $_TPL['RIGHTBLOCK']=file_get_contents(TPL_CMS_BLOCK.'rightblock.tpl');
		$_TPL['CONTACTS']=file_get_contents(TPL_CMS_BLOCK.'contact.tpl');
		$_TPL['LEFTBOTTOM']=file_get_contents(TPL_CMS_BLOCK.'leftbottomblock.tpl');
		$_TPL['RIGHTBOTTOM']=file_get_contents(TPL_CMS_BLOCK.'rightbottomblock.tpl');
}
}
?>