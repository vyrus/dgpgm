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
				
				case($action =='spravka'):
					include ACTIONS_STATS."spravka.php";
                break;
				
            }
        }

}
?>