<?

class ManagerPhoto{
  var $db=false;
  var $limit=30;
  function ManagerPhoto(&$db){
      $this->db=$db;
  }

function ListCategory($page, $limit){
   $r=array();
   $cnt=0;
   $sql=sql_placeholder('
      select SQL_CALC_FOUND_ROWS  *  from ?#FK_PHOTO_CATEGORY as p 
      where 1   order by p.num limit ?, ?      ', ($page-1)*$limit, $limit);
   $r=$this->db->_array_data($sql);
   $cnt=$this->db->getFoundRow();
   return array( 'data'=>$r, 'cnt'=>$cnt);
}


function ListPhoto($page, $limit,$pid){
  $sql_pid =  ($pid)?' and parent_id = '.intval($pid):'';
   $sql=sql_placeholder('
   select SQL_CALC_FOUND_ROWS   p.parent_id, p.photo_id, p.title, p. cntview, p.rating  from ?#FK_PHOTO as p
   where  1 '.$sql_pid.'  limit ?, ?      ', ($page-1)*$limit, $limit);
   $r=$this->db->_array_data($sql);
   $cnt=$this->db->getFoundRow();
     return array( 'data'=>$r, 'cnt'=>$cnt);
     }


function viewPhoto($id){
   $sql=sql_placeholder('select p.*  from ?#FK_PHOTO as p  where   p.photo_id =? ', $id);
   $row = $this->db->select_row($sql);

   $tmp = $this->backPhoto($id, $row['parent_id'], 1);
   $row['BACK'] = (empty($tmp))?0:$tmp[0]['photo_id'];
   
   $tmp = $this->nextPhoto($id, $row['parent_id'], 1);
   
   $row['NEXT'] = (empty($tmp))?0:$tmp[0]['photo_id'];
   return  $row;
}


function backPhoto($id, $pid, $limit){
  $sql=sql_placeholder('select p.photo_id,p.title, p.parent_id  from ?#FK_PHOTO as p  where   p.photo_id <? and parent_id=?  order by photo_id desc limit ?', $id, $pid, $limit);
  $result =  $this->db->_array_data($sql);
  $cnt = count($result);
  if ($cnt<$limit){
    $sql=sql_placeholder('select p.photo_id,p.title, p.parent_id  from ?#FK_PHOTO as p  where   p.photo_id >? and parent_id=?  order by photo_id desc limit ?', $id, $pid, $limit-$cnt);
    $result = array_merge($result, $this->db->_array_data($sql));
  }
  return $result;

}

function nextPhoto($id, $pid, $limit){
  $sql=sql_placeholder('select p.photo_id,p.title, p.parent_id  from ?#FK_PHOTO as p  where   p.photo_id >? and parent_id=?  order by photo_id limit ?', $id, $pid, $limit);
  $result =  $this->db->_array_data($sql);
  $cnt = count($result);
  if ($cnt<$limit){
    $sql=sql_placeholder('select p.photo_id,p.title, p.parent_id  from ?#FK_PHOTO as p  where   p.photo_id <? and parent_id=?  order by photo_id  limit ?', $id, $pid, $limit-$cnt);
    $result = array_merge($result, $this->db->_array_data($sql));
  }
  return $result;


}



function addPhoto($row){
   global $_MODCONFIG;
   $dir=DIR_GALLERY;
   $error=array();


    if(empty($_FILES['photo']['name'])){
       $error[]='Вы не выбрали фото <br> <a href=# onclick="history.back();">Назад</a> ';
    }else{
       $row=$this->prepare_photo($row);
       if (!file_exists($dir.$row['parent_id'])){
         mkdir($dir.$row['parent_id'], 0777);
       }

     $r=array(
         'parent_id'=>$row['parent_id'],
         'title'=>$row['name'],
         'content'=>$row['content'],
         'num'=>$row['num'],
     );

     $id = $this->db->addrow(FK_PHOTO, $r);
     $dir=$dir.$row['parent_id'].'/';
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
        if (count($error)){
            $sql=sql_placeholder('delete from ?#FK_PHOTO  where photo_id = ?', $id);
            $this->db->query($sql); 
            $id=0;
        }
           return  array('ERROR'=>$error, 'id'=>$id);
     }


function prepare_photo($row){
        $r['parent_id']=(empty($row['parent_id']))?'':$row['parent_id'];
          $r['photo_id']=(empty($row['photo_id']))?'':$row['photo_id'];
            $r['name']=(empty($row['name']))?'':$row['name'];
          $r['num']=(empty($row['num']))?'':$row['num'];
            $r['content']=(empty($row['content']))?'':$row['content'];
            $r['count']=(empty($row['count']))?'':$row['count'];
        $r['rating']=(empty($row['rating']))?'':$row['rating'];
        return $r;
     }




function resize_image($max_width, $max_height, $source, $destination) {


         $w_h=GetImageSize($source);
         $width=$w_h[0];
         $height=$w_h[1];

         $x_ratio = $max_width / $width;
         $y_ratio = $max_height / $height;

         if ( ($width <= $max_width) && ($height <= $max_height) ) {
           $tn_width = $width;
           $tn_height = $height;
         }
         else if (($x_ratio * $height) < $max_height) {
           $tn_height = ceil($x_ratio * $height);
           $tn_width = $max_width;
         }
         else {
           $tn_width = ceil($y_ratio * $width);
           $tn_height = $max_height;
         }

      $width=$tn_width ;
      $height= $tn_height;


      $src = imagecreatefromjpeg($source);
      $img = imagecreatetruecolor($width, $height);
      imagecopyresampled($img, $src, 0, 0, 0, 0, $width, $height, imagesx($src),imagesy($src));
//    @unlink($destination);
      imageJPEG($img, $destination);
       imagedestroy($img);
       imagedestroy($src);

}




function upload_jpg($fname, $filename, $dir)
{

 if (!empty($_FILES[$fname]) && is_uploaded_file($_FILES[$fname]["tmp_name"]))
    {

            $type=$_FILES[$fname]['type'];
            $allowed_types = array("image/jpeg","image/pjpeg" );
            $size=filesize($_FILES[$fname]['tmp_name']);
            if($size>5307000) return -1;


        if ((in_array($type,$allowed_types)))// && $size<40*1024)
           {

                   $res = move_uploaded_file($_FILES[$fname]["tmp_name"], $dir.$filename );
                  // resize_image($tn_width,$tn_height, $dir.$filename, $dir.'_'.$filename );

            if ($res)  return $filename;
            else
            {
                    return 0;
            }
           }
         else
         {

            return 0;
         }
    }
    else
    {
      return 0;
    }
}


function photoRND($limit, $a=false){
    $sql=sql_placeholder('select p.*  from ?#FK_PHOTO as p  order by rand() limit ?', $limit);
    $row = $this->db->select_row($sql);
    
    if ($a){
    $tmp = $this->backPhoto($row['photo_id'], $row['parent_id'], 1);
    $row['BACK'] = (empty($tmp))?0:$tmp[0]['photo_id'];
    $tmp = $this->nextPhoto($row['photo_id'], $row['parent_id'], 1);
    $row['NEXT'] = (empty($tmp))?0:$tmp[0]['photo_id'];
    }
    return  $row;

}

function photoRand($limit){
    $sql=sql_placeholder('select p.* from ?#FK_PHOTO as p order by rand() limit ?', $limit);
    $row = $this->db->_array_data($sql);
    return  $row;
}

function viewCategory($id){
  $sql  = sql_placeholder('   select SQL_CALC_FOUND_ROWS  *  from ?#FK_PHOTO_CATEGORY as p 
      where category_id = ?  ', $id);
      return $this->db->select_row($sql);
  
}

  function work(){
   global $_MODCONFIG, $_TPL;
   $id = $_GET['id'] =(empty($_GET['id']) || abs(intval($_GET['id']))!=$_GET['id'])?0:intval($_GET['id']);
   $tmp=$this->ListCategory(1, 100);
   $_TPL['CATEGORY'] = $tmp['data'];
   $_TPL['RIGHT_BLOCK']=TPL_PHOTO.'right_block.php';

    switch(1){
       default:
         $_TPL['LISTROW']=$tmp['data'];
         include TPL_PHOTO."list_category.php";
       break;
       case($_GET['action']=='listphoto'):
         if (empty($id)) $id = 3; 
         $tmp=$this->ListPhoto(CURRENT_PAGE, $this->limit,$id);
         $_TPL['LISTROW']=$tmp['data'];
        $url="/photo".$_TPL['LISTROW'][0]['parent_id']."_page1";
        $_TPL['PAGES_LIST']=pages_list_uri($tmp['cnt'], $this->limit,$url,CURRENT_PAGE);
        
        $_TPL['ROW'] = $this->viewCategory($id);
        $_TPL['PAGE_TITLE'] = $_TPL['TITLE'][]=$_TPL['ROW']['title'];
         include TPL_PHOTO."list_photo.php";
       break;
       case($_GET['action']=='viewphoto' && $id):
         $_TPL['ROW']=$this->viewPhoto($id );
#$tmp=$this->ListPhoto(CURRENT_PAGE, $this->limit,$_TPL['ROW']['parent_id']);
         
         $_TPL['LISTPHOTO']=array_merge($this->backPhoto($id, $_TPL['ROW']['parent_id'],3), array($_TPL['ROW']),$this->nextPhoto($id, $_TPL['ROW']['parent_id'],3) );
         $_TPL['TITLE'][]='Фото ';
         $_TPL['TITLE'][]=$_TPL['PAGE_TITLE']=$_TPL['ROW']['title'];
         include TPL_PHOTO."view_photo.php";
       break;
       case($_GET['action']=='addphoto'):
         if (!empty($_POST['name'])){
           $_POST['user_id']=USER_ID;
           $tmp = $this->addPhoto($_POST);
           $_TPL['ERROR'] = $tmp['ERROR'];
           if (!count($_TPL['ERROR'])){
             header('Location: /photo_'.$tmp['id'].'/');
             exit;
           }
         }
         @ $_TPL['ROW']=array_map("htmlspecialchars",$_POST);
         include TPL_PHOTO."form_photo.php";
        break;
        /*case($_GET['action']=='widget'):
          if (!$id){
          $_TPL['ROW'] =    $this->photoRND(1, true);
          }else{
            $_TPL['ROW']=$this->viewPhoto($id);
          }
          include TPL_PHOTO."photo_widget.php";
        break;*/
        case( $_GET['action']== 'scrolln' && $id):
          $_TPL['ROW'] = $this->viewPhoto($id);
          $tmp = $this->nextPhoto($id, $_TPL['ROW']['parent_id'],3);
          echo json_encode($tmp);
        break;
     }
}




}


?>