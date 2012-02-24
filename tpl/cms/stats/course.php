<?
include TPL_CMS."_header.php";
?>
<style>
#pp, #pp option {
	width: 300px;
}
</style>
<h1>Ход заявочной кампании</h1>

<form method="post">

<h2>Условия отбора</h2>

<select name="pp" id="pp">
	<option value="0">Все подпрограммы</option>
<?	foreach ($TPL['SUBPROGRAM'] as $row) { ?>
	<option value="<?=$row['id']?>"><?=$row['title']?></option>
<? } ?>
</select>

<h2>Выводимые показатели</h2>

<input type="checkbox" name="tender_count" value="1" checked="checked" /> Количество конкурсов (план)<br /><br />

<input type="checkbox" name="tend_num_podacha" value="1" checked="checked" /> Количество конкурсов на этапе подачи заявок<br /><br />

<input type="checkbox" name="tend_num_rassmotr" value="1" /> Количество конкурсов на этапе рассмотрения<br /><br />

<input type="checkbox" name="tend_num_commit" value="1" checked="checked" /> Проведено конкурсов<br /><br />

<?/*<input type="checkbox" name="total_funding" value="1" /> Общее финансирование на <?=date('Y')?>, тыс. руб.<br /><br />*/?>

<input type="checkbox" name="tender_commited_money" value="1" /> Сумма проведенных конкурсов, тыс. руб.<br /><br />

<input type="checkbox" name="winners_money" value="1" /> Сумма заявок победителей, тыс. руб.<br /><br />

<input type="checkbox" name="economy" value="1" checked="checked" /> Экономия средств по проведенным конкурсам в <?=date('Y')?> году, тыс. руб.<br /><br />


<h2>Параметры вывода</h2>

<input type="checkbox" name="detail_by_measures" value="1" /> Детализировать по мероприятиям<br /><br />

<input type="submit" value="Сформировать отчет">

</form>

<?
    include TPL_CMS."_footer.php";
?>