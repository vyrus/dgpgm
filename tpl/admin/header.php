<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title>OpenWEB CMS · Админцентр</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="/files/admin/css/admin.css" type="text/css" />
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="/files/js/cal.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/dojo/1.6/dojo/dojo.xd.js" data-dojo-config="parseOnLoad:true"></script>
<script type="text/javascript" src="/files/admin/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/files/js/thickbox.js"></script>
<link rel="stylesheet" href="/files/css/thickbox.css" type="text/css" media="screen" />
<!-- use the "claro" theme -->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/dojo/1.7.0/dijit/themes/claro/claro.css">

<script type="text/javascript">
$(document).ready(function() {
	$('ul#my-menu ul').each(function(i) { // Check each submenu:
		if ($.cookie('submenuMark-' + i)) {  // If index of submenu is marked in cookies:
			$(this).show().prev().removeClass('collapsed').addClass('expanded'); // Show it (add apropriate classes)
		}else {
			$(this).hide().prev().removeClass('expanded').addClass('collapsed'); // Hide it
		}
		$(this).prev().addClass('collapsible').click(function() { // Attach an event listener
			var this_i = $('ul#my-menu ul').index($(this).next()); // The index of the submenu of the clicked link
			if ($(this).next().css('display') == 'none') {

				// When opening one submenu, we hide all same level submenus:
				$(this).parent('li').parent('ul').find('ul').each(function(j) {
					if (j != this_i) {
						$(this).slideUp(200, function () {
							$(this).prev().removeClass('expanded').addClass('collapsed');
							cookieDel($('ul#my-menu ul').index($(this)));
						});
					}
				});
				// :end

				$(this).next().slideDown(200, function () { // Show submenu:
					$(this).prev().removeClass('collapsed').addClass('expanded');
					cookieSet(this_i);
				});
			}else {
				$(this).next().slideUp(200, function () { // Hide submenu:
					$(this).prev().removeClass('expanded').addClass('collapsed');
					cookieDel(this_i);
					$(this).find('ul').each(function() {
						$(this).hide(0, cookieDel($('ul#my-menu ul').index($(this)))).prev().removeClass('expanded').addClass('collapsed');
					});
				});
			}
		return false; // Prohibit the browser to follow the link address
		});
	});
});
function cookieSet(index) {
	$.cookie('submenuMark-' + index, 'opened', {expires: null, path: '/'}); // Set mark to cookie (submenu is shown):
}
function cookieDel(index) {
	$.cookie('submenuMark-' + index, null, {expires: null, path: '/'}); // Delete mark from cookie (submenu is hidden):
}

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

    dojo.ready(function()
    {
        highlightControlElements();
    })
    /* eo highlight control elements*/
</script>
</head>
<body class="claro">
<div id="container">
   <div id="header"><div class="quit"><a href="/" title="На сайт">На сайт</a></div><div class="quit"><a href="/?action=logout" title="Выйти">Выход</a></div></div>

<div id="body">

<?
 if (!empty($_TPL['ERROR']) && is_array($_TPL['ERROR'])){
echo "<div style='text-align: center; color: red;'>";
 	foreach($_TPL['ERROR'] as $vl){
		echo $vl."<br>";
	}
echo "</div>";
 }
?>