<?
/*
После нажатия "отправить заявку на конкурс":
- в таблице "заявка" проставляется дата и время;
- генерируются печатные формы (одним файлом или несколькими?.. лучше одним... тогда таблица "Форма" БД не будет хранить ссылки на формы,
а будет использоваться только для простановки флажков готовности форм) и сохраняются на сервере (после отправки менять уже ничего нельзя, поэтому имеет смысл их сохранить и больше не генерировать).

После нажатия "распечатать формы заявки":
- если заявка подана, то грузится файл, сохраненный при отправке заявки;
- если заявка не подана, то генерируется файл форм и также выгружается пользователю.

Пользователь принадлежит одной организации. Следовательно, когда он заполнил сведения об организации, они должны быть едиными для всех заявок.
То есть по факту в базе должна быть "неправильная" связь "один к одному" между пользователем и организацией.
У нас пока данные из одной заявки в другой автоматически не появляются.
По физ лицам должно быть также. Вводится одни раз.

в базе должна быть "неправильная" связь "один к одному" между пользователем и организацией
*/

class ManagerForms{
       var $db=false;

	function ManagerForms(&$db){
          $this->db=$db;
	}
	//Все подпрограммы
	function listSubprogram(){
		$sql=sql_placeholder('select * from ?#FK_SUBPROGRAM order by id asc');
		return $this->db->_array_data($sql);
   }

   	//Информация об одной подпрограмме
	function viewSubprogram($id){
		$sql=sql_placeholder('select * from ?#FK_SUBPROGRAM where id=?', $id);
		return $this->db->select_row($sql);
   }
	// Все мероприятия подпрограммы
	function listMeasure($id){
		$sql=sql_placeholder('select * from ?#FK_MEASURE where subprogram_id=?', $id);
		return $this->db->_array_data($sql);
   }
    //Информация об одном мероприятии
	function viewMeasure($id){
		$sql=sql_placeholder('select * from ?#FK_MEASURE where id=?', $id);
		return $this->db->select_row($sql);
   }
	// Все объявления
	function allBids(){
		$sql=sql_placeholder('select * from ?#FK_NOTICE order by start_acquisition');
		return $this->db->_array_data($sql);
    }
	// Заполнение форм для заявки
	function formsComplete($id){
		$sql=sql_placeholder('select * from ?#FK_FORM where bid_id=?', $id);
		return $this->db->_array_data($sql);
    }

	//Информация об одной заявке
	function viewBid($id){
		$sql=sql_placeholder('select b.*, m.title as title_measure, m.subprogram_id, p.title as title_subprogram, YEAR(n.start_realization) as start_realization, n.finish_acquisition, d.title as departament_name
		from ?#FK_BID as b, ?#FK_MEASURE as m, ?#FK_SUBPROGRAM as p, ?#FK_NOTICE as n, ?#FK_MEASURE_HAS_NOTICE as mn, ?#FK_DEPARTAMENT as d
		where
			b.id=? and
			mn.measure_id=m.id AND
			mn.notice_id=n.id AND
			b.measure_has_notice_measure_id=mn.measure_id AND
			b.measure_has_notice_notice_id=mn.notice_id  AND
			p.id=m.subprogram_id AND
			d.id=n.department_id
		', $id);
		return $this->db->select_row($sql);
   }
   /*
                                    mn.measure_id=m.id AND
                                    mn.notice_id=n.id AND
                                    b.measure_has_notice_measure_id=mn.measure_id AND
                                    b.measure_has_notice_notice_id=mn.notice_id  AND
   */
   // Список общие поля формы ТЗ
	function listFieldsTZ(){
		$sql=sql_placeholder('select * from ?#FK_FIELDS_TZ');
		return $this->db->select_row($sql);
	}
	//Обновить информацию о заявке
	function updateBid($id, $row){
		$sql=sql_placeholder('update ?#FK_BID set ?% where id=? ', $row, $id);
		return $this->db->query($sql);
	}
	function viewOrganization($id){
		$sql=sql_placeholder('select * from ?#FK_APP_ORG where id=? ', $id);
		return $this->db->select_row($sql);
	}
	function viewIndividual($id){
		$sql=sql_placeholder('select * from ?#FK_APP_IND where id=? ', $id);
		return $this->db->select_row($sql);
	}
	//Все коэффиценты зарплат
	function listWage(){
		$sql=sql_placeholder('select * from ?#FK_WAGE order by id asc');
		return $this->db->_array_data($sql);
   }
   	//один коэффицент
	function viewWage($id){
		$sql=sql_placeholder('select coef from ?#FK_WAGE where id=?', $id);
		return $this->db->select_row($sql);
   }
	//Константы для расчета себестоимости
	function listCost(){
		$sql=sql_placeholder('select * from ?#FK_COST order by id asc');
		return $this->db->_array_data($sql);
   }
	//заполненость формы 1 заполнена, 0 - нет
	function tableComplete($id, $form_type, $complete){
		$sql=sql_placeholder('update ?#FK_FORM set complete=? where bid_id=? and form_type=?', $complete, $id, $form_type);
		return $this->db->query($sql);
    }
	//Все исполнители для одной заявки
	function listPerformers($id){
		$sql=sql_placeholder('select * from ?#FK_PERFORMER where bid_id=? order by id asc', $id);
		return $this->db->_array_data($sql);
   }
   function listPerformersWage($id){
		$sql=sql_placeholder('select p.*, w.position, w.coef from ?#FK_PERFORMER as p, ?#FK_WAGE as w where w.id=p.dolzhnost and bid_id=? order by id asc', $id);
		return $this->db->_array_data($sql);
   }
   //Удаление всех исполнителей заявки
   function deletePerformers($id){
		$sql=sql_placeholder('delete from ?#FK_PERFORMER where bid_id=?', $id);
		return $this->db->query($sql);
   }
   // список созданных заявок пользователем, связанный с мероприятиями куда он может подать заявку
   function listBids($pp, $mr, $st, $user_id) {
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
            select *
            from
                (select s.title stitle, s.id sid,
                        m.title mtitle, m.id mid,
                        d.title dtitle,
                        mn.measure_id,
                        mn.notice_id nid,
                        YEAR(n.start_realization) start_realization,
                        ? as step_name
                 from ?#FK_MEASURE as m,
                      ?#FK_SUBPROGRAM as s,
                      ?#FK_NOTICE as n,
                      ?#FK_MEASURE_HAS_NOTICE as mn,
                      ?#FK_DEPARTAMENT as d
                 where s.id=m.subprogram_id AND
                       mn.measure_id=m.id AND
                       mn.notice_id=n.id AND
                       d.id=n.department_id
                       '.$s_condition.'
                       '.$m_condition.$condition.'
                ) mm
            left join
                (select b.datetime_electron_bid_receiving,
                        b.datetime_paper_bid_receiving,
                        b.id bid,
                        b.measure_has_notice_measure_id,
                        b.measure_has_notice_notice_id
                 from ?#FK_BID as b
                 where user_id=?
                ) bb
            on    bb.measure_has_notice_measure_id=mm.measure_id AND
                  bb.measure_has_notice_notice_id=mm.nid
            order by sid, mid
            ', $step_name,$user_id); // conditions вставила без помощи ?  т к не смогла избавиться от кавычек
		return $this->db->_array_data($sql);
    }

    function makeInformNote($not)
    {
		$sql=sql_placeholder('select s.id sid,
                                     s.title stitle,
                                     m.id  mid,
                                     d.title dtitle,
                                     n.start_acquisition,
                                     n.finish_acquisition
                               from ?#FK_MEASURE as m,
                                    ?#FK_SUBPROGRAM as s,
                                    ?#FK_NOTICE as n,
                                    ?#FK_MEASURE_HAS_NOTICE as mn,
                                    ?#FK_DEPARTAMENT as d
                               where
                                     s.id=m.subprogram_id AND
                                     mn.measure_id=m.id AND
                                     mn.notice_id=n.id AND
                                     d.id=n.department_id AND
                                     mn.notice_id=?'
                                     , $not);
		return $this->db->_array_data($sql);
    }

     function getStartRealizationDate($bid_id)
    {
		$sql=sql_placeholder('select YEAR(n.start_realization) start_realization, duration_realization
                               from ?#FK_NOTICE as n,
                                    ?#FK_MEASURE_HAS_NOTICE as mn,
                                    ?#FK_BID as b
                               where
                                     b.measure_has_notice_measure_id=mn.measure_id AND
                                     b.measure_has_notice_notice_id=mn.notice_id AND
                                     mn.notice_id=n.id AND
                                     b.id=?'
                                     , $bid_id);
		return $this->db->_array_data($sql);
    }
	//Коэффициент квалификации (участия) специалистов Ккв(уч) = (Тфi/Тп)*Чi*Иi
	function calculateCoefQualif($fti, $tp, $chi, $ii) {
		$cfq = ($fti/$tp)*$chi*$ii;
		return $cfq;
	}
	//Коэффициент квалификации (участия) специалистов итог = Сумма строк/Чп
	function calculateCoefQualifEnd($cfq, $chp) {
		$cfqe = $cfq/$chp;
		return $cfqe;
	}
	//Среднедневная зарплата исполнителей
	function calculateAverageDailyWages($wage, $dm) {
		$w_d = strval($wage)/strval($dm);
		return $w_d;
	}
	//Единичная себестоимость
	function calculateUnitCost($w_d, $kz) {
		$ss1 = strval($w_d)/strval($kz);
		return $ss1;
	}
	//Общая себестоимость выполняемых работ
	function calculateAllCost($ss1,$tp,$chp,$cfqe) {
		$ssob = strval($ss1)*strval($tp)*strval($chp)*strval($cfqe);
		return $ssob;
	}
	//Стоимость работ  ССоб* Р
	function calculatePriceWorks($ssob, $p) {
		$ss2000 = strval($ssob)*(1+strval($p));
		return $ss2000;
	}
	//Стоимость выполняемых работ (услуг) в текущих ценах (тыс. руб.), Ст.ц.
	function calculatePriceWorksActual($ss2000, $kper) {
		$stc = strval($ss2000)*strval($kper);
		return $stc;
	}
	//В том числе НДС 18% (тыс. руб.)
	function calculateNDS($stc, $nds) {
		$nds_out = strval($stc)*strval($nds);
		return $nds_out;
	}

	//Получение атрибутов работ: целей, условий, ...
	function viewWorkPurpose($bid_id) {
		$sql=sql_placeholder('select title from ?#FK_WORK_PURPOSE where bid_id=? order by id', $bid_id);
		return $this->db->_array_data($sql);
	}
	function viewWorkRequirement($bid_id) {
		$sql=sql_placeholder('select work_requirement_title from ?#FK_WORK_REQUIREMENT where bid_id=? order by id', $bid_id);
		return $this->db->_array_data($sql);
	}
	function viewWorkCondition($bid_id) {
		$sql=sql_placeholder('select work_condition_title from ?#FK_WORK_CONDITION where bid_id=? order by id', $bid_id);
		return $this->db->_array_data($sql);
	}
	function viewSafetyRequirements($bid_id) {
		$sql=sql_placeholder('select safety_requirements_title from ?#FK_SAFETY_REQUIREMENTS where bid_id=? order by id', $bid_id);
		return $this->db->_array_data($sql);
	}

    function getDistricts()
    {
		$sql=sql_placeholder('select id,title from ?#FK_PLACE_DISTRICT where title="Город федерального значения Москва"');
		$res_m = $this->db->_array_data($sql);
		$sql=sql_placeholder('select id,title from ?#FK_PLACE_DISTRICT where title<>"Город федерального значения Москва" order by title');
        $res = $this->db->_array_data($sql);
        return array_merge($res_m,$res);
    }
    function getOkrugs()
    {
		$sql=sql_placeholder('select id,title from ?#FK_PLACE_OKRUG');
		return $this->db->_array_data($sql);
    }

    function delStep($step_id)
    {
		$sql=sql_placeholder('select bid_id,year,step_number from ?#FK_WORK_STEP where id=?',$step_id);
		$bid_id_year = $this->db->select_row($sql);
        $bid_id = $bid_id_year['bid_id']; $year = $bid_id_year['year'];
		$sql=sql_placeholder('select count(*) from ?#FK_WORK_STEP where bid_id=? and year=? order by step_number',$bid_id,$year);
		$steps_amount = $this->db->select_row($sql);
		$sql=sql_placeholder('select * from ?#FK_WORK_STEP where bid_id=? and year=? order by step_number',$bid_id,$year);
        $existed_steps = $this->db->_array_data($sql);
        /*del all steps of bid_id to rewrite them later but in right order*/
		$sql=sql_placeholder('delete from ?#FK_WORK_STEP where bid_id=? and year=?',$bid_id,$year);
		$this->db->query($sql);

        /*write steps again*/
        $after_deleted = false; $rows = array(); $completes= array();
        foreach ($existed_steps as $st)
        {
            if ($st['id'] != $step_id)
            {
                $completes[] = $st['complete'];
                if ($after_deleted)
                {
                    $rows = $st;
                    $rows['step_number'] = --$st['step_number'];
                } else
                {$rows = $st;}
            } else { $after_deleted = true; continue;}
        	$this->db->addrow(FK_WORK_STEP, $rows);
        }
         //del works of deliting step
		$sql=sql_placeholder('delete from ?#FK_WORK where work_step_id=?', $step_id);
		$this->db->query($sql);
        return array($steps_amount,$year,$completes);
    }

    function stepWorksByBid($bid_id)
    {
   		$sql=sql_placeholder('select * from ?#FK_WORK_STEP where bid_id=? order by year, step_number', $bid_id);
        $work_steps = $this->db->_array_data($sql);
        if (!empty($work_steps))
        {
            foreach ($work_steps as $key=>$row)
            {
                 //read works for step_id
                $sql=sql_placeholder('select * from ?#FK_WORK where work_step_id=? order by id', $row['id']);
                $works_list = $this->db->_array_data($sql);
                $work_steps[$key]['works'] = $works_list;
            }
        }
        return $work_steps;
    }

	function stepWorksByBidMerge($bid_id)
    {
   		$sql=sql_placeholder('select st.*, YEAR(st.year) as year, w.* from ?#FK_WORK_STEP as st, ?#FK_WORK as w where bid_id=? and st.id=w.work_step_id order by year, step_number', $bid_id);
        return $this->db->_array_data($sql);
    }

    function work(){
            global $_TPL;
            $action = (empty($_GET['action']))?'':$_GET['action'];
            switch(1){
				case($action=='form1'):
					if (USER_ID>1) {
						$TPL['SUBPROGRAM'] = $this->listSubprogram();
						include TPL_CMS_FORMS."form1.php";
					} else {
						include TPL_CMS_FORMS."no-rights.php";
					}
                break;
				case($action=='get_mr'):
					$pp_id = @intval($_GET['pp_id']);
					$r=$this->listMeasure($pp_id);
					if ($r) {
						$measure = array();
						$measure[] = array('id'=>'0', 'title'=>'Все мероприятия');
						foreach ($r as $row) {
							$measure[] = array('id'=>$row['id'], 'title'=>$row['title']);
						}
						$result = array('type'=>'success', 'measure'=>$measure);
					} else {
						$result = array('type'=>'error');
					}
					print json_encode($result);
				break;

				case($action=='tablefilter' && isset($_POST['pp']) && isset($_POST['mr']) && isset($_POST['r'])):
					$pp = intval($_POST['pp']);
					$mr = strval($_POST['mr']);
					$r = intval($_POST['r']);
                    $user_id = USER_ID;
					// функция вывода данных таблички
					$TPL['TABLEFORM'] = $this->listBids($pp, $mr, $r, $user_id);
                    include TPL_CMS_FORMS."table-filter.php";
				break;
				case($action=='infoyur'):// && $_GET['id']
					//получаем информацию о заявке
					//$TPL['INFO']=$this->viewBid(intval($_GET['id']));
					//$finish_acquisition_tsh = strtotime($TPL['INFO']['finish_acquisition']);
					if (USER_TYPE == 'yur' && USER_ID>2) { //$TPL['INFO']['user_id'] == USER_ID &&
					// показывать кнопочку отправить или нет
					/*if ($finish_acquisition_tsh<time() || !empty($TPL['INFO']['datetime_electron_bid_receiving'])) {
						$form_dis='disabled="disabled"';
					} else {
						$form_dis='';
					}*/
					//select данных по организации
					$_TPL['ROW']=$this->viewOrganization($_TPL['USERDATA']['id_org_ind']);

					if ($_POST/* && $finish_acquisition_tsh>time() || $_POST && empty($TPL['INFO']['datetime_electron_bid_receiving'])*/) {
						$tmp=$this->prepareInfoorgData($_POST);
						$_TPL['ERROR'] = $tmp['error'];
						$_TPL['ROW'] = $tmp['data'];
							if (!count($tmp['error'])){
								$row['complete_info'] = 1;
								ManagerUser::editUserData(USER_ID, $row);
								//$this->tableComplete($_GET['id'], $action, 1);
								$_TPL['ERROR'][] = "Форма заполнена полностью";
							} else {
								$row['complete_info'] = 0;
								ManagerUser::editUserData(USER_ID, $row);
								//$this->tableComplete($_GET['id'], $action, 0);
							}
						$sql=sql_placeholder('update ?#FK_APP_ORG set ?% where id=? ', $_TPL['ROW'], $_TPL['USERDATA']['id_org_ind']);
						$this->db->query($sql);
						//$user=& new ManagerUser($db);
						$user_data=ManagerUser::GetUserData(SESSION_ID);
						$_TPL['USERDATA'] =&$user_data;
					}
                    include TPL_CMS_FORMS."info-org.php";
					}else{
					include TPL_CMS_FORMS."no-rights.php";
					}
                break;

				case($action=='infofiz'): // && $_GET['id']
					//получаем информацию о заявке
					//$TPL['INFO']=$this->viewBid(intval($_GET['id']));
					//$finish_acquisition_tsh = strtotime($TPL['INFO']['finish_acquisition']);
					if (USER_TYPE == 'fiz' && USER_ID>2) { //$TPL['INFO']['user_id'] == USER_ID && 
					/*if ($finish_acquisition_tsh<time() || !empty($TPL['INFO']['datetime_electron_bid_receiving'])) {
						$form_dis='disabled="disabled"';
					} else {
						$form_dis='';
					}*/
					//select данных по индивидуальному
					$_TPL['ROW']=$this->viewIndividual($_TPL['USERDATA']['id_org_ind']);//$TPL['INFO']['applicant_individual_id']

					if ($_POST/* && $finish_acquisition_tsh>time() || $_POST && empty($TPL['INFO']['datetime_electron_bid_receiving'])*/) {
						$tmp=$this->prepareInfofizData($_POST);
						$_TPL['ERROR'] = $tmp['error'];
						$_TPL['ROW'] = $tmp['data'];
							if (!count($tmp['error'])){
								$row['complete_info'] = 1;
								ManagerUser::editUserData(USER_ID, $row);
								//$this->tableComplete($_GET['id'], $action, 1);
								$_TPL['ERROR'][] = "Форма заполнена полностью";
							} else {
								$row['complete_info'] = 0;
								ManagerUser::editUserData(USER_ID, $row);
								//$this->tableComplete($_GET['id'], $action, 0);
							}
						$sql=sql_placeholder('update ?#FK_APP_IND set ?% where id=? ', $_TPL['ROW'], $_TPL['USERDATA']['id_org_ind']);//$TPL['INFO']['applicant_individual_id']
						$this->db->query($sql);
						$user_data=ManagerUser::GetUserData(SESSION_ID);
						$_TPL['USERDATA'] =&$user_data;
					}
                    include TPL_CMS_FORMS."info-fiz.php";
					}else{
					include TPL_CMS_FORMS."no-rights.php";
					}
                break;

				case($action=='subprogramedit'):
					// вывод списка подпрограмм для редактирования и добавления
					$TPL['SUBPROG'] = $this->listSubprogram();
                    include TPL_CMS_FORMS."subprogram-edit.php";
				break;
				case($action=='tz' && $_GET['id']):
                    $bid_id = intval($_GET['id']);
					//получаем информацию о заявке
					$TPL['INFO']=$this->viewBid($_GET['id']);
					if ($TPL['INFO']['user_id'] == USER_ID)
                    {
                        // write bid data
    					if ($_POST['tzinsert'])
                        {
    						$tmp=$this->prepareTZData($_POST,$bid_id);
    						$_TPL['ERROR'] = $tmp['error'];
    						$_TPL['ROW'] = $tmp['data'];
                			// обновить заполненость формы
                			if (!count($tmp['error'])){
    //            				$row['complete_info'] = 1;
    //            				ManagerUser::editUserData(USER_ID, $row);
                				$this->tableComplete($_GET['id'], $action, 1);
                				$_TPL['ERROR'][] = "Форма заполнена полностью";
                			} else {
                				//$row['complete_info'] = 0;
                				//ManagerUser::editUserData(USER_ID, $row);
                				$this->tableComplete($_GET['id'], $action, 0);
                			}

                            // вставка ТЗ в БД
                            $_TPL['ROW']['start_date'] = $_POST['yearstart']."-".$_POST['monthstart']."-01";
                            $_TPL['ROW']['finish_date'] = $_POST['yearend']."-".$_POST['monthend']."-01";

            				//update bid set start_date, finish_date, work_topic
    						$sql=sql_placeholder('update ?#FK_BID set work_topic= ?,
                                                                  place_name=?,
                                                                  place_type_id=?,
                                                                  place_district_id=?,
                                                                  place_okrug_id=?,
                                                                  start_date=?,
                                                                  finish_date=?
                                                  where id=?',
                                $_TPL['ROW']['work_topic'], $_TPL['ROW']['place_name'],
                                $_TPL['ROW']['place_type_id'], $_TPL['ROW']['place_district_id'],
                                $_TPL['ROW']['place_okrug_id'], $_TPL['ROW']['start_date'],
                                $_TPL['ROW']['finish_date'], $_GET['id']);
    						$this->db->query($sql);
                        }

                        // read bid data
    					$TPL['INFO']=$this->viewBid($bid_id);
                        $_TPL['WORKPURPOSE'] = $this->viewWorkPurpose($bid_id);
                        $_TPL['WORKREQUIREMENT'] = $this->viewWorkRequirement($bid_id);
                        $_TPL['WORKCONDITION'] = $this->viewWorkCondition($bid_id);
                        $_TPL['SAFETYREQUIREMENTS'] = $this->viewSafetyRequirements($bid_id);
                        $_TPL['WORKATTRIBUTESNUM'] = count($_TPL['WORKPURPOSE'])+
                                                     count($_TPL['WORKREQUIREMENT'])+
                                                     count($_TPL['WORKCONDITION'])+
                                                     count($_TPL['SAFETYREQUIREMENTS']);
                        // dates
                        $start_year = $this->getStartRealizationDate($bid_id);
                        $startDateArr = split("-",$TPL['INFO']['start_date']);
    					$TPL['YEARSTART']=listYears2('yearstart', $start_year[0]['start_realization'],
                                                     $start_year[0]['start_realization']+$start_year[0]['duration_realization']-1,
                                                     $startDateArr[0]                                                           );
    					$TPL['MONTHSTART']=listMonth('monthstart', $startDateArr[1]);

                        $finishDateArr = split("-",$TPL['INFO']['finish_date']);
    					$TPL['YEAREND']=listYears2('yearend', $start_year[0]['start_realization'],
                                                    $start_year[0]['start_realization']+$start_year[0]['duration_realization']-1,
                                                    $finishDateArr[0]);
    					$TPL['MONTHEND']=listMonth('monthend', $finishDateArr[1]);
                        // places
                        $TPL['REGIONS'] = $this->getDistricts();
                        $TPL['OKRUGS'] = $this->getOkrugs();
                        include TPL_CMS_FORMS."tz.php";
					}else{
					include TPL_CMS_FORMS."no-rights.php";
					}
				break;
/*				case($action=='calendplan' && $_GET['id']):
					$TPL['INFO']=$this->viewBid($_GET['id']);
                    $startDateArr = split("-",$TPL['INFO']['start_date']);
                    $finishDateArr = split("-",$TPL['INFO']['finish_date']);
					$start = $startDateArr[0];
					$end = $finishDateArr[0];
					$years = array();
					for ($i=$start; $i<=$end; $i++) {
						$years[] = $i;
					}

                    include TPL_CMS_FORMS."calendar-plan.php";
				break;*/
				case($action=='delstep' && $_GET['stepid']):
                    list($steps_amount,$year,$completes) = $this->delStep($_GET['stepid']);
                    $y = split("-",$year);
                    echo '{"steps_amount":"'.($steps_amount-1).'","year":"'.$y[0].'","completes":"'.join("_",$completes).'"}';
				break;
				case($action=='addstep' && $_GET['bidid'] && $_GET['year']):
              		$row['year'] = $_GET['year']."-01-01";
               		$sql=sql_placeholder('select MAX(step_number) sn from ?#FK_WORK_STEP where bid_id=? AND year=? ', $_GET['bidid'], $row['year']);
                    $step_number_arr = $this->db->_array_data($sql);
                    if (!empty($step_number_arr))
                    {
                        $step_number = $step_number_arr[0]['sn']+1;
                        if ($step_number > 4) {echo '{"step_id":"'.$id.'", "step_number":"'.$step_number.'"}'; exit;}
                    } else {$step_number = 1;}
              		$row['step_number'] = $step_number;
              		$row['year'] = $_GET['year']."-01-01";
                  	$row['bid_id'] = $_GET['bidid'];
                    $id=$this->db->addrow(FK_WORK_STEP,  $row);
                    $jsonStepData = '{"step_id":"'.$id.'", "step_number":"'.$step_number.'"}';
                   	echo $jsonStepData;
				break;
				case($action=='price' && $_GET['id']):
					$TPL['INFO']=$this->viewBid(intval($_GET['id']));
					$finish_acquisition_tsh = strtotime($TPL['INFO']['finish_acquisition']);
					if ($TPL['INFO']['user_id'] == USER_ID) {
						$TPL['WAGE']=$this->listWage();
						$TPL['COST']=$this->listCost();
						$TPL['PERFORMERS']=$this->listPerformers(intval($_GET['id']));
						if ($finish_acquisition_tsh<time() || !empty($TPL['INFO']['datetime_electron_bid_receiving'])) {
							$form_dis='disabled="disabled"';
						} else {
							$form_dis='';
						}
					include TPL_CMS_FORMS."price.php";
					} else {
					include TPL_CMS_FORMS."no-rights.php";
					}
				break;
				case($action=='priceajax' && $_GET['id']):
					$TPL['INFO']=$this->viewBid(intval($_GET['id']));
					if ($TPL['INFO']['user_id'] == USER_ID) {
					if ($_POST) {
					$array = array();
					foreach ($_POST as $row=>$key) {
						foreach ($key as $i=>$val) {
							$array[$i][$row] .= $val;
						}
					}
					$r = array();
					$prodolzhitelnost = $array[0]['prodolzhitelnost'];
					$user_nds = ($_POST['user_nds']!=0)?($_POST['user_nds']/100):0; // ндс из формы пользователя
					$summa_koefitsent_specialists = 0;
					$summa_performers = 0;
					foreach ($array as $row) {
						$ii = strval($this->viewWage($row['dolzhnost']));
						if (!$ii) {
							$error[] = 'Неправильно введены данные';
						} else {
							if (empty($row['fact_time_job']) ||  empty($prodolzhitelnost) || empty($row['number_performers'])) {
								$error[] = 'Неправильно введены данные';
							} else {
								$cfq = $this->calculateCoefQualif($row['fact_time_job'], $prodolzhitelnost, $row['number_performers'], $ii);
								if (!$cfq) {
									$error[] = 'Неправильно введены данные';
								} else {
									$summa_koefitsent_specialists = $summa_koefitsent_specialists + $cfq; //Сумма Ккв(уч)
									$chp = $chp + $row['number_performers'];//Чп
									$r[] = array('input'=>'koefitsent_specialists_'.$row['row'], 'value'=>round($cfq, 3));
								}
							}
						}
					}
					//Коэффициент квалификации (участия) специалистов итог = Сумма строк/Чп
					if (!empty($chp) || $summa_koefitsent_specialists !=0) {
					$cfqe = $this->calculateCoefQualifEnd($summa_koefitsent_specialists, $chp);
						if (!$cfqe) {
							$error[] = 'Неправильно введены данные';
						} else {
							$r[] = array('input'=>'koefitsent_specialists', 'value'=>round($cfqe, 3));
							// получим значения для расчета
							$TPL['COST']=$this->listCost();
							//Среднедневная зарплата исполнителей
							$w_d = $this->calculateAverageDailyWages($TPL['COST'][0]['value'], $TPL['COST'][1]['value']);
							if (!$w_d) {
								$error[] = 'Неправильно введены данные';
							} else {
								$r[] = array('input'=>'wages_of_performers', 'value'=>round($w_d, 2));
								//Единичная себестоимость
								$ss1 = $this->calculateUnitCost($w_d, $TPL['COST'][2]['value']);
								if (!$ss1) {
									$error[] = 'Неправильно введены данные';
								} else {
									$r[] = array('input'=>'unit_cost', 'value'=>round($ss1, 2));
									//Общая себестоимость выполняемых работ
									$ssob = $this->calculateAllCost($ss1,$prodolzhitelnost,$chp,$cfqe);
									if (!$ssob) {
										$error[] = 'Неправильно введены данные';
									} else {
										$r[] = array('input'=>'all_cost', 'value'=>round($ssob/1000, 2));
										//Стоимость работ  ССоб* Р
										$ss2000 = $this->calculatePriceWorks($ssob, $TPL['COST'][3]['value']);
										if (!$ss2000) {
											$error[] = 'Неправильно введены данные';
										} else {
											$r[] = array('input'=>'price_works', 'value'=>round($ss2000/1000, 2));
											//Стоимость выполняемых работ (услуг) в текущих ценах (тыс. руб.), Ст.ц.
											$stc = $this->calculatePriceWorksActual($ss2000, $TPL['COST'][4]['value']);
											if (!$stc) {
												$error[] = 'Неправильно введены данные';
											} else {
												$r[] = array('input'=>'price_works_actual', 'value'=>round($stc/1000, 2));
												//В том числе НДС (тыс. руб.)
												$nds = ($user_nds!=0)?$this->calculateNDS($stc, $user_nds):0;
												//$nds = $this->calculateNDS($stc, $user_nds); // тут рассчитываем ндс $TPL['COST'][5]['value']
												if (!isset($nds)) {
													$error[] = 'Неправильно введены данные';
												} else {
													$r[] = array('input'=>'nds', 'value'=>round($nds/1000, 2));
												}
											}
										}
									}
								}
							}
						}
					} else {
						$error[] = 'Неправильно введены данные';
					}
					if (!count($error)) {
						$result = array('type'=>'success', 'value'=>$r);
						// пишем данные в базу предварительно, удалив старые
						if ($_POST['add'] == 1) {
							// получить всех исполнителей
							$performers = $this->listPerformers($_GET['id']);
							// если они есть, удалить всех исполнителей
							if ($performers) {
								$this->deletePerformers($_GET['id']);
							}
							// записать исполнителей
							foreach ($array as $row) {
								$rows['dolzhnost'] = $row['dolzhnost'];
								$rows['fact_time_job'] = $row['fact_time_job'];
								$rows['number_performers'] = $row['number_performers'];
								$rows['bid_id'] = $_GET['id'];
								$this->db->addrow(FK_PERFORMER, $rows);
							}
							// обновить значения полей в таблице bid
							$bid_info = array();
							$bid_info['duration'] = $prodolzhitelnost;
							$bid_info['price_works_actual'] = round($stc,2);
							$bid_info['nds'] = round($nds,2);
							$bid_info['user_nds'] = $user_nds;
							$this->updateBid($_GET['id'], $bid_info);
							// обновить заполненость формы
							$this->tableComplete($_GET['id'], 'price', 1);
						}
					} else {
						$result = array('type'=>'error');
						// не пишем данные в базу
						$this->tableComplete($_GET['id'], 'price', 0);
					}
					print json_encode($result);
					}
					}
				break;
				
				case($action=='other-price' && $_GET['id']):
					$TPL['INFO']=$this->viewBid(intval($_GET['id']));
					$finish_acquisition_tsh = strtotime($TPL['INFO']['finish_acquisition']);
					if ($TPL['INFO']['user_id'] == USER_ID) {
						if ($_POST) {
							if (empty($_POST['price_works_actual']) || empty($_POST['user_nds'])) {
								$error[]='Заполнены не все поля';
								$_TPL['ERROR']=$error;
							}
							$filename=$_GET['id'].".pdf";
							$err=$this->upload_pdf('price', $filename, PRICE_PDF);
							
							if ($err==-1){
								$error[]='Файл не *.pdf';
								$_TPL['ERROR']=$error;
							}elseif ($err==0){
								$error[]='При загрузке файла возникла ошибка';
								$_TPL['ERROR']=$error;
							}
							if(!count($error)) { // если нет ошибок
								//стоимость работ, ставка НДС, сумма НДС (=стоим. работ*ставка НДС).
								$bid_info = array();
								$bid_info['price_works_actual'] = $_POST['price_works_actual'];
								$bid_info['user_nds'] = $_POST['user_nds']/100;
								$bid_info['nds'] = $_POST['price_works_actual']*($_POST['user_nds']/100);
								$this->updateBid($_GET['id'], $bid_info);
								// обновить заполненость формы
								$this->tableComplete($_GET['id'], 'price', 1);
							}
						}
						$TPL['INFO']=$this->viewBid(intval($_GET['id']));
						if ($finish_acquisition_tsh<time() || !empty($TPL['INFO']['datetime_electron_bid_receiving'])) {
							$form_dis='disabled="disabled"';
						} else {
							$form_dis='';
						}
					include TPL_CMS_FORMS."other-price.php";
					} else {
					include TPL_CMS_FORMS."no-rights.php";
					}
				break;
				
				

				
				case($action=='1' && $_GET['id']): // это генерация печатной формы заявки
					$TPL['INFO']=$this->viewBid(intval($_GET['id'])); //получили информацию о заявке
					echo "<pre>";
					print_r($TPL['INFO']);
					echo "</pre>";
					$TPL['LISTFIELDS'] = $this->listFieldsTZ();
					$_TPL['WORKPURPOSE'] = $this->viewWorkPurpose($_GET['id']);
					$_TPL['WORKREQUIREMENT'] = $this->viewWorkRequirement($_GET['id']);
					$_TPL['WORKCONDITION'] = $this->viewWorkCondition($_GET['id']);

					$TPL['STEPSDATA'] = $this->stepWorksByBid($_GET['id']);
				
					include TPL_CMS_FORMS."print-tz.php";
				break;
				
				
				
				
				case($action=='print' && $_GET['id']): // это генерация печатной формы заявки
					$TPL['INFO']=$this->viewBid(intval($_GET['id'])); //получили информацию о заявке
					$bid_number = $TPL['INFO']['start_realization']."-".$TPL['INFO']['measure_has_notice_measure_id']."-".$_GET['id'];
					// получаем заявку
					if (USER_TYPE == 'yur') {
						$TPL['APPLICANT']=$this->viewOrganization($_TPL['USERDATA']['id_org_ind']);
						$applicant_form = $TPL['APPLICANT']['director_duty']." ".$TPL['APPLICANT']['full_title'];
						$applicant_name = $TPL['APPLICANT']['director_lastname_initials'];
					} elseif (USER_TYPE == 'fiz') {
						$TPL['APPLICANT']=$this->viewIndividual($_TPL['USERDATA']['id_org_ind']);
						if ($TPL['APPLICANT']['org_form'] != 'физическое лицо') {
							$applicant_form = $TPL['APPLICANT']['org_form'];
						}
						$applicant_name = $TPL['APPLICANT']['last_name']." ".$TPL['APPLICANT']['first_name']." ".$TPL['APPLICANT']['middle_name'];
					}
					$formname = md5(USER_ID.$_GET['id'].$_TPL['USERDATA']['passwd']);
					$finish_acquisition_tsh = strtotime($TPL['INFO']['finish_acquisition']);
					// если владелец заявки = пользователю, время окончания заявок больше текущего времени и если заявку еще не подавали в электронном виде, значит можно генерить pdf
					if ($TPL['INFO']['user_id'] == USER_ID) {
						// начнем с генерации формы прайса
						if ($finish_acquisition_tsh>time() && empty($TPL['INFO']['datetime_electron_bid_receiving'])) {
						if (!file_exists(PRICE_PDF.$_GET['id'].'.pdf')){ // если нет файла на сервере, значит рассчет производился нашим калькулятором, значит нужно генерить форму
							ob_start();
							//$TPL['WAGE']=$this->listWage();
							$TPL['COST']=$this->listCost();
							$TPL['PERFORMERS']=$this->listPerformersWage(intval($_GET['id']));
							include TPL_CMS_FORMS."print-price.php";
							$price=ob_get_contents();
							ob_end_clean();
						}
						
						// страницы ТЗ
						ob_start();
						$TPL['LISTFIELDS'] = $this->listFieldsTZ();
						$_TPL['WORKPURPOSE'] = $this->viewWorkPurpose($_GET['id']);
						$_TPL['WORKREQUIREMENT'] = $this->viewWorkRequirement($_GET['id']);
						$_TPL['WORKCONDITION'] = $this->viewWorkCondition($_GET['id']);
						$_TPL['SAFETYREQUIREMENT'] = $this->viewSafetyRequirements($_GET['id']);
						$TPL['STEPSDATA'] = $this->stepWorksByBid($_GET['id']);
						include TPL_CMS_FORMS."print-tz.php";
						$tz=ob_get_contents();
						ob_end_clean();
						//и тд и тп.
						
						// календарь
						ob_start();
						$TPL['STEPDATA'] = $this->stepWorksByBidMerge($_GET['id']);
						$array = array();
						foreach ($TPL['STEPDATA'] as $row) {
							$array[$row['year']][$row['step_number']][] = $row;
						}
						include TPL_CMS_FORMS."print-calendar.php";
						$calendar=ob_get_contents();
						ob_end_clean();
						// генерация PDF
						require_once('/var/www/pdf/config/lang/rus.php');
						require_once('/var/www/pdf/tcpdf.php');

						// create new PDF document
						$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

						// set document information
						//$pdf->SetCreator(PDF_CREATOR);
						//$pdf->SetAuthor('Nicola Asuni');
						//$pdf->SetTitle('TCPDF Example 006');
						//$pdf->SetSubject('TCPDF Tutorial');
						//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
						// set font
						$pdf->SetFont('dejavusans', '', 10);

						// set header and footer fonts
						$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
						$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
						// set default monospaced font
						$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

						//set margins
						$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
						$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
						$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

						//set auto page breaks
						$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

						//set image scale factor
						$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

						//set some language-dependent strings
						$pdf->setLanguageArray($l);

						$pdf->setHeaderTemplateAutoreset(true);
						$pdf->SetHeaderData(false, false, 'Заявка №'.$bid_number, 'Форма ТЗ');
						$pdf->AddPage();
						$pdf->writeHTML($tz, true, false, true, false, '');
						$pdf->lastPage();
						
						$pdf->setHeaderTemplateAutoreset(true);
						$pdf->SetHeaderData(false, false, 'Заявка №'.$bid_number, 'Форма КП');
						$pdf->AddPage();
						$pdf->writeHTML($calendar, true, false, true, false, '');
						$pdf->lastPage();

						if (isset($price)) {
							// set default header data
							$pdf->setHeaderTemplateAutoreset(true);
							$pdf->SetHeaderData(false, false, 'Заявка №'.$bid_number, 'Форма ОЦ');
							$pdf->AddPage();
							$pdf->writeHTML($price, true, false, true, false, '');
							$pdf->lastPage();
						}

						$pdf->Output(DIR_FORM_PDF.$formname.'.pdf', 'F');
						}

						include TPL_CMS_FORMS."print.php";

					} else {
						include TPL_CMS_FORMS."no-rights.php";
					}
				break;

				case($_GET['action']=='delete-pdf' && $_GET['id']):
					if (file_exists(PRICE_PDF.$_GET['id'].'.pdf')){
						unlink(PRICE_PDF.$_GET['id'].'.pdf');
					}
					header('Location: /forms/bid/'.$_GET['id'].'/other-price');
					// тут надо еще форму скинуть на не заполненную
					$this->tableComplete($_GET['id'], 'price', 0);
                    exit;
				break;

				case($action=='tablestep' || $action=='calendplan'):
                    $bid_id = intval($_GET['id']);
					//получаем информацию о заявке
    				$TPL['INFO']=$this->viewBid(intval($_GET['id']));
					if ($TPL['INFO']['user_id'] == USER_ID)
                    {
                        // if calendar  plan exists..
                        $stepWorksForBid = $this->stepWorksByBid($bid_id);
                        //if doesn't exist - write new steps of work
                        if (empty($stepWorksForBid))
                        {
                            $startDateArr = split("-",$TPL['INFO']['start_date']);
                            $finishDateArr = split("-",$TPL['INFO']['finish_date']);
                            if (empty($TPL['INFO']['start_date']) || empty($TPL['INFO']['finish_date'])) {include TPL_CMS_FORMS."redirect-tz.php";exit;}
        					$start = $startDateArr[0];
        					$end = $finishDateArr[0];
                            for ($year=$start;$year<=$end;$year++)
                            {
                        		$row['step_number'] = 1;
                        		$row['year'] = $year."-01-01";
                            	$row['bid_id'] = $bid_id;
                            	$id=$this->db->addrow(FK_WORK_STEP,  $row);
                            }
                        }
                        // update data about steps of work
                        if (isset($_POST['addstepsdata']))
                        {
                            $step_id = $_GET['step_id'];
                            // check if all fields are filled in then mark a "complete"
                            $complete = $this->checkStepIsComplete($_POST);
                      		$sql=sql_placeholder('update ?#FK_WORK_STEP set report_documentation_composition=?,
                                                                            cost=?,
                                                                            handing_over_date=STR_TO_DATE(?,"%d.%m.%Y"),
                                                                            duration_in_month=?,
                                                                            complete=?
                                                  where bid_id=? and id=?',
                                                        $_POST['report_documentation_composition'],
                                                        $_POST['cost'], $_POST['handing_over_date'],
                                                        $_POST['duration_in_month'], $complete, $bid_id, $step_id);
                    		$this->db->query($sql);
                        }
            			// обновить заполненость формы
                        $stepWorksForBid = $this->stepWorksByBid($bid_id);
                        foreach($stepWorksForBid as $stp)
                        {
                          if ($stp['complete']!=1)
                            {
                                $this->tableComplete($_GET['id'], 'calendplan', 0); break;
                            } else {$this->tableComplete($_GET['id'], 'calendplan', 1);}
                        }
                        // read steps of work and existed works
                        $TPL['STEPSDATA'] = $this->stepWorksByBid($bid_id);
                        include TPL_CMS_FORMS."table-step.php";
					}else{
					include TPL_CMS_FORMS."no-rights.php";
					}
				break;
				case($action=='newwork'):
                    $bidid_stepid_arr = split("_",$_GET['bidid_stepid']);
      				$row['work_step_id'] = $bidid_stepid_arr[1];
                    // write new work
    				if (isset($_GET['new']))
                    {
        				$row['title'] = $_POST['work_name'];
        				$row['description'] = $_POST['work_description'];
        				$this->db->addrow(FK_WORK, $row);
                    }
                    //read works for step_id
              		$sql=sql_placeholder('select * from ?#FK_WORK where work_step_id=? order by id', $row['work_step_id']);
                    $works_list = $this->db->_array_data($sql);
                    $jsonworkslist = "[{works_data:[";
                    foreach ($works_list as $work)
                    {
                        if (empty($work['description'])) {$jsonworkslist .= "{id: ".$work['id'].", title: '".$work['title']."', done: 0},";}
                        else {$jsonworkslist .= "{id: ".$work['id'].", title: '".$work['title']."', done: 1},";}
                    }
                    $jsonworkslist .= "],ws:".$row['work_step_id']."}]";
                    echo $jsonworkslist;
				break;
				case($action=='modifywork'):
                    $work_id = $_GET['workid'];
                    // delete work
    				if (isset($work_id))
                    {
                        //what step_id connected with work_id
                		$sql=sql_placeholder('select work_step_id from ?#FK_WORK where id=? order by id', $work_id);
                		$work_step_id_arr = $this->db->_array_data($sql);
                        $row['work_step_id'] = $work_step_id_arr[0]['work_step_id'];
                        //del
                        if (isset($_GET['del']))
                        {
                    		$sql=sql_placeholder('delete from ?#FK_WORK where id=?', $work_id);
                    		$this->db->query($sql);
                        }
                        //edit
                        if (isset($_GET['edit']))
                        {
            				$row['title'] = $_POST['work_name'];
            				$row['description'] = $_POST['work_description'];
                    		$sql=sql_placeholder('update ?#FK_WORK set ?% where id=? ', $row, $work_id);
                    		$this->db->query($sql);
                        }
                    }
                    //read works for step_id
              		$sql=sql_placeholder('select * from ?#FK_WORK where work_step_id=?  order by id', $row['work_step_id']);
                    $works_list = $this->db->_array_data($sql);
                    $jsonworkslist = "[{works_data:[";
                    foreach ($works_list as $work)
                    {
                        if (empty($work['description'])) {$jsonworkslist .= "{id: ".$work['id'].", title: '".$work['title']."', done: 0},";}
                        else {$jsonworkslist .= "{id: ".$work['id'].", title: '".$work['title']."', done: 1},";}
                    }
                    $jsonworkslist .= "],ws:".$row['work_step_id']."}]";
                    echo $jsonworkslist;
				break;
				case($action=='getwork'):
                    $work_id = $_GET['workid'];
                    // get work's data
    				if (isset($work_id))
                    {
                		$sql=sql_placeholder('select * from ?#FK_WORK where id=?', $work_id);
                		$wd = $this->db->select_row($sql);
                        $jsonworkdata = "{title: '".$wd['title']."', description: '".$wd['description']."', work_id: '".$work_id."'}";
                        echo $jsonworkdata;
                    }
				break;
  				case($action=='noticeread'):
                    $TPL['NOTICES'] = $this->allBids();
                    include TPL_CMS_FORMS."notice-read.php";
				break;
				case($action=='noticeedit'):
					// создание/редактирование объявления о сборе тематики и связи его с мероприятиями
                    echo "создание/редактирование объявления о сборе тематики";
                    include TPL_CMS_FORMS."notice-edit.php";
				break;
				case($action=='createbid' && $_GET['nt'] && $_GET['mr']):
					// создание заявки
					if (USER_GROUP == 2) {
					$row['user_id'] = USER_ID;
					$row['date_create_bid'] = date('Y-m-d');
					$row['measure_has_notice_measure_id'] = $_GET['mr'];
					$row['measure_has_notice_notice_id'] = $_GET['nt'];
					//$row['datetime_electron_bid_receiving'] = date('Y-m-d H:i:s');
					if (USER_TYPE == 'fiz') {
						$row['applicant_individual_id'] = USER_ORG_IND;
					}elseif  (USER_TYPE == 'yur') {
						$row['applicant_organization_id'] = USER_ORG_IND;
					}
					$id=$this->db->addrow(FK_BID,  $row);
					if ($id) {
					// добавляем три записи в таблицу с формами
					$forms = array('tz','calendplan','price');
						foreach ($forms as $form) {
							$data['bid_id'] = $id;
							$data['form_type'] = $form;
							$data['complete'] = 0;
							$this->db->addrow(FK_FORM, $data);
						}
						header('Location: /forms/bid/'.$id);
					}
					}
				break;
				case($action=='bid' && $_GET['id']):
					//получаем информацию о заявке
					$TPL['INFO']=$this->viewBid($_GET['id']);
					if ($TPL['INFO']['user_id'] == USER_ID) {
						$TPL['COMPLETE']=$this->formsComplete($_GET['id']);
						$finish_acquisition_tsh = strtotime($TPL['INFO']['finish_acquisition']);
						$formname = md5(USER_ID.$_GET['id'].$_TPL['USERDATA']['passwd']);
						
						$compl = 0;
						foreach ($TPL['COMPLETE'] as $complete) {
							if ($complete['complete']==1) {
									$compl = $compl +1;
							}
						}
						
						if ($compl!=3) {
							$form_dis_submit = ' disabled="disabled"';
							$form_dis_print = ' disabled="disabled"';
						} elseif ($_TPL['USERDATA']['complete_info'] != 1) {
							$form_dis_submit = ' disabled="disabled"';
							$form_dis_print = ' disabled="disabled"';
						} elseif (!empty($TPL['INFO']['datetime_electron_bid_receiving'])) { // заявка отправлена уже
							$form_dis_submit = ' disabled="disabled"';
							$form_dis_print = '';
						} elseif ($finish_acquisition_tsh<time()) {
							$form_dis_submit = ' disabled="disabled"';
							$form_dis_print = '';
						} elseif (!file_exists(DIR_FORM_PDF.$formname.'.pdf')) { // пока не сгенерирован пдф
							$form_dis_submit = ' disabled="disabled"';
							$form_dis_print = '';
						} else {
							$form_dis_submit = '';
							$form_dis_print = '';
						}
						if ($_POST['post-elect-bid']) {
							$bid_info = array();
							$bid_info['datetime_electron_bid_receiving'] = date('Y-m-d H:i:s');
							$this->updateBid($_GET['id'], $bid_info);
						}
					include TPL_CMS_FORMS."bid.php";
					}else{
					include TPL_CMS_FORMS."no-rights.php";
					}
				break;
				case($action=='index'):
					$_TPL['TITLE'] [] = 'Первая страница';
                    $_POST['not'] =1; /*!!!!!!remake it  это номер объявления, для которого сейчас формируется информационное сообщение*/
                    if (!empty($_POST['not']))
                    {
                        $not = intval($_POST['not']);
                        $TPL['INFORMNOTE'] = $this->makeInformNote($not);
                        $stert_acquisition_arr = split(":",strval($TPL['INFORMNOTE'][0]['start_acquisition']));
                        $TPL['stert_acquisition'] = join(":",array($stert_acquisition_arr[0],$stert_acquisition_arr[1]));
                        $finish_acquisition_arr = split(":",strval($TPL['INFORMNOTE'][0]['finish_acquisition']));
                        $TPL['finish_acquisition'] = join(":",array($finish_acquisition_arr[0],$finish_acquisition_arr[1]));
                    }
					include TPL_CMS_FORMS."form0.php";
				break;
            }
        }
	function nameform($form_type) {
		if ($form_type == 'infofiz') {
			$form_name = "Сведения о физическом лице";
		} elseif  ($form_type == 'infoyur') {
			$form_name = "Сведения об организации - юридическое лицо";
		} elseif  ($form_type == 'tz') {
			$form_name = "Техническое задание";
		} elseif  ($form_type == 'calendplan') {
			$form_name = "Календарный план выполнения работ";
		} elseif  ($form_type == 'price') {
			$form_name = "Обоснование цены";
		}
		return $form_name;
	}

    function checkStepIsComplete($step_data)
    {
        if (!empty($step_data['report_documentation_composition']) &&
            !empty($step_data['cost']) &&
            !empty($step_data['handing_over_date']) && ($step_data['handing_over_date'] != '0000-00-00') &&
            !empty($step_data['duration_in_month']) ) {return true;} else {return false;}
    }

	function prepareInfoorgData($row) { //валидация формы для организации
		$data = array();
		$error = array();
		if (empty($row['full_title'])) {
			$error[]='Укажите Полное наименование организации';
			$data['full_title'] = '';
		} else {
			$data['full_title'] = $row['full_title'];
		}
		if (empty($row['full_title_genitive'])) {
			$error[]='Укажите Полное наименование организации в родительном падеже';
			$data['full_title_genitive'] = '';
		} else {
			$data['full_title_genitive'] = $row['full_title_genitive'];
		}
		if (empty($row['full_title_instrumental'])) {
			$error[]='Укажите Полное наименование организации в творительном падеже';
			$data['full_title_instrumental'] = '';
		} else {
			$data['full_title_instrumental'] = $row['full_title_instrumental'];
		}
		if (empty($row['short_title'])) {
			$error[]='Укажите Краткое наименование организации';
			$data['short_title'] = '';
		} else {
			$data['short_title'] = $row['short_title'];
		}
		if (empty($row['INN'])) {
			$error[]='Укажите ИНН';
			$data['INN'] = '';
		} else {
			$data['INN'] = $row['INN'];
		}
		if (empty($row['KPP'])) {
			$error[]='Укажите КПП';
			$data['KPP'] = '';
		} else {
			$data['KPP'] = $row['KPP'];
		}
		if (empty($row['OGRN'])) {
			$error[]='Укажите ОГРН';
			$data['OGRN'] = '';
		} else {
			$data['OGRN'] = $row['OGRN'];
		}
		if (empty($row['OGRN_attribution'])) {
			$error[]='Укажите Дату присвоения ОГРН';
			$data['OGRN_attribution'] = '';
		} else {
			if (preg_match("#[0-9.]{3}[0-9.]{3}[0-9.]{5}#is", $row['OGRN_attribution'])) {
				$pieces = explode(".", $row['OGRN_attribution']);
				$row['OGRN_attribution'] = $pieces[2]."-".$pieces[1]."-".$pieces[0];
				$data['OGRN_attribution'] = $row['OGRN_attribution'];
			} else {
				$data['OGRN_attribution'] = $row['OGRN_attribution'];
			}
		}
		if (empty($row['title_for_bank']) && $row['title_for_bank_check'] != 'no'){
			$error[]='Необходимо указать наименование принятое в ОФК';
			$data['title_for_bank'] = '';
		}elseif (!empty($row['title_for_bank']) && $row['title_for_bank_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрено ли наименование принятое в ОФК';
			$data['title_for_bank'] = '';
        }elseif(!empty($row['title_for_bank'])) {
			$data['title_for_bank'] = $row['title_for_bank'];
		}elseif ($row['title_for_bank_check'] == 'no') {
			$data['title_for_bank'] = $row['title_for_bank_check'];
		}
		if (empty($row['bank_ls']) && $row['bank_ls_check'] != 'no'){
			$error[]='Необходимо указать Л/С';
			$data['bank_ls'] = '';
		}elseif (!empty($row['bank_ls']) && $row['bank_ls_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрено ли Л/С';
			$data['bank_ls'] = '';
        }elseif(!empty($row['bank_ls'])) {
			$data['bank_ls'] = $row['bank_ls'];
		}elseif ($row['bank_ls_check'] == 'no') {
			$data['bank_ls'] = $row['bank_ls_check'];
		}
		if (empty($row['bank_bik'])) {
			$error[]='Укажите БИК';
			$data['bank_bik'] = '';
		} else {
			$data['bank_bik'] = $row['bank_bik'];
		}
		if (empty($row['bank_ras'])) {
			$error[]='Укажите Расчетный счет';
			$data['bank_ras'] = '';
		} else {
			$data['bank_ras'] = $row['bank_ras'];
		}
		if (empty($row['bank_cor']) && $row['bank_cor_check'] != 'no'){
			$error[]='Необходимо указать Корреспондентский счет';
			$data['bank_cor'] = '';
		}elseif (!empty($row['bank_cor']) && $row['bank_cor_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрен ли Корреспондентский счет';
			$data['bank_cor'] = '';
        }elseif(!empty($row['bank_cor'])) {
			$data['bank_cor'] = $row['bank_cor'];
		}elseif ($row['bank_cor_check'] == 'no') {
			$data['bank_cor'] = $row['bank_cor_check'];
		}
		if (empty($row['bank_rasv']) && $row['bank_rasv_check'] != 'no'){
			$error[]='Необходимо указать Расчетный счет (внебюджетный)';
			$data['bank_rasv'] = '';
		}elseif (!empty($row['bank_rasv']) && $row['bank_rasv_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрен ли Расчетный счет (внебюджетный)';
			$data['bank_rasv'] = '';
        }elseif(!empty($row['bank_rasv'])) {
			$data['bank_rasv'] = $row['bank_rasv'];
		}elseif ($row['bank_rasv_check'] == 'no') {
			$data['bank_rasv'] = $row['bank_rasv_check'];
		}
		if (empty($row['bank_receiver'])) {
			$error[]='Укажите Наименование банка получателя';
			$data['bank_receiver'] = '';
		} else {
			$data['bank_receiver'] = $row['bank_receiver'];
		}
		if (empty($row['KBK']) && $row['KBK_check'] != 'no'){
			$error[]='Необходимо указать КБК';
			$data['KBK'] = '';
		}elseif (!empty($row['KBK']) && $row['KBK_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрен ли КБК';
			$data['KBK'] = '';
        }elseif(!empty($row['KBK'])) {
			$data['KBK'] = $row['KBK'];
		}elseif ($row['KBK_check'] == 'no') {
			$data['KBK'] = $row['KBK_check'];
		}
		if (empty($row['general_permission_num']) && $row['general_permission_num_check'] != 'no'){
			$error[]='Необходимо указать Номер генерального разрешения';
			$data['general_permission_num'] = '';
		}elseif (!empty($row['general_permission_num']) && $row['general_permission_num_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрен ли Номер генерального разрешения';
			$data['general_permission_num'] = '';
        }elseif(!empty($row['general_permission_num'])) {
			$data['general_permission_num'] = $row['general_permission_num'];
		}elseif ($row['general_permission_num_check'] == 'no') {
			$data['general_permission_num'] = $row['general_permission_num_check'];
		}
		if (empty($row['general_permission_date']) && $row['general_permission_date_check'] != '0000-00-00'){
			$error[]='Необходимо указать Дату генерального разрешения';
			$data['general_permission_date'] = '';
		}elseif (!empty($row['general_permission_date']) && $row['general_permission_date_check'] == '0000-00-00'){
            $error[]='Необходимо уточнить Дату генерального разрешения';
			$data['general_permission_date'] = '';
        }elseif(!empty($row['general_permission_date'])) {
			if (preg_match("#[0-9.]{3}[0-9.]{3}[0-9.]{5}#is", $row['general_permission_date'])) {
				$pieces = explode(".", $row['general_permission_date']);
				$row['general_permission_date'] = $pieces[2]."-".$pieces[1]."-".$pieces[0];
				$row['general_permission_date'] = $row['general_permission_date'];
			} else {
				$data['general_permission_date'] = $row['general_permission_date'];
			}
		}elseif ($row['general_permission_date_check'] == '0000-00-00') {
			$data['general_permission_date'] = $row['general_permission_date_check'];
		}
		if (empty($row['general_permission_paragraph']) && $row['general_permission_paragraph_check'] != 'no'){
			$error[]='Необходимо указать Пункт генерального разрешения';
			$data['general_permission_paragraph'] = '';
		}elseif (!empty($row['general_permission_paragraph']) && $row['general_permission_paragraph_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрен ли Пункт генерального разрешения';
			$data['general_permission_paragraph'] = '';
        }elseif(!empty($row['general_permission_paragraph'])) {
			$data['general_permission_paragraph'] = $row['general_permission_paragraph'];
		}elseif ($row['general_permission_paragraph_check'] == 'no') {
			$data['general_permission_paragraph'] = $row['general_permission_paragraph_check'];
		}
		if (empty($row['director_duty'])) {
			$error[]='Укажите Должность руководителя организации (в именительном падеже)';
			$data['director_duty'] = '';
		} else {
			$data['director_duty'] = $row['director_duty'];
		}
		if (empty($row['director_duty'])) {
			$error[]='Укажите Должность руководителя организации (в именительном падеже)';
			$data['director_duty'] = '';
		} else {
			$data['director_duty'] = $row['director_duty'];
		}
		if (empty($row['director_duty_genitive'])) {
			$error[]='Укажите Должность руководителя организации (в родительном падеже)';
			$data['director_duty_genitive'] = '';
		} else {
			$data['director_duty_genitive'] = $row['director_duty_genitive'];
		}
		if (empty($row['director_lastname_initials'])) {
			$error[]='Укажите Фамилия и инициалы руководителя организации (в именительном падеже)';
			$data['director_lastname_initials'] = '';
		} else {
			$data['director_lastname_initials'] = $row['director_lastname_initials'];
		}
		if (empty($row['director_fio'])) {
			$error[]='Укажите ФИО руководителя организации (полностью, в именительном падеже)';
			$data['director_fio'] = '';
		} else {
			$data['director_fio'] = $row['director_fio'];
		}
		if (empty($row['director_fio_genitive'])) {
			$error[]='Укажите ФИО руководителя организации (полностью, в родительном падеже)';
			$data['director_fio_genitive'] = '';
		} else {
			$data['director_fio_genitive'] = $row['director_fio_genitive'];
		}
		if (empty($row['based_on_doc_genitive'])) {
			$error[]='Укажите, на основании чего действует руководитель';
			$data['based_on_doc_genitive'] = '';
		} else {
			$data['based_on_doc_genitive'] = $row['based_on_doc_genitive'];
		}
		if (empty($row['OKATO'])) {
			$error[]='Укажите ОКАТО';
			$data['OKATO'] = '';
		} else {
			$data['OKATO'] = $row['OKATO'];
		}
		if (empty($row['OKPO'])) {
			$error[]='Укажите ОКПО';
			$data['OKPO'] = '';
		} else {
			$data['OKPO'] = $row['OKPO'];
		}
		if (empty($row['OKVED'])) {
			$error[]='Укажите ОКВЭД';
			$data['OKVED'] = '';
		} else {
			$data['OKVED'] = $row['OKVED'];
		}
		if (empty($row['OKOGU'])) {
			$error[]='Укажите ОКОГУ';
			$data['OKOGU'] = '';
		} else {
			$data['OKOGU'] = $row['OKOGU'];
		}
		if (empty($row['OKOPF'])) {
			$error[]='Укажите ОКОПФ';
			$data['OKOPF'] = '';
		} else {
			$data['OKOPF'] = $row['OKOPF'];
		}
		if (empty($row['legal_type_settlement'])) {
			$error[]='Укажите Тип населенного пункта для юридического адреса';
			$data['legal_type_settlement'] = '';
		} else {
			$data['legal_type_settlement'] = $row['legal_type_settlement'];
		}
		if (empty($row['legal_name_settlement'])) {
			$error[]='Укажите Название населенного пункта для юридического адреса';
			$data['legal_name_settlement'] = '';
		} else {
			$data['legal_name_settlement'] = $row['legal_name_settlement'];
		}
		if (empty($row['legal_post_index'])) {
			$error[]='Укажите Почтовый индекс для юридического адреса';
			$data['legal_post_index'] = '';
		} else {
			$data['legal_post_index'] = $row['legal_post_index'];
		}
		if (empty($row['legal_type_street']) || empty($row['legal_name_street'])) {
			$error[]='Укажите улицу, бульвар, проспект или др. юридического адреса';
			$data['legal_type_street'] = '';
			$data['legal_name_street'] = '';
		} else {
			$data['legal_type_street'] = $row['legal_type_street'];
			$data['legal_name_street'] = $row['legal_name_street'];
		}
		if (empty($row['legal_number_house'])) {
			$error[]='Укажите Номер дома для юридического адреса';
			$data['legal_number_house'] = '';
		} else {
			$data['legal_number_house'] = $row['legal_number_house'];
		}
			$data['legal_number_housing'] = $row['legal_number_housing'];
			$data['legal_number_structure'] = $row['legal_number_structure'];
			$data['legal_number_office'] = $row['legal_number_office'];
			$data['checkfactadress'] = $row['checkfactadress'];
		if ($row['checkfactadress'] == 'yes') { // если адреса совпадают
			$data['fact_type_settlement'] = $row['legal_type_settlement'];
			$data['fact_name_settlement'] = $row['legal_name_settlement'];	
			$data['fact_post_index'] = $row['legal_post_index'];		
			$data['fact_type_street'] = $row['legal_type_street'];
			$data['fact_name_street'] = $row['legal_name_street'];
			$data['fact_number_house'] = $row['legal_number_house'];
			$data['fact_number_housing'] = $row['legal_number_housing'];
			$data['fact_number_structure'] = $row['legal_number_structure'];
			$data['fact_number_office'] = $row['legal_number_office'];		
		} else {
			if (empty($row['fact_type_settlement'])) {
				$error[]='Укажите Тип населенного пункта для фактического адреса';
				$data['fact_type_settlement'] = '';
			} else {
				$data['fact_type_settlement'] = $row['fact_type_settlement'];
			}
			if (empty($row['fact_name_settlement'])) {
				$error[]='Укажите Название населенного пункта для фактического адреса';
				$data['fact_name_settlement'] = '';
			} else {
				$data['fact_name_settlement'] = $row['fact_name_settlement'];
			}
			if (empty($row['fact_post_index'])) {
				$error[]='Укажите Почтовый индекс для фактического адреса';
				$data['fact_post_index'] = '';
			} else {
				$data['fact_post_index'] = $row['fact_post_index'];
			}
			if (empty($row['fact_type_street']) || empty($row['fact_name_street'])) {
				$error[]='Укажите улицу, бульвар, проспект или др. фактического адреса';
				$data['fact_type_street'] = '';
				$data['fact_name_street'] = '';
			} else {
				$data['fact_type_street'] = $row['fact_type_street'];
				$data['fact_name_street'] = $row['fact_name_street'];
			}
			if (empty($row['fact_number_house'])) {
				$error[]='Укажите Номер дома для фактического адреса';
				$data['fact_number_house'] = '';
			} else {
				$data['fact_number_house'] = $row['fact_number_house'];
			}
				$data['fact_number_housing'] = $row['fact_number_housing'];
				$data['fact_number_structure'] = $row['fact_number_structure'];
				$data['fact_number_office'] = $row['fact_number_office'];
		}
		
		if (empty($row['director_phone'])) {
			$error[]='Укажите Телефон руководителя организации';
			$data['director_phone'] = '';
		} else {
			$data['director_phone'] = $row['director_phone'];
		}
		if (empty($row['accountant_phone'])) {
			$error[]='Укажите Телефон гл. бухгалтера';
			$data['accountant_phone'] = '';
		} else {
			$data['accountant_phone'] = $row['accountant_phone'];
		}
		if (empty($row['perfomer_phone'])) {
			$error[]='Укажите Телефон для связи с участником';
			$data['perfomer_phone'] = '';
		} else {
			$data['perfomer_phone'] = $row['perfomer_phone'];
		}
		if (empty($row['e_mail']) || preg_match("#^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,6}$#i", $row['e_mail'])==0) {
			$error[]='поле Email не заполнено или заполнено не корректно';
			$data['e_mail'] = '';
		} else {
			$data['e_mail'] = $row['e_mail'];
		}
		
        return array('error'=>$error, 'data'=>$data) ;
    }

	function prepareInfofizData($row) { //валидация формы для организации
		$data = array();
		$error = array();
		if (empty($row['last_name'])) {
			$error[]='Укажите Фамилию участника в именительном падеже';
			$data['last_name'] = '';
		} else {
			$data['last_name'] = $row['last_name'];
		}
		if (empty($row['first_name'])) {
			$error[]='Укажите Имя участника в именительном падеже';
			$data['first_name'] = '';
		} else {
			$data['first_name'] = $row['first_name'];
		}
		if (empty($row['middle_name'])) {
			$error[]='Укажите Отчество участника в именительном падеже';
			$data['middle_name'] = '';
		} else {
			$data['middle_name'] = $row['middle_name'];
		}
		if (!in_array($row['org_form'], array('физическое лицо','ИП','ИЧП','ПБОЮЛ'))) {
			$error[]='Укажите статус, к которому Вы относитесь';
			$data['org_form'] = '';
		} else {
			$data['org_form'] = $row['org_form'];
		}
		if (empty($row['INN'])) {
			$error[]='Укажите ИНН';
			$data['INN'] = '';
		} else {
			$data['INN'] = $row['INN'];
		}
		if (empty($row['pasport_number'])) {
			$error[]='Укажите Номер паспорта';
			$data['pasport_number'] = '';
		} else {
			$data['pasport_number'] = $row['pasport_number'];
		}
		if (empty($row['pasport_ser'])) {
			$error[]='Укажите Серию паспорта';
			$data['pasport_ser'] = '';
		} else {
			$data['pasport_ser'] = $row['pasport_ser'];
		}
		if (empty($row['pasport_date'])) {
			$error[]='Укажите Дату выдачи паспорта';
			$data['pasport_date'] = '';
		} else {
			if (preg_match("#[0-9.]{3}[0-9.]{3}[0-9.]{5}#is", $row['pasport_date'])) {
				$pieces = explode(".", $row['pasport_date']);
				$row['pasport_date'] = $pieces[2]."-".$pieces[1]."-".$pieces[0];
				$data['pasport_date'] = $row['pasport_date'];
			} else {
				$data['pasport_date'] = $row['pasport_date'];
			}
		}
		if (empty($row['pasport_issued_by'])) {
			$error[]='Укажите Кем выдан паспорт';
			$data['pasport_issued_by'] = '';
		} else {
			$data['pasport_issued_by'] = $row['pasport_issued_by'];
		}
		if (empty($row['compartment_code'])) {
			$error[]='Укажите Код подразделения';
			$data['compartment_code'] = '';
		} else {
			$data['compartment_code'] = $row['compartment_code'];
		}
/*адреса*/
		if (empty($row['legal_type_settlement'])) {
			$error[]='Укажите Тип населенного пункта по прописке';
			$data['legal_type_settlement'] = '';
		} else {
			$data['legal_type_settlement'] = $row['legal_type_settlement'];
		}
		if (empty($row['legal_name_settlement'])) {
			$error[]='Укажите Название населенного пункта по прописке';
			$data['legal_name_settlement'] = '';
		} else {
			$data['legal_name_settlement'] = $row['legal_name_settlement'];
		}
		if (empty($row['legal_post_index'])) {
			$error[]='Укажите Почтовый индекс по прописке';
			$data['legal_post_index'] = '';
		} else {
			$data['legal_post_index'] = $row['legal_post_index'];
		}
		if (empty($row['legal_type_street']) || empty($row['legal_name_street'])) {
			$error[]='Укажите улицу, бульвар, проспект или др. по прописке';
			$data['legal_type_street'] = '';
			$data['legal_name_street'] = '';
		} else {
			$data['legal_type_street'] = $row['legal_type_street'];
			$data['legal_name_street'] = $row['legal_name_street'];
		}
		if (empty($row['legal_number_house'])) {
			$error[]='Укажите Номер дома по прописке';
			$data['legal_number_house'] = '';
		} else {
			$data['legal_number_house'] = $row['legal_number_house'];
		}
			$data['legal_number_housing'] = $row['legal_number_housing'];
			$data['legal_number_structure'] = $row['legal_number_structure'];
			$data['legal_number_office'] = $row['legal_number_office'];
			$data['checkfactadress'] = $row['checkfactadress'];
		if ($row['checkfactadress'] == 'yes') { // если адреса совпадают
			$data['fact_type_settlement'] = $row['legal_type_settlement'];
			$data['fact_name_settlement'] = $row['legal_name_settlement'];	
			$data['fact_post_index'] = $row['legal_post_index'];
			$data['fact_type_street'] = $row['legal_type_street'];
			$data['fact_name_street'] = $row['legal_name_street'];
			$data['fact_number_house'] = $row['legal_number_house'];
			$data['fact_number_housing'] = $row['legal_number_housing'];
			$data['fact_number_structure'] = $row['legal_number_structure'];
			$data['fact_number_office'] = $row['legal_number_office'];		
		} else {
			if (empty($row['fact_type_settlement'])) {
				$error[]='Укажите Тип населенного пункта для фактического адреса';
				$data['fact_type_settlement'] = '';
			} else {
				$data['fact_type_settlement'] = $row['fact_type_settlement'];
			}
			if (empty($row['fact_name_settlement'])) {
				$error[]='Укажите Название населенного пункта для фактического адреса';
				$data['fact_name_settlement'] = '';
			} else {
				$data['fact_name_settlement'] = $row['fact_name_settlement'];
			}
			if (empty($row['fact_post_index'])) {
				$error[]='Укажите Почтовый индекс для фактического адреса';
				$data['fact_post_index'] = '';
			} else {
				$data['fact_post_index'] = $row['fact_post_index'];
			}
			if (empty($row['fact_type_street']) || empty($row['fact_name_street'])) {
				$error[]='Укажите улицу, бульвар, проспект или др. фактического адреса';
				$data['fact_type_street'] = '';
				$data['fact_name_street'] = '';
			} else {
				$data['fact_type_street'] = $row['fact_type_street'];
				$data['fact_name_street'] = $row['fact_name_street'];
			}
			if (empty($row['fact_number_house'])) {
				$error[]='Укажите Номер дома для фактического адреса';
				$data['fact_number_house'] = '';
			} else {
				$data['fact_number_house'] = $row['fact_number_house'];
			}
				$data['fact_number_housing'] = $row['fact_number_housing'];
				$data['fact_number_structure'] = $row['fact_number_structure'];
				$data['fact_number_office'] = $row['fact_number_office'];		
		}
/*конец адресам*/
		if (empty($row['licence_num']) && $row['licence_num_check'] != 'no'){
			$error[]='Необходимо указать Номер свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя';
			$data['licence_num'] = '';
		}elseif (!empty($row['licence_num']) && $row['licence_num_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрен ли Номер свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя';
			$data['licence_num'] = '';
        }elseif(!empty($row['licence_num'])) {
			$data['licence_num'] = $row['licence_num'];
		}elseif ($row['licence_num_check'] == 'no') {
			$data['licence_num'] = $row['licence_num_check'];
		}
		if (empty($row['licence_ser']) && $row['licence_ser_check'] != 'no'){
			$error[]='Необходимо указать Серию свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя';
			$data['licence_ser'] = '';
		}elseif (!empty($row['licence_ser']) && $row['licence_ser_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрена ли Серия свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя';
			$data['licence_ser'] = '';
        }elseif(!empty($row['licence_ser'])) {
			$data['licence_ser'] = $row['licence_ser'];
		}elseif ($row['licence_ser_check'] == 'no') {
			$data['licence_ser'] = $row['licence_ser_check'];
		}
		if (empty($row['licence_date']) && $row['licence_date_check'] != '0000-00-00'){
			$error[]='Необходимо указать Дату выдачи свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя';
			$data['licence_date'] = '';
		}elseif (!empty($row['licence_date']) && $row['licence_date_check'] == '0000-00-00'){
            $error[]='Необходимо уточнить Дату выдачи свидетельства о государственной регистрации физического лица в качестве индивидуального предпринимателя';
			$data['licence_date'] = '';
        }elseif(!empty($row['licence_date'])) {
			if (preg_match("#[0-9.]{3}[0-9.]{3}[0-9.]{5}#is", $row['licence_date'])) {
				$pieces = explode(".", $row['licence_date']);
				$row['licence_date'] = $pieces[2]."-".$pieces[1]."-".$pieces[0];
				$row['licence_date'] = $row['licence_date'];
			} else {
				$data['licence_date'] = $row['licence_date'];
			}
		}elseif ($row['licence_date_check'] == '0000-00-00') {
			$data['licence_date'] = $row['licence_date_check'];
		}
		if (empty($row['EGRIP_num']) && $row['EGRIP_num_check'] != 'no'){
			$error[]='Необходимо указать Номер свидетельства о внесении в ЕГРИП';
			$data['EGRIP_num'] = '';
		}elseif (!empty($row['EGRIP_num']) && $row['EGRIP_num_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрен ли Номер свидетельства о внесении в ЕГРИП';
			$data['EGRIP_num'] = '';
        }elseif(!empty($row['EGRIP_num'])) {
			$data['EGRIP_num'] = $row['EGRIP_num'];
		}elseif ($row['EGRIP_num_check'] == 'no') {
			$data['EGRIP_num'] = $row['EGRIP_num_check'];
		}
		if (empty($row['EGRIP_ser']) && $row['EGRIP_ser_check'] != 'no'){
			$error[]='Необходимо указать Серию свидетельства о внесении в ЕГРИП';
			$data['EGRIP_ser'] = '';
		}elseif (!empty($row['EGRIP_ser']) && $row['EGRIP_ser_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрена ли Серия свидетельства о внесении в ЕГРИП';
			$data['EGRIP_ser'] = '';
        }elseif(!empty($row['EGRIP_ser'])) {
			$data['EGRIP_ser'] = $row['EGRIP_ser'];
		}elseif ($row['EGRIP_ser_check'] == 'no') {
			$data['EGRIP_ser'] = $row['EGRIP_ser_check'];
		}		
		if (empty($row['EGRIP_date']) && $row['EGRIP_date_check'] != '0000-00-00'){
			$error[]='Необходимо указать Дату регистрации в регистрирующем органе';
			$data['EGRIP_date'] = '';
		}elseif (!empty($row['EGRIP_date']) && $row['EGRIP_date_check'] == '0000-00-00'){
            $error[]='Необходимо уточнить Дату регистрации в регистрирующем органе';
			$data['EGRIP_date'] = '';
        }elseif(!empty($row['EGRIP_date'])) {
			if (preg_match("#[0-9.]{3}[0-9.]{3}[0-9.]{5}#is", $row['EGRIP_date'])) {
				$pieces = explode(".", $row['EGRIP_date']);
				$row['EGRIP_date'] = $pieces[2]."-".$pieces[1]."-".$pieces[0];
				$row['EGRIP_date'] = $row['EGRIP_date'];
			} else {
				$data['EGRIP_date'] = $row['EGRIP_date'];
			}
		}elseif ($row['EGRIP_date_check'] == '0000-00-00') {
			$data['EGRIP_date'] = $row['EGRIP_date_check'];
		}		
		if (empty($row['registrator']) && $row['registrator_check'] != 'no'){
			$error[]='Необходимо указать Наименование регистрирующего органа';
			$data['registrator'] = '';
		}elseif (!empty($row['registrator']) && $row['registrator_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрено ли Наименование регистрирующего органа';
			$data['registrator'] = '';
        }elseif(!empty($row['registrator'])) {
			$data['registrator'] = $row['registrator'];
		}elseif ($row['registrator_check'] == 'no') {
			$data['registrator'] = $row['registrator_check'];
		}
		if (empty($row['registr_num']) && $row['registr_num_check'] != 'no'){
			$error[]='Необходимо указать Государственный регистрационный номер записи о государственной регистрации';
			$data['registr_num'] = '';
		}elseif (!empty($row['registr_num']) && $row['registr_num_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрен ли Государственный регистрационный номер записи о государственной регистрации';
			$data['registr_num'] = '';
        }elseif(!empty($row['registr_num'])) {
			$data['registr_num'] = $row['registr_num'];
		}elseif ($row['registr_num_check'] == 'no') {
			$data['registr_num'] = $row['registr_num_check'];
		}		
		if (empty($row['registr_date']) && $row['registr_date_check'] != '0000-00-00'){
			$error[]='Необходимо указать внесения записи';
			$data['registr_date'] = '';
		}elseif (!empty($row['registr_date']) && $row['registr_date_check'] == '0000-00-00'){
            $error[]='Необходимо уточнить Дату внесения записи';
			$data['registr_date'] = '';
        }elseif(!empty($row['registr_date'])) {
			if (preg_match("#[0-9.]{3}[0-9.]{3}[0-9.]{5}#is", $row['registr_date'])) {
				$pieces = explode(".", $row['registr_date']);
				$row['registr_date'] = $pieces[2]."-".$pieces[1]."-".$pieces[0];
				$row['registr_date'] = $row['registr_date'];
			} else {
				$data['registr_date'] = $row['registr_date'];
			}
		}elseif ($row['registr_date_check'] == '0000-00-00') {
			$data['registr_date'] = $row['registr_date_check'];
		}
		if (empty($row['KPP']) && $row['KPP_check'] != 'no'){
			$error[]='Необходимо указать КПП';
			$data['KPP'] = '';
		}elseif (!empty($row['KPP']) && $row['KPP_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрен ли КПП';
			$data['KPP'] = '';
        }elseif(!empty($row['KPP'])) {
			$data['KPP'] = $row['KPP'];
		}elseif ($row['KPP_check'] == 'no') {
			$data['KPP'] = $row['KPP_check'];
		}
		if (empty($row['OGRN']) && $row['OGRN_check'] != 'no'){
			$error[]='Необходимо указать ОГРН';
			$data['OGRN'] = '';
		}elseif (!empty($row['OGRN']) && $row['OGRN_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрен ли ОГРН';
			$data['OGRN'] = '';
        }elseif(!empty($row['OGRN'])) {
			$data['OGRN'] = $row['OGRN'];
		}elseif ($row['OGRN_check'] == 'no') {
			$data['OGRN'] = $row['OGRN_check'];
		}
		if (empty($row['OGRN_attribution']) && $row['OGRN_attribution_check'] != '0000-00-00'){
			$error[]='Необходимо указать Дату присвоения ОГРН';
			$data['OGRN_attribution'] = '';
		}elseif (!empty($row['OGRN_attribution']) && $row['OGRN_attribution_check'] == '0000-00-00'){
            $error[]='Необходимо уточнить Дату присвоения ОГРН';
			$data['OGRN_attribution'] = '';
        }elseif(!empty($row['OGRN_attribution'])) {
			if (preg_match("#[0-9.]{3}[0-9.]{3}[0-9.]{5}#is", $row['OGRN_attribution'])) {
				$pieces = explode(".", $row['OGRN_attribution']);
				$row['OGRN_attribution'] = $pieces[2]."-".$pieces[1]."-".$pieces[0];
				$row['OGRN_attribution'] = $row['OGRN_attribution'];
			} else {
				$data['OGRN_attribution'] = $row['OGRN_attribution'];
			}
		}elseif ($row['OGRN_attribution_check'] == '0000-00-00') {
			$data['OGRN_attribution'] = $row['OGRN_attribution_check'];
		}
		if (empty($row['bank_ls']) && $row['bank_ls_check'] != 'no'){
			$error[]='Необходимо указать Л/С';
			$data['bank_ls'] = '';
		}elseif (!empty($row['bank_ls']) && $row['bank_ls_check'] == 'no'){
            $error[]='Необходимо уточнить предусмотрено ли Л/С';
			$data['bank_ls'] = '';
        }elseif(!empty($row['bank_ls'])) {
			$data['bank_ls'] = $row['bank_ls'];
		}elseif ($row['bank_ls_check'] == 'no') {
			$data['bank_ls'] = $row['bank_ls_check'];
		}
		if (empty($row['bank_bik'])) {
			$error[]='Укажите БИК';
			$data['bank_bik'] = '';
		} else {
			$data['bank_bik'] = $row['bank_bik'];
		}
		if (empty($row['bank_ras'])) {
			$error[]='Укажите Расчетный счет';
			$data['bank_ras'] = '';
		} else {
			$data['bank_ras'] = $row['bank_ras'];
		}
		if (empty($row['bank_cor'])){
			$error[]='Необходимо указать Корреспондентский счет';
			$data['bank_cor'] = '';
		}else {
			$data['bank_cor'] = $row['bank_cor'];
		}
		if (empty($row['bank_receiver'])){
			$error[]='Необходимо указать Наименование банка получателя';
			$data['bank_receiver'] = '';
		}else {
			$data['bank_receiver'] = $row['bank_receiver'];
		}
		if (empty($row['bank_INN'])){
			$error[]='Необходимо указать ИНН банка получателя';
			$data['bank_INN'] = '';
		}else {
			$data['bank_INN'] = $row['bank_INN'];
		}	
		if (empty($row['bank_KPP'])){
			$error[]='Необходимо указать КПП банка получателя';
			$data['bank_KPP'] = '';
		}else {
			$data['bank_KPP'] = $row['bank_KPP'];
		}
		if (empty($row['bank_type_settlement'])) {
			$error[]='Укажите Тип населенного пункта, в котором расположен банк';
			$data['bank_type_settlement'] = '';
		} else {
			$data['bank_type_settlement'] = $row['bank_type_settlement'];
		}
		if (empty($row['bank_name_settlement'])) {
			$error[]='Укажите Название населенного пункта, в котором расположен банк';
			$data['bank_name_settlement'] = '';
		} else {
			$data['bank_name_settlement'] = $row['bank_name_settlement'];
		}
		if (empty($row['bank_post_index'])) {
			$error[]='Укажите Почтовый индекс по месту расположения банка';
			$data['bank_post_index'] = '';
		} else {
			$data['bank_post_index'] = $row['bank_post_index'];
		}
		if (empty($row['bank_type_street']) || empty($row['bank_name_street'])) {
			$error[]='Укажите улицу, бульвар, проспект или др., где расположен банк';
			$data['bank_type_street'] = '';
			$data['bank_name_street'] = '';
		} else {
			$data['bank_type_street'] = $row['bank_type_street'];
			$data['bank_name_street'] = $row['bank_name_street'];
		}
		if (empty($row['bank_number_house'])) {
			$error[]='Укажите Номер дома, где расположен банк';
			$data['bank_number_house'] = '';
		} else {
			$data['bank_number_house'] = $row['bank_number_house'];
		}
			$data['bank_number_housing'] = $row['bank_number_housing'];
			$data['bank_number_structure'] = $row['bank_number_structure'];
			$data['bank_number_office'] = $row['bank_number_office'];

        return array('error'=>$error, 'data'=>$data) ;
    }
    function prepareTZData($row,$bid_id) { //валидация формы для TZ и внесение атрибутов работ в БД
		$data = array();
		$error = array();
		if (empty($row['work_topic'])) {
			$error[]='Укажите наименование предлагаемой работы';
			$data['work_topic'] = '';
		} else {
			$data['work_topic'] = $row['work_topic'];
		}

        // place for time & address....
		if (empty($row['place_name'])) {
			$error[]='Укажите тип населенного пункта';
			$data['place_name'] = '';
		} else {
			$data['place_name'] = $row['place_name'];
		}
		if (empty($row['place_type_id'])) {
			$error[]='Укажите название выбранного населенного пункта';
			$data['place_type_id'] = '';
		} else {
			$data['place_type_id'] = $row['place_type_id'];
		}
		if (empty($row['place_district_id'])) {
			$error[]='Укажите  регион';
			$data['place_district_id'] = '';
		} else {
			$data['place_district_id'] = $row['place_district_id'];
		}
		if (empty($row['place_okrug_id'])) {
			$error[]='Укажите федеральный округ';
			$data['place_okrug_id'] = '';
		} else {
			$data['place_okrug_id'] = $row['place_okrug_id'];
		}

        // work attributes
		if (empty($row['workpurpose'])) {
			$error[]='Укажите цели выполнения работ';
			$_TPL['WORKPURPOSE'] = array();
		} else {
    		$sql=sql_placeholder('delete from ?#FK_WORK_PURPOSE where bid_id=?', $bid_id);
    		$this->db->query($sql);
            $er_workpurpose = false;
            foreach ($row['workpurpose'] as $workpurpose)
            {
                if (empty($workpurpose)) {$er_workpurpose = true;}
                $_TPL['WORKPURPOSE'] = array("title"=>$workpurpose, "bid_id"=>$bid_id);
        		$id=$this->db->addrow(FK_WORK_PURPOSE, $_TPL['WORKPURPOSE']);
            }
            if ($er_workpurpose) {$error[]='Указаны не все цели выполнения работ';}
		}
		if (empty($row['workrequirement'])) {
			$error[]='Укажите требования к выполнению работ';
			$_TPL['WORKREQUIREMENT'] = array();
		} else {
    		$sql=sql_placeholder('delete from ?#FK_WORK_REQUIREMENT where bid_id=?', $bid_id);
    		$this->db->query($sql);
            $er_workrequirement = false;
            foreach ($row['workrequirement'] as $workrequirement)
            {
                if (empty($workrequirement)) {$er_workrequirement = true;}
                $_TPL['WORKREQUIREMENT'] = array("work_requirement_title"=>$workrequirement, "bid_id"=>$bid_id);
        		$id=$this->db->addrow(FK_WORK_REQUIREMENT, $_TPL['WORKREQUIREMENT']);
            }
            if ($er_workrequirement) {$error[]='Указаны не все требования к выполнению работ';}
		}
		if (empty($row['workcondition'])) {
			$error[]='Укажите условия выполнения работ';
			$_TPL['WORKCONDITION'] = array();
		} else {
    		$sql=sql_placeholder('delete from ?#FK_WORK_CONDITION where bid_id=?', $bid_id);
    		$this->db->query($sql);
            $er_workcondition = false;
            foreach ($row['workcondition'] as $workcondition)
            {
                if (empty($workcondition)) {$er_workcondition = true;}
                $_TPL['WORKCONDITION'] = array("work_condition_title"=>$workcondition, "bid_id"=>$bid_id);
        		$id=$this->db->addrow(FK_WORK_CONDITION, $_TPL['WORKCONDITION']);
            }
            if ($er_workcondition) {$error[]='Указаны не все условия выполнения работ';}
		}
		if (empty($row['safetyrequirements'])) {
			$error[]='Укажите требования к качеству, безопасности выполнения работ';
			$_TPL['SAFETYREQUIREMENTS'] = array();
		} else {
    		$sql=sql_placeholder('delete from ?#FK_SAFETY_REQUIREMENTS where bid_id=?', $bid_id);
    		$this->db->query($sql);
            $er_safetyrequirements = false;
            foreach ($row['safetyrequirements'] as $safetyrequirements)
            {
                if (empty($safetyrequirements)) {$er_safetyrequirements = true;}
                $_TPL['SAFETYREQUIREMENTS'] = array("safety_requirements_title"=>$safetyrequirements, "bid_id"=>$bid_id);
        		$id=$this->db->addrow(FK_SAFETY_REQUIREMENTS, $_TPL['SAFETYREQUIREMENTS']);
            }
            if ($er_safetyrequirements) {$error[]='Указаны не все требования к качеству, безопасности выполнения работ';}
		}
        return array('error'=>$error, 'data'=>$data) ;
    }

	function upload_pdf($fname, $filename, $dir) {
		if (!empty($_FILES[$fname]) && is_uploaded_file($_FILES[$fname]["tmp_name"])) {
			$type=$_FILES[$fname]['type'];
            //$allowed_types = array("application/excel","application/vnd.ms-excel","application/x-excel","application/x-msexcel");
			$allowed_types = array("application/pdf","application/x-pdf");
			/*.xls 	application/excel
			.xls 	application/vnd.ms-excel
			.xls 	application/x-excel
			.xls 	application/x-msexcel
			.xlsb	application/vnd.ms-excel.sheet.binary.macroEnabled.12
			.xlsm	application/vnd.ms-excel.sheet.macroEnabled.12
			.xlsx	application/vnd.openxmlformats-officedocument.spreadsheetml.sheet*/

			$size=filesize($_FILES[$fname]['tmp_name']);

			if($size>5307000) return -1;

			if ((in_array($type,$allowed_types)))
				{
					$res = move_uploaded_file($_FILES[$fname]["tmp_name"], $dir.$filename );
					if ($res)  return $filename;
					else
					{
					return 0;
					}
				}
			else {
				return 0;
			}
		} else {
			return 0;
		}
	}

}

?>