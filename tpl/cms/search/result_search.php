<?
  include "tpl/cms/_header.php";

if (count($_TPL['LISTRESULT'])){
  foreach($_TPL['LISTRESULT'] as $row){
?>

 <div class="center_bar">
   <div> <b><?=$row['name']?></b></div>
   <?=$row['note']?>
   <div align="right"><a href="/<?=$row['action']?><?=$row['id']?>">������ �����</a></div>
 </div>



<?  }

echo "<div align='center'>".$_TPL['PAGES']."</div>" ;
}else{
  ?> <div align="center"> �� ������ ������� ������ �� ��������</a></div> <?

}

  include "tpl/cms/_footer.php";
?>