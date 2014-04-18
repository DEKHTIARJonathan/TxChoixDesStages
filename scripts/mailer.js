$(document).ready( function () { 

	$("#MailerForm").submit( function() {					 
		$.ajax({ // fonction permettant de faire de l'ajax
		   type: "POST", // methode de transmission des données au fichier php
		   url: "mailer.php", // url du fichier php
		   
		   data: "name="+$("#name").val()+"&email="+$("#email").val()+"&message="+$("#message").val()+"&recaptcha_challenge_field="+$("#recaptcha_challenge_field").val()+"&recaptcha_response_field="+$("#recaptcha_response_field").val(), // données à transmettre
		   
		   success: function(msg)// si l'appel a bien fonctionné
		   {
		      alert(msg);            
		   },
		   error: function(msg) {
               alert('error');
            }
		   
		});
		return false; // permet de rester sur la même page à la soumission du formulaire
	});
});