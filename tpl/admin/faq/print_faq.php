<script language="Javascript1.2">function printpage() {window.print();}</script>
<h2>���������, ����������� � ��������-��������, �� ����� <?=$_SERVER['HTTP_HOST']?></h2>
���������� � ���������: <?=$_TPL['ROW']['faq_info_title']?><br />
<h1><?=$_TPL['ROW']['faq_title']?></h1>

<?=nl2br($_TPL['ROW']['faq_question'])?><br /><br />

����� ���������: <?=$_TPL['ROW']['faq_author']?><br />
<?=$_TPL['ROW']['faq_autor_contact']?><br />
<?
if (!empty($_TPL['ROW']['faq_autor_email'])) {?>
e-mail: <?=$_TPL['ROW']['faq_autor_email']?><br />
<? } ?>
<br />
����: <?=$_TPL['ROW']['faq_date']?><br />

<?php
if (file_exists(FAQ_ZIP.$_TPL['ROW']['faq_id'].'.zip')){
?>
<div style="font-size: 12px;">� ������� ���������, � �������� ����������, ��� ���������� �������������� ����. ������ ��� ���������� �����: <?='http://'.$_SERVER['HTTP_HOST'].'/'.FAQ_ZIP.$_TPL['ROW']['faq_id'].'.zip'?></div>
<?
}
?>
<br /><br /><center><input onClick="printpage()" type="button" value="��������� �� ������ &rarr;" size="550"></center>