<?
  include "tpl/cms/_header.php";
  //include "tpl/cms/".$_TPL['HEADERINC'] ;

if (count($_TPL['LISTARTICLE'])){
?>
<h1 class="header-big">Примеры использования системы</h1>

<table style="width: 100%;"><tr><?
$i=0;
  foreach($_TPL['LISTARTICLE'] as $row){
?>  
<td style="padding: 5px 10px; width: 50%;">
  <div style="float: right; font-size: 1.4em;"><a href="/article<?=$row['id']?>" title="<?=$row['name']?>" style="text-decoration: none; color: #53920d;"><?=$row['name']?></a></div>
  <div style="margin: 10px 0 0 0;"><?=$row['short_note']?></div>
  <a href="/article<?=$row['id']?>" title="<?=$row['name']?>">подрбнее...</a>
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