<?php
    // Page de voeux : /voeux/index.php
    header("Content-Type: text/html; charset=UTF-8"); 
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
    require_once $root.'/inc/checkaccount.php';
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
/*
            .table-scroll td {
                padding: 3px 10px;
            }

            .table-scroll thead > tr {
                position:relative;
                display:block;
            }
*/

        </style>

                    
        
        <script src="../scripts/jquery-1.9.1.min.js"  ></script>
        <script type="text/javascript" src="../raty/jquery.raty.min.js"></script>


                
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

            <div class="jumbotron" style="padding-top:15px; overflow:hidden; height:150px">

                <h1 style="font-size:300%;">Réalisation des voeux.</h1>
                <p style="margin-top:-20px">
                    <font size='3' >
                        <br>Veuillez réaliser vos voeux en vue de l'attribution futur des suiveurs de stage TN09 & TN10.
                    </font>
                </p>

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
                      <th>Note</th>
                    </tr>
                  </thead>
                  <tbody data-bind="foreach: MediaGroups">
                
              
                    <?php
    
                        $sth = $connexion->prepare('SELECT idStage as id, numSerie, titreStage as titre, nomEntreprise as entreprise, uv, pays, ville, departement as dpt , coalesce(tVotes.note, "0") as note  FROM stages as tStages LEFT OUTER JOIN ( SELECT note, stage, login FROM votes) as tVotes ON tVotes.`stage`= tStages.idStage and tVotes.login = :login ORDER BY note DESC, numSerie');
                        $sth->bindParam(':login', $login);

                        $login = $_SESSION['login'];
                        
                        $sth->execute();
                        
                        while ($row = $sth->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
                        

                            $id = $row['id'];
                            
                            $note = $row['note'];

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

                            echo 
                                    '<tr>
                                        <td style="vertical-align:middle;">'.$numSerie.'</td>
                                        <td style="vertical-align:middle;">'.$uv.'</td>
                                        <td style="vertical-align:middle;">'.$city.'</td>
                                        <td style="max-width: 300px;vertical-align:middle;">'.$titre.'</td>
                                        <td style="max-width: 100px;vertical-align:middle;">'.$entreprise.'</td>
                                        <td style="vertical-align:middle;"><a data-toggle="modal" class="link" data-stage="'.$id.'" href="#stageFullDesc">Détails</a></td>
                                        <td style="vertical-align:middle;"><div class="score" data-stage="'.$id.'" data-score="'.$note.'"></div></td>
                                    </tr>';
                        }
                        
                    ?>
                    </tbody>
                </table>
            </div>

            <div class="modal modal-wide fade" id="stageFullDesc">
            </div>

            
            
            <?php
                include("../parts/footer.php");
            ?>

        <script src="../scripts/jquery.bpopup.min.js"></script>
        <script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../scripts/jquery.easing.1.3.js"></script>

        <script type="text/javascript">
        
            $(function() {
              $.fn.raty.defaults.path = '../raty/img';

             
                $(".score").raty({
                    cancel   : true,
                    cancelOff: "cancel-off.png",
                    cancelOn : "cancel-on.png",
                    score: function() {
                        return $(this).attr("data-score");
                    },
                    click: function(score, evt) {
                        if (score == null) {
                            score = 0;
                        }
                        $.ajax(
                        {
                            url : "vote.php",
                            type : "GET",
                            data: { stageID: $(this).attr("data-stage"), note: score },
                            dataType : "html",

                            success: function(data){
                                if (data != 1) {
                                    alert("Attention, cette action n\'a pas pu être enregistrée.");
                                }
                            }
                            
                        });
                    },
                });
            });

            $(".link").click(function()
            {
                $("stageFullDesc").modal({show:true});
                $.ajax(
                {
                    url : "stageDesc.php",
                    type : "GET",
                    data: { stageID: $(this).attr("data-stage")},
                    dataType : "html",

                    success: function(data){
                        $("#stageFullDesc").html(data);
                    }
                    
                });
            });

        </script>
    </body>
</html>
        
   