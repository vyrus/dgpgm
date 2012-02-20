<?
    include TPL_CMS."_header.php";
?>
<style>
#pp, #pp option, #mr, #mr option {
	width: 300px;
}
</style>
<h1>Общая статистика</h1>
<form method="post">
<h2>Условия отбора</h2>

<select name="pp" id="pp">
	<option value="0">Все подпрограммы</option>
<?	foreach ($TPL['SUBPROGRAM'] as $row) { ?>
	<option value="<?=$row['id']?>"><?=$row['title']?></option>
<? } ?>
</select>
<br /><br />

Годы с <select name="start_year"><?
for ($i=$startYear; $i<=$endYear; $i++) { ?>
	<option value="<?=$i?>"><?=$i?></option>
<?
	}	?></select>  по <select name="end_year"><?
for ($i=$start2Year; $i<=$endYear; $i++) { ?>
	<option value="<?=$i?>"><?=$i?></option>
<?
	}	?></select>

<h2>Выводимые показатели</h2>

<input type="checkbox" name="financing" value="1" checked="checked" /> Финансирование (план), тыс. руб.<br />
<input type="checkbox" name="financed" value="1" /> Профинансировано, тыс. руб.<br />
<input type="checkbox" name="tender_count" value="1" checked="checked" /> Количество конкурсов (план)<br />
<input type="checkbox" name="tender_commited" value="1" checked="checked" /> Проведено конкурсов<br />
<input type="checkbox" name="gk_count" value="1" /> Количество заключенных госконтрактов (план)<br />
<input type="checkbox" name="gk_commited" value="1" checked="checked" /> Заключено госконтрактов<br />

<h2>Параметры вывода</h2>

<input type="checkbox" name="number_gk" value="1" />  Детализировать по мероприятиям<br /><br />

<input type="submit" value="Сформировать отчет">

</form>

<?
    include TPL_CMS."_footer.php";
?>
