<?
	include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
?>
<script type="text/javascript">
   function delcatphoto(id){
      if(confirm('Вы действительно желаете удалить категорию '+id)){
      	document.location.href ='?mod=photo&action=delcategory&id='+id;

      }
   }
</script>
<h3>Список категорий</h3>

<table class="table">

<tr><th>Заголовок</td><th colspan="2">Опции</th></tr>
	<?
if (count($_TPL['LISTROW'])){
        $i=0;
	foreach($_TPL['LISTROW'] as $ROW){
	?>
		<tr>
		<td><a href="?mod=photo&action=listphoto&id=<?=$ROW['category_id']?>"><?=$ROW['title']?></a></td>
		<td><a href="?mod=photo&action=editcategory&id=<?=$ROW['category_id']?>"><img src="icon/edit_16.png" alt="Редактировать" border="0" hspace="10"></a></td>
		<td><a href="javascript:" onclick="delcatphoto(<?=$ROW['category_id']?>); return false"><img src="icon/delete_16.png" alt="Удалить" border="0" hspace="10"></a></td></tr>
	<?
	}
}	
	?>
	
	</tr>
	</table>