<?

$_TPL['TITLE'] [] = 'Формирование тематики';

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
        var url = '/?mod=forms&action=mforoper1';
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
                        options += '<option value="' + $(this).attr('id') + '">' + $(this).attr('title') + '</option>';
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
    		$.post('/?mod=forms&action=tablefilteroper1', {'pp': pp, 'mr': mr, 'r': r},
    		function( data ) {
    			$('#table-filter-oper1').html( data );
    		});
    }

	$("input[name=send]").click(showTableFilter);

    showTableFilter();
});

filter_ext_cont = '<table width="100%">'+
  '<tr>'+
    '<td>Год:</td>'+
    '<td><input type="text" value="" name="work_topic"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Шифр заявки:</td>'+
    '<td><input type="text" value="" name="work_topic"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Участник:</td>'+
    '<td><input type="text" value="" name="work_topic">ERROR</td>'+
  '</tr>'+

'</table>';

/* new version
function makeMeasureCombo($pp_id)
{
    var options = '';
    dojo.xhrPost(
    {
        url: '/?mod=forms&action=mforoper1', handleAs: 'json',
        load: function(sp_json)
        {
            console.info(sp_json);
        },
        error: function(data){ options = ''; }
    })
    measures = '<option value="0">Все мероприятия</option>'+options;

    measure_combo = dojo.create("select", {innerHTML: measures, style:{opacity:"0"}}, dojo.byId('pp'), "after");
    dojo.fadeIn({ node:measure_combo,duration:1000 }).play();
}*/

function makeFilterExt()
{
    filter_ext = dojo.create("div", {innerHTML: filter_ext_cont, style:{opacity:"0"}}, dojo.byId('filter'), "last");
    dojo.fadeIn({ node:filter_ext,duration:1000 }).play();
}

dojo.ready(function()
{
// new version    dojo.connect(dojo.byId('pp'),'onchange', function() {makeMeasureCombo(this.val);});
    dojo.connect(dojo.byId('open_filter'),'onclick', makeFilterExt);
})
</script>

<h1>Формирование тематики</h1>

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

<p><input type="button" name="send" value="Выбрать">&nbsp;&nbsp;<input type="button" id="open_filter" value="Фильтр формирования тематики">&nbsp;&nbsp;<input type="button" id="make_excell" value="Cформировать таблицу в формате Excel"></p>

</form>
<br />
<div id="table-filter-oper1"></div>

<?
    include TPL_CMS."_footer.php";
?>