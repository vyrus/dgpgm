<?
	include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
	include $_SERVER['DOCUMENT_ROOT']."/editor/spaw.inc.php";
	$spaw1 = new SpawEditor("spaw1");
?>

<div align="center"><a href="?mod=faq&page=<?=$_GET[page]?>">Назад</a></div>

<form name=add_form method=post>
<table class="table">
<tr>
<td align="right">Выберите категорию</td>
<td>
<?
$cat = mysql_query('SELECT * FROM cms_faq_info');

  echo "<select name=\"faq_category\">
    <option id=\"faq_category\" value='0'>- Выберите категорию -</option>";

    while($row = mysql_fetch_array($cat))
      echo "<!--<option value='" . $row['faq_info_id'] . "'>" . $row['faq_info_title'] . "</option>-->
	  <option value=\"".$row['faq_info_id']."\" ".($row['faq_info_id'] == $_TPL['ROW']['faq_category'] ? " selected " : "" ).">".$row['faq_info_title']."</option>\r\n";

  echo "</select>";
?>
</td>
</tr>
<tr>
<td align="right">Тема вопроса</td>
<td><input maxlength="255" name="faq_title" size="50" value="<? echo  (isset($_TPL['ROW']['faq_title'])?$_TPL['ROW']['faq_title']:'')?>" type="text"></td>
</tr>
<tr>
<td align="right">Вопрос:</td>
<td>
<textarea name="faq_question" rows="5" cols="49"><? echo  (isset($_TPL['ROW']['faq_question'])?$_TPL['ROW']['faq_question']:'')?></textarea>
</td>
</tr>
<tr>
<td align="right">Ответ:</td>
<td>
<!--<textarea name="faq_answer" rows="5" cols="49"><? echo  (isset($_TPL['ROW']['faq_answer'])?$_TPL['ROW']['faq_answer']:'')?></textarea>-->
<?
		$spaw1 = new SPAW_Wysiwyg("faq_answer",$_TPL['ROW']['faq_answer']);
		$spaw1->show();
?>
</td>
</tr>
<tr>
<td align="right">Автор</td>
<td><input maxlength="255" name="faq_author" size="50" value="<? echo (isset($_TPL['ROW']['faq_author'])?$_TPL['ROW']['faq_author']:'')?>" type="text"></td>
</tr>
<tr>
<td align="right">Контактные данные</td>
<td><textarea name="faq_autor_contact" rows="5" cols="49"><? echo (isset($_TPL['ROW']['faq_autor_contact'])?$_TPL['ROW']['faq_autor_contact']:'')?></textarea></td>
</tr>
<tr>
<td align="right">e-mail (если есть)</td>
<td><input maxlength="255" name="faq_autor_email" size="50" value="<? echo (isset($_TPL['ROW']['faq_autor_email'])?$_TPL['ROW']['faq_autor_email']:'')?>" type="text"></td>
</tr>
<tr>
<td align="right">Одобрить?</td>
<td><input type="checkbox" name="faq_approved" value="1" <?=($_TPL['ROW']['faq_approved'] == "1" ? " checked":"")?>></td>
</tr>
</table>
<input name="submit" value="Ответить" type="submit">
</form>

<div align="center"><a href="?mod=faq&page=<?=$_GET[page]?>">Назад</a></div>