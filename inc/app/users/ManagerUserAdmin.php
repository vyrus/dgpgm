<?
   class ManagerUserAdmin extends ManagerUser {
          var $limit=20;

       function work(){
         global $_TPL, $_MODCONFIG;
          switch(1){
            default:
              include TPL_ADMIN_USERS."index.php";
            break;
            case($_GET['action']=='listusers'):

              $tmp=$this->listUsers();
              $_TPL['LISTROW']=$tmp['data'];
              $url="mod=users&action=".$_GET['action'].'&page=';
	          $_TPL['LISTPAGE']= pages_list($tmp['cnt'], $this->limit,$url,CURRENT_PAGE);
              include TPL_ADMIN_USERS."list_users.php";
            break;
            case($_GET['action']=='edituser' && $_GET['id']):
                if (empty($_POST)){
                	$_TPL['ROW']=$this->GetUserData($_GET['id']);
                	$_TPL['ROW']['login_1']=$_TPL['ROW']['login'];
                }else{
                	$_TPL['ROW']=$_POST;
                	$_TPL['ERROR']=$this->editUserData($_GET['id'], $_POST);
                	if (!count($_TPL['ERROR'])){
	                	header('Location: ?mod=users&action=listusers');
 		               	exit;
 		            }
                }
                include TPL_ADMIN_USERS."form_user.php";
            break;
            case ($_GET['action']=='adduser'):
                if (!empty($_POST)){
                	$_TPL['ROW']=$_POST;
                	$_TPL['ERROR']=$this->addUser($_POST);
                	if (!count($_TPL['ERROR'])){
	                	header('Location: ?mod=users&action=listusers');
 		               	exit;
 		            }
                }

				 include TPL_ADMIN_USERS."form_user.php";
            break;
            case($_GET['action']=='deluser' && $_GET['id']):
                 if(!$this->deleteUser($_GET['id'])){
                 	$_TPL['ERROR'][]='Ошибка удаления пользователя, пользователь не сушествует';
                 }else{
                 	header('Location: ?mod=users&action=listuser&page='.CURRENT_PAGE);
                 }
            break;

            case($_GET['action']=='listgroup'):
                if (!empty($_POST['title'])){
                  $this->addGroup($_POST);

                  header('Location: ?mod=user&action=listgroup');
                  exit;
                }
                $tmp=$this->listGroup();
                $_TPL['LISTROW']=$tmp['data'];
                include TPL_ADMIN_USERS."list_group.php";
            break;
            case($_GET['action']=="editgroup" && $_GET['id']):

              $tmp=$this->viewGroup($_GET['id']);
                if (!empty($_POST['title']) && $this->editGroup($_GET['id'], $_POST)){
                  header('Location: ?mod=user&action=listgroup');
                  exit;
                }

                if (!empty($_POST['access'])){
	                 $this->SetAccessGroup($_GET['id'],$_POST);
                }

                $_TPL['ROW']=$tmp['data'];
                $_TPL['ACCESS']=$tmp['access'];

				include TPL_ADMIN_USERS."form_group.php";
            break;
            case($_GET['action']=='deletegroup' && $_GET['id']):
               $this->deleteGroup($_GET['id']);
                header('Location: ?mod=user&action=listgroup');
               exit;
            break;
            case($_GET['action']=='g2u' && $_GET['id']):
            	if(!empty($_POST['login_1'])){
            		$_TPL['ERROR']=$this->addUser2Group($_POST['login_1'], $_GET['id']);
            		if (!count($_TPL['ERROR'])){
						header('Location: '.$_SERVER['REQUEST_URI']);; exit;
            		}
            	}
                $tmp=$this->listUser2Group($_GET['id']);
                $_TPL['LISTROW']=$tmp['data'];
                $url="?mod=user&action=".$_GET['action'];
	            $_TPL['LISTPAGE']=     pages_list($tmp['cnt'], $this->limit,$url,CURRENT_PAGE);
                include TPL_ADMIN_USERS."list_user2group.php";
            break;
             case($_GET['action']=='deluser2group' && !empty($_GET['id']) && !empty($_GET['uid'])):
                  if ($this->deleteUser2Group($_GET['id'], $_GET['uid'])){
                    header('Location: ?mod=user&action=g2u&id='.$_GET['id'].'&page='.$_GET['page']);
                    exit;
                  }else{
					$_TPL['ERROR'][]='Ошибка удаления пользователя из группы';

                  }
            break;

            case($_GET['action']=='u2g' && $_GET['id']):
				$tmp=$this->listGroup2User($_GET['id']);
  			 	$_TPL['LISTROW']=$tmp['data'];
                $url="?mod=users&action=".$_GET['action'];
	            $_TPL['LISTPAGE']=     pages_list($tmp['cnt'], $this->limit,$url,CURRENT_PAGE);

               include TPL_ADMIN_USERS."list_group2user.php";
            break;


          }
       }

		function deleteGroup($id){
           $sql=sql_placeholder('delete from ?#FK_ACCESS where group_id = ?', $id);
           $this->db->query($sql);
           $sql=sql_placeholder('delete from ?#FK_GROUP where group_id=?', $id);
           $this->db->query($sql);
		}

		function deleteUser2Group($gid, $uid){
			$sql=sql_placeholder('delete from ?#FK_G2U where group_id=? and user_id=? ', $gid, $uid);
			return $this->db->query($sql);
		}

       function viewGroup($id){
       		  $error=$news=array();
              $sql=sql_placeholder('select * from ?#FK_GROUP where group_id=?', $id);
              $data=$this->db->select_row($sql);
              if(!$data){
               $error[]='Группа не найдена';
              }else{


               $sql=sql_placeholder('select n.title, n.action, a.action as access  from ?#FK_ACTION as n
             				left join ?#FK_ACCESS as a
              				    on (a.action=n.action and a.group_id = ?)
              				    where    1
              				    ', $id);
               $access=$this->db->_array_data($sql);


              }
              return array('error'=>$error, 'data'=>$data,  'access'=>$access);
       }

       /*
		function SetAccessNewsCity($id, $r){
    		$sql= sql_placeholder('delete from ?#FK_ACCESS where action="city" ) and  group_id=?', $id);
    		$this->db->query($sql);
    		foreach($r as $cid=>$tmp){
                 $row=array('group_id'=>$id, 'catalog_id'=>$cid, )
    		}
		}
           */

       function SetAccessGroup($id, $r){
         $sql=sql_placeholder('delete from ?#FK_ACCESS where group_id=?', $id);
         $this->db->query($sql);

        if(!empty($r['c'])){
         foreach($r['c'] as $cid=>$vl){
           $row['group_id']=$id;
           $row['catalog_id']=$cid;
           $row['action']='city';
           $this->db->addrow(FK_ACCESS, $row);
         }
        }
        if(!empty($r['a'])){
         foreach($r['a'] as $cid=>$vl){
           $row['group_id']=$id;
           $row['catalog_id']=0;
           $row['action']=$cid;
           $this->db->addrow(FK_ACCESS, $row);
         }
        }
           return 1;
       }

       function listGroup(){
          $sql=sql_placeholder('select * from ?#FK_GROUP order by title');
           $r=$this->db->_array_data($sql);
           $cnt=$this->db->getFoundRow();
           return array('data'=>$r, 'cnt'=>$cnt);
       }

       function addGroup($row){
 			$row=$this->prepare_group($row);
            return $this->db->addrow(FK_GROUP,$row);

       }

       function editGroup($id, $row){
           $row=$this->prepare_group($row);
           $sql=sql_placeholder('update ?#FK_GROUP set ?% where group_id=?', $row, $id);
           return $this->db->query($sql);
       }

       function prepare_group($r){
          $row['title']=(!empty($r['title']))?$r['title']:'';
          return $row;
       }

       function listUsers(){
           $r=$error=array();
           $cnt=0;

           $sql=sql_placeholder('select SQL_CALC_FOUND_ROWS * from  ?#FK_USER  order by login limit  ?,?', (CURRENT_PAGE-1)*$this->limit, $this->limit);
           $r=$this->db->_array_data($sql);
           $cnt=$this->db->getFoundRow();

           return  array('data'=>$r, 'cnt'=>$cnt) ;
       }

       function GetUserData($id){
	      $sql=sql_placeholder('select * from ?#FK_USER where id=?', $id);
 	      return    $this->db->select_row($sql);
       }

        function editUserData($id, $row){
          $tmp=$this->prepare_userdata($id, $row);
          if (count($tmp['error'])) return $tmp['error'];
          $error=array();
          $sql=sql_placeholder('update ?#FK_USER set ?% where id=?', $tmp['data'], $id);
        //  echo $sql;
          if (!$this->db->query($sql)){
          	$error[]="Ошибка SQL запроса";
          }

          return $error;

      }





       function prepare_userdata($id, $row){
      	 $error=$data=array();
      	 @$row['login']=$row['login_1']; unset ($row['login_1']);
         if (!empty($row['login'])  && preg_match("#[^A-Za-z0-9_]#is", $row['login'])){
           $error[]=' Логин может содежать только цифры и буквы латинского алфавита ';
         }elseif (/*$id==0 &&*/ !empty($row['login'])){
           $sql=sql_placeholder('select count(*) as cnt from ?#FK_USER where login=? and id!=?', $row['login'], $id);
           if ($this->db->select_row($sql)){
             $error[]='Логин занять';
           }else{
            $data['login']=$row['login'];
           }
         }

         if (empty($row['name'])) $error[]=' Не заполнено поле ФИО ';
         else $data['name']=$row['name'];

         //if (empty($row['password_old'])) $error[]=' Не указан старый пароль  ';
         if (!empty($row['passwd_1']) || !empty($row['passwd_2'])){
         	if(empty($row['passwd_1']) || empty($row['passwd_2']) || ($row['passwd_2']!=$row['passwd_1']) ){
         	  $error[]=' Пароли не совпадают  ';
         	}else{
         	  $data['passwd']=($row['passwd_1']);
         	}
         }elseif($id==0){
         	$error[]='Пароль не задан';
         }
         /*
         if (empty($row['email'])) $error[]='поле  Email  не заполнено ';
         else $data['email']=$row['email'];

             */

         return array('error'=>$error, 'data'=>$data);

      }


      function deleteUser($id){
        $row=$this->getUserData($id);
        if (!$row) return false;
        $sql=sql_placeholder('delete from ?#FK_G2U where user_id=?', $row['id']);
	    if (! $this->db->query($sql)) return false ;

        $sql=sql_placeholder('delete from ?#FK_USER where id=?', $row['id']);
        return  $this->db->query($sql);

      }

      function addUser($row){
        $tmp=$this->prepare_userdata(0, $row);

        if (!count($tmp['error'])){
          $this->db->addrow(FK_USER , $tmp['data']);
        }
        return $tmp['error'];
      }

 	  function listUser2Group($id){
    	 $sql=sql_placeholder(' select u.* from ?#FK_USER as u , ?#FK_G2U as g where u.id=g.user_id and g.group_id=? order by u.login limit ?,?', $id, (CURRENT_PAGE-1)*$this->limit, $this->limit);
    	 $r=$this->db->_array_data($sql);
         $cnt=$this->db->getFoundRow();
         return array('data'=>$r, 'cnt'=>$cnt);
 	  }

 	  function addUser2Group($login, $gid){
		$sql=sql_placeholder('select id from ?#FK_USER where login=?', $login);
		$id=$this->db->select_row($sql);
        if(!$id){
        	return array(0=>"Пользователь не найден");
        }else{
           $sql=sql_placeholder('select count(*) as cnt from ?#FK_G2U where user_id=? and group_id=?', $id, $gid);
           if (!$this->db->select_row($sql)){
           	$this->db->addrow(FK_G2U, array('usr_id'=>$id, 'group_id'=>$gid));
           }
          return array();
        }
 	  }

 	  function listGroup2User($id){
          $sql=sql_placeholder('select * from ?#FK_GROUP as g, ?#FK_G2U as g2u
          	where g.group_id=g2u.group_id
          	and g2u.user_id=?  limit ?,?
          	', $id, (CURRENT_PAGE-1)*$this->limit, $this->limit);
          $r=$this->db->_array_data($sql);
         $cnt=$this->db->getFoundRow();
         return array('data'=>$r, 'cnt'=>$cnt);
 	  }

  }

?>