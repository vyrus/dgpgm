<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php"
?>

<h3>Редактировать фото</h3>

<form action="" method="post" enctype="multipart/form-data" name="photo" >

<table class="table">
<tr>
	<th>Заголовок (255 символов)</th>
</tr>
<tr>
	<td><input type="text" name="name" value="<?=(!empty($_TPL['ROW']['title'])?$_TPL['ROW']['title']:'');?>" size="60" maxlength="255"></td>
</tr>
<tr>
	<th>Фото </th>
</tr>
<tr>
	<td><a href="<?=$GLOBALS['p']?>files/gallery/<?=$_TPL['ROW']['parent_id']?>/_<?=$_TPL['ROW']['photo_id']?>.jpg" target="_blank"><img src="<?=$GLOBALS['p']?>files/gallery/<?=$_TPL['ROW']['parent_id']?>/__<?=$_TPL['ROW']['photo_id']?>.jpg" border=0 alt="<?=htmlspecialchars($_TPL['ROW']['title'])?>"></a> <br> <input type="file" name="photo"></td>
</tr>
<tr>
	<th>Описание</th>
</tr>
<tr>
	<td>
	<textarea cols="60" rows="5" name="content"><?=(!empty($_TPL['ROW']['content'])?$_TPL['ROW']['content']:'')?></textarea>
	</td>
</tr>
<tr>
	<th>Количество просмотров  &nbsp;&nbsp; (<b> <?echo(!empty($_TPL['ROW']['cntview'])?$_TPL['ROW']['cntview']:0)?></b>)</th>
</tr>
<tr>
	<th>Номер</th>
</tr>
<tr>
	<td><input type="text" name="num" value="<?=(!empty($_TPL['ROW']['num'])?$_TPL['ROW']['num']:'');?>" size="10" maxlength="255"></td>
</tr>

<tr>
	<td><input type="submit" value="Сохранить"></td>
</tr>
</table>


</form>


<?
 if(!empty($_TPL['ROW']['parent_id'])){
?><div style="text-align:center;"><a href="?mod=photo&action=listphoto&id=<?=$_TPL['ROW']['parent_id']?>">Назад в категорию</a></div><?
 }
?> 