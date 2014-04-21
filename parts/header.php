<div class="navbar navbar-fixed-top navbar-inverse ">
	<a href="https://github.com/JonathanDekhtiar/TxChoixDesStages">
		<img style="position: absolute; top: 0; right: 0; border: 0;" src="/images/forkme.png">
	</a>
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="/">Suiveur TN09/TN10 GSM - Choix des stages</a>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
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