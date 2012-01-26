<?
	include $_SERVER['DOCUMENT_ROOT']."/tpl/admin/header.php";
	include($_SERVER['DOCUMENT_ROOT']."/editor/spaw.inc.php");
	$spaw1 = new SpawEditor("spaw1");
?>
	<form  method="post">
	<table class="table">
		<tr>
		<th>
			Правый блок сайта
		</th>
		</tr>
  		<tr>
		<td>
		<? /*<textarea rows="15" cols="60" id="rightblock" name="rightblock"><?=$_TPL['RIGHTBLOCK']?></textarea>*/ ?>
			
<?		$spaw1 = new SPAW_Wysiwyg("rightblock",$_TPL['RIGHTBLOCK']);
		$spaw1->show();
?>		
		</td>
		</tr>
  		<tr>
		<td align="center">
<input type="submit" value="Сохранить">
		</td>
		</tr>

	</table>
	</form>