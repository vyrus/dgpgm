<?

class ManagerFAQAdmin extends ManagerFAQ {
    var $limit=5;
    var $user=false;

    function ManagerFAQAdmin(&$db,  $user){
        $this->db=$db;
        $this->user=$user;
    }

//функция    удаления 
   function delFAQ($id){
        $r=$this->ViewFAQ($id);
		if (file_exists(FAQ_ZIP.$id.'.zip')){
                unlink(FAQ_ZIP.$id.'.zip');
            }
        if($r){
          $sql=sql_placeholder('delete from ?#FK_FAQ where faq_id=?', $id);
          $this->db->query($sql);

        }
    }
	
	
   function delFAQcat($id){
    /*    $r=$this->ViewFAQ($id);
        if($r){*/
          $sql=sql_placeholder('delete from ?#FK_FAQ_CAT where faq_info_id=?', $id);
          $this->db->query($sql);

        //}
    }
	

//список вопросов и ответов

    function listFAQ($page, $limit){
         $error=array();
         $r=array();
         $cnt=0;
             $sql=sql_placeholder('
             select SQL_CALC_FOUND_ROWS faq_id, faq_title, faq_question, faq_datestamp, faq_author, faq_approved from ?#FK_FAQ order by faq_id desc  limit ?, ? ', ($page-1)*$limit, $limit);
               $r=$this->db->_array_data($sql);
               $cnt=$this->db->getFoundRow();

         return array('error'=>$error, 'data'=>$r, 'cnt'=>$cnt);
    }

	function ListFAQcat($page, $limit){
         $error=array();
         $r=array();
         $cnt=0;
             $sql=sql_placeholder('
             select SQL_CALC_FOUND_ROWS faq_info_id, faq_info_title, faq_info_about from ?#FK_FAQ_CAT order by faq_info_id desc  limit ?, ? ', ($page-1)*$limit, $limit);
               $r=$this->db->_array_data($sql);
               $cnt=$this->db->getFoundRow();

         return array('error'=>$error, 'data'=>$r, 'cnt'=>$cnt);
    }
	
	/*faq_id
	faq_category
	faq_title
	faq_question
	faq_answer
	faq_datestamp
	faq_author
	faq_autor_email
	faq_autor_contact
	faq_approved*/

//редактирование
    function editFAQ($id , $row){

         $error=array();
           $row=$this->prepare_faq($row);

            $r=array('faq_id'=>$id,
                      'faq_category'=>$row['faq_category'],
                      'faq_title'=>$row['faq_title'],
                      'faq_question'=>$row['faq_question'],
					  'faq_answer'=>$row['faq_answer'],
                      //'faq_datestamp'=>$row['faq_datestamp'],
                      'faq_author'=>$row['faq_author'],
					  'faq_autor_email'=>$row['faq_autor_email'], //Zlyuk
					  'faq_autor_contact'=>$row['faq_autor_contact'], //Zlyuk
					  'faq_approved'=>$row['faq_approved'], //Zlyuk
                      ); 
			
            $sql=sql_placeholder('update ?#FK_FAQ set ?% where faq_id=?', $r, $id );
		
            if (!$this->db->query($sql)){
                $error[]='Проблема добавления ответа';
            }
	
         return $error;

    }

//просмотр	
   function ViewFAQ($id){
          $sql=sql_placeholder('select f.faq_id, f.faq_category, f.faq_title , f.faq_question, f.faq_answer, FROM_UNIXTIME(f.faq_datestamp, "%d.%m.%Y") as faq_date, f.faq_author, f.faq_autor_contact, f.faq_approved, i.faq_info_title  from  ?#FK_FAQ as f,  ?#FK_FAQ_CAT as i
     	where (f.faq_category = i.faq_info_id) AND f.faq_id=?', $id);
          return $this->db->select_row($sql);

    }
	
	function ViewFAQcat($id){
          $sql=sql_placeholder('select * from ?#FK_FAQ_CAT where faq_info_id=? ', $id);
          return $this->db->select_row($sql);

    }

    function prepare_faq($row){
          $r['faq_title']=(empty($row['faq_title']))?'':$row['faq_title'];
          $r['faq_category']=(empty($row['faq_category']))?'':$row['faq_category'];
          $r['faq_question']=(empty($row['faq_question']))?'':$row['faq_question'];
        $r['faq_answer']=(empty($row['faq_answer']))?'':$row['faq_answer'];
        $r['faq_datestamp']=(empty($row['faq_datestamp']))?'':$row['faq_datestamp'];
        
        $r['faq_author']=(empty($row['faq_author']))?'':$row['faq_author'];
        $r['faq_autor_email']=(empty($row['faq_autor_email']))?'':$row['faq_autor_email'];
		$r['faq_autor_contact']=(empty($row['faq_autor_contact']))?'':$row['faq_autor_contact'];
		$r['faq_approved']=(empty($row['faq_approved']))?'':$row['faq_approved'];

        return $r;


    }

	function addfaqcat($row){
		$tmp=$this->prepare_faqcat($row);
        if (count($tmp['error'])) return $tmp['error'];
        $error=array();
            $r=array('faq_info_id'=>$id,
                     'faq_info_title'=>$row['faq_info_title'],
                     'faq_info_about'=>$row['faq_info_about'],
					 );

              $this->db->addrow(FK_FAQ_CAT, $r);
		
         return  $error;
    }
	
	function updatefaqcat($id, $row){

        $error=array();
		
          //$row=$this->prepare_faqcat($row);
				
				$r=array('faq_info_id'=>$id,
                'faq_info_title'=>$row['faq_info_title'],
                'faq_info_about'=>$row['faq_info_about'],
				);
			
            $sql=sql_placeholder('update ?#FK_FAQ_CAT set ?% where faq_info_id=?', $r, $id );
			
            if (!$this->db->query($sql)){
                $error[]='Проблема добавления ответа';
            }
	
         return $error;

    }
	
	function prepare_faqcat($row){
	if (empty($row['faq_info_title'])){
	$error[]='Вы не указали название категории';
	$r['faq_info_title']='';
	}else{
	$r['faq_info_title']=$row['faq_info_title'];
	}
    $r['faq_info_about']=(empty($row['faq_info_about']))?'':$row['faq_info_about'];

        return array ('data'=>$r, 'error'=>$error);
    }

function work(){
       global $_MODCONFIG;
       switch(1){
          default:
            $tmp=$this->ListFAQ(CURRENT_PAGE, $this->limit);
            $_TPL['ERROR']=$tmp['error'];
            $_TPL['LISTROW']=$tmp['data'];
            $_TPL['CNTPAGE']=ceil($tmp['cnt']/$this->limit);
            include TPL_ADMIN_FAQ."list_faq.php";
          break;
          /*case($_GET['action']=='editfaq' && $_GET['id']):
            header('Location: ?mod=faq&action=editfaq&id='.$_GET['id'].'&page='.CURRENT_PAGE); exit;
          break;*/
          case($_GET['action']=='editfaq' && $_GET['id']):
             if (!empty($_POST['faq_title'])){
               $_TPL['ERROR']=$this->editFAQ($_GET['id'], $_POST);
               if(!count($_TPL['ERROR'])){
               $_TPL['ERROR'][]="<script>alert('Данные сохранены')</script>"; 
               //$_TPL['ERROR'][]='Данные сохранены';
			   header('Location: ?mod=faq');
				}
             }

             $_TPL['ROW']=$this->viewFAQ($_GET['id']);
             include TPL_ADMIN_FAQ."form_faq.php";
          break;
		  
		  case($_GET['action']=='print' && $_GET['id']):
				$_TPL['ROW']=$this->viewFAQ($_GET['id']);
				include TPL_ADMIN_FAQ."print_faq.php";
          break;
		  
		  case($_GET['action']=='delfaq' && $_GET['id']):
             $this->delFAQ($_GET['id']);
             header('Location: ?mod=faq');
		  exit;
          break;
		  case($_GET['action']=='delfaqcat' && $_GET['id']):
             $this->delFAQcat($_GET['id']);
             header('Location: ?mod=faq&action=cat');
		  exit;
          break;
		  
		  
		  case($_GET['action']=='cat'):
            $tmp=$this->ListFAQcat(CURRENT_PAGE, $this->limit);
            $_TPL['ERROR']=$tmp['error'];
            $_TPL['LISTROW']=$tmp['data'];
            $_TPL['CNTPAGE']=ceil($tmp['cnt']/$this->limit);
            include TPL_ADMIN_FAQ."list_faq_cat.php";
          break;
		  case($_GET['action']=='catadd'):
					if (!empty($_POST)){
						$_TPL['ERROR']=$this->addfaqcat($_POST);
							if (!count($_TPL['ERROR'])){
								header('Location: ?mod=faq&action=cat');
							exit;
							}
						@ $_TPL['ROW']=array_map("htmlspecialchars",$_POST);
						}
					include TPL_ADMIN_FAQ."form_faqcat.php";
          break;
		   case($_GET['action']=='editfaqcat' && $_GET['id']):
             if (!empty($_POST['faq_info_title'])){
               $_TPL['ERROR']=$this->updatefaqcat($_GET['id'], $_POST);
               if(!count($_TPL['ERROR'])){
               $_TPL['ERROR'][]="<script>alert('Данные сохранены')</script>"; 
               $_TPL['ERROR'][]='Данные сохранены';
				}
             }
			$_TPL['ROW']=$this->viewFAQcat($_GET['id']);
			include TPL_ADMIN_FAQ."form_faqcat.php";
          break;
		  
        }
    }

}
?>