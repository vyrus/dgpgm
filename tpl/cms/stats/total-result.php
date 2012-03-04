<?
    include TPL_CMS."_header.php";
?>
<script language="JavaScript" src="/files/js/outingTable.js"></script>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojo/resources/dojo.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dijit/themes/claro/claro.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojox/grid/resources/claroGrid.css">

<link rel="stylesheet" href="/files/css/gridstyle.css">
<style>
 	.claro table tr:nth-child(0n+3) td:nth-child(1n+2).dojoxGridCell 
 	{
		padding : 0px !important;
		border : 0px !important;
 	} 	

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
    makeOut(<?=$TPL['DATA']?>,"<?=$TPL['STATTITLE']?>");
</script>

<div id="gridContainer"></div>

<br />
<div style="text-align: center;"><a href="/files/excel/total.xls">Сформировать файл Excel</a></div>

<?
    include TPL_CMS."_footer.php";
?>
