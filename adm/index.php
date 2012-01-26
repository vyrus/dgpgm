<?
  chdir('../');
  
  include $_SERVER['DOCUMENT_ROOT']."/inc/lib_include.php";
  
  $mod=(empty($_GET['mod']))?'':$_GET['mod'];

  $access=$user->GetListAccess(USER_ID, 0);

  if(!empty($_POST['login']) || !empty($_POST['password'])){
        $tmp=$user->login(SESSION_ID, $_POST);
       if (count($tmp['error'])){
          $_TPL['ERROR']=$tmp['error'];
          include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/form_login.php";
        }else{
         	header('Location: http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
       }
      	exit;
   }else if (empty($access['AdmLogin'])){
    include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/form_login.php";
    exit;
  }
  
   if (!empty($_MODCONFIG[$mod]['pathAdmin'])){
	   	include $_MODCONFIG[$mod]['pathAdmin'];
	   	eval($_MODCONFIG[$mod]['run']);

   }else{
   		include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/index.php";
   }
   $no_footer = array('updatepaper','regpaperfilter','addcomment','comment','editcomment','evaluationfilter','deletecomment','updateevaluation','mforoper1');
	if (!in_array($_GET['action'], $no_footer)){
		include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/footer.php";
	}
?>