<?

class MysqlDB{
   var  $sql_host='localhost';
   var  $sql_user='';
   var  $sql_password='';
   var  $sql_db='';
   var  $log_error='_mysql_error.txt';
   var  $debug=0;
   var $cnt_query=0;
   function _set_config($sql_host, $sql_user, $sql_password, $sql_db, $debug=0){
      $this->sql_host=$sql_host;
      $this->sql_user=$sql_user;
      $this->sql_password=$sql_password;
      $this->sql_db=$sql_db;
      $this->debug=$debug;

   }

   function getFoundRow(){
      return $this->select_row('select FOUND_ROWS() as cnt');
   }

   function db_connect(){
    @  $link = mysql_connect($this->sql_host, $this->sql_user, $this->sql_password);
      
      if (!$link){
        include "remont.php"; exit;
      }
    //     or die ("mysql_connect() failed!");
      if (!empty($this->sql_db)){
     	  mysql_selectdb($this->sql_db)  or die ("mysql_selectdb() failed!");

		mysql_query ("set character_set_client='utf8'");
        mysql_query ("set character_set_results='utf8'");
        mysql_query ("set collation_connection='utf8_general_ci'");

      }
     return $link;
   }

   function  addrow($table, $row) {
      $kl_array=array();
      $vl_array=array();
       foreach($row as $kl=>$vl){
         array_push ($kl_array ,$kl);
         array_push ($vl_array ,$vl);
       }

      $query=sql_placeholder('INSERT INTO '.$table.' (?_) VALUES (?@)' , $kl_array , $vl_array);

      $this->query($query);
       return mysql_insert_id();
     }


  function db_error($text=''){
    $text.="\n";
    $f=fopen($this->log_error, 'a');
    if ($this->debug){
    	echo "<pre>$text</pre>";
    }
    fwrite($f, $text);
	fclose($f);
  }

  function select_row($query){
   $result=$this->query($query);
   if ($row=mysql_fetch_assoc($result))
   {
       $row=array_map("stripslashes", $row);
       if (count($row)==1)
        {
         foreach($row as $vl)
         return $vl;
        }
    return  $row;
   }
   else
    return '';

   }

   function query($query){
   	 $result=mysql_query($query);
   	 if (!$result){
      $this->db_error(mysql_error()."\n ".$query);
   	 }
   	 ++$this->cnt_query;
     return $result;
   }


   function _array_data($query){
     $r=array();
     $result=$this->query($query);
     if (!$result) return $r;

      while($row=mysql_fetch_assoc($result)){
     	$r[]= array_map('stripslashes',$row);
      }
     return $r;

   }


}

?>