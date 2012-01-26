<?
include "header.php";
?>
<form action="" method="post">
<div class="bx-auth-form">
	<div class="bx-auth-header">Пожалуйста, авторизуйтесь	</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tbody><tr>
	<td>
	<div class="bx-auth-picture"></div>
	</td>
	<td>
	<div class="bx-auth-table">

	
	<table border="0" cellpadding="0" cellspacing="0">
		<tbody><tr>
			<td class="bx-auth-label">Логин:</td>
			<td><input name="login" maxlength="50" size="20" class="bx-auth-input-text" type="text"></td>
		</tr>
		<tr>
			<td class="bx-auth-label">Пароль:</td>
			<td><input name="passwd" maxlength="50" size="20" class="bx-auth-input-text" type="password"></td>

		</tr>
		<tr>
			<td></td>
			<td><input name="Login" value="Авторизоваться" type="submit"></td>

		</tr>
	</tbody></table>
	</div>
	<br clear="all">
	</td>
</tr>
</tbody></table>
</div>
</form>

<?
include "footer.php";
?>