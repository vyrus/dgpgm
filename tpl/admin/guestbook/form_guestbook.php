<?
	include "tpl/admin/header.php";
	include("editor/spaw.inc.php");
	$spaw1 = new SpawEditor("spaw1");
?>

<div align="center"><a href="?mod=guestbook&page=<?=$_GET[page]?>">Назад</a></div>

<form name=add_form method=post>
<table class="table">
<tr>
<td align="right">Автор сообщения</td>
<td><input maxlength="255" name="name" size="50" value="<? echo  (isset($_TPL['ROW']['name'])?$_TPL['ROW']['name']:'')?>" type="text"></td>
</tr>
<tr>
<td align="right">e-mail автора</td>
<td><input maxlength="255" name="email" size="50" value="<? echo  (isset($_TPL['ROW']['email'])?$_TPL['ROW']['email']:'')?>" type="text"></td>
</tr>
<tr>
<td align="right">Запись в гостевую:</td>
<td>
<textarea name="msg" rows="5" cols="49"><? echo  (isset($_TPL['ROW']['msg'])?$_TPL['ROW']['msg']:'')?></textarea>
</td>
</tr>
<tr>
<td align="right">Ответ на запись:</td>
<td>
<?
		$spaw1 = new SPAW_Wysiwyg("answer",$_TPL['ROW']['answer']);
		$spaw1->show();
?>
</td>
</tr>
<tr>
<td align="right">Опубликовать на сайте</td>
<td><input type="checkbox" name="hide" value="1" <?=($_TPL['ROW']['hide'] == 1 ?'checked':'')?>></td>
</tr>
</table>
<input name="submit" value="Обновить сообщение" type="submit">
</form>

<div align="center"><a href="?mod=faq&page=<?=$_GET[page]?>">Назад</a></div>