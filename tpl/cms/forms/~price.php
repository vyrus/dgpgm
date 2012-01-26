<?

include TPL_CMS."_header.php";

?>
<script>

</script>	
<a href="/lk">Личный кабинет</a> · <a href="/forms/bid/<?=$_GET['id']?>">Выбор форм заявки №<?=$TPL['INFO']['start_realization']?>-<?=$TPL['INFO']['measure_has_notice_measure_id']?>-<?=$_GET['id']?></a> · Редактирование формы Обоснование цены
  
<h1>Обоснование цены</h1>
<style>
.table td input[type="text"] {
	width: 100px;
}
.table td select {
	width: 100px;
}

</style>
<script type="text/javascript">
$(document).ready(function() {
	
	add_new_row();
	
	$('.calculate').focus(function() {
		alert('Нажмите на кнопку "Расчитать"');
	});

});

</script>
<form method="post">

<table class="table" id="dataTable">
<tbody>
  <tr>
    <th nowrap>№ п/п</td>
    <th>Наименование должностей исполнителей</th>
    <th>Фактическое время участия исполнителя в работе</th>
    <th>Плановая продолжительность выполнения работ</th>
    <th>Численность исполнителей одной квалификации</th>
    <th>Индекс уровня зарплаты специалистов исполнителей работы</td>
    <th>Коэффициент квалификации (участия) специалистов</th>
  </tr>
  <tr>
    <td nowrap colspan="2"><p align="right">ИТОГО:</td>
    <td></td>
    <td nowrap><input type="text" name="prodolzhitelnost" /></td>
    <td nowrap><input type="text" name="summa_performers" readonly="readonly"></td>
    <td nowrap></td>
    <td nowrap></td>
  </tr>
</table>
<input type="button" value="Добавить поле" id="add" onclick="return add_new_row();">
<br /><br />
 
<table class="table">
  <tr>
    <th colspan="2">Базовые значения для расчета</th>
    <th colspan="2">Расчет стоимости</th>
  </tr>
  <tr>
    <td><?=$TPL['COST'][0]['name']?></td>
    <td><?=$TPL['COST'][0]['value']?></td>
    <td>Среднедневная зарплата    исполнителей (руб.)</td>
    <td><input type="text" name="" class="calculate" /></td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][1]['name']?></td>
    <td><?=$TPL['COST'][1]['value']?></td>
    <td>Единичная    себестоимость (руб.)</td>
    <td><input type="text" name="" class="calculate" /></td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][2]['name']?></td>
    <td><?=$TPL['COST'][2]['value']?></td>
    <td>Общая себестоимость    выполняемых работ (тыс. руб.)</td>
    <td><input type="text" name="" class="calculate" /></td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][3]['name']?></td>
    <td><?=$TPL['COST'][3]['value']?></td>
    <td>Стоимость работ    (услуг) (тыс. руб.)</td>
    <td><input type="text" name="" class="calculate" /></td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][4]['name']?></td>
    <td><?=$TPL['COST'][4]['value']?></td>
    <td>Стоимость выполняемых работ (услуг) в текущих ценах (тыс. руб.)</td>
    <td><input type="text" name="" class="calculate" /></td>
  </tr>
  <tr>
    <td><?=$TPL['COST'][5]['name']?></td>
    <td><?=$TPL['COST'][5]['value']?></td>
    <td>В том числе НДС 18% (тыс. руб.)</td>
    <td><input type="text" name="" class="calculate" /></td>
  </tr>
</tbody> 
</table>
<br />
<button>Расчитать</button> <button>Сохранить</button>


	
</form>

<script type="text/javascript">
   
function select($id) {//
	var select = '<select name="dolzhnost_'+$id+'" class="dolzhnost"><option value="">Выберите должность из списка</option>';
	<? foreach ($TPL['WAGE'] as $wage) {?>
		select += '<option value="<?=$wage['id']?>" onclick="return dolzhnost('+$id+',<?=$wage['index']?>);"><?=$wage['position']?></option>';
	<? } ?>
	select += '</select>';
	return select;
}
var total = 0;
function add_new_row(){
   total++;
   $('<tr>')
   .attr('id','tr_row_'+total)
   .append (
       $('<td style="text-align:center;">')
       .append(
           $('<div>'+total+'<br /><a onclick="$(\'#tr_row_'+total+'\').remove(); summaperformers();" class="ico_delete"><img src="/adm/icon/delete_16.png" alt="del" border="0"></a></div>')
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
           .attr('name','fact_time_job_'+total)
       )                             
                              
    )
	.append(
        $('<td>')
	)
	.append (
       $('<td>')
       .append(
           $('<input type="text" onBlur="return summaperformers();" />')
           .attr('name','number_performers_'+total)
		   .attr('class','performers')
       )                             
                              
    )
	.append (
       $('<td>')
       .append(
           $('<input type="text" />')
           .attr('name','koefitsent_job_'+total)
		   .attr('readonly', true)
       )                             
                              
    )
	.append(
        $('<td>')
		   .append(
			$('<input type="text" onFocus="return calculate();" />')
			.attr('name','koefitsent_specialists_'+total)
			.attr('class','calculate')
			.attr('readonly', true)
       )
	)
    .insertBefore('#dataTable tbody>tr:last');                
}
function dolzhnost(id,kf){
		$("input[name=koefitsent_job_"+id+"]").val(kf);
}

function summaperformers() {
	var summa = 0;
	$('.performers').each(function() {
		var n = parseInt($(this).val());
		if (isNaN(n) == false) {
			summa += n;
		}
	});
	$("input[name=summa_performers]").val(summa);
};

function calculate() {
	alert('Нажмите на кнопку "Расчитать"');
};

</script>


<?
    include TPL_CMS."_footer.php";
?>