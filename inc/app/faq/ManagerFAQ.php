<?

class ManagerFAQ{
    var $db=false;

	function ManagerFAQ(&$db){
        $this->db=$db;
	}

    function work(){
            global $_TPL;
            $_TPL['LIMIT']=10;
            $id=$_GET['id'];
            $action=$_GET['action'];
            switch(1){
                default:

                break;
                case($action=='view' && $id):
					$_TPL['ROW']=$this->viewFAQ($id);
					if (!$_TPL['ROW']){
						header('Location: /error404');
					exit;
					}
					$_TPL['TITLE'][]=$_TPL['ROW']['faq_title'];
					$_TPL['KEYWORDS']=$_TPL['ROW']['faq_title'];
					$_TPL['DESCRIPTION']=$_TPL['ROW']['faq_question'];
					include TPL_CMS_FAQ."view_faq.php";
                break;
                case($action=='faq'):
                case($action=='list'):
					$tmp=$this->listfaq(CURRENT_PAGE, $_TPL['LIMIT']);
					$_TPL['M_FAQ'] =  $_TPL['LISTFAQ']=$tmp['data'];
					$_TPL['TITLE'][]='Вопрос директору · Страница '.CURRENT_PAGE;
					//$_TPL['PAGES_FAQ'] = pages_list($tmp['cnt'], $_TPL['LIMIT'],'index.php?mod=faq&action=list',CURRENT_PAGE);
					$_TPL['PAGES_FAQ'] = pages_list_uri($tmp['cnt'], $_TPL['LIMIT'],'faqpage1', CURRENT_PAGE);
					include TPL_CMS_FAQ."list_faq_page.php";
				break;
				case($action=='listcat'):
					$tmp=$this->listfaqcat(CURRENT_PAGE, $_TPL['LIMIT']);
					$_TPL['M_FAQ'] =  $_TPL['LISTFAQ']=$tmp['data'];
					$_TPL['TITLE'][]='Вопрос директору · Страница '.CURRENT_PAGE;
					$_TPL['PAGES_FAQ'] = pages_list($tmp['cnt'], $_TPL['LIMIT'],'faq_cat'.$_GET['id'].'_page1',CURRENT_PAGE);
					include TPL_CMS_FAQ."list_faq_page.php";
				break;
				case($action=='add'):
					$_TPL['TITLE'][]='Вопрос директору · Добавить вопрос';
				        if (!empty($_POST)){
						$_TPL['ERROR']=$this->addfaq($_POST);
							if (!count($_TPL['ERROR'])){
								header('Location: '.$GLOBALS["p"].'faq_aplove');
							exit;
							}
						@ $_TPL['ROW']=array_map("htmlspecialchars",$_POST);
						}
					include TPL_CMS_FAQ."form_faq.php";
				break;
				case($action=='add_gut'):
					$_TPL['TITLE'][]='Вопрос директору · Ваш вопрос добавлен';
					include TPL_CMS_FAQ."form_gut_faq.php";
				break;
            }
    }



	function listfaq($page, $limit){
     	$sql=sql_placeholder('select SQL_CALC_FOUND_ROWS f.faq_id, f.faq_category, f.faq_title , f.faq_question, FROM_UNIXTIME(f.faq_datestamp, "%d.%m.%Y") as faq_date, f.faq_author, i.faq_info_title  from  ?#FK_FAQ as f,  ?#FK_FAQ_CAT as i
     	where (f.faq_category = i.faq_info_id) AND f.faq_approved = 1 order by f.faq_id desc limit ?, ?', ($page-1)*$limit, $limit);
        //"SELECT f.faq_id, f.faq_title, FROM_UNIXTIME(f.faq_datestamp,'%d/%m/%Y') as dat, f.faq_author, f.faq_question, f.faq_category, c.faq_info_title FROM cms_faq f LEFT JOIN cms_faq_info c ON (f.faq_category = c.faq_info_id) WHERE ".$fotowhere." ORDER BY f.faq_id DESC LIMIT ".$start." , ".$rowsPerPage."");
		return array('data'=>$this->db->_array_data($sql), 'cnt'=>$this->db->getFoundRow());
    }
	function listfaqcat($page, $limit){
     	$sql=sql_placeholder('select SQL_CALC_FOUND_ROWS f.faq_id, f.faq_category, f.faq_title , f.faq_question, FROM_UNIXTIME(f.faq_datestamp, "%d.%m.%Y") as faq_date, f.faq_author, i.faq_info_title  from  ?#FK_FAQ as f,  ?#FK_FAQ_CAT as i
     	where (f.faq_category = i.faq_info_id) AND f.faq_approved = 1 AND f.faq_category = '.$_GET['id'].' order by f.faq_id desc limit ?, ?', ($page-1)*$limit, $limit);
		return array('data'=>$this->db->_array_data($sql), 'cnt'=>$this->db->getFoundRow());
    }
	 
    function ViewFAQ($id){
       $sql=sql_placeholder('select f.faq_id, f.faq_category, f.faq_title , f.faq_question, f.faq_answer, FROM_UNIXTIME(f.faq_datestamp, "%d.%m.%Y") as faq_date, f.faq_author, f.faq_autor_contact, i.faq_info_title  from  ?#FK_FAQ as f,  ?#FK_FAQ_CAT as i
     	where (f.faq_category = i.faq_info_id) AND f.faq_id=?', $id);
       return $this->db->select_row($sql);
    }

	
	function addfaq($row){
		$tmp=$this->prepare_faq($row);
        if (count($tmp['error'])) return $tmp['error'];
        $error=array();
		
            $r=array('faq_id'=>$id,
                     'faq_category'=>$row['faq_category'],
                     'faq_title'=>$row['faq_title'],
                     'faq_question'=>$row['faq_question'],
                     'faq_datestamp'=>time(),
                     'faq_author'=>$row['faq_author'],
                     'faq_autor_email'=>$row['faq_autor_email'],
					 'faq_autor_contact'=>$row['faq_autor_contact'],
                     'faq_approved'=>0,
					 );
			
             $id=$this->db->addrow(FK_FAQ, $r);
		//Zlyuk файл
		    if (!empty($_FILES['photo']['name'])){
              $error = array_merge($error, $this->addZip($id));
            }
		//Zlyuk файл
         return  $error;
    }
	//Zlyuk файл
	function addZip($id){
    global $_MODCONFIG;
    $pic = new MiniPicBilder(); 
    $filename=$id.'.zip';
    $dir=FAQ_ZIP;
    $err=$pic->upload_zip('photo', $filename, $dir);
    
    /*if (!count($err)){
      list($x,$y) = explode("x", $_MODCONFIG['news']['photo_small']);
      $pic->resize_image($x, $y,  $dir.$filename, $dir.$filename );
      chmod ($dir.$filename, 0777);
    }*/
    return $err;
	}
	//Zlyuk файл
	
	function prepare_faq($row){
	if ($row['faq_category'] == 0){
	$error[]='Вы не выбрали категорию';
	$r['faq_category']='';
	}else{
	$r['faq_category']=$row['faq_category'];
	}
	if ($row['faq_autor_email'] != ""){
	$faq_autor_email = $row['faq_autor_email'];
	if (!preg_match('/^[-0-9\.a-z_]+@([-0-9\.a-z]+\.)+[a-z]{2,6}$/i',$faq_autor_email))
	$error[]='e-mail должен соответствовать формату mail@mail.ru';
	$r['faq_autor_email']='';
	}else{
	$r['faq_autor_email']=$row['faq_autor_email'];
	}
	if (empty($row['faq_title'])){
	$error[]='Вы не указали тему вопроса';
	$r['faq_title']='';
	}else{
	$r['faq_title']=$row['faq_title'];
	}
	if (empty($row['faq_question'])){
	$error[]='Вы не задали вопрос';
	$r['faq_question']='';
	}else{
	$r['faq_question']=$row['faq_question'];
	}
	if (empty($row['faq_author'])){
	$error[]='Вы не указали ФИО';
	$r['faq_author']='';
	}else{
	$r['faq_author']=$row['faq_author'];
	}
	if (empty($row['faq_autor_contact'])){
	$error[]='Вы не оставили контактные данные';
	$r['faq_autor_contact']='';
	}else{
	$r['faq_autor_contact']=$row['faq_autor_contact'];
	}
    $r['faq_datestamp']=(empty($row['faq_datestamp']))?'':$row['faq_datestamp'];

        return array ('data'=>$r, 'error'=>$error);
    }
	
	
}




?>