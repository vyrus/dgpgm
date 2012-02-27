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
    
    $debug = false;
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
                  mp2.measure_id = m2.id
        
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
                   
    $TPL['program_data'] = json_encode($data);
    $TPL['year'] = $cur_year;
               
    if (!empty($_GET['subprogram_id'])) {
        $subprogram_id = intval($_GET['subprogram_id']);
        
        /* Начальные остатки суммы финансирования и количества госконтрактов */
        $sql = sql_placeholder('
            SELECT m.id, m.title, mp.financing, mp.gk_count
            FROM ?#FK_MEASURE m, ?#FK_MEASURE_PLAN mp
            
            WHERE mp.year = "' . $cur_year . '" AND
                  mp.measure_id = m.id AND
                  m.subprogram_id = ' . $subprogram_id . '
            
            ORDER BY m.id;
        ');
        
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
            $id = $row['id'];
            $data[$id] = array('id'             => $id,
                               'title'          => $row['title'],
                               'plan_financing' => $row['financing'],
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
        
        $TPL['subprogram_data'] = json_encode($data);
        $TPL['subprogram_id'] = $subprogram_id;
        $TPL['date'] = date('d.m.Y');
    }
    
    include TPL_CMS_STATS . 'finance-program-result.php';
    
?>
