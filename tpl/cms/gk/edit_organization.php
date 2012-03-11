<?
    include TPL_CMS."_header.php";
?>
<script>
$(document).ready(function () {
    $("#org-info-descr").hide();
});
</script>

<div style="text-alighn: center;"><a href="<?=$back?>">Вернуться</a></div>
<?
	include "tpl/cms/forms/info-org-form.php";
?>
<div style="text-alighn: center;"><a href="<?=$back?>">Вернуться</a></div>
<?
    include TPL_CMS."_footer.php";
?>
