<?
 include "tpl/cms/_header.php";
?>

<div class="main-date"><span><?=$_TPL['ROW']['date_news']?></span></div>

<div class="news_one"><a href="<?=$GLOBALS['p']?>newspage1" title="Новости">Новости</a></div><!--  Подправить ссылку в деп на "2-" -->

<div style="clear: both; border: 5px solid #fff;"></div>
			
<div class="hh1"><h1><?=$_TPL['ROW']['name']?></h1></div>
<div style="clear: both;"></div>


	<?=$_TPL['ROW']['full_news']?>
	<div style="text-align: right;"><em><?=$_TPL['ROW']['author']?></em></div>

<?
include "tpl/cms/_footer.php";
?>