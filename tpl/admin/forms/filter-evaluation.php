<? if ($TPL['FILTER']['cnt'] != 0) {?>
Всего найдено заявок <?=$TPL['FILTER']['cnt']?><br />
Дата окончания сбора тематики: <?=$TPL['FILTER']['data'][0]['finish_acquisition']?><br />
Дата подведения итогов: <?=$TPL['FILTER']['data'][0]['summing_up_date']?>
<form method="post" id="edit-evaluation">
<table class="table">
<tr>
	<th>&#8470; п/п</th>
	<th>Шифр заявки</th>
	<th>Предложенная тема работ</th>
	<th>Участник</th>
	<th>Соответствует требованиям</th>
	<th>Рейтинг заявки-эксперты, балл</th>
	<th>Итоговый райтинг - Протокол НКС, балл</th>
	<th>Победитель</th>
	<th>Примечания</th>
</tr>
<? $i = 1;
 foreach ($TPL['FILTER']['data'] as $row) { ?>
<tr>
	<td><?=$i?></td>
	<td><?=$row['start_realization']?>-<?=$row['measure_has_notice_measure_id']?>-<?=$row['id']?></td>
	<td><?=$row['work_topic']?></td>
	<td><?=$row['applicant']?></td>
	<td><select name="matches[<?=$row['id']?>]"><option value="не определено"<?=($row['matches']=='не определено')?' selected="selected"':''?>>не определено</option><option value="да"<?=($row['matches']=='да')?' selected="selected"':''?>>да</option><option value="нет"<?=($row['matches']=='нет')?' selected="selected"':''?>>нет</option></select></td>
	<td><input name="rating_experts[<?=$row['id']?>]" type="text" value="<?=$row['rating_experts']?>" /></td>
	<td><input name="rating_protocol_NKS[<?=$row['id']?>]" type="text" value="<?=$row['rating_protocol_NKS']?>" /></td>
	<td><select name="winner[<?=$row['id']?>]"><option value="не определено"<?=($row['winner']=='не определено')?' selected="selected"':''?>>не определено</option><option value="да"<?=($row['winner']=='да')?' selected="selected"':''?>>да</option><option value="нет"<?=($row['winner']=='>нет')?' selected="selected"':''?>>нет</option></select></td>
	<td><a href="/adm/?mod=forms&action=addcomment&id=<?=$row['id']?>?height=650&width=800" class="thickbox" title="Добавить / редактировать примечания">Примечания (<?=$row['cnt_comment']?>)</a></td>
</tr>
<? $i++;
} ?>
</table>
<p><input type="button" name="send" value="Сохранить"></p>
</form>
<script>
$(document).ready(function () {

	tb_init('.thickbox');
	
	$("input[name=send]").click(function() {
		$.post('/adm/?mod=forms&action=updateevaluation',  $("#edit-evaluation").serialize(), function (result) {
                if (result.type == 'error') {
					alert('Изменений не произошло');
                    return(false);
                }
                else {
                    alert(result.message);
					var pp = $('select[name=pp] option[value]:selected').val();
					var mr = $('select[name=mr] option[value]:selected').val();
					$.post('/adm/?mod=forms&action=evaluationfilter', {'pp': pp, 'mr': mr},
						function( data ) {
							$('#table-filter').html( data );
						});
					//return(false);
                }
            },
            "json"
    );
});
	
	
	
});
</script>
<? } else { ?>
	<div style="color: red;">Нет заявок для отображения.</div>
<? } ?>