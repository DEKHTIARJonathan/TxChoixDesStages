<?php

	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/dbconnect.php';

	$error = "";
	$msg = "";
	$fileElementName = 'excelInputTN10';
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
				break;
			case '2':
				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				break;
			case '3':
				$error = 'The uploaded file was only partially uploaded';
				break;
			case '4':
				$error = 'No file was uploaded.';
				break;

			case '6':
				$error = 'Missing a temporary folder';
				break;
			case '7':
				$error = 'Failed to write file to disk';
				break;
			case '8':
				$error = 'File upload stopped by extension';
				break;
			case '999':
			default:
				$error = 'No error code avaiable';
		}
	
	} elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
		$error = 'No file was uploaded..';
	
	else 
	{
			
			$allowed =  array('xls','xlsx', 'XLS', 'XLSX');
			$filename = $_FILES[$fileElementName]['name'];
			
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			
			if(!in_array($ext,$allowed) )
    			$error = 'File extension is not XLS or XLSX';
			else 
			{
				$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
				$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
				
				$chemin_destination = $root.'/admin/files/';     
				move_uploaded_file($_FILES[$fileElementName]['tmp_name'], $chemin_destination.'importTN10.'.$ext);
			}

			updateDB();

	}	

	echo "{";
	echo				"error: TN10 / '" . $error . "',\n";
	echo				"msg: TN10 / '" . $msg . "'\n";
	echo "}";

	function updateDB()
	{
		error_reporting(E_ALL ^ E_NOTICE);
		require_once 'excel_reader2.php';
		$data = new Spreadsheet_Excel_Reader("files/importTN10.xls",true,"ISO-8859-1");

		$numrow = $data->rowcount($sheet_index=0);
		
		$stmt = $connexion->prepare("INSERT INTO  stages (idStage ,titreStage ,nomEtudiant ,nomEntreprise ,pays ,uv ,descriptionComplete) VALUES (NULL ,  :titre,  :etudiant,  :entreprise,  :pays,  'TN10',  :description)");
	  
		$stmt->bindParam(':titre', $titreStage);
		$stmt->bindParam(':etudiant', $nomEtudiant);
		$stmt->bindParam(':entreprise', $nomEntreprise);
		$stmt->bindParam(':pays', $pays);
		$stmt->bindParam(':description', $descriptionComplete);

		for ($i=2; $i <= $numrow; $i++)
		{
			$titreStage	= mb_convert_encoding($data->val($i,20),"UTF-8", "ISO-8859-1");
			$nomEtudiant = mb_convert_encoding($data->val($i,1),"UTF-8", "ISO-8859-1");
			$nomEntreprise = mb_convert_encoding($data->val($i,18),"UTF-8", "ISO-8859-1");
			$pays = mb_convert_encoding($data->val($i,17),"UTF-8", "ISO-8859-1");
			$descriptionComplete = mb_convert_encoding($data->val($i,21),"UTF-8", "ISO-8859-1");
			
			$stmt->execute();
		}
	}
?>