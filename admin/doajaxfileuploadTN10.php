<?php

	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/checkassist.php';

	$error = "";
	$msg = "";
	$fileElementName = 'excelInputTN10';
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'TN10 / The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'TN10 / The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'TN10 / The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'TN10 / No file was uploaded.';
				break;

			case '6':
				$error = 'TN10 / Missing a temporary folder';
				break;
			case '7':
				$error = 'TN10 / Failed to write file to disk';
				break;
			case '8':
				$error = 'TN10 / File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'TN10 / No error code avaiable';
		}
	
	} elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
		$error = 'TN10 / No file was uploaded..';
	
	else 
	{
			
			
			$allowed =  array('xls','xlsx', 'XLS', 'XLSX');
			$filename = $_FILES[$fileElementName]['name'];
			
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			if(!in_array($ext,$allowed) )
    			$error = 'TN10 / File extension is not XLS or XLSX';
			else 
			{
				$msg .= "TN10 / File Name: " . $_FILES[$fileElementName]['name'] . ", ";
				$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
				
				$chemin_destination = $root.'/admin/files/';     
				move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $chemin_destination.'importTN10.'.$ext);
			}

			include 'dbTN10.php';

	}	

	echo 
	"{
		error: '" . $error . "',
		msg: '" . $msg . "'
	}";


?>