<?
	include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";

	include $_SERVER['DOCUMENT_ROOT']."/editor/spaw.inc.php";
	$spaw1 = new SpawEditor("spaw1");

if ($_GET['action'] == "edit") {$vtitle = "Редактировать";}
if ($_GET['action'] == "add") {$vtitle = "Создать";}

?>
<h3><?=$vtitle?> страницу / раздел</h3>

<form method="post">
      <table class="table">
      <tr><th>SEO title (для поисковых систем)</th></tr>
      <tr><td><input type="text" name="seo_title" style="width:100%" size="60" value="<?=(isset($_TPL['ROW']['seo_title'])?htmlspecialchars($_TPL['ROW']['seo_title']):'')?>">
     </td></tr>
	  <tr><th>Ключевые слова (для поисковых систем)</th></tr>
      <tr><td><input type="text" id="keywords" name="keywords" style="width:100%" size="60" value="<?=(isset($_TPL['ROW']['keywords'])?htmlspecialchars($_TPL['ROW']['keywords']):'')?>">
  </td></tr>
	<tr><th>Название</th></tr>
      <tr><td><input type="text" name="name" style="width:100%" size="60" value="<?=(isset($_TPL['ROW']['name'])?htmlspecialchars($_TPL['ROW']['name']):'')?>">
     </td></tr>
     <tr><th>Короткое описание, выводится в качестве анонса и в тэге description</th></tr>
      <tr><td><textarea rows="10" id="short_note" name="short_note" cols="40" style="width:100%"><?=(isset($_TPL['ROW']['short_note'])?$_TPL['ROW']['short_note']:'')?></textarea>
	  </td></tr>
      <tr><th>Основное содержание</th></tr>
      <tr><td>
<?
		$spaw1 = new SPAW_Wysiwyg("content",$_TPL['ROW']['content']);
		$spaw1->show();
?>
      </td></tr>
      <tr><td>Дата <input type="text" name="date_article" value="<?=(isset($_TPL['ROW']['date_article'])?$_TPL['ROW']['date_article']:date('Y-m-d H:i:s', time()))?>">
      Опубликовать <input type="checkbox" name="state" value="1"  <?=(!empty($_TPL['ROW']['state'])?'checked':'')?>>
      <select name="view">
          <option value="list" <?=((!empty($_TPL['ROW']['view']) && $_TPL['ROW']['view']=='list')?'selected':'')?>>Раздел</option>
          <option value="view" <?=((!empty($_TPL['ROW']['view']) && $_TPL['ROW']['view']=='view')?'selected':'')?>>Страница</option>
      </select>
<br>
формат даты гггг-мм-дд чч:мм:cc

      </td></tr>
      <tr><td> Номер сортировки <input type="text" name="num" value="<?=(empty($_TPL['ROW']['num'])?'':$_TPL['ROW']['num'])?>"></td></tr>
      <tr><td align="center"><input type="submit" value="Опубликовать"></td></tr>

      </table>

 </form>
 <div style="text-align:center;"><a href="?mod=article&action=list&id=<?=($_GET['action']=='add')?$_GET['id']:$_TPL['ROW']['parent_id']?>">Вернуться назад</a></div>