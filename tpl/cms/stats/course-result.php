<?
include TPL_CMS."_header.php";
?>
<script language="JavaScript" src="/files/js/outingCourse.js"></script>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojo/resources/dojo.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dijit/themes/claro/claro.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojox/grid/resources/claroGrid.css">

<link rel="stylesheet" href="/files/css/gridstyle.css">

<style>
 	.claro .dojoxGridInvisible .dojoxGridCell 
 	{
		outline: none;
		padding : 0px !important;
		border : 0px !important;
		word-wrap: break-word;
		border-color: transparent #E5DAC8 #E5DAC8 transparent;
	}
</style>

<script>
    makeOut(<?=$TPL['DATA']?>);
</script>

<h1>Ход заявочной кампании</h1>

<h2><?=$TPL['STATTITLE']?></h2>
<div id="gridContainer"></div>

<br />
<div style="text-align: center;"><a href="/files/excel/course.xls">Сформировать файл Excel</a></div>

<?/*
echo "<pre>";
print_r($work_steps);
echo "</pre>";*/
?>

<?
    include TPL_CMS."_footer.php";
?>