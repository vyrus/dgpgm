<?
	class ManagerFormsAdmin extends ManagerForms {
		var $limit=20;

		function work(){
			global $_TPL, $_MODCONFIG;
			switch(1){
				default:
					include TPL_ADMIN_FORMS."index.php";
				break;

				case($_GET['action']=='regpaper'):
					$TPL['SUBPROGRAM'] = $this->listSubprogram();
					include TPL_ADMIN_FORMS."reg-paper.php";
				break;
				case($_GET['action']=='regpaperfilter'):
					if ($_POST) {
						$pp=intval($_POST['pp']);
						$mr=strval($_POST['mr']);
						$TPL['FILTER'] = $this->filterBidPaper($pp,$mr);
						$TPL['FILTER']['data'] = $TPL['FILTER'];
						$TPL['FILTER']['cnt'] = count($TPL['FILTER']['data']);
					}
					include TPL_ADMIN_FORMS."filter-paper.php";
				break;
				case($_GET['action']=='updatepaper'):
					$i=0;
					foreach ($_POST['date'] as $key=>$val) {
						if (!empty($val)) {
							// обновляем
							$paper = $this->updateBidPaper($val, $key);
							$i++;
						}
					}
					if ($i>0) {
						$result = array('type'=>'success', 'message'=>'Количество обновленных строк '.$i);
					} else {
						$result = array('type'=>'error');
					}
					print json_encode($result);
				break;
				case($_GET['action']=='evaluation'):
					$TPL['SUBPROGRAM'] = $this->listSubprogram();
					include TPL_ADMIN_FORMS."evaluation.php";
				break;
				case($_GET['action']=='evaluationfilter'):
					if ($_POST) {
						$pp=intval($_POST['pp']);
						$mr=strval($_POST['mr']);
						$TPL['FILTER'] = $this->filterBidEvaluation($pp,$mr);
						$TPL['FILTER']['data'] = $TPL['FILTER'];
						$TPL['FILTER']['cnt'] = count($TPL['FILTER']['data']);
					}
					include TPL_ADMIN_FORMS."filter-evaluation.php";
				break;

				case($_GET['action']=='updateevaluation'):
					foreach ($_POST as $key=>$val) {
						foreach ($val as $id=>$data) {
							$array[$id][$key] .= $data;
						}
					}
					$i=0;
					foreach ($array as $key=>$val) {
						if (!empty($val)) {
							// обновляем
							$this->updateBidEvaluation($val, $key);
							$i++;
						}
					}
					if ($i>0) {
						$result = array('type'=>'success', 'message'=>'Количество обновленных строк '.$i);
					} else {
						$result = array('type'=>'error');
					}
					print json_encode($result);
				break;



				case($_GET['action']=='addcomment'):
					$TPL['COMMENT']=$this->listComments($_GET['id']);
					if (!empty($_POST['comment'])) {
						$row['date'] = date('Y-m-d');
						$row['text'] = $_POST['comment'];
						$row['active'] = 1;
						$row['bid_id'] =  $_POST['id'];
						$this->db->addrow(FK_COMMENT, $row);
					}
					include TPL_ADMIN_FORMS."comment.php";
				break;
				case($_GET['action']=='editcomment'):
					$TPL['COMMENT']=$this->viewComment($_GET['id']);
					if (!empty($_POST['text']) && !empty($_POST['id']) && !empty($_POST['date'])) {
						$row['date'] =  $_POST['date'];
						$row['text'] = $_POST['text'];
						$row['active'] = $_POST['active'];
						$sql=sql_placeholder('update ?#FK_COMMENT set ?% where id=?', $row, $_POST['id']);
						return $this->db->query($sql);
					}
					include TPL_ADMIN_FORMS."editcomment.php";
				break;
				case($_GET['action']=='deletecomment'):
					if ($_POST) {
						$sql=sql_placeholder('delete from ?#FK_COMMENT where id=?', $_POST['id']);
						$this->db->query($sql);
					}
				break;
                /* choosing subprogram for operator */
				case($_GET['action']=='spforoper1'):
					$TPL['SUBPROGRAM'] = $this->listSubprogram();
  					$TPL["YEAR"]=listYears2('year', '2011',
                                                   '2015',
                                                   '2011');
                    include TPL_ADMIN_FORMS."oper1.php";
                break;
                /* choosing measure for operator */
				case($_GET['action']=='mforoper1'):
					$pp_id = @intval($_GET['pp_id']);
					$r=$this->listMeasure($pp_id);
					if ($r) {
						$measure = array();
						$measure[] = array('id'=>'0', 'title'=>'Не выбрано');
						foreach ($r as $row) {
							$measure[] = array('id'=>$row['id'], 'title'=>$row['title']);
						}
						$result = array('type'=>'success', 'measure'=>$measure);
					} else {
						$result = array('type'=>'error');
					}
					print json_encode($result);
				break;
				case($_GET['action']=='tablefilteroper1' && isset($_POST['pp']) && isset($_POST['mr']) && isset($_POST['r'])):
					$pp = intval($_POST['pp']);
					$mr = strval($_POST['mr']);
					$r = intval($_POST['r']);
					// функция вывода данных таблички
					$TPL['TABLEFORM'] = $this->listBidsOper1($pp, $mr, $r);
                    $moneyForWS = $this->moneyForWorkSteps();
                    foreach ($moneyForWS as $year_data)
                    {
                        $TPL['YEARSMONEY'][$year_data['bid']][$year_data['year']] = $year_data['cost'];
                    }
                    include TPL_ADMIN_FORMS."table-filter-oper1.php";
				break;

			}
		}
	// поиск заявок по номеру подпрограммы и мероприятию для проставления даты получения бумажки
	function filterBidPaper($sup_p, $measure){
/*
Если связывать заявки с мероприятиями и объявлениями отдельными условиями (m.id=b.measure_has_notice_measure_id,n.id=b.measure_has_notice_notice_id),
а не через промежуточную таблицу measure_has_notice, то в резальтате будут дублированые строки, относяциеся к одной и той же заявке,
т к заявка выберется сначала по одному условию(на нужное мероприятие), затем еще раз по второму (на нужное объявление).
Произойдет это потому что таблицы bid и measure_has_notice связаны не 1 х 1, а 1 х мн.
Это можно было б решить DISTINCT'ом, но увеличит вермя запроса, тк перед UNION'ом будет в 2раза большая таблица.

А если это делать не линейными двумя условиями, а вложенными как было, то сам вложенный подзапрос не выдает в общий результат связанные заявки, а тольлко номера нужных подпрограмм,
фактически сводя его к условию выбора subprogram_id=$sup_p,

Это почему я заменила связь таблиц на через промежуточную таблицу measure_has_notice.
Теперь по организациям и индивидуалам:
Связываем по union 2 завпроса. Первый запрос попытается связать заявку с индивидуалом, который ее подавал, второй запрос попытается связать заявку с организацией.
Один из этих запросов провалится, т к попытается связаться с NULLом. union объядинит несуществующую строку (из провального запроса) и существующую.
Т о заявка будет связана только с одной таблицей, а податель заявки, кто бы он ни был, окажется в поле applicant.
PS: у индивид-а не уверена, что надо брать fio. Замени тогда это в "i.fio applicant".

PSS: не нашла определений этих таблиц, поэтому добавила их в modLoad. Если я продублировала - удали пожалуйста.
define ('FK_APP_IND', 'applicant_individual');
define ('FK_APP_ORG', 'applicant_organization');

PSSS: Не включила эти условия. Не успела с ними проверить. Проверь с ними пожалуйста еще раз.
                                           b.datetime_electron_bid_receiving is null and
                                           b.datetime_paper_bid_receiving is null and
Ниже запрос. Сам запрос проверяла, но он без указанных выше условий и без подсчета строк. Но проверь на всяк случай еще раз, чтоб посмотреть то нужно было вывести или нет
*/

        if (!empty($sup_p))
        {
            $s_condition = " AND s.id=".$sup_p;
        }else $s_condition ="";

        if (!empty($measure))
        {
            $m_condition = " AND m.id=".$measure;
        }else $m_condition ="";

		$sql=sql_placeholder('select b.*, b.id as bid_id,
                                     YEAR(n.start_realization) as start_realization,
                                     n.finish_acquisition,
                                     n.summing_up_date,
                                     concat_ws(" ", i.last_name, i.first_name, i.middle_name) as applicant
                               from ?#FK_SUBPROGRAM as s,
                                    ?#FK_MEASURE as m,
                                    ?#FK_NOTICE as n,
                                    ?#FK_MEASURE_HAS_NOTICE as mn,
                                    ?#FK_BID as b,
                                    ?#FK_USER as u,
                                    ?#FK_APP_IND as i
                              where s.id=m.subprogram_id AND

                                    mn.measure_id=m.id AND
                                    mn.notice_id=n.id AND
                                    b.measure_has_notice_measure_id=mn.measure_id AND
                                    b.measure_has_notice_notice_id=mn.notice_id  AND
									b.datetime_electron_bid_receiving is not null AND
									b.datetime_paper_bid_receiving is null AND
                                    b.user_id=u.id AND
									u.`type-face`="fiz" AND
                                    i.id=u.id_org_ind'.$s_condition.$m_condition.'
                    union
                              select b.*, b.id bid_id,
                                     YEAR(n.start_realization) as start_realization,
                                     n.finish_acquisition,
                                     n.summing_up_date,
                                     o.short_title applicant
                               from ?#FK_SUBPROGRAM as s,
                                    ?#FK_MEASURE as m,
                                    ?#FK_NOTICE as n,
                                    ?#FK_MEASURE_HAS_NOTICE as mn,
                                    ?#FK_BID as b,
                                    ?#FK_USER as u,
                                    ?#FK_APP_ORG as o
                              where s.id=m.subprogram_id AND

                                    mn.measure_id=m.id AND
                                    mn.notice_id=n.id AND
                                    b.measure_has_notice_measure_id=mn.measure_id AND
                                    b.measure_has_notice_notice_id=mn.notice_id  AND
									b.datetime_electron_bid_receiving is not null AND
									b.datetime_paper_bid_receiving is null AND
                                    b.user_id=u.id AND
									u.`type-face`="yur" AND
                                    o.id=u.id_org_ind'.$s_condition.$m_condition.'
                             order by bid_id asc');
        return $this->db->_array_data($sql);
    }
	// поиск заявок по номеру подпрограммы и мероприятию для их оценки
	function filterBidEvaluation($sup_p, $measure){
		if (!empty($sup_p))
        {
            $s_condition = " AND s.id=".$sup_p;
        }else $s_condition ="";

        if (!empty($measure))
        {
            $m_condition = " AND m.id=".$measure;
        }else $m_condition ="";

		$sql=sql_placeholder('select b.*, b.id as bid_id,
                                     YEAR(n.start_realization) as start_realization,
                                     n.finish_acquisition,
                                     n.summing_up_date,
                                     concat_ws(" ", i.last_name, i.first_name, i.middle_name) as applicant,
									 (select count(c.id) from ?#FK_COMMENT as c where c.bid_id=b.id) as cnt_comment
                               from ?#FK_SUBPROGRAM as s,
                                    ?#FK_MEASURE as m,
                                    ?#FK_NOTICE as n,
                                    ?#FK_MEASURE_HAS_NOTICE as mn,
                                    ?#FK_BID as b,
                                    ?#FK_USER as u,
                                    ?#FK_APP_IND as i
                              where s.id=m.subprogram_id AND
                                    mn.measure_id=m.id AND
                                    mn.notice_id=n.id AND
									n.summing_up_date > CURDATE() AND
                                    b.measure_has_notice_measure_id=mn.measure_id AND
                                    b.measure_has_notice_notice_id=mn.notice_id  AND
									b.datetime_electron_bid_receiving is not null AND
									b.datetime_paper_bid_receiving is not null AND
                                    b.user_id=u.id AND
									u.`type-face`="fiz" AND
                                    i.id=u.id_org_ind'.$s_condition.$m_condition.'
                    union
                              select b.*, b.id bid_id,
                                     YEAR(n.start_realization) as start_realization,
                                     n.finish_acquisition,
                                     n.summing_up_date,
                                     o.short_title applicant,
									 (select count(c.id) from ?#FK_COMMENT as c where c.bid_id=b.id) as cnt_comment
                               from ?#FK_SUBPROGRAM as s,
                                    ?#FK_MEASURE as m,
                                    ?#FK_NOTICE as n,
                                    ?#FK_MEASURE_HAS_NOTICE as mn,
                                    ?#FK_BID as b,
                                    ?#FK_USER as u,
                                    ?#FK_APP_ORG as o
                              where s.id=m.subprogram_id AND
                                    mn.measure_id=m.id AND
                                    mn.notice_id=n.id AND
									n.summing_up_date > CURDATE() AND
                                    b.measure_has_notice_measure_id=mn.measure_id AND
                                    b.measure_has_notice_notice_id=mn.notice_id  AND
									b.datetime_electron_bid_receiving is not null AND
									b.datetime_paper_bid_receiving is not null AND
                                    b.user_id=u.id AND
									u.`type-face`="yur" AND
                                    o.id=u.id_org_ind'.$s_condition.$m_condition.'
                             order by bid_id asc');
		//	$sql=sql_placeholder('select SQL_CALC_FOUND_ROWS u.login, b.*, YEAR(n.start_realization) as start_realization, n.finish_acquisition, n.summing_up_date, (select count(c.id) from ?#FK_COMMENT as c where c.bid_id=b.id) as cnt_comment from ?#FK_BID as b, ?#FK_NOTICE as n, ?#FK_USER as u where (select m.subprogram_id from ?#FK_MEASURE as m where m.id=b.measure_has_notice_measure_id)=? and b.datetime_electron_bid_receiving is null and b.datetime_paper_bid_receiving is null and b.measure_has_notice_measure_id=? and n.id=b.measure_has_notice_notice_id and b.user_id=u.id order by b.id asc', $sup_p, $measure); //datetime_electron_bid_receiving в is not null
        return $this->db->_array_data($sql);
    }
	// функция обновления бумажной даты заявки
	function updateBidPaper($date, $bid_id){
		if (preg_match("#^\d{2}\.\d{2}.\d{4}$#", $date)) {
			$pieces = explode(".", $date);
			$date_out = $pieces[2]."-".$pieces[1]."-".$pieces[0]." ".date('H:i:s');
		}
		$sql=sql_placeholder('update ?#FK_BID set datetime_paper_bid_receiving=? where id=? ', $date_out, $bid_id);
		return $this->db->query($sql);
	}
	// функция обновления победителей
	function updateBidEvaluation($row, $bid_id){
		$sql=sql_placeholder('update ?#FK_BID set ?% where id=? ', $row, $bid_id);
		return $this->db->query($sql);
	}
	// Комментарии к заявке
	function listComments($id){
		$sql=sql_placeholder('select * from ?#FK_COMMENT where bid_id=? order by id asc', $id);
		return $this->db->_array_data($sql);
	}
	//Информация об одном комментарии к заявке
	function viewComment($id){
		$sql=sql_placeholder('select * from ?#FK_COMMENT where id=?', $id);
		return $this->db->select_row($sql);
	}

   function listBidsOper1($pp, $mr, $r)
   {
        /* if $st==2 с начала сбора до даты завершения
           if $st==3 с даты завершения до даты подведения итогов
           if $st==4 с даты подведения итогов и >
        */
        if ($st==2)
        {
            $step_name = "Этап подачи заявок";
            $condition = " AND (n.start_acquisition < NOW() AND NOW() < n.finish_acquisition)";
        }elseif ($st==3)
        {
            $step_name = "Этап рассмотрения заявок";
            $condition = " AND (n.finish_acquisition < NOW() AND NOW() < n.summing_up_date)";
        }elseif ($st==4)
        {
            $step_name = "Формирование тематики завершено";
            $condition = " AND (summing_up_date < NOW())";
        }else
        {
            $step_name = "Все этапы";
            $condition = "";
        }

        if (!empty($pp))
        {
            $s_condition = " AND s.id=".$pp;
        }else $s_condition ="";

        if (!empty($mr))
        {
            $m_condition = " AND m.id=".$mr;
        }else $m_condition ="";

		$sql=sql_placeholder('
            select s.title stitle, s.id sid,
                   m.title mtitle, m.id mid,
                   d.title dtitle,
                   mn.measure_id, mn.notice_id,
                   YEAR(n.start_realization) start_realization,
                   ? as step_name,
                   b.id bid,
                   b.work_topic, b.price_works_actual, b.applicant_organization_id,
                   i.last_name as applicant,
                   b.datetime_electron_bid_receiving, b.datetime_paper_bid_receiving,
                   b.matches, b.rating_experts, b.rating_protocol_NKS, b.winner,b.date_create_bid,
                   DATE(n.finish_acquisition) finish_acquisition,DATE(n.summing_up_date) summing_up_date,
                   (select count(c.id) from ?#FK_COMMENT as c where c.bid_id=b.id) as cnt_comment
           from ?#FK_MEASURE as m,
                ?#FK_SUBPROGRAM as s,
                ?#FK_NOTICE as n,
                ?#FK_MEASURE_HAS_NOTICE as mn,
                ?#FK_DEPARTAMENT as d,
                ?#FK_BID as b,
                ?#FK_USER as u,
                ?#FK_APP_IND as i
          where s.id=m.subprogram_id AND
                mn.measure_id=m.id AND
                mn.notice_id=n.id AND
                b.measure_has_notice_measure_id=mn.measure_id AND
                b.measure_has_notice_notice_id=mn.notice_id AND
                d.id=n.department_id
                '.$s_condition.'
                '.$m_condition.$condition.' AND
                b.user_id=u.id AND
                i.id=u.id_org_ind
 union
            select s.title stitle, s.id sid,
                   m.title mtitle, m.id mid,
                   d.title dtitle,
                   mn.measure_id, mn.notice_id,
                   YEAR(n.start_realization) start_realization,
                   ? as step_name,
                   b.id bid,
                   b.work_topic, b.price_works_actual, b.applicant_organization_id,
                   o.short_title applicant,
                   b.datetime_electron_bid_receiving, b.datetime_paper_bid_receiving,
                   b.matches, b.rating_experts, b.rating_protocol_NKS, b.winner,b.date_create_bid,
                   DATE(n.finish_acquisition) finish_acquisition,DATE(n.summing_up_date) summing_up_date,
                   (select count(c.id) from ?#FK_COMMENT as c where c.bid_id=b.id) as cnt_comment
           from ?#FK_MEASURE as m,
                ?#FK_SUBPROGRAM as s,
                ?#FK_NOTICE as n,
                ?#FK_MEASURE_HAS_NOTICE as mn,
                ?#FK_DEPARTAMENT as d,
                ?#FK_BID as b,
                ?#FK_USER as u,
                ?#FK_APP_ORG as o
          where s.id=m.subprogram_id AND
                mn.measure_id=m.id AND
                mn.notice_id=n.id AND
                b.measure_has_notice_measure_id=mn.measure_id AND
                b.measure_has_notice_notice_id=mn.notice_id AND
                d.id=n.department_id
                '.$s_condition.'
                '.$m_condition.$condition.' AND
                b.user_id=u.id AND
                o.id=u.id_org_ind
 order by sid, mid
            ', $step_name,$step_name);
		return $this->db->_array_data($sql);
    }

    function moneyForWorkSteps()
    {
		$sql=sql_placeholder(' select bid_id bid, YEAR(year) year, SUM(cost) cost
                               from ?#FK_WORK_STEP
                               group by bid, year');
        return $this->db->_array_data($sql);
    }
	}

?>