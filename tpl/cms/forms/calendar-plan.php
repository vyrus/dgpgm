<?
include TPL_CMS."_header.php";
?>

<?=$TPL['BIDMENU']?>

<h1>Календарный план выполнения работ</h1>
<script>
/*
$(document).ready(function () {
	$("input[name=send]").click(function() {
		$.post('/?mod=forms&action=tablestep',$("#formstep").serialize(),
		function( data ) {
			$('#table-step').html( data );
		});
	});

});
*/
</script>
<form method="post" id="formstep" action="/?mod=forms&action=tablestep&id=<?=$TPL['INFO']['id']?>">
<table width="100%">
  <tr>
    <td>Наименование предлагаемой темы работ:
</td>
    <td><?=$TPL['INFO']['work_topic']?></td>
  </tr>
<? foreach ($years as $year) { ?>
 <tr>
	<td>Количество этапов в <?=$year?> году:</td>
    <td>
	<select name="<?=$year?>">
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
	</select>
	</td>
  </tr>
<? } ?>
  <tr>
    <td colspan="2"><input type="submit" name="formstep" value="Вывести форму для заполнения"></td>
  </tr>
</table>
</form>
<!--
<div id="table-step"></div>
-->
<?
    include TPL_CMS."_footer.php";
?>