<?
/*
	if (USER_GROUP == 5) {*/
		if ($_POST) {
		
		$row = $_POST;
		
		/*chosen properties*/
		$possiblePropsNames = array('tender_count'=> array('title'=>'Количество конкурсов (план)', 'periodical'=>false), //
									'tend_num_podacha'=> array('title'=>'Количество конкурсов на этапе подачи заявок', 'periodical'=>false),
									'tend_num_rassmotr'=> array('title'=>'Количество конкурсов на этапе рассмотрения', 'periodical'=>false),
									'tend_num_commit'=> array('title'=>'Проведено конкурсов', 'periodical'=>false), //tender_commited
									'tender_commited_money'=> array('title'=>'Сумма проведенных конкурсов, тыс. руб.', 'periodical'=>true),
									'winners_money'=> array('title'=>'Сумма заявок победителей, тыс. руб.', 'periodical'=>true),
									'economy'=> array('title'=>'Экономия средств по проведенным конкурсам в '.date('Y').' году, тыс. руб.', 'periodical'=>false)
		);
		$reportPropsNames = array();

		foreach($possiblePropsNames as $prop_name=>$prop_title)
		{
			if (!empty($row[$prop_name]))
			{
				$reportPropsNames[] = $prop_name;
			}
		}
		
		/* Выводить ли детализированный отчёт по мероприятиям */
		$detailByMeasures = isset($row['detail_by_measures']) ? true : false;
	
		if ($row['pp'] != 0) {
			$row['pp'] = intval($row['pp']);
			$sp_condition = 'and m.subprogram_id = '.$row['pp'];
		}
		
		$sql1=sql_placeholder('
			SELECT *
			FROM
			(
				SELECT mp.*, sp.id sp_id, sp.title spt, m.title mt
				FROM ?#FK_MEASURE_PLAN mp, ?#FK_MEASURE m, ?#FK_SUBPROGRAM sp
				WHERE mp.measure_id = m.id
				AND m.subprogram_id = sp.id
				' . $sp_condition . '
				AND year= YEAR(NOW())
			) tab1

			LEFT JOIN
			(
				SELECT count( t.title )  tend_num_podacha, t.measure_id
				FROM ?#FK_TENDER t, ?#FK_MEASURE m
				WHERE m.id=t.measure_id
				AND t.notice_date IS NOT NULL
				AND t.notice_date <= NOW()
				AND t.envelope_opening_date >= NOW()
				GROUP BY t.measure_id
			) tab2
			ON (tab1.measure_id = tab2.measure_id)

			LEFT JOIN
			(
				SELECT count( t.title ) tend_num_rassmotr , t.measure_id
				FROM ?#FK_TENDER t,?#FK_MEASURE m
				WHERE m.id=t.measure_id
				AND t.envelope_opening_date IS NOT NULL
				AND t.envelope_opening_date <= NOW()
				AND t.estimation_date >= NOW()
				GROUP BY t.measure_id
			) tab3
			ON (tab3.measure_id = tab2.measure_id)

			LEFT JOIN
			(
				SELECT count( t.title ) tend_num_commit, t.measure_id
				FROM ?#FK_TENDER t,?#FK_MEASURE m
				WHERE m.id=t.measure_id
				AND t.estimation_date >= CONCAT(YEAR(NOW()),"-01-01")
				AND t.estimation_date < DATE_SUB(CURDATE(),INTERVAL 1 DAY)
				GROUP BY t.measure_id
			) tab4
			ON (tab4.measure_id = tab2.measure_id)

			LEFT JOIN
			(
				SELECT  SUM(sgk.price) winners_cnt, YEAR(sgk.finish_date) works_year, gk.measure_id
				FROM ?#FK_BIDGK bgk, ?#FK_LOT l, ?#FK_TENDER t, ?#FK_STEPGK sgk, ?#FK_GK gk
				WHERE sgk.GK_id = gk.id
				AND gk.bidGK_id = bgk.id
				AND bgk.winner = 1
				AND  bgk.lot_id = l.id
				AND l.tender_id= t.id
				AND t.estimation_date >= CONCAT(YEAR(NOW()),"-01-01")
				AND t.estimation_date < DATE_SUB(CURDATE(),INTERVAL 1 DAY)
				AND YEAR(sgk.finish_date) = YEAR(NOW())
				GROUP by gk.measure_id
			) tab6
			ON tab6.measure_id = tab1.measure_id
		');

		$sql2=sql_placeholder('
			SELECT *, (tab1.tender_commited_money - tab2.winners_money) economy
			FROM
			(
				SELECT SUM(lp.price) tender_commited_money, lp.year, t.measure_id, sp.id sp_id
				FROM lot_price lp, lot l, tender t, ?#FK_SUBPROGRAM sp, ?#FK_MEASURE m
				WHERE m.id=t.measure_id
				AND lp.lot_id = l.id	
				AND l.tender_id = t.id
				AND t.estimation_date >= CONCAT(YEAR(NOW()),"-01-01")
				AND t.estimation_date < DATE_SUB(CURDATE(),INTERVAL 1 DAY)
				AND ( lp.year = YEAR(NOW()) OR (lp.year = YEAR(NOW())+1) OR (lp.year = YEAR(NOW())+2) )
				AND lp.year <="2016"
				AND m.subprogram_id = sp.id
				' . $sp_condition . '
				GROUP BY t.measure_id, lp.year
			) tab1
			
			LEFT JOIN
			(
				SELECT  SUM(sgk.price) winners_money, YEAR(sgk.finish_date) works_year, gk.measure_id
				FROM bidGK bgk, lot l, tender t, stepGK sgk, GK gk
				WHERE sgk.GK_id = gk.id
				AND gk.bidGK_id = bgk.id
				AND bgk.winner = 1
				AND  bgk.lot_id = l.id
				AND l.tender_id= t.id
				AND t.estimation_date >= CONCAT(YEAR(NOW()),"-01-01")
				AND t.estimation_date < DATE_SUB(CURDATE(),INTERVAL 1 DAY)
				GROUP by gk.measure_id,	works_year 
			) tab2
			ON tab2.works_year = tab1.year
			AND tab1.measure_id = tab2.measure_id
		');
			
/*print_r($sql1);		
echo "<br>-----<br>";
print_r($sql2);*/

    $work_steps1 = $this->db->_array_data($sql1);
    $work_steps2 = $this->db->_array_data($sql2);

    $data = array();
	$m_titles = array();
	$sp_titles = array();
			    
    /* Группировка элементов по значению ключа-индикатора */
    function group_by($items, $indicator_name) {
        $indicator = null;
        $groups = array();
    	$group = array();
        $first_item_has_been = true;
            
        foreach ($items as $item) {
            if ($first_item_has_been) {
        	    $first_item_has_been = false;
				$indicator = $item[$indicator_name];                    
            }
               
            /*if first item*/
            if ($item[$indicator_name] != $indicator) {
            	$first_item_has_been = true;
                $groups[$indicator] = $group;
            	$indicator = $item[$indicator_name];
                $group = array();
            }
                
            $group[] = $item;
        }
            
        if (sizeof($group) > 0) {
            $groups[$indicator] = $group;
        }
            
        return $groups;
    }
    
	/*Непериодические показатели*/
    if (!empty($work_steps1))
    {
        /* 
         * Значения показателей собранные для текущего мероприятия, в формате 
         * prop_name => array(prop_values) 
         */
        $prop_values = array();
        
        /* Группируем годичные записи о мероприятиях по подпрограммам */
        $subprograms = group_by($work_steps1, 'sp_id');
        $subprogram_totals = array();
        $subprogram_data = array();
        
        foreach ($subprograms as $sp_id => $subprogram_measures)
        {
            $sp_title = $record['spt'];
            if (!isset($sp_titles[$sp_id])) {$sp_titles[$sp_id] = $sp_title;}      
        	
        	/* Суммарные знания показателей по годам для всей подпрограммы */
            $subprogram_totals[$sp_id] = array();
            /* Временный массив для хранения данных мероприятий текущей подпрограммы */
            $subprogram_data[$sp_id] = array();
            /* Группируем годичные записи по мероприятиям */

            $measures = group_by($subprogram_measures, 'measure_id');

            foreach ($measures as $m_id => $measure_records) 
            {
            	$prop_values[$m_id] = array();
                $record = $measure_records[0];

                $m_title = $record['mt'];
                if (!isset($m_titles[$m_id])) {$m_titles[$m_id] = $m_title;}      
                
                /* Для всех выбранных непериодических показателей запоминаем значения */
               	foreach($reportPropsNames as $prop_name) 
               	{
               		if (!$possiblePropsNames[$prop_name]["periodical"])
					{
						$value = $record[$prop_name];
                       
	                    $prop_values[$m_id][$prop_name] = $value;
	
	                    /* Обновляем суммарное значение у подпрограммы */
	                    if (!isset($subprogram_totals[$sp_id][$prop_name])) {
	                        $subprogram_totals[$sp_id][$prop_name] = $value;
	                    } else {
	                        $subprogram_totals[$sp_id][$prop_name] += $value;
	                    }
					}
                }
            }   
        }
    }
	/*ео Непериодические показатели*/
print_r($prop_values);
echo "<br>%%<br>";
print_r($subprogram_totals);
echo "<br>%%<br>";
    
	/*Периодические показатели*/
    if (!empty($work_steps2))
    {
    	/* Группируем годичные записи о мероприятиях по подпрограммам */
        $subprograms = group_by($work_steps2, 'sp_id');
        $subprogram_data = array();
        
        foreach ($subprograms as $sp_id => $subprogram_measures)
        {
            $sp_title = $record['spt'];
            if (!isset($sp_titles[$sp_id])) {$sp_titles[$sp_id] = $sp_title;}      
        	
        	/* Суммарные знания показателей по годам для всей подпрограммы */
	        if (!isset($subprogram_totals[$sp_id]))
	        {
        		$subprogram_totals[$sp_id] = array();
	        }
            /* Временный массив для хранения данных мероприятий текущей подпрограммы */
            $subprogram_data[$sp_id] = array();

            /* Группируем годичные записи по мероприятиям */
            $measures = group_by($subprogram_measures, 'measure_id');
            
            foreach ($measures as $m_id => $measure_years) 
            {
                /* 
                 * Значения показателей по году в формате:
                 * prop_name => array(year => array(prop_value))
                 */
            	if (!isset($prop_values[$m_id]))
                {
            		$prop_values[$m_id] = array();
                }

                /* Перебираем годичные записи */
                foreach ($measure_years as $record) {
                    $year = $record['year'];

                    $m_title = $record['mt'];
                    if (!isset($m_titles[$m_id])) {$m_titles[$m_id] = $m_title;}      

                    /* Для всех выбранных показателей запоминаем значения */
                    foreach($reportPropsNames as $prop_name) 
                    {
                    	if ($possiblePropsNames[$prop_name]["periodical"])
						{
							$value = $record[$prop_name];

						
	                        if (!isset($prop_values[$m_id][$prop_name])) {
	                            $prop_values[$m_id][$prop_name] = array();
	                        }
	                        
	                        /* Запоминаем значения для текущего года */
	                        $prop_values[$m_id][$prop_name][$year] = $value;
	                        if (!isset($subprogram_totals[$sp_id][$prop_name])) {
	                            $subprogram_totals[$sp_id][$prop_name] = array();
	                        }
	                        
	                        /* Обновляем суммарное значение у подпрограммы */
	                        $prop_total = & $subprogram_totals[$sp_id][$prop_name];
	                        if (!isset($prop_total[$year])) {
	                            $prop_total[$year] = $value;
	                        } else {
	                            $prop_total[$year] += $value;
	                        }
						}
					}
                }  
            }
        }
    }        
print_r($prop_values);
echo "<br>%%<br>";
print_r($subprogram_totals);
echo "<br>%%<br>";
    
	foreach ($subprogram_totals as $sp_id => $sp_totals_data)
	{
		foreach ($prop_values as $m_id=>$measure_data)
		{
	                /* Формируем список значений показателей мероприятия */
	                $content = array();
	                foreach ($reportPropsNames as $prop_name) {
	                    $content[] = array('propTitle' => $possiblePropsNames[$prop_name]["title"],
	                                       'values'    => $prop_values[$m_id][$prop_name]);
	                }
	                /* Добавляем элемент во временный список */
	                $subprogram_data[] = array('title'   => $m_id . ' ' . $m_titles[$m_id],
	                                           'type'    => 'measure', 
	                                           'content' => $content);
		}
	    /* Формируем список значений показателей подпрограммы */
	    $content = array();
	    foreach ($reportPropsNames as $prop_name) {
	    	$content[] = array('propTitle' => $possiblePropsNames[$prop_name]["title"],
							   'values'    => $subprogram_totals[$sp_id][$prop_name]);
		}
	            
		/* Добавляем элемент во итоговый список */
		$data[] = array('title'   => $sp_titles[$sp_id],
	    				'type'    => 'subprogram',
						'content' => $content);
	}

	/*
	* И также переносим элементы из временного списка, чтобы записи о 
	* меропритиях оказались после записей о подпрограммах
	*/      
	if ($detailByMeasures) {
		$data = array_merge($data, $subprogram_data);
	}

               
      echo '<!--' . print_r($data, true) . '-->';
        $TPL['DATA'] = json_encode($data);
        $TPL['STATTITLE'] = 'Общая статистика по программе за '.$startYear.'-'.$finishYear.' годы';
	    include TPL_CMS_STATS."course-result.php";
    } // end Post
   else {
    		$startYear = date('Y');
    		$start2Year = date('Y');
    		$endYear = 2016;
    		$TPL['SUBPROGRAM'] = ManagerForms::listSubprogram();
    		include TPL_CMS_STATS."course.php";
   }    
	/* 
    } else {
    		include TPL_CMS_STATS."no-rights.php";
    }
    */
?>