<?
if ($_SERVER['REQUEST_URI']!==$GLOBALS['p']){
?>   
<h1>Новости</h1>
<?
} else {
?>
<div style="background: #E0E3EB; line-hieght: 30px; width: 100%;"><h3 style="padding: 0px 0px 0px 15px; font-weight: bold;">Новости Малоархангельского района</h3></div>
<?
}
?>
<?
if (!empty($_TPL['M_NEWS'])){
    foreach($_TPL['M_NEWS'] as $ROW){
?>
<div style="float: left; border-bottom: 1px dashed #999;"><?=$ROW['date_news']?></div>

<div style="margin: 0px 0px 0px 70px"><a href="<?=$GLOBALS['p']?>news<?=$ROW['id']?>"><?=$ROW['name']?></a><br />
<?=$ROW['short_news']?></div>
<div style="background:transparent url(<?=$GLOBALS['p']?>files/images/border-x.png) left bottom repeat-x;margin:0 0 10px 70px;padding:10px 0; padding-bottom:7px !important;" class="clr"></div>
<?}
}
?>

<?
if ($_SERVER['REQUEST_URI']!==$GLOBALS['p']){
?>   
<div id="pagination">
<?=$_TPL['PAGES_NEWS']?>
</div>
<?
} else {
?>
<div id="pagination" style="float: right;">
	<a href="<?=$GLOBALS['p'];?>newspage1" title="Новости">Все новости</a>
</div>
<?
}
?>