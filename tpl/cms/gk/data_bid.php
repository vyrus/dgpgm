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
  $('#data_org').html('<a href="/gk/edit_organization/'+id+'">Данные организации</a>');
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
	$('#select_org_id').val(<?=$_TPL['ROW']['id_org_ind']?>);
	var url = '/?mod=forms&action=vieworgname';
       $.get(
           url,
           "id=<?=$_TPL['ROW']['id_org_ind']?>",
           function (data) {
			$('#select_org').text(data);
            }
       );
	$('#data_org').html('<a href="/gk/edit_organization/<?=$_TPL['ROW']['id_org_ind']?>">Данные организации</a>');
<? } ?>

</script>


	<h1>Данные заявки</h1>

	<form method="post">
	
	<input type="hidden" value="" name="id_org_ind" id="select_org_id" />
	
	Организация: <strong><span id="select_org"></span></strong> <span id="data_org"></span> <a href="javascript:" onclick="loadSearch()">Поиск</a>

	<div id="search_org"></div>
	
	<br />

	Конкурс: <select name="lot_id">
	<option <?=(isset($_TPL['ROW']['lot_id']) && $_TPL['ROW']['lot_id']==0)?'cheked="cheked" ':''?>value="0">Номер извещения не выбран</option>
	<? foreach ($TPL['NOTICE'] as $row) { ?>
		<option <?=(isset($_TPL['ROW']['lot_id']) && $_TPL['ROW']['lot_id']==$row['lot_id'])?'cheked="cheked" ':''?>value="<?=$row['lot_id']?>"><?=$row['notice_num']?></option>
	<? } ?>
	</select>
	<br /><br />
	
	Шифр заявки	<input name="cifer" type="text" value="<?=(isset($_TPL['ROW']['cifer']))?htmlspecialchars($_TPL['ROW']['cifer']):''?>" /><br /><br />
	
	Наименование работ	<textarea name="work_title"><?=(isset($_TPL['ROW']['work_title']))?htmlspecialchars($_TPL['ROW']['work_title']):''?></textarea><br /><br />
	
	Победитель	выбор: <input type="radio" value="1" name="winner" <?=(isset($_TPL['ROW']['winner']) && $_TPL['ROW']['winner'] == '1')?' checked="checked"':''?>> да <input type="radio" value="0" name="winner"<?=(isset($_TPL['ROW']['winner']) && $_TPL['ROW']['winner'] == '0')?' checked="checked"':''?>> нет</span><br /><br />
	
	Руководитель работ <input name="director" type="text" value="<?=(isset($_TPL['ROW']['director']))?htmlspecialchars($_TPL['ROW']['director']):''?>" /><br /><br />
	
	Электронная почта для связи: <input name="e_mail" type="text" value="<?=(isset($_TPL['ROW']['e_mail']))?htmlspecialchars($_TPL['ROW']['e_mail']):''?>" /><br /><br />
	
	Телефон для связи: <input name="phone" type="text" value="<?=(isset($_TPL['ROW']['phone']))?htmlspecialchars($_TPL['ROW']['phone']):''?>" /><br /><br />
	
	Цена работ <input name="price" type="text" value="<?=(isset($_TPL['ROW']['price']))?htmlspecialchars($_TPL['ROW']['price']):''?>" /><br /><br />

    <input type="submit" value="Сохранить данные заявки" >
	<br /><br />
	<center><a href="/gk/<?=$_GET['id']?>">Вернуться к редактированию Госконтракта №<?=$TPL['GK']['number']?></a></center>

	</form>

<?
    include TPL_CMS."_footer.php";
?>
