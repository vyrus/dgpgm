<?
	include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
?>
<script type="text/javascript">
   function delcatphoto(id){
      if(confirm('�� ������������� ������� ������� ��������� '+id)){
      	document.location.href ='?mod=photo&action=delcategory&id='+id;

      }
   }
</script>
<h3>������ ���������</h3>

<table class="table">

<tr><th>���������</td><th colspan="2">�����</th></tr>
	<?
if (count($_TPL['LISTROW'])){
        $i=0;
	foreach($_TPL['LISTROW'] as $ROW){
	?>
		<tr>
		<td><a href="?mod=photo&action=listphoto&id=<?=$ROW['category_id']?>"><?=$ROW['title']?></a></td>
		<td><a href="?mod=photo&action=editcategory&id=<?=$ROW['category_id']?>"><img src="icon/edit_16.png" alt="�������������" border="0" hspace="10"></a></td>
		<td><a href="javascript:" onclick="delcatphoto(<?=$ROW['category_id']?>); return false"><img src="icon/delete_16.png" alt="�������" border="0" hspace="10"></a></td></tr>
	<?
	}
}	
	?>
	
	</tr>
	</table>