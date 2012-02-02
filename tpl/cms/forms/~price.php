<?
include TPL_CMS."_header.php";
?>
  
<h1>Обоснование цены</h1>

<p>Заполните поля формы. В любой момент Вы можете сохранить введенные данные нажатием на кнопку внизу формы. Также Вы можете перемещаться между формами используя ссылки на соответствующие шаги в меню слева или ссылки над заголовком формы.</p>

<a href="/forms/bid/<?=$_GET['id']?>/other-price">Расчет по другой методике</a><br /><br />

<style>
.table td input[type="text"] {
	width: 100px;
}
.table td select {
	width: 100px;
}
.table td input.calculate {
	color: #ACA889;
	border: 1px solid #fff;
}
</style>
<? if (!$TPL['PERFORMERS']) {?>
<script type="text/javascript">
$(document).ready(function() {
	
	add_new_row();

});
</script>
<? } ?>
<form method="post" id="priceForm">

<table class="table" id="dataTable">
<tbody>
  <tr>
    <th nowrap>№ п/п</td>
    <th>Наименование должностей исполнителей</th>
    <th>Фактическое время участия исполнителя в работе, день</th>
    <th>Плановая продолжительность выполнения работ, день</th>
    <th>Численность исполнителей одной квалификации, чел.</th>
    <th>Индекс уровня зарплаты специалистов исполнителей работы</td>
    <th>Коэффициент квалификации (участия) специалистов</th>
  </tr>
<? if ($TPL['PERFORMERS']) {
	$i = 1;
	$performers = 0;
	foreach ($TPL['PERFORMERS'] as $perform) { ?>
	<tr id="tr_row_<?=$i?>">
		<td style="text-align: center;">
			<input type="hidden" name="row[]" value="<?=$i?>">
			<div id="input_title_<?=$i?>"><span class="number"><?=$i?></span><br /><a class="ico_delete" onclick="$('#tr_row_<?=$i?>').remove(); summaperformers(); return reloadnumber();"><img border="0" alt="del" src="/adm/icon/delete_16.png"></a></div>
		</td>
		<td>
			<select class="dolzhnost" name="dolzhnost[]" style="width: 150px;">
				<option value="">Выберите должность из списка</option>
				<? foreach ($TPL['WAGE'] as $wage) {?>
					<? if ($perform['dolzhnost']==$wage['id']) {
						$koefitsent_job = $wage['coef'];
					}?>
					<option value="<?=$wage['id']?>" onclick="return dolzhnost('<?=$i?>',<?=$wage['coef']?>);"<?=($perform['dolzhnost']==$wage['id'])?' selected':''?>><?=$wage['position']?></option>
				<? } ?>
			</select>
		</td>
		<td><input type="text" name="fact_time_job[]" id="fact_time_job_1" value="<?=$perform['fact_time_job']?>"></td>
		<td></td>
		<td><input type="text" onblur="return summaperformers();" name="number_performers[]" id="number_performers_<?=$i?>" class="performers" value="<?=$perform['number_performers']?>"></td>
		<td><input type="hidden" name="koefitsent_job[]" id="koefitsent_job_<?=$i?>" class="koefitsent_job"><p id="koefitsent_job_<?=$i?>"><?=$koefitsent_job?></p></td>
		<td><input type="text" onfocus="pereschet()" name="koefitsent_specialists_<?=$i?>" class="calculate" readonly="readonly"></td>
	</tr>
<?	$performers = $performers + $perform['number_performers'];
	$i++;
	}
} ?>
  <tr>
    <td nowrap colspan="2"><p align="right">ИТОГО:</td>
    <td></td>
    <td nowrap><input type="text" name="prodolzhitelnost[]" id="prodolzhitelnost" value="<?=$TPL['INFO']['duration']?>" /></td>
    <td nowrap><p id="summa_performers"><?=$performers?></p></td>
    <td nowrap></td>
    <td nowrap><input type="text" name="koefitsent_specialists" disabled="disabled" /></p></td>
  </tr>
</table>
<input type="button" value="Добавить поле" id="add_row" onclick="return add_new_row();">
<br /><br />
 
<table class="table">
  <tr>
    <th colspan="2">Базовые значения для расчета</th>
    <th colspan="2">Расчет стоимости</th>
  </tr>
  <tr>
    <td><?=$TPL['COST'][0]['name']?></td>
    <td><?=$TPL['COST'][0]['value']?></td>
    <td>Среднедневная зарплата исполнителей (руб.)</td>
    <td><input type="text" name="wages_of_performers" onFocus="pereschet()" disabled="disabled" /></td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][1]['name']?></td>
    <td><?=$TPL['COST'][1]['value']?></td>
    <td>Единичная себестоимость (руб.)</td>
    <td><input type="text" name="unit_cost" onFocus="pereschet()" disabled="disabled" /></td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][2]['name']?></td>
    <td><?=($TPL['COST'][2]['value']*100)?></td>
    <td>Общая себестоимость выполняемых работ (тыс. руб.)</td>
    <td><input type="text" name="all_cost" onFocus="pereschet()" disabled="disabled" /></td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][3]['name']?></td>
    <td><?=($TPL['COST'][3]['value']*100)?></td>
    <td>Стоимость работ (услуг) (тыс. руб.)</td>
    <td><input type="text" name="price_works" onFocus="pereschet()" disabled="disabled" /></td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][4]['name']?></td>
    <td><?=$TPL['COST'][4]['value']?></td>
    <td>Стоимость выполняемых работ (услуг) в текущих ценах (тыс. руб.)</td>
    <td><input type="text" name="price_works_actual" onFocus="pereschet()" disabled="disabled" /></td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][5]['name']?></td>
    <td><input type="text" name="user_nds" value="<?=(!isset($TPL['INFO']['user_nds']))?($TPL['COST'][5]['value']*100):($TPL['INFO']['user_nds']*100)?>" /></td>
    <td>В том числе НДС 18% (тыс. руб.)</td>
    <td><input type="text" name="nds" onFocus="pereschet()" disabled="disabled" /></td>
  </tr>
</tbody> 
</table>
<br />
<input type="button" name="send" value="Рассчитать" id="calculate" style="float: left; margin-left: 250px;"<?=$form_dis?>> <input type="button" name="send" value="Сохранить" id="add" style="float: right; margin-right: 250px;"<?=$form_dis?>>
<div style="clear: both;"></div>
</form>

<script type="text/javascript">
   
function select($id) {//
	var select = '<select name="dolzhnost[]" class="dolzhnost"><option value="">Выберите должность из списка</option>';
	<? foreach ($TPL['WAGE'] as $wage) {?>
		select += '<option value="<?=$wage['id']?>" onclick="return dolzhnost('+$id+',<?=$wage['coef']?>);"><?=$wage['position']?></option>';
	<? } ?>
	select += '</select>';
	return select;
}
<? if (!$TPL['PERFORMERS']) {?>
var total = 0;
<? } else { ?>
var total = <?=count($TPL['PERFORMERS'])?>;
<? } ?>

function add_new_row(){
   total++;
   $('<tr>')
   .attr('id','tr_row_'+total)
   .append (
       $('<td style="text-align:center;">')
	   .append(
			$('<input type="hidden" />')
			.attr('name','row[]')
			.attr('value', total)
       )
	   .append(
           $('<div><span class="number">'+total+'</span><br /><a onclick="$(\'#tr_row_'+total+'\').remove(); summaperformers(); return reloadnumber();" class="ico_delete"><img src="/adm/icon/delete_16.png" alt="del" border="0"></a></div>')
		   .attr('id','input_title_'+total)
       )                             
                              
    )
	.append (
       $('<td>')
       .append(
           $(select(total))
          .css({width:'150px'})
       )                             
                              
    )
   .append (
       $('<td>')
       .append(
           $('<input type="text" />')
           .attr('name','fact_time_job[]')
		   .attr('id','fact_time_job_'+total)
       )                             
                              
    )
	.append(
        $('<td>')
	)
	.append (
       $('<td>')
       .append(
           $('<input type="text" onBlur="return summaperformers();" />')
           .attr('name','number_performers[]')
		   .attr('id','number_performers_'+total)
		   .attr('class','performers')
       )                             
                              
    )
	.append (
       $('<td>')
       .append(
           $('<input type="hidden" />')
           .attr('name','koefitsent_job[]')
		   .attr('id','koefitsent_job_'+total)
		   .attr('class','koefitsent_job')
		   //.attr('readonly', true)
       )
	   .append(
           $('<p>')
		   .attr('id','koefitsent_job_'+total)
		)
                              
    )
	.append(
        $('<td>')
		   .append(
			$('<input type="text" onFocus="pereschet()" />')
			.attr('name','koefitsent_specialists_'+total)
			.attr('class','calculate')
			.attr('readonly', true)
       )
	)
    .insertBefore('#dataTable tbody>tr:last');                
	
	reloadnumber();
}
function dolzhnost(id,kf){
		$("p#koefitsent_job_"+id).text(kf);
		$("input[id=koefitsent_job_"+id+"]").val(kf);
}

function summaperformers() {
	var summa = 0;
	$('.performers').each(function() {
		var n = parseInt($(this).val());
		if (isNaN(n) == false) {
			summa += n;
		}
	});
	//$("input[name=summa_performers]").val(summa);
	$("p#summa_performers").text(summa);
};

function pereschet() {
	alert('Нажмите на кнопку "Рассчитать"');
};

$("input[name=send]").click(function() {	
	
	var id = this.id;
	
	if (id == 'calculate') {
		$('<tr>')
		.append(
			$('<input type="hidden" />')
			.attr('name','calculate')
			.attr('value','1')
		)
		.insertBefore('#dataTable tbody>tr:last');
	} else if  (id == 'add') {
		$('<tr>')
		.append(
			$('<input type="hidden" />')
			.attr('name','add')
			.attr('value','1')
		)
		.insertBefore('#dataTable tbody>tr:last');
	} 
	
	$.post('/?mod=forms&action=priceajax&id=<?=$_GET['id']?>',  $("#priceForm").serialize(), function (result) {
                if (result.type == 'error') {
					alert('Неправильно введены данные');
                    return(false);
                }
                else {
					//массив отдаст input и value
                    $(result.value).each(function() {
						$("input[name="+ $(this).attr('input') +"]").val($(this).attr('value'));
                    });
					if  (id == 'add') {
						//alert('Форма заполнена полностью');
						window.location.href = '/forms/bid/<?=$_GET['id']?>/price/complete';
					}
                }
            },
            "json"
    );
});
<? if ($TPL['PERFORMERS']) { ?>
	$('<tr>')
		.append(
			$('<input type="hidden" />')
			.attr('name','calculate')
			.attr('value','1')
		)
	.insertBefore('#dataTable tbody>tr:last');
	
	$.post('/?mod=forms&action=priceajax&id=<?=$_GET['id']?>',  $("#priceForm").serialize(), function (result) {
                if (result.type == 'error') {
					alert('Неправильно введены данные');
                    return(false);
                }
                else {
					//массив отдаст input и value
                    $(result.value).each(function() {
						$("input[name="+ $(this).attr('input') +"]").val($(this).attr('value'));
                    });
                }
            },
            "json"
    );
<? } ?>

function job() {
	alert(' Выберите должность из списка');
};

function reloadnumber() {
	$("span.number").each(function (i) {
       $(this).html( ( 1 *  i+1 ))
      });
}; 
</script>


<?
    include TPL_CMS."_footer.php";
?>