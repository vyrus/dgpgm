<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
?>
<script>
   function delarticle(id){
      if(confirm('Вы действительно желаете удалить '+id)){
      	document.location.href ='?mod=article&action=del&id='+id;

      }
   }
</script>
<h3>Структура сайта</h3>

<div class="navi">
<?
if (count($_TPL['LISTPARENT'])){

	foreach ($_TPL['LISTPARENT'] as $row){
		?>
		<a href="?mod=article&action=list&id=<?=$row['id']?>"><?=$row['name']?></a> &rarr;
		<?
	}
}
?>
</div>

<table class="table">
<tr>
	<th>Название</th>
	<th>Тип</th>
	<th>Состояние</th>
	<th colspan="3">Опции</th>
</tr>
<? if (count($_TPL["LISTROW"])){ $i=0;
	foreach($_TPL['LISTROW'] as $row){
?>
<tr>

<td>
<?
if ($row['view'] == "list") {
?>
<a href="?mod=article&action=list&id=<?=$row['id']?>"><?=$row['name']?></a></td>
<?
}
else {
echo $row['name'];
}

if ($row['view'] == "list") {$type = "Раздел";}
else {$type = "Страница";}
?>
<td><?=$type?></td>

<td><?=($row['state']?'Опубликованно':'Закрыто')?></td>

<td><? if ($row['id'] != MAIN_PAGE_ID && $row['id'] != ARTICLE_TOPMENU) {?><a href="?mod=article&action=move&id=<?=$row['id']?>"><img src="icon/sublink_16.png" title="Переместить" alt="Переместить" width="16" height="16" border="0"></a><? } ?></td>

<td><? if ($row['id'] != ARTICLE_TOPMENU) {?><a href="?mod=article&action=edit&id=<?=$row['id']?>"><img src="icon/edit_16.png" title="Редактировать" alt="Редактировать" width="16" height="16" border="0"></a><? } ?></td>

<!--<td><a href="?mod=article&action=del&id=<?=$row['id']?>"><img src="icon/delete_16.png" title="Удалить" alt="Удалить" width="16" height="16" border="0"></a></td>-->
<td><? if ($row['id'] != MAIN_PAGE_ID && $row['id'] != ARTICLE_TOPMENU) {?><a href="javascript:" onclick="delarticle(<?=$row['id']?>); return false"><img src="icon/delete_16.png" title="Удалить" alt="Удалить" width="16" height="16" border="0"></a><? } ?></td>

</tr>

<?}
}
?>
</table>