<?
class ManagerStats{
       var $db=false;

	function ManagerStats(&$db){
          $this->db=$db;
	}


    function work(){
            global $_TPL;  
            $action = (empty($_GET['action'])?'':$_GET['action']);
            switch(1){
				default:
					include ACTIONS_STATS."index.php";
                break;

				case($action =='total'):
					include ACTIONS_STATS."total.php";
                break;

				case($action =='course'):
					include ACTIONS_STATS."course.php";
                break;
				
				case($action =='finance'):
					include ACTIONS_STATS."finance.php";
                break;
                
                case($action == 'finance-program'):
                    include ACTIONS_STATS . 'finance-program.php';
                break;
				
				case($action =='spravka'):
					include ACTIONS_STATS."spravka.php";
                break;
				
            }
        }

}

    /* Группировка элементов по значению ключа-индикатора */
    function group_by($items, $indicator_name) {
        $indicator = null;
        $groups = array();
        $group = array();
        
        foreach ($items as $item) {
            if ($indicator == null) {
                $indicator = $item[$indicator_name];
            }
            
            if ($item[$indicator_name] != $indicator) {
                $indicator = null;
                $groups[] = $group;
                $group = array();
            }
            
            $group[] = $item;
        }
        
        if (sizeof($group) > 0) {
            $groups[] = $group;
        }
        
        return $groups;
    }

?>