<?
$_TPL['TITLE'] [] = 'Редактирование подпрограмм';

include TPL_CMS."_header.php";
?>

<h1>Редактирование подпрограмм</h1>

<button class="toggle">Добавить подпрограмму</button>
<div class="toggle" style="display: none">
    <form action="" method="post" id="new-subprog">
        <p>Укажите название:</p>
        <input type="text" value="" name="title">
        <table>
          <tr>
            <td><input type="button" name="send" value="Создать"></td>
            <td><input type="button" name="send" value="Отменить"></td>
          </tr>
        </table>
    </form>
</div>
<div class="toggle" style="display: none">
    <form action="" method="post" id="new-subprog">
        <p>Укажите новое название:</p>
        <input type="text" value="" name="title">
        <table>
          <tr>
            <td><input type="button" name="send" value="Изменить"></td>
            <td><input type="button" name="send" value="Отменить"></td>
          </tr>
        </table>
    </form>
</div>
<br />
<strong>Зарегистрированные подпрограммы:</strong>
<table class="table">
  <tr>
    <th>№ п/п </th>
    <th>Подпрограмма</th>
    <th>Редактировать</th>
  </tr>
<? foreach ($TPL['SUBPROG'] as $row) {?>
  <tr>
    <td><?=$row['id']?></td>
    <td><?=$row['title']?></td>
    <td><button class="toggle">Редактировать</button></td>
  </tr>
  <tr>
  </tr>
 <? } ?>
</table>
<?
    include TPL_CMS."_footer.php";
?>