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
	
    .div_cell
	  {
	    width : 50px;
		padding : 5px;
		text-align : center;
	  }
	.div_cell1
	{
		padding : 5px;
		text-align : center;
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
</script>

<div style="position: absolute;border: solid #AAAAAA 1px;background-color: #DDDDDD;  
font-family: Tahoma, sans-serif;font-size: 11px;line-height: 16px;  
padding: 2px 5px;visibility: hidden;margin: 25px 0px 0px 5px;" id="mess"></div>
<h1><?=$TPL['STATTITLE']?></h1><br />

<div style="overflow: scroll;">
<table style="font-size:9px;" border=1>
<tr>
<th rowpan="2"><div style="width:46px;padding : 5px;">№ п/п</div></th>
<th rowpan="2"><div style="width:400px;">Мероприятие</div></th>
<th colspan="12">Финансирование на текущий год (план)</th>
</tr>  
<tr>
<th></th>
<th></th>
<th><div class="div_cell">Январь</div></th>
<th><div class="div_cell">Февраль</div></th>
<th><div class="div_cell">Март</div></th>
<th><div class="div_cell">Апрель</div></th>
<th><div class="div_cell">Май</div></th>
<th><div class="div_cell">Июнь</div></th>
<th><div class="div_cell">Июль</div></th>
<th><div class="div_cell">Август</div></th>
<th><div class="div_cell">Сентябрь</div></th>
<th><div class="div_cell">Октябрь</div></th>
<th><div class="div_cell">Ноябрь</div></th>
<th><div class="div_cell">Декабрь</div></th>
</tr>

<?
  // вывод самих значений в сформированную таблицу
  $d = $TPL['DATA'];
/*  $crlf = "\r\n";
        return '<!--' . $crlf . 
                   print_r($d, true) . $crlf . 
               '-->' . $crlf;
 */ $i=1;
  foreach ($d as $data_row)
    {
	  echo '<tr>'."\r\n";
	  echo '<td align="center">'.$i.'</td>'."\r\n";
	  echo '<td>'.$data_row['id'].' '.$data_row['title'].'</td>'."\r\n";
	  foreach ($data_row['sums'] as $m_data)
	    {
		  if ($m_data['value']!==0) echo "<td><div class=\"div_cell1\" 
		  onmouseover=\"tooltip(this,'".$m_data['text']."')\" 
		  onmouseout=\"hide_info(this)\">".$m_data['value']."</div></td>\r\n";
		  else echo "<td><div class=\"div_cell1\">".$m_data['value']."</div></td>\r\n";
		}
	  echo '</tr>'."\r\n";	
	  $i++;	
	}
?>

</table>
</div>
<br />
<div style="text-align: center;"><a href="#">Сформировать файл Excel</a></div>

<?php
    include TPL_CMS."_footer.php";
?>
