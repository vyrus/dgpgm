<?
	include "tpl/admin/header.php";
?>
   <script type="text/javascript" src="/FCKeditor/fckeditor.js"></script>
 <form method="post">
      <table border=0 center="center" width="600" align="center">
      <tr><td>Заголовок</td></tr>
      <tr><td><input type="text" name="name" style="width:100%" size="60" value="<?=(isset($_TPL['ROW']['name'])?$_TPL['ROW']['name']:'')?>">
            <input type="hidden" name="short_note" value=''>
      </td></tr>

      <!--
      <tr><td>анатация к статье</td></tr>

      <tr><td><textarea rows="10" id="short_note" name="short_note" cols="40" style="width:100%"><?=(isset($_TPL['ROW']['short_note'])?$_TPL['ROW']['short_note']:'')?></textarea>
        <script>
	  var oFCKeditor = new FCKeditor( 'short_note' ) ;
        oFCKeditor.BasePath = "/FCKeditor/" ;
        oFCKeditor.ReplaceTextarea() ;
		oFCKeditor.Height = 600;
	</script>
        -->
      </td></tr>

      <tr><td>статья</td></tr>
      <tr><td><textarea rows="10" name="content" id="content"   cols="40" style="width:100%"><?=(isset($_TPL['ROW']['content'])?$_TPL['ROW']['content']:'')?></textarea>
          <script>
	  var oFCKeditor = new FCKeditor( 'content' ) ;
        oFCKeditor.BasePath = "/FCKeditor/" ;
        oFCKeditor.ReplaceTextarea() ;
		oFCKeditor.Height = 800;
	</script>

      </td></tr>
      <tr><td>Дата сортировки <input type="text" name="date_article" value="<?=(isset($_TPL['ROW']['date_article'])?$_TPL['ROW']['date_article']:date('Y-m-d H:i:s', time()))?>">
      Опубликовать <input type="checkbox" name="state" value="1"  <?=(!empty($_TPL['ROW']['state'])?'checked':'')?>>

      <select name="view">
          <option value="list" <?=((!empty($_TPL['ROW']['view']) && $_TPL['ROW']['view']=='list')?'selected':'')?>>Список вложенных элементов</option>
          <option value="view" <?=((!empty($_TPL['ROW']['view']) && $_TPL['ROW']['view']=='view')?'selected':'')?>>Показать страницу</option>

      </select>
<br>
формат даты гггг-мм-дд чч:мм:cc

      </td></tr>
      <tr><td align="center"><input type="submit" value="Применить"></td></tr>

      </table>

 </form>


<?
	include "tpl/admin/footer.php";
?>
