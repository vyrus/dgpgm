<?
  include "tpl/cms/_header.php";
?>




			<!-- Заголовок -->	
			  <h1><?=$_TPL['ROW']['name']?></h1> 
			<div class="clr"></div>
			<!-- Заголовок конец-->
			<?=$_TPL['ROW']['content']?>

<?/*
// Тут перечисляем через запятую номера, в которых не нужно "в этом разделе"
$a =array(2,9);

if(!in_array($id, $a) && !empty($_TPL['LISTARTICLE']) && $_SERVER['REQUEST_URI']!='/'){
    echo "<div class=\"punktir clr\"></div>
	<div class=\"tut\">В этом разделе:</div>
	<ul>";
	foreach($_TPL['LISTARTICLE'] as $ROW) {
	if ($id!=$ROW['id']){?><li><a href="<?=$GLOBALS['p']?>article<?=$ROW['id']?>"><?=$ROW['name']?></a></li>
	
    <?}
	}
	echo "</ul>";
}*/
?>
<div class="clr"></div>

<?
  include "tpl/cms/_footer.php";
?>