<? if ($_GET['mod'] == 'users' && $_GET['action'] == 'reg') { ?>
<br /><br /><a href="/" title="Вернуться к информационному сообщению">Вернуться к информационному сообщению</a>
<? } else {?>
<div class="login">
<?
if(USER_ID<2){
?>
<form method="POST">
<table>
	<tr>
		<td>Логин</td><td><input type="text" value="" name="login" style="width: 100px;"></td>
	</tr>
	<tr>
		<td>Пароль</td><td><input type="password" value="" name="passwd" style="width: 100px;"></td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" value="Вход" style="margin-left: 35px;" name="enter"></td>
	</tr>
</table>
</form>
<br />
<?
}else{
?>
Здравствуйте!<br />
Вы авторизованы под логином <b><?=USER_LOGIN?></b><br />
<br />
<? if (USER_GROUP == 2) { ?>
<a href="/help">Инструкция по прохождению регистрации и процедуре подачи заявки</a><br /><br />
<? } elseif (in_array(USER_GROUP,array(1,3))) { ?>
	<a href="/adm">Перейти в админцентр</a><br /><br />
<? } ?>

<a href="/logout" title="Выйти">Выход</a><br />
<? } ?>
</div>
<? } ?>