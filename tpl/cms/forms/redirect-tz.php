<?
include TPL_CMS."_header.php";
?>
<a href="/lk">Создание/выбор заявки</a> · <a href="/forms/bid/<?=$_GET['id']?>">Выбор форм заявки №<?=$TPL['INFO']['start_realization']?>-<?=$TPL['INFO']['measure_has_notice_measure_id']?>-<?=$_GET['id']?></a> · Редактирование формы Этапы выполнения работ

<h1>Этапы выполнения работ</h1>
<p>
Для редактирования календарного плана Вам необходимо указать сроки
реализации проекта в <a href="/forms/bid/<?=$_GET['id']?>/tz">форме "Техническое задание" </a>
</p>
<?
    include TPL_CMS."_footer.php";
?>