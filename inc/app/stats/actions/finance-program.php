<?php
    
    /*
     * Финансовая справка по реализации программы
     */
    
    /*
        - проверить запрос 
        - протестировать на нормальных данных
    */
    
    //if (USER_GROUP != 5) {
    if (!true) {                                                                                                                                                              
        include TPL_CMS_STATS."no-rights.php";
        exit();
    }  
    
    function pre($var) {
        $crlf = "\r\n";
        return '<!--' . $crlf . 
                   print_r($var, true) . $crlf . 
               '-->' . $crlf;
    }
    
    $debug = true;
    $cur_year = date('Y');
    $cur_date = date('Y-m-d');
    
    /*
     * Плановое финансирование по подпрограммам на текущий год, 
     * количество ГК на текущий год
     */
    $sql = sql_placeholder('
        SELECT sp.id, sp.title, SUM(mp.financing) AS total_financing, (
        
            /* Сумма ГК по всем мероприятиям подпрограммы */
            SELECT SUM(mp2.gk_count)
            FROM ?#FK_MEASURE m2, ?#FK_MEASURE_PLAN mp2
            WHERE m2.subprogram_id = sp.id AND
                  mp2.measure_id = m2.id AND
              mp2.year = "' . $cur_year . '"
        ) AS num_signed_gk
        FROM ?#FK_MEASURE_PLAN mp, ?#FK_MEASURE m, ?#FK_SUBPROGRAM sp
        
        WHERE mp.measure_id = m.id AND
              m.subprogram_id = sp.id AND
              mp.year = "' . $cur_year . '"
        GROUP BY sp.id
        ORDER BY sp.id ASC
    ');
    
    $rows_1 = $this->db->_array_data($sql);
    if ($debug) {
        echo pre($sql);
        echo pre($rows_1);
    }
    
    /*
    INSERT INTO  `stepGK` (  `id` ,  `start_date` ,  `finish_date` ,  `presentation_date` ,  `review_date` ,  `prepayment_date` ,  `act_financing_date` ,  `integration_date` ,  `price` ,  `prepayment` ,  `act_number` ,  `act_reg_date` ,  `act_file_link` ,  `GK_id` ,  `financing_act` ) 
    VALUES ('25',  '2012-02-07',  '2012-02-09', NULL , NULL ,  '2012-02-10',  '2012-02-14', NULL ,  '1300',  '1234', NULL , NULL , NULL ,  '0',  '4321');
    */
    
    /* Заключенные до текущего года ГК, сумма финансирования и кол-во */
    $sql = sql_placeholder('
        SELECT sp.id AS subprogram_id, gk.id AS gk_id, gk.work_title, (
        
            /* Сумма цен всех этапов ГК */
            SELECT SUM(st.price)
            FROM ?#FK_STEPGK AS st
            WHERE YEAR(st.finish_date) = "' . $cur_year . '" AND
                  st.GK_id = gk.id
            GROUP BY st.GK_id
        
        ) AS financing
        FROM ?#FK_GK AS gk, ?#FK_STATUS AS s, ?#FK_MEASURE AS m, 
             ?#FK_SUBPROGRAM AS sp
        
        WHERE gk.signing_date < "' . $cur_year . '-01-01" AND
              s.title IN ("заключен", "завершен") AND
              EXISTS(
                  
                  /* Существуют этапы с датой окончания после начала года */
                  SELECT st.id
                  FROM ?#FK_STEPGK st
                  WHERE st.finish_date > "' . $cur_year . '-01-01" AND
                        st.GK_id = gk.id
              
              ) AND
              s.id = gk.status_id AND
              m.id = gk.measure_id AND
              sp.id = m.subprogram_id
              
        ORDER BY subprogram_id ASC
    ');
    
    $rows_2 = $this->db->_array_data($sql);
    if ($debug) {
        echo pre($sql);
        echo pre($rows_2);
    }
    
    $data = array();
    
    /* Перебираем первые полученные данные по подпрограммам */
    foreach ($rows_1 as $row) {
        /* Формируем начальный массив данных для передачи в шаблон */
        $id = $row['id'];
        $data[$id] = array(
            'id'        => $id,
            'title'     => $row['title'],
            'financing' => $row['total_financing'],
            /* 
             * По умолчанию сумма финансирования по заключенным госконтрактам и 
             * их количество равны нулю. Эти значения буду обновлены после 
             * обработки данных о заключенных госконтрактах из второго запроса.
             * А если таковых не будет, то значения и останутся нулевыми.
             */
            'signed_gk_amount' => 0,
            'signed_gk_num'    => 0,
            /* 
             * Сумма остатка равна полному запланированному объему 
             * финансирования, она также будет обновлена после обработки данных 
             * заключенных госконтрактов
             */
            'leftover_amount' => $row['total_financing'], 
            'leftover_num'    => $row['num_signed_gk']
        );
    }
    
    /* Группируем данные о госконтрактах по подпрограммам, к которым они относятся */
    $gk_groups = group_by($rows_2, 'subprogram_id');
    
    /* Перебираем группы госконтрактов по подпрограммам */
    foreach ($gk_groups as $group) {
        /* Массив для накопления суммарных значений для подпрограммы целиком */
        $total = array('amount' => 0, 'num' => 0);
        
        /* Перебираем госконтракты из группы */
        foreach ($group as $gk) {
            $total['num']++;
            $total['amount'] += $gk['financing']; 
        }
        
        /* Берём идентификатор подпрограммы из первого госконтракта в группе */
        $first = reset($group);
        $id = $first['subprogram_id'];
        assert(isset($data[$id]));
        
        /* Обновляем данные подпрограммы */
        $subprogram = & $data[$id];
        /* Уменьшаем остаток финансирования на сумму заключенных госконтрактов */
        $subprogram['leftover_amount'] -= $total['amount'];
        /* Добавляем суммарные данные о заключенных госконтрактах */
        $subprogram['signed_gk_amount'] = $total['amount']; 
        $subprogram['signed_gk_num'] = $total['num'];
    }
    
    if ($debug) {
        echo pre($data);
    }
    
	if (empty($_GET['subprogram_id'])) {	
    /* формирование эксель файла с отчетом по общей финансовой справке */ 
	
	$fpath='/var/www/dgpgm/files/excel/finance.xls';
	  //$fpath='c:\excel\fifnance.xls';
	if (file_exists($fpath) ) 
			  {
			    unlink ($fpath);
			  }
			// Include PEAR::Spreadsheet_Excel_Writer
			require_once "Spreadsheet/Excel/Writer.php";
			// Create an instance, passing the filename to create
			$xls =& new Spreadsheet_Excel_Writer($fpath);
			
			$xls->setVersion(8); 
	
    		// Add a worksheet to the file, returning an object to add data to
			$cart =& $xls->addWorksheet('Finance report');
			$cart->setInputEncoding('UTF-8'); 
			
			// какой нибудь текст в роли заголовка листа 
			$titleText = 'Финансовая справка по реализации программы'; 
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
			$cart->write(1,0,$titleText,$titleFormat); 
			for ($i=1;$i<7;$i++) { $cart->write(1,$i,'',$titleFormat);  }
			$cart->mergeCells(1,0,1,6);
			$cart->setRow(1,30);
			$cart->setColumn(0,0,5);
			$cart->setColumn(1,1,35);
			$cart->setColumn(2,6,15);
			
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
			
			$colformat = & $xls->addFormat();
			$colformat->setFontFamily('Times New Roman'); 
			$colformat->setSize('9'); 
			$colformat->setColor('navy'); 
			$colformat->setHAlign('center');
			$colformat->setVAlign('vcenter');
			$colformat->setTextWrap();
			$colformat->setBorder(1);
			
		    $cart->write(2,0,'№',$coltitleformat);
			$cart->write(3,0,'',$coltitleformat);
			$cart->mergeCells(2,0,3,0);
			$cart->write(2,1,'Подпрограмма',$coltitleformat);
			$cart->write(3,1,'',$coltitleformat);
			$cart->mergeCells(2,1,3,1);
			$cart->write(2,2,'Плановое финансирование на '.$cur_year.' г., млн. руб.',$coltitleformat);
			$cart->write(3,2,'',$coltitleformat);
			$cart->mergeCells(2,2,3,2);
			$cart->write(2,3,'Заключенные до '.$cur_year.' г. контракты',$coltitleformat);
			$cart->write(2,4,'',$coltitleformat);
			$cart->mergeCells(2,3,2,4);
			$cart->write(3,3,'Сумма финансирования на '.$cur_year.' г., млн. руб.',$coltitleformat);
			$cart->write(3,4,'Количество',$coltitleformat);
			$cart->write(2,5,'Остаток',$coltitleformat);
			$cart->write(2,6,'',$coltitleformat);
			$cart->mergeCells(2,5,2,6);
			$cart->write(3,5,'Сумма, млн. руб.',$coltitleformat);
			$cart->write(3,6,'Количество',$coltitleformat);
				
			// заморозка верхних строк таблицы
			$freeze = array(4,0); 
			$cart->freezePanes($freeze);  
			
			// вывод самих значений
			$element_count=count($data);
			$currow=4;
			foreach ($data as $data_element)
			  {
			    $fin=round($data_element['financing']/1000000,3);
				$sga=round($data_element['signed_gk_amount']/1000000,3);
				$loa=round($data_element['leftover_amount']/1000000,3);
				$cart->write($currow,0,' '.$data_element['id'],$colformat);
				$cart->write($currow,1,$data_element['title'],$colformat);
				$cart->write($currow,2,"$fin",$colformat);
				$cart->write($currow,3,"$sga",$colformat);
				$cart->write($currow,4,$data_element['signed_gk_num'],$colformat);
				$cart->write($currow,5,"$loa",$colformat);
				$cart->write($currow,6,$data_element['leftover_num'],$colformat);
				$currow++;
			  }
			// добавление итоговой строки
				$cart->write($currow,1,'Всего по программе',$colformat);
				$cart->write($currow,0,'',$colformat);
				for ($col=2;$col<7;$col++)
				  {
					$cell1 = Spreadsheet_Excel_Writer::rowcolToCell(4, $col);
					$cell2 = Spreadsheet_Excel_Writer::rowcolToCell($currow-1, $col);
					$formula="=SUM($cell1:$cell2)";
					$cart->writeFormula($currow,$col,$formula,$colformat); 
				  }
   		$xls->close();

	/* конец формирования эксель файла с отчетом по общей финансовой справке */
		}
	
    $TPL['program_data'] = json_encode($data);
    $TPL['year'] = $cur_year;
               
    if (!empty($_GET['subprogram_id'])) {
        $subprogram_id = intval($_GET['subprogram_id']);
        
        /* Начальные остатки суммы финансирования и количества госконтрактов */
/*
        $sql = sql_placeholder('
            SELECT m.id, m.title, mp.financing, mp.gk_count
            FROM ?#FK_MEASURE m, ?#FK_MEASURE_PLAN mp
            
            WHERE mp.year = "' . $cur_year . '" AND
                  mp.measure_id = m.id AND
                  m.subprogram_id = ' . $subprogram_id . '
	        ORDER BY m.id;
	        ');
print_r($sql);
echo "<br><br>";
*/
        $sql = sql_placeholder('
		SELECT *
		FROM
        (
			SELECT m.id me1_id, m.title, mp.financing, mp.gk_count gk_count
            FROM ?#FK_MEASURE m, ?#FK_MEASURE_PLAN mp
            
            WHERE mp.year = "' . $cur_year . '" AND
                  mp.measure_id = m.id AND
                  m.subprogram_id = ' . $subprogram_id . '
		) tab0

		LEFT JOIN
		(
			SELECT m.id me2_id, COUNT(gk.id) gk_cnt, gk.work_title, SUM((
	        
	            /* Сумма цен всех этапов ГК */
	            SELECT SUM(st.price)
	            FROM ?#FK_STEPGK AS st
	            WHERE YEAR(st.finish_date) = "' . $cur_year . '" AND
	                  st.GK_id = gk.id
	            GROUP BY st.GK_id
	        
	        )) real_financing
	        FROM ?#FK_GK AS gk, ?#FK_STATUS AS s, ?#FK_MEASURE AS m
	        WHERE gk.signing_date < "' . $cur_year . '-01-01" AND
	              s.title IN ("заключен", "завершен") AND
	              EXISTS(
	                  
	                  /* Существуют этапы с датой окончания после начала года */
	                  SELECT st.id
	                  FROM ?#FK_STEPGK st
	                  WHERE st.finish_date > "' . $cur_year . '-01-01" AND
	                        st.GK_id = gk.id
	              
	              ) AND
	              s.id = gk.status_id AND
	              m.id = gk.measure_id AND
	              m.subprogram_id = ' . $subprogram_id . '
	         GROUP BY me2_id
		) tab1
		
		ON tab0.me1_id = tab1.me2_id
		ORDER BY tab0.me1_id;
        ');
              
//print_r($sql);
        
        $rows_1 = $this->db->_array_data($sql);
        if ($debug) {
            echo pre($sql);
            echo pre($rows_1);
        }
        
        /* Сумма цен лотов по каждому конкурсу мероприятия */
        $sql = sql_placeholder('
            SELECT m.id, t.title, (
                
                /* Сумма цен лотов текущего года для конкурсов */
                SELECT COALESCE(SUM(lp.price), 0)
                FROM ?#FK_LOT l, ?#FK_LOT_PRICE lp
                WHERE lp.year = "' . $cur_year . '" AND
                      lp.lot_id = l.id AND
                      l.tender_id = t.id
                
            ) AS total_lot_price
            FROM ?#FK_MEASURE m, ?#FK_TENDER t
            
            WHERE t.notice_date > "' . $cur_year . '-01-01" AND 
                  t.notice_date < "' . $cur_date . '" AND 
                  t.measure_id = m.id AND
                  m.subprogram_id = ' . $subprogram_id . '
            
            ORDER BY m.id
        ');
        
        $rows_2 = $this->db->_array_data($sql);
        if ($debug) {
            echo pre($sql);
            echo pre($rows_2);
        }
        
        /* Сумма цен этапов госконтрактов мероприятия */
        $sql = sql_placeholder('
            SELECT m.id, gk.work_title, (
                
                /* Сумма цен этапов, завершающихся в текущем году */
                SELECT SUM(st.price)
                FROM ?#FK_STEPGK AS st
                WHERE YEAR(st.finish_date) = "' . $cur_year . '" AND
                      st.GK_id = gk.id
                GROUP BY st.GK_id
                
            ) AS total_step_price
            FROM ?#FK_MEASURE m, ?#FK_GK AS gk, ?#FK_STATUS AS s
            
            WHERE gk.signing_date >= "' . $cur_year . '-01-01" AND 
                  gk.signing_date <= "' . $cur_date . '" AND 
                  s.title IN ("заключен", "завершен") AND
                  s.id = gk.status_id AND
                  gk.measure_id = m.id AND
                  m.subprogram_id = ' . $subprogram_id . '
            
            ORDER BY m.id
        ');
        
        $rows_3 = $this->db->_array_data($sql);
        if ($debug) {
            echo pre($sql);
            echo pre($rows_3);
        }
        
        $data = array();
        
        foreach ($rows_1 as $row) {
            $id = $row['me1_id'];
            $data[$id] = array('id'             => $id,
                               'title'          => $row['title'],
                               'plan_financing' => $row['financing'] - $row['real_financing'],
                               'plan_gk_count'  => $row['gk_count'],
                               'tenders_amount' => 0,
                               'tenders_num'    => 0,
                               'gk_amount'      => 0,
                               'gk_num'         => 0,
                               'economy'        => $row['financing']);
        }
        
        $tender_groups = group_by($rows_2, 'id');
        
        foreach ($tender_groups as $group) {
            $total = (object) array('amount' => 0, 'num' => 0);
            
            foreach ($group as $tender) {
                $total->num += 1;
                $total->amount += $tender['total_lot_price']; 
            }
            
            $first = reset($group);
            $id = $first['id'];
            assert(isset($data[$id]));
            
            $measure = & $data[$id];
            $measure['tenders_amount'] = $total->amount;
            $measure['tenders_num'] = $total->num;
        }
        
        $gk_groups = group_by($rows_3, 'id');
        
        foreach ($gk_groups as $group) {
            $total = (object) array('amount' => 0, 'num' => 0);
            
            foreach ($group as $gk) {
                $total->num += 1;
                $total->amount = $gk['total_step_price'];     
            }
            
            $first = reset($group);
            $id = $first['id'];
            assert(isset($data[$id]));
            
            $measure = & $data[$id];
            $measure['gk_amount'] = $total->amount;
            $measure['gk_num'] = $total->num;
            $measure['economy'] -= $total->amount;
        }
        
        if ($debug) {
            echo pre($data);
        }
		
		    /* формирование эксель файла с отчетом по общей финансовой справке */ 
	
	$fpath='/var/www/dgpgm/files/excel/finance.xls';
	  //$fpath='c:\excel\fifnance.xls';
	if (file_exists($fpath) ) 
			  {
			    unlink ($fpath);
			  }
			// Include PEAR::Spreadsheet_Excel_Writer
			require_once "Spreadsheet/Excel/Writer.php";
			// Create an instance, passing the filename to create
			$xls =& new Spreadsheet_Excel_Writer($fpath);
			
			$xls->setVersion(8); 
	
    		// Add a worksheet to the file, returning an object to add data to
			$cart =& $xls->addWorksheet('Detail finance report');
			$cart->setInputEncoding('UTF-8'); 
			
			// какой нибудь текст в роли заголовка листа 
			$titleText = 'Финансовая справка по реализации подпрограммы '.$subprogram_id.' за '.$cur_year.' год на '.$cur_date; 
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
			$cart->write(1,0,$titleText,$titleFormat); 
			for ($i=1;$i<9;$i++) { $cart->write(1,$i,'',$titleFormat);  }
			$cart->mergeCells(1,0,1,8);
			$cart->setRow(1,30);
			$cart->setColumn(0,0,5);
			$cart->setColumn(1,1,35);
			$cart->setColumn(2,8,15);
			
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
			
			$colformat = & $xls->addFormat();
			$colformat->setFontFamily('Times New Roman'); 
			$colformat->setSize('9'); 
			$colformat->setColor('navy'); 
			$colformat->setHAlign('center');
			$colformat->setVAlign('vcenter');
			$colformat->setTextWrap();
			$colformat->setBorder(1);
			
		    $cart->write(2,0,'№',$coltitleformat);
			$cart->write(3,0,'',$coltitleformat);
			$cart->mergeCells(2,0,3,0);
			$cart->write(2,1,'Мероприятие',$coltitleformat);
			$cart->write(3,1,'',$coltitleformat);
			$cart->mergeCells(2,1,3,1);
			
			$cart->write(2,2,'План',$coltitleformat);
			$cart->write(2,3,'',$coltitleformat);
			$cart->mergeCells(2,2,2,3);
			$cart->write(3,2,'Сумма, млн. руб.',$coltitleformat);
			$cart->write(3,3,'Количество',$coltitleformat);
			
			$cart->write(2,4,'Объявлено конкурсов',$coltitleformat);
			$cart->write(2,5,'',$coltitleformat);
			$cart->mergeCells(2,4,2,5);
			$cart->write(3,4,'Сумма на '.$cur_year.', г., млн. руб.',$coltitleformat);
			$cart->write(3,5,'Количество',$coltitleformat);
			
			$cart->write(2,6,'Заключено ГК',$coltitleformat);
			$cart->write(2,7,'',$coltitleformat);
			$cart->mergeCells(2,6,2,7);
			$cart->write(3,6,'Сумма на '.$cur_year.',г., млн. руб.',$coltitleformat);
			$cart->write(3,7,'Количество',$coltitleformat);
			
			$cart->write(2,8,'Экономия, млн. руб.',$coltitleformat);
			$cart->write(3,8,'',$coltitleformat);
			$cart->mergeCells(2,2,3,2);
				
			// заморозка верхних строк таблицы
			$freeze = array(4,0); 
			$cart->freezePanes($freeze);  
			
			// вывод самих значений
			$element_count=count($data);
			$currow=4;
			foreach ($data as $data_element)
			  {
			    $pf=round($data_element['plan_financing']/1000000,3);
				$ta=round($data_element['tenders_amount']/1000000,3);
				$ga=round($data_element['gk_amount']/1000000,3);
				$ec=round($data_element['economy']/1000000,3);
				$cart->write($currow,0,' '.strval($data_element['id']),$colformat);
				$cart->write($currow,1,$data_element['title'],$colformat);
				$cart->write($currow,2,"$pf",$colformat);
				$cart->write($currow,3,$data_element['plan_gk_count'],$colformat);
				$cart->write($currow,4,"$ta",$colformat);
				$cart->write($currow,5,$data_element['tenders_num'],$colformat);
				$cart->write($currow,6,"$ga",$colformat);
				$cart->write($currow,7,$data_element['gk_num'],$colformat);
				$cart->write($currow,8,"$ec",$colformat);
				$currow++;
			  }
			// добавление итоговой строки
				$cart->write($currow,1,'Итого',$colformat);
				$cart->write($currow,0,'',$colformat);
				for ($col=2;$col<9;$col++)
				  {
					$cell1 = Spreadsheet_Excel_Writer::rowcolToCell(4, $col);
					$cell2 = Spreadsheet_Excel_Writer::rowcolToCell($currow-1, $col);
					$formula="=SUM($cell1:$cell2)";
					$cart->writeFormula($currow,$col,$formula,$colformat); 
				  }
   		$xls->close();

	/* конец формирования эксель файла с отчетом по общей финансовой справке */
        
        $TPL['subprogram_data'] = json_encode($data);
        $TPL['subprogram_id'] = $subprogram_id;
        $TPL['date'] = date('d.m.Y');
    }
    
    include TPL_CMS_STATS . 'finance-program-result.php';
    
?>
