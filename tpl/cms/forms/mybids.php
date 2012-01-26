<? if (USER_GROUP == 2) { ?>
<script>
$(document).ready(function () {
	$('#selector-my-bids').change(function () {
    	var st = $('select[name=selector-my-bids] option[value]:selected').val();
		var url = '/?mod=forms&action=my-bids';
        $.get(
            url,
            "st=" + st,
            function (result) {
                if (result.type == 'error') {
					$('#my-bids').html("Нет заявок для отображения");
                    return(false);
                }
                else {
                    var resond_text = '<ul>';
                    $(result.value).each(function() {
                        resond_text += '<li><a href="/forms/bid/' + $(this).attr('bid') + '" title="Заявка №' + $(this).attr('start_realization') + '-' + $(this).attr('measure_has_notice_measure_id') + '-' + $(this).attr('bid') + '">Заявка №' + $(this).attr('start_realization') + '-' + $(this).attr('measure_has_notice_measure_id') + '-' + $(this).attr('bid') + '</a></li>';
                    });
					resond_text += '</ul>';
                    $('#my-bids').html(resond_text);
                }
            },
            "json"
        );
	});
});
</script>
<h3>Мои заявки</h3>

<select name="selector-my-bids" id="selector-my-bids">
	<option value="1">Не поданные</option>
	<option value="2">В рассмотрении</option>
	<option value="3">Рассмотренные</option>
	<option value="4">Все</option>
</select>

<div id="my-bids">
<? if (!empty($_TPL['MYBIDS'])) { ?>
<ul>
<? foreach ($_TPL['MYBIDS'] as $bids) { ?>
	<li><a href="/forms/bid/<?=$bids['bid']?>" title="Заявка №<?=$bids['start_realization']?>-<?=$bids['measure_has_notice_measure_id']?>-<?=$bids['bid']?>">Заявка №<?=$bids['start_realization']?>-<?=$bids['measure_has_notice_measure_id']?>-<?=$bids['bid']?></a></li>
<? } ?>
</ul>
<? } else { ?>
Нет заявок для отображения
<? } ?>
</div>
<? } ?>