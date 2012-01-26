<?
  include "tpl/admin/header.php";
?>

<script type="text/javascript">
   function delguestbook(id){
      if(confirm('Вы действительно желаете удалить '+id)){
      	document.location.href ='?mod=guestbook&action=delguestbook&id='+id;

      }
   }
</script>

<h3>Список сообщений</h3>

<table class="table">
<tr>
	<th>Сообщение и дата</th>
	<th>Автор и e-mail</th>
	<th>Состояние</th>
	<th colspan="2">Опции</th>
</tr>
	

	<?
if (count($_TPL['LISTROW'])){
$i=0;
	foreach($_TPL['LISTROW'] as $ROW){
	?>
	
<tr bgcolor="#<?=($i++%2?'f0f0f0':'ffffff')?>">
<td>
<?=strftime("%d/%m/%Y - %H:%M", $ROW['time'])?><br />
<?=$ROW['msg']?>
</td>
<td>
<?=$ROW['name']?><br />
<?=$ROW['email']?>
</td>
<?
	if ($ROW['hide'] == 2) $faq_approved = "Без одобрения";
	if ($ROW['hide'] == 1) $faq_approved = "Одобрено";
?>
<td><?=$faq_approved?><br /><br />
<td>
<a href="?mod=guestbook&action=editguestbook&id=<?=$ROW['id_msg']?>&page=<?=$_GET['page']?>"><img src="icon/edit_16.png" title="Редактировать" alt="Ред-ть" width="16" height="16" border="0"></a>
</td>
<td>
<a href="javascript:" onclick="delguestbook(<?=$ROW['id_msg']?>); return false"><img src="icon/delete_16.png" title="Удалить" alt="Уд-ть" width="16" height="16" border="0"></a>
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
 	  		?>[<a href="?mod=guestbook&page=<?=$i?>"><?=$i?></a>] <?
 	  	}else{
 	  		echo " &nbsp;[$i]&nbsp; ";
 	  	}
 	  }
 	?>