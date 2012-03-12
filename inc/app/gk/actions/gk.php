<?

//	if (USER_GROUP == 5) {
  //  if ($_POST) {
    /*chosen gk*/
    function change_data_format_revers($date)
    {
	  $values=explode('.',$date);
	  $d=date("Y-m-d",mktime(0,0,0,$values[1],$values[0],$values[2]));
	  return $d;
	}
	
	$gk_id = $_REQUEST['gk_id'];
	
	if (isset($_REQUEST['id']))
	  {
	    $id = $_REQUEST['id'];
		if ($_REQUEST['act']==1) $sql="delete from stepGK where id=$id";
		elseif ($_REQUEST['act']==2) $sql="delete from payment_order where id=$id";
		$this->db->query($sql);
	  }
	  
	if (isset($_REQUEST['save']))
      {
	    $number=$_REQUEST['number'];
		$signing_date=change_data_format_revers($_REQUEST['signing_date']);
		$VAT=$_REQUEST['VAT'];
		$status=$_REQUEST['status'];
		$work_title=$_REQUEST['work_title'];
		$work_director=$_REQUEST['work_director'];
		$e_mail=$_REQUEST['e_mail'];
		$phone=$_REQUEST['phone'];
		$sql="update GK 
		set number='$number', signing_date='$signing_date', VAT='$VAT',
		status_id=$status, work_title='$work_title', work_director='$work_director',
		e_mail='$e_mail', phone='$phone'
		where id=$gk_id
		";
		$this->db->query($sql);
	  }
	
	$debug=true;
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
	$data['tender_notice_date']=change_data_format(substr($r['n_date'],0,11));
	
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
	$data['work_kind_title']=$r['work_title'];
	
	// данные по самому госконтракту
	$sql="select number, signing_date, VAT, status_id, work_title, work_director, e_mail, phone from GK, status 
		where GK.id=$gk_id";
	$sql = sql_placeholder($sql);
    $rows_1 = $this->db->_array_data($sql);
	$r=$rows_1[0];
	$data['number']=$r['number'];
	$data['signing_date']=change_data_format($r['signing_date']);
	$data['VAT']=$r['VAT'];
	$data['status']=$r['status_id'];
	$data['work_title']=$r['work_title'];
	if ($r['work_director']=="null") $data['work_director']=''; else $data['work_director']=$r['work_director'];
	if ($r['e_mail']=="null") $data['e_mail']=''; else $data['e_mail']=$r['e_mail'];
	if ($r['phone']=="null") $data['phone']=''; else $data['phone']=$r['phone'];

if ($debug) {
        echo pre($sql);
        echo pre($data);
    }

	
	// получение данных по этапам госконтракта
	$sql="select s.id as id, lp.price as plan_price, s.start_date as start_date,
          s.presentation_date as presentation_date, s.finish_date as finish_date,
		  s.price as price, s.prepayment_percent as prepayment_percent, 
		  s.integration_date as integration_date, s.number as number
	      from GK, stepGK s, bidGK b, lot l, lot_price lp 
		  where GK.id=s.GK_id AND GK.id=$gk_id AND GK.bidGK_id=b.id AND b.lot_id=l.id 
		  AND l.id=lp.lot_id AND lp.step_number=s.number
		  ORDER BY s.number ASC";
	$sql = sql_placeholder($sql);
    $rows_1 = $this->db->_array_data($sql);
	
	$data['steps']= array ();
	foreach ($rows_1 as $r)
	  {
		$data['steps'][]=$r;
	  }
	
	// получение данных по платежкам
	$sql="select p.id as id, s.number as number, p.number as p_number, p.date as p_data,
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
	
	// получение всех статусов
	$sql="select id, title from status";
	$sql = sql_placeholder($sql);
    $rows_1 = $this->db->_array_data($sql);
	$data['statuses']= array ();
	foreach ($rows_1 as $r)
	  {
		$data['statuses'][]=$r;
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