<?php
    
    if (!isset($_POST['send'])) 
    {
        $subProgram = 'all'; 
        $measure = 'all'; 
        $signingYear = '2011';
        $measures = array();
    }    
    else
    {
        $subProgram = $_POST['pp'];
        if (empty($subProgram)) {$subProgram = "all";}
        else
        {
            $measures = $this->listMeasure($subProgram);
        }
    
        $measure = $_POST['mr'];
        if (empty($measure)) {$measure = "all";}
    
        $signingYear = $_POST['year'];
        //if (empty($signingYear)) {$signingYear = "all";}   
    }
    
    if ($subProgram != 'all')
    {
        $sp_condition = 'and m.subprogram_id = '.$subProgram;
    }
    
    if ($measure != 'all')
    {
        $m_condition = 'and m.id = '.$measure;
    }
    
    if ($signingYear != 'all')
    {
        $y_condition = 'and YEAR(gk.signing_date) = "'.$signingYear.'"';
    }
    
    $sql = sql_placeholder('
		SELECT *
		FROM
		(
		SELECT m.id mid, m.title, gk.id, DATE_FORMAT(gk.signing_date, "%d.%m.%Y") signing_date, gk.number, bgk.cifer, gk.work_title, gk.matching_organization, o.full_title 
		FROM `GK` gk, measure m, bidGK bgk, applicant_organization o  
		WHERE m.id=gk.`measure_id` 
		AND o.id=gk.id_org_ind
		AND bgk.id=gk.`bidGK_id`
		'.$sp_condition.'
		'.$m_condition.'
		'.$y_condition.'
		) tab0
		
		LEFT JOIN
		(
		SELECT gk.id gkid, SUM(sgk.price) sum
		FROM stepGK sgk, GK gk  
		WHERE sgk.GK_id=gk.id
		GROUP BY gk.id
		) tab1
		ON tab0.id=tab1.gkid
		
		LEFT JOIN
		(
		SELECT COUNT(po.id) orders_amount, gk.id gkid2
		FROM payment_order po, stepGK sgk, GK gk  
		WHERE sgk.id=po.stepGK_id
		AND sgk.GK_id=gk.id
		GROUP BY gk.id
		) tab2		
		ON tab0.id=tab2.gkid2
		
		LEFT JOIN
		(
		SELECT DATE_FORMAT(MAX(sgk.finish_date), "%d.%m.%Y") finish_date, gk.id gkid3
		FROM stepGK sgk, GK gk  
		WHERE sgk.GK_id=gk.id
		GROUP BY gk.id
		ORDER BY sgk.finish_date
		) tab3		
		ON tab0.id=tab3.gkid3
	');
	
    $gk_data = $this->db->_array_data($sql);
	//print_r($sql);
	if (!empty($gk_data))
    {
    	$TPL['DATA'] = json_encode($gk_data);    
    }

    $TPL['SUBPROGRAM'] = $this->listSubprogram();
    $TPL['MEASURES'] = $measures; 
	$TPL["YEAR"]=listYears2('year', '2011', '2016', $signingYear);
    $TPL['SELECTED_SUBPROGRAM'] = $subProgram;
    $TPL['SELECTED_MEASURE'] = $measure;
    
    include TPL_CMS_GK . 'itemization.php';

?>

