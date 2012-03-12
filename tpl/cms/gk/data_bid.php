<?
    include TPL_CMS."_header.php";
?>
<script type= "text/javascript">
function loadSearch(){
  $('#search_org').load('/gk/search_organization')
}

function selectOrg(id,title){
  $('#select_org').text(title);
  $('#select_org_id').val(id);
  $('#search_org').text('');
  //$('#data_org').html('<a href="/gk/<?=$_GET['id']?>/bid/edit_organization/'+id+'">Данные организации</a>');
  $('#data_org').html('<a href="javascript:" onClick="saveFormRedirect()">Данные организации</a>');
}

function viewOrg(id) {
	var url = '/?mod=forms&action=vieworg';
       $.get(
           url,
           "id=" + id,
           function (data) {
			$('#orginfo').html(data);
            }
       );
}

<? if (isset($_TPL['ROW']['id_org_ind']) && !empty($_TPL['ROW']['id_org_ind'])) { ?>
 $(document).ready(function () {
	//$('#data_org').html('<a href="/gk/<?=$_GET['id']?>/bid/edit_organization/<?=$_TPL['ROW']['id_org_ind']?>" target="_blank">Данные организации</a>');
	$('#data_org').html('<a href="javascript:" onClick="saveFormRedirect()">Данные организации</a>');
	
	$('#select_org_id').val(<?=$_TPL['ROW']['id_org_ind']?>);
  });
  
	var url = '/?mod=forms&action=vieworgname';
       $.get(
           url,
           "id=<?=$_TPL['ROW']['id_org_ind']?>",
           function (data) {
			$('#select_org').text(data);
            }
       );
	   
<? } ?>

function saveFormRedirect() {
	$.post('<?=$_SERVER['REQUEST_URI']?>',  $("#bidForm").serialize(), function(result) {
		if (!result) {
			return false;
		}
	}, "json");
	setTimeout('redirect()',1000);
}

function redirect() {
	window.location.href = '/gk/<?=$_GET['id']?>/bid/edit_organization/<?=$_TPL['ROW']['id_org_ind']?>';
}
</script>


	<h1>Данные заявки Госконтракта №<?=$TPL['GK']['number']?></h1>

<table style="width: 100%">
	<tr>
		<td style="width: 250px">Организация:</td>
		<td><strong><span id="select_org"></span></strong> <span id="data_org"></span> <a href="javascript:" onclick="loadSearch()">Поиск</a></td>
	</tr>
	<tr>
		<td colspan="2">
		<div id="search_org"></div>
		</td>
	</tr>
</table>
<form method="post" id="bidForm">
<table style="width: 100%">
	<tr>
		<td style="width: 250px">Конкурс:</td>
		<td><input type="hidden" value="" name="id_org_ind" id="select_org_id" /><select name="lot_id">
	<option <?=(isset($_TPL['ROW']['lot_id']) && $_TPL['ROW']['lot_id']==0)?'selected ':''?>value="0">Номер извещения не выбран</option>
	<? foreach ($TPL['NOTICE'] as $row) { ?>
		<option <?=(isset($_TPL['ROW']['lot_id']) && $_TPL['ROW']['lot_id']==$row['lot_id'])?'selected ':''?>value="<?=$row['lot_id']?>"><?=$row['notice_num']?></option>
	<? } ?>
	</select></td>
	</tr>
	<tr>
		<td>Шифр заявки:</td>
		<td><input name="cifer" type="text" value="<?=(isset($_TPL['ROW']['cifer']) && $_TPL['ROW']['cifer'] != 'null')?htmlspecialchars($_TPL['ROW']['cifer']):''?>" /></td>
	</tr>
	<tr>
		<td>Наименование работ:</td>
		<td><textarea name="work_title"><?=(isset($_TPL['ROW']['work_title']) && $_TPL['ROW']['work_title'] != 'null')?htmlspecialchars($_TPL['ROW']['work_title']):''?></textarea></td>
	</tr>
	<tr>
		<td>Победитель	выбор:</td>
		<td><input type="radio" value="1" name="winner" <?=(isset($_TPL['ROW']['winner']) && $_TPL['ROW']['winner']=='1')?' checked="checked"':''?>> да <input type="radio" value="0" name="winner"<?=(isset($_TPL['ROW']['winner']) && $_TPL['ROW']['winner']=='0')?' checked="checked"':''?>> нет</span></td>
	</tr>
	<tr>
		<td>Руководитель работ:</td>
		<td><input name="director" type="text" value="<?=(isset($_TPL['ROW']['director']) && $_TPL['ROW']['director'] != 'null')?htmlspecialchars($_TPL['ROW']['director']):''?>" /></td>
	</tr>
	<tr>
		<td>Электронная почта для связи:</td>
		<td><input name="e_mail" type="text" value="<?=(isset($_TPL['ROW']['e_mail']) && $_TPL['ROW']['e_mail'] != 'null')?htmlspecialchars($_TPL['ROW']['e_mail']):''?>" /></td>
	</tr>
	<tr>
		<td>Телефон для связи:</td>
		<td><input name="phone" type="text" value="<?=(isset($_TPL['ROW']['phone']) && $_TPL['ROW']['phone'] != 'null')?htmlspecialchars($_TPL['ROW']['phone']):''?>" /></td>
	</tr>
	<tr>
		<td>Цена работ:</td>
		<td><input name="price" type="text" value="<?=(isset($_TPL['ROW']['price']) && $_TPL['ROW']['price'] != 'null')?htmlspecialchars($_TPL['ROW']['price']):''?>" /></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center;"><br /><input type="submit" value="Сохранить данные заявки" ></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center;"><br /><a href="/gk/gk/<?=$_GET['id']?>">Вернуться к редактированию Госконтракта №<?=$TPL['GK']['number']?></a></td>
	</tr>
</table>
</form>

<?
    include TPL_CMS."_footer.php";
?>
