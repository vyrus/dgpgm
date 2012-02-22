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
            'signed_gk' => array('amount' => 0,
                                 'num'    => 0),
            /* 
             * Сумма остатка равна полному запланированному объему 
             * финансирования, она также будет обновлена по обработки данных 
             * заключенных госконтрактов
             */
            'leftover'  => array('amount' => $row['total_financing'], 
                                 'num'    => $row['num_signed_gk'])
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
        $subprogram['leftover']['amount'] -= $total['amount'];
        /* Добавляем суммарные данные о заключенных госконтрактах */
        $subprogram['signed_gk'] = array('amount' => $total['amount'], 
                                         'num'    => $total['num']);
    }
    
    if ($debug) {
        echo pre($data);
    }
    
?>
