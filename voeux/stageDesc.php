<?php
	// Page de voeux : /voeux/index.php
	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/dbconnect.php';

    if (!isset($_GET["stageID"]) || $_GET["stageID"] == "")
    {
    	echo "<h1>Probleme avec le paramètre</h1>";
    	die();
    }
    else
    {
    	$id = $_GET["stageID"];

    	$sth = $connexion->prepare('SELECT titreStage as titre, numSerie, departement as dpt, ville, nomEtudiant as etudiant, nomEntreprise as entreprise, uv, pays, descriptionComplete as full FROM stages where idStage= :idStage');
		$sth->bindParam(':idStage', $id);
		$sth->execute();
		$result = $sth -> fetch(PDO::FETCH_ASSOC);

		$titre = $result['titre'];
		$etudiant = $result['etudiant'];
		$uv = $result['uv'];
		$full = $result['full'];
		$entreprise = $result['entreprise'];
		$pays = $result['pays'];
		$numero = $result['numSerie'];
		$ville = $result['ville'];
		$dpt = $result['dpt'];

		// Ce passage sert à afficher la ville des stages en France et le pays des stages à l'étranger.
        if (strtolower($pays) == "france")
            $city = $ville."(".$dpt.")";
        else
            $city = $ville; 

		echo '<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title">'.$titre.'</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-4" style="text-align:left;"><b>Étudiant : </b>'.$etudiant.'</div>
								<div class="col-md-4" style="text-align:left;"><b>Type de Stage : </b>'.$uv.'</div>
								<div class="col-md-4" style="text-align:left;"><b>Numéro de Stage : </b>'.$numero.'</div>
							</div>
							<br><br>
							<div class="row">
								<div class="col-md-4" style="text-align:left;"><b>Entreprise : </b>'.$entreprise.'</div>
								<div class="col-md-4" style="text-align:left;"><b>Pays : </b>'.$pays.'</div>
								<div class="col-md-4" style="text-align:left;"><b>Ville : </b>'.$city.'</div>
							</div>
							<hr>
							<div id="descFull" >
								'.$full.'
							</div>
						</div>
						<div class="modal-footer">
							<a href="#" data-dismiss="modal" class="btn btn-info">Close</a>
						</div>
					</div>
				</div>';
    }

?> 