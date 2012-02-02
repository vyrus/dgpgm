<?

$_TPL['TITLE'] [] = 'Формирование тематики';
include TPL_CMS."_header.php";
?>
<style>
.claro .dijitDialogTitleBar {
  background-color: #BDC6D5;
}
</style>

<script>
// Load the Tooltip & Dialog widget class
dojo.require("dijit.Tooltip");
dojo.require("dijit.Dialog");

var myDialog;
var cont = '';

dojo.ready(function()
{
    var presser =
    {
        i : <?=$_TPL['WORKATTRIBUTESNUM']?>,
        onPlus : function(evt)
        {
            var curNameArr = this.id.split("_");
            curTD = this.parentNode.parentNode.children[1]; // <td>
            presser.i++;     // new i
            nextP = dojo.create("p", {innerHTML: "<textarea name='"+curNameArr[0]+"[]'></textarea>&nbsp;", style:{opacity:"0"}}, curTD, "last"); // new <p> after last <p>
            sp = dojo.create("span",{"class":"inline"},nextP,"last");
            am = dojo.create("a",{id:curNameArr[0]+"_minus_"+presser.i, innerHTML:"[удалить]"}, sp, "last");
            dojo.connect(am, "onclick", presser.onMinus);
            findElements(); setTooltips(); highlightControlElements();
            dojo.fadeIn({ node:nextP,duration:1000 }).play();
        },
        onMinus : function(evt)
        {
            curP = this.parentNode.parentNode; //last <p>
            var animation = dojo.fadeOut({ node:curP,duration:1000 });
            dojo.connect(animation, "onEnd", function(){
                dojo.destroy(curP);
            });
            animation.play(); // start it up
        }
    }

    /* preventing message on changing dates*/
    dojo.body().className = "claro";

    myDialog = new dijit.Dialog({
        title: "Данные работы по этапу",
        content: cont,
        style: "width:600px;"
    });

    var yearStart = dojo.query("select[name=yearstart]")[0].value;
    var yearEnd = dojo.query("select[name=yearend]")[0].value;
    var monthStart = dojo.query("select[name=monthstart]")[0].value;
    var monthEnd = dojo.query("select[name=monthend]")[0].value;
    dojo.connect(dojo.byId("tzf"),"onsubmit", function(e)
    {
        dojo.stopEvent(e);
        if ((yearStart != dojo.query("select[name=yearstart]")[0].value) || (yearEnd != dojo.query("select[name=yearend]")[0].value))
        {
            myDialog.set("content",
                         "Вы изменили годы начала или окончания работ. Календарный план будет изменен. <br /><input type='button' value='Продолжить'  onclick='dojo.byId(\"tzf\").submit(); myDialog.hide();'><input type='button' value='Отменить' onclick='myDialog.hide();dojo.stopEvent(e);'>");
            myDialog.show();
        } else
        {
            if ((monthStart != dojo.query("select[name=monthstart]")[0].value) || (monthEnd != dojo.query("select[name=monthend]")[0].value))
            {
                myDialog.set("content", "Вы изменили месяцы начала и окончания работ. В календарном плане вам необходимо внести изменения в сроки работ по этапам вручную<br /><input type='button' value='Продолжить' onclick='myDialog.hide(); dojo.byId(\"tzf\").submit();'>");
                myDialog.show();
            } else
            { dojo.byId("tzf").submit();
            }
        }

    })
    /* eo preventing message on changing dates*/

//    fojo.query("input[type='text']").foreach( function(node,i,nodelist){node.value = blockQuote(node.value);});
    findElements(); setTooltips();

    wpp.connect('onclick', presser.onPlus);
    wpm.connect('onclick', presser.onMinus);
    wrp.connect('onclick', presser.onPlus);
    wrm.connect('onclick', presser.onMinus);
    wcp.connect('onclick', presser.onPlus);
    wcm.connect('onclick', presser.onMinus);
    srp.connect('onclick', presser.onPlus);
    srm.connect('onclick', presser.onMinus);
})

function findElements()
{
    wpp = dojo.query("[id='workpurpose_plus']");
    wpm = dojo.query("[id^='workpurpose_minus']");
    wrp = dojo.query("[id='workrequirement_plus']");
    wrm = dojo.query("[id^='workrequirement_minus']");
    wcp = dojo.query("[id='workcondition_plus']");
    wcm = dojo.query("[id^='workcondition_minus']");
    srp = dojo.query("[id='safetyrequirements_plus']");
    srm = dojo.query("[id^='safetyrequirements_minus']");
}
function setTooltips()
{
    var tip = new dijit.Tooltip({ label: '<div class="myTipType">Добавить цель выполнения работ</div>', showDelay: 250, connectId: wpp });
    var tip = new dijit.Tooltip({ label: '<div class="myTipType">Удалить цель выполнения работ</div>', showDelay: 250, connectId: wpm });
    var tip = new dijit.Tooltip({ label: '<div class="myTipType">Добавить требование к выполнению работ</div>', showDelay: 250, connectId: wrp });
    var tip = new dijit.Tooltip({ label: '<div class="myTipType">Удалить требование к выполнению работ</div>', showDelay: 250, connectId: wrm });
    var tip = new dijit.Tooltip({ label: '<div class="myTipType">Добавить условие выполнения работ</div>', showDelay: 250, connectId: wcp });
    var tip = new dijit.Tooltip({ label: '<div class="myTipType">Удалить условие выполнения работ</div>', showDelay: 250, connectId: wcm });
    var tip = new dijit.Tooltip({ label: '<div class="myTipType">Добавить требование к качеству, безопасности</div>', showDelay: 250, connectId: srp });
    var tip = new dijit.Tooltip({ label: '<div class="myTipType">Удалить требование к качеству, безопасности</div>', showDelay: 250, connectId: srm });
}
function blockQuote(s)
{
    return s.replace(/'/g, "&quot;").replace(/"/g, "&quot;");
}


</script>

<?=$TPL['BIDMENU']?>

<h1>Техническое задание</h1>
<p>Заполните поля формы. В любой момент Вы можете сохранить введенные данные нажатием на кнопку внизу формы. Также Вы можете перемещаться между формами используя ссылки на соответствующие шаги в меню слева или ссылки над заголовком формы.</p>
<form method="post" id="tzf">

<table width="100%">
  <tr>
    <td style="width: 250px;">Подпрограмма:</td>
    <td><?=$TPL['INFO']['title_subprogram']?></td>
  </tr>
  <tr>
    <td>Мероприятие:</td>
    <td><?=$TPL['INFO']['title_measure']?></td>
  </tr>
  <tr>
    <td>Наименование предлагаемой темы работ:</td>
    <td>
		<textarea name="work_topic"><?=(isset($TPL['INFO']['work_topic']))? preventXss($TPL['INFO']['work_topic']):''?></textarea>
    </td>
  </tr>
  <tr>
    <td>Место проведения работ:</td>
    <td>
	<select name="place_name">
		<option value="1" <?=($TPL['INFO']['place_name']==1)? 'selected':''?>>город</option>
		<option value="2" <?=($TPL['INFO']['place_name']==2)? 'selected':''?>>поселок</option>
		<option value="3" <?=($TPL['INFO']['place_name']==3)? 'selected':''?>>село</option>
		<option value="4" <?=($TPL['INFO']['place_name']==4)? 'selected':''?>>поселок городского типа</option>
		<option value="5" <?=($TPL['INFO']['place_name']==5)? 'selected':''?>>деревня</option>
	</select><br />
<span class="smalltext">выберите тип населенного пункта</span><br />
<input type="text" value="<?=(isset($TPL['INFO']['place_type_id']))? $TPL['INFO']['place_type_id']:''?>" name="place_type_id"><br>
<span class="smalltext">название выбранного населенного пункта, как в почтовом адресе, без сокращений</span><br />

	<select name="place_district_id">
		<option value="0" <?=($TPL['INFO']['place_district_id']==0)? 'selected':''?>>Укажите регион</option> <?
        foreach ($TPL['REGIONS'] as $reg)
        { ?>
    		<option value="<?=$reg['id']?>" <?=($TPL['INFO']['place_district_id']==$reg['id'])? 'selected':''?>><?=$reg['title']?></option> <?
        } ?>
	</select><br />
<span class="smalltext">укажите регион (область, край)</span><br />

	<select name="place_okrug_id">
		<option value="0" <?=($TPL['INFO']['place_okrug_id']==0)? 'selected':''?>>Укажите округ</option> <?
        foreach ($TPL['OKRUGS'] as $okr)
        { ?>
    		<option value="<?=$okr['id']?>" <?=($TPL['INFO']['place_okrug_id']==$okr['id'])? 'selected':''?>><?=$okr['title']?></option> <?
        } ?>
	</select><br />
<span class="smalltext">укажите федеральный округ</span><br />
</td>
  </tr>
  <tr>
    <td>Год и месяц начала работ:</td>
    <td><?=$TPL['YEARSTART']?>&nbsp;&nbsp;<?=$TPL['MONTHSTART']?>
<!--
    <table>
		<tr><td><?=$TPL['YEARSTART']?><br /><span class="smalltext">год начала работ</span></td>
		<td><?=$TPL['MONTHSTART']?><br /><span class="smalltext">месяц начала работ</span></td></tr>
		<tr><td><?=$TPL['YEAREND']?><br /><span class="smalltext">год окончания работ</span></td>
		<td><?=$TPL['MONTHEND']?><br /><span class="smalltext">месяц окончания работ</span></td></tr>
		</table>-->
    </td>
  </tr>
  <tr>
    <td>Год и месяц окончания работ:</td>
    <td><?=$TPL['YEAREND']?>&nbsp;&nbsp;<?=$TPL['MONTHEND']?>
<!--    <table>
		<tr><td><?=$TPL['YEARSTART']?><br /><span class="smalltext">год начала работ</span></td>
		<td><?=$TPL['MONTHSTART']?><br /><span class="smalltext">месяц начала работ</span></td></tr>
		<tr><td><?=$TPL['YEAREND']?><br /><span class="smalltext">год окончания работ</span></td>
		<td><?=$TPL['MONTHEND']?><br /><span class="smalltext">месяц окончания работ</span></td></tr>
		</table> -->
    </td>
  </tr>
<!--
  <tr>
    <td>Общая стоимость работ, тыс. руб.:</td>
    <td><input type="text" name="price_works_actual" value="<?=(isset($TPL['INFO']['price_works_actual']))? $TPL['INFO']['price_works_actual']:''?>"></td>
  </tr>-->
  <tr>
    <td>Цели выполнения работ:<br /><a id="workpurpose_plus">[добавить]</a></td>
    <td> <?
    if (!empty($_TPL['WORKPURPOSE']))
    {
        foreach($_TPL['WORKPURPOSE'] as $i=>$wp)
        { ?>
		<p><textarea name="workpurpose[]"><?=$wp['title']?></textarea>
		<span class="inline"><a id="workpurpose_minus_<?=$i?>">[удалить]</a></span></p> <?
        }
    }else
    { ?>
		<p><textarea name="workpurpose[]"></textarea>
		<span class="inline"><a id="workpurpose_minus_0">[удалить]</a></span></p> <?
    } ?>
	</td>
  </tr>
  <tr>
    <td>Требования к выполнению работ:<br /><a id="workrequirement_plus">[добавить]</a></td>
    <td> <?
    if (!empty($_TPL['WORKREQUIREMENT']))
    {
        foreach($_TPL['WORKREQUIREMENT'] as $i=>$wr)
        { ?>
		<p><textarea name="workrequirement[]"><?=$wr['work_requirement_title']?></textarea>
		<span class="inline"><a id="workrequirement_minus_<?=$i?>">[удалить]</a></span></p> <?
        }
    }else
    { ?>
		<p><textarea name="workrequirement[]"></textarea>
		<span class="inline"><a id="workrequirement_minus_0">[удалить]</a></span></p> <?
    } ?>
    </td>
  </tr>
  <tr>
    <td>Условия выполнения работ:<br /><a id="workcondition_plus">[добавить]</a></td>
    <td> <?
    if (!empty($_TPL['WORKCONDITION']))
    {
        foreach($_TPL['WORKCONDITION'] as $i=>$wc)
        { ?>
		<p><textarea name="workcondition[]"><?=$wc['work_condition_title']?></textarea>
		<span class="inline"><a id="workcondition_minus_<?=$i?>">[удалить]</a></span></p> <?
        }
    }else
    { ?>
		<p><textarea name="workcondition[]"></textarea>
		<span class="inline"><a id="workcondition_minus_0">[удалить]</a></span></p> <?
    } ?>
    </td>
  </tr>
  <tr>
    <td>Требования к качеству, безопасности выполнения работ:<br /><a id="safetyrequirements_plus">[добавить]</a></td>
    <td> <?
    if (!empty($_TPL['SAFETYREQUIREMENTS']))
    {
        foreach($_TPL['SAFETYREQUIREMENTS'] as $i=>$sr)
        { ?>
		<p><textarea name="safetyrequirements[]"><?=$sr['safety_requirements_title']?></textarea>
		<span class="inline"><a id="safetyrequirements_minus_<?=$i?>">[удалить]</a></span></p> <?
        }
    }else
    { ?>
		<p><textarea name="safetyrequirements[]"></textarea>
		<span class="inline"><a id="safetyrequirements_minus_0">[удалить]</a></span></p> <?
    } ?>
    </td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" value="Сохранить данные формы" name="tzinsert">
    <input type="hidden" value="1" name="tzinsert"></td>
  </tr>
</table>
</form>

<?
    include TPL_CMS."_footer.php";
?>