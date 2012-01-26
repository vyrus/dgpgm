<?
   include "tpl/admin/header.php";
?>

<form method="POST">
<table width="600" border="0" cellspacing="0" cellpadding="2" align="center">
<tr><td>Группа</td><td><input type="text" name="title" value="<?=$_TPL['ROW']['title']?>" size="40"></td></tr>
<tr><td colspan=2 align="center"><input type="submit" name="name" value="Применить"></td></tr>
</table></form>

<form method="POST">
<div align="center">Управлениен правами</div>

<table width="600" align="center">



<?
 foreach($_TPL['ACCESS'] as $row ){
?>
<tr><td><?=$row['title']?></td><td><input type="checkbox" name="a[<?=$row['action']?>]" value="1" <?=($row['access']?'checked':'')?>> </td></tr>

<?
  }
?>
</table>

<tr><td colspan=2 align="center"><input type="submit" name='access' value="Сохранить"></td></tr>
</table>

</form>
