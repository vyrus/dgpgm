<?php
    include TPL_CMS."_header.php";
?>
<script language="JavaScript" src="/files/js/outingTable.js"></script>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojo/resources/dojo.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dijit/themes/claro/claro.css">
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojox/grid/resources/claroGrid.css">
<style>
    #grid
    {
        height: 570px;
        width: 920px;
    }
    button
    {
        background-color: #E4F2FF;
        background-image: url("http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dijit/themes/claro/form/images/button.png");
        background-position: center top;
        background-repeat: repeat-x;
        border: 1px solid #769DC0;
        border-radius: 4px 4px 4px 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.15);
        font-size: 1em;
        padding: 2px 8px 4px;
    }
    button:hover
    {
        background-color: #AFD9FF;
        color: #000000;
    }
    
    .dojoxGridRowTable tr {
        background: none !important;
    }
</style>

<script>
    makeOut(<?=$TPL['DATA']?>,"<?=$TPL['STATTITLE']?>");
</script>

<div id="gridContainer"></div>

<br />
<div style="text-align: center;"><a href="#">Сформировать файл Excel</a></div>

<?php
    include TPL_CMS."_footer.php";
?>
