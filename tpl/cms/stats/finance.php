<?
include TPL_CMS."_header.php";
?>
<script>
$(document).ready(function () {

	$('input[name="date_start"]').attachDatepicker({
		rangeSelect: false,
		yearRange: '<?=date('Y')?>',
		firstDay: 1,
		minDate: 0,
		maxDate: new Date(2012, 11, 31)
	});
	
	$('input[name="date_end"]').attachDatepicker({
		dateFormat: 'dd.mm.yy',
		rangeSelect: false,
		minDate: '+1d',
		maxDate: new Date(2012, 11, 31),
		firstDay: 1
	});

	$('#pp').change(function () {
        var pp_id = $(this).val();
		// ничего не выбранно
        if (pp_id == '0') {
            $('#mr').html('<option value="0">Все мероприятия</option>');
            return(false);
        }
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
                }
            },
            "json"
        );
    });

});
require(["dojo/_base/fx", "dojo/fx", "dojo/dom", "dojo/on", "dojo/domReady!"],
    function(fx, fx2, dom, on)
    {
		var fadeTarget = dojo.byId("detail");
		var fadeTarget2 = dojo.byId("m_datail");
		var fadeTarget3 = dojo.byId("props_datail");

		dojo.fadeOut({ node: fadeTarget2,
			onEnd: function(){
    	    	dojo.style(fadeTarget2, "display", "none");
    		} }).play();
		dojo.fadeOut({ node: fadeTarget3,
			onEnd: function(){
    	    	dojo.style(fadeTarget3, "display", "none");
    		} }).play();
		

		on(dojo.byId("output_common"), "click", function(e)
		{
			dojo.fadeIn({ node: fadeTarget,
			beforeBegin: function(){
    	    	dojo.style(fadeTarget, "display", "block");
    		} }).play();
		});    
		on(dojo.byId("output_list"), "click", function(e)
		{
			dojo.fadeOut({ node: fadeTarget,
			onEnd: function(){
    	    	dojo.style(fadeTarget, "display", "none");
    		} }).play();
		});    

		on(dojo.byId("output_list"), "click", function(e)
		{
			dojo.fadeIn({ node: fadeTarget2 ,
			beforeBegin: function(){
    	    	dojo.style(fadeTarget2, "display", "block");
    	    }}).play();
			dojo.fadeIn({ node: fadeTarget3 ,
			beforeBegin: function(){
    	    	dojo.style(fadeTarget3, "display", "block");
    	    }}).play();
		});    
		on(dojo.byId("output_common"), "click", function(e)
		{
			anim = dojo.fadeOut({ node: fadeTarget2 }).play();
				on(anim, "End", function(){
					dojo.style(fadeTarget2, "display", "none");
                }, true);			
			
			dojo.fadeOut({ node: fadeTarget3,
			onEnd: function(){
    	    	dojo.style(fadeTarget3, "display", "none");
    		} }).play();			
		});    
    }                
 );
</script>

<style>
#pp, #pp option, #mr, #mr option {
	width: 300px;
}
</style>
<h1>План финансирования работ</h1>

<form method="post">

<h2>Параметры вывода</h2>

<input type="radio" checked="checked" value="pp" name="output" id="output_common"> Вывести общую статистику по подпрограммам<br />
<input type="radio" value="gk" name="output" id="output_list"> Вывести перечень Госконтрактов<br />

<span id="detail">
<input type="checkbox" name="detail" value="1" /> Детализировать по мероприятиям<br /></span>

<h2>Условия отбора</h2>

<select name="pp" id="pp">
	<option value="0">Все подпрограммы</option>
<?	foreach ($TPL['SUBPROGRAM'] as $row) { ?>
	<option value="<?=$row['id']?>"><?=$row['title']?></option>
<? } ?>
</select>
<br /><br />
<span id="m_datail">
<select name="mr" id="mr">
	<option value="0">Все мероприятия</option>
</select>
<br /><br /></span>

Период с <input type="text" name="date_start" value="<?=date('d.m.Y')?>" />  по <input type="text" name="date_end" value="31.12.2012" /><br /><br /> 

<span id="props_datail">
<h2>Выводимые показатели</h2>

<input type="checkbox" name="number_gk" value="1" checked="checked" /> № Госконтракта<br />

<input type="checkbox" name="date_gk" value="1" checked="checked" /> Дата заключения Госконтракта<br />

<input type="checkbox" name="title_work" value="1" /> Наименование работы<br />

<input type="checkbox" name="executing_agency" value="1" checked="checked" /> Организация-исполнитель<br />

<input type="checkbox" name="count_steps" value="1" /> Всего этапов<br />

<input type="checkbox" name="number_step" value="1" /> № этапа<br />

<input type="checkbox" name="type_financing" value="1" /> Тип финансирования (аванс/акт)<br />

<input type="checkbox" name="type_work" value="1" checked="checked" /> Вид работ (НИР/НИОКР/Прочие)<br />

<input type="checkbox" name="date_financing" value="1" checked="checked" /> Дата финансирования (план)<br />

<input type="checkbox" name="sum_financing" value="1" checked="checked" /> Сумма финансирования, тыс. руб.<br />
</span>
<input type="submit" value="Сформировать отчет">

</form>

<?
    include TPL_CMS."_footer.php";
?>