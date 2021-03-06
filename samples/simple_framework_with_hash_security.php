<?php

define('K_NETTOOLS_INIT_LOCALE', 'fr_FR.utf8');

if ( file_exists(__DIR__ . '/../../../autoload.php') )
    include_once __DIR__ . '/../../../autoload.php';
else
    die('Composer autoload is not found in ' . realpath(__DIR__ . '/../../../'));



// dependencies
use \Nettools\Simple_Framework\WebApplication;
use \Nettools\Simple_Framework\Registry;
use \Nettools\Simple_Framework\Config\ConfigObject;
use \Nettools\Simple_Framework\SecurityHandlers\HashSecurityHandler;



// we have to include our commands ; of course, on a real application, you would use an autoload 
// mechanism for your own files
include __DIR__ . '/Commands/AuthenticatedRequest.php';
include __DIR__ . '/Commands/RedirectGETtoPOST.php';
include __DIR__ . '/Commands/RedirectedToPOST.php';




// create application suitable for web gui
$app = new WebApplication(
        // user namespace
        '\\Myapp\\Commands', 
    
        // registry
        new Registry(
                array(
                    'appcfg' => new ConfigObject(
                                    (object)array(
                                        'controller'    => (object)array(
																'userSecurityHandlers' => (object)[
																	'HashSecurityHandler'	=> ['my secret', '_h_', '_i_']
																]
                                                            )
                                    )
                                )
                )
		)
    );



// execute app ; exceptions are catched inside run()
$output = $app->run();


if ( $app->controller->getRequest()->cmd == 'redirectGETtoPOST' )
{
	// send redirect ; script halts here
	$app->controller->sendPOST('redirectedToPOST', ['param1'=>'', 'param2'=>'characters to encode properly : "+=<>']);
}


?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Simple_Framework sample</title>
</head>
<body>
    <p>In this sample, we have 3 commands ; one has required security parameters to authenticate the request, the second is missing those parameters and will fail ; 
	the third one is a GET request which will be transformed to a POST request.</p>
    <ul>
		<?php 
		$h = HashSecurityHandler::makeHash('an_id_for_client', 'my secret');
		$i = 'an_id_for_client';
		?>
        <li><a href="?cmd=authenticatedRequest&_h_=<?php echo $h; ?>&_i_=<?php echo $i; ?>">Execute command 'authenticatedRequest'</a></li>
        <li><a href="?cmd=authenticatedRequest&_h_=wrong_value&_i_=<?php echo $i; ?>">Execute command 'authenticatedRequest' with wrong parameters</a></li>
        <li><a href="?cmd=redirectGETtoPOST">Execute command 'redirectGETtoPOST'</a></li>
    </ul>
    <div>
    ====
    </div>
    <p>Below is the output of the command ; if not successful (command failed by user call to Command::fail), the error message is displayed in red and bold font.</p>
    <?php 
    if ( $output->isSuccessful() )
        echo $output;
    else
        echo "<span style=\"color:firebrick; font-weight:bold;\">$output</span>";
    ?>
	<p>By the way :
		<br>- the authentication process returned the following boolean : <b><?php echo $app->controller->authenticationPassed() ? 'TRUE':'FALSE'; ?></b>
		<br>- the command requires authentication : <b><?php echo $app->controller->authenticationRequired() ? 'TRUE':'FALSE'; ?></b>
	</p>
    <div>
    ====
    </div>
</body>
</html>