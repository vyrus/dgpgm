<?

 class ManagerSearch{
       var $db=false;
       var $limit=10;
      function ManagerSearch(&$db){
 		$this->db=$db;
      }

 		function work(){
		   global $_TPL;
           $search=$_GET['s'];
		   $_TPL['TITLE'][]="Результат поиска по запросу ".$search;
           $tmp = $this->resultSearch($search);
		   
           $_TPL['LISTRESULT']=$tmp['data'];
    		$_TPL['PAGES']='';
			for($i=1; $i<=$tmp['cnt']/$this->limit;$i++){
				$link=preg_replace("#(search_\d+_)#is", "search_{$i}_", $_SERVER['REQUEST_URI']);
                                	$_TPL['PAGES'].= (CURRENT_PAGE!=$i)?"[<a href='".$link."'>$i</a>] ":" [$i] ";
			}
	    include  TPL_CMS_SEARCH."result_search.php";
 		}


		function resultSearch($search){
	       //$stemka = new stemka();
		   $stemka = new MorphyIndex();
	       $m_word=$stemka->prepare_index($search);
		   
           $tmp =  $this->search(array_keys($m_word), CURRENT_PAGE, $this->limit);

           $r=array();
           foreach($tmp['data'] as $kl=>$row){
	           $r[$kl]['id']=$row['id'];
               $r[$kl]['name']=$row['name'];
               $r[$kl]['action']=$row['action'];
               if($row['action']=='article'){
               	$r[$kl]['note']=substr(preg_replace("#\s+#is", ' ', strip_tags($row['content'])), 0, 300);
               }elseif ($row['action']=='news'){

               	$r[$kl]['note']=(empty($row['full_news']))?'':substr(preg_replace("#\s+#is", ' ', strip_tags($row['full_news'])), 0, 300);
               }else{
               	$r[$kl]['note']='';
               }

           }

            return array('data'=>$r, 'cnt'=>$tmp['cnt']);
		}

    	function search($m_word, $page, $limit){
        //   $_where = "( concat(' ', c.name) LIKE '% ". implode("%' and concat(' ', c.name) LIKE '% ", $m_word)."%' )";
// 		   $_where .= " or ( concat(' ', n.short_news) like '% ". implode("%' and concat(' ', n.short_news) like '% ", $m_word)."%' )";
  		//   $_where .= " or ( concat(' ', n.full_news) LIKE '% ". implode("%' and concat(' ', n.full_news) LIKE '% ", $m_word)."%' )";
  		//   $_where .= " or ( concat(' ', cn.content) LIKE '% ". implode("%' and concat(' ', cn.content) LIKE '% ", $m_word)."%' )";
		

		   $_where = "(c.name LIKE '%". implode("%' and c.name LIKE '%", $m_word)."%')";
// 		   $_where .= " or ( concat(' ', n.short_news) like '% ". implode("%' and concat(' ', n.short_news) like '% ", $m_word)."%' )";
  		   $_where .= " or (n.full_news LIKE '%". implode("%' and n.full_news LIKE '% ", $m_word)."%')";
  		   $_where .= " or (cn.content LIKE '%". implode("%' and cn.content LIKE '%", $m_word)."%')";
				

   			$sql=sql_placeholder('select SQL_CALC_FOUND_ROWS c.id ,c.name, c.action , cn.content, n.full_news    from
   					?#FK_CATALOGID as c
   					left join ?#FK_CONTENT as cn on (c.id=cn.id) left join ?#FK_NEWS as n on (c.id=n.news_id)

   					where '.$_where.' and c.id>1 limit ?,?

 	  					', ($page-1)*$limit, $limit);
           //echo $sql."<hr>";
            $r=$this->db->_array_data($sql);
		  	return array('data'=>$r, 'cnt'=>$this->db->getFoundRow());


		}


 }

?>