<?
    include TPL_CMS."_header.php";
?>
<script language="JavaScript" src="/files/js/outingTable.js"></script>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojo/resources/dojo.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dijit/themes/claro/claro.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojox/grid/resources/claroGrid.css">

<link rel="stylesheet" href="/files/css/gridstyle.css">

<script>
    makeOut(<?=$TPL['DATA']?>,"<?=$TPL['STATTITLE']?>");
</script>

<div id="gridContainer"></div>

<br />
<div style="text-align: center;"><a href="#">Сформировать файл Excel</a></div>

<?
    include TPL_CMS."_footer.php";
?>
