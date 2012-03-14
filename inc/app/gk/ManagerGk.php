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
	
	// Информация о заявке ГК
	function viewbidGK($id){
		$sql=sql_placeholder('select * from ?#FK_BIDGK where id=?', $id);
		return $this->db->select_row($sql);
   }
	
	//Все подпрограммы
	function listSubprogram(){
		$sql=sql_placeholder('select * from ?#FK_SUBPROGRAM order by id asc');
		return $this->db->_array_data($sql);
   }

	// Все мероприятия подпрограммы
	function listMeasure($id){
		$sql=sql_placeholder('select * from ?#FK_MEASURE where subprogram_id=?', $id);
		return $this->db->_array_data($sql);
   }
   
	// Все мероприятия
	function listAllMeasures(){
		$sql=sql_placeholder('select * from ?#FK_MEASURE');
		return $this->db->_array_data($sql);
   }
   
   function rightToWrongDateFormat($right_format_date)
   {
		$date_time = explode(" ", $right_format_date);
		$new_t = $date_time[1].":00";
		$d = explode(".",$date_time[0]);
		$new_d = implode("-", array($d[2],$d[1],$d[0]));
		$new_date_time = $new_d." ".$new_t;
		return $new_date_time;
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

				case($action =='data_payment_order'):
					include ACTIONS_GK."data_payment_order.php";
                break;

				case($action =='data_bid'):
					include ACTIONS_GK."data_bid.php";
                break;
				
				case($action =='edit_organization'):
					include ACTIONS_GK."edit_organization.php";
                break;

				case($action =='itemization'):
					include ACTIONS_GK."itemization.php";
                break;

				case($action == 'gk'):
					include ACTIONS_GK."gk.php";
                break;
                
				case($action == 'tender'):
					include ACTIONS_GK."tender.php";
                break;
				
                case($action=='get_mr'):
					$pp_id = @intval($_GET['pp_id']);
					$r=$this->listMeasure($pp_id);
					if ($r) {
						$measure = array();
						$measure[] = array('id'=>'0', 'title'=>'Все мероприятия');
						foreach ($r as $row) {
							$measure[] = array('id'=>$row['id'], 'title'=>$row['title']);
						}
						$result = array('type'=>'success', 'measure'=>$measure);
					} else {
						$result = array('type'=>'error');
					}
					print json_encode($result);
				break;
                
            }
        }


}

?>