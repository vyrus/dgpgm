<?

class ManagerNews{
    var $db=false;

	function ManagerNews(&$db){
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
					$_TPL['ROW']=$this->viewNews($id);
					if (!$_TPL['ROW']){
							header('Location: /error404');
					exit;
					}					
					$_TPL['TITLE'][]=$_TPL['ROW']['name'];
					include TPL_CMS_NEWS."view_news.php";
                break;
                case($action=='news'):
                case($action=='list'):
					$tmp=$this->listNews(CURRENT_PAGE, $_TPL['LIMIT']);
					$_TPL['M_NEWS'] =  $_TPL['LISTNEWS']=$tmp['data'];
					$_TPL['TITLE'][]='Новости · Страница '.CURRENT_PAGE;
					$_TPL['PAGES_NEWS'] = pages_list_uri($tmp['cnt'], $_TPL['LIMIT'],'newspage1',CURRENT_PAGE);
					include TPL_CMS_NEWS."list_news_page.php";
				break;
				break;
            }
    }



    function listNews($page, $limit){
     	$sql=sql_placeholder('select SQL_CALC_FOUND_ROWS c.id, c.name, n.short_news , DATE_FORMAT(n.date_news, "%d.%m.%Y") as date_news, addtime  from  ?#FK_CATALOGID as c,  ?#FK_NEWS as n
     	where c.id=n.news_id order by n.date_news desc, c.addtime desc limit ?, ?', ($page-1)*$limit, $limit);
        return array('data'=>$this->db->_array_data($sql), 'cnt'=>$this->db->getFoundRow());
    }
	
    function ViewNews($id){
       $sql=sql_placeholder('select * from ?#FK_CATALOGID as c, ?#FK_NEWS as n where c.id=n.news_id and c.id=?', $id);
       return $this->db->select_row($sql);
    }

}
?>