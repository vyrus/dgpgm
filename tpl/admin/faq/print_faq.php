<script language="Javascript1.2">function printpage() {window.print();}</script>
<h2>Обращение, поступившее в интернет-приемную, на сайте <?=$_SERVER['HTTP_HOST']?></h2>
Направлено в категорию: <?=$_TPL['ROW']['faq_info_title']?><br />
<h1><?=$_TPL['ROW']['faq_title']?></h1>

<?=nl2br($_TPL['ROW']['faq_question'])?><br /><br />

Автор обращения: <?=$_TPL['ROW']['faq_author']?><br />
<?=$_TPL['ROW']['faq_autor_contact']?><br />
<?
if (!empty($_TPL['ROW']['faq_autor_email'])) {?>
e-mail: <?=$_TPL['ROW']['faq_autor_email']?><br />
<? } ?>
<br />
Дата: <?=$_TPL['ROW']['faq_date']?><br />

<?php
if (file_exists(FAQ_ZIP.$_TPL['ROW']['faq_id'].'.zip')){
?>
<div style="font-size: 12px;">К данному обращению, в качестве приложения, был прекреплен дополнительный файл. Ссылка для скачивания файла: <?='http://'.$_SERVER['HTTP_HOST'].'/'.FAQ_ZIP.$_TPL['ROW']['faq_id'].'.zip'?></div>
<?
}
?>
<br /><br /><center><input onClick="printpage()" type="button" value="Отправить на печать &rarr;" size="550"></center>