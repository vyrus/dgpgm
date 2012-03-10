<?
class ManagerGk extends MysqlDB {
       var $db=false;

	function ManagerGk(&$db){
          $this->db=$db;
	}

	function listOrgName($name){
		if (!empty($name)) {
			$sql=sql_placeholder("SELECT * FROM ?#FK_APP_ORG WHERE full_title LIKE '%".$name."%'");
			return $this->db->_array_data($sql);
		}
	}
	
	function listNoticeNum(){
		$sql=sql_placeholder("SELECT l.id as lot_id, t.notice_num FROM ?#FK_TENDER as t, ?#FK_LOT as l where l.tender_id=t.id");
		return $this->db->_array_data($sql);
	}
	
    //Информация о госконтакте
	function viewGk($id){
		$sql=sql_placeholder('select * from ?#FK_GK where id=?', $id);
		return $this->db->select_row($sql);
   }
	
	
    function work(){
            global $_TPL;  
            $action = (empty($_GET['action'])?'':$_GET['action']);
			$id =  (empty($_GET['id'])?'':$_GET['id']);
            switch(1){
				default:
					include ACTIONS_GK."index.php";
                break;

				case($action =='search_organization'):
					include ACTIONS_GK."search_organization.php";
                break;
		
				case($action =='data_step'):
					include ACTIONS_GK."data_step.php";
                break;

				case($action =='data_bid'):
					include ACTIONS_GK."data_bid.php";
                break;
				
				case($action =='edit_organization'):
					include ACTIONS_GK."edit_organization.php";
                break;
				
            }
        }


}

?>