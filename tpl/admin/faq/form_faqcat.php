<?
	include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";

?>
<h3>�������� ��������� ��������</h3>

<form name=add_form method=post>
<table class="table">
<tr>
<td>�������� *</td>
<td><input maxlength="255" name="faq_info_title" size="50" value="<? echo  (isset($_TPL['ROW']['faq_info_title'])?$_TPL['ROW']['faq_info_title']:'')?>" type="text"></td>
</tr>
<tr>
<td>�������� *</td>
<td><textarea name="faq_info_about" rows="5" cols="49"><? echo  (isset($_TPL['ROW']['faq_info_about'])?$_TPL['ROW']['faq_info_about']:'')?></textarea></td>
</tr>
</table>
<input name="submit" value="�������" type="submit">
</form>