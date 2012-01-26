<?

include_once $_SERVER['DOCUMENT_ROOT'].'/inc/app/picbilder/MiniPicBilder.php';

class ManagerNewsAdmin extends ManagerNews {
    var $limit=20;
    var $user=false;

    function ManagerNewsAdmin(&$db,  $user){
        $this->db=$db;
        $this->user=$user;
    }


    function delNews($id){
        $r=$this->ViewNews($id);
        if($r){
          $sql=sql_placeholder('delete from ?#FK_CATALOGID where id=?', $id);
          $this->db->query($sql);
          $sql=sql_placeholder('delete from ?#FK_NEWS where news_id=?', $id);
          $this->db->query($sql);

        }
    }

    function listNews($page, $limit){
         $error=array();
         $r=array();
         $cnt=0;
             $sql=sql_placeholder('
             select SQL_CALC_FOUND_ROWS c.name , c.id, n.short_news, n.view from ?#FK_NEWS as n, ?#FK_CATALOGID as c
              where  c.id=n.news_id and parent_id=?     order by date_news desc  limit ?, ? ',NEWS_CATALOG_ID, ($page-1)*$limit, $limit);
               $r=$this->db->_array_data($sql);
               $cnt=$this->db->getFoundRow();

         return array('error'=>$error, 'data'=>$r, 'cnt'=>$cnt);
    }


    function addNews($row){

         $error=array();
         //$access= $this->user->GetListAccess(USER_ID, 0);


         if (0){
             $error[]='Нет доступа для добавления новостей';
         }else{
           $row=$this->prepare_news($row);
           $r=array( 'name'=>$row['name'],
                     'action'=>'news' ,
                     'addtime'=>time(),
                     'parent_id'=>NEWS_CATALOG_ID,
                     'list_parent'=>'0;'.NEWS_CATALOG_ID.';',
                     );

            $id=$this->db->addrow(FK_CATALOGID, $r);




            $r=array('news_id'=>$id,
                      'short_news'=>$row['short_news'],
                      'full_news'=>$row['full_news'],
                      'user_id'=>USER_ID,
//                     'city_id'=>(empty($row['city_id'])?0:$row['city_id']),
                     'author'=>$row['author'],
                     'date_news'=>$row['date_news'],
                        'url'=>$row['url'],
//						'view'=>$row['view'], //Zlyuk
                      );

              $this->db->addrow(FK_NEWS, $r);
            if (!empty($_FILES['photo']['name'])){
              $error = array_merge($error, $this->addPicture($id));
            }
         }
         return  $error;
    }

function addPicture($id){
    global $_MODCONFIG;
    $pic = new MiniPicBilder(); 
    $filename=$id.'.jpg';
    $dir=NEWS_PIC;
    $err=$pic->upload_jpg('photo', $filename, $dir);
    
    if (!count($err)){
      list($x,$y) = explode("x", $_MODCONFIG['news']['photo_small']);
      $pic->resize_image($x, $y,  $dir.$filename, $dir.$filename );
      chmod ($dir.$filename, 0777);
    }
    return $err;
}

    function editNews($id , $row){

         $error=array();
           $row=$this->prepare_news($row);
           $r=array( 'name'=>$row['name'],
                     'action'=>'news' ,
                     'addtime'=>time(),
                     'parent_id'=>NEWS_CATALOG_ID,
                     'list_parent'=>'0;'.NEWS_CATALOG_ID.';',
                     );

            $sql=sql_placeholder('update ?#FK_CATALOGID set ?% where id=?', $r, $id );
            $this->db->query($sql);

            $r=array('news_id'=>$id,
                      'short_news'=>$row['short_news'],
                      'full_news'=>$row['full_news'],
                      'user_id'=>USER_ID,
                      'author'=>$row['author'],
                      'date_news'=>$row['date_news'],
	//				  'view'=>$row['view'], //Zlyuk
                      );

            $sql=sql_placeholder('update ?#FK_NEWS set ?% where news_id=?', $r, $id );
            if (!$this->db->query($sql)){
                $error[]='Проблема добавления полной новости';
            }

       if (!empty($_FILES['photo'])){
              $error = array_merge($error, $this->addPicture($id));
            }
         

         return $error;

    }

    function ViewNews($id){
          $sql=sql_placeholder('select n.*, c.name from ?#FK_NEWS as n , ?#FK_CATALOGID  as c where   c.id=?  and  c.id=n.news_id ', $id);
          return $this->db->select_row($sql);

    }

    function prepare_news($row){
          $r['name']=(empty($row['name']))?'':$row['name'];
          $r['short_news']=(empty($row['short_news']))?'':$row['short_news'];
          $r['full_news']=(empty($row['full_news']))?'':$row['full_news'];
        $r['date_news']=(empty($row['date_news']))?'':$row['date_news'];
        $r['user_id']=(empty($row['user_id']))?'':$row['user_id'];
        
        $r['author']=(empty($row['author']))?'':$row['author'];
        $r['url']=(empty($row['url']))?'':$row['url'];
		$r['view']=(empty($row['view']))?'':$row['view'];

        return $r;


    }



function work(){
       global $_MODCONFIG;
      // $_TPL['CITY']= $this->user->listCityAccess(USER_ID);
       switch(1){
          default:
            $tmp=$this->ListNews(CURRENT_PAGE, $this->limit);
            $_TPL['ERROR']=$tmp['error'];
            $_TPL['LISTROW']=$tmp['data'];
            $_TPL['CNTPAGE']=ceil($tmp['cnt']/$this->limit);
            include TPL_ADMIN_NEWS."list_news.php";
          break;
          case($_GET['action']=='addnews'):
             if (!empty($_POST['name'])){
                  $_POST['user_id']=USER_ID;
                  $_TPL['ERROR']=$this->addNews($_POST);
                  if (!count($_TPL['ERROR'])){
                      header('Location: ?mod=news');
                      exit;
                  }
                @ $_TPL['ROW']=array_map("htmlspecialchars",$_POST);
             }

            
               include TPL_ADMIN_NEWS."form_news.php";
          break;
          case($_GET['action']=='editnews' && $_GET['id'] && isset($_GET['delphoto'])):
            if (file_exists(NEWS_PIC.$_GET['id'].'.jpg')){
                unlink(NEWS_PIC.$_GET['id'].'.jpg');
            }
            header('Location: ?mod=news');
          break;
          case($_GET['action']=='editnews' && $_GET['id']):
             if (!empty($_POST['name'])){
               $_TPL['ERROR']=$this->editNews($_GET['id'], $_POST);
               if(!count($_TPL['ERROR'])){
               $_TPL['ERROR'][]="<script>alert('Данные сохранены')</script>"; 
               header('Location: ?mod=news');
               }
             }

             $_TPL['ROW']=$this->viewNews($_GET['id']);
             include TPL_ADMIN_NEWS."form_news.php";
          break;
          case($_GET['action']=='delnews' && $_GET['id']):
             $this->delNews($_GET['id']);
             header('Location: ?mod=news');
                      exit;
          break;
        }
    }



}
?>