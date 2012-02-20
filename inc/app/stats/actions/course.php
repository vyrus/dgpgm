<?

	if (USER_GROUP == 5) {
		if ($_POST) {
		
		$row = $_POST;
		
		if ($row['pp'] != 0) {
			$row['pp'] = intval($row['pp']);
			$sp_condition = 'and m.subprogram_id = '.$row['pp'];
		}
		
		$sql=sql_placeholder('select * from
			(select mp.*, sp.title spt, m.title mt
			from ?#FK_MEASURE_PLAN mp, ?#FK_MEASURE m, ?#FK_SUBPROGRAM sp
			where mp.measure_id = m.id
			and m.subprogram_id = sp.id
			'.$sp_condition.'
			and year = YEAR(NOW())) tab1
			left join
			(SELECT count( t.title )  tend_num_podacha, t.measure_id , m.title
			FROM ?#FK_TENDER t, ?#FK_MEASURE m
			WHERE m.id=t.measure_id
			AND t.notice_date IS NOT NULL
			AND t.notice_date <= NOW()
			AND t.envelope_opening_date >= NOW()
			GROUP BY t.measure_id) tab2
			on (tab1.measure_id = tab2.measure_id)
			left join
			(SELECT count( t.title ) tend_num_rassmotr , t.measure_id , m.title
			FROM ?#FK_TENDER t, ?#FK_MEASURE m
			WHERE m.id=t.measure_id
			AND t.envelope_opening_date IS NOT NULL
			AND t.envelope_opening_date <= NOW()
			AND t.estimation_date >= NOW()
			GROUP BY t.measure_id) tab3
			on (tab3.measure_id = tab1.measure_id)
			left join
			(SELECT count( t.title ) tend_num_commit, t.measure_id , m.title
			FROM ?#FK_TENDER t, ?#FK_MEASURE m
			WHERE m.id=t.measure_id
			AND t.estimation_date >= CONCAT(YEAR(NOW()),"-01-01")
			AND t.estimation_date < DATE_SUB(CURDATE(),INTERVAL 1 DAY)
			GROUP BY t.measure_id) tab4
			on (tab4.measure_id = tab1.measure_id)

			');

/*
			Сумма конкурсов (план), тыс. руб.
			left join
			(SELECT sum_per_year as sum_per_year_first, mp.measure_id
			FROM ?#FK_TENDER_SUM_PLAN as tsp, ?#FK_MEASURE_PLAN as mp where tsp.financing_year = YEAR(NOW()) AND tsp.financing_year <= 2016 AND tsp.measure_plan_id = mp.id) tab5
			on (tab5.measure_id = tab1.measure_id)

*/
			
			$work_steps = $this->db->_array_data($sql);
			
		if (!empty($work_steps))
		{
        $cur_measure = 0;
        $data = array();
        $first_measure = true;
        $tender_count = array();
        $gk_count = array();
        $financing = array();
        $financed = array();
        $gk_commited = array();
        $tender_commited = array();

        foreach($work_steps as $measure_data)
        {
            if ($cur_measure['measure_id'] != $measure_data['measure_id'] && (!$first_measure))
            {
                /*end old measure array*/
                $named_tender_count = array("propTitle"=>"Количество конкурсов (план)","values"=>$tender_count);
                $named_gk_count = array("propTitle"=>"Количество конкурсов на этапе подачи заявок","values"=>$gk_count);
				$named_gk_commited = array("propTitle"=>"Количество конкурсов на этапе рассмотрения","values"=>$gk_commited);
				$named_tender_commited = array("propTitle"=>"Проведено конкурсов","values"=>$tender_commited);
                $named_financing = array("propTitle"=>"Общее финансирование на ".date('Y').", тыс.руб.","values"=>$financing);
                //$named_financed = array("propTitle"=>"Профинансировано, тыс. руб.","values"=>$financed);
                $data[$cur_measure['measure_id']]['title'] = $cur_measure['mt'];
                $data[$cur_measure['measure_id']]['content'] = array($named_tender_count,$named_gk_count,$named_gk_commited,$named_tender_commited,$named_financing);//,$named_financed

                /*new measure*/
                $cur_measure = $measure_data;
                $data[$cur_measure['measure_id']] = array('title'=>'', 'content'=>array());
                $tender_count = array();
                $gk_count = array();
                $financing = array();
            }
            if ($first_measure)
            {
                $first_measure = false;
                $cur_measure = $measure_data;
            }
            $tender_count[$measure_data['year']] = $measure_data['tender_count'];
            $gk_count[$measure_data['year']] = $measure_data['tend_num_podacha'];
			$gk_commited[$measure_data['year']] = $measure_data['tend_num_rassmotr'];
			$tender_commited[$measure_data['year']] = $measure_data['tend_num_commit'];
            $financing[$measure_data['year']] = $measure_data['financing']/1000;
           // $financed[$measure_data['year']] = $measure_data['financed'];
        }
        /*end old measure array*/
        $named_tender_count = array("propTitle"=>"Количество конкурсов (план)","values"=>$tender_count);
        $named_gk_count = array("propTitle"=>"Количество конкурсов на этапе подачи заявок","values"=>$gk_count);
		$named_gk_commited = array("propTitle"=>"Количество конкурсов на этапе рассмотрения","values"=>$gk_commited);
		$named_tender_commited = array("propTitle"=>"Проведено конкурсов","values"=>$tender_commited);
        $named_financing = array("propTitle"=>"Общее финансирование на ".date('Y').", тыс.руб.","values"=>$financing);
        //$named_financed = array("propTitle"=>"Профинансировано, тыс. руб.","values"=>$financed);
        $data[$cur_measure['measure_id']]['title'] = $cur_measure['mt'];
        $data[$cur_measure['measure_id']]['content'] = array($named_tender_count,$named_gk_count,$named_gk_commited,$named_tender_commited,$named_financing); //,$named_financed
    
		/*
		// Детализировать по мероприятиям if ($row['detail'] == 1)
		
		if ($row['number_calls_stage_application'] == 1) {
		// Количество конкурсов по мероприятию из таблицы Конкурс, у которых (через «и»):
		// 1. Дата извещения не нулевая и меньше или равна текущей.  notice_date дата извещения
		// 2. Дата вскрытия конвертов больше или равна текущей. envelope_opening_date дата вскрытия конвертов
		// select count(id) as cnt from tender where notice_date is not null and notice_date <= NOW() and envelope_opening_date >= NOW()
		}
		
		if ($row['number_calls_stage_consideration'] == 1) {
		// Количество конкурсов по мероприятию из таблицы Конкурс, у которых (через «и»):
		// 1. Дата вскрытия конвертов не нулевая и меньше или равна текущей.
		// 2. Дата оценки и сопоставления больше или равна текущей.
		// select count(id) as cnt from tender where notice_date is not null and notice_date <= NOW() and estimation_date >= NOW() ???????????
		}
		
		if ($row['count_contest'] == 1) {
		// Проведено конкурсов
		// Количество конкурсов по мероприятию из таблицы Конкурс, у которых дата оценки и сопоставления в диапазоне от 1 января тек. года до текущей даты – 1.
		// select count(id) as cnt from tender where estimation_date >= '2012-01-01 00:00:00' and estimation_date <= DATE_ADD(NOW(), INTERVAL -1 DAY);
		}
		
		if ($row['total_funding'] == 1) {
		// Общее финансирование на 20__ (примечание: текущий), тыс. руб.
		// План из таблицы План по мероприятию
		// поле financing округлить до тысяч
		}
		
		if ($row['amount_competition'] == 1) {
		// Сумма конкурсов (план), тыс. руб.
		// По годам: текущий, тек.+1, тек. +2 (но не больше 2016) из таблицы Сумма конкурсов план
		// select * from tender_sum_plan where financing_year >= YEAR(NOW()) and measure_plan_id = 2
		}
		
		if ($row['amount_winners_bids'] == 1) {
		// Сумма заявок победителей, тыс. руб
		// Сумма цен по аналогичным годам по заявкам со статусом «победитель» по проведенным в тек. году конкурсам (см. показатель «Проведено конкурсов»).
		// ?????????????????????
		}
		
		if ($row['savings'] == 1) {
		// Экономия средств в 20__ году, тыс. руб.
		// Разница между суммой конкурсов (план) на тек. год и суммой заявок победителей на тек. год.
		}
		*/
		
		$TPL['DATA'] = json_encode($data);
		if ($row['pp'] != 0) {
			$TPL['STATTITLE'] = 'Статистка по ходу проведения конкурсов по подпрограмме '.$work_steps[0]['spt'].' на '.date('d').' '.MonthsName(date('m')).' '.date('Y').' г. ';
		} else {
			$TPL['STATTITLE'] = 'Статистка по ходу проведения конкурсов на '.date('d').' '.MonthsName(date('m')).' '.date('Y').' г. ';
		}
		} // !empty
			include TPL_CMS_STATS."course-result.php";
		} else {
			$TPL['SUBPROGRAM'] = ManagerForms::listSubprogram();
			include TPL_CMS_STATS."course.php";
		}
	} else {
		include TPL_CMS_STATS."no-rights.php";
	}
	

?>