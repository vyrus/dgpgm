<?

//	if (USER_GROUP == 5) {
    if ($_POST) {
    /*chosen subprogtam*/
    $subProgram = $_POST['pp'];
    if (empty($subProgram)) {$subProgram = "all";}

    /*chosen dates*/
	$startYear = $_POST['start_year'];
	$finishYear = $_POST['end_year'];

    /*chosen properties*/
    $possiblePropsNames = array('tender_count'=>'Количество конкурсов (план)',
                                'gk_count'=>'Количество заключенных контрактов (план)',
                                'financing'=>'Финансирование (план), тыс.руб.',
                                'financed'=>'Профинансировано, тыс. руб.',
                                'gk_commited'=>'Заключено госконтрактов',
                                'tender_commited'=>'Проведено конкурсов');
    $reportPropsNames = array();
    foreach($possiblePropsNames as $prop_name=>$prop_title)
    {
        if (!empty($_POST[$prop_name]))
        {
            $reportPropsNames[] = $prop_name;
        }
    }

    /* Выводить ли детализированный отчёт по мероприятиям */
    $detailByMeasures = isset($_POST['detail_by_measures']) ? true : false;
    
    if ($subProgram != 'all')
    {
        $sp_condition = 'and m.subprogram_id = '.$subProgram;
    }
    /*remake query because 1 sentence doesn't work*/
	$sql = sql_placeholder('
        SELECT *
        FROM (
            SELECT mp.measure_id, mp.year, (mp.financing DIV 1000) financing, 
                   mp.gk_count, mp.tender_count, sp.id sp_id, sp.title spt, 
                   m.title mt
            FROM ?#FK_MEASURE_PLAN mp, ?#FK_MEASURE m, ?#FK_SUBPROGRAM sp
            WHERE mp.measure_id = m.id AND
                  m.subprogram_id = sp.id
                  ' . $sp_condition . ' AND
                  mp.year BETWEEN ? AND ?
        ) tab1

        LEFT JOIN (
            SELECT (SUM(sum) DIV 1000) financed, count(gk.number) gk_commited, 
                   gk.measure_id
            FROM `payment_order` po, stepGK sgk, GK gk
            WHERE `status` <> "отменено" AND
                  po.stepGK_id = sgk.id AND
                  sgk.GK_id = gk.id
            GROUP BY gk.measure_id
        ) tab2 
        ON (tab1.measure_id = tab2.measure_id)

        LEFT JOIN (
            SELECT count(t.title) tender_commited, t.measure_id
            FROM tender t
            WHERE /* YEAR(t.estimation_date) = tab1.year AND */ 
                  t.estimation_date < DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            GROUP BY t.measure_id
        ) tab3
        ON (tab1.measure_id = tab3.measure_id)

        ORDER BY tab1.measure_id, tab1.year', $startYear, $finishYear);

    $work_steps = $this->db->_array_data($sql);
    if (!empty($work_steps))
    {
        $data = array();

        /* 
         * Значения показателей собранные для текущего мероприятия, в формате 
         * prop_name => array(prop_values) 
         */
        $prop_values = array();
        
        /* Группировка элементов по значению ключа-индикатора */
        function group_by($items, $indicator_name) {
            $indicator = null;
            $groups = array();
            $group = array();
            
            foreach ($items as $item) {
                if ($indicator == null) {
                    $indicator = $item[$indicator_name];
                }
                
                if ($item[$indicator_name] != $indicator) {
                    $indicator == null;
                    $groups[] = $group;
                    $group = array();
                }
                
                $group[] = $item;
            }
            
            if (sizeof($group) > 0) {
                $groups[] = $group;
            }
            
            return $groups;
        }
        
        /* Группируем годичные записи о мероприятиях по подпрограммам */
        $subprograms = group_by($work_steps, 'sp_id');
        
        foreach ($subprograms as $subprogram_measures)
        {
            /* Суммарные знания показателей по годам для всей подпрограммы */
            $subprogram_totals = array();
            /* Временный массив для хранения данных мероприятий текущей подпрограммы */
            $subprogram_data = array();
            /* Группируем годичные записи по мероприятиям */
            $measures = group_by($subprogram_measures, 'measure_id');
            
            foreach ($measures as $measure_years) {
                /* 
                 * Значения показателей по году в формате:
                 * prop_name => array(year => array(prop_value)) 
                 */
                $prop_values = array();
                
                /* Перебираем годичные записи */
                foreach ($measure_years as $record) {
                    $year = $record['year'];
                        
                    /* Для всех выбранных показателей запоминаем значения */
                    foreach($reportPropsNames as $prop_name) {
                        $value = $record[$prop_name];
                        
                        if (!isset($prop_values[$prop_name])) {
                            $prop_values[$prop_name] = array();
                        }
                        
                        /* Запоминаем значения для текущего года */
                        $prop_values[$prop_name][$year] = $value;
                        
                        if (!isset($subprogram_totals[$prop_name])) {
                            $subprogram_totals[$prop_name] = array();
                        }
                        
                        /* Обновляем суммарное значение у подпрограммы */
                        $prop_total = & $subprogram_totals[$prop_name];
                        if (!isset($prop_total[$year])) {
                            $prop_total[$year] = $value;
                        } else {
                            $prop_total[$year] += $value;
                        }
                    }
                }  
                
                /* Формируем список значений показателей мероприятия */
                $content = array();
                foreach ($reportPropsNames as $prop_name) {
                    $content[] = array('propTitle' => $possiblePropsNames[$prop_name],
                                       'values'    => $prop_values[$prop_name]);
                }
                
                /* Добавляем элемент во временный список */
                $record = reset($measure_years);
                $subprogram_data[] = array('title'   => $record['mt'],
                                           'type'    => 'measure', 
                                           'content' => $content);
            }
            
            /* Формируем список значений показателей подпрограммы */
            $content = array();
            foreach ($reportPropsNames as $prop_name) {
                $content[] = array('propTitle' => $possiblePropsNames[$prop_name],
                                   'values'    => $subprogram_totals[$prop_name]);
            }
            
            /* Добавляем элемент во итоговый список */
            $data[] = array('title'   => '+++ ' . $record['spt'],
                            'type'    => 'subprogram', 
                            'content' => $content);
            
            /*
             * И также переносим элементы из временного списка, чтобы записи о 
             * меропритиях оказались после записей о подпрограммах
             */      
            if ($detailByMeasures) {
                $data = array_merge($data, $subprogram_data);
            }
        }
        
        //echo '<!--' . print_r($data, true) . '-->';
        $TPL['DATA'] = json_encode($data);
        $TPL['STATTITLE'] = 'Общая статистика по программе за '.$startYear.'-'.$finishYear.' годы';
	    include TPL_CMS_STATS."total-result.php";
    }
    
    } else {
    		$startYear = date('Y');
    		$start2Year = date('Y');
    		$endYear = 2016;
    		$TPL['SUBPROGRAM'] = ManagerForms::listSubprogram();
    		include TPL_CMS_STATS."total.php";
    } // end Post
	/* 
    } else {
    		include TPL_CMS_STATS."no-rights.php";
    }
    */
?>