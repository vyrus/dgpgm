<?
  include "tpl/admin/header.php";
?>

<script type="text/javascript">
   function delguestbook(id){
      if(confirm('�� ������������� ������� ������� '+id)){
      	document.location.href ='?mod=guestbook&action=delguestbook&id='+id;

      }
   }
</script>

<h3>������ ���������</h3>

<table class="table">
<tr>
	<th>��������� � ����</th>
	<th>����� � e-mail</th>
	<th>���������</th>
	<th colspan="2">�����</th>
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
	if ($ROW['hide'] == 2) $faq_approved = "��� ���������";
	if ($ROW['hide'] == 1) $faq_approved = "��������";
?>
<td><?=$faq_approved?><br /><br />
<td>
<a href="?mod=guestbook&action=editguestbook&id=<?=$ROW['id_msg']?>&page=<?=$_GET['page']?>"><img src="icon/edit_16.png" title="�������������" alt="���-��" width="16" height="16" border="0"></a>
</td>
<td>
<a href="javascript:" onclick="delguestbook(<?=$ROW['id_msg']?>); return false"><img src="icon/delete_16.png" title="�������" alt="��-��" width="16" height="16" border="0"></a>
</td>
</tr>
<?
	}
}
	?>

</table>

  ��������:
 	<?
 	  for($i=1;$i<=$_TPL['CNTPAGE'];$i++){
 	  	if ($i!=CURRENT_PAGE){
 	  		?>[<a href="?mod=guestbook&page=<?=$i?>"><?=$i?></a>] <?
 	  	}else{
 	  		echo " &nbsp;[$i]&nbsp; ";
 	  	}
 	  }
 	?>