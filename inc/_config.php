<?
  
  header('Content-type: text/html; charset=utf-8');
  
  ob_start();
  $time_start=array_sum(explode(' ', microtime()));

  $sql_host='localhost';
  $sql_user='root';
  $sql_password='MYdgpSQL';
  $sql_db='dgpscience'; 
  
  $db=& new MysqlDB();
  $db->_set_config($sql_host, $sql_user, $sql_password, $sql_db, 1);
  $db->db_connect();

	unset($sql_password);

	$GLOBALS['p'] = "/";
	
	define ('SITE_NAME', 'Управление научно-технической политики города Москвы');
	define ('SITE_ADRESS', 'г. Москва, Никитский переулок, д. 5');
	define ('SITE_TEL', '8 (445) 956-81-88');

	define ('TPL_CMS', 'tpl/cms/');
	define ('FK_SESSION', 'cms_session');
	define ('FK_USER', 'usr');
	define ('FK_ACCESS', 'cms_access');
	define ('FK_ACTION' , 'cms_action');
	define ('FK_G2U', 'cms_g2u');
	define ('FK_GROUP', 'cms_group');

    define ("FK_NEWS", 'cms_news');
    define ("FK_CATALOGID" , "cms_catalogid");
    define ("FK_TREE", "cms_tree");

	define ("FK_FAQ", 'cms_faq');
	define ("FK_FAQ_CAT", 'cms_faq_info');

	define ('FK_APP_IND', 'applicant_individual');
	define ('FK_APP_ORG', 'applicant_organization');	
	define ('FK_BID', 'bid');
	
	
   $user=& new ManagerUser($db);
   $user->SetField(array('domain'=>$_SERVER['SERVER_NAME']));

   define("SESSION_ID",   $user->BildSession());

   //define('USER_SID',  $user->getSid());
   //define('USER_SID_ID',  $user->getSid_id());

	if(isset($_POST['login']) && isset($_POST['passwd']) && isset($_POST['enter'])){
        $tmp=$user->login(SESSION_ID, $_POST);
		$_TPL['ERROR'] = $tmp['error'];
        if (!count($tmp['error'])){
            //header('Location: http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']); exit;
			$u_data=$user->GetUserData(SESSION_ID);
			header('Location: http://'.$_SERVER['SERVER_NAME'].'/forms/bid/'.$u_data['bid_id']); exit;
        }
	}

	$user_data=$user->GetUserData(SESSION_ID);
	define('USER_ID',  $user_data['id']);
	define('USER_LOGIN', $user_data['login']);
	define('USER_TYPE', $user_data['type-face']);
	define('USER_GROUP', $user_data['group_id']);
	$_TPL['USERDATA'] =&$user_data;	

	$form=& new ManagerForms($db);
	$TPL['INFO']=$form->viewBid(intval($_TPL['USERDATA']['bid_id']));
	
	if ($_GET['action'] == 'other-price') {
		$my_action = 'price';
	} else {
		$my_action = $_GET['action'];
	}
	$_TPL['BIDMENU']=$form->createBidMenu($TPL['INFO'], $_TPL['USERDATA'], $my_action);
	
   $action=$_GET['action']=(empty($_GET['action']))?'':$_GET['action'];
   $page=$_GET['page']=(empty($_GET['page']) || intval($_GET['page']<1))?1:intval($_GET['page']);
   $id=$_GET['id']=(empty($_GET['id']) ||  intval($_GET['id']<1))?0:intval($_GET['id']);
   define('CURRENT_PAGE', $page);

   if($action=='logout'){
        $user->logout();
 //             header('Location: http://'.$_SERVER['SERVER_NAME'].'/'.$_SERVER['REQUEST_URI']);
        header( 'Location: /');
        exit;
   }
     
	function fnSendMail($sTo,$sSubject,$sBody){    //Послать почту
    require_once('Mail.php');
    $sFrom='reg@dgpscience.ru';

    $aHeaders=array(
        'From'=>$sFrom,
        'To'=>$sTo,
        'Subject'=>'=?utf8?B?'.base64_encode($sSubject).'?=',
		'Mime-Version'=>'1.0',
        'Content-Type'=>'text/html; charset=utf8'
    );
	$smtp=Mail::factory(
        'smtp',
        array (
            'host'=>'ssl://smtp.yandex.ru',
            'port'=>'465',
            'auth'=>true,
            'username'=>'reg@dgpscience.ru',
            'password'=>'registr2012',
			//'localhost'=>'dgpscience.ru'
        )
    );

    $mail=$smtp->send($sTo,$aHeaders,$sBody);
	if (PEAR::isError($mail)) {
   echo("<p>" . $mail->getMessage() . "</p>");
  } else {
   echo("<p>Message successfully sent!</p>");
  }
    }
	
	function listMonth($name, $curr_month) {
		$month = array (1=>"январь",2=>"февраль",3=>"март",4=>"апрель",5=>"май",6=>"июнь",7=>"июль",8=>"август",9=>"сентябрь",10=>"октябрь",11=>"ноябрь",12=>"декабрь");
		$select = "<select name=\"".$name."\">\n";
			foreach ($month as $key => $val) {
				$select .= "\t<option value=\"".$key."\"";
					if ($key == $curr_month) {
						$select .= " selected>".$val."</option>\n";
					} else {
						$select .= ">".$val."</option>\n";
				}
			}
		$select .= "</select>";
		return $select;
	}
	
	function listYears($name, $curr_year) {
		$years = array();
			for($i=date("Y");$i<=2016;$i++){
				$years[] = $i;
			}
		$select = "<select name=\"".$name."\">\n";
			foreach ($years as $val) {
				$select .= "\t<option value=\"".$val."\"";
					if ($val == $curr_year) {
						$select .= " selected>".$val."</option>\n";
					} else {
						$select .= ">".$val."</option>\n";
				}
			}
		$select .= "</select>";
		return $select;
	}
	function listYears2() {
        $numargums = func_num_args();
        $elementName = func_get_arg(0);
        $startYear = func_get_arg(1);
        $finishYear = func_get_arg(2);
        if ($numargums>2)
        {
            $curYear = func_get_arg(3);
        }
		$years = array();
			for($i=$startYear;$i<=$finishYear;$i++){
				$years[] = $i;
			}
		$select = "<select name=\"".$elementName."\">\n";
    	foreach ($years as $val) {
    		$select .= "\t<option value=\"".$val."\"";
    			if ($val == $curYear) {
    				$select .= " selected>".$val."</option>\n";
    			} else {
    				$select .= ">".$val."</option>\n";
    		}
    	}
        $select .= "</select>";
		return $select;
	}
	
	function MonthsName($number) {
		$months = array('', 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь');
		$m = intval($number);
		return $months[$m];
	}
	
	function utf8_str_word($text, $counttext = 10, $sep = ' ') {
		$words = split($sep, $text);
		if ( count($words) > $counttext ) {
			$text = join($sep, array_slice($words, 0, $counttext));
		}
		$text .= "...";
		return $text;
	}

    /**
    * Защита от XSS, заменяет опасные символы на HTML-entities.
    */
    function preventXss($var) {
        /**
        * Если включено автоматическое экранирование значений запроса,
        * отменяем его
        */
        if (get_magic_quotes_gpc()) $var = stripslashes($var);
        $var = htmlentities($var, ENT_QUOTES, 'UTF-8');

        return $var;
    }

?>
