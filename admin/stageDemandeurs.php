<?php
	// Page de voeux : /voeux/index.php
	header("Content-Type: text/html; charset=UTF-8"); 
	$root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/checkassist.php';
    require_once $root.'/inc/dbconnect.php';

    if (!isset($_GET["stageID"]) || $_GET["stageID"] == "")
    {
    	echo "<h1>Probleme avec le paramètre</h1>";
    	die();
    }
    else
    {
    	$id = $_GET["stageID"];

    	$sth = $connexion->prepare('SELECT concat(`firstName`, " ", `lastName`) as name, note, voteDate FROM `votes`, `users` WHERE `votes`.`login` = `users`.`casLogin` and `votes`.`stage` = :stage order by voteDate');
		$sth->bindParam(':stage', $id);
		$sth->execute();


		echo '<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h4 class="modal-title">Récapitulatif des votes sur le stage</h4>
						</div>
						<div class="modal-body">';

		$first = true;

		if ($sth->rowcount() == 0)
			echo '<h4>Désolé il n\'y a aucun demandeur</h4>';
		else {

			while ($row = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
				$name = $row['name'];
				$note = $row['note'];
				$date = $row['voteDate'];

				if(!$first)
					echo '<hr>';

				echo '			<div class="row">
									<div class="col-md-5" style="text-align:left;"><b>Demandeur : </b>'.$name.'</div>
									<div class="col-md-2" style="text-align:left;"><b>Note: </b>'.$note.'</div>
									<div class="col-md-5" style="text-align:left;"><b>Date du vote : </b>'.$date.'</div>
								</div>';

				$first = false;

		    }
		}

		echo '			</div>
						<div class="modal-footer">
							<a href="#" data-dismiss="modal" class="btn btn-info">Close</a>
						</div>
					</div>
				</div>';
    }

?> 