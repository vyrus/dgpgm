<?
	include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
?>
<script type="text/javascript">
   function photodel(id, id2){
      if(confirm('�� ������������� ������� ������� ���� '+id)){
      	document.location.href ='?mod=photo&action=delphoto&photo_id='+id+'&parent_id='+id2;

      }
   }
</script>
<h3>������ ����������</h3>

<table class="table">
<tr>
<th> ���������</th><th colspan="2">�����</th>
</tr>
	<?
if (count($_TPL['LISTROW'])){
$i=0;
	foreach($_TPL['LISTROW'] as $ROW){
	?>
		<tr>
		<td><a href="<?=$GLOBALS['p']?>files/gallery/<?=$ROW['parent_id']?>/<?=$ROW['photo_id']?>.jpg" target="_blank"><img src="<?=$GLOBALS['p']?>files/gallery/<?=$ROW['parent_id']?>/__<?=$ROW['photo_id']?>.jpg" border=0 alt="{alt}"></a></td>
		<td><a href="?mod=photo&action=editphoto&photo_id=<?=$ROW['photo_id']?>&parent_id=<?=$ROW['parent_id']?>"><img src="icon/edit_16.png" alt="�������������" border="0" hspace="10"></a></td>
		<!--<td><a href="?mod=photo&action=delphoto&photo_id=<?=$ROW['photo_id']?>&parent_id=<?=$ROW['parent_id']?>"><img src="icon/delete_16.png" alt="�������" border="0" hspace="10"></a></td>-->
		<td><a href="javascript:" onclick="photodel(<?=$ROW['photo_id']?>,<?=$ROW['parent_id']?>); return false"><img src="icon/delete_16.png" alt="�������" border="0" hspace="10"></a></td>
		</tr>

		<?
	}
}
	?>

	</tr>
	</table>