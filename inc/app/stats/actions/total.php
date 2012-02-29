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

    $sql = sql_placeholder('
        SELECT *
        FROM 
        (
			SELECT ys.year, sp.id sp_id, sp.title spt, 
                   m.title mt, m.id measure_id
			FROM years_store ys, ?#FK_MEASURE m, ?#FK_SUBPROGRAM sp
			WHERE m.subprogram_id = sp.id
			  AND year BETWEEN ? AND ?
                  ' . $sp_condition . '
		) tab0
		
		LEFT JOIN
		(
        	SELECT mp.measure_id me4_id, mp.year y1, (mp.financing DIV 1000) financing, 
                   mp.gk_count, mp.tender_count
            FROM ?#FK_MEASURE_PLAN mp
        ) tab1
		ON (tab0.year = tab1.y1) AND (tab0.measure_id = tab1.me4_id) 
		
        LEFT JOIN 
        (
            SELECT (SUM(sum) DIV 1000) financed, 
                   gk.measure_id me3_id, YEAR(po.date) p_date
            FROM `payment_order` po, stepGK sgk, GK gk
            WHERE `status` <> "отменено" AND
                  po.stepGK_id = sgk.id AND
                  sgk.GK_id = gk.id
            GROUP BY me3_id, p_date
        ) tab2 
        ON (tab0.measure_id = tab2.me3_id) AND (tab2.p_date = tab0.year)

        LEFT JOIN 
        (
            SELECT count(t.title) tender_commited, t.measure_id me1_id, YEAR(t.estimation_date) estimation_year
            FROM tender t
            WHERE t.estimation_date < DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            GROUP BY estimation_year, me1_id 	
        ) tab3
        ON (tab0.measure_id = tab3.me1_id) AND (tab3.estimation_year = tab0.year)
        
        LEFT JOIN 
        (
            SELECT count(DISTINCT gk.number) gk_commited, 
                   gk.measure_id me2_id, YEAR(gk.signing_date) s_date 
            FROM GK gk, status s
            WHERE s.title = "заключен" OR s.title = "завершен" AND
            		s.id = gk.status_id AND 
                  gk.signing_date < DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            GROUP BY me2_id, s_date
        ) tab4 
        ON (tab0.measure_id = tab4.me2_id) AND (tab4.s_date = tab0.year) 
      

        ORDER BY tab0.sp_id, tab0.measure_id, tab0.year', $startYear, $finishYear);
//echo '<!--' . print_r($sql).'<br>--<br> -->';
	$work_steps = $this->db->_array_data($sql);
//	print_r($work_steps);
	if (!empty($work_steps))
    {
        $data = array();

        /* 
         * Значения показателей собранные для текущего мероприятия, в формате 
         * prop_name => array(prop_values) 
         */
        $prop_values = array();
        
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
       // echo '<!--' . print_r($measure_years).'<br>--<br> -->';
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
                $subprogram_data[] = array('title'   => $record['measure_id'] . ' ' . $record['mt'],
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
            $data[] = array('title'   => $record['spt'],
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
        
        echo '<!--' . print_r($data, true) . '-->';
		
		// Start creating excel file
		
			if (file_exists('/var/www/dgpgm/files/excel/total.xls') ) 
			  {
			    unlink ('/var/www/dgpgm/files/excel/total.xls');
			  }
			// Include PEAR::Spreadsheet_Excel_Writer
			require_once "Spreadsheet/Excel/Writer.php";
			// Create an instance, passing the filename to create
			$xls =& new Spreadsheet_Excel_Writer('/var/www/dgpgm/files/excel/total.xls');
			
			$xls->setVersion(8); 
	
    		// Add a worksheet to the file, returning an object to add data to
			$cart =& $xls->addWorksheet('Main report');
			$cart->setInputEncoding('UTF-8'); 
			
			// какой нибудь текст в роли заголовка листа 
			if ($startYear!==$finishYear) 
			$titleText = 'Общая статистика по программе за '.$startYear.'-'.$finishYear.' годы'; 
			else $titleText = 'Общая статистика по программе за '.$startYear.' год';
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
			if ($startYear!==$finishYear) $main_col_count=($finishYear-$startYear+2)*count($reportPropsNames)+1;
			else $main_col_count=count($reportPropsNames)+1;
			$cart->write(1,0,$titleText,$titleFormat); 
			for ($i=1;$i<$main_col_count;$i++) { $cart->write(1,$i,'',$titleFormat);  }
			$cart->mergeCells(1,0,1,$main_col_count-1);
			$cart->setRow(1,30);
			$cart->setColumn(0,0,40);
			$cart->setColumn(1,9,10);
			
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
			
			if ($startYear!==$finishYear)
			  {
			    $cart->write(2,0,'Подпрограмма / Мероприятие',$coltitleformat);
				$cart->write(3,0,'',$coltitleformat);
				$cart->mergeCells(2,0,3,0);
				$col=1;
				foreach($reportPropsNames as $prop_title)
				  {
					$startcol=$col;
					$cart->write(2,$col,"$possiblePropsNames[$prop_title]",$coltitleformat);
					$cart->write(3,$col,"$startYear",$coltitleformat);
					$col++;
					for ($i=$startYear+1;$i<=$finishYear;$i++)
					  {
					    $cart->write(2,$col,'',$coltitleformat);
						$cart->write(3,$col,"$i",$coltitleformat);
						$col++;
					  }
					$cart->write(2,$col,'',$coltitleformat);
					$cart->write(3,$col,"Всего",$coltitleformat);
					$col++;  
					$cart->mergeCells(2,$startcol,2,$col-1);  
				  }
				$freeze = array(4,0); 
				$cart->freezePanes($freeze);  
				// вывод самих значений
				$element_count=count($data);
				$currow=4;
				foreach ($data as $data_element)
				  {
				    if ($data_element['type']=='subprogram') $fname="colprogramformat"; else
					  $fname="colmeasureformat";
					  $title=$data_element['title'];
					  $cart->write($currow,0,strval($title),$$fname);
					  $col=1;
					  foreach ($data_element['content'] as $content_element)
					    {
						  $startcol=$col;
						  foreach ($content_element['values'] as $year_value)
						    {
							  if ($year_value=='') $cart->write($currow,$col,"0",$$fname); else $cart->write($currow,$col,$year_value,$$fname);
							  $col++;
							} 
						  // вывод итого
						  $cell1 = Spreadsheet_Excel_Writer::rowcolToCell($currow, $startcol);
						  $cell2 = Spreadsheet_Excel_Writer::rowcolToCell($currow, $col-1);
						  $formula="=SUM($cell1:$cell2)";
						  $cart->writeFormula($currow,$col,$formula,$$fname); 
						  $col++;
						}
					  $currow++; 
				  }
				// добавление итоговой строки
				if ($subProgram=="all")
				  {
					$cart->write($currow,0,'Всего по программе',$colprogramformat);
					for ($col=1;$col<$main_col_count;$col++)
					  {
						$cell1 = Spreadsheet_Excel_Writer::rowcolToCell(4, $col);
						$cell2 = Spreadsheet_Excel_Writer::rowcolToCell($currow-1, $col);
						$formula="=SUM($cell1:$cell2)";
						$cart->writeFormula($currow,$col,$formula,$colprogramformat); 
					  }
				  }	  
			    }
			else 
			  {
			    $cart->setColumn(1,$main_col_count,17);
				$cart->write(2,0,'Подпрограмма / Мероприятие',$coltitleformat);
			//	$cart->write(3,0,'',$coltitleformat);
			//	$cart->mergeCells(2,0,3,0);
				$col=1;
				foreach($reportPropsNames as $prop_title)
				  {
					$startcol=$col;
					$cart->write(2,$col,"$possiblePropsNames[$prop_title]",$coltitleformat);
				//	$cart->write(3,$col,"$startYear",$coltitleformat);
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
						  foreach ($content_element['values'] as $year_value)
						    {
							  $cart->write($currow,$col,$year_value,$$fname);
							  $col++;
							} 
						}
					  $currow++;
				  }
				// добавление итоговой строки
				if ($subProgram=="all")
				  {
					$cart->write($currow,0,'Всего по программе',$colprogramformat);
					for ($col=1;$col<$main_col_count;$col++)
					  {
						$cell1 = Spreadsheet_Excel_Writer::rowcolToCell(4, $col);
						$cell2 = Spreadsheet_Excel_Writer::rowcolToCell($currow-1, $col);
						$formula="=SUM($cell1:$cell2)";
						$cart->writeFormula($currow,$col,$formula,$colprogramformat); 
					  }
				  }
			  }  
    		$xls->close();
          
		  // end creating excel file
		
        $TPL['DATA'] = json_encode($data);
        $TPL['STATTITLE'] = 'Общая статистика по программе за '.$startYear.'-'.$finishYear.' годы';
	    include TPL_CMS_STATS."total-result.php";
    }
    
    } else {
    		$startYear = 2011;
    		$start2Year = 2012;
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