<?   include "tpl/admin/header.php"; ?>
<script type="text/javascript">
   function deluser(id){
      if(confirm('Удалить '+id)){
      	document.location.href ='?mod=users&action=deluser2group&id='+<?=@$_GET['id']?>+'&uid='+id+'page=<?=$_GET['page']?>';
      }
   }
</script>
<h3> Список пользователей для  группы</h3>

<form method="POST">
<table class="table">
<tr>
	<th>Логин</th>
	<th colspan="2">дейсвие</th>
</tr>
<?
if(count($_TPL['LISTROW'])){
 foreach($_TPL['LISTROW'] as $row){
 	?>
	<tr><td><a href="?mod=users&action=u2g&id=<?=$row['id']?>"><?=$row['login']?>/ <?=$row['name']?></a></td>
	<td><a href="?mod=users&action=edituser&id=<?=$row['id']?>"><img src="icon/edit_16.png"  alt="Редактировать"  hspace="10" border="0"></td>
	<td><a href="javascript:" onclick="deluser(<?=$row['id']?>); return false;"><img src="icon/delete_16.png"  alt="удалить из группы"  hspace="10" border="0"></a></td>
	</tr>
<?
 }

}

?>
<tr bgcolor="#CCCCCC">
	<td>Логин: <input type="text" name="login_1" size="40"></td>
	<td colspan="2"><input type="submit" value="Добавить в группу"></td>
</tr>
</table></form>

<div style="text-align:center;"><?=$_TPL['LISTPAGE']?></div>