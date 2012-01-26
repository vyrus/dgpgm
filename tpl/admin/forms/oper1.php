<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
$_TPL['TITLE'] [] = 'Формирование тематики';
?>
<style>
    input[type='text'] {
      width: 200px;
    }
    select {
      width: 200px;
    }
</style>
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
        var url = '/adm/?mod=forms&action=mforoper1';
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

        var posted_values = {'pp': pp, 'mr': mr, 'r': r};

    	var y = $('select[name=year] option[value]:selected').val();
console.info(y);
        if (y) {posted_values['y'] = y};
    	var bc = $('input[name=bid_cipher]').val();
console.info(bc);
        if (bc) {posted_values['bc'] = bc};
    	var ba = $('input[name=bid_applicant]').val();
console.info(ba);
        if (ba) {posted_values['ba'] = ba};
    	var debrf = $('input[name=date_create_bid_from]').val();
console.info(debrf);
        if (debrf) {posted_values['debrf'] = debrf};
    	var debrt = $('input[name=date_create_bid_till]').val();
console.info(debrt);
        if (debrt) {posted_values['debrt'] = debrt};
        var at = $('input:radio[name=applicant_type]:checked').val();
console.info(at);
        if (at) {posted_values['at'] = at};
    	var ebr = $('select[name=electron_bid_received] option[value]:selected').val();
console.info(ebr);
        if (ebr) {posted_values['ebr'] = ebr};
    	var debrf = $('input[name=datetime_electron_bid_receiving_from]').val();
console.info(debrf);
        if (debrf) {posted_values['debrf'] = debrf};
    	var debrt = $('input[name=datetime_electron_bid_receiving_till]').val();
console.info(debrt);
        if (debrt) {posted_values['debrt'] = debrt};
    	var pbr = $('select[name=paper_bid_received] option[value]:selected').val();
console.info(pbr);
        if (pbr) {posted_values['pbr'] = pbr};
    	var dpbrf = $('input[name=datetime_paper_bid_receiving_from]').val();
console.info(dpbrf);
        if (dpbrf) {posted_values['dpbrf'] = dpbrf};
    	var dpbrt = $('input[name=datetime_paper_bid_receiving_till]').val();
console.info(dpbrt);
        if (dpbrt) {posted_values['dpbrt'] = dpbrt};
    	var ce = $('select[name=comment_exist] option[value]:selected').val();
console.info(ce);
        if (ce) {posted_values['ce'] = ce};
    	var cdf = $('input[name=comment_date_from]').val();
console.info(cdf);
        if (cdf) {posted_values['cdf'] = cdf};
    	var cdt = $('input[name=comment_date_till]').val();
console.info(cdt);
        if (cdt) {posted_values['cdt'] = cdt};
    	var m = $('select[name=matches] option[value]:selected').val();
console.info(m);
        if (m) {posted_values['m'] = m};
    	var reu = $('input[name=rating_experts_upper]').val();
console.info(reu);
        if (reu) {posted_values['reu'] = reu};
    	var rel = $('input[name=rating_experts_lower]').val();
console.info(rel);
        if (rel) {posted_values['rel'] = rel};
    	var rpnu = $('input[name=rating_protocol_NKS_upper]').val();
console.info(rpnu);
        if (rpnu) {posted_values['rpnu'] = rpnu};
    	var rpnl = $('input[name=rating_protocol_NKS_lower]').val();
console.info(rpnl);
        if (rpnl) {posted_values['rpnl'] = rpnl};
    	var w = $('select[name=winner] option[value]:selected').val();
console.info(w);
        if (w) {posted_values['w'] = w};

console.info(posted_values);



    		$.post('/adm/?mod=forms&action=tablefilteroper1', {'pp': pp, 'mr': mr, 'r': r},
    		function( data ) {
    			$('#table-filter-oper1').html( data );
    		});
    }

	$("input[name=send]").click(showTableFilter);

    showTableFilter();
});

filter_ext_cont = '<table width="100%">'+
  '<tr>'+
    '<td style="width:448px">Год:</td>'+
    '<td><select name="year"><option value="2011">2011</option><option  value="2012">2012</option><option  value="2013">2013</option><option  value="2014">2014</option><option  value="2015">2015</option></select></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Шифр заявки:</td>'+
    '<td><input type="text" value="" name="bid_cipher"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Участник:</td>'+
    '<td><input type="text" value="" name="bid_applicant"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Дата создания заявки:</td>'+
    '<td>с&nbsp;<input type="text" value="" name="date_create_bid_from" style="width: 70px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;по&nbsp;<input type="text" value="" name="date_create_bid_till" style="width: 70px;"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Только юр. лица:</td>'+
    '<td><input type="radio" value="organization_only" name="applicant_type"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Только физ. лица:</td>'+
    '<td><input type="radio" value="individual_only" name="applicant_type"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Заявка поступила в электронном виде:</td>'+
    '<td><select name="electron_bid_received"><option value="-1">не выбрано</option><option value="1">да</option><option value="0">нет</option></select></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Дата поступления заявки в электронном виде:</td>'+
    '<td>с&nbsp;<input type="text" value="" name="datetime_electron_bid_receiving_from" style="width: 70px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;по&nbsp;<input type="text" value="" name="datetime_electron_bid_receiving_till" style="width: 70px;"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Заявка поступила в бумажном виде:</td>'+
    '<td><select name="paper_bid_received"><option value="-1">не выбрано</option><option value="1">да</option><option value="0">нет</option></select></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Дата поступления заявки в бумажном виде:</td>'+
    '<td>с&nbsp;<input type="text" value="" name="datetime_paper_bid_receiving_from" style="width: 70px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;по&nbsp;<input type="text" value="" name="datetime_paper_bid_receiving_till" style="width: 70px;"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Есть ли примечания по заявке:</td>'+
    '<td><select name="comment_exist"><option value="-1">не выбрано</option><option value="1">да</option><option value="0">нет</option></select></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Дата выставления примечания:</td>'+
    '<td>с&nbsp;<input type="text" value="" name="comment_date_from" style="width: 70px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;по&nbsp;<input type="text" value="" name="comment_date_till" style="width: 70px;"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Заявка соответствует требованиям:</td>'+
    '<td><select name="matches"><option value="-1">не выбрано</option><option value="1">да</option><option value="0">нет</option></select></td>'+
  '</tr>'+
  '<tr>'+
    '<td>С рейтингом экспертов выше:</td>'+
    '<td><input type="text" value="" name="rating_experts_upper"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>С рейтингом экспертов ниже:</td>'+
    '<td><input type="text" value="" name="rating_experts_lower"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>С итоговым рейтингом выше:</td>'+
    '<td><input type="text" value="" name="rating_protocol_NKS_upper"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>С итоговым рейтингом ниже:</td>'+
    '<td><input type="text" value="" name="rating_protocol_NKS_lower"></td>'+
  '</tr>'+
  '<tr>'+
    '<td>Заявка является победителем:</td>'+
    '<td><select name="winner"><option value="-1">не выбрано</option><option value="1">да</option><option value="0">нет</option></select></td>'+
  '</tr>'+
'</table>';

dojo.require("dojox.fx.ext-dojo.complex");
dojo.require("dojo.fx");
dojo.require("dijit.Tooltip");
dojo.ready(function()
{
var filter_ext_opener = {
        makeFilterExt : function()
        {
            var filter_ext = dojo.create("div", {innerHTML: filter_ext_cont, style:{opacity:"0"}}, dojo.byId('open_filter'), "after");
            dojo.fx.combine([
                dojo.fadeIn({ node:filter_ext,duration:1000 }),
                dojo.animateProperty({
                    node: this.firstChild,
                    properties: {
                                transform:{start:'rotate(0deg)', end:'rotate(90deg)'},
                                MozTransform:{start:'rotate(0deg)', end:'rotate(90deg)'},
                                WebkitTransform:{start:'rotate(0deg)', end:'rotate(90deg)'},
                                MozTransform:{start:'rotate(0deg)', end:'rotate(90deg)'},
                                OTransform:{start:'rotate(0deg)', end:'rotate(90deg)'},
                                msTransform:{start:'rotate(0deg)', end:'rotate(90deg)'}
                                },
                    duration: 1000
                })
            ]).play();

            /*calendar*/
        	$('input[name="date_create_bid_from"]').attachDatepicker({		rangeSelect: false,		yearRange: '2000:2050',		firstDay: 1	});
        	$('input[name="date_create_bid_till"]').attachDatepicker({		rangeSelect: false,		yearRange: '2000:2050',		firstDay: 1	});
        	$('input[name="datetime_electron_bid_receiving_from"]').attachDatepicker({		rangeSelect: false,		yearRange: '2000:2050',		firstDay: 1	});
        	$('input[name="datetime_electron_bid_receiving_till"]').attachDatepicker({		rangeSelect: false,		yearRange: '2000:2050',		firstDay: 1	});
        	$('input[name="datetime_paper_bid_receiving_from"]').attachDatepicker({		rangeSelect: false,		yearRange: '2000:2050',		firstDay: 1	});
        	$('input[name="datetime_paper_bid_receiving_till"]').attachDatepicker({		rangeSelect: false,		yearRange: '2000:2050',		firstDay: 1	});
        	$('input[name="comment_date_from"]').attachDatepicker({		rangeSelect: false,		yearRange: '2000:2050',		firstDay: 1	});
        	$('input[name="comment_date_till"]').attachDatepicker({		rangeSelect: false,		yearRange: '2000:2050',		firstDay: 1	});
            /*change to close*/
            dojo.disconnect(open_connect);
            close_connect = dojo.connect(dojo.byId('open_filter'), 'onclick', filter_ext_opener.killFilterExt);
            var tip = new dijit.Tooltip({ label: '<div class="myTipType">Закрыть расширенный фильтр</div>', showDelay: 250, connectId: this.firstChild});
        },
        killFilterExt : function()
        {
            var filter_ext = dojo.byId('open_filter').nextSibling;
            animation = dojo.fx.combine([
            dojo.animateProperty({
                node: this.firstChild,
                properties: {
                            transform:{start:'rotate(90deg)', end:'rotate(0deg)'},
                            MozTransform:{start:'rotate(90deg)', end:'rotate(0deg)'},
                            WebkitTransform:{start:'rotate(90deg)', end:'rotate(0deg)'},
                            MozTransform:{start:'rotate(90deg)', end:'rotate(0deg)'},
                            OTransform:{start:'rotate(90deg)', end:'rotate(0deg)'},
                            msTransform:{start:'rotate(90deg)', end:'rotate(0deg)'}
                            },
                duration: 1000
            }),
            dojo.fadeOut({ node:filter_ext,duration:1000 })
            ]).play();
            dojo.connect(animation, "onEnd", function(){ dojo.destroy(filter_ext);});
            var tip = new dijit.Tooltip({ label: '<div class="myTipType">Открыть расширенный фильтр</div>', showDelay: 250, connectId: this.firstChild});
        }
    };
    open_connect = dojo.connect(dojo.byId('open_filter'), 'onclick', filter_ext_opener.makeFilterExt);
    var tip = new dijit.Tooltip({ label: '<div class="myTipType">Открыть расширенный фильтр</div>', showDelay: 250, connectId: dojo.byId('open_filter')});
})
</script>

<h4>Объявленные конкурсы на формирование тематики:</h4>

<form action="" method="post" id="filter">
<table width="100%">
    <tr>
    <td style="width:448px">Выберите Подпрограмму:</td><td>
    <select name="pp" id="pp">
    	<option value="0">Все подпрограммы</option>
    <?	foreach ($TPL['SUBPROGRAM'] as $row) { ?>
    	<option value="<?=$row['id']?>"><?=$row['title']?></option>
    <? } ?>
    </select>
    </td>
    </tr>
    <tr>
    <td>Выберите мероприятие:</td><td>
    <select name="mr" id="mr">
    	<option value="0">Все мероприятия</option>
    </select>
    </td>
    </tr>
    <tr>
    <td>Выберите этап реализации:</td><td>
    <select name="r" id="r">
    	<option value="0">Все</option>
    	<option value="2">Этап подачи заявок</option>
    	<option value="3">Этап рассмотрения заявок</option>
    	<option value="4">Формирование тематики завершено</option>
    </select>
    </td>
    </tr>
</table>

<br />
<span id="open_filter"  style="cursor:pointer"><img border="0" class="" src="/adm/icon/closed.png" style="cursor:pointer"></span>
<br /><br />
<p><input type="button" name="send" value="Выбрать">&nbsp;&nbsp;<input type="button" id="make_excell" value="Cформировать таблицу в формате Excel"></p>
</form>
<div id="table-filter-oper1"></div>