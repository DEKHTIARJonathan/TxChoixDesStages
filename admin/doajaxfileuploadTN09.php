<?php

	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';

	$error = "";
	$msg = "";
	$fileElementName = 'excelInputTN09';
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'TN09 / The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'TN09 / The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'TN09 / The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'TN09 / No file was uploaded.';
				break;

			case '6':
				$error = 'TN09 / Missing a temporary folder';
				break;
			case '7':
				$error = 'TN09 / Failed to write file to disk';
				break;
			case '8':
				$error = 'TN09 / File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'TN09 / No error code avaiable';
		}
	
	} elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
		$error = 'TN09 / No file was uploaded..';
	
	else 
	{
			
			$allowed =  array('xls','xlsx', 'XLS', 'XLSX');
			$filename = $_FILES[$fileElementName]['name'];
			
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			if(!in_array($ext,$allowed) )
    			$error = 'TN09 / File extension is not XLS or XLSX';
			else 
			{
				$msg .= "TN09 / File Name: " . $_FILES[$fileElementName]['name'] . ", ";
				$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
				
				$chemin_destination = $root.'/admin/files/';     
				move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $chemin_destination.'importTN09.'.$ext);
			}

			include 'dbTN09.php';

	}	

	echo 
	"{
		error: '" . $error . "',
		msg: '" . $msg . "'
	}";

?>