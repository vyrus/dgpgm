<?
  include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
?>

<script type="text/javascript">
   function delfaqcat(id){
      if(confirm('�� ������������� ������� ������� ��������� '+id)){
      	document.location.href ='?mod=faq&action=delfaqcat&id='+id;

      }
   }
</script>

<h3>������ ��������� ��������</h3>

<table class="table">

<tr>
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
<b>��������:</b> <?=$ROW['faq_info_title']?><br />
<b>��������:</b> <?=$ROW['faq_info_about']?><br />
</td>
<td>
<a href="?mod=faq&action=editfaqcat&id=<?=$ROW['faq_info_id']?>&page=<?=$_GET['page']?>"><img src="icon/edit_16.png" title="�������������" alt="���-��" width="16" height="16" border="0"></a>
</td>
<td>
<a href="javascript:" onclick="delfaqcat(<?=$ROW['faq_info_id']?>); return false"><img src="icon/delete_16.png" title="�������" alt="�������" width="16" height="16" border="0"></a>
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
 	  		?>[<a href="?mod=faq&page=<?=$i?>"><?=$i?></a>] <?
 	  	}else{
 	  		echo " &nbsp;[$i]&nbsp; ";
 	  	}
 	  }
 	?>