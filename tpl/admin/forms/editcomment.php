<table class="table">
<tbody>
<tr>
	<td>Дата</td>
	<td><input type="text" value="<?=$TPL['COMMENT']['date']?>" name="date" /><br />
		<small>в формате ГГГГ-ММ-ДД</small>
	</td>
</tr>
<tr>
	<td>Текст</td>
	<td><textarea name="text"><?=$TPL['COMMENT']['text']?></textarea></td>
</tr>
<tr>
	<td>Активность</td>
	<td>
		<select name="active">
			<option value="0"<?=($TPL['COMMENT']['active'] == 0)?' selected="selected"':''?>>отменено</option>
			<option value="1"<?=($TPL['COMMENT']['active'] == 1)?' selected="selected"':''?>>действует</option>
		<select>
	</td>
</tr>
<tr>
	<td colspan="5"><input type="button" name="send_comment" value="Редактировать комментарий" onclick="self.parent.tb_open_new('/adm/?mod=forms&amp;action=addcomment&amp;id=<?=$TPL['COMMENT']['bid_id']?>?height=650&amp;width=800')"></td>
</tr>
</tbody>
</table>
<script type="text/javascript">
$("input[name=send_comment]").click(function() {
	var id = <?=$_GET['id']?>;
	var date = $('input[name=date]').val();
	var comment = $('textarea[name=text]').val();
	var active = $('select[name=active] option[value]:selected').val();
	$.post('/adm/?mod=forms&action=editcomment', {'id': id, 'date': date, 'text': comment, 'active': active});
});
</script>