<?
include TPL_CMS."_header.php";
?>

<style>
.claro .dijitDialogTitleBar {
  background-color: #BDC6D5;
}
.local_red
{
  color: #FF0000;
}
.local_grey
{
  color: #CCCCCC;
}
.notice
{
    background: none repeat scroll 0 0 #ECEFF2;
    border: 1px solid #ccd5e0;
    border-radius: 8px 8px 8px 8px;
    color: #000000;
    padding: 0.8em;
    width: 88%;
}
</style>

<script>
var bidid_stepid;
var editingWork;
var cont = '';
var myDialog;
var w_edit_links_arr = [];

dojo.require("dojo.NodeList-data");

// Load the Tooltip & Dialog widget class
dojo.require("dijit.Tooltip");
dojo.require("dijit.Dialog");

dojo.ready(function()
{
    dojo.body().className = "claro";

    myDialog = new dijit.Dialog({
        title: "Данные работы по этапу",
        content: cont,
        style: "width:600px;"
    });

    /* work handlers : add, prepare for edit, del*/
    dojo.query(".work_plus").connect("onclick",function(){bidid_stepid = this.name; cont = formWorkContent('','','newWorkHandler'); myDialog.set("content", cont); myDialog.show();});
    dojo.query(".work_minus").forEach(
        function(node, index, nodelist)
        {
            dojo.connect(node,"onclick",function(){requestWorksData('/?mod=forms&action=modifywork&del=1&workid='+this.id,'');});
            dojo.connect(node.nextSibling.nextSibling,"onclick",function() { requestWorkData('/?mod=forms&action=getwork&workid='+node.id);} );
        }
    );
    /* step handlers: del & add*/
    dojo.query(".del_icon").connect("onclick", deleteStep);
    dojo.query("[name='addstep']").connect("onclick", addStep);

    /*tooltips*/
    w_plus_arr = dojo.query(".work_plus");
    var tip = new dijit.Tooltip({
        label: '<div class="myTipType">Добавить работу</div>',
        showDelay: 250,
        connectId: w_plus_arr
    });
    w_minus_arr = dojo.query(".work_minus");
    var tip = new dijit.Tooltip({
        label: '<div class="myTipType">Удалить работу</div>',
        showDelay: 250,
        connectId: w_minus_arr
    });
    w_minus_arr.forEach(function(node,i,arr){w_edit_links_arr[i] = node.nextSibling.nextSibling; });
    var tip = new dijit.Tooltip({
        label: '<div class="myTipType">Редактировать данные о работе</div>',
        showDelay: 250,
        connectId: w_edit_links_arr
    });
    w_attension = dojo.query(".attension");
    var tip = new dijit.Tooltip({
        label: '<div class="myTipType">Не заполнено описание работы</div>',
        showDelay: 250,
        connectId: w_attension
    });
    st_minus = dojo.query(".del_icon");
    var tip = new dijit.Tooltip({
        label: '<div class="myTipType">Удалить этап </div>',
        showDelay: 250,
        connectId: st_minus
    });
    /*eo tooltips*/

    dojo.query("select.step_date").connect("onchange",remakeRestCombos);
});

function formWorkContent(wt,wd,handlerName)
{
    return "<table>"+
                "<tr>"+
                  "<td>Наименование вида работы</td>"+
                  "<td><textarea id='work_name' name='work_name'>"+wt+"</textarea></td>"+
                "</tr>"+
                "<tr>"+
                  "<td>Краткое описание выполняемой работ</td>"+
                  "<td><textarea id='work_description' name='work_description'>"+wd+"</textarea></td>"+
                "</tr>"+
                "<tr>"+
                    "<td colspan='2'><input type='submit' value='Сохранить работу' name='newwork' onclick='"+handlerName+"();'></td>"+
                "</tr>"+
              "</table>";
}

function requestWorkData(to)
{
    dojo.xhrPost(
    {
        url: to,
        handleAs: 'json',
        load: function(jsonworkslist)
        {
            cont = formWorkContent(jsonworkslist.title,jsonworkslist.description,'editWorkHandler');
            editingWork = jsonworkslist.work_id;
            myDialog.set("content", cont);
            myDialog.show();
        },
        error: function(data){// alert(data);
        }
    }
    );
}

function requestWorksData(to,parcel)
{
    dojo.xhrPost( {
        url: to,
        content: parcel,
        handleAs: 'json',
        load: function(jsonworkslist)
        {
            bid_step = "<?=$bid_id?>_"+jsonworkslist[0].ws;
            var htmlcontent = "";
            var funny_counter_for_IE = 1;
            dojo.forEach(jsonworkslist[0]['works_data'],function(w) {
                if (document.all && document.querySelector && !document.getElementsByClassName) {
                    if (funny_counter_for_IE == jsonworkslist[0]['works_data'].length) {return false;}
                }
                funny_counter_for_IE++;
                if (w['done'] == 1) {var done_icon='<img border="0" src="/adm/icon/done.png">';} else {var done_icon='<img class="attension" border="0" src="/adm/icon/attension.png">';};
                htmlcontent += done_icon +"&nbsp;<a class='work_minus' id='" + w.id + "'>[-]</a>&nbsp;<a>" + w.title + "</a><br />";
            });
            dojo.byId("works_"+bid_step).innerHTML = htmlcontent;
            /*tooltips*/
            this_step_works_arr = dojo.query(".work_minus",dojo.byId("works_"+bid_step));  // find works of current step work
            var tip = new dijit.Tooltip({
                label: '<div class="myTipType">Удалить работу</div>',
                showDelay: 250,
                connectId: this_step_works_arr
            });
            w_attension = dojo.query(".attension");
            var tip = new dijit.Tooltip({
                label: '<div class="myTipType">Не заполнено описание работы</div>',
                showDelay: 250,
                connectId: w_attension
            });
            /*eo tooltips*/
            this_step_works_arr.forEach(
                function(node, index, nodelist)
                {
                    dojo.connect(node,"onclick",function(){requestWorksData('/?mod=forms&action=modifywork&del=1&workid='+this.id,'');});
                    dojo.connect(node.nextSibling.nextSibling,"onclick",function() { requestWorkData('/?mod=forms&action=getwork&workid='+node.id);} );
                }
            );
        },
        error: function(data){// alert("failure "+data);
        }
    });
}

function newWorkHandler()
{
    parcel = { work_name: dojo.byId('work_name').value, work_description: dojo.byId('work_description').value};
    if (!dojo.byId('work_name').value) {alert('Не указано название работы'); return false;}
    requestWorksData('/?mod=forms&action=newwork&new=1&bidid_stepid='+bidid_stepid,parcel);
    myDialog.hide();
}

function editWorkHandler()
{
    parcel = { work_name: dojo.byId('work_name').value, work_description: dojo.byId('work_description').value};
    if (!dojo.byId('work_name').value) {alert('Не указано название работы'); return false;}
    requestWorksData('/?mod=forms&action=modifywork&edit=1&workid='+editingWork,parcel);
    myDialog.hide();
}

function deleteStep()
{
    //hide
    step_boxout = this.parentNode.parentNode; //step span
    var animation = dojo.fadeOut({ node:step_boxout });
    var b_id_st_id_arr = step_boxout.id.split("_");
    dojo.connect(animation, "onEnd", function(){
        //del from DB
        dojo.xhrPost(
        {
            url: '/?mod=forms&action=delstep&stepid='+b_id_st_id_arr[2],
            handleAs: 'json',
            load: function(data) {
                var i = 1;
                var f = "#"+data.year+"~span[id^='step']>h4";
                var completes_arr = data.completes.split("_");
                dojo.query(f).forEach(function(node,ii,arr){console.info(i);
                        if (i<=data.steps_amount)
                        {
                            if (completes_arr[i-1] ==1) {var complete='<span class="local_grey">все поля этапа заполнены</span>';} else {var complete='<span class="local_red">не все поля этапа заполнены</span>';}
                            node.innerHTML = 'Этап '+i+'&nbsp;'+complete+'&nbsp; <img border="0" src="/adm/icon/delete_16.png" style="cursor:pointer" class="del_icon">';
                            i++;
                        }
                    });
                dojo.query(".del_icon").connect("onclick", deleteStep);                /*del handler for step*/
            },
            error: function(data){alert([data,'Удаление прошло с ошибками']);}
        });
        //del from DOM
        dojo.destroy(step_boxout);
    });
    animation.play(); // start it up
}

function addStep()
{
    var y = this.parentNode.parentNode.parentNode.parentNode.id;
    var ny = y-1+2;
    ny = dojo.byId(ny.toString());
    if (!ny) {refobj = dojo.byId(y.toString()).parentNode; plc = 'last';}
    else {refobj = ny; plc = 'before';}
        //add in DB
        dojo.xhrPost(
        {
/*@todo add "year":year & step_num*/
            url: '/?mod=forms&action=addstep&bidid=<?=$bid_id?>&year='+y,
            handleAs: 'json',
            load: function(data){
                if (data.step_number > 4){alert("Вы пытаетесь создать больше 4 этапов"); return;}
                st_box_cont = '<h4>Этап '+data.step_number+'&nbsp;<span class="local_red">не все поля этапа заполнены</span>&nbsp; <img border="0" src="/adm/icon/delete_16.png" style="cursor:pointer" class="del_icon"></h4>'+
                    '<form method="post" action="/?mod=forms&action=tablestep&id=<?=$bid_id?>&step_id='+data.step_id+'">'+
                    '<table width="100%">'+
                      '<tr>'+
                        '<td >Наименование основных видов работ</td>'+
                        '<td id="works_<?=$bid_id?>_'+data.step_id+'" width="500px"></td>'+
                      '</tr>'+
                      '<tr>'+
                        '<td ></td>'+
                        '<td><a class="work_plus" name="<?=$bid_id?>_'+data.step_id+'">[+]</a></td>  </tr>'+
                      '<tr>'+
                        '<td>Состав отчетной документации</td>'+
                        '<td><textarea name="report_documentation_composition"></textarea></td>'+
                      '</tr>'+
                      '<tr>'+
                        '<td>Месяц начала работ</td>'+
                        '<td id="startmonth_<?=$bid_id?>_'+data.step_id+'"><select class="step_date" name="start_month"></select></td>'+
                      '</tr>'+
                      '<tr>'+
                        '<td>Месяц окончания работ</td>'+
                        '<td id="finishmonth_<?=$bid_id?>_'+data.step_id+'"><select class="step_date" name="finish_month"></select></td>'+
                      '</tr>'+
                      '<tr>'+
                        '<td>Стоимость работ, руб</td>'+
                        '<td><input type="text" name="cost" value=""></td>'+
                      '</tr>'+
                    '</table>'+
                    '<input type="submit" name="addstepsdata" value="Сохранить данные об этапе">'+
                    '</form>'+
                    '<hr>';
                // add attributes
                dojo.query("#startmonth_<?=$bid_id?>_<?=$step_data['id']?>").data("year", data.year);
                dojo.query("#finishmonth_<?=$bid_id?>_<?=$step_data['id']?>").data("year", data.year);
                dojo.query("#startmonth_<?=$bid_id?>_<?=$step_data['id']?>").data("step_num", data.step_number);
                dojo.query("#finishmonth_<?=$bid_id?>_<?=$step_data['id']?>").data("step_num", data.step_number);
                // add in DOM
                newStep = dojo.create("span",{id:"step_<?=$bid_id?>_"+data.step_id, innerHTML:st_box_cont, opacity: 0},refobj,plc);
                dojo.query(".del_icon").connect("onclick", deleteStep);                /*del handler for step*/
                dojo.query(".work_plus").connect("onclick",function(){console.info(this.name);bidid_stepid = this.name; cont = formWorkContent('','','newWorkHandler'); myDialog.set("content", cont); myDialog.show();});
                dojo.fadeIn({ node:newStep }).play();

                /*tooltips*/
                st_minus = dojo.query(".del_icon");
                var tip = new dijit.Tooltip({
                    label: '<div class="myTipType">Удалить этап </div>',
                    showDelay: 250,
                    connectId: st_minus
                });
                w_plus_arr = dojo.query(".work_plus");
                var tip = new dijit.Tooltip({
                    label: '<div class="myTipType">Добавить работу</div>',
                    showDelay: 250,
                    connectId: w_plus_arr
                });
                /*eo tooltips*/
                /*calendar*/
            	$('input[name="handing_over_date"]').attachDatepicker({
            		rangeSelect: false,
            		yearRange: '2000:2050',
            		firstDay: 1
            	});
                /*eo calendar*/
            },
            error: function(data){alert([data,'Добавление прошло с ошибками']);}
        });
}

/*calendar*/
$(document).ready(function () {
	$('input[name="handing_over_date"]').attachDatepicker({
		rangeSelect: false,
		yearRange: '2000:2050',
		firstDay: 1
	});
})
</script>

<?=$TPL['BIDMENU']?>

<h1>Календарный план выполнения работ</h1>
<p>Заполните поля формы. В любой момент Вы можете сохранить введенные данные нажатием на кнопку внизу формы. Также Вы можете перемещаться между формами используя ссылки на соответствующие шаги в меню слева или ссылки над заголовком формы.</p>

<div class="notice"> В техническом задании Вами заданы следующие сроки выполнения работ:<br />
<b>начало работ:</b>&nbsp;&nbsp; <?=MonthsName($TPL['STARTDATEARR'][1])?>&nbsp;&nbsp;<?=$TPL['STARTDATEARR'][0]?>г.<br />
<b>окончание работ:</b>&nbsp;&nbsp;<?=MonthsName($TPL['FINISHDATEARR'][1])?>&nbsp;&nbsp;<?=$TPL['FINISHDATEARR'][0]?>г.<br />
<b>общая стоимость работ:</b>&nbsp;&nbsp;<?=$TPL['INFO']['price_works_actual']?> тыс.руб.
</div>
<script>
function makeCombobox(containerId,diapasonArr,selected,classNm,year)
{
    dojo.query("select",dojo.byId(containerId)).forEach(dojo.destroy);
    var select = dojo.create("select",{"class":[classNm, "step_date"], "year":year},dojo.byId(containerId),"first");
    dojo.connect(select, "onchange", remakeRestCombos);
    if (typeof diapasonArr === "object")
    {
        dojo.forEach(diapasonArr, function(node,index,arr){
            if (node==selected) {sel = true;} else {sel = false;}
            //dojo.create("option",{"value":node,"text":monthName(node), "selected":sel},select,"first");
            select.add(dojo.create("option",{"value":node,"innerHTML":monthName(node), "selected":sel}));
        })
    } else
    {
        //dojo.create("option",{"value":diapasonArr,"text":monthName(diapasonArr),"selected":true},select,"first");
        select.add(dojo.create("option",{"value":diapasonArr,"innerHTML":monthName(diapasonArr),"selected":true}))
    }
}

function remakeRestCombos(e, that)
{
var select;
if (that) {select = that;} else {select = this;}
yearA = new dojo.NodeList();
yearA.push(select.parentNode);
year = yearA.data('year')[0];
nextStepNum = yearA.data('step_num')[0]+1;
curStepFinishMonthVal = parseInt(select.options[select.selectedIndex].value);

var list = match_step_num_to_id[year];
for (var st=nextStepNum;st<list.length;st++)
{
    var nextSelStartContainerId = "startmonth_"+list[st]; //id контейнерa селекта стартового месяца в следующем этапе
    nextSelStart = dojo.query("#"+nextSelStartContainerId+" > select")[0]; //селект стартового месяца в следующем этапе
    makeCombobox(nextSelStartContainerId,curStepFinishMonthVal+1,0,"wellSel",year) // исправляем селект стартового месяца
    //исправляем селект финишгого месяца
    var nextSelFinishContainerId = "finishmonth_"+list[st]; //id контейнерa селекта финишгого месяца в следующем этапе
    nextSelFinish = dojo.query("#"+nextSelFinishContainerId+" > select")[0]; //селект финишгого месяца в следующем этапе
    vs = nextSelFinish.options[nextSelFinish.selectedIndex].value;
        range = [];
        for (var i=curStepFinishMonthVal+1; i<=12;i++)
        {
            range.push(i);
        }

    if (vs) {        //если сохраненное значение селекта финишгого месяца в следующем этапе существует
        if (vs in range) //и оно лежит в новом допустимом диапазоне, то
        {
            makeCombobox(nextSelFinishContainerId,range,vs,"wellSel",year) // перерисовываем селект с классом "верно" и с выбранным значением,
            curStepFinishMonthVal = vs;
            continue; //пошли пересчитывать следующие этапы
        } else //  но оно не лежит в новом допустимом диапазоне, то
        {
            makeCombobox(nextSelFinishContainerId,range,0,"wrongSel",year) // перерисовываем селект с классом "неверная дата,пересчитайте",
            dojo.query("td[id="+nextSelFinishContainerId+"] ~ td[id^=finishmonth] > select", "table[id="+year+"]").attr({disabled:true});
            // остальные селекты до конца года перерисовываем пустыми.
            return ;
        }
    } else
    {
        makeCombobox(nextSelFinishContainerId,range,0,"wellSel",year) // перерисовываем селект с классом "верно" без выбранного значения,
        dojo.query("td[id="+nextSelFinishContainerId+"] ~ td[id^=finishmonth] > select", "table[id="+year+"]").attr({disabled:true});        // остальные селекты до конца года перерисовываем пустыми.
        return ;
    }
}
}
/*
при условиях, что 1) работы в году всегда начинаются с января 2) нет нерабочих периодов

при загрузке страницы создание селектов с одним опшном, содержащим сохраненное значение, если значения не было, то пустого
onready:
remakeRestCombos(curEl) для каждого года

ф проверки лежит ли сохраненное значение окончания этапа в допустимых пределах (зависит от окончания предыдущего этапа)
ф поиска года и номера этапа в нем по идентификатору

starting_point = $TPL['STARTDATEARR'][1] - 1; // месяц начала работ, указанный в ТЗ
current_point = starting_point;

    {
        "startmonth_<?=$bid_id?>_<?=$step_data['id']?>": [current_point+1]
        "finishmonth_<?=$bid_id?>_<?=$step_data['id']?>": [current_point+2,12]   //onchange: current_point = this, recount all steps of this year
        ...
        "startmonth_<?=$bid_id?>_<?=$step_data['id']?>": [current_point+1]
        "finishmonth_<?=$bid_id?>_<?=$step_data['id']?>": [current_point+2,12]
    }
    ...
current_point = 0;
    "<?=$year?>": {
        "startmonth_<?=$bid_id?>_<?=$step_data['id']?>": [current_point+1]
        "finishmonth_<?=$bid_id?>_<?=$step_data['id']?>": [current_point+2,12]    //onchange: current_point = this, recount all steps of this year
        ...
        "startmonth_<?=$bid_id?>_<?=$step_data['id']?>": месяц окончания пред. этапа+1
        "finishmonth_<?=$bid_id?>_<?=$step_data['id']?>": [месяц окончания пред. этапа+2,12]
    }
    ...  if (count($TPL['STEPSDATA']) == $i-1)
    "<?=$year?>": {
        "startmonth_<?=$bid_id?>_<?=$step_data['id']?>": [1]
        "finishmonth_<?=$bid_id?>_<?=$step_data['id']?>": [2,12]
        ...
        "startmonth_<?=$bid_id?>_<?=$step_data['id']?>": месяц окончания пред. этапа+1
        "finishmonth_<?=$bid_id?>_<?=$step_data['id']?>": [$TPL['FINISHDATEARR'][1]]
    }
}
*/

var match_step_num_to_id = [];  //массив соответствия номеров этапов идентификаторам DOM-элементам с месяцами
/*
собираемая структура match_step_num_to_id = {
    "year": [step_num:"<?=$bid_id?>_<?=$step_data['id']?>",...],
    ...
}
сборка при загрузке страницы
*/

function monthName(monthNum)
{
    var mn = ["Нулябрь","Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"];
    return mn[monthNum];
}
</script>

<?
$cur_year = 0;
foreach ($TPL['STEPSDATA'] as $step_data)
{
    $year_arr = split("-",$step_data['year']);
    $year = $year_arr[0];
    if ($year != $cur_year)
    {
        $cur_year = $year; ?>
        <table width="100%" id="<?=$year?>">
          <tr>
            <td><h3><b>Год выполнения работ: <?=$year?></b></h3></td>
            <td width="500px" class="top"><img src="/adm/icon/spacer.gif" width="0" height="25" alt="" /><input type="submit" name="addstep" value="Добавить этап к году"></td>
          </tr>
        </table>
        <?
        $postfix = $bid_id."_".$step_data['id'];
        ?>
        <script>
            dojo.ready(function()
            { <?
                if ($year == $TPL['STARTDATEARR'][0])  // first  year
                { ?>
                    makeCombobox("startmonth_<?=$postfix?>",<?=$TPL['STARTDATEARR'][1]?>,<?=$TPL['STARTDATEARR'][1]?>,"wellSel",<?=$TPL['STARTDATEARR'][0]?>);
                    var range = [];
                    for (var i=<?=$TPL['STARTDATEARR'][1]+1?>; i<=12; i++)
                    {
                        range.push(i);
                    }
                    makeCombobox("finishmonth_<?=$postfix?>",range,0,"wellSel",<?=$TPL['STARTDATEARR'][0]?>); <?
                } else
                { ?>
                    makeCombobox("startmonth_<?=$postfix?>",1,1,"wellSel",<?=$year?>);
                    var range = [];
                    for (var i=2; i<=12; i++)
                    {
                        range.push(i);
                    }
                    makeCombobox("finishmonth_<?=$postfix?>",range,0,"wellSel",<?=$year?>); <?
                } ?>
                match_step_num_to_id["<?=$year?>"] = [];
                match_step_num_to_id["<?=$year?>"].push(0); //нулевой элемент пустой тк номера этапов начинаются с 1, а делать для этого ассоциативный не надо
            })
        </script>
         <?
    } ?>
<script> dojo.ready(function()
{
    match_step_num_to_id["<?=$year?>"].push("<?=$bid_id?>_<?=$step_data['id']?>");
}) </script>
<span id="step_<?=$bid_id?>_<?=$step_data['id']?>">
<h4>Этап <?=$step_data['step_number']?>&nbsp;
    <? if ($step_data['complete']!=1) { ?> <span class="local_red">не все поля этапа заполнены</span> <? } else { ?> <span class="local_grey">все поля этапа заполнены</span> <? } ?>&nbsp;
    <img border="0" src="/adm/icon/delete_16.png" style="cursor:pointer" class="del_icon"></h4>
<form method="post" action="/?mod=forms&action=tablestep&id=<?=$bid_id?>&step_id=<?=$step_data['id']?>">
<table width="100%">
  <tr>
    <td >Наименование основных видов работ</td>
    <td id="works_<?=$bid_id?>_<?=$step_data['id']?>" width="500px"> <?
        if (isset($step_data['works']))
        {
            foreach ($step_data['works'] as $work_data)
            {
                if (!empty($work_data['description'])) {$done_icon='<img border="0" src="/adm/icon/done.png">';} else {$done_icon='<img class="attension" border="0" src="/adm/icon/attension.png">';}?>
                <?=$done_icon?>&nbsp;<a class='work_minus' id='<?=$work_data['id']?>'>[-]</a>&nbsp;<a><?=$work_data['title']?></a><br /> <?
            }
        } ?>
    </td>
  </tr>
  <tr>
    <td ></td>
    <td><a class="work_plus" name="<?=$bid_id?>_<?=$step_data['id']?>">[+]</a></td>  </tr>
  <tr>
    <td>Состав отчетной документации</td>
    <td><textarea name="report_documentation_composition"><?=(isset($step_data['report_documentation_composition']))? $step_data['report_documentation_composition']:''?></textarea></td>
  </tr>
  <tr>
    <td>Месяц начала работ</td>
    <td id="startmonth_<?=$bid_id?>_<?=$step_data['id']?>"><select class="step_date" name="start_month"><option selected value=<?=(isset($step_data['start_month']))? $step_data['start_month']:''?>><?=(isset($step_data['start_month']))? MonthsName($step_data['start_month']):''?></option></select></td>
    <script>
    dojo.ready(function()
    {
        dojo.query("#startmonth_<?=$bid_id?>_<?=$step_data['id']?>").data("year", <?=$year?>);
        dojo.query("#startmonth_<?=$bid_id?>_<?=$step_data['id']?>").data("step_num", <?=$step_data['step_number']?>);
    })</script>
  </tr>
  <tr>
    <td>Месяц окончания работ</td>
    <td id="finishmonth_<?=$bid_id?>_<?=$step_data['id']?>"><select class="step_date" name="finish_month"><option selected value=<?=(isset($step_data['finish_month']))? $step_data['finish_month']:''?>><?=(isset($step_data['finish_month']))? MonthsName($step_data['finish_month']):''?></option></select></td>
    <script>
    dojo.ready(function()
    {
        dojo.query("#finishmonth_<?=$bid_id?>_<?=$step_data['id']?>").data("year", <?=$year?>);
        dojo.query("#finishmonth_<?=$bid_id?>_<?=$step_data['id']?>").data("step_num", <?=$step_data['step_number']?>);
    })</script>
  </tr>
  <tr>
    <td>Стоимость работ, руб</td>
    <td><input type="text" name="cost" value="<?=(isset($step_data['cost']))? $step_data['cost']:''?>"></td>
  </tr>
</table>
<input type="submit" name="addstepsdata" value="Сохранить данные об этапе">
</form>
<hr></span>
<? } ?>

<script>
dojo.ready(function() {
    dojo.query("[name='addstep']").forEach(function(node,i,a)
    {
      var year = node.parentNode.parentNode.parentNode.parentNode.id;
      var postfix = match_step_num_to_id[year][match_step_num_to_id[year].length-1];
      var select = dojo.query("#finishmonth_"+postfix+">select")[0];
      dojo.connect(node,"onclick",function(e){remakeRestCombos(e, select)});
    });
})
</script>

<?
    include TPL_CMS."_footer.php";
?>