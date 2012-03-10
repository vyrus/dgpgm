<?

header("Expires: Thu, 19 Feb 2006 13:24:18 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");

if (!empty($_POST['s'])){
    header('Location: /search_1_'.urlencode($_POST['s'])); exit; 
}

  include "inc/lib_include.php";

  $mod=(empty($_GET['mod']))?'article':$_GET['mod'];
  $site = new ManagerArticle($db);
  $news =  new ManagerNews($db); 
  $forms = new ManagerForms($db);
  
	if (!empty($_GET['id']) && MAIN_PAGE_ID==$_GET['id'] && $_GET['mod']=="article"){
		header('Location: /'); exit;
	}
	
	if ($_SERVER['REQUEST_URI']== '/'){
		header('Location: /forms/index');
	}

	$id = $_GET['id']=empty($_GET['id'])?MAIN_PAGE_ID  :$_GET['id'];


	$_TPL['LEFTMENU']=$site->bildMainMenu(ARTICLE_TOPMENU);

	$tmp = $news->listNews(CURRENT_PAGE, 3); 
	$_TPL['M_NEWS'] = $tmp['data'];

	$tmp = $site->getCatalogData($id); 
	$mod=(empty($_GET['mod']))?'':$_GET['mod'];

/*	if (USER_GROUP == 2) { // получаем список заявок
		$_TPL['MYBIDS'] = $forms->listUserBids(1, USER_ID);
	}*/

	$_TPL['TITLE']=array();

	switch($mod){
		case "news":
			$news->work();
		break;
		case "search":
			$site = new ManagerSearch($db);
			$site->work();
		break;
		case "error":
			$site = new ManagerError();
			$site->work();
		break;
		case "users":
			$site = new ManagerUser($db);
			$site->work();
		break;
		case "stats":
			$site = new ManagerStats($db);
			$site->work();
		break;
		case "gk":
			$site = new ManagerGk($db);
			$site->work();
		break;
		case "forms":
			$forms->work();
		break;
		default:
			$site->work();
		break;
  }

?>