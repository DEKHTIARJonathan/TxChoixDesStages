<?php
    // Page de voeux : /voeux/index.php
    header("Content-Type: text/html; charset=UTF-8"); 
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/checkassist.php';
    require_once $root.'/inc/dbconnect.php';
?>
<!DOCTYPE html>
<html lang="en">
    
    <head>
        <meta charset="utf-8">
        <title>Réalisation des voeux</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Jonathan Dekhtiar">

        <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
            
            td
            {
                vertical-align: middle;
            }
            th
            {
                text-align:center;
            }

            .modal.modal-wide .modal-dialog {
                width: 90%;
            }

            .modal-wide .modal-body {
                overflow-y: auto;
            }

            .modal.modal-mid .modal-dialog {
                width: 60%;
            }

            .modal-mid .modal-body {
                overflow-y: auto;
            }

        </style>

                    
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
                
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

</head>
    
    <body>
        
        <?php
            include("../parts/header.php");
        ?>
        
        <div class="container">

            <div class="jumbotron" style="padding-top:15px; overflow:hidden; height:120px">

                <h1 style="font-size:300%;">Dashboard des voeux.</h1>

            </div>

            <div class="alert alert-danger">
                <b>Informations sur l'état des votes :<br><br></b>
                    <ul>
                        <?php 
                            $sql = 'Select * from (Select count(*) as nbrOccurences, T1.nbrDemande from (SELECT count(*) as nbrDemande FROM `votes` group by `stage`) as T1 group by T1.nbrDemande 
                                    UNION ALL
                                    Select (select count(*) from stages) - (select count(distinct `stage`) from votes), "0") as T2 order by T2.nbrDemande';

                            foreach  ($connexion->query($sql) as $row) {
                                echo "<li>Nombre de Stage(s) avec <b>".$row['nbrDemande']." vote(s) :</b> ".$row['nbrOccurences']."</li>";
                            }
                        ?>
                    </ul>
                    <br>
                    <ul>
                        <?php 
                            $sql = 'select uv, count(*) as nombre from stages group by `uv` UNION ALL select "total", count(*) from stages';

                            foreach  ($connexion->query($sql) as $row) {
                                echo "<li>Nombre de stages <b>".$row['uv']." :</b> ".$row['nombre']."</li>";
                            }
                        ?>
                    </ul>

                    
            </div>
            
            <div align="center">
                <table class="table table-hover" style="text-align:center;">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Type de Stage</th>
                      <th>Ville</th>
                      <th>Titre du Stage</th>
                      <th>Nom de l'Entreprise</th>
                      <th>Description Complète</th>
                      <th>Nombre de Demandes</th>
                    </tr>
                  </thead>
                  <tbody data-bind="foreach: MediaGroups">
                
              
                    <?php
    
                        $sql =  'SELECT idStage as id, numSerie, titreStage as titre, nomEntreprise as entreprise, uv, pays, ville, departement as dpt, coalesce(tVotes.nbrDemandes, "0") as nbrDemandes FROM stages as tStages LEFT OUTER JOIN (SELECT count(*) as nbrDemandes, `stage` FROM votes group by stage) as tVotes ON tVotes.stage = tStages.idStage ORDER BY nbrDemandes, numSerie';

                        $stages = array();
                        
                        foreach  ($connexion->query($sql) as $row) {

                            $id = $row['id'];
                            
                            
                            $nbrDemande = $row['nbrDemandes'];

                            $pays = $row['pays'];
                            $numSerie = $row['numSerie'];
                            $ville = $row['ville'];
                            $dpt = $row['dpt'];
                            $titre = $row['titre'];
                            $entreprise = $row['entreprise'];
                            $uv = $row['uv'];

                            // Ce passage sert à afficher la ville des stages en France et le pays des stages à l'étranger.
                            if (strtolower($pays) == "france")
                                $city = $ville."(".$dpt.")";
                            else
                                $city = $pays; 

                            $class = true;
                            if ($nbrDemande == 0)
                                $class = "danger";
                            elseif ($nbrDemande < 2)
                                $class = "warning";
                            else
                                $class = "success";

                            echo 
                                    '<tr class="'.$class.'">
                                        <td style="vertical-align:middle;">'.$numSerie.'</td>
                                        <td style="vertical-align:middle;">'.$uv.'</td>
                                        <td style="vertical-align:middle;">'.$city.'</td>
                                        <td style="max-width: 300px;vertical-align:middle;">'.$titre.'</td>
                                        <td style="max-width: 100px;vertical-align:middle;">'.$entreprise.'</td>
                                        <td style="vertical-align:middle;"><a data-toggle="modal" class="link1" data-stage="'.$id.'" href="#stageFullDesc">Détails</a></td>
                                        <td style="vertical-align:middle;">'.$nbrDemande.' : <a data-toggle="modal" class="link2" data-stage="'.$id.'" href="#stageDemandeurs">Demandeurs</a></div></td>
                                    </tr>';
                            $stages[] = $id;
                        }
                        
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="modal modal-wide fade" id="stageFullDesc"></div>
            <div class="modal modal-mid fade" id="stageDemandeurs"></div>

            
            
            <?php
                include("../parts/footer.php");
            ?>

        <script src="../scripts/jquery.bpopup.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../scripts/jquery.easing.1.3.js"></script>

        <script type="text/javascript">

            $(".link1").click(function()
            {
                $("stageFullDesc").modal({show:true});
                $.ajax(
                {
                    url : "/inc/stageDesc.php",
                    type : "GET",
                    data: { stageID: $(this).attr("data-stage")},
                    dataType : "html",

                    success: function(data){
                        $("#stageFullDesc").html(data);
                    }
                    
                });
            });

            $(".link2").click(function()
            {
                $("stageFullDesc").modal({show:true});
                $.ajax(
                {
                    url : "/inc/stageDemandeurs.php",
                    type : "GET",
                    data: { stageID: $(this).attr("data-stage")},
                    dataType : "html",

                    success: function(data){
                        $("#stageDemandeurs").html(data);
                    }
                    
                });
            });

        </script>
    </body>
</html>
        
   