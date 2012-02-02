<?
include TPL_CMS."_header.php";
?>

<style>
.claro .dijitDialogTitleBar {
  background-color: #BDC6D5;
}
.local_red, .step_date_error
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
    -moz-border-radius:8px 8px 8px 8px; /* Firefox 3.6 and earlier */
    color: #000000;
    padding: 0.8em;
    width: 88%;
}

.work_plus_btn
{
    border: 1px solid #ccd5e0;
    color: #000000;
    border-radius: 8px 8px 8px 8px;
    -moz-border-radius:8px 8px 8px 8px; /* Firefox 3.6 and earlier */
}


</style>

<script>
var bidid_stepid;
var editingWork;
var cont = '';
var myDialog;
var w_edit_links_arr = [];

// Load the Tooltip & Dialog widget class
dojo.require("dijit.Tooltip");
dojo.require("dijit.Dialog");

dojo.ready(function()
{
    //dojo.query("select.step_date").forEach(function(node,i,a){node.innerHTML = makeCommonSelect();});

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

    /*check datas*/
    checkingConnect = dojo.connect(dojo.byId("checkStepDatesBtn"),"onclick",function()
    {
      checkStepDates(); dojo.disconnect(checkingConnect);
    });

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

function makeCommonSelect()
{
    selText = "";
    for (var i=1;i<=12;i++)
    {
        selText += "<option value='"+i+"'>"+monthName(i)+"</option>";
    }
    return selText;
}

function checkStepDates()
{
    dojo.xhrPost(
    {
        url: '/?mod=forms&action=checkStepDates&bidid=<?=$bid_id?>',
        handleAs: 'json',
        load: function (data) {
            var err;
            var td;
            for (var idx in data) {
                err = data[idx];
                td = dojo.query("td#" + err[0])[0];
                dojo.create('div', {class: "step_date_error", innerHTML: err[1]}, td, "last");
            }
        },
        error: function (data) {alert("error ".data);}
    })
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
                selText = makeCommonSelect();
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
                        '<td id="startmonth_<?=$bid_id?>_'+data.step_id+'"><select class="step_date" name="start_month">'+selText+'</select></td>'+
                      '</tr>'+
                      '<tr>'+
                        '<td>Месяц окончания работ</td>'+
                        '<td id="finishmonth_<?=$bid_id?>_'+data.step_id+'"><select class="step_date" name="finish_month">'+selText+'</select></td>'+
                      '</tr>'+
                      '<tr>'+
                        '<td>Доля стоимости работ этапа в общей стоимости работ, %</td>'+
                        '<td><input type="text" name="cost" value=""></td>'+
                      '</tr>'+
                    '</table>'+
                    '<input type="submit" name="addstepsdata" value="Сохранить данные об этапе">'+
                    '</form>'+
                    '<hr>';
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
<!--<b>общая стоимость работ:</b>&nbsp;&nbsp;<?=$TPL['INFO']['price_works_actual']?> тыс.руб.-->
<table>
  <tr>
    <td id="checking_message">Вы можете в любой момент проверить корректность введенных Вами сроков выполнения работ и распределения стоимости работ&nbsp;&nbsp;&nbsp; <input type="button" value="Проверить" id="checkStepDatesBtn"></td>
  </tr>
</table>
</div>


<script>
function monthName(monthNum)
{
    var mn = ["нулябрь","январь","февраль","март","апрель","май","июнь","июль","август","сентябрь","октябрь","ноябрь","декабрь"];
    return mn[monthNum];
}
</script>

<?
function makeCommonSelectWithSelected($selected)
{
    $selText = "";
    for ($i=1;$i<=12;$i++)
    {
        if ($selected == $i)
        {
            $selText .= "<option value='".$i."' selected>".MonthsName($i)."</option>";
        } else {
            $selText .= "<option value='".$i."'>".MonthsName($i)."</option>";
        }
    }
    return $selText;
}

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
    } ?>
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
    <td id="startmonth_<?=$bid_id?>_<?=$step_data['id']?>"><select class="step_date" name="start_month"><? if (isset($step_data['start_month'])) {echo makeCommonSelectWithSelected($step_data['start_month']);} else {echo makeCommonSelectWithSelected(null);}?></select></td>
  </tr>
  <tr>
    <td>Месяц окончания работ</td>
    <td id="finishmonth_<?=$bid_id?>_<?=$step_data['id']?>"><select class="step_date" name="finish_month"><? if (isset($step_data['finish_month'])) {echo makeCommonSelectWithSelected($step_data['finish_month']);} else {echo makeCommonSelectWithSelected(null);}?></select></td>
  </tr>
  <tr>
    <td>Доля стоимости работ этапа в общей стоимости работ, %</td>
    <td><input type="text" name="cost" value="<?=(isset($step_data['cost']))? $step_data['cost']:''?>"></td>
  </tr>
</table>
<input type="submit" name="addstepsdata" value="Сохранить данные об этапе">
</form>
<hr></span>
<? } ?>

<?
    include TPL_CMS."_footer.php";
?>