<?php
    include TPL_CMS."_header.php";
?>
<script language="JavaScript" src="/files/js/finance-program-table.js"></script>

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

div.text-area {
line-height: 2;
}
</style>

<script>
    renderProgramTable(<?=$TPL['program_data']?>, "<?=$TPL['year']?>", "gridProgramContainer");
</script>

<h1>Финансовая справка по реализации программы</h1><br />

<div id="gridProgramContainer"></div>

<?php if (isset($TPL['subprogram_data'])): ?>
<script>
    renderSubprogramTable(<?=$TPL['subprogram_data']?>, "<?=$TPL['year']?>", "gridSubprogramContainer");
</script>

<h1>Финансовая справка по реализации подпрограммы <?php echo $TPL['subprogram_id'] ?> за <?php echo $TPL['year'] ?> год на <?php echo $TPL['date'] ?></h1><br />

<div id="gridSubprogramContainer"></div>
<?php endif; ?>

<br />
<div style="text-align: center;"><a href="/files/excel/finance.xls">Сформировать файл Excel</a></div>

<?php
    include TPL_CMS."_footer.php";
?>
