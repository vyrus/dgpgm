<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php"
?>
<h3>�������� ���������</h3>
<form action="" method="post" enctype="multipart/form-data"  >
<table class="table">
<tr>
	<th>��������� (255 ��������)</th>
</tr>
<tr>
	<td><input type="text" name="title" value="<?=(!empty($_TPL['ROW']['title'])?$_TPL['ROW']['title']:'');?>" size="60" maxlength="255"></td>
</tr>
<tr>
	<th>����������� � ���������</th>
</tr>

<tr>
	<td><textarea rows="10" cols="60" name="note"><?=(!empty($_TPL['ROW']['note'])?$_TPL['ROW']['note']:'');?></textarea></td>
</tr>

<tr>
	<th>������ ��������� <font color=red> ������ *.jpg</font></th>
</tr>
<tr>
	<td><input type="file" name="photo"></td>
</tr>


<tr>
	<th>�����</th>
</tr>
<tr>
	<td><input type="text" name="num" value="<?=(empty($_TPL['ROW']['num'])? '' : $_TPL['ROW']['num']  )?>" size="10" maxlength="255"></td>
</tr>



<tr>
	<td><input type="submit" value="���������"></td>
</tr>
</table>


</form>