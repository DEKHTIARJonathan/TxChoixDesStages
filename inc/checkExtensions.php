<?php
	
	if(class_exists('ZipArchive') || extension_loaded('zip'))
		echo "zip installed & running";
	else
		echo "L'extension 'zip' et la classe ZipArchive (php récent) n'est pas chargée sur le serveur => configuration PHP";

	echo "<br><br>";

	if (extension_loaded('xml'))
        echo "xml installed & running";
    else
    	echo "L'extension 'xml' n'est pas chargée sur le serveur => configuration PHP";

    echo "<br><br>";

    if (extension_loaded('gd'))
    	echo "gd installed & running";
    else 
    	echo "L'extension 'gd' n'est pas chargée sur le serveur => configuration PHP";

?>