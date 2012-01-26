<?

$_TPL['TITLE'] [] = 'Данные о заявителе';

include TPL_CMS."_header.php";

?>

<script>
$(document).ready(function () {

	$("input[name=s_inn]").click(function() {

    	var inn = $('input[name=inn]').val();
		var url = '/?mod=forms&action=search_inn';
        $.get(
            url,
            "inn=" + inn,
            function (data) {
				$('#result').html(data);
             }
        );
		$('#orginfo').html('');
	});

});

	function viewOrg(id) {
		var url = '/?mod=forms&action=vieworg';
        $.get(
            url,
            "id=" + id,
            function (data) {
				$('#orginfo').html(data);
             }
        );
	}

</script>

<h1>Сведения об организации</h1>

Вы можете выбрать одну из имеющихся в базе данных организации.<br /><br />

<form method="post">

<table width="100%">
	<tr>
		<td>Введите ИНН организации<br /><br /></td>
		<td><input type="text" value="" name="inn"></td>
	</tr>
	<tr>
		<td><input type="button" name="s_inn" value="Найти организацию"></td>
		<td></td>
	</tr>
</table>
</form>

<div id="result"></div>

<div id="orginfo"></div>

<?
    include TPL_CMS."_footer.php";
?>