<?
include TPL_CMS."_header.php";
?>

<?=$TPL['BIDMENU']?>
  
<h1>Обоснование цены</h1>

<a href="/forms/bid/<?=$_GET['id']?>/price">Расчет по другой методике</a><br /><br />

<style>
.table td input[type="text"] {
	width: 100px;
}
.table td input[type="file"] {
	border: 1px solid #999999;
}
.table td select {
	width: 100px;
}
</style>
<form method="post" id="priceForm" enctype="multipart/form-data"> 

<table class="table">
<tbody>
  <tr>
    <td>Стоимость выполняемых работ (услуг) в текущих ценах (тыс. руб.)</td>
    <td><input type="text" name="price_works_actual" value="<?=(!empty($TPL['INFO']['price_works_actual']))?$TPL['INFO']['price_works_actual']:""?>" /></td>
  </tr>
  <tr>
    <td>Ставка НДС (%)</td>
    <td><input type="text" name="user_nds" value="<?=(!empty($TPL['INFO']['user_nds']))?($TPL['INFO']['user_nds']*100):"18"?>" /></td>
  </tr>
  <tr>
    <td>Добавить файл расчета стоимости<br />
	<span style="color: red;">только в формате *.pdf</span></td>
    <td><input type="file" name="price"><br />
<? if (!empty($_GET['id']) && file_exists(PRICE_PDF.$_GET['id'].'.pdf')){ ?>
	<a href="/files/price/<?=$_GET['id']?>.pdf" title="Файл расчета стоимости" style="font-size: 13px;">Загруженный файл расчета стоимости</a> <a href="javascript;" onclick="delpdf(<?=$_GET['id']?>); return false" title="Удалить"><img src="/adm/icon/delete_16.png" alt="Удалить"></a>
<? } ?></td>
  </tr>
</tbody> 
</table>
<br />
<input type="submit" name="send" value="Сохранить"<?=$form_dis?>>

<div style="clear: both;"></div>

</form>							   
<script>
   function delpdf(id){
      if(confirm('Вы действительно желаете удалить файл')){
      	document.location.href ='/?mod=forms&action=delete-pdf&id='+id;
      }
   }
</script>
<?
    include TPL_CMS."_footer.php";
?>