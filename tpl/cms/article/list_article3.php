<?
 include "tpl/cms/_header.php";
 //include "tpl/cms/".$_TPL['HEADERINC'] ;

if (count($_TPL['LISTARTICLE'])){
?><table class="prepod"><tr><?
$i=0;
  foreach($_TPL['LISTARTICLE'] as $row){
?>  
<td>
  <div class="prename"><b><?=$row['name']?></b></div>
    <a href="/article<?=$row['id']?>" title="<?=$row['name']?>"><?=$row['short_note']?></a>
</td>
<?  
if ($i++%2){
?></tr><tr><?
}
}
?></tr></table><?
}
	
  include "tpl/cms/_footer.php";
  //include "tpl/cms/".$_TPL['FOOTERINC'] ;
?>