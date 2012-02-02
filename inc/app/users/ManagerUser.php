<?

class ManagerUser{
       var $db=false;
       var $session_name='session';
       var $domain='';
       var $session_time=18000;
       var $user_id=1;
       var $login='login';
       var $password='passwd';



	function ManagerUser(&$db){
          $this->db=$db;
	}

       function setField($field){
          if (is_array($field) ){
          	 foreach($field as  $kl=>$vl){
              $this->$kl=$vl;
          	 }
          }
       }

       function GetUserData($key){
          $sql=sql_placeholder('select u.*, s.sid, g.group_id, bid.id as bid_id from ?#FK_SESSION as s, ?#FK_G2U as g, ?#FK_USER as u
		  left join ?#FK_BID as bid on (bid.user_id=u.id) where s.uid=u.id and g.user_id=u.id and s.sid=?
           	', $key);

           $row=$this->db->select_row($sql);

           if ($row['id']>1){
           	 $sql=sql_placeholder('update ?#FK_USER set last_ut=? where id=? ', time(), $row['id']);
             $this->db->query($sql);
           }
           return $row;
       }


       function BildSession(){
         $this->deleteOldSession();
       	 if (empty($_COOKIE[$this->session_name])
       	 	|| preg_match("#[^\d\w]#is",$_COOKIE[$this->session_name] ) ){
            return $this->_NewSession();
       	 }
       	 if(!$this->checkedSession()) {
           return  $this->_NewSession();
      	 }else{
           return  $this->_ProlongSession();
      	 }

       }

       function _NewSession(){
       	   $key=md5(time().$_SERVER['REMOTE_ADDR'].rand(1, 10000));
           setcookie($this->session_name, $key, time()+$this->session_time, '/', $this->domain );
           $session = array('sid'=>$key, 'uid'=>$this->user_id, 'delut'=>time()+$this->session_time);
           $this->db->addrow(FK_SESSION, $session);
          // $_COOKIE[$this->session_name]=$key;
           return $key;
       }

       function _ProlongSession(){
           $sql=sql_placeholder('update ?#FK_SESSION set delut=? where sid=?',
           	 mktime()+$this->session_time,  $_COOKIE[$this->session_name]);
           $this->db->query($sql);
           setcookie($this->session_name, $_COOKIE[$this->session_name], time()+$this->session_time, '/', $this->domain );
           return $_COOKIE[$this->session_name];
       }


       function checkedSession(){
          $sql=sql_placeholder('select count(*) as cnt from ?#FK_SESSION where sid=?', $_COOKIE[$this->session_name]);
          return $this->db->select_row($sql);
       }

       function Login($key, $row){
       	  $id=0;
          $error=array();
		  
          if (empty($row[$this->login])) $error[]='Поле логин не заполнено';
          if (empty($row[$this->password])) $error[]="Поле пароль не заполнено";
		  
          if(!count($error)){
             $sql=sql_placeholder('select id from ?#FK_USER where login=? and passwd=? /* and state!="blocked" */',
             	//$row[$this->login], $row[$this->password]);
             	$row[$this->login], md5($row[$this->password]));

			 if(!$id=$this->db->select_row($sql)){
             	 $error[]='Такого сочетания логина и пароля не найдено';
              }else{
				 $sql=sql_placeholder('select id from ?#FK_USER where login=? and state!="inactive"', $row[$this->login]);
					if(!$id2=$this->db->select_row($sql)){
						$error[]='Необходимо активировать учетную запись';
					} else {
						$sql=sql_placeholder('update ?#FK_SESSION  set uid=? where sid=?' , $id, $key);
						$this->db->query($sql);
					}
             }
           }
           return array('error'=>$error, 'id'=>$id);
       }

       function Logout($key){
          $sql=sql_placeholder('delete from ?#FK_SESSION where sid=?', $key);
          setcookie($this->session_name, '', time()-$this->session_time, '/', $this->domain );
          return $this->db->query($sql);
       }

      function deleteOldSession(){
         $sql=sql_placeholder('delete  from ?#FK_SESSION where delut<?', time());
         return $this->db->query($sql);
      }


	function generate_password($length){
		$num = range(0, 9);
		$alf = range('a', 'z');
		$_alf = range('A', 'Z');
		$symbols = array_merge($num, $alf, $_alf);
		shuffle($symbols);
		$code_array = array_slice($symbols, 0, (int)$length);
		$code = implode("", $code_array);
		return $code;
	}

	function generate_login($length){
		$num = range(0, 9);
		$symbols = array_merge($num);
		shuffle($symbols);
		$code_array = array_slice($symbols, 0, (int)$length);
		$code = implode("", $code_array);
		return $code;
	}  
	  
	function addUser($id,  $row){

        /*$tmp=$this->prepareUserData($id, $row);
        if (!count($tmp['error'])){*/
			$tmp['data'] = $row;
			$tmp['data']['registration_ut']=time();
			$tmp['data']['state'] = 'active';
			$id =  $this->db->addrow(FK_USER, $tmp['data']);
			if ($id) {
			$this->db->addrow('?#FK_G2U', array('user_id'=>$id, 'group_id'=>2));
			// добавить пустые поля в таблицу организации или индивид., смотря какой указан тип
			if ($tmp['data']['type-face'] == 'yur') {
				$sql_table = FK_APP_ORG;
			} elseif ($tmp['data']['type-face'] == 'fiz') {
				$sql_table = FK_APP_IND;
			}
			$org_ind_id = $this->db->addrow($sql_table, Array('id'=>'NULL'));
			$sql = sql_placeholder('update ?#FK_USER set id_org_ind=? where id = ?', $org_ind_id, $id);
            $this->db->query($sql);
			//$this->db->addrow('?#FK_G2U', array('user_id'=>$id, 'group_id'=>2));
			/*$to = $tmp['data']['email'];
			$subject = "Регистрация на сайте ".$_SERVER['SERVER_NAME'];
			$body = "Здравствуйте!<br /><br />
			Вы зарегистрировались на сайте ".$_SERVER['SERVER_NAME'].". Пожалуйста, активируйте Вашу учетную запись, перейдя по ссылке:<br /><br />
			<a href='http://".$_SERVER['SERVER_NAME']."/login/".$tmp['data']['login']."/activate/".md5($tmp['data']['registration_ut'].$tmp['data']['login'].$tmp['data']['passwd'])."'>http://".$_SERVER['SERVER_NAME']."/login/".$tmp['data']['login']."/activate/".md5($tmp['data']['registration_ut'].$tmp['data']['login'].$tmp['data']['passwd'])."</a><br /><br />
			Для входа в систему используйте следующие данные:<br /><br />
			Логин: ".$tmp['data']['login']."<br />
			Пароль: ".$password."<br /><br />
			-- Всего хорошего!";
			
			fnSendMail($to,$subject,$body);*/
			
			/*} else {
				$tmp['error'] = "Ошибка при регистрации. Пожалуйста, попробуйте еще раз.";
			}*/
		
		}

		return array('user_id'=>$id, 'org_ind_id'=>$org_ind_id);
	}
	
		
		function prepareRegData($row){

		$error =
		$data  = array();
		
		if ($row['pp'] == 0){
			$error[]='Необходимо выбрать подпрограмму';
		}else{
            $data['pp']=$row['pp'];
        }
		
		if ($row['mr'] == 0){
			$error[]='Необходимо выбрать мероприятие';
		}else{
            $data['mr']=$row['mr'];
        }
		
		if (empty($row['name'])){
			$error[]='Укажите ФИО контактного лица';
		} else {
			$data['name']=$row['name'];
	    }
		
        if (empty($row['type-face'])){
				$error[]='Необходимо выбрать один из типов';
			} elseif (!in_array($row['type-face'], array("fiz", "yur"))) {
				$error[]='Неверное значение поля';
			}else{
	            $data['type-face']=$row['type-face'];
	         }

        if (empty($row['email'])) {
            $error[]='поле Email не заполнено';
		} elseif (preg_match("#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#i", $row['email'])==0 ) {
			$error[]='поле Email заполнено некорректно';
		} else {
			$data['email']=$row['email'];
		}

        if (empty($row['phone'])){
			$error[]='поле Телефон не может быть пустым';
		} elseif (!preg_match("#[0-9-\+\(\)\s]#is", $row['phone'])) {	
			$error[]='Указан некорректный телефон';
        }else{
            $data['phone'] = $row['phone'];
        }
		
		// капча
		if(empty($row['keystring'])) {
			$error[]='Введите код, изображенный на картинке';
		} elseif ($_SESSION['captcha_keystring'] != $_POST['keystring']){
			$error[]='Неправильно введен защитный код';
		}
		
         return array('error'=>$error, 'data'=>$data) ;
      }
		


      function prepareUserData($id, $row){

      	 $error =
      	 $data  = array();
		
		/*
		
			 $sql=sql_placeholder('select count(*) as cnt from ?#FK_USER where login=? and id!=? and id!=1 ', $row['login_1'], $id );
	         if ($this->db->select_row($sql)){
	             $error[]='Логин занят, придумайте другой';
	         }
		*/
		
		
           if (empty($row['type-face'])){
				$error[]='Необходимо выбрать один из типов';
			} elseif (!in_array($row['type-face'], array("fiz", "yur"))) {
				$error[]='Неверное значение поля';
			}else{
	            $data['type-face']=$row['type-face'];
	         }

        if (empty($row['email'])) {
            $error[]='поле Email не заполнено';
		} elseif (preg_match("#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$#i", $row['email'])==0 ) {
			$error[]='поле Email заполнено некорректно';
		} else {
			$data['email']=$row['email'];
		}

        if (empty($row['phone'])){
			$error[]='поле Телефон не может быть пустым';
		} elseif (!preg_match("#[0-9-\+\(\)\s]#is", $row['phone'])) {	
			$error[]='Указан некорректный телефон';
        }else{
            $data['phone'] = $row['phone'];
        }
		
		// капча
		if(empty($row['keystring'])) {
			$error[]='Введите код, изображенный на картинке';
		} elseif ($_SESSION['captcha_keystring'] != $_POST['keystring']){
			$error[]='Неправильно введен защитный код';
		}
		
		$data['login'] = $row['login'];
		$data['passwd'] = $row['passwd'];
		
         return array('error'=>$error, 'data'=>$data) ;
      }


      function listUser($page, $limit=20){
         $sql=sql_placeholder('select SQL_CALC_FOUND_ROWS * from ?#FK_USER where action!=0 order by login limit ?, ? ',
           ($page - 1)*$limit, ($page - 1)*$limit+$limit);
         $r=$this->db->_array_data($sql);
         if (!$r) return false;
         $cnt=$this->db->getFoundRow();
         return array('data'=>$r, 'cnt'=>$cnt);
      }

	  function editUserData($id, $row){
		$sql=sql_placeholder('update ?#FK_USER set ?% where id=?', $row, $id);
		return $this->db->query($sql);
	  }
      /*function editUserData($id, $row){
          $tmp=$this->prepareUserData($id, $row);


          if (count($tmp['error'])) return $tmp['error'];
          $error=array();
            
          $sql=sql_placeholder('update ?#FK_USER set ?% where id=?', $tmp['data'], $id);
      
          if (!$this->db->query($sql)){
          	$error[]="Ошибка SQL запроса";
          }else{


                if (!empty($_FILES['photo']['name'])){
                       $error = MiniPicBilder::upload_jpg('photo', $id.'.jpg', 'IMG/user/' );
                       if (!count($error)){
                            MiniPicBilder::resize_image(150, 150, "IMG/user/$id.jpg", "IMG/user/_$id.jpg"); 
                                     
                        }
                }
         } 

          return $error;

      }*/


     /* function prepare_userdata($id, $row){

      	 $error=$data=$h=array();
         if (!empty($row['login']) && $id==0 && preg_replace("#[^A-Za-z0-9_]#is", $row['login'])){
           $error[]=' Логин может содежать только цифры и буквы латинского алфавита ';
            $h['login']=1;
         }elseif ($id==0 && !empty($row['login'])){
           $sql=sql_placeholder('select count(*) as cnt from ?#FK_USER where login=?', $row['login']);
           if ($this->db->select_row($sql)){
             $error[]='Логин занят, придумайте другой';
             $h['login']=1;
           }else{
            $data['login']=$row['login'];
           }
         }

         if (empty($row['name'])){
                 $error[]=' Не заполнено поле ФИО ';
                $h['name']=1;
         }else{
                 $data['name']=$row['name'];
         }

         if (empty($row['password_old'])){
                 $error[]=' Не указан старый пароль  ';
                $h['password_old']=1;
         }
         if (!empty($row['password_1']) || !empty($row['password_2'])){
         	if(empty($row['password_1']) || empty($row['password_2']) || ($row['password_2']!=$row['password_1']) ){
         	  $error[]=' Пароли не совпадают';
                    $h['password_1']=1;
                    $h['password_2']=1;
         	}else{
       //  	  $data['passwd']=md5($row['password_1']);
           	  $data['passwd']=$row['password_1'];
         	}
         }
         if (empty($row['email'])){
                 $error[]='поле  Email  не заполнено ';
                $h['email']=1;
         }else{
             $data['email']=$row['email'];
         }

     
         if (!empty($row['testgroup']) && in_array($row['testgroup'] , array(1,2,3,4))){
                $data['testgroup'] = intval($row['testgroup']);
         }else{
                $error[]='Вы не выбрали группу';
                $h['testgroup']=1;
            
         }

         if (!empty($row['city'])){
                $data['city'] = htmlspecialchars($row['city']);
         }else{
                $error[]='Вы не указали город';
                $h['city']=1;
         }


         return array('error'=>$error, 'data'=>$data);

      }*/

      function GetListAccess($user_id, $catalog_id){
	       $access=array();
           $sql=sql_placeholder('select a.* from ?#FK_G2U as g2u ,  ?#FK_ACCESS as a
				where   /* a.catalog_id=?
					and
					*/
					a.group_id=g2u.group_id
					and	g2u.user_id=?
           	', $catalog_id, $user_id);
            $r=$this->db->_array_data($sql);
            foreach($r as $row){
             $access[$row['action']][]=$row['catalog_id'];
            }
         /*
            echo $sql."<hr>";
            echo "<pre>";
                print_R($access);
            echo "</pre>";
         */
            return  $access;

      }


      function listCityAccess($user_id){
  		 $city_id=array();
  		$sql=sql_placeholder('select  c.* from ?#FK_ACCESS as a, ?#FK_G2U as g2u, ?#FK_CITY as c
  		 where g2u.user_id=?
  		 and g2u.group_id=a.group_id
         and a.action="city"
         and c.id=a.catalog_id order by title
  		 ', $user_id);

  		 $r= $this->db->_array_data($sql);

  		 foreach($r as $row){
             $city_id[$row['id']]=$row['title'];
  		 }
  		 return $city_id;
	}

      function ip2int($ip) {
                $a=explode(".",$ip);
                return $a[0]*256*256*256+$a[1]*256*256+$a[2]*256+$a[3];
      }


      function rebildIntIp(){
                $sql = sql_placeholder('select id, ip from ?#FK_SID where intip<1 limit 500');
                $r = $this->db->_array_data($sql);
                foreach($r as $row){
                        $sql = sql_placeholder('update ?#FK_SID set intip=? where id = ?', $this->ip2int($row['ip']), $row['id'] );
//echo $sql."<hr>";
                        $this->db->query($sql);

                }


                if (count($r)){
                        $this->rebildIntIp();
                }

      }



      function getSid(){
           return (empty($_COOKIE['sid']) || !preg_match('#\w{32}#', $_COOKIE['sid']))?$this->bildSid():$_COOKIE['sid'];
      }

      function bildSid(){
          $sid = md5(time().$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

          $a = array(
                'sid'=>$sid,
                'ip'=>$_SERVER['REMOTE_ADDR'],
                'ua'=>$_SERVER['HTTP_USER_AGENT'],
                'intip'=>  $this->ip2int($_SERVER['REMOTE_ADDR']), 
            );

         setcookie('sid', $sid, time()+60*60*24*30*24, '/', '.'.str_replace('www.', '',$_SERVER['SERVER_NAME']));
         
         $sql =sql_placeholder('select count(*) as cnt from ?#FK_SID where sid=?', $sid);
         if (!$this->db->select_row($sql)){
                $this->db->addrow(FK_SID, $a);
        }
         return $sid;

      }

      function getSid_id(){
            global $_MODUSERCACHE;
            $sid = $this->getSid();
            if (!empty($_MODUSERCACHE[$sid])) return  $_MODUSERCACHE[$sid];
            $sql = sql_placeholder('select id from ?#FK_SID where sid =?', $sid);
            $id = $this->db->select_row($sql);
            $_MODUSERCACHE[$sid] =$id;
            return $id;
      }

      function cntUser(){
         $sql =sql_placeholder('select count(*) as cnt from ?#FK_USER');
         return $this->db->select_row($sql);
      }

	  function prepareActivateDataNew($row){
		$sql = sql_placeholder('select registration_ut,	passwd from ?#FK_USER where login =?', $row['login']);
		$data = $this->db->select_row($sql);
		
		$code = md5($data['registration_ut'].$row['login'].$data['passwd']);
		
		if ($code != $row['code']) {
			$error = "Неверный код активации";
		}
		
		return $error;
		}
	  
      function work(){
            global $_TPL;
            $action = (empty($_GET['action']))?'':$_GET['action'];
			// сессия капчи
			session_start();
            switch(1){			
            
				case($action=='reg' && !empty($_POST)):
					if (USER_ID==1){
						$TPL['SUBPROGRAM'] = ManagerForms::listSubprogram();
						$tmp = $this->prepareRegData($_POST);
						if(!count($tmp['error'])) {
							//создаем заявку
							$row = array();
							$row['date_create_bid'] = date('Y-m-d');
							$row['measure_has_notice_measure_id'] = $tmp['data']['mr'];
							$noticeinfo = ManagerForms::viewNotice($tmp['data']['mr']);
							$row['measure_has_notice_notice_id'] = $noticeinfo['notice_id'];
							// потом нужно будет вставить bid_id_in_measure
							//$maxbid = ManagerForms::maxBidMeasure($tmp['data']['mr'], $noticeinfo['turn']);
							//$maxbid = $maxbid + 1;
							//$row['bid_id_in_measure'] = $maxbid;
							//$max_id_bid = sprintf("%03d", $row['bid_id_in_measure']);
							//$bid_login = $noticeinfo['start_realization']."_".$tmp['data']['mr']."_".$noticeinfo['turn']."_".$max_id_bid;
							//$row['bid_cipher'] = $bid_login;
							$id=$this->db->addrow(FK_BID, $row);
							if ($id) {
							// добавляем три записи в таблицу с формами
							$forms = array('tz','tablestep','price');
								foreach ($forms as $form) {
									$data['bid_id'] = $id;
									$data['form_type'] = $form;
									$data['complete'] = 0;
									$this->db->addrow(FK_FORM, $data);
								}
							//данные для заявки
							$bid = array();							
							$maxbid = $id;
							$max_id_bid = sprintf("%03d", $maxbid);
							$bid_login = $noticeinfo['start_realization']."_".$tmp['data']['mr']."_".$noticeinfo['turn']."_".$max_id_bid;
							$bid['bid_cipher'] = $bid_login;
							// регистрируем пользователя
							$user = array();
							$user['login'] = $bid_login;
							$password = $this->generate_password(6);
							$user['passwd'] = md5($password);
							$user['type-face'] = $tmp['data']['type-face'];
							$user['name'] = $tmp['data']['name'];
							$user['phone'] = $tmp['data']['phone'];
							$user['email'] = $tmp['data']['email'];
							$user_id = $this->addUser(0, $user);
							// обновляем заявку
							$bid['user_id'] = $user_id['user_id'];
							/*if ($tmp['data']['type-face'] == 'fiz') {
								$bid['applicant_individual_id'] = $user_id['org_ind_id'];
							}elseif  ($tmp['data']['type-face'] == 'yur') {
								$bid['applicant_organization_id'] = $user_id['org_ind_id'];
							}*/
							ManagerForms::updateBid($id, $bid);
							$filename = md5($user['login'].$user['passwd']);
							$f = fopen(USERS_LP.$filename.".txt", "w");
							fwrite ($f, "Данные для доступа на сайт ".$_SERVER['SERVER_NAME']."\n\n
Ваш логин: ".$user['login']."\r\n
Ваш пароль: ".$password);
							fclose ($f);
							$message='Ваш логин: '.$user['login'].'<br />
							Пароль: '.$password.'<br />
							<a href="/'.USERS_LP.$filename.'.txt">Сохраните файл с логином и паролем</a><br />
							Для входа введите полученные логин и пароль <a href="/" title="Главная страница">на главной странице</a>';
							include TPL_CMS_USER."form_reg_end.php";
							}
						} else {
							$_TPL['ERROR'] = $tmp['error'];
							$_TPL['ROW']=$tmp['data'];
							include TPL_CMS_USER."form_reg.php";
						}
						
                    }    
                break;
				
                case($action=='reg'):
                    if(USER_ID>1) {
                     //   $_TPL['ROW']=$this->GetUserData(SESSION_ID);
                     //   $_TPL['ROW']['login_1']=$_TPL['ROW']['login'];
					 header('Location: /');
                    }
					$TPL['SUBPROGRAM'] = ManagerForms::listSubprogram();
                    include TPL_CMS_USER."form_reg.php";
                break;
				
				
				 case($action=='activate' && $_GET['login'] && $_GET['code']):
					$tmp=$this->prepareActivateDataNew($_GET);
					$_TPL['ERROR'][] = $tmp;
					if (!count($tmp['error'])){
					// Активируем и в личный кабинет его
						$sql=sql_placeholder('update ?#FK_USER set state="active" where login=? ', $_GET['login']);
						$this->db->query($sql);
					$_TPL['ERROR'][] = "Ваша учетная запись активирована";	
					//	Header("HTTP/1.1 301 Moved Permanently" );
					//	Header("Location: /lk" );
					}
					include TPL_CMS_USER."activate.php";
                break;
				
				/*case($action=='form1'):
                    include TPL_CMS_USER."form1.php";
                break;
				case($action=='get_mr'):
					$pp_id = @intval($_GET['pp_id']);
					
					$sql=sql_placeholder('select * from measure where subprogram_id=?', $pp_id);
					$r=$this->db->_array_data($sql);
					
					if ($r) {
						$measure = array();
						$measure[] = array('id'=>'0', 'title'=>'Выберите мероприятие');
						foreach ($r as $row) {
							$measure[] = array('id'=>$row['id'], 'title'=>$row['title']);
						}    
						$result = array('type'=>'success', 'measure'=>$measure);
					} else {
						$result = array('type'=>'error');
					}
					print json_encode($result);
				break;
				case($action=='form2'):
                    include TPL_CMS_USER."form2.php";
                break;		*/
				
            }
		unset($_SESSION['captcha_keystring']);
        }


}

?>