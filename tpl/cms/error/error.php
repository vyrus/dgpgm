<?
	include "tpl/cms/_header.php";
?>
<h1><?=$_TPL['TITLE'][0]?></h1>
<?
$time = date("d.m.Y H:i:s");
?>
Перейдите <a href="/" title="Главная страница">на главную страницу</a> или посетите сайт позже.<br />
<br />
Ваш IP: <b><?=$_SERVER['REMOTE_ADDR']?></b><br />
Ваш браузер: <b><?=$_SERVER['HTTP_USER_AGENT']?></b><br />
Текущее время сервера: <b><?=$time?></b><br />
<?
if (!empty($_SERVER['HTTP_REFERRER'])) echo "Вы пришли со страницы: <b>".$_SERVER['HTTP_REFERRER']."</b><br />";
if (!empty($_SERVER['HTTP_X_FORWARDER_FOR'])) echo "Ваш IP через прокси: <b>".$_SERVER['HTTP_X_FORWARDER_FOR']."</b><br />";
?>

<?=$GLOBALS['SERVER_SIGNATURE']?> 

<?
	include "tpl/cms/_footer.php";
?>
