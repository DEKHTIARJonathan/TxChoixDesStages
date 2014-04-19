<div class="navbar navbar-inverse navbar-fixed-top" >
    <div class="navbar-inner">
    <a href="https://github.com/JonathanDekhtiar/TxChoixDesStages">
    <img style="position: absolute; top: 0; right: 0; border: 0;" src="/images/forkme.png">
    </a>
    
        <div class="container" style="width:1170px;">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>

            </a>
            <a class="brand" href="/">Suiveur TN09/TN10 GSM - Choix des stages</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li id="accueil">
                        <a href="/">Accueil</a>
                    </li>
                    <li id="voeux">
                        <a href="/voeux/">Réalisation des voeux</a>
                    </li>
                    
                    <li id="admin">
                        <a href="/admin/">Administration</a>
                    </li>
                    
                    <li id="contact">
                        <a href="/contact/">Contact</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
 jQuery(document).ready(function () {   
    var current_href = $(location).attr('href');
    
    if (current_href.indexOf("admin") !== -1)
    {        
        var btn = document.getElementById("admin"); 
        btn.className= "active";
    }
    else if (current_href.indexOf("voeux") !== -1)
    {
        var btn = document.getElementById("voeux"); 
        btn.className= "active"; 
    }
    else if (current_href.indexOf("contact") !== -1)
    {
        var btn = document.getElementById("contact"); 
        btn.className= "active";  
    }
    else
    {
        var btn = document.getElementById("accueil"); 
        btn.className= "active";  
    }

});
</script>