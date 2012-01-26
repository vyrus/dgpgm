<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php"
?>
<h3>Добавить фото</h3>
<form action="" method="post" enctype="multipart/form-data" name="photo" >


<table class="table">
<tr>
	<th>Заголовок (255 символов)</th>
	<th>Номер</th>
	<th>Фото<br />
	<font color=red> Формат *.jpg</font></th>
	<th>Описание</th>
</tr>

<? $i = 1; ?>

<? while ($i <= 5) { ?>

<input type="hidden" name="parent_id[]" value="<?=$_GET['parent_id']?>">

<tr>
	<td><input type="text" name="name[]" value="" size="30" maxlength="255"></td>
	<td><input type="text" name="num[]" value="" size="5" maxlength="255"></td>
	<td><input type="file" name="photo[]"></td>
	<td><textarea cols="30" rows="5" name="content[]"></textarea></td>
</tr>

<?
	$i++;
}
?>

<tr>
	<td colspan="4"><input type="submit" value="Сохранить"></td>
</tr>
</table>


</form>