<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
?>

<h3>Регистрация заявок в бумажном виде</h3>

<form action="" method="post" id="filter">
<p>Выберите Подпрограмму:</p>
<select name="pp" id="pp">
	<option value="0">Не выбрано</option>
<?	foreach ($TPL['SUBPROGRAM'] as $row) { ?>
	<option value="<?=$row['id']?>"><?=$row['title']?></option>
<? } ?>
</select>

<p>Выберите мероприятие:</p>

<select name="mr" id="mr">
	<option value="0">Не выбрано</option>
</select>

<p><input type="button" name="senden" value="Выбрать"></p>

</form>

<div id="table-filter"></div>


<script>
$(document).ready(function () {
    
	$('#pp').change(function () {
        var pp_id = $(this).val();
		// ничего не выбранно
        if (pp_id == '0') {
            $('#mr').html('<option value="0">Не выбрано</option>');
            //$('#mr').attr('disabled', true);
            return(false);
        }
        //$('#mr').attr('disabled', true);
        //$('#mr').html('<option>загрузка...</option>');
        var url = '/adm/?mod=forms&action=mforoper1';
        $.get(
            url,
            "pp_id=" + pp_id,
            function (result) {
                if (result.type == 'error') {
					$('#mr option').remove();
					alert('Нет мероприятий, в этой подпрограмме');
                    return(false);
                }
                else {
                    var options = '';
					//массив отдаст id и title
                    $(result.measure).each(function() {
                        options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
                    });
                    $('#mr').html(options);
                    //$('#mr').attr('disabled', false);
                }
            },
            "json"
        );
    });
	
	$("input[name=senden]").click(function() {
	var pp = $('select[name=pp] option[value]:selected').val();
	var mr = $('select[name=mr] option[value]:selected').val();
	if (pp != 0 && mr != 0) {
		$.post('/adm/?mod=forms&action=regpaperfilter', {'pp': pp, 'mr': mr},
		function( data ) {
			$('#table-filter').html( data );
		});
	}	
	});

});
</script>