<?
class ManagerArticleAdmin extends ManagerArticle {
	var $limit=20;



function ReBildTree(){
$id=1;
 	$this->db->query('TRUNCATE TABLE '.FK_TREE);
	$this->bild_tree2($id);
}

function listArticle($id){
    $sql=sql_placeholder('select * from ?#FK_CATALOGID as c , ?#FK_CONTENT as a where a.id=c.id and c.parent_id=? order by c.num',$id);
	return $this->db->_array_data($sql);
}

function getNexNum($id){
 	$sql=sql_placeholder('select max(num)+10 from ?#FK_CATALOGID as c where c.parent_id=? ', $id);
 	return $this->db->select_row($sql);
}

   function addArticle($pid, $r){
	   $error=array();
	   $id=0;
       $sql=sql_placeholder('select * from ?#FK_CATALOGID as c where id=?', $pid);
       $parent_row=$this->db->select_row($sql);
       if($parent_row){
         $row=array(
         	'name'=>$r['name'],
			'num'=>(empty($r['num'])?$this->getNexNum($pid):intval($r['num'])),
			'parent_id'=>$pid,
			'list_parent'=>$parent_row['list_parent'].';'.$pid,
			'addtime'=>time(),
			'state'=>(empty($r['state'])?0:1),
			'action'=>'article',
                        'seo_title'=>(empty($r['seo_title']))?'':$r['seo_title'],
                        'keywords'=>(empty($r['keywords']))?'':$r['keywords'],
         	);
         $id=$this->db->addrow(FK_CATALOGID,  $row);
         $row=array(
                'id'=>$id,
                'content'=>$r['content'],
                'short_note'=>$r['short_note'],
                'date_article'=>((preg_match("#\d{4}\-\d{1,2}\-\d{1,2} +\d{1,2}:\d{1,2}:\d{1,2}#",$r['date_article']))?$r['date_article']:date("Y-m-d H:i:s", time())),
                'view'=>((!empty($r['view']) && $r['view']=='list' )?'list':'view'),

         		);
		 //$id=
		 $this->db->addrow(FK_CONTENT,  $row);
       }else{
       	$error[]='Невозможно добавить статью родительски каталог не сушествует';
       	echo '<div style="color:red;"> error </div>';
       }
       return  $id; //$error;
   }



   function viewArticle($id){
  		$sql=sql_placeholder('select * from ?#FK_CATALOGID as c left join ?#FK_CONTENT as a on (a.id=c.id) where  c.id=? and c.action="article"', $id);
  		return $this->db->select_row($sql);
   }

   function listParent($list_parent){
     $a=explode(';', $list_parent);
     if (count($a)){
     	 $sql=sql_placeholder('select * from ?#FK_CATALOGID as c where id in (?@) and id>1', $a);
         return  $this->db->_array_data($sql);
     }
         return array();
   }

   function move($id, $pid){
        $sql=sql_placeholder('update ?#FK_CATALOGID as c set parent_id= ? where id=?', $pid, $id);
        $this->db->query($sql);
   }

	function editArticle($id, $r){
		print_r($r);
        $error=array();
 		$row=$this->viewArticle($id);
        if (is_array($row)){
           $a=array(
           	'name'=>$r['name'],
           	'state'=>empty($r['state'])?0:1,
			'num'=>(empty($r['num'])?$this->getNexNum($pid):intval($r['num'])),
                    'seo_title'=>(empty($r['seo_title']))?'':$r['seo_title'],
                    'keywords'=>(empty($r['keywords']))?'':$r['keywords'],
           	);

			$sql=sql_placeholder('update ?#FK_CATALOGID set ?% where id=? ', $a, $id);

			$this->db->query($sql);


        	$a=array(
        	  'content'=>$r['content'],
        	  'short_note'=>$r['short_note'],
        	  'date_article'=>((preg_match("#\d{4}\-\d{1,2}\-\d{1,2} +\d{1,2}:\d{1,2}:\d{1,2}#is",$r['date_article']))?$r['date_article']:date("Y-m-d H:i:s", time())),
              'view'=>((!empty($r['view']) && $r['view']=='list' )?'list':'view'),

        		);


            $sql=sql_placeholder('update ?#FK_CONTENT set ?% where id=? ', $a, $id);
            $this->db->query($sql);


        } else{

            $error[]='Статья не найдена';
        }

         return $error;
	}


    function listChildren($id){
    	$a=array();
        $sql=sql_placeholder('select id from ?#FK_CATALOGID where parent_id=?', $id);
        $r=$this->db->_array_data($sql);
        foreach($r as $row){
            $a[]=$row['id'];
            $a = array_merge($a, $this->listChildren($row['id']));
        }

           return $a;
    }


    function delArticle($id){
    	$a = $this->listChildren($id);
    	$a[]=$id;
    	$sql=sql_placeholder('delete from ?#FK_CATALOGID where id in (?@)' , $a);
    	$this->db->query($sql);
    	$sql=sql_placeholder('delete from ?#FK_CONTENT where id in (?@)' , $a);
    	$this->db->query($sql);
    }

    function bildTreeMove($id){
        if (empty($this->level)) $this->level=1;
        $r=$this->listArticle($id);
        $text='';
        foreach($r as $row){
           $this->level++;
           $text.="<option value='".$row['id']."'";
		   $text.=($row['view']=='view') ? 'disabled=\'disabled\'' :'';
		   $text.=">";
		   $text.=str_repeat("&nbsp;&nbsp;&nbsp;", $this->level). $row['name']."</option>";
           $text.=$this->bildTreeMove($row['id']);
            $this->level--;
        }
        return $text; 
    }

function work(){
     global $_MODCONFIG, $_TPL;
     $action=$_GET['action'];
     $id=$_GET['id']=(!$_GET['id'])?ARTICLE_CATALOG_ID:$_GET['id'];

     switch(1){
	     default:
	     case($action=='list'):
	     	$_TPL['LISTROW']=$_TPL['LISTPARENT']=array();
	        $row=$this->viewArticle($id);
	        if (is_array($row)){
	        	$_TPL['LISTPARENT']=$this->listParent($row['list_parent']);
	     		$_TPL['LISTROW']=$this->listArticle($id);

	     	}else{
	     		$_TPL['ERROR'][]='Ошибка: страница не сушествует';
	     	}
		        include  TPL_ADMIN_ARTICLE.'list_article.php';
	     break;
	     case ($action=='add' && $id):
            if (!empty($_POST['name'])){
              $_TPL['ERROR']=$this->addArticle($id, $_POST);
              if (!count($_TPL['ERROR'])){
	            $this->ReBildTree(ARTICLE_CATALOG_ID);
                header('Location: ?mod='.$_GET['mod'].'&action=list&id='.$id);
                exit;
              }
            }
            include TPL_ADMIN_ARTICLE.'form_article.php';
	     break;
	     case($action=='edit' && $id):

      		$_TPL['ROW']=$this->viewArticle($id);
      		if (!$_TPL['ROW']){
      			$_TPL['ERROR'][]='Ошибка страница не сушествует';
      		}else{
      			if (!empty($_POST['name'])){
      				$_TPL['ERROR']=$this->editArticle($id, $_POST);
      				if (!count($_TPL['ERROR'])){
			            $this->ReBildTree(ARTICLE_CATALOG_ID);
                      header('Location: ?mod='.$_GET['mod'].'&action=list&id='.$_TPL['ROW']['parent_id']);
                      exit;
      				}
      			}

      		}
            include TPL_ADMIN_ARTICLE.'form_article.php';
	     break;
	     case($action == 'del' && $id):
              $row=$this->viewArticle($id);
              if (is_array($row)){
      		  	$this->delArticle($id);
         	            $this->ReBildTree(ARTICLE_CATALOG_ID);
                      header('Location: ?mod='.$_GET['mod'].'&action=list&id='.$row['parent_id']);
                      exit;
      		  }else{
      		  	   $_TPL['ERROR'][]='Статья не найдена';
      		  }

	     break;
             case($action=='move' && $id):
                $_TPL['ROW'] = $this->getCatalogData($id);
                if (isset($_POST['pid']) && intval($_POST['pid'])==$_POST['pid'] ){
                   $this->move($id, $_POST['pid']);
                   header('Location: ?mod=article&action=list&id='.$_POST['pid']); exit;
                }
                $_TPL['OPTION']=$this->bildTreeMove(1);
                include TPL_ADMIN_ARTICLE.'form_move.php';
             break;



     }

   }



}
?>