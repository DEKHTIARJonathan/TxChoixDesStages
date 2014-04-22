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
				<table class="table table-hover">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Titre du Stage</th>
                      <th>Description Complète</th>
					  <th>Nom de L'étudiant</th>
					  <th>Type de Stage</th>
					  <th>Note</th>
					</tr>
				  </thead>
				  <tbody>
                
              
					<?php
	
						$sql =  'SELECT idStage as id, titreStage as titre, nomEtudiant as etudiant, uv FROM stages ORDER BY id';
                        $stages = array();
						foreach  ($connexion->query($sql) as $row) {
							$id = $row['id'];
							$titre = $row['titre'];
							$etudiant = $row['etudiant'];
							$uv = $row['uv'];
							echo '<tr><td>'.$id.'</td><td>'.$titre.'</td><td><a data-toggle="modal" id="link'.$id.'" href="#stageFullDesc" style="padding-left:50px">Détail</a></td><td>'.$etudiant.'</td><td>'.$uv.'</td><td><div id="score'.$id.'" data-score="0"></div></td></tr>';
                            $stages[] = $id;
						}
						
					?>
					</tbody>
				</table>
			</div>

            <div class="modal" id="stageFullDesc">
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

                echo "$('#score".$id."').raty({
                    cancel   : true,
                    cancelOff: 'cancel-off.png',
                    cancelOn : 'cancel-on.png',
                    score: function() {
                    return $(this).attr('data-score');
                        }
                    });
                    ";
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
                            data : "stageID=" + $(this).attr("id").substring(4),
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
        
    </body>

</html>