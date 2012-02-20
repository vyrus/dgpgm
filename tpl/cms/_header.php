<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html style="background-attachment: fixed;" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?
  if(!empty($_TPL['SEO_TITLE'])){
    echo $_TPL['SEO_TITLE'];

  }elseif (count($_TPL['TITLE'])){
        echo implode(" - ", $_TPL['TITLE']);
  }
?> · <?=SITE_NAME?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?=!empty($_TPL['KEYWORDS'])?$_TPL['KEYWORDS']:''?>">
<meta name="description" content="<?=!empty($_TPL['DESCRIPTION'])?htmlspecialchars($_TPL['DESCRIPTION']):''?>">
<link href="<?=$GLOBALS['p']?>files/css/style.css" rel="stylesheet" type="text/css" media="screen" />
<!--<link rel="shortcut icon" href="<?=$GLOBALS['p']?>files/images/favicon.ico" type="image/x-icon" />-->
<script src="http://code.jquery.com/jquery-latest.js"></script>
<!--<script src="http://ajax.googleapis.com/ajax/libs/dojo/1.6/dojo/dojo.xd.js" data-dojo-config="parseOnLoad:true"></script>-->
<script src="http://ajax.googleapis.com/ajax/libs/dojo/1.7.1/dojo/dojo.js" type="text/javascript"></script>

<script src="/files/js/cal.js"></script>
<!-- use the "claro" theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.0/dijit/themes/claro/claro.css">
<script>
  $(document).ready(function(){

    $("button.toggle").click(function () {
      $("div.toggle").slideToggle(300);
    });

  });

    /* highlight control elements  подсветка элементов управления. если выглядит позорно, то удалить этот блок до закрывающего коммента*/
    dojo.require("dojo.fx.easing");
    var controlElaments = new dojo.NodeList();
    function highlightControlElements()
    {
        dojo.query("input").filter(function(item){
            return (item.type != "submit") && (item.type != "button");
        }).forEach(function(node,i,arr){controlElaments.push(node)});
        dojo.query("textarea").forEach(function(node,i,arr){controlElaments.push(node)});
        controlElaments.connect("onmouseover",function(){dojo.animateProperty
            ({
                easing: dojo.fx.easing.bounceOut,
                duration: 500,
                node: this,
                properties: {backgroundColor:'#dde6f0'}
            }).play();}
        );
        controlElaments.connect("onmouseout",function(){dojo.animateProperty
            ({
                easing: dojo.fx.easing.bounceOut,
                duration: 500,
                node: this,
                properties: {backgroundColor:'#fff'}
            }).play();}
        );
    }

    require(["dojo/domReady!"], function() 
    {
        highlightControlElements();
    });
    /* eo highlight control elements*/
  </script>
</head>
<body class="claro">
<div id="solid_top"><div>
<div id="center"><!--макс-мин ширина-->
	<div class="content-area" id="content-area">
		<!-- Контент -->
		<div class="wrapper">
		<div class="article">
		<!-- ШАПКА -->
		<div class="glubina">
			<div class="logo"></div>
			<div class="logo-name">
			<h2>Департамент градостроительной политики города Москвы</h2>
			<h3>Управление научно-технической политики</h3>
			</div>
		
			<div style="clear: both;"></div>
		</div>
		<!-- НЕТ ШАПКА-->	
	  
		<!-- Горизонтальная навигация -->
		<div class="horizont_menu">
			<div style="float: left;">
				<a href="" title="">Государственная программа города Москвы "Градостроительная политика" на 2012-2016 г.</a>
				<a href="/" title="">Формирование тематики</a>
				<a href="" title="">Вопросы и ответы</a>
			</div>
		</div>
		<!-- Горизонтальная навигация -->
		<div class="text-area inwrap ">
<?
if(!empty($_TPL['ERROR'])){
?>
		<div style="text-align: center; color:red;"><?=implode("<br />", $_TPL['ERROR'])?></div>
<?
}
?>