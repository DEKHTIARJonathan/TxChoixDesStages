<?php
	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/checkassist.php';
	require_once $root.'/inc/dbconnect.php';
	require_once 'excel_reader2.php';
	
	error_reporting(E_ALL ^ E_NOTICE);

	$typeStage = "TN09";
	
	$data = new Spreadsheet_Excel_Reader("files/importTN10.xls",true,"ISO-8859-1");

	$numrow = $data->rowcount($sheet_index=0);
	
	$stmt = $connexion->prepare("INSERT INTO  stages (idStage, numSerie, titreStage, nomEtudiant, nomEntreprise, pays, ville, departement, uv, descriptionComplete) VALUES (NULL , :numSerie, :titre, :etudiant, :entreprise,  :pays, :ville, :departement, :stage,  :description)");
  
	$stmt->bindParam(':titre', $titreStage);
	$stmt->bindParam(':etudiant', $nomEtudiant);
	$stmt->bindParam(':entreprise', $nomEntreprise);
	$stmt->bindParam(':pays', $pays);
	$stmt->bindParam(':description', $descriptionComplete);
	$stmt->bindParam(':stage', $typeStage);
	$stmt->bindParam(':ville', $ville);
	$stmt->bindParam(':departement', $departement);
	$stmt->bindParam(':numSerie', $numSerie);

	for ($i=2; $i <= $numrow; $i++)
	{
		$titreStage	= mb_convert_encoding($data->val($i,20),"UTF-8", "ISO-8859-1");
		$nomEtudiant = mb_convert_encoding($data->val($i,1),"UTF-8", "ISO-8859-1");
		$nomEntreprise = mb_convert_encoding($data->val($i,18),"UTF-8", "ISO-8859-1");
		$pays = mb_convert_encoding($data->val($i,17),"UTF-8", "ISO-8859-1");
		$descriptionComplete = mb_convert_encoding($data->val($i,21),"UTF-8", "ISO-8859-1");
		$numSerie = mb_convert_encoding($data->val($i,30),"UTF-8", "ISO-8859-1");
		$ville = mb_convert_encoding($data->val($i,15),"UTF-8", "ISO-8859-1");
		$departement = mb_convert_encoding($data->val($i,16),"UTF-8", "ISO-8859-1");
		
		$stmt->execute();
	}
?>