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

	function change_data_format($date)
    {
	  $values=explode('-',$date);
	  $d=date("d.m.Y",mktime(0,0,0,$values[1],$values[2],$values[0]));
	  return $d;
	}

    $debug = true;
    $cur_year = date('Y');
    $cur_date = date('Y-m-d');

    $subprogram_id = intval($_GET['subprogram_id']);
    // выбираем все мероприятия по данной подпрограмме
    $sql='select id from measure where subprogram_id='.$subprogram_id;
	$sql = sql_placeholder($sql);

    $rows_1 = $this->db->_array_data($sql);
    if ($debug) {
        echo pre($sql);
        echo pre($rows_1);
    }
	$data = array();
	$i=0;
	$start_date=date('Y-m-d',mktime(0,0,0,1,1,$cur_year));
	$finish_date=date('Y-m-d',mktime(0,0,0,12,31,$cur_year));
	// формируем массив по выбранным мероприятиям заданной подпрограммы
    foreach ($rows_1 as $measure)
      {
		$m_id=$measure['id'];
		// выбираем все госконтракты по данному мероприятию
		$sql='select GK.id as id, GK.number as number, GK.signing_date as s_date,
		org.full_title as f_title, org.short_title as s_title from GK, applicant_organization org
		where GK.id_org_ind=org.id AND GK.measure_id='.$m_id;
		$sql = sql_placeholder($sql);
    	$row = $this->db->_array_data($sql);

		if ($debug) {
        echo pre($sql);
        echo pre($rows_1);
    }

		// для каждого госконтракта находим платежки за данный год и все плановые платежи
		foreach ($row as $r)
		  {
		    $gk_id=$r['id'];
			$data[$i]= array(
            'id'        => $m_id,
            'number'     => $r['number'],
			's_date'     => $r['s_date'],
			'f_title'     => $r['f_title'],
			's_title'     => $r['s_title'],
			'sums' => array ()
			);
			//находим все платежки
			$sql="select stepGK.number as number, p.type as type, p.date as date, p.sum as sum
			from stepGK, payment_order p
			where stepGK.id=p.stepGK_id AND stepGK.GK_id='$gk_id'
			AND p.status='действует' AND date between '$start_date' AND '$finish_date'
			order by date ASC";
			$row1 = $this->db->_array_data($sql);
			foreach ($row1 as $r1)
			  {
			    $s=round($r1['sum'],2).' руб. ('.$r1['number'].' этап, '.$r1['type'].') - выплачено '.change_data_format($r1['date']);
				$data[$i]['sums'][]=$s;
			  }

			// находим все будущие платежи аванс
			$sql="select number, prepayment_date as p_date, prepayment as p
			from stepGK
			where stepGK.GK_id='$gk_id' AND prepayment_date between '$cur_date' AND '$finish_date'
			order by prepayment_date ASC";
			$row1 = $this->db->_array_data($sql);
			foreach ($row1 as $r1)
			  {
			    $s=round($r1['p'],2).' руб. ('.$r1['number'].' этап, аванс) - план на '.change_data_format($r1['p_date']);
				$data[$i]['sums'][]=$s;
			  }

			// находим все будущие платежи акт
			$sql="select number, act_financing_date as a_date, financing_act as f
			from stepGK
			where stepGK.GK_id='$gk_id' AND act_financing_date between '$cur_date' AND '$finish_date'
			order by act_financing_date ASC";
			$row1 = $this->db->_array_data($sql);
			foreach ($row1 as $r1)
			  {
			    $s=round($r1['f'],2).' руб. ('.$r1['number'].' этап, акт) - план на '.change_data_format($r1['a_date']);
				$data[$i]['sums'][]=$s;
			  }
			$i++;
		  }
     }

	if ($debug) echo pre($data);

	//скрипт для формирования EXCEL файла
    include 'finance-program_gkEXCEL.php';

	$TPL['DATA']=$data;
	$TPL['STATTITLE'] = 'Детализация финансовой справки (по госконтрактам) по подпрограмме № '.$subprogram_id;

    include TPL_CMS_STATS . 'finance-program-result_gk.php';

?>