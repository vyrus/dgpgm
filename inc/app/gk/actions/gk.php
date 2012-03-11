<?

//	if (USER_GROUP == 5) {
  //  if ($_POST) {
    /*chosen gk*/
    $gk_id = $_REQUEST['gk_id'];
	
	$debug=true;
	function pre($var) {
        $crlf = "\r\n";
        return '<!--' . $crlf . 
                   print_r($var, true) . $crlf . 
               '-->' . $crlf;
    }

	$data= array ();
	// данные по конкурсу и мероприятию
	$sql="select m.id as m_id, m.title as m_title, t.notice_num as n_num, t.notice_date as n_date 
	from measure m, GK, tender t, bidGK b, lot l
	where GK.id=$gk_id AND GK.measure_id=m.id AND 
	GK.bidGK_id=b.id AND b.lot_id=l.id AND l.tender_id=t.id";
	$sql = sql_placeholder($sql);
    $rows_1 = $this->db->_array_data($sql);
	$r=$rows_1[0];
	$data['measure_id']=$r['m_id'];
	$data['measure_title']=$r['m_title'];
	$data['tender_notice_num']=$r['n_num'];
	$data['tender_notice_date']=$r['n_date'];
	
	// данные по организации
	$sql="select org.full_title as f_title from applicant_organization org, GK 
		where GK.id=$gk_id AND GK.id_org_ind=org.id";
	$sql = sql_placeholder($sql);
    $rows_1 = $this->db->_array_data($sql);
	$r=$rows_1[0];
	$data['full_org_title']=$r['f_title'];
	
	// данные по заявке
	$sql="select b.cifer as bid_cifer from bidGK b, GK 
		where GK.id=$gk_id AND GK.bidGK_id=b.id";
	$sql = sql_placeholder($sql);
    $rows_1 = $this->db->_array_data($sql);
	$r=$rows_1[0];
	$data['bid_cifer']=$r['bid_cifer'];
	
    // данные по статье расходов
	$sql="select w.title as work_title from work_kind w, GK 
		where GK.id=$gk_id AND GK.work_kind_id=w.id";
	$sql = sql_placeholder($sql);
    $rows_1 = $this->db->_array_data($sql);
	$r=$rows_1[0];
	$data['work_title']=$r['work_title'];
	
	// данные по самому госконтракту
	$sql="select number, signing_date, VAT, title, work_title, work_director, e_mail, phone from GK, status 
		where GK.id=$gk_id AND GK.status_id=status.id";
	$sql = sql_placeholder($sql);
    $rows_1 = $this->db->_array_data($sql);
	$r=$rows_1[0];
	$data['number']=$r['number'];
	$data['signing_date']=$r['signing_date'];
	$data['VAT']=$r['VAT'];
	$data['status']=$r['title'];
	$data['work_title']=$r['work_title'];
	$data['work_director']=$r['work_director'];
	$data['e_mail']=$r['e_mail'];
	$data['phone']=$r['phone'];
	
	// получение данных по этапам госконтракта
	$sql="select lp.price as plan_price, s.start_date as start_date,
          s.presentation_date as presentation_date, s.finish_date as finish_date,
		  s.price as price, s.prepayment_percent as prepayment_percent, 
		  s.integration_date as integration_date, s.number as number
	      from GK, stepGK s, bidGK b, lot l, lot_price lp 
		  where GK.id=s.GK_id AND GK.id=$gk_id AND GK.bidGK_id=b.id AND b.lot_id=l.id 
		  AND l.id=lp.lot_id AND lp.step_number=s.number
		  ORDER BY s.number ASC";
	$sql = sql_placeholder($sql);
    $rows_1 = $this->db->_array_data($sql);
	if ($debug) {
        echo pre($sql);
        echo pre($rows_1);
    }
	$data['steps']= array ();
	foreach ($rows_1 as $r)
	  {
		$data['steps'][]=$r;
	  }
	
	// получение данных по платежкам
	$sql="select s.number as number, p.number as p_number, p.date as p_data,
		  p.type as p_type, p.sum as p_sum, p.status as p_status
		  from stepGK s, payment_order p
		  where s.id=p.stepGK_id AND s.GK_id=$gk_id
		  ORDER BY s.number ASC";
	$sql = sql_placeholder($sql);
    $rows_1 = $this->db->_array_data($sql);
	$data['payments']= array ();
	foreach ($rows_1 as $r)
	  {
		$data['payments'][]=$r;
	  }
	
	//print_r($data,true);
	
	
	$TPL['gk_id'] = $gk_id;
	$TPL['DATA'] = $data;
    $TPL['STATTITLE'] = 'Данные госконтракта';
	include TPL_CMS_GK."gk-result.php";
  //  }
    
   // end Post
	/* 
    } else {
    		include TPL_CMS_STATS."no-rights.php";
    }
    */
?>