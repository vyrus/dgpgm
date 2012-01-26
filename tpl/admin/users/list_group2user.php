<?   include "tpl/admin/header.php"; ?>
<script type="text/javascript">
   function delgroup(id){
	  if(confirm('Удалить '+id)){
      	document.location.href ='?mod=users&action=deletegroup&id='+id;
      }
   }
</script>

<h3>Список групп, в которые входит пользователь</h3>

<form method="POST">
<table class="table">
<tr>
	<th>Login (name)</th>
	<th colspan="2">дейсвие</th>
</tr>
<?
if(count($_TPL['LISTROW'])){
 foreach($_TPL['LISTROW'] as $row){
 	?>
	<tr><td><a href="?mod=users&action=g2u&id=<?=$row['group_id']?>"><?=$row['title']?></a></td>
	<td><a href="?mod=users&action=editgroup&id=<?=$row['group_id']?>"><img src="icon/edit_16.png"  alt="редактировать"  hspace="10" border="0"></td>
	<td><a href="javascript:" onclick="deluser(<?=$row['group_id']?>); return false;"><img src="icon/delete_16.png"  alt="удалить"  hspace="10" border="0"></a></td>
	</tr>
<?
 }

}

?>
<tr>
	<td>Логин: <input type="text" name="login_1" size="40"></td>
	<td colspan="2"><input type="submit" value="Добавить в группу"></td>
</tr>
</table>
</form>

<div style="text-align:center;"><?=$_TPL['LISTPAGE']?></div>
