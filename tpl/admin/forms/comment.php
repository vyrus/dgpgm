<table class="table">
<tbody>
<tr>
    <th>№</th>
    <th>Дата</th>
	<th>Текст</th>
	<th>Активность</th>
	<th>Опции</th>
</tr>
<?  $i=1;
	foreach ($TPL['COMMENT'] as $row) { ?>  
<tr>
    <td><?=$i?></td>
    <td><?=$row['date']?></td>
	<td><?=$row['text']?></td>
	<td><?=($row['active'] == 1)?'действует':'отменено'?></td>
	<td>
		<a title="Редактировать комментарий" class="thickbox" href="/adm/?mod=forms&action=editcomment&id=<?=$row['id']?>?height=450&width=500"><img width="16" height="16" border="0" src="icon/edit_16.png"></a>
		<a title="Удалить комментарий" class="delete" id="<?=$row['id']?>" onclick="self.parent.tb_open_new('/adm/?mod=forms&action=addcomment&id=<?=$_GET['id']?>?height=650&width=800')"><img width="16" height="16" border="0" alt="" align="right" src="icon/delete_16.png"></a>
	</td>
</tr>
<? 	$i++;
	} ?>
<tr>
	<td colspan="5"><input name="bid_id" value="<?=$_GET['id']?>" type="hidden" /><textarea name="comment"></textarea></td>
</tr>
<tr>
	<td colspan="5"><input type="button" name="send_comment" value="Добавить комментарий" onclick="self.parent.tb_open_new('/adm/?mod=forms&amp;action=addcomment&amp;id=<?=$_GET['id']?>?height=650&amp;width=800')"></td>
</tr>
</tbody>
</table>
<script type="text/javascript">
$("input[name=send_comment]").click(function() {
	var id = $('input[name=bid_id]').val();
	var comment = $('textarea[name=comment]').val();
	$.post('/adm/?mod=forms&action=addcomment', {'id': id, 'comment': comment});
});

$("a.delete").click(function() {
	var id = this.id;
	$.post('/adm/?mod=forms&action=deletecomment', {'id': id});
});
</script>