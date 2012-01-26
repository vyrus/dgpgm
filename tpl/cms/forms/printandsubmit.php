<?
include TPL_CMS."_header.php";
?>

<h1>Заявка №<?=$TPL['INFO']['start_realization']?>-<?=$TPL['INFO']['measure_has_notice_measure_id']?>-<?=$_GET['id']?> <?=(!empty($TPL['INFO']['datetime_electron_bid_receiving']))?"<span style='color:green;'>(отправлена на конкурс)</span>":""?></h1>

<?=$TPL['BIDMENU']?>

<p>Внимание!</p>
<ul><li>После отправки заявки на конкурс Вы не сможете продолжать редактировать заявку! Внимательно проверьте заполненные данные!</li>
<li>В бумажном виде направляется только контрольный экземпляр заявки, печать которого становится доступной после нажатия на кнопку «Отправить заявку на конкурс». В случае отправки чернового варианта заявка будет отклонена!</li>
</ul>

<? /*<form method="post">
<input type="hidden" value="<?=$_GET['id']?>" name="post-elect-bid">*/?>
<? /*</form>*/?>
<? if (!empty($TPL['INFO']['datetime_electron_bid_receiving'])) { ?>
	<?=(file_exists(DIR_FORM_PDF.$formname.'.pdf'))?"<br /><a href='/files/print-form/".$formname.'.pdf'."' title='Открыть файл' target='_blank'>Посмотреть контрольный экземпляр заявки</a>":""?>
<? } ?>
<input type="button" name="submit" <?/*type="submit" onclick="return (confirm('Вы действительно желаете отправить заявку на конкурс?'));"*/?> value="Отправить заявку на конкурс" style="float: right; margin-right: 200px;"<?=$form_dis_submit?> />
<? if (empty($TPL['INFO']['datetime_electron_bid_receiving'])) { ?>
<input type="button" value="<?=$form_print_name?>" name="send" style="float: left;"<?=$form_dis_print?> /> <?/*onClick="location.href='/forms/bid/<?=$_GET['id']?>/print'" type="submit" */?>
<div style="clear: both;"></div>
	<div id="start"><?=(file_exists(DIR_FORM_PDF.$formname.'.pdf'))?"<br /><a href='/files/print-form/".$formname.'.pdf'."' title='Открыть файл' target='_blank'>Посмотреть последний вариант заявки</a>":""?></div>
	<div id="end"></div>
<? } ?>

<script type="text/javascript">
$("input[name=send]").click(function() {
	
	$('#start').fadeIn(1).delay(5000).fadeOut('slow');
	$('#end').fadeOut(1).delay(5000).fadeIn('slow');
	$('#start').html('Идет генерация печатных форм. Пожалуйста, подождите...');
	$('#end').html('Печатные формы сгенерированы.');
	$.get('/forms/bid/<?=$_GET["id"]?>/print');
	setTimeout( function() {  location='<?=$_SERVER['REQUEST_URI']?>' }, 5400 );
	//window.location.href = '<?=$_SERVER['REQUEST_URI']?>';	
});

$("input[name=submit]").click(function() {
	if (confirm('Вы действительно желаете отправить заявку на конкурс?')) {
		$.post('/forms/bid/<?=$_GET["id"]?>/printandsubmit', {'post-elect-bid': <?=$_GET["id"]?>});
		$.get('/forms/bid/<?=$_GET["id"]?>/print');
		setTimeout( function() {  location='<?=$_SERVER['REQUEST_URI']?>' }, 2400 );
	}
});
</script>
<?
    include TPL_CMS."_footer.php";
?>