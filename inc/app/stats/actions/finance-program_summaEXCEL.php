<?
/* формирование эксель файла с отчетом по общей финансовой справке */
	$fpath='/var/www/dgpgm/files/excel/finance_summa_detail.xls';
	if (file_exists($fpath) ) {
		unlink ($fpath);
	}
	// Include PEAR::Spreadsheet_Excel_Writer
	require_once "Spreadsheet/Excel/Writer.php";
	// Create an instance, passing the filename to create
	$xls =& new Spreadsheet_Excel_Writer($fpath);

	$xls->setVersion(8);

    // Add a worksheet to the file, returning an object to add data to
	$cart =& $xls->addWorksheet('Finance summa detail report');
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
	$row = array('Детализация финансовой справки (финансирование по месяцам) по подпрограмме №'.$_GET['subprogram_id'],
		'','','','','','','','','','','','','');
	$cart->writeRow(1,0,$row,$titleFormat);
	$cart->mergeCells(1,0,1,13);
	$cart->setRow(1,30);
	$cart->setColumn(0,0,10);
	$cart->setColumn(1,1,50);
	$cart->setColumn(2,13,10);

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
	$row = array('№ п/п','Мероприятие','Финансирование на текущий год (план)','','','','','','','','','','','');
	$cart->writeRow(2,0,$row,$coltitleFormat);
	$cart->mergeCells(2,2,2,13);
	$row = array('','','Январь','Феврвль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
	$cart->writeRow(3,0,$row,$coltitleFormat);
	$cart->mergeCells(2,0,3,0);
	$cart->mergeCells(2,1,3,1);
	// заморозка верхних строк таблицы
	$freeze = array(4,0);
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

	$currow=4;
	foreach ($data as $data_element) {		$row = array($currow-3,'',
		$data_element['sums']['1']['value'],$data_element['sums']['2']['value'],$data_element['sums']['3']['value'],
		$data_element['sums']['4']['value'],$data_element['sums']['5']['value'],$data_element['sums']['6']['value'],
		$data_element['sums']['7']['value'],$data_element['sums']['8']['value'],$data_element['sums']['9']['value'],
		$data_element['sums']['10']['value'],$data_element['sums']['11']['value'],$data_element['sums']['12']['value']);
		$cart->writeRow($currow,0,$row,$colFormat);
		$cart->write($currow,1,$data_element['id'].'. '.$data_element['title'],$colFormat1);
		$currow++;
	}
	$xls->close();

	/* конец формирования эксель файла с отчетом по общей финансовой справке */
?>