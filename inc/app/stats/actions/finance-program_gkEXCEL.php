<?
/* формирование эксель файла с отчетом по общей финансовой справке */
	$fpath='/var/www/dgpgm/files/excel/finance_gk_detail.xls';
	if (file_exists($fpath) ) {
		unlink ($fpath);
	}
	// Include PEAR::Spreadsheet_Excel_Writer
	require_once "Spreadsheet/Excel/Writer.php";
	// Create an instance, passing the filename to create
	$xls =& new Spreadsheet_Excel_Writer($fpath);

	$xls->setVersion(8);

    // Add a worksheet to the file, returning an object to add data to
	$cart =& $xls->addWorksheet('Finance gk detail report');
	$cart->setInputEncoding('UTF-8');

	// Заголовок листа
	// Создание объекта форматирования
	$titleFormat =& $xls->addFormat();
	$titleFormat->setFontFamily('Times New Roman');
	$titleFormat->setBold();
	$titleFormat->setSize('12');
	$titleFormat->setColor('navy');
	$titleFormat->setBorder(2);
	$titleFormat->setBorderColor('navy');
	$titleFormat->setHAlign('center');
	$titleFormat->setVAlign('vcenter');
	//$cart->write(1,0,$titleText,$titleFormat);
	$row = array('Детализация финансовой справки (по госконтрактам) по подпрограмме №'.$_GET['subprogram_id'],
		'','','','');
	$cart->writeRow(1,0,$row,$titleFormat);
	$cart->mergeCells(1,0,1,4);
	$cart->setRow(1,30);
	$cart->setColumn(0,0,10);
	$cart->setColumn(1,1,15);
	$cart->setColumn(2,2,75);
	$cart->setColumn(3,3,20);
	$cart->setColumn(4,4,40);

	// задание заголовков столбцов таблицы
	$coltitleFormat = & $xls->addFormat();
    $coltitleFormat->setFontFamily('Times New Roman');
	$coltitleFormat->setBold();
	$coltitleFormat->setSize('10');
	$coltitleFormat->setColor('navy');
	$coltitleFormat->setBorder(1);
	$coltitleFormat->setHAlign('center');
	$coltitleFormat->setVAlign('vcenter');
	$coltitleFormat->setBorder(1);
	$coltitleFormat->setTextWrap();
	$row = array('№ п/п','№ мероприятия','Наименование организации - победителя','№ и дата ГК','Перечисление средств');
	$cart->writeRow(2,0,$row,$coltitleFormat);
	// заморозка верхних строк таблицы
	$freeze = array(3,0);
	$cart->freezePanes($freeze);

	// вывод самих значений
	$colFormat = & $xls->addFormat();
	$colFormat->setFontFamily('Times New Roman');
	$colFormat->setSize('9');
	$colFormat->setColor('navy');
	$colFormat->setHAlign('center');
	$colFormat->setVAlign('vcenter');
	$colFormat->setTextWrap();
	$colFormat->setBorder(1);

	$colFormat1 = & $xls->addFormat();
	$colFormat1->setFontFamily('Times New Roman');
	$colFormat1->setSize('9');
	$colFormat1->setColor('navy');
	$colFormat1->setHAlign('left');
	$colFormat1->setVAlign('vcenter');
	$colFormat1->setTextWrap();
	$colFormat1->setBorder(1);

	$currow=3;
	foreach ($data as $data_element) {
		$row = array($currow-2,$data_element['id']);
		$cart->writeRow($currow,0,$row,$colFormat);
		$col = '';
		foreach ($data_element['sums'] as $data_element_element) {			$col = $col.$data_element_element.'          ';
		}
		if ($data_element['s_title'] == '') {			$row = array($data_element['f_title'],
				$data_element['number'].' от '.change_data_format($data_element['s_date']),$col);		} else {			$row = array($data_element['f_title'].' ('.$data_element['s_title'].')',
				$data_element['number'].' от '.change_data_format($data_element['s_date']),$col);		}
		$cart->writeRow($currow,2,$row,$colFormat1);
		$currow++;
	}
	$xls->close();

	/* конец формирования эксель файла с отчетом по общей финансовой справке */
?>