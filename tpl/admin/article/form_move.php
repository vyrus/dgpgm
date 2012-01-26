<?
        include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
?>
<h3>Изменить раздел страницы/раздела</h3>

<form method="POST">
<table class="table">
<tr>
<th>Название страницы/раздела</th>
<td><?=$_TPL['ROW']['name']?></td>
</tr>
<tr>
<th>Перенести</th>
<td>
<select name="pid">
<option value="1">Корень</option>
<?=$_TPL['OPTION']?>
</select>
</td>
</tr>
<tr>
<td colspan="2"><input type="submit" value="Применить"></td>
</tr>
</table>
</form>

<div style="text-align: center;"><a href="?mod=article&action=list&id=<?=($_GET['action']=='add')?$_GET['id']:$_TPL['ROW']['parent_id']?>">Назад</a></div>