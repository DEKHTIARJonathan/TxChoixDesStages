<?php
    // Page de voeux : /voeux/index.php
    header("Content-Type: text/html; charset=UTF-8"); 
    $root = realpath($_SERVER["DOCUMENT_ROOT"]);
    require_once $root.'/config.inc.php';
    require_once $root.'/inc/checksession.php';
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

                    
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
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
                      <th>Pays</th>
                      <th>Titre du Stage</th>
                      <th>Nom de l'Entreprise</th>
                      <th>Description Complète</th>
                      <th>Note</th>
                    </tr>
                  </thead>
                  <tbody data-bind="foreach: MediaGroups">
                
              
                    <?php
    
                        $sql =  'SELECT idStage as id, titreStage as titre, nomEntreprise as entreprise, uv, pays FROM stages ORDER BY id';
                        $sth = $connexion->prepare('SELECT note FROM votes where login = :login and stage= :idStage');
                        $sth->bindParam(':login', $login);
                        $sth->bindParam(':idStage', $id);
                        $login = $_SESSION['login'];

                        $stages = array();
                        foreach  ($connexion->query($sql) as $row) {

                            $id = $row['id'];
                            
                            $sth->execute();
                            $result = $sth -> fetch(PDO::FETCH_ASSOC);
                            $note = $result['note'];

                            $pays = $row['pays'];
                            $titre = $row['titre'];
                            $entreprise = $row['entreprise'];
                            $uv = $row['uv'];

                            echo 
                                    '<tr>
                                        <td style="vertical-align:middle;">'.$id.'</td>
                                        <td style="vertical-align:middle;">'.$uv.'</td>
                                        <td style="vertical-align:middle;">'.$pays.'</td>
                                        <td style="max-width: 300px;vertical-align:middle;">'.$titre.'</td>
                                        <td style="max-width: 100px;vertical-align:middle;">'.$entreprise.'</td>
                                        <td style="vertical-align:middle;"><a data-toggle="modal" id="link'.$id.'" href="#stageFullDesc">Détails</a></td>
                                        <td style="vertical-align:middle;"><div id="score'.$id.'" data-score="'.$note.'"></div></td>
                                    </tr>';
                            $stages[] = $id;
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

             
            <?php
                foreach ($stages as $id){

                echo '$("#score'.$id.'").raty({
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
                            data: { stageID: $(this).attr("id").substring(5), note: score },
                            dataType : "html",

                            success: function(data){
                                if (data != 1) {
                                    alert("Attention, cette action n\'a pas pu être enregistrée.");
                                }
                            }
                            
                        });
                    },
                });
                ';
                }  
            ?>
              

            });

            <?php
                foreach ($stages as $id){
                    echo '$("#link'.$id.'").click(function()
                    {
                        $("stageFullDesc").modal({show:true});
                        $.ajax(
                        {
                            url : "stageDesc.php",
                            type : "GET",
                            data: { stageID: $(this).attr("id").substring(4)},
                            dataType : "html",

                            success: function(data){
                                $("#stageFullDesc").html(data);
                            }
                            
                        });
                    });'
                    ;
                }
            ?>

        </script>
        
   