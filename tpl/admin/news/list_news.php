<?
	include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
?>

<script type="text/javascript">
   function delnews(id){
      if(confirm('�� ������������� ������� ������� ������� '+id)){
      	document.location.href ='?mod=news&action=delnews&id='+id;

      }
   }
</script>


<table class="table">
<tr><th>���������</th><th colspan="2">�����</th></tr>

	<?
if (count($_TPL['LISTROW'])){
$i=0;
	foreach($_TPL['LISTROW'] as $ROW){
	?>
<tr>
	<td><?=$ROW['name']?></td>
	<td><a href="?mod=news&action=editnews&id=<?=$ROW['id']?>&page=<?=$_GET['page']?>"><img src="icon/edit_16.png"  alt="�������������"  hspace="10" border="0"></a></td>
	<td><a href="javascript:" onclick="delnews(<?=$ROW['id']?>); return false"><img src="icon/delete_16.png"  alt="�������"  hspace="10" border="0"></a></td>
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
 	  		?>[<a href="?mod=news&page=<?=$i?>"><?=$i?></a>] <?
 	  	}else{
 	  		echo " &nbsp;[$i]&nbsp; ";
 	  	}
 	  }
 	?>