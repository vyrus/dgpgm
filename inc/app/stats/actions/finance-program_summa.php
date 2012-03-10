<?php
    
    /*
     * Финансовая справка по реализации программы
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
    
    $subprogram_id = intval($_GET['subprogram_id']);
    // выбираем все мероприятия по данной подпрограмме
    $sql='select id, title from measure where subprogram_id='.$subprogram_id;
	$sql = sql_placeholder($sql);
    
    $rows_1 = $this->db->_array_data($sql);
    if ($debug) {
        echo pre($sql);
        echo pre($rows_1);
    } 
	$data = array();
	$i=1;
	// формируем массив по выбранным мероприятиям заданной подпрограммы
    foreach ($rows_1 as $measure) 
      {
	    $m_title=$measure['title'];
		$m_id=$measure['id'];
		$data[$i]= array(
            'id'        => $m_id,
            'title'     => $m_title,
			'sums' => array ()
			);
			
			// формируем значения по каждому месяцу для заданного мероприятия
			for ($j=1;$j<=12;$j++)
			  {
			    $start_date=date('Y-m-d',mktime(0,0,0,$j,1,$cur_year));
				$finish_date=date('Y-m-d',mktime(0,0,0,$j,31,$cur_year));
				// находим авансовые платежи
				$sql="SELECT GK.number as num, GK.signing_date as s_date, stepGK.number as s_num,
				stepGK.prepayment as prepayment 
				FROM GK, stepGK
				WHERE (GK.id=stepGK.GK_id) AND (GK.measure_id='$m_id') 
				AND (stepGK.prepayment_date between '$start_date' AND '$finish_date')";
				$row=$this->db->_array_data($sql);
				$s='';
				$summa=0;
				foreach ($row as $r)
				  {
				    $s.='ГК № '.$r[num].' от '.$r['s_date'].', этап № '.$r[s_num].', аванс'."<br>";
					$summa+=$r['prepayment'];
				  }
				
				// находим платежи по акту
				$sql="SELECT GK.number as num, GK.signing_date as s_date, stepGK.number as s_num,
				stepGK.financing_act as financing_act 
				FROM GK, stepGK
				WHERE (GK.id=stepGK.GK_id) AND (GK.measure_id='$m_id') 
				AND (stepGK.act_financing_date between '$start_date' AND '$finish_date')";
				$row=$this->db->_array_data($sql);
				foreach ($row as $r)
				  {
				    $s.='ГК № '.$r[num].' от '.$r['s_date'].', этап № '.$r[s_num].', акт'."<br>";
					$summa+=$r['financing_act'];
				  } 
				$data[$i]['sums'][$j]= array
				  ('text'=>$s,'value'=>$summa);  
				}  
				  if ($debug) {
						//echo pre($sql);
						echo pre($data);
								}
				$i++;
	  }


	  
	$TPL['DATA']=$data;
	$TPL['STATTITLE'] = 'Детализация финансовой справки (финансирование по месяцам) по подпрограмме № '.$subprogram_id;
    
    include TPL_CMS_STATS . 'finance-program-result_summa.php';
    
?>
