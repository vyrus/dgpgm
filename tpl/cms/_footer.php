		</div>
  
		</div>
		</div>
        <!-- Контент-конец -->
        
        <!-- Правая колонка -->
		<div class="rightCol">
			<?/*<div class="titleFil">
				<h1>Навигация</h1>
			</div>*/?>
			<?
				include "tpl/cms/users/form_login.php";
			?>	
			
			<? if(USER_ID == 1) { ?>
				<ul class="tabs"><li class="<?=($action=='reg')?'current':'hover'?>"><a href="/reg"><img border="0" src="/adm/icon/attension.png"> Шаг 1<br>Регистрация</a></li>
					<li class="">Шаг 2<br>Данные о заявителе</li>
					<li class="">Шаг 3<br>Техническое задание</li>
					<li class="">Шаг 4<br>Календарный план</li>
					<li class="">Шаг 5<br>Обоснование цены</li>
					<li class="">Шаг 6<br>Печать и отправка</li>
				</ul>
			<? } else { ?>
				<?=$_TPL['BIDMENU']?>
			<? } ?>
			
		</div>
		<!-- Правая колонка конец-->
        <!-- Футер -->
		<div class="clear"></div>
        <div class="footer-sep"></div>
		<div class="footer">
			<div class="block" style="width: 550px;">
				&copy; 2011-<?=date('Y')?> <?=SITE_NAME?><br />
				Адрес: <?=SITE_ADRESS?><br />
				Телефон: <?=SITE_TEL?><br /></div>
				<div class="counters"></div>
		<div class="clear"></div>
        </div>
		<!-- Футер конец-->
	</div>
</div>
<!-- Основная часть сайта конец-->
<!--макс-мин ширина - конец-->
</body>
</html>