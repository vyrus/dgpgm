<h4>Результаты поиска</h4>
	
	<?	if (!empty($TPL['ORG'])) { ?>
		<strong>Найдены следующие организации:</strong>
			<ul>
			<? foreach ($TPL['ORG'] as $row) { ?>
				<li><?=$row['full_title']?> <a onClick='selectOrg(<?=$row['id']?>,<?=json_encode($row['full_title'])?>)' href='javascript:'>выбрать</a></li>
			<? } ?>
			</ul>
		<? } else { ?>
		<h3>Нет результатов, удовлетворяющих запросу.</h3>
		<? } ?>