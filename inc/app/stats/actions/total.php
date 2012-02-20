<?
if (true) {
//	if (USER_GROUP == 5) {
    if ($_POST) {
    /*chosen subprogtam*/
    $subProgram = $_POST['pp'];
    if (empty($subProgram)) {$subProgram = "all";}

    /*chosen dates*/
	$startYear = $_POST['start_year'];
	$finishYear = $_POST['end_year'];

    /*chosen properties*/
    $possiblePropsNames = array('tender_count'=>'Количество конкурсов (план)',
                                'gk_count'=>'Количество заключенных контрактов (план)',
                                'financing'=>'Финансирование (план), тыс.руб.',
                                'financed'=>'Профинансировано, тыс. руб.',
                                'gk_commited'=>'Заключено госконтрактов',
                                'tender_commited'=>'Проведено конкурсов');
    $reportPropsNames = array();
    foreach($possiblePropsNames as $prop_name=>$prop_title)
    {
        if (!empty($_POST[$prop_name]))
        {
            $reportPropsNames[] = $prop_name;
        }
    }

    if ($subProgram != 'all')
    {
        $sp_condition = 'and m.subprogram_id = '.$subProgram;
    }
    /*remake query because 1 sentence doesn't work*/
	$sql = sql_placeholder('
        SELECT *
        FROM (
            SELECT mp.measure_id, mp.year, (mp.financing DIV 1000) financing, 
                   mp.gk_count, mp.tender_count, sp.title spt, m.title mt
            FROM ?#FK_MEASURE_PLAN mp, ?#FK_MEASURE m, ?#FK_SUBPROGRAM sp
            WHERE mp.measure_id = m.id AND
                  m.subprogram_id = sp.id
                  ' . $sp_condition . ' AND
                  mp.year BETWEEN ? AND ?
        ) tab1

        LEFT JOIN (
            SELECT (SUM(sum) DIV 1000) financed, count(gk.number) gk_commited, 
                   gk.measure_id
            FROM `payment_order` po, stepGK sgk, GK gk
            WHERE `status` <> "отменено" AND
                  po.stepGK_id = sgk.id AND
                  sgk.GK_id = gk.id
            GROUP BY gk.measure_id
        ) tab2 
        ON (tab1.measure_id = tab2.measure_id)

        LEFT JOIN (
            SELECT count(t.title) tender_commited, t.measure_id
            FROM tender t
            WHERE /* YEAR(t.estimation_date) = tab1.year AND */ 
                  t.estimation_date < DATE_SUB(CURDATE(), INTERVAL 1 DAY)
            GROUP BY t.measure_id
        ) tab3
        ON (tab1.measure_id = tab3.measure_id)

        ORDER BY tab1.measure_id, tab1.year', $startYear, $finishYear);

    $work_steps = $this->db->_array_data($sql);
    if (!empty($work_steps))
    {
        $cur_measure = 0;
        $data = array();
        $first_measure = true;

        foreach($reportPropsNames as $prop_name)
        {
            $$prop_name = array();
        }
/*        $tender_count = array();
        $gk_count = array();
        $financing = array();
        $financed = array();
        $gk_commited = array();
        $tender_commited = array();*/

        foreach($work_steps as $measure_data)
        {
            if ($cur_measure['measure_id'] != $measure_data['measure_id'] && (!$first_measure))
            {
                /*end old measure array*/
                $data[$cur_measure['measure_id']]['title'] = $cur_measure['mt'];
//                $data[$cur_measure['measure_id']]['content'] = array($named_financing,$named_financed,$named_tender_count,$named_tender_commited,$named_gk_count,$named_gk_commited);
                $data[$cur_measure['measure_id']]['content'] = array();

                foreach($reportPropsNames as $prop_name)
                {
                    $var_name = "named_".$prop_name;
                    $$var_name = array("propTitle"=>$possiblePropsNames[$prop_name],"values"=>$$prop_name);
                    array_push($data[$cur_measure['measure_id']]['content'], $$var_name);
                }
/*
                $named_tender_count = array("propTitle"=>"Количество конкурсов (план)","values"=>$tender_count);
                $named_gk_count = array("propTitle"=>"Количество заключенных контрактов (план)","values"=>$gk_count);
                $named_financing = array("propTitle"=>"Финансирование (план), тыс.руб.","values"=>$financing);
                $named_financed = array("propTitle"=>"Профинансировано, тыс. руб.","values"=>$financed);
                $named_gk_commited = array("propTitle"=>"Заключено госконтрактов","values"=>$gk_commited);
                $named_tender_commited = array("propTitle"=>"Проведено конкурсов","values"=>$tender_commited);

                $data[$cur_measure['measure_id']]['title'] = $cur_measure['mt'];
                $data[$cur_measure['measure_id']]['content'] = array($named_financing,$named_financed,$named_tender_count,$named_tender_commited,$named_gk_count,$named_gk_commited);*/

                /*new measure*/
                $cur_measure = $measure_data;
                $data[$cur_measure['measure_id']] = array('title'=>'', 'content'=>array());
                $tender_count = array();
                $gk_count = array();
                $financing = array();
            }
            if ($first_measure)
            {
                $first_measure = false;
                $cur_measure = $measure_data;
            }
            foreach($reportPropsNames as $prop_name)
            {
                ${$prop_name}[$measure_data['year']] = $measure_data[$prop_name];
            }
/*            $tender_count[$measure_data['year']] = $measure_data['tender_count'];
            $gk_count[$measure_data['year']] = $measure_data['gk_count'];
            $financing[$measure_data['year']] = $measure_data['financing'];
            $financed[$measure_data['year']] = $measure_data['financed'];
            $gk_commited[$measure_data['year']] = $measure_data['gk_commited'];
            $tender_commited[$measure_data['year']] = $measure_data['tender_commited'];*/
        }
        /*end old measure array*/
        $data[$cur_measure['measure_id']]['title'] = $cur_measure['mt'];
//                $data[$cur_measure['measure_id']]['content'] = array($named_financing,$named_financed,$named_tender_count,$named_tender_commited,$named_gk_count,$named_gk_commited);
        $data[$cur_measure['measure_id']]['content'] = array();

        foreach($reportPropsNames as $prop_name)
        {
            $var_name = "named_".$prop_name;
            $$var_name = array("propTitle"=>$possiblePropsNames[$prop_name],"values"=>$$prop_name);
            array_push($data[$cur_measure['measure_id']]['content'], $$var_name);
        }
/*        $named_tender_count = array("propTitle"=>"Количество конкурсов (план)","values"=>$tender_count);
        $named_gk_count = array("propTitle"=>"Количество заключенных контрактов (план)","values"=>$gk_count);
        $named_financing = array("propTitle"=>"Финансирование (план), тыс.руб.","values"=>$financing);
        $named_financed = array("propTitle"=>"Профинансировано, тыс. руб.","values"=>$financed);
        $named_gk_commited = array("propTitle"=>"Заключено госконтрактов","values"=>$gk_commited);
        $named_tender_commited = array("propTitle"=>"Проведено конкурсов","values"=>$tender_commited);
        $data[$cur_measure['measure_id']]['title'] = $cur_measure['mt'];
        $data[$cur_measure['measure_id']]['content'] = array($named_financing,$named_financed,$named_tender_count,$named_tender_commited,$named_gk_count,$named_gk_commited);*/
    }

    $TPL['DATA'] = json_encode($data);
    $TPL['STATTITLE'] = 'Общая статистика по программе за '.$startYear.'-'.$finishYear.' годы';
	include TPL_CMS_STATS."total-result.php";
	} else {
    		$startYear = date('Y');
    		$start2Year = date('Y');
    		$endYear = 2016;
    		$TPL['SUBPROGRAM'] = ManagerForms::listSubprogram();
    		include TPL_CMS_STATS."total.php";
    } // end Post
	} else {
    		include TPL_CMS_STATS."no-rights.php";
    }
?>