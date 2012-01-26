<?


require_once(dirname(__FILE__).'/src/common.php');
class MorphyIndex{
// first we include phpmorphy library


// set some options


   var $opts = array(
	// storage type, follow types supported
	// PHPMORPHY_STORAGE_FILE - use file operations(fread, fseek) for dictionary access, this is very slow...
	// PHPMORPHY_STORAGE_SHM - load dictionary in shared memory(using shmop php extension), this is preferred mode
	// PHPMORPHY_STORAGE_MEM - load dict to memory each time when phpMorphy intialized, this useful when shmop ext. not activated. Speed same as for PHPMORPHY_STORAGE_SHM type
	'storage' => PHPMORPHY_STORAGE_MEM ,
	// Extend graminfo for getAllFormsWithGramInfo method call
	'with_gramtab' => false,
	// Enable prediction by suffix
	'predict_by_suffix' => true,
	// Enable prediction by prefix
	'predict_by_db' => true
);

// Path to directory where dictionaries located

// Create descriptor for dictionary located in $dir directory with russian language



   function MorphyIndex(){
       $this->dir = dirname(__FILE__).  '/dicts';
       $this->dict_bundle = new phpMorphy_FilesBundle($this->dir, 'rus');
	   $this->morphy = new phpMorphy($this->dict_bundle, $this->opts);
   }

  function bildwords($text) {
         $del=array('&nbsp;','&shy;','&quot;', '&lt;' ,'&copy;', '&reg;', '&mdash;', '&thorn;','&lsquo;', '&ldquo;','&rsquo;','&rdquo;','&gt;','&amp;');
         $text=str_replace($del," ", $text);
         $text=strip_tags($text);
         $repl_symbol=array(".",",",";",":");
         $str="� ��� ����� �� ��� ���� ���� ���� ���� � ��� ��� ���� �� ��� ��� ����� ���� �� ���
              �� ���� ��� �� ��� �� ���� ���� ��� �� �� ����� � �� ��� �� �� � ��� �� ����� ��� ��
			  ���� ��� ����� �� �� ���� ��� �� ���� ��� ��� �� ��� �� �� � �� ������ ��
			  ��� ��� ��� �� ����� �� ��� ��� � �� ��� ����� ����� ��� �� ��� �� ���� ����
   			  ��� ������  ��� �� � ��� ���� ���� ��� ��� ��� ����� ��� ��� ��� ��� ��� �";
         $mas_stop=preg_split("#\s+#is", $str);

         $text=strtolower(str_replace($repl_symbol," ", $text));
         preg_match_all('/([a-zA-Z�-��-�0-9]+)/',$text,$ok);
         $cnt_stop_word=count($mas_stop);
         $cnt=count($ok[1]);
         for($j=0;$j < $cnt; $j++) {
             for($i=0;$i < $cnt_stop_word; $i++) {
                 if (trim($ok[1][$j]) == $mas_stop[$i] || strlen($ok[1][$j])<1){
                  unset($ok[1][$j]);
                  break;
                 }
             }
         }

         foreach($ok[1] as $kl=>$vl){
         	$ok[1][$kl]=trim($vl);
         }


         return $ok[1];
	}


	function prepare_index($text){
		   $_iWord=array();
				   $words= $this->bildwords($text);
				   foreach($words as $word){
				    //  $result = explode('|', $this->DrawStem($word));
					//  $result=$this->getWordform($word);
  					  $result[0] = $this->morphy->getBaseForm($word);
				          $result[0]=(!empty($result[0]))?strtoupper($result[0][0]): strtoupper($word);
				   	  if(empty($_iWord[$result[0]])){
				        $_iWord[$result[0]]=1;
				      //  $_iWord[$result[0]]['word']=$result;

				   	  }else{
				    	$_iWord[$result[0]]++;
				   	  }
		            }
		  return $_iWord;
	}




}




?>