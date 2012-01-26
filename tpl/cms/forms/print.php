<?

include TPL_CMS."_header.php";

if ($finish_acquisition_tsh>time() && empty($TPL['INFO']['datetime_electron_bid_receiving'])){
?>
<script>
$(document).ready(function () {
	$('#start').fadeIn(1).delay(5000).fadeOut('slow');
	$('#end').fadeOut(1).delay(5000).fadeIn('slow');
});
</script>

<? } ?>

<a href="/lk">Создание/выбор заявки</a> ·  <a href="/forms/bid/<?=$_GET['id']?>" title="Выбор форм">Выбор форм заявки №<?=$TPL['INFO']['start_realization']?>-<?=$TPL['INFO']['measure_has_notice_measure_id']?>-<?=$_GET['id']?></a> · Печатные формы

<h1>Генерация печатных форм</h1>

<? if ($finish_acquisition_tsh>time() && empty($TPL['INFO']['datetime_electron_bid_receiving'])){ ?>
<div id="start">Идет генерация печатных форм. Пожалуйста, подождите...</div>
<? } ?>

<div id="end">Печатные формы сгенерированы. <a href="/files/print-form/<?=$formname?>.pdf" target="_blank">Открыть</a>.</div>

<?
    include TPL_CMS."_footer.php";
?>