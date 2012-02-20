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

<input type="checkbox" name="number_calls_plan" value="1" checked="checked" /> Количество конкурсов (план)<br /><br />

<input type="checkbox" name="number_calls_stage_application" value="1" checked="checked" /> Количество конкурсов на этапе подачи заявок<br /><br />

<input type="checkbox" name="number_calls_stage_consideration" value="1" /> Количество конкурсов на этапе рассмотрения<br /><br />

<input type="checkbox" name="count_contest" value="1" checked="checked" /> Проведено конкурсов<br /><br />

<input type="checkbox" name="total_funding" value="1" /> Общее финансирование на <?=date('Y')?>, тыс. руб.<br /><br />

<input type="checkbox" name="amount_competition" value="1" /> Сумма конкурсов (план), тыс. руб.<br /><br />

<input type="checkbox" name="amount_winners_bids" value="1" /> Сумма заявок победителей, тыс. руб.<br /><br />

<input type="checkbox" name="savings" value="1" checked="checked" /> Экономия средств в <?=date('Y')?> году, тыс. руб.<br /><br />


<h2>Параметры вывода</h2>

<input type="checkbox" name="detail" value="1" /> Детализировать по мероприятиям<br /><br />

<input type="submit" value="Сформировать отчет">

</form>

<?
    include TPL_CMS."_footer.php";
?>