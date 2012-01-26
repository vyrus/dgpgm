<?

function pages_list($cnt, $limit,$url,$page){

   $page_list='';
   $page_cnt=ceil ($cnt/$limit);
  // постраничный вывод
 if  ($page_cnt<11){
       for($i=1;$i<=$page_cnt;$i++){
          if ($i==$page) $page_list.='['.$i.'] ';
          else $page_list.='[<a href=?'.$url.'&page='.$i.'>'.$i.'</a>] ';
       }
 }else{

      switch(TRUE){
       case ($page<6):
          for($i=1;$i<=6;$i++)
         {
            if ($i==$page) $page_list.='['.$i.'] ';
            else $page_list.='[<a href=?"'.$url.'"&page='.$i.'>'.$i.'</a>] ';
         }
        $page_list.='...[<a href=?"'.$url.'"&page='.$page_cnt.'>'.$page_cnt.'</a>]';
       break;
       case ($page>5 && $page<($page_cnt - 5)):
       $page_list.='[<a href=?"'.$url.'"&page=1>1</a>]...';

             for($i=($page-3);$i<($page+3);$i++)
           {
             if ($i==$page) $page_list.='['.$i.'] ';
             else $page_list.='[<a href=?"'.$url.'"&page='.$i.'>'.$i.'</a>] ';
           }
          $page_list.='...[<a href=?"'.$url.'"&page='.$page_cnt.'>'.$page_cnt.'</a>]';

       break;
       default:
         $page_list.='[<a href=?"'.$url.'"&page=1>1</a>]...';

         for($i=$page_cnt-6; $i<=$page_cnt; $i++){
             if ($i==$page) $page_list.='['.$i.'] ';
             else $page_list.='[<a href=?"'.$url.'"&page='.$i.'>'.$i.'</a>] ';
           }

       break;
      }
   }
   // конец постраничной навигации
 return  $page_list;
}

function pages_list_uri($cnt, $limit,$url,$page){

   $page_list='';
   $page_cnt=ceil ($cnt/$limit);
  // постраничный вывод
 if  ($page_cnt<11){
       for($i=1;$i<=$page_cnt;$i++){
           $url=preg_replace("#page(\d+)#", "page$i", $url);
          if ($i==$page) $page_list.='['.$i.'] ';
          else $page_list.='[<a href="'.$url.'">'.$i.'</a>] ';

       }
 }else{

      switch(TRUE){
       case ($page<6):
          for($i=1;$i<=6;$i++)
         {
            $url=preg_replace("#page(\d+)#", "page$i", $url);
            if ($i==$page) $page_list.='['.$i.'] ';
            else $page_list.='[<a href="'.$url.'">'.$i.'</a>] ';
         }
        
        $url=preg_replace("#page(\d+)#", "page$page_cnt", $url);
        $page_list.='...[<a href="'.$url.'">'.$page_cnt.'</a>]';
       break;
       case ($page>5 && $page<($page_cnt - 5)):
       $url=preg_replace("#page(\d+)#", "page1", $url);
       $page_list.='[<a href="'.$url.'">1</a>]...';

             for($i=($page-3);$i<($page+3);$i++)
           {
             $url=preg_replace("#page(\d+)#", "page$i", $url);
             if ($i==$page) $page_list.='['.$i.'] ';
             else $page_list.='[<a href="'.$url.'">'.$i.'</a>] ';
           }
          $url=preg_replace("#page(\d+)#", "page$page_cnt", $url);
          $page_list.='...[<a href="'.$url.'">'.$page_cnt.'</a>]';

       break;
       default:
         $url=preg_replace("#page(\d+)#", "page1", $url);
         $page_list.='[<a href="'.$url.'">1</a>]...';

         for($i=$page_cnt-6; $i<=$page_cnt; $i++){
             $url=preg_replace("#page(\d+)#", "page$i", $url);
             if ($i==$page) $page_list.='['.$i.'] ';
             else $page_list.='[<a href="'.$url.'">'.$i.'</a>] ';
           }

       break;
      }
   }
   // конец постраничной навигации
 return  $page_list;
}

function listpagesuri($cnt){
    $uri=preg_replace("#page(\d+)/#is", '',  $_SERVER['REQUEST_URI']);
    $list_page='';
    for($i=1;$i<=$cnt;$i++){
       if ($i==CURRENT_PAGE){
            $list_page.=" [$i] ";
       }else{
            $url=$uri.'page'.$i.'/';
            $list_page.=" [<a href='$url'>$i</a>] ";
       } 
   }
   return $list_page;
}

function send_mail($from, $to, $subject, $msg)
{
$header="From: $from
MIME-Version: 1.0
Content-type: text/plain; charset=windows-1251
Content-transfer-encoding: 8bit";

mail($to , $subject, $msg, $header);

}

function send_mail_html($from,$to,$sub,$msg )
{
            $subject="Тема письма";
$header='Content-type: text/html; charset="windows-1251
From: "Метизкомплект" <'.$from.'>
Subject: '.$sub.'
Content-type: text/html; charset="windows-1251"';

$msg="<body>$msg

</body>";
mail($to, $sub, $msg, $header);
}

function utf8_substr($str,$from,$len){
# utf8 substr
 return preg_replace('#^(?:[x00-x7F]|[xC0-xFF][x80-xBF]+){0,'.$from.'}'.
                       '((?:[x00-x7F]|[xC0-xFF][x80-xBF]+){0,'.$len.'}).*#s',
                       '$1',$str);
}

function load_tpl_var($tpl)
{
  $text='';
  $lang=(!empty($_COOKIE['lang']) && $_COOKIE['lang']=='en')?'en':'ru';

  $filename="tpl/$lang/$tpl";
  if(!file_exists($filename))
    $text="Шаблон <b>".$filename."</b> не найден";
  else
   $text =        file_get_contents($filename);
  return $text;
}

?>