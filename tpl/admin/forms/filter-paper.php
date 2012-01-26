<? if ($TPL['FILTER']['cnt'] != 0) {?>
Всего найдено заявок <?=$TPL['FILTER']['cnt']?><br />
Дата окончания сбора тематики: <?=$TPL['FILTER']['data'][0]['finish_acquisition']?><br />
Дата подведения итогов: <?=$TPL['FILTER']['data'][0]['summing_up_date']?>
<form method="post" id="edit-paper">
<table class="table">
<tr>
	<th>&#8470; п/п</th>
	<th>Шифр заявки</th>
	<th>Предложенная тема работ</th>
	<th>Участник</th>
	<th>Дата получения в&nbsp;бум. Виде</th>
	<th>Бум. подтверждение получено</th>
</tr>
<? $i = 1;
 foreach ($TPL['FILTER']['data'] as $row) { ?>
<tr>
	<td><?=$i?></td>
	<td><?=$row['start_realization']?>-<?=$row['measure_has_notice_measure_id']?>-<?=$row['id']?></td>
	<td><?=$row['work_topic']?></td>
	<td><?=$row['applicant']?></td>
	<td><input id="date_<?=$row['id']?>" type="text" name="date[<?=$row['id']?>]" value=""></td>
	<td><input type="checkbox" name="option[<?=$row['id']?>]" value="<?=$row['id']?>"></td>
</tr>
<? $i++;
} ?>
</table>
<p><input type="button" name="send" value="Сохранить"></p>
</form>
<script>
$(document).ready(function () {

	$("input[type=checkbox]").click(function() {
		var n = $("input[type=checkbox]").is(":checked");
		if (n) {
			var id = $(this).val();
			$("input#date_"+id).val('<?=date('d.m.Y')?>');
			//$("input#date_"+id).attr({'class':'pickers'}); 
			$('input#date_'+id).attachDatepicker({
				rangeSelect: false,
				yearRange: '<?=date('Y')-1?>:<?=date('Y')?>',
				firstDay: 1
			});
		} else {
			var id = $(this).val();
			$("input#date_"+id).attr({ value: '' });
			$("input#date_"+id).removeClass('hasDatepicker');
		}
	});
	
	
	$("input[name=send]").click(function() {
		$.post('/adm/?mod=forms&action=updatepaper',  $("#edit-paper").serialize(), function (result) {
                if (result.type == 'error') {
					alert('Изменений не произошло');
                    return(false);
                }
                else {
                    alert(result.message);
					var pp = $('select[name=pp] option[value]:selected').val();
					var mr = $('select[name=mr] option[value]:selected').val();
					$.post('/adm/?mod=forms&action=regpaperfilter', {'pp': pp, 'mr': mr},
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