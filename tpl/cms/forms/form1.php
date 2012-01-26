<?

$_TPL['TITLE'] [] = 'Создание/выбор заявки';

include TPL_CMS."_header.php";

?>
<script>
$(document).ready(function () {

	$('#pp').change(function () {
        var pp_id = $(this).val();
		// ничего не выбранно
        if (pp_id == '0') {
            $('#mr').html('<option value="0">Все мероприятия</option>');
            //$('#mr').attr('disabled', true);
            return(false);
        }
        //$('#mr').attr('disabled', true);
        //$('#mr').html('<option>загрузка...</option>');
        var url = '/?mod=forms&action=get_mr';
        $.get(
            url,
            "pp_id=" + pp_id,
            function (result) {
                if (result.type == 'error') {
                    var options = '';
                    $('#mr').html(options);
                }
                else {
                    var options = '';
					//массив отдаст id и title
                    $(result.measure).each(function() {
						var num_m = $(this).attr('id');
						if (num_m == 0) {
							num_m = '';
						}
                        options += '<option value="' + $(this).attr('id') + '">' + num_m + ' ' + $(this).attr('title') + '</option>';
                    });
                    $('#mr').html(options);
                    //$('#mr').attr('disabled', false);
                }
            },
            "json"
        );
    });

    function showTableFilter()
    {
    	var pp = $('select[name=pp] option[value]:selected').val();
    	var mr = $('select[name=mr] option[value]:selected').val();
    	var r = $('select[name=r] option[value]:selected').val();
    		$.post('/?mod=forms&action=tablefilter', {'pp': pp, 'mr': mr, 'r': r},
    		function( data ) {
    			$('#table-filter').html( data );
    		});
    }

	$("input[name=send]").click(showTableFilter);

    showTableFilter();
});
</script>

<h1>Создание/выбор заявки</h1>

<br />
<strong>Объявленные конкурсы на формирование тематики:</strong>

<form action="" method="post" id="filter">
<p>Выберите Подпрограмму:</p>
<select name="pp" id="pp">
	<option value="0">Все подпрограммы</option>
<?	foreach ($TPL['SUBPROGRAM'] as $row) { ?>
	<option value="<?=$row['id']?>"><?=$row['title']?></option>
<? } ?>
</select>

<p>Выберите мероприятие:</p>

<select name="mr" id="mr">
	<option value="0">Все мероприятия</option>
</select>

<p>Выберите этап реализации:</p>

<select name="r" id="r">
	<option value="0">Все</option>
	<option value="2">Этап подачи заявок</option>
	<option value="3">Этап рассмотрения заявок</option>
	<option value="4">Формирование тематики завершено</option>
</select>

<p><input type="button" name="send" value="Выбрать"></p>

</form>
<br />
<div id="table-filter"></div>

<?
    include TPL_CMS."_footer.php";
?>