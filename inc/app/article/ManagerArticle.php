<?
class ManagerArticle{
    var $db=false;
	var $limit=20;
	
    function ManagerArticle(&$db){
            $this->db=$db;
    }

    function listArticle($id){
         $sql=sql_placeholder('
         select c.id, c.name , c.action, c.parent_id, list_parent, date_format(a.date_article, "%d.%m.%Y") as date_article, a.view from ?#FK_CATALOGID as c , ?#FK_CONTENT as a where c.state=1 and a.id=c.id and c.parent_id=? order by c.num asc',
            $id
                       );

          return $this->db->_array_data($sql);
   }

    function listLArticle($id){
         $sql=sql_placeholder('
         select c.id, c.name  from ?#FK_CATALOGID as c , ?#FK_CONTENT as a where c.state=1 and a.id=c.id and c.parent_id=?  and a.view="list" order by c.num',
            $id
                       );
        return $this->db->_array_data($sql);
   }


	function listArticlePage($id, $page, $limit){
         $sql=sql_placeholder('
         select c.id, c.name, a.short_note ,
         date_format(a.date_article, "%H:%i %d-%m-%Y") as date_article
         from ?#FK_CATALOGID as c , ?#FK_CONTENT as a
         where c.state=1 and a.id=c.id and c.parent_id=?
         order by c.num
         limit ?, ?
         ',
            $id, ($page-1)*$limit, $limit);
          return $this->db->_array_data($sql);
    }

function listParentId($id){
        $sql=sql_placeholder('SELECT b.id 
FROM ?#FK_TREE AS a, ?#FK_TREE AS b
WHERE a.id =?
AND a.cleft <= b.cright
AND a.cright >= b.cleft  and a.level>b.level 
ORDER BY b.cleft DESC', $id);
echo $sql."<hr>";

        $r = $this->db->_array_data($sql);
        $a = array();
        foreach($r as $row){
            $a[]=$row['id'];
        }

	echo "<pre>";
	    print_r($a);
	echo "</pre>";

return $a;
    }


function listParentIdOld($id){
        $a=array();
        $sql = sql_placeholder('select parent_id from ?#FK_CATALOGID where id=? ', $id);
        $a[] =$pid=$this->db->select_row($sql);
        if ($pid>1){
        $a = array_merge($a, $this->listParentIdOld($pid));
        }
        return $a;
}
	
function maps($id){
$text="<ul>";
$r=$this->listArticle($id);
foreach($r as $row){
    $text.="<li><a href='/article".$row['id']."' titile='".$row['name']."'>".$row['name']."</a>";
    if ($row['view'] == 'list'){
        $text.=$this->maps($row['id']);
    }
    $text .= "</li>"; 
}
$text.='</ul>';
return $text;
}

   function bildMainMenu($id){

         $menu=array();
      $r=$this->listArticle($id);

      foreach($r as $row){
          $menu[$row['id']]['children']=$this->listArticle($row['id']);
          $menu[$row['id']]['name']=$row['name'];
      }

      return $menu;
   }

function getCatalogData($id){
    $sql=sql_placeholder('select * from ?#FK_CATALOGID where id=?', $id);
    return $this->db->select_row($sql);
}

function bild_tree2($id=1){
    $this->limit=10000;
    $this->level++;
    $arr=$this->listCatalogID($id);
    foreach($arr['data'] as $row){
       $row['level']=$this->level;
       $row['cleft']=++$this->i;
       $this->bild_tree2($row['id']);
       $row['cright']=++$this->i;
       $this->db->addrow(FK_TREE, $row);
       $this->level--;
      }

}


function listCatalogID($pid){
 $a=array();
 $sql=sql_placeholder('select SQL_CALC_FOUND_ROWS id  from ?#FK_CATALOGID as c where c.state=1 and c.parent_id=? order by num', $pid);

 $r=$this->db->_array_data($sql);
 $cnt=$this->db->getFoundRow();
 return array('data'=>$r, 'cnt'=>$cnt);
}


function cntArticle ($id){
$sql = sql_placeholder('select count(*) as cnt from ?#FK_CATALOGID where parent_id=?', $id);
return $this->db->select_row($sql);
}


   function viewArticle($id){
                $sql=sql_placeholder('select * from ?#FK_CATALOGID as c left join ?#FK_CONTENT as a on (a.id=c.id) where  c.id=? and c.action="article" and c.state=1 ', $id);
                return $this->db->select_row($sql);
   }

   function work(){
        global $_TPL;
        $action=$_GET['action'];
        $id=$_GET['id']=(!$_GET['id'])?MAIN_PAGE_ID:$_GET['id'];

$tmp=$this->viewArticle($id);

 	if (!$tmp){
            header('Location: /error404');
            exit;
        }
		
     $_TPL['TITLE'][]=$tmp['name'];
     $_TPL['KEYWORDS']=$tmp['keywords'];
     $_TPL['DESCRIPTION']=$tmp['short_note'];
     $_TPL['SEO_TITLE']=$tmp['seo_title'];

      switch(1){
          default:
        $_TPL['ROW']=$tmp;
                $tmp=explode(';', $_TPL['ROW']['list_parent']);
                $tmp[]=$id;
/*
                if (count($tmp)>2){
                $_TPL['SUBMENU']=$this->listArticle($tmp[3]);
                }
 */


             if ($_TPL['ROW']['view']=='view' || $id==1){
                 $_TPL['ROW']['content']=$this->maps($id).$_TPL['ROW']['content'] ;
                 $_TPL['LISTARTICLE'] = $this->listArticle($_TPL['ROW']['parent_id']);
                 include TPL_CMS_ARTICLE.'view_article.php';

             }else{
                 //$_TPL['LISTARTICLE']=$this->listArticlePage($id, 30); // лимит количества материалов в списке на страницу
				 //$_TPL['CNTARTICLE']=$this->cntArticle($id);			 
				  $_TPL['LISTARTICLE']=$this->listArticlePage($id, CURRENT_PAGE, $this->limit); // лимит количества материалов в списке на страницу
				  $_TPL['CNTARTICLE']=$this->cntArticle($id);
				  $_TPL['PAGES_ARTICLE'] = pages_list_uri($_TPL['CNTARTICLE'], $this->limit,'article'.$id.'_page1',CURRENT_PAGE);
/*if (in_array($id, array(899, 908))){
                    include TPL_CMS_ARTICLE.'list_article2.php';
           }
           elseif (in_array($id, array(967))){
                    include TPL_CMS_ARTICLE.'list_article3.php';
           }
           else{*/
		   
               include TPL_CMS_ARTICLE.'list_article.php';
           /*}*/
		 
             }

        break;
        case($action=='maps'):
             $_TPL['ROW']=array('name'=>'Карта сайта',
                  'content'=>$this->maps(ARTICLE_TOPMENU)
                  );
             include TPL_CMS_ARTICLE.'view_article.php';
        break;
      }
   }



}
?>