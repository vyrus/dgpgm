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

<h1><?=$TPL['STATTITLE']?></h1><br />

<div style="overflow: scroll;">
<table style="font-size:9px;table-layot : fixed;" border="1">
<tr>
<th><div style="width:46px;padding : 5px;">№ п/п</div></th>
<th><div style="width:80px;padding : 5px;">№ мероприятия</div></th>
<th><div style="width:250px;padding : 5px;">Наименование&nbsp;организации&nbsp;-&nbsp;победителя</div></th>
<th><div style="width:90px;padding : 5px;">№ и дата ГК</div></th>
<th><div style="width:250px;padding : 5px;">Перечисление средств</div></th>
</tr>

<?

  // вывод самих значений в сформированную таблицу
  $d = $TPL['DATA'];
  $i=1;
  foreach ($d as $data_row)
    {
	  echo '<tr>'."\r\n";
	  echo '<td>'.$i.'</td>'."\r\n";
	  echo '<td>'.$data_row['id'].'</td>'."\r\n";
	  if ($data_row['s_title']!=='') echo '<td>'.$data_row['f_title'].'('.$data_row['s_title'].')'.'</td>'."\r\n";
	  else echo '<td>'.$data_row['f_title'].'</td>'."\r\n";
  	  echo '<td>'.$data_row['number'].' от '.change_data_format($data_row['s_date']).'</td>'."\r\n";
	  echo "<td>";
	  foreach ($data_row['sums'] as $m_data)
	    {
		  echo $m_data."<br>";
		}
	  echo "</td>\r\n";
	  echo '</tr>'."\r\n";
	  $i++;
	}
?>

</table>
</div>
<br />
<div style="text-align: center;"><a href="/files/excel/finance_gk_detail.xls">Сформировать файл Excel</a></div>

<?php
    include TPL_CMS."_footer.php";
?>
