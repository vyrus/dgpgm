<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
?>

<script type="text/javascript">
   function delfaq(id){
      if(confirm('Вы действительно желаете удалить '+id)){
      	document.location.href ='?mod=faq&action=delfaq&id='+id;

      }
   }
</script>

<h3>Список вопросов</h3>

<table class="table">
<tr>
	<th>Вопрос</th>
	<th>Состояние</th>
	<th>Файл</th>
	<th colspan="2">Опции</th>
</tr>
	

	<?
if (count($_TPL['LISTROW'])){
$i=0;
	foreach($_TPL['LISTROW'] as $ROW){
	?>
	
<tr bgcolor="#<?=($i++%2?'f0f0f0':'ffffff')?>">
<td>
<strong><?=$ROW['faq_title']?></strong><br />
<?=$ROW['faq_question']?><br />
Автор: <?=$ROW['faq_author']?>
</td>
<?
	if ($ROW['faq_approved'] == 0) $faq_approved = "Без одобрения";
	if ($ROW['faq_approved'] == 1) $faq_approved = "Одобрено";
	?>
<td><?=$faq_approved?><br /><br />
(<a href="?mod=faq&action=print&id=<?=$ROW['faq_id']?>" target="_blank">версия для печати</a>)</td>
<td>
<?php
if (file_exists(FAQ_ZIP.$ROW['faq_id'].'.zip')){
?>
<a href="<?='/'.FAQ_ZIP.$ROW['faq_id'].'.zip'?>" title="Скачать файл">Скачать файл</a>
<?
}
else {
?>
Нет
<?
}
?>
</td>
<td>
<a href="?mod=faq&action=editfaq&id=<?=$ROW['faq_id']?>&page=<?=$_GET['page']?>"><img src="icon/edit_16.png" title="Редактировать" alt="Ред-ть" width="16" height="16" border="0"></a>
</td>
<td>
<a href="javascript:" onclick="delfaq(<?=$ROW['faq_id']?>); return false"><img src="icon/delete_16.png" title="Удалить" alt="Уд-ть" width="16" height="16" border="0"></a>
</td>
 </tr>

<?
	}
}
	?>

</table>

  Страницы:
 	<?
 	  for($i=1;$i<=$_TPL['CNTPAGE'];$i++){
 	  	if ($i!=CURRENT_PAGE){
 	  		?>[<a href="?mod=faq&page=<?=$i?>"><?=$i?></a>] <?
 	  	}else{
 	  		echo " &nbsp;[$i]&nbsp; ";
 	  	}
 	  }
 	?>