<?
class ManagerBlockAdmin {
   
   function init(){
	global $_TPL;
	 $_TPL['RIGHTBLOCK']=file_get_contents(TPL_CMS_BLOCK.'rightblock.tpl');
     $_TPL['CONTACTS']=file_get_contents(TPL_CMS_BLOCK.'contact.tpl');
     $_TPL['LEFTBOTTOM']=file_get_contents(TPL_CMS_BLOCK.'leftbottomblock.tpl');
     $_TPL['RIGHTBOTTOM']=file_get_contents(TPL_CMS_BLOCK.'rightbottomblock.tpl');
   }
   
   function save($name){
      $f=fopen(TPL_CMS_BLOCK. $name.'.tpl', 'w');
      fwrite($f, stripslashes($_POST[$name]));
      fclose($f);
   }


   function work(){
     global $_MODCONFIG, $_TPL;
     $action=$_GET['action'];
	 $this->init();
     switch(1){
	   	 case(!empty($_POST['contacts'])):
			$this->save('contacts');
      	  	$_TPL['ERROR'][]='Нижний блок сохранен';
	   	 break;
	   	 case(!empty($_POST['rightblock'])):
	   	    $this->save('rightblock');
			$_TPL['ERROR'][]='Правый блок сохранен';
	   	 break;
 	   	 case(!empty($_POST['leftbottomblock'])):
	   	    $this->save('leftbottomblock');
         
			$_TPL['ERROR'][]='Левнй нижний блок сохранен';
	   	 break;
	   	 case(!empty($_POST['rightbottomblock'])):
	   	    $this->save('rightbottomblock');
			$_TPL['ERROR'][]='Правый нижний блок сохранен';
	   	 break;
     }

     include TPL_ADMIN_BLOCK."form_other.php";


   }

}

?>