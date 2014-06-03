<?php
	header("Content-Type: text/html; charset=UTF-8"); 
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
	date_default_timezone_set("Europe/Paris");

	include $root.'/Classes/PHPExcel.php';
	include	$root.'/Classes/PHPExcel/Writer/Excel2007.php';

    require_once $root.'/config.inc.php';
    require_once $root.'/inc/dbconnect.php';

    /*for($i = 'a'; $i <= 'z'; $i++) 
		print "$i ";
	*/

	// Cherche le stage le plus demandé pour déterminer l'offset global de la partie descriptive du stage
	$stmt = $connexion->prepare("select stage, count(*) as 'max_demand' FROM `votes` group by `stage` order by max_demand DESC limit 1");
	$stmt-> execute();
	$rslt = $stmt -> fetch();

	$offset_table =  $rslt['max_demand'];

	//Without any offset the normal position is the column F = 6
	$startColumn_stageDesc = 6 + $offset_table;

	$workbook = new PHPExcel;

	$sql = 'SELECT CONCAT( `firstName`, " ", `lastName`) as name FROM `users`, (SELECT distinct `login` FROM `votes`) as logs where `logs`.`login` = `users`.`casLogin`';

	$i = 2;
	$sheet = $workbook->getActiveSheet();
		 	
	/* Reglage des Headers de colonnes. */
	$sheet->setCellValue('A1',"Nom du Suiveur");
	$sheet->setCellValue('B1',"Nbr Stages attribués");
	$sheet->setCellValue('D1',"Numéro du stage");

	$j=5;
	while($j < $startColumn_stageDesc-1){
		$sheet->getColumnDimension(num_to_letter($j, true))->setAutoSize(true);
		$j++;
	}

	$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true)."1","Departement & Ville");
	$sheet->getColumnDimension(num_to_letter($startColumn_stageDesc, true))->setAutoSize(true);
	$startColumn_stageDesc++;

	$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true)."1","Entreprise");
	$sheet->getColumnDimension(num_to_letter($startColumn_stageDesc, true))->setAutoSize(true);
	$startColumn_stageDesc++;
	
	$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true)."1","Etudiant");
	$sheet->getColumnDimension(num_to_letter($startColumn_stageDesc, true))->setAutoSize(true);
	$startColumn_stageDesc++;

	$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true)."1","Type de Stage");
	$sheet->getColumnDimension(num_to_letter($startColumn_stageDesc, true))->setAutoSize(true);

	$sheet->getStyle('A1:Z1')->getFont()->setBold(true);

	/* Remplissage des données par Suiveur */

	$stmt = $connexion->prepare('SELECT count(*)+1 as lastLigne FROM `stages`');
	$stmt-> execute();
	$rslt = $stmt -> fetch();

	$lastLigne =  $rslt['lastLigne'];

	foreach  ($connexion->query($sql) as $row) {
		$sheet->setCellValue('A'.$i, $row['name']);
		$sheet->setCellValue('B'.$i, "=COUNTIF(E2:E".$lastLigne.", A".$i.")");
		$i++;
	}	

	/* Remplissage des données par sujet de stage */

	$sql = 'SELECT `idStage` as id, `numSerie`, ville, departement as dpt, `nomEtudiant` as etudiant, `nomEntreprise` as entreprise, `uv`, pays FROM `stages`';

	$i = 2;

	foreach  ($connexion->query($sql) as $row) {
		$ville = $row['ville'];
		$pays = $row['pays'];
		$dpt = $row['dpt'];
		$etudiant = $row['etudiant'];
		$entreprise = $row['entreprise'];
		$numSerie = $row['numSerie'];
		$uv = $row['uv'];
		$id = $row['id'];

		if (strtolower($pays) == "france")
            $city = $ville."(".$dpt.")";
        else
            $city = $pays; 

		$sheet->setCellValue('D'.$i, $numSerie);
		
		// On renseigne les détails du stage
		$startColumn_stageDesc = 6 + $offset_table;

		$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true).$i, $city);
		$startColumn_stageDesc++;
		$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true).$i, $entreprise);
		$startColumn_stageDesc++;
		$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true).$i, $etudiant);
		$startColumn_stageDesc++;
		$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true).$i, $uv);

		// On renseigne les demandeurs du stage

		$sth = $connexion->prepare('SELECT concat(`firstName`, " ", `lastName`) as name FROM `votes`, `users` WHERE `votes`.`login` = `users`.`casLogin` and `votes`.`stage` = :stage order by voteDate');
		$sth->bindParam(':stage', $id);
		$sth-> execute();

		$j = 0;
	    while ($user = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			$sheet->setCellValue(num_to_letter(5+$j, true).$i, $user['name']);
			$j++;
	    }
	    
		$i++;
		
	}


	$sheet->getColumnDimension('A')->setAutoSize(true);
	$sheet->getColumnDimension('B')->setWidth(17);
	$sheet->getColumnDimension('D')->setWidth(14.5);
	$writer = new PHPExcel_Writer_Excel2007($workbook);

	$records = './files/export.xlsx';

	$writer->save($records);

	echo 'Le fichier Excel peut-être téléchargé en cliquant sur ce lien : <a href="files/export.xlsx"><b>Télécharger</b></a>';


	function num_to_letter($num, $uppercase = FALSE)
	{
		$num -= 1;
		
		$letter = 	chr(($num % 26) + 97);
		$letter .= 	(floor($num/26) > 0) ? str_repeat($letter, floor($num/26)) : '';
		return 		($uppercase ? strtoupper($letter) : $letter); 
	}

?>