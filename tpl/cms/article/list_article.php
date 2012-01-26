<?
  include "tpl/cms/_header.php";

echo "<h1>".$_TPL['SEO_TITLE']."</h1>";
?>
<strong><?=$_TPL['ROW']['content']?></strong>
<?  
if (count($_TPL['LISTARTICLE'])){

  foreach($_TPL['LISTARTICLE'] as $row){
?>  
  <div style="font-size: 17px;"><a href="<?=$GLOBALS['p']?>article<?=$row['id']?>" title="<?=$row['name']?>"><?=$row['name']?></a></div>
  <?=$row['short_note']?>
  <div class="clr punktir"></div>
<?  }
}

  include "tpl/cms/_footer.php";
?>