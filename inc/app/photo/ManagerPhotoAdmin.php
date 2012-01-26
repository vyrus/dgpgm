<?

class ManagerPhotoAdmin extends ManagerPhoto {
      var $limit=350;
      var $user=false;


      function ManagerPhotoAdmin(&$db,  &$user){
         $this->db=$db;
         $this->user=$user;
           }

function work(){
  global $_MODCONFIG, $_TPL;

switch(1){
  default:
    $tmp=$this->ListCategory(CURRENT_PAGE, $this->limit);
    $_TPL['ERROR']=$tmp['error'];
    $_TPL['LISTROW']=$tmp['data'];
    include TPL_ADMIN_PHOTO."list_category.php";
  break;
  case($_GET['action']=='addcategory'):
     if (!empty($_POST['title'])){
          $_POST['user_id']=USER_ID;
          $_TPL['ERROR']=$this->addCategory($_POST);
          if (!count($_TPL['ERROR'])){
             header('Location: ?mod=photo');
             exit;
          }
      }

      if(!empty($_POST)){ 
        $_TPL['ROW']=array_map("htmlspecialchars",$_POST);
      }
      
      include TPL_ADMIN_PHOTO."form_category.php";
  break;
  case($_GET['action']=='editcategory' && $_GET['id']):
      if (!empty($_POST['title'])){
           $_TPL['ERROR']=$this->editCategory($_GET['id'], $_POST);
           if (!count($_TPL['ERROR'])){
             header('Location: ?mod=photo');
             exit;
            }
      }
  
      $_TPL['ROW']=$this->viewCategory($_GET['id']);
      include TPL_ADMIN_PHOTO."form_category.php";
  break;
  case($_GET['action']=='delcategory' && $_GET['id']):
      $this->delCategory($_GET['id'], $_POST);
      $tmp=$this->ListCategory(CURRENT_PAGE, $this->limit);
      $_TPL['ERROR']=$tmp['error'];
      $_TPL['LISTROW']=$tmp['data'];

      include TPL_ADMIN_PHOTO."list_category.php";
  break;
  case($_GET['action']=='listphoto'):
      $tmp=$this->ListPhoto(CURRENT_PAGE, $this->limit,$_GET['id']);
      $_TPL['LISTROW']=$tmp['data'];
      include TPL_ADMIN_PHOTO."list_photo.php";
  break;
  case($_GET['action']=='addphoto'):
      if (!empty($_POST)){
         $_POST['user_id']=USER_ID;
          $tmp=$this->addPhoto($_POST);
		
         if (count($tmp)){
            header('Location: ?mod=photo&action=listphoto&id='.$_GET['parent_id']);
          exit;
         }
      }

     if(!empty($_POST)){
         $_TPL['ROW']=array_map("htmlspecialchars",$_POST);
     }

     include TPL_ADMIN_PHOTO."add_photo.php";
  break;
  
   case($_GET['action']=='addphotomuch'):
      if (!empty($_POST)){
		//foreach($_POST['name'] as $i=>$vl){
         //$_POST['user_id']=USER_ID;
         $_TPL['ERROR']=$this->addPhotoMuch();
         if (!count($_TPL['ERROR'])){
            header('Location: ?mod=photo&action=listphoto&id='.$_GET['parent_id']);
          exit;
         }

	//	}  forreach
	}

     /*if(!empty($_POST)){
         $_TPL['ROW']=array_map("htmlspecialchars",$_POST);
     }*/

     include TPL_ADMIN_PHOTO."add_much_photo.php";
  break;
  
  case($_GET['action']=='editphoto' && !empty($_GET['photo_id']) && !empty($_POST['name'])):

        $_POST['user_id']=USER_ID;
        $_TPL['ERROR']=$this->editPhoto($_GET['photo_id'],$_POST);
        if (!count($_TPL['ERROR'])){
            // header('Location: ?mod=photo&action=listphoto&id='.$_POST['parent_id']);
            // exit;
            $_TPL['ERROR'][]="Данные Именены";
        }
      
  case($_GET['action']=='editphoto' && !empty($_GET['photo_id'])):
      $_TPL['ROW']=$this->viewPhoto($_GET['photo_id']);
      include TPL_ADMIN_PHOTO."edit_photo.php";
   break;
   case($_GET['action']=='delphoto'):
      $_TPL['ERROR']=$this->delPhoto($_GET['photo_id'], $_GET['parent_id']);
      if (!count($_TPL['ERROR'])){
           header('Location: ?mod=photo&action=listphoto&id='.$_GET['parent_id']);
           exit;
      }
    break;
 }
}




function addPhotoMuch(){

        $er_msg=array();
        $width_1=800;
        $height_1=600;
        $width_2=600;
        $height_2=450;
        $width_3=150;
        $height_3=100;

        #полный путь до папки де будут фотки

        $dir=DIR_GALLERY;

        #$_POST[name][0] имя
        #$_POST[pos][0] позиция
        #$_POST[desc][0] описание


        if(!empty($_FILES['photo']['name'])){
			
        foreach($_FILES['photo']['name'] as $kl=>$vl){
            if ($vl && file_exists($_FILES['photo']["tmp_name"][$kl])){

            #добавляю запись в базу и получаю ид

             $row = array();
             $row['title'] = $_POST['name'][$kl];
			 $row['parent_id'] = $_POST['parent_id'][$kl];
             $row['num'] = $_POST['num'][$kl];
             $row['content'] = $_POST['content'][$kl];
             $row['id'] = $this->db->addrow(FK_PHOTO, $row);

		 
			$dirs=$dir.$row['parent_id'].'/';
			if (!file_exists($dirs)){
				mkdir($dirs, 0777);
			}
              $src=$dirs.'/'.$row['id'].'.jpg';
              $_src=$dirs.'/_'.$row['id'].'.jpg';
              $__src=$dirs.'/__'.$row['id'].'.jpg';

            # переношу файл в новую директорию
              $res=rename($_FILES['photo']["tmp_name"][$kl], $src);
              #изменяю себя если больше чем надо было
              $this->resize_image($width_1, $height_1,   $src, $src );
              #Делаю средний размер
              $this->resize_image($width_2, $height_2,   $src, $_src );
              #Делаю маленький размер
              $this->resize_image($width_3, $height_3,   $_src, $__src );

            }
        }
       }
     return $er_msg;
    }


function addCategory($row){
    $error=array();
    /*$access= $this->user->GetListAccess(USER_ID, PHOTO_CATALOG_ID);

    if (0 && empty($access['photo'])){
        $error[]='Нет доступа для добавления категории';
    }else{*/
     $row=$this->prepare_category($row);
     $r=array(
          'title'=>$row['title'],
          'num'=>$row['num'],
          'note'=>$row['note']
         );

     $id = $this->db->addrow(FK_PHOTO_CATEGORY, $r); // вот тут была ошибка

    if (!empty($_FILES['photo']['name'])){
    $filename=$id.".jpg";
    $dir=DIR_GALLERY;
    $err=$this->upload_jpg('photo', $filename, $dir);


    if ($err==-1){
       $error[]='Файл не *.jpg <br> <a href=# onclick="history.back();">Назад</a> ';
       return  $error;
    }elseif ($err<1){
       $error[]='Возникла ошибка сервера <br> <a href=# onclick="history.back();">Назад</a> ';
       return  $error;
    }
    chmod('./'.$dir.$filename, 0777);
    $this->resize_image(120, 120,  $dir.$filename, $dir.$filename );

    }
        /* }*/
           return  $error;
     }
      function ViewCategory($id){
          $sql=sql_placeholder('select * from ?#FK_PHOTO_CATEGORY as p  where   p.category_id=?', $id);
          return $this->db->select_row($sql);

     }
    function editCategory($id , $row){
         $error=array();
           //$access= $this->user->GetListAccess(USER_ID, PHOTO_CATALOG_ID);


           /*if (0 || empty($access['photo'])){

             $error[]='Нет доступа для редактирования категории';
           }else{*/

            $row=$this->prepare_category($row);


            $r=array(
                         'title'=>$row['title'],
                         'num'=>$row['num'],
                         'note'=>$row['note'],
                         );

               $sql=sql_placeholder('update ?#FK_PHOTO_CATEGORY set ?% where category_id=?', $r, $id );


               $this->db->query($sql);
            if (!empty($_FILES['photo']['name'])){
                    $filename=$id.".jpg";
                    $dir=DIR_GALLERY;
                    $err=$this->upload_jpg('photo', $filename, $dir);

                    if ($err==-1){
                         $error[]='Файл не *.jpg <br> <a href=# onclick="history.back();">Назад</a> ';
                         return  $error;
                   }elseif ($err<1){
                          $error[]='Возникла ошибка сервера <br> <a href=# onclick="history.back();">Назад</a> ';
                             return  $error;
                     }
                    $this->resize_image(120, 120,  $dir.$filename, $dir.$filename );
             }

         /*}*/

         return $error;

    }

function delCategory($id){
   //$access= $this->user->GetListAccess(USER_ID, PHOTO_CATALOG_ID);
   $error=array();

   /*if (empty($access['photo'])){
      $error[]='Нет доступа для удаления категории';
   }else{*/
    $dir=DIR_GALLERY;
    $dir=$dir.$id;
    if (is_file($dir.".jpg")) unlink($dir.".jpg") ;
    if (is_dir($dir)) $this->full_del_dir($dir);

    $sql=sql_placeholder('delete from ?#FK_PHOTO_CATEGORY where category_id=? limit 1 ', $id);
    $this->db->query($sql);

    $sql=sql_placeholder('delete from ?#FK_PHOTO where parent_id=?', $id);
    $this->db->query($sql);
  /* }*/
   return $error;
}

function full_del_dir($directory){
  $dir = opendir($directory);
  while(($file = readdir($dir)))
  {
     if ( is_dir ($directory."/".$file) &&
             ($file != ".") && ($file != ".."))
    {
      $this->full_del_dir ($directory."/".$file);
    }

    if ( is_file ($directory."/".$file) && file_exists($directory."/".$file))
    {
      unlink ($directory."/".$file);
    }
    else   continue;
  }
  closedir ($dir);
  rmdir ($directory);

  }

     function prepare_category($row){
            $r['title']=(empty($row['title']))?'':$row['title'];
            $r['num']=(empty($row['num']))?'':$row['num'];
         $r['note']=(empty($row['note']))?'':$row['note'];
        return $r;
    }



   function editPhoto($id, $row){

         $error=array();
           //$access= $this->user->GetListAccess(USER_ID, PHOTO_CATALOG_ID);


   $sql=sql_placeholder('select p.*  from ?#FK_PHOTO as p  where   p.photo_id =? ', $id);
   $photo = $this->db->select_row($sql);

           /*if (empty($access['photo'])){
             $error[]='Нет доступа для редактирования фото';
           }else{*/

           $row=$this->prepare_photo($row);
           $r=array(
              'title'=>$row['name'],
              'content'=>$row['content'],
              'num'=>$row['num'],
           );

               $sql=sql_placeholder('update ?#FK_PHOTO set ?% where photo_id=?', $r, $id );
               $this->db->query($sql);

          if (!empty($_FILES['photo']['name'])){
                        $dir='./'.DIR_GALLERY;
                        $dir=$dir.$photo['parent_id'].'/';
                        $filename=$id.".jpg";
                        $err=$this->upload_jpg('photo', $filename, $dir);
                        
                        if ($err==-1){
                                $error[]='Файл не *.jpg <br> <a href=# onclick="history.back();">Назад</a> ';
                                return  $error;
                        }elseif ($err<1){
                                $error[]='Возникла ошибка сервера <br> <a href=# onclick="history.back();">Назад</a> ';
                                return  $error;
                        }
                        
                        list($x,$y) = explode("x", $_MODCONFIG['photo']['size_middle']);
                        $this->resize_image($x, $y,  $dir.$filename, $dir."_".$filename );
                        list($x,$y) = explode("x", $_MODCONFIG['photo']['size_small']);
                        $this->resize_image($x, $y,   $dir."_".$filename, $dir."__".$filename );
                        chmod ($dir.$filename, 0777);
                        chmod ($dir."_".$filename, 0777);
                        chmod ($dir."__".$filename, 0777);

         }

         return $error;

    /*}*/
}

  function delPhoto($id,$parent_id){
   $error=array();

  $dir=''.DIR_GALLERY;

  $dir=$dir.$parent_id.'/';
  $filename1="__".$id.".jpg";
  $filename2="_".$id.".jpg";
  $filename3=$id.".jpg";
  $filename1=$dir.$filename1;
  $filename2=$dir.$filename2;
  $filename3=$dir.$filename3;
         // $access= $this->user->GetListAccess(USER_ID, PHOTO_CATALOG_ID);


           /*if (empty($access['photo'])){
             $error[]='Нет доступа для удаления фото';
           }else{*/
                  if ( is_file ($filename1) && file_exists($filename1))   unlink($filename1);
          if ( is_file ($filename2) && file_exists($filename2)) unlink($filename2);
                if ( is_file ($filename3) && file_exists($filename3)) unlink($filename3);
          $sql=sql_placeholder('delete from ?#FK_PHOTO where photo_id=? limit 1 ', $id);
           $this->db->query($sql);
		   
        //    $sql=sql_placeholder('delete from ?#FK_CATALOGID where id=? limit 1 ', $id);
        //   $this->db->query($sql);
            return $error;
          /*}*/

     }



}
?>