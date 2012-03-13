<?php
    include TPL_CMS."_header.php";
?>

<link rel="stylesheet" href="/files/css/gridstyle.css">
<style>
 	.claro .dojoxGridInvisible .dojoxGridCell 
 	{
		outline: none;
		padding : 0px !important;
		border : 0px !important;
		word-wrap: break-word;
		border-color: transparent #E5DAC8 #E5DAC8 transparent;
	}
	

div.text-area {
line-height: 2;
}
</style>

<script language="javascript">
var d = document;
var offsetfromcursorY=15 // y offset of tooltip
var ie=d.all && !window.opera;
var ns6=d.getElementById && !d.all;
var tipobj,op;

function tooltip(el,txt) {
tipobj=d.getElementById('mess');
tipobj.innerHTML = txt;
op = 0.1;  
tipobj.style.opacity = op;
tipobj.style.visibility="visible";
el.onmousemove=positiontip;
appear();
}
 
function hide_info(el) {
d.getElementById('mess').style.visibility='hidden';
el.onmousemove='';
}
 
function ietruebody(){
return (d.compatMode && d.compatMode!="BackCompat")? d.documentElement : d.body
}

function positiontip(e) {
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
var winwidth=ie? ietruebody().clientWidth : window.innerWidth-20
var winheight=ie? ietruebody().clientHeight : window.innerHeight-20
var rightedge=ie? winwidth-event.clientX : winwidth-e.clientX;
var bottomedge=ie? winheight-event.clientY-offsetfromcursorY : winheight-e.clientY-offsetfromcursorY;
if (rightedge < tipobj.offsetWidth)  tipobj.style.left=curX-tipobj.offsetWidth+"px";
else tipobj.style.left=curX+"px";
if (bottomedge < tipobj.offsetHeight) tipobj.style.top=curY-tipobj.offsetHeight-offsetfromcursorY+"px"
else tipobj.style.top=curY+offsetfromcursorY+"px";
}
 
function appear() {
if(op < 1) {
op += 0.1;
tipobj.style.opacity = op;
tipobj.style.filter = 'alpha(opacity='+op*100+')';
t = setTimeout('appear()', 30);
}
}
<? /* search org */?>
function loadSearch(){
  $('#search_org').load('/gk/search_organization')
}

function selectOrg(id,title){
  $('#select_org').text(title);
  $('#select_org_id').val(id);
  $('#search_org').text('');
  $('#data_org').html('<a href="/gk/<?=$gk_id?>/gk/edit_organization/'+id+'">Данные организации</a>');
}

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
<? /* search org end */?>







</script>

<div style="position: absolute;border: solid #AAAAAA 1px;background-color: #DDDDDD;  
font-family: Tahoma, sans-serif;font-size: 11px;line-height: 16px;  
padding: 2px 5px;visibility: hidden;margin: 25px 0px 0px 5px;" id="mess"></div>

<h1 align="center"><?=$TPL['STATTITLE']?></h1>
<h2 align="center">Создание/редактирование госконтракта</h2>
<?
  $gk_id=$TPL['gk_id'];
  $d=$TPL['DATA'];
  echo '<table>';
  echo '<tr><td width="50%">';
  echo '<b>Конкурс:</b> извещение № '.$d['tender_notice_num'].' от '.$d['tender_notice_date'].'</td><td>
  <form action="" name="tender_form">
  <input type="hidden" name="gk_id" value="'.$gk_id.'">
  <input type="button" name="tender" value="Данные конкурса" onclick="location.href=\'/gk/tender/'.$gk_id.'\'">
  </form>
  </td></tr>';	
  
  echo '<tr><td>';
  if ($d['bid_cifer']=="null") $b_cifer="Не задано"; else $b_cifer=$d['bid_cifer'];
  echo '<b>Заявка:</b> шифр заявки - '.$b_cifer.'</td><td>
  <form action="/gk/'.$gk_id.'/data_bid/'.$d['b_id'].'">
  <input type="submit" value="Данные заявки">
  </form>
  </td></tr>';
  
  echo '<tr><td>';
  echo '<b>Организация:</b> <span id="select_org">'.$d['full_org_title'].'</span></td><td>
  <input type="button" value="Поиск" onclick="loadSearch()"><br />
  <span id="data_org"><a href="/gk/'.$gk_id.'/gk/edit_organization/'.$data['org_id'].'">Данные организации</a></span>
  </td></tr>';
  ?>
  
  	<tr>
		<td colspan="2">
		<div id="search_org"></div>
		</td>
	</tr>
  
  <?
  echo '<tr><td colspan=2>';
  echo '<div onmouseover="tooltip(this,\'Номер мероприятия может быть отредактирован только в данных конкурса\')" onmouseout="hide_info(this)"><b>Мероприятие:</b> '.$d['measure_id'].' '.$d['measure_title'].'</div></td><td></td></tr>';
  
  echo '<tr><td colspan=2>';
  echo '<div style="padding-bottom : 20px;" onmouseover="tooltip(this,\'Вид работ может быть отредактирован только в данных конкурса\')" onmouseout="hide_info(this)"><b>Статья расходов:</b> '.$d['work_kind_title'].'</div></td><td></td></tr>';
  
  echo '<form method="post">';
  echo '<input type="hidden" value="'.$data['org_id'].'" name="id_org_ind" id="select_org_id" />';
  echo '<tr><td>Номер контракта</td><td>'.'<input type="text" name="number" size="10" value="'.$d['number'].'">'.'</td></tr>';
  echo '<tr><td>Дата заключения</td><td>'.'<input type="text" name="signing_date" size="10" value="'.$d['signing_date'].'">'.'</td></tr>';
  echo '<tr><td>Ставка НДС (%)</td><td>'.'<input type="text" name="VAT" size="10" value="'.$d['VAT'].'">'.'</td></tr>';
  
  echo '<tr><td>Статус контракта</td><td>';
  echo '<select size="1" name="status">';
  foreach ($d['statuses'] as $st)
    {
      if ($st['id']!==$d['status']) echo '<option value="'.$st['id'].'">'.$st['title'].'</option>';
	  else echo '<option selected="selected" value="'.$st['id'].'">'.$st['title'].'</option>';
    }
  echo '</select>';
  echo '</td></tr>';
  
  echo '<tr><td>Наименование проекта</td><td>'.'<textarea name="work_title" rows="6">'.$d['work_title'].'</textarea>'.'</td></tr>';
  echo '<tr><td>Руководитель проекта (ФИО полностью)</td><td>'.'<input type="text" name="work_director" size="10" value="'.$d['work_director'].'">'.'</td></tr>';
  echo '<tr><td>Электронная почта для связи</td><td>'.'<input type="text" name="e_mail" size="10" value="'.$d['e_mail'].'">'.'</td></tr>';
  echo '<tr><td>Телефон для связи</td><td>'.'<input type="text" name="phone" size="10" value="'.$d['phone'].'">'.'</td></tr>';
  
  /* if ($d['number']!=="null") echo '<tr><td>Номер контракта</td><td>'.$d['number'].'</td></tr>';
  else echo '<tr><td>Номер контракта</td><td>Не задано</td></tr>';
  if ($d['signing_date']!=="null") echo '<tr><td>Дата заключения</td><td>'.$d['signing_date'].'</td></tr>';
  else echo '<tr><td>Дата заключения</td><td>Не задано</td></tr>';
  if ($d['VAT']!=="null") echo '<tr><td>Ставка НДС (%)</td><td>'.$d['VAT'].'</td></tr>';
  else echo '<tr><td>Ставка НДС (%)</td><td>Не задано</td></tr>';
  if ($d['status']!=="null") echo '<tr><td>Статус контракта</td><td>'.$d['status'].'</td></tr>';
  else echo '<tr><td>Статус контракта</td><td>Не задано</td></tr>';
  if ($d['work_title']!=="null") echo '<tr><td>Наименование проекта</td><td>'.$d['work_title'].'</td></tr>';
  else echo '<tr><td>Наименование проекта</td><td>Не задано</td></tr>';
  if ($d['work_director']!=="null") echo '<tr><td>Руководитель проекта (ФИО полностью)</td><td>'.$d['work_director'].'</td></tr>';
  else echo '<tr><td>Руководитель проекта (ФИО полностью)</td><td>Не задано</td></tr>';
  if ($d['e_mail']!=="null") echo '<tr><td>Электронная почта для связи</td><td>'.$d['e_mail'].'</td></tr>';
  else echo '<tr><td>Электронная почта для связи</td><td>Не задано</td></tr>';
  if ($d['phone']!=="null") echo '<tr><td>Телефон для связи</td><td>'.$d['phone'].'</td></tr>';
  else echo '<tr><td>Телефон для связи</td><td>Не задано</td></tr>'; */
  echo '</table>';
	
  // вывод этапов
  echo '<h3>Этапы</h3>';
  echo '<div style="overflow: scroll;"><table border="1">';
  echo '<tr>';
  echo '<th>№ п/п</th>';
  echo '<th>Дата начала</th>';
  echo '<th>Дата предоставления отчета</th>';
  echo '<th>Дата окончания</th>';
  echo '<th>Плановая сумма, руб.</th>';
  echo '<th>Сумма по ГК, руб.</th>';
  echo '<th>Аванс, %</th>';
  echo '<th>Дата внедрения, план</th>';
  echo '<th>Действия</th>';
  echo '</tr>';
  
  $steps=$d['steps'];
  $summa=0;
  foreach ($steps as $step)
    {
	  echo '<tr align="center">';
	  echo '<td>'.$step['number'].'</td>';
	  echo '<td>'.change_data_format($step['start_date']).'</td>';
	  if ($step['presentation_date']!=='0000-00-00') echo '<td>'.change_data_format($step['presentation_date']).'</td>';
	  else echo '<td>Не задано</td>';
	  echo '<td>'.change_data_format($step['finish_date']).'</td>';
	  echo '<td>'.$step['plan_price'].'</td>';
	  echo '<td>'.$step['price'].'</td>';
	  $summa+=$step['price'];
	  echo '<td>'.$step['prepayment_percent'].'</td>';
	  if ($step['integration_date']!=='0000-00-00') echo '<td>'.change_data_format($step['integration_date']).'</td>';
	  else echo '<td>Не задано</td>';
	  echo '<td><a href="/gk/data_step/'.$gk_id.'/'.$step['id'].'">Ред.</a>/
	  <a href="/gk/gk/'.$gk_id.'/'.$step['id'].'/1/">Удал.</a>/
	  Подробнее</td>';
	  echo '</tr>'; 
	}
  echo '<tr><td colspan="5" align="right">Итого:&nbsp;&nbsp;&nbsp;</td>
  <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$summa.'</td>
  </tr>';
  echo '</table></div><div align="left"><a href="/gk/data_step/'.$gk_id.'/-1">Добавить этап</a></div>';
  
  // вывод платежных поручений
  echo '<h3>Платежные поручения</h3>';
  echo '<div style="overflow: scroll;"><table border="1">';
  echo '<tr>';
  echo '<th>№ этапа</th>';
  echo '<th>№ плат. поручения</th>';
  echo '<th>Дата</th>';
  echo '<th>Тип</th>';
  echo '<th>Сумма</th>';
  echo '<th>Статус</th>';
  echo '<th>Действия</th>';
  echo '</tr>';
  
  $payments=$d['payments'];
  foreach ($payments as $payment)
    {
	  echo '<tr align="center">';
	  echo '<td>'.$payment['number'].'</td>';
	  echo '<td>'.$payment['p_number'].'</td>';
	  echo '<td>'.change_data_format($payment['p_data']).'</td>';
	  echo '<td>'.$payment['p_type'].'</td>';
	  echo '<td>'.$payment['p_sum'].'</td>';
	  echo '<td>'.$payment['p_status'].'</td>';
	  echo '<td><a href="/gk/data_payment_order/'.$gk_id.'/'.$payment['id'].'">Ред.</a>/
	  <a href="/gk/gk/'.$gk_id.'/'.$payment['id'].'/2/">Удал.</a>/
	  Подробнее</td>';
	  echo '</tr>'; 
	}
  
  echo '</table></div><div align="left"><a href="/gk/data_payment_order/'.$gk_id.'/-1">Добавить поручение</a></div>'; 
  
  echo '<input type="hidden" name="gk_id" value="'.$gk_id.'">
  <center><input type="submit" name="save" value="Сохранить данные контракта"></center>
  </form>';
?>

<?php
    include TPL_CMS."_footer.php";
?>
