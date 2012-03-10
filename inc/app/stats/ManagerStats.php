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
				
				case($action == 'finance-program_summa'):
                    include ACTIONS_STATS . 'finance-program_summa.php';
                break;
				
				case($action == 'finance-program_gk'):
                    include ACTIONS_STATS . 'finance-program_gk.php';
                break;
				
				case($action =='spravka'):
					include ACTIONS_STATS."spravka.php";
                break;
				
            }
        }

}

    function group_by($items, $indicator_name) {
    
     	$indicator = $items[0][$indicator_name];
     	$groups = array();
     	$group = array();
     
        foreach ($items as $item) {
               
            /*if first item*/
            if ($item[$indicator_name] != $indicator) {
                $groups[] = $group;
                $indicator = $item[$indicator_name];
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