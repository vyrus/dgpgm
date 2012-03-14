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
		if ($_REQUEST['act']==1) {
			$sql=sql_placeholder("delete from ?#FK_PAYMENT_ORDER where stepGK_id=?", $id);
			$this->db->query($sql);
			$sql=sql_placeholder("delete from ?#FK_STEPGK where id=?", $id);
			$this->db->query($sql);
		} elseif ($_REQUEST['act']==2) {
			$sql=sql_placeholder("delete from ?#FK_PAYMENT_ORDER where id=?", $id);
			$this->db->query($sql);
		}
	  }
	  
	if (isset($_REQUEST['save']))
      {
	    $row['number']=$_REQUEST['number'];
		$row['signing_date']=change_data_format_revers($_REQUEST['signing_date']);
		$row['VAT']=$_REQUEST['VAT'];
		$row['status_id']=$_REQUEST['status'];
		$row['work_title']=$_REQUEST['work_title'];
		$row['work_director']=$_REQUEST['work_director'];
		$row['e_mail']=$_REQUEST['e_mail'];
		$row['phone']=$_REQUEST['phone'];
		$row['id_org_ind']=$_REQUEST['id_org_ind'];
		$row['matching_organization']=$_REQUEST['matching_organization'];
		$sql=sql_placeholder('update ?#FK_GK set ?% where id=? ', $row, $gk_id);
		if ($this->db->query($sql)) {
			$_TPL['ERROR'][] = 'Данные сохранены';
		}
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
    $r = $this->db->select_row($sql);
	$data['measure_id']=$r['m_id'];
	$data['measure_title']=$r['m_title'];
	$data['tender_notice_num']=$r['n_num'];
	$data['tender_notice_date']=change_data_format(substr($r['n_date'],0,11));
	
	// данные по организации
	$sql="select GK.id_org_ind as org_id, org.full_title as f_title from applicant_organization org, GK 
		where GK.id=$gk_id AND GK.id_org_ind=org.id";
	$sql = sql_placeholder($sql);
    $r = $this->db->select_row($sql);
	$data['full_org_title']=$r['f_title'];
	$data['org_id']=$r['org_id'];
	
	// данные по заявке
	$sql="select b.id as b_id, b.cifer as bid_cifer from bidGK b, GK 
		where GK.id=$gk_id AND GK.bidGK_id=b.id";
	$sql = sql_placeholder($sql);
    $r = $this->db->select_row($sql);
	$data['bid_cifer']=$r['bid_cifer'];
	$data['b_id']=$r['b_id'];
	
    // данные по статье расходов
	$sql=sql_placeholder("select w.title as work_title from ?#FK_WORK_KIND w, ?#FK_GK 
		where GK.id=? AND GK.work_kind_id=w.id", $gk_id);
    $r = $this->db->select_row($sql);
	$data['work_kind_title']=$r;
	
	// данные по самому госконтракту
	$sql="select number, matching_organization, signing_date, VAT, status_id, work_title, work_director, e_mail, phone from GK, status 
		where GK.id=$gk_id";
	$sql = sql_placeholder($sql);
    $r = $this->db->select_row($sql);
	$data['number']=$r['number'];
	$data['signing_date']=change_data_format($r['signing_date']);
	$data['VAT']=$r['VAT'];
	$data['status']=$r['status_id'];
	$data['work_title']=$r['work_title'];
    $data['matching_organization']=$r['matching_organization'];	
	if ($r['work_director']=="null") $data['work_director']=''; else $data['work_director']=$r['work_director'];
	if ($r['e_mail']=="null") $data['e_mail']=''; else $data['e_mail']=$r['e_mail'];
	if ($r['phone']=="null") $data['phone']=''; else $data['phone']=$r['phone'];

	// получение данных по этапам госконтракта
	$sql=sql_placeholder("select
			s.id as id,
			s.price as plan_price,
			s.start_date as start_date,
			s.presentation_date as presentation_date,
			s.finish_date as finish_date,
			s.price as price,
			s.prepayment_percent as prepayment_percent,
			s.integration_date as integration_date,
			s.number as number
		from
			?#FK_GK GK, ?#FK_STEPGK s
		where
			GK.id=s.GK_id AND
			GK.id=?
		ORDER BY s.number ASC", $gk_id);
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