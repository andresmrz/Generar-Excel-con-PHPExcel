<?php

	require '../lb/PHPExcel/PHPExcel.php';

	$columnas = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
	$fila = 1;
	$columna = 0;

	$descripcion = $_POST['formulario-generar-excel-descripcion'];
	$hoja = $_POST['formulario-generar-excel-hoja'];
	$titulo = strtoupper($_POST['formulario-generar-excel-titulo']);
	$tituloTabla = $_POST['formulario-generar-excel-tituloTabla'];
	$contenido = $_POST['formulario-generar-excel-contenido'];
	$nombre = $_POST['formulario-generar-excel-nombre'];
	$autor = $_POST['formulario-generar-excel-autor'];

	$objeto = new PHPExcel();

	$objeto->getProperties()
	->setCreator($autor)
	->setLastModifiedBy($autor)
	->setTitle('Reporte')
	->setDescription($descripcion)
	->setKeywords('reporte')
	->setCategory('reporte');

	$styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN,'color' => array('rgb' => '000000'),),),);

	$objeto->setActiveSheetIndex(0);
	$objeto->getActiveSheet()->setTitle($hoja);

	for($col = 'A'; $col !== 'Z'; $col++)
	{
    	$objeto->getActiveSheet()->getColumnDimension($col)->setWidth(15);
    }

	$objeto->getActiveSheet()->getColumnDimension('O')->setWidth(25);

	$datos = explode('**',$titulo);
	$cantColumn = intval($datos[1]);

	if(trim($datos[0]) != '')
	{
		$objeto->getActiveSheet()->calculateColumnWidths();

		$objeto->getActiveSheet()->setCellValue($columnas[$columna].$fila,$datos[0]);
		$objeto->getActiveSheet()->getStyle($columnas[$columna].$fila)->applyFromArray($styleArray);
		$objeto->getActiveSheet()->mergeCells($columnas[$columna].$fila.':'.$columnas[$columna + intval($datos[1]) - 1].$fila);
		$objeto->getActiveSheet()->getStyle($columnas[$columna].$fila.':'.$columnas[$columna + intval($datos[1]) - 1].$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objeto->getActiveSheet()->getStyle($columnas[$columna].$fila.':'.$columnas[$columna + intval($datos[1]) - 1].$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('19A7E4');
		$objeto->getActiveSheet()->getStyle('A1')->getFont()->setSize(28);
		$objeto->getActiveSheet()->getRowDimension('1')->setRowHeight(32);

		$fila++;
	}


	$datos = explode(';;',$tituloTabla);

	for($i = 0;$i < sizeof($datos);$i++)
	{
		$filas = explode('++',$datos[$i]);

		for($j = 0;$j < sizeof($filas);$j++)
		{
			$datofila2 = explode('**',$filas[$j]);
			$datofila = explode('-',$datofila2[1]);
			$objeto->getActiveSheet()->setCellValue($columnas[$columna].$fila,$datofila2[0]);
			$objeto->getActiveSheet()->mergeCells($columnas[$columna].$fila.':'.$columnas[$columna + intval($datofila[1]) - 1].($fila + intval($datofila[0]) - 1));

			$objeto->getActiveSheet()->getStyle($columnas[$columna].$fila.':'.$columnas[$columna + intval($datofila[1]) - 1].$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objeto->getActiveSheet()->getStyle($columnas[$columna].$fila.':'.$columnas[$columna + intval($datofila[1]) - 1].$fila)->applyFromArray($styleArray);

			if($i === 0)
			{
				$objeto->getActiveSheet()->getStyle($columnas[$columna].$fila.':'.$columnas[$columna + intval($datofila[1]) - 1].$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('ffd600');
			}
			else
			{
				$objeto->getActiveSheet()->getStyle($columnas[$columna].$fila.':'.$columnas[$columna + intval($datofila[1]) - 1].$fila)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('d5f3c4');

			}

			$columna += intval($datofila[1]);
		}
		$fila++;
		$columna = 0;
	}


	$datos = explode(';;',$contenido);

	for($i = 0;$i < sizeof($datos);$i++)
	{
		$filas = explode('++',$datos[$i]);

		for($j = 0;$j < sizeof($filas);$j++)
		{
			$datofila = explode('**',$filas[$j]);
			$objeto->getActiveSheet()->setCellValue($columnas[$columna].$fila,$datofila[0]);
			$objeto->getActiveSheet()->mergeCells($columnas[$columna].$fila.':'.$columnas[$columna + intval($datofila[1]) - 1].$fila);

			$objeto->getActiveSheet()->getStyle($columnas[$columna].$fila.':'.$columnas[$columna + intval($datofila[1]) - 1].$fila)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$objeto->getActiveSheet()->getStyle($columnas[$columna].$fila.':'.$columnas[$columna + intval($datofila[1]) - 1].$fila)->applyFromArray($styleArray);

			$columna += intval($datofila[1]);
		}
		$fila++;
		$columna = 0;
	}

	$objeto->getActiveSheet()->getStyle('O')->getNumberFormat()->setFormatCode('YYYY\-MM\-DD');

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="'.$nombre.'.xlsx"');
	header('Cache-Control: max-age=0');

	$writer = PHPExcel_IOFactory::createWriter($objeto,'Excel2007');
	$writer->save('php://output');

?>