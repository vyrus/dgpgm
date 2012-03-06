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
									'economy'=> array('title'=>'Экономия средств по проведенным конкурсам в '.date('Y').' году, тыс. руб.', 'periodical'=>true)
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
            $subprogram_id = intval($row['pp']);
			$sp_condition = 'and m.subprogram_id = ' . $subprogram_id;
		}
		
		$sql1=sql_placeholder('
			SELECT *
			FROM
	        (
				SELECT sp.id sp_id, sp.title spt, 
	                   m.title mt, m.id measure_id
				FROM ?#FK_MEASURE m, ?#FK_SUBPROGRAM sp
				WHERE m.subprogram_id = sp.id
	                  ' . $sp_condition . '
			) tab0
			
			LEFT JOIN
			(
				SELECT mp.year, mp.financing, mp.gk_count, mp.tender_count, mp.measure_id me1_id 
				FROM ?#FK_MEASURE_PLAN mp
				WHERE mp.year= YEAR(NOW())
			) tab1
			ON (tab1.me1_id = tab0.measure_id)
			
			LEFT JOIN
			(
				SELECT count( t.title )  tend_num_podacha, t.measure_id me2_id
				FROM ?#FK_TENDER t
				WHERE t.notice_date IS NOT NULL
				AND t.notice_date <= NOW()
				AND t.envelope_opening_date >= NOW()
				GROUP BY me2_id
			) tab2
			ON (tab0.measure_id = tab2.me2_id)

			LEFT JOIN
			(
				SELECT count( t.title ) tend_num_rassmotr , t.measure_id me3_id
				FROM ?#FK_TENDER t
				WHERE t.envelope_opening_date IS NOT NULL
				AND t.envelope_opening_date <= NOW()
				AND t.estimation_date >= NOW()
				GROUP BY me3_id
			) tab3
			ON (tab3.me3_id = tab0.measure_id)

			LEFT JOIN
			(
				SELECT count( t.title ) tend_num_commit, t.measure_id me4_id
				FROM ?#FK_TENDER t
				WHERE t.estimation_date >= CONCAT(YEAR(NOW()),"-01-01")
				AND t.estimation_date < DATE_SUB(CURDATE(),INTERVAL 1 DAY)
				GROUP BY me4_id
			) tab4
			ON (tab4.me4_id = tab0.measure_id)

			LEFT JOIN
			(
				SELECT  SUM(sgk.price) winners_cnt, YEAR(sgk.finish_date) works_year, gk.measure_id me5_id
				FROM ?#FK_BIDGK bgk, ?#FK_LOT l, ?#FK_TENDER t, ?#FK_STEPGK sgk, ?#FK_GK gk
				WHERE sgk.GK_id = gk.id
				AND gk.bidGK_id = bgk.id
				AND bgk.winner = 1
				AND  bgk.lot_id = l.id
				AND l.tender_id= t.id
				AND t.estimation_date >= CONCAT(YEAR(NOW()),"-01-01")
				AND t.estimation_date < DATE_SUB(CURDATE(),INTERVAL 1 DAY)
				AND YEAR(sgk.finish_date) = YEAR(NOW())
				GROUP by me5_id
			) tab6
			ON tab6.me5_id = tab0.measure_id
		');

		$sql2=sql_placeholder('
			SELECT *, (tab1.tender_commited_money - tab2.winners_money) economy
			FROM
			(
				SELECT (SUM(lp.price) / 1000) tender_commited_money, lp.year, t.measure_id me1_id, sp.id sp_id
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
				GROUP BY me1_id, lp.year
			) tab1
			
			LEFT JOIN
			(
				SELECT  (SUM(sgk.price) / 1000) winners_money, YEAR(sgk.finish_date) works_year, gk.measure_id me2_id
				FROM bidGK bgk, lot l, tender t, stepGK sgk, GK gk
				WHERE sgk.GK_id = gk.id
				AND gk.bidGK_id = bgk.id
				AND bgk.winner = 1
				AND  bgk.lot_id = l.id
				AND l.tender_id= t.id
				AND t.estimation_date >= CONCAT(YEAR(NOW()),"-01-01")
				AND t.estimation_date < DATE_SUB(CURDATE(),INTERVAL 1 DAY)
				GROUP by me2_id,	works_year 
			) tab2
			ON tab2.works_year = tab1.year
			AND tab1.me1_id = tab2.me2_id
		');
			
/*print_r($sql1);		
echo "<br>-----<br>";
print_r($sql2);*/

    $work_steps1 = $this->db->_array_data($sql1);
    $work_steps2 = $this->db->_array_data($sql2);

    $data = array();
	$m_titles = array();
	$sp_titles = array();
			    
	function key_group_by($items, $indicator_name) {
    
     	$indicator = $items[0][$indicator_name];
     	$groups = array();
     	$group = array();
     
        foreach ($items as $item) {
               
            //if first item
            if ($item[$indicator_name] != $indicator) {
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
    
	/* Группировка элементов по значению ключа-индикатора */
/*    function key_group_by($items, $indicator_name) {
        $indicator = null;
        $groups = array();
    	$group = array();
        $first_item_has_been = true;
            
        foreach ($items as $item) {
            if ($first_item_has_been) {
        	    $first_item_has_been = false;
				$indicator = $item[$indicator_name];                    
            }
               
            //if first item
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
  */  
	/*Непериодические показатели*/
    if (!empty($work_steps1))
    {
        /* 
         * Значения показателей собранные для текущего мероприятия, в формате 
         * prop_name => array(prop_values) 
         */
        $prop_values = array();
        
        /* Группируем годичные записи о мероприятиях по подпрограммам */
        $subprograms = key_group_by($work_steps1, 'sp_id');
        $subprogram_totals = array();
        
        foreach ($subprograms as $sp_id => $subprogram_measures)
        {
        	$prop_values[$sp_id] = array();
        	/* Суммарные знания показателей по годам для всей подпрограммы */
            $subprogram_totals[$sp_id] = array();
            /* Группируем годичные записи по мероприятиям */

            $measures = key_group_by($subprogram_measures, 'measure_id');

            foreach ($measures as $m_id => $measure_records) 
            {
            	$prop_values[$sp_id][$m_id] = array();
                $record = $measure_records[0];
                
                $sp_title = $record['spt'];
                $m_title = $record['mt'];
                
                if (!isset($sp_titles[$sp_id])) {$sp_titles[$sp_id] = $sp_title;}
                if (!isset($m_titles[$m_id])) {$m_titles[$m_id] = $m_title;}      
                
                /* Для всех выбранных непериодических показателей запоминаем значения */
               	foreach($reportPropsNames as $prop_name) 
               	{
               		if (!$possiblePropsNames[$prop_name]["periodical"])
					{
						$value = $record[$prop_name];
                        
	                    $prop_values[$sp_id][$m_id][$prop_name] = $value;
	
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

    
    /* 
     * Список годов, который будет потом использоваться, чтобы заполнить нулями 
     * те показатели, для которых не найдено данных и соответствующие ключи в 
     * массиве $prop_values[$sp_id][$m_id] не были даже проинициализированы 
     */
    $years_list = array();
    
    /* 
     * Список годов, который будет потом использоваться, чтобы заполнить нулями 
     * те показатели, для которых не найдено данных и соответствующие ключи в 
     * массиве $prop_values[$sp_id][$m_id] не были даже проинициализированы 
     */
    $years_list = array();
    
	/*Периодические показатели*/
    if (!empty($work_steps2))
    {
    	/* Группируем годичные записи о мероприятиях по подпрограммам */
        $subprograms = key_group_by($work_steps2, 'sp_id');
        
        foreach ($subprograms as $sp_id => $subprogram_measures)
        {
            $sp_title = $record['spt'];
            if (!isset($sp_titles[$sp_id])) {$sp_titles[$sp_id] = $sp_title;}      
        	
            if (!isset($prop_values[$sp_id])) {
                $prop_values[$sp_id] = array();
            }
            
        	/* Суммарные знания показателей по годам для всей подпрограммы */
	        if (!isset($subprogram_totals[$sp_id]))
	        {
        		$subprogram_totals[$sp_id] = array();
	        }

            /* Группируем годичные записи по мероприятиям */
            $measures = key_group_by($subprogram_measures, 'measure_id');
            
            foreach ($measures as $m_id => $measure_years) 
            {
            	$firstMetEconomy = true; // economy isnt a periodical property but got with query for periodical prop-s, that's why will handle it separately
            	
            	/* 
                 * Значения показателей по году в формате:
                 * prop_name => array(year => array(prop_value))
                 */
            	if (!isset($prop_values[$sp_id][$m_id]))
                {
            		$prop_values[$sp_id][$m_id] = array();
                }

                /* Перебираем годичные записи */
                foreach ($measure_years as $record) {
                    $year = $record['year'];
                    $years_list[$year] = true;

                    $m_title = $record['mt'];
                    if (!isset($m_titles[$m_id])) {$m_titles[$m_id] = $m_title;}      

                    /* Для всех выбранных показателей запоминаем значения */
                    foreach($reportPropsNames as $prop_name) 
                    {
                    	if ($possiblePropsNames[$prop_name]["periodical"])
						{
							$value = $record[$prop_name];
						
							if ($prop_name == "economy") 
							{
								if ($firstMetEconomy)
								{
			                        if (!isset($prop_values[$sp_id][$m_id][$prop_name])) {
			                            $prop_values[$sp_id][$m_id][$prop_name] = 0;
			                        }
			                        /* Запоминаем значения для текущего года */
			                        $prop_values[$sp_id][$m_id][$prop_name] = $value;
								}
							} else 
							{
		                        if (!isset($prop_values[$sp_id][$m_id][$prop_name])) {
		                            $prop_values[$sp_id][$m_id][$prop_name] = array();
		                        }
		                        /* Запоминаем значения для текущего года */
		                        $prop_values[$sp_id][$m_id][$prop_name][$year] = $value;
							}

							if ($prop_name == "economy") 
							{
								if ($firstMetEconomy)
								{
									if (!isset($subprogram_totals[$sp_id][$prop_name])) {
			                            $subprogram_totals[$sp_id][$prop_name] = 0;
			                        }

			                        /* Обновляем суммарное значение у подпрограммы */
			                        $prop_total = & $subprogram_totals[$sp_id][$prop_name];
			                        if (!isset($prop_total)) {
			                            $prop_total = $value;
			                        } else {
			                            $prop_total += $value;
			                        }
			                        $firstMetEconomy = false;
								}
							} else
							{
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
    }        
    
    function fill_up_with_nulls($props, $years, $reportPropsNames, $possiblePropsNames) {
        foreach($reportPropsNames as $prop_name) {
            if (isset($props[$prop_name])) {
                continue;
            }
                        
            if ($possiblePropsNames[$prop_name]['periodical']) {
                $props[$prop_name] = array();
                
                foreach ($years as $year) {
                    $props[$prop_name][$year] = 0;
                }
            } else {
                $props[$prop_name] = 0;
            }
        }
        
        return $props;
    } 
    
    $years = array_keys($years_list);
    
    foreach ($subprogram_totals as $sp_id => $props) {
        $subprogram_totals[$sp_id] = fill_up_with_nulls($props, $years, $reportPropsNames, $possiblePropsNames);
    }
    
    foreach ($prop_values as $sp_id => $measures) {
        foreach ($measures as $m_id => $props) {           
            $prop_values[$sp_id][$m_id] = fill_up_with_nulls($props, $years, $reportPropsNames, $possiblePropsNames);
        }    
    }
      
	foreach ($subprogram_totals as $sp_id => $sp_totals_data)
	{
        $subprogram_data = array();
        
		foreach ($prop_values[$sp_id] as $m_id=>$measure_data)
		{
	                /* Формируем список значений показателей мероприятия */
	                $content = array();
	                foreach ($reportPropsNames as $prop_name) {
	                    $content[] = array('propTitle' => $possiblePropsNames[$prop_name]["title"],
	                                       'values'    => $measure_data[$prop_name]);
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
                        
        /*
        * И также переносим элементы из временного списка, чтобы записи о 
        * меропритиях оказались после записей о подпрограммах
        */      
        if ($detailByMeasures) {
            $data = array_merge($data, $subprogram_data);
        }
	}

		// Start creating excel file
		
			if (file_exists('/var/www/dgpgm/files/excel/course.xls') ) 
			  {
			    unlink ('/var/www/dgpgm/files/excel/course.xls');
			  }
			// Include PEAR::Spreadsheet_Excel_Writer
			require_once "Spreadsheet/Excel/Writer.php";
			// Create an instance, passing the filename to create
			$xls =& new Spreadsheet_Excel_Writer('/var/www/dgpgm/files/excel/course.xls');
			
			$xls->setVersion(8); 
	
    		// Add a worksheet to the file, returning an object to add data to
			$cart =& $xls->addWorksheet('Course report');
			$cart->setInputEncoding('UTF-8'); 
			
			$titleText = 'Статистика по ходу проведения конкурсов на '.date('Y-m-d'); 
			// Создание объекта форматирования 
			$titleFormat =& $xls->addFormat(); 
			$titleFormat->setFontFamily('Times New Roman'); 
			$titleFormat->setBold(); 
			$titleFormat->setSize('12'); 
			$titleFormat->setColor('navy'); 
			$titleFormat->setBorder(2); 
			$titleFormat->setBorderColor('navy'); 
			$titleFormat->setHAlign('center');
			$titleFormat->setVAlign('vcenter');	
			$main_col_count=count($reportPropsNames)+1;
			$cart->write(1,0,$titleText,$titleFormat); 
			for ($i=1;$i<$main_col_count;$i++) { $cart->write(1,$i,'',$titleFormat);  }
			$cart->mergeCells(1,0,1,$main_col_count-1);
			$cart->setRow(1,30);
			$cart->setColumn(0,0,40);
			$cart->setColumn(1,$main_col_count-1,20);
			
			// задание заголовков столбцов таблицы
			$coltitleformat = & $xls->addFormat();
			$coltitleformat->setFontFamily('Times New Roman'); 
			$coltitleformat->setBold(); 
			$coltitleformat->setSize('10'); 
			$coltitleformat->setColor('navy'); 
			$coltitleformat->setHAlign('center');
			$coltitleformat->setVAlign('vcenter');
			$coltitleformat->setBorder(1);
			$coltitleformat->setTextWrap();
			
			$colprogramformat = & $xls->addFormat();
			$colprogramformat->setFontFamily('Times New Roman'); 
			$colprogramformat->setSize('9'); 
			$colprogramformat->setBold();
			$colprogramformat->setFGColor('silver'); 
			$colprogramformat->setHAlign('center');
			$colprogramformat->setVAlign('vcenter');
			$colprogramformat->setTextWrap();
			$colprogramformat->setBorder(1);
			
			$colmeasureformat = & $xls->addFormat();
			$colmeasureformat->setFontFamily('Times New Roman'); 
			$colmeasureformat->setSize('9'); 
			$colmeasureformat->setColor('navy'); 
			$colmeasureformat->setHAlign('center');
			$colmeasureformat->setVAlign('vcenter');
			$colmeasureformat->setTextWrap();
			$colmeasureformat->setBorder(1);
			
			$cart->write(2,0,'Подпрограмма / Мероприятие',$coltitleformat);
			$col=1;
			foreach($reportPropsNames as $prop_title)
			  {
				$cart->write(2,$col,$possiblePropsNames[$prop_title]["title"],$coltitleformat);
				$col++;
			  }
			$freeze = array(3,0); 
			$cart->freezePanes($freeze);  
			// вывод самих значений
			$element_count=count($data);
			$currow=3;
			foreach ($data as $data_element)
			  {
			    if ($data_element['type']=='subprogram') $fname="colprogramformat"; else
				  $fname="colmeasureformat";
				  $cart->write($currow,0,strval($data_element['title']),$$fname);
				  $col=1;
				  foreach ($data_element['content'] as $content_element)
				    {
					  $cart->write($currow,$col,$content_element['values'],$$fname);
					  $col++;
					}
				  $currow++;
			  }
    		$xls->close();
          
		  // end creating excel file
    
      echo '<!--' . print_r($data, true) . '-->';
        $TPL['DATA'] = json_encode($data);
        
        /* Если выбрана одна подпрограмма */
        if (isset($subprogram_id)) {
            $subprogram = reset($data);
            $title = $subprogram['title'];
            
            $TPL['STATTITLE'] = 'Статистка по ходу проведения конкурсов по подпрограмме ' . $title . ' на ' . date('d.m.Y') . ' г.';
        } else {
            $TPL['STATTITLE'] = 'Статистка по ходу проведения конкурсов на ' . date('d.m.Y') . ' г.';
	    }
        
        include TPL_CMS_STATS."course-result.php";
    } // end Post
   else {
    		$startYear = 2011;
    		$start2Year = 2011;
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