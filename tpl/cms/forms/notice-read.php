<?

$_TPL['TITLE'] [] = 'Опубликование информационных сообщений';

include TPL_CMS."_header.php";

?>
<h1>Опубликование информационных сообщений</h1>

<!-- список объявлений о сборке тематики-->
<br />
<strong>Объявленные конкурсы на формирование тематики:</strong>

<form method="post" id="note_for_main_page">
<p>Выберите объявление:</p>
<select name="not" id="not">
	<option value="0">Все объявления</option>
<?	foreach ($TPL['NOTICES'] as $row) { ?>
	<option value="<?=$row['id']?>">c <?=$row['start_acquisition']?> по <?=$row['finish_acquisition']?></option>
<? } ?>
</select>

<!-- создать объявление-->
<p><input type="button" name="send" value="Создать объявление" onclick="this.action='noticeedit';this.submit();"></p>

<!-- публиковать объявление-->
<p><input type="button" name="send" value="Опубликовать информационное сообщение" onclick="this.action='';this.submit();"></p>

</form>

<?
    include TPL_CMS."_footer.php";
?>