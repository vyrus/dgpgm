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
		$('#fizinfo').html('');
	});

});

	function viewFiz(id) {
		var url = '/?mod=forms&action=viewfiz';
        $.get(
            url,
            "id=" + id,
            function (data) {
				$('#fizinfo').html(data);
             }
        );
	}

</script>

<h1>Сведения о физическом лице</h1>

Прежде чем приступить к вводу информации, проверьте, может быть Вы уже вводили ее.<br /><br />

<form method="post">

<table width="100%">
	<tr>
		<td>Введите ИНН физического лица<br /><br /></td>
		<td><input type="text" value="" name="inn"></td>
	</tr>
	<tr>
		<td><input type="button" name="s_inn" value="Найти данные"></td>
		<td></td>
	</tr>
</table>
</form>

<div id="result"></div>

<div id="fizinfo"></div>

<?
    include TPL_CMS."_footer.php";
?>