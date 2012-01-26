<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
  
	include $_SERVER['DOCUMENT_ROOT']."/editor/spaw.inc.php";
	$spaw1 = new SpawEditor("spaw1");
  
?>

<form name="f1" method="post" enctype="multipart/form-data">
<table class="table">
<tr>
	<th>Заголовок (255 символов)</th>
</tr>
<tr>
	<td><input type="text" name="name" style="width: 600px" value="<? echo  (isset($_TPL['ROW']['name'])?htmlspecialchars($_TPL['ROW']['name']):'')?>"  maxlength="255"></td>
</tr>


<tr>
	<td valign="middle">

<?

if (!empty($_GET['id']) && file_exists(NEWS_PIC.$_GET['id'].'.jpg')){
?>
<img src="/files/images/news/<?=$_GET['id']?>.jpg?<?=time()?>"><br><a href="?mod=news&action=editnews&id=<?=$_GET['id']?>&page=1&delphoto">Удалить фото</a>
<? 
}
?>  &nbsp; Фото: <input type="file" name="photo">

</td>
</tr>

<tr>
	<th>Короткое описание (выводится только на странице списка новостей и в метатэге description)</th>
</tr>
<tr>
	<td>
	<textarea rows="10" name="short_news" style="width: 600px"><? echo  (isset($_TPL['ROW']['short_news'])?$_TPL['ROW']['short_news']:'')?></textarea>
	</td>
</tr>
<tr>
	<td align="right"></td>
</tr>
<tr>
	<th>Полное описание (содержание новости)</th>
</tr>
<tr>
	<td>
<?php
		$spaw1 = new SPAW_Wysiwyg("full_news",$_TPL['ROW']['full_news']);
		$spaw1->show();
?>
	</td>
</tr>
<tr>
	<th>Автор</th>
</tr>
<tr>
	<td><input type="text" name="author" value="<? echo  (isset($_TPL['ROW']['author'])?htmlspecialchars($_TPL['ROW']['author']):'')?>" maxlength="255" style="width: 100%"></td>
</tr>
<tr>
	<th>дата новости</th>
</tr>	
	<tr><td> <input type="text" name="date_news" style="width: 215px" value="<?echo  (isset($_TPL['ROW']['date_news'])?$_TPL['ROW']['date_news']:date("Y-m-d H:i:s"))?>" size="20" maxlength="20"></td>
</tr>
<tr>
	<td><input type="submit" value="Сохранить"></td>
</tr>
</table>


</form>