<?php

// including composer autoload
include_once $_SERVER['DOCUMENT_ROOT'] . '/%%libc%%/vendor/autoload.php'; 


// autoload app classes
include_once "autoload.php";


// clauses use
use \Nettools\Simple_Framework\WebApplication;
use \Nettools\Simple_Framework\Registry;
use \Nettools\Simple_Framework\Config\JsonFile;
use \Nettools\Simple_Framework\Config\ConfigObject;



// create app
$app = new WebApplication(
        // user namespace
       '\\%%ns%%\\Commands', 
    
        // registry
        new Registry(
                array(
                    'appcfg' => new JsonFile(__DIR__ . '/business_data/_private/config/app.json', true),
                )
            )
    );


// execute request ; if exception, an error message is displayed on screen top ; if a command fails,
// a unsuccessful return value is stored in $output ($output->isSuccessful() == false)
$output = $app->run();


// get Request object
$req = $app->controller->getRequest();


?>
<!DOCTYPE html>
<html lang="fr">
<body>
	<?php 
	if ( !$output->isSuccessful() )
		echo "<div style=\"font-weight:bold; color:#d00;\">$output</div>";
	else
		if ( $output instanceof \Nettools\Simple_Framework\ReturnedValues\HTML )
			echo $output;
	?>
</body>
</html>



