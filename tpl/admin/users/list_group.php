<?   include "tpl/admin/header.php"; ?>

<script type="text/javascript">
   function delgroup(id){
	  if(confirm('Удалить '+id)){
      	document.location.href ='?mod=users&action=deletegroup&id='+id;
      }
   }
</script>

<h3>Список групп</h3>

<form method="POST">
<table class="table">
<tr>
	<th>Группа</th>
	<th colspan="2">Дейсвие</th>
</tr>
<?
if(count($_TPL['LISTROW'])){
 foreach($_TPL['LISTROW'] as $row){
 	?>
	<tr>
	<td><a href="?mod=users&action=g2u&id=<?=$row['group_id']?>"><?=$row['title']?></a></td>
	<td><a href="?mod=users&action=editgroup&id=<?=$row['group_id']?>"><img src="icon/edit_16.png"  alt="Редактировать"  hspace="10" border="0"></td>
	<td><a href="javascript:" onclick="delgroup(<?=$row['group_id']?>); return false;"><img src="icon/delete_16.png"  alt="Удалить"  hspace="10" border="0"></a></td>
	</tr>
<?
 }

}

?>
<tr><td colspan="2">Добавить <input type="text" name="title" value="" size="60"></td><td><input type="submit" value="Добавить"></td></tr>
</table>
</form>