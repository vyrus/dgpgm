<?
include TPL_CMS."_header.php";
?>

<h1>Заявка №<?=$TPL['INFO']['bid_cipher']?> <?=(!empty($TPL['INFO']['datetime_electron_bid_receiving']))?"<span style='color:green;'>(отправлена на конкурс)</span>":""?></h1>

<? if (empty($TPL['INFO']['datetime_electron_bid_receiving'])) {?>
<p>Вы можете приступить к формированию заявки.<br />
Выберите очередную форму для заполнения (очередной шаг) в меню слева. Заполняемую форму Вы можете сохранить на любом этапе. Также Вы можете в любой момент вернуться к ранее заполненным или частично заполненным формам для дополнения или исправления.<br />
Полностью заполненные формы (пройденные шаги) будут отмечаться знаком <img border='0' src='/adm/icon/done.png'>. Требующие заполнения формы (не пройденные шаги) – знаком <img border='0' src='/adm/icon/attension.png'>.</p>
<? } else { ?>
<?=(file_exists(DIR_FORM_PDF.$formname.'.pdf'))?"<br /><a href='/files/print-form/".$formname.'.pdf'."' title='Открыть файл' target='_blank'>Контрольный вариант заявки</a>":""?>
<? }?>
<?
    include TPL_CMS."_footer.php";
?>