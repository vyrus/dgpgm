<?
	$gk = $_GET['gk_id'];
	$TPL['gk_id'] = $gk;

	$sql = sql_placeholder('
		SELECT t.id
		FROM `GK` gk, bidGK bgk,  lot l, tender t
		WHERE gk.bidGK_id = bgk.id
		AND bgk.lot_id = l.id
		AND l.tender_id = t.id
		AND gk.id='.$gk.'
	');
	$tender_id = $this->db->_array_data($sql);
	$tender_id = $tender_id[0]['id'];

	/*saving data*/
	if ($_POST['send'])
	{
		$sql = sql_placeholder('
			UPDATE tender
			SET measure_id = ?, 
			work_kind_id = ?,
			tender_kind_id = ?,
			notice_num = ?,
			notice_date = ?,
			title = ?,
			envelope_opening_date = ?,
			review_bid_date = ?,
			estimation_date = ?,
			protocol_number = ?,
			protocol_date = ?
			WHERE id = ?',
			$_POST['measure_id'], $_POST['work_kind_id'], $_POST['tender_kind_id'], $_POST['notice_num'],
			$_POST['notice_date'], $_POST['title'], $_POST['envelope_opening_date'], 
			$_POST['review_bid_date'], $_POST['estimation_date'], $_POST['protocol_number'], 
			$_POST['protocol_date'], $tender_id
		);	
		$this->db->query($sql);
		
		$sql = sql_placeholder('
			SELECT l.id
			FROM `GK` gk, bidGK bgk,  lot l
			WHERE gk.bidGK_id = bgk.id
			AND bgk.lot_id = l.id
			AND gk.id='.$gk.'
		');
		$lot_id = $this->db->_array_data($sql);		
		$sql = sql_placeholder('
			DELETE FROM lot_price
			WHERE lot_id = '.$lot_id[0]['id'].' 
		');
		$this->db->query($sql);
	
		$rows = array();
		$step_number = 1;
		
		foreach ($_POST['year'] as $y)
		{
			$rows[$step_number]['price'] = $_POST['price'][$step_number-1];
			$rows[$step_number]['year'] = $_POST['year'][$step_number-1];
			$rows[$step_number]['step_number'] = $step_number;
			$rows[$step_number]['lot_id'] = $lot_id[0]['id'];

			$this->db->addrow("lot_price", $rows[$step_number]);
			$step_number++;			
		}

	}
	/*eo saving data*/
	
	$sql = sql_placeholder('
		SELECT sp.id spid, t.measure_id,t.title ttitle,t.notice_date, 
			   m.title mtitle, tk.title tktitle, wk.title wktitle, 
			   t.envelope_opening_date, t.review_bid_date,
			   t.estimation_date, t.protocol_number,
			   t.protocol_date, t.notice_num, t.work_kind_id, t.tender_kind_id
		FROM `tender` t, measure m, tender_kind tk, work_kind wk, subprogram sp
		WHERE t.id = '.$tender_id.'
		AND m.id = t.measure_id
		AND tk.id = t.tender_kind_id
		AND wk.id = t.work_kind_id 
		AND sp.id = m.subprogram_id
	');
	$tender_data = $this->db->_array_data($sql);
	$TPL['tender_data'] = $tender_data[0];

	$sql = sql_placeholder('
		SELECT `title`,`id` FROM `tender_kind`
	');
	$kinds = $this->db->_array_data($sql);
	$TPL['kinds'] = $kinds;
		
	$notice_date_time = explode(" ",$tender_data[0]['notice_date']);
	$TPL['late'] = isset($tender_data[0]['notice_date']) && ($notice_date_time[0] <= date('Y-m-d'));

	if (!$TPL['late'])
	{
		$measures = $this->listAllMeasures();
		$TPL['measures'] = $measures;

		$sql = sql_placeholder('
			SELECT id, `title` FROM `work_kind`
		');
		$w_kinds = $this->db->_array_data($sql);
		$TPL['w_kinds'] = $w_kinds;
	} 

    $sql = sql_placeholder('
		SELECT lp.price, lp.year, lp.step_number
		FROM `GK` gk, bidGK bgk,  lot l, lot_price lp
		WHERE gk.bidGK_id = bgk.id
		AND bgk.lot_id = l.id
		AND lp.lot_id = l.id
		AND gk.id = '.$gk.'
		ORDER BY lp.step_number
		');

	$step_data = $this->db->_array_data($sql);
	$TPL['step_data'] = $step_data; 

    include TPL_CMS_GK . 'tender.php';
?>	