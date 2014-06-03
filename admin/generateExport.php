<?php
	header("Content-Type: text/html; charset=UTF-8"); 
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
	date_default_timezone_set("Europe/Paris");
	//ini_set("memory_limit","512M");
	//ini_set('max_execution_time', 60);

	include $root.'/Classes/PHPExcel.php';
	include	$root.'/Classes/PHPExcel/Writer/Excel2007.php';

    require_once $root.'/config.inc.php';
    require_once $root.'/inc/dbconnect.php';

    /* Initialisation du Workbook */
    $workbook = new PHPExcel;
	$sheet = $workbook->getActiveSheet();
	
	/* Without any offset the normal position is the column F = 6 */
	$offset_table =  getMaxDemand($connexion);
	$startColumn_stageDesc = 6 + $offset_table;


	/* Reglage des Headers de colonnes. */
	setHeaders($sheet, $startColumn_stageDesc);

	/* Remplissage du nbr de stage par Suiveur avec formule pour le comptage*/
	suiveurCounting ($connexion, $sheet);

	/* Remplissage des données par sujet de stage */
	fillInStage($connexion, $sheet, $startColumn_stageDesc);

	/* Coloring same Company */
    coloringSimilar ($sheet, $startColumn_stageDesc);

	/* Sauvegarde du Workbook */
	$writer = new PHPExcel_Writer_Excel2007($workbook);
	$records = './files/export.xlsx';
	$writer->save($records);

	/* Output affichée par le script Ajax */
	echo 'Le fichier Excel peut-être téléchargé en cliquant sur ce lien : <a href="files/export.xlsx"><b>Télécharger</b></a>';


/* ========================== FUNCTIONS =========================== */


	function num_to_letter($num, $uppercase = FALSE)
	{
		$num -= 1;
		
		$letter = 	chr(($num % 26) + 97);
		$letter .= 	(floor($num/26) > 0) ? str_repeat($letter, floor($num/26)) : '';
		return 		($uppercase ? strtoupper($letter) : $letter); 
	}

	function setHeaders($sheet, $startColumn_stageDesc)
	{
		$sheet->setCellValue('A1',"Nom du Suiveur");
		$sheet->setCellValue('B1',"Nbr Stages attribués");
		$sheet->setCellValue('D1',"Numéro du stage");
		$sheet->getColumnDimension('A')->setAutoSize(true);
		$sheet->getColumnDimension('B')->setWidth(17);
		$sheet->getColumnDimension('D')->setWidth(14.5);

		$j=5;
		while($j < $startColumn_stageDesc-1){
			$sheet->setCellValue(num_to_letter($j, true)."1","Demandeur".($j-4));
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
	}

	function getMaxDemand ($connexion)
	{
		// Cherche le stage le plus demandé pour déterminer l'offset global de la partie descriptive du stage
		$stmt = $connexion->prepare("select stage, count(*) as 'max_demand' FROM `votes` group by `stage` order by max_demand DESC limit 1");
		$stmt-> execute();
		$rslt = $stmt -> fetch();

		return $rslt['max_demand'];
	}

	function getLastLine ($connexion)
	{
		$stmt = $connexion->prepare('SELECT count(*)+1 as lastLigne FROM `stages`');
		$stmt-> execute();
		$rslt = $stmt -> fetch();

		return $rslt['lastLigne'];
	}

	function suiveurCounting ($connexion, $sheet)
	{
		$lastLigne =  getLastLine($connexion);
		$i = 2;

		$sql = 'SELECT CONCAT( `firstName`, " ", `lastName`) as name FROM `users`, (SELECT distinct `login` FROM `votes`) as logs where `logs`.`login` = `users`.`casLogin`';
		foreach  ($connexion->query($sql) as $row) {
			$sheet->setCellValue('A'.$i, $row['name']);
			$sheet->setCellValue('B'.$i, '=COUNTIF(E2:E'.$lastLigne.', A'.$i.'&"*")');
			$i++;
		}	
	}

	function getCity($pays, $ville, $dpt)
	{
		if (strtolower($pays) == "france")
            return $ville."(".$dpt.")";
        else
            return $pays; 
	}

	function fillInStage($connexion, $sheet, $startColumn)
	{
		
		$sql = 'SELECT `idStage` as id, `numSerie`, ville, departement as dpt, `nomEtudiant` as etudiant, `nomEntreprise` as entreprise, `uv`, pays FROM `stages` order by ville, nomEntreprise';

		$i = 2;

		foreach  ($connexion->query($sql) as $row) {
			
			$startColumn_stageDesc = $startColumn;

			$ville = $row['ville'];
			$pays = $row['pays'];
			$dpt = $row['dpt'];
			$etudiant = $row['etudiant'];
			$entreprise = $row['entreprise'];
			$numSerie = $row['numSerie'];
			$uv = $row['uv'];
			$id = $row['id'];

	        $city = getCity($pays, $ville, $dpt);
	        
			$sheet->setCellValue('D'.$i, $numSerie);
			
			// On renseigne les détails du stage
			
			$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true).$i, $city);
			$startColumn_stageDesc++;
			$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true).$i, $entreprise);
			$startColumn_stageDesc++;
			$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true).$i, $etudiant);
			$startColumn_stageDesc++;
			$sheet->setCellValue(num_to_letter($startColumn_stageDesc, true).$i, $uv);

			// On renseigne les demandeurs du stage

			fillDemandeurStage($connexion, $sheet, $id, $i);
		    
			$i++;	
			
		}
	}

	function fillDemandeurStage($connexion, $sheet, $id, $i){
		$sth = $connexion->prepare('SELECT concat(`firstName`, " ", `lastName`) as name, note FROM `votes`, `users` WHERE `votes`.`login` = `users`.`casLogin` and `votes`.`stage` = :stage order by voteDate');
		$sth->bindParam(':stage', $id);
		$sth-> execute();

		$j = 0;
	    while ($user = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			$sheet->setCellValue(num_to_letter(5+$j, true).$i, $user['name']."(".$user['note'].")");
			$j++;
	    }
	}

	function random_color_part() {
	    return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	}

	function random_color() {

		do {
		    $red = random_color_part();
			$green = random_color_part();
			$blue = random_color_part();

			$luminance = hexdec($red)/255 * 0.2126 + hexdec($green)/255 * 0.7152 + hexdec($blue)/255 * 0.0722;
		} while ($luminance < 0.6);

	    return strtoupper($red . $green . $blue);
	}

	function cellColor($sheet, $colStart, $colEnd, $line, $color){
		$cells = num_to_letter($colStart, true).$line.":".num_to_letter($colEnd, true).$line;

        $sheet->getStyle($cells)->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => $color)));
    }

    function isCellNotColored($sheet, $col, $line){
    	
    	$cell = num_to_letter($col, true).$line;
    	
    	if (($color = $sheet->getStyle($cell)->getFill()->getStartColor()->getRGB()) == "FFFFFF")
    	{
    		//echo "White = line = $line & color = $color <br>";
    		return true;
    	}
    	else
    		//echo "Colored = line = $line & color = $color <br>";
    		return false;
    }

    function coloringSimilar ($sheet, $startColumn_stageDesc){
    	$i = 2;
    	
    	while (($val_i = getStageKey($sheet, $startColumn_stageDesc, $i)) != " ") {
    			
			if (isCellNotColored($sheet, $startColumn_stageDesc, $i)){
    			$color = random_color();
    			$matched = false;
    			$j = $i+1;
    			
    			while (($val_j = getStageKey($sheet, $startColumn_stageDesc, $j)) != " "){
    				if ($val_i == $val_j){
    					cellColor($sheet, $startColumn_stageDesc, $startColumn_stageDesc+3, $j, $color);
    					$matched = true;
    				}
    				$j++;
    			}

    			if ($matched)
    				cellColor($sheet, $startColumn_stageDesc, $startColumn_stageDesc+3, $i, $color);
    		}
    		$i++;
		}	
    }

    function getStageKey($sheet, $startColumn_stageDesc, $line)
    {
    	$returnValue1 = $sheet->getCell(num_to_letter($startColumn_stageDesc, true).$line)->getValue();
    	$returnValue2 = $sheet->getCell(num_to_letter($startColumn_stageDesc+1, true).$line)->getValue();
    	return $returnValue1." ".$returnValue2;
    }
?>