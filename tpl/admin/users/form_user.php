<? include "tpl/admin/header.php" ?>

<h3>Добавить пользователя</h3>

<form action="" method="post" enctype="multipart/form-data">

<table class="table">
<tr>
	<th>Логин</th>
	<td><input type="text" name="login_1" value="<?=$_TPL['ROW']['login_1']?>" size="20"></td>
</tr>
<tr>
	<th>Имя</th>
	<td><input type="text" name="name" value="<?=$_TPL['ROW']['name']?>" size="20"></td>
</tr>
<tr>
	<th>Пароль</th>
	<td><input type="password" name="passwd_1" value="<?=@$_TPL['ROW']['passwd_1']?>" size="20"></td>
</tr>
<tr>
	<th>Пароль еще раз</th>
	<td><input type="password" name="passwd_2" value="<?=@$_TPL['ROW']['passwd_2']?>" size="20"></td>
</tr>

<tr>
	
	<td colspan="2" align="center"><input type="submit" value="Применить"></td>
</tr>
</table>


</form>