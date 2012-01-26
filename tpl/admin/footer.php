</div>

<div id="admin_left_block">
<h3>Навигация</h3>

	<ul id="my-menu" class="sample-menu">
	<?

		foreach($_MODCONFIG as  $modname=>$cnf){
			if ($access[$modname]) {?> <li><a href="?mod=<?=$modname?>"><?=$cnf['name']?></a>
	<?
		//if (!empty($cnf['sub']) && !empty($_GET['mod']) && $_GET['mod']==$modname){ echo $cnf['sub'];}
		echo $cnf['sub'];
	?>
	</li>
	<?
		}
		}
	?>
	</ul>

</div>

</div>
</body></html>