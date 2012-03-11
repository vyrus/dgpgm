<?
	$_TPL['TITLE'] [] = 'Государственные контракты';    
	include TPL_CMS."_header.php";
?>
<script language="JavaScript" src="/files/js/itemization-table.js"></script>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojo/resources/dojo.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dijit/themes/claro/claro.css">
<!--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojox/grid/resources/claroGrid.css">-->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojox/grid/enhanced/resources/claro/EnhancedGrid.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojox/grid/enhanced/resources/EnhancedGrid_rtl.css">
<link rel="stylesheet" href="/files/css/gridstyle.css">

<script>
	$(document).ready(function () 
	{
		$('#pp').change(function () 
		{
	        var pp_id = $(this).val();
			// ничего не выбранно
			if (pp_id == '0') {
	            $('#mr').html('<option value="0">Все мероприятия</option>');
	            //$('#mr').attr('disabled', true);
	            return(false);
	        }
	        //$('#mr').attr('disabled', true);
	        //$('#mr').html('<option>загрузка...</option>');
	        var url = '/?mod=gk&action=get_mr';
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
    });
</script>

    <style>
        #itemizationTable {
            font-size: 11px;
        }
    </style>

	<h1>Государственные контракты</h1>

	<form method="post">
	Выберите Подпрограмму:
	<select name="pp" id="pp">
		<option value="0">Все подпрограммы</option>
	<?	foreach ($TPL['SUBPROGRAM'] as $row) { ?>
		<option value="<?=$row['id']?>"<?= $TPL['SELECTED_SUBPROGRAM'] == $row['id'] ? ' selected' : ''?>><?=$row['title']?></option>
	<? } ?>
	</select><br>
	
	Выберите мероприятие:&nbsp;&nbsp;&nbsp;
	<select name="mr" id="mr" style="width:1030px">
		<option value="0">Все мероприятия</option>
    <?    foreach ($TPL['MEASURES'] as $row) { ?>
        <option value="<?=$row['id']?>"<?= $TPL['SELECTED_MEASURE'] == $row['id'] ? ' selected' : ''?>><?=$row['id'] . ' ' . $row['title']?></option>
    <? } ?>
	</select><br>

	Выберите год заключения:
		<?=$TPL["YEAR"]?>
	<br>
	
	<input type="submit" name="send" value="Показать">
	</form>
	<br>
	<? 
	if (isset($TPL['DATA']))
	{ ?>
		<div id="itemizationTable"></div>
		<script> renderItemizationTable(<?=$TPL['DATA']?>, "itemizationTable"); </script> <?
	}
	?>	
<?
    include TPL_CMS."_footer.php";
?>

