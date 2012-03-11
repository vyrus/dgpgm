<script>
$(document).ready(function () {

	$('input[name=search]').keyup(function(e) {
		if(e.keyCode == 13){
			var search = $('input[name=search]').val();
			var url = '/gk/search_organization';
			$.post(
				url,
				"search=" + search,
				function (data) {
					$('#result').html(data);
				}
			);
			$('#orginfo').html('');
			
			e.preventDefault();
			return false;
			
			}
    });

	$("input[name=s_org]").click(function() {

    	var search = $('input[name=search]').val();
		var url = '/gk/search_organization';
        $.post(
            url,
            "search=" + search,
            function (data) {
				$('#result').html(data);
             }
        );
		$('#orginfo').html('');
	});

});

</script>

	<h4>Поиск организации</h4>
	
	Для поиска организации введите ИНН или часть названия организации: <input type="text" value="" name="search">
	<br /><br /><input type="button" name="s_org" value="Найти организацию">
	
	<div id="result"></div>

	<div id="orginfo"></div>