<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php"
?>
<h3>Добавить фото</h3>
<form action="" method="post" enctype="multipart/form-data" name="photo" >
<input type="hidden" name="parent_id" value="<?=$_GET['parent_id']?>">

<table class="table">
<tr>
	<th>Заголовок (255 символов)</th>
</tr>
<tr>
	<td><input type="text" name="name" value="" size="60" maxlength="255"></td>
</tr>
<tr>
	<th>Фото <font color=red> Формат *.jpg</font></th>
</tr>
<tr>
	<td><input type="file" name="photo"></td>
</tr>
<tr>
	<th>Номер</th>
</tr>
<tr>
	<td><input type="text" name="num" value="" size="10" maxlength="255"></td>
</tr>
<tr>
	<th>Описание</th>
</tr>
<tr>
	<td>
	<textarea cols="40" rows="5" name="content"></textarea>
	</td>
</tr>


<tr>
	<td><input type="submit" value="Сохранить"></td>
</tr>
</table>


</form>