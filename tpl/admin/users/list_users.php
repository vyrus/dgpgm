<?   include "tpl/admin/header.php"; ?>

<script type="text/javascript">
   function deluser(id){
      if(confirm('Удалить '+id)){
      	document.location.href ='?mod=users&action=deluser&id='+id+'page=<?=$_GET['page']?>';
      }
   }
</script>

<h3>Список Пользователей</h3>

<table class="table">
<tr>
	<th>Login (name)</th>
	<th colspan="2">дейсвие</th>
</tr>
<?
if(count($_TPL['LISTROW'])){
 foreach($_TPL['LISTROW'] as $row){
 	?>
	<tr><td><a href="?mod=users&action=u2g&id=<?=$row['id']?>"><?=$row['login']?>/ <?=$row['name']?></a></td>
	<td><a href="?mod=users&action=edituser&id=<?=$row['id']?>"><img src="icon/edit_16.png"  alt="редактировать"  hspace="10" border="0"></td>	
	<td><a href="javascript:" onclick="deluser(<?=$row['id']?>); return false;"><img src="icon/delete_16.png"  alt="Удалить"  hspace="10" border="0"></a></td>
	</tr>
<?
 }

}

?>
</table>

<div style="text-align:center;"><?=$_TPL['LISTPAGE']?></div>
