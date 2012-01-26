<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php"
?>
<h3>Добавить категорию</h3>
<form action="" method="post" enctype="multipart/form-data"  >
<table class="table">
<tr>
	<th>Заголовок (255 символов)</th>
</tr>
<tr>
	<td><input type="text" name="title" value="<?=(!empty($_TPL['ROW']['title'])?$_TPL['ROW']['title']:'');?>" size="60" maxlength="255"></td>
</tr>
<tr>
	<th>Комментраий к категории</th>
</tr>

<tr>
	<td><textarea rows="10" cols="60" name="note"><?=(!empty($_TPL['ROW']['note'])?$_TPL['ROW']['note']:'');?></textarea></td>
</tr>

<tr>
	<th>Иконка категории <font color=red> Формат *.jpg</font></th>
</tr>
<tr>
	<td><input type="file" name="photo"></td>
</tr>


<tr>
	<th>Номер</th>
</tr>
<tr>
	<td><input type="text" name="num" value="<?=(empty($_TPL['ROW']['num'])? '' : $_TPL['ROW']['num']  )?>" size="10" maxlength="255"></td>
</tr>



<tr>
	<td><input type="submit" value="Сохранить"></td>
</tr>
</table>


</form>