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
use \Nettools\Simple_Framework\SecurityHandlers\CSRFSecurityHandler;



// we have to include our commands ; of course, on a real application, you would use an autoload 
// mechanism for your own files
include __DIR__ . '/Commands/InitCSRF.php';
include __DIR__ . '/Commands/RevokeCSRF.php';
include __DIR__ . '/Commands/AuthenticatedCSRFRequest.php';




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
																	'CSRFSecurityHandler'	=> ['_myCSRF_', '_CSRF_value_', __FILE__]
																]
                                                            )
                                    )
                                )
                )
		)
    );



// execute app ; exceptions are catched inside run()
$output = $app->run();

?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Simple_Framework sample</title>
</head>
<body>
    <p>In this sample, we have 5 commands ; the first one must be launched before the second one and third one, so that the CSRF security
		layer is initialized (in real world, this should be done after successful authentication of user login). The second
		one has required security parameters (CSRF) to authenticate the request, the third one has the same required security parameters
		but the CSRF submitted value (double CSRF cookie submit pattern) is a hashed value of the real CSRF cookie value, thus preventing
		disclosure of cookie in GET url, browser history, etc. The fourth one is missing those parameters and will fail ; the fifth command
		revokes the CSRF layer and subsequent commands will fail.</p>
    <ul>
		<?php
		try
		{
			$cookie = $app->controller->getCSRFSecurityHandler()->getCSRFCookie();
			$hashedCookie = $app->controller->getCSRFSecurityHandler()->getHashedCSRFCookie();
		}
		catch (\Nettools\Core\Helpers\SecureRequestHelper\CSRFException $e)
		{
			$cookie = '__not_initialized__';
		}
		?>
        <li><a href="?cmd=initCSRF">Initialize CSRF security layer</a></li>
        <li><a href="?cmd=authenticatedCSRFRequest&_CSRF_value_=<?php echo $cookie; ?>&value=test+value">Execute command 'authenticatedCSRFRequest'</a></li>
        <li><a href="?cmd=authenticatedCSRFRequest&_CSRF_value_=<?php echo $hashedCookie; ?>&value=test+value">Execute command 'authenticatedCSRFRequest' with hashed CSRF submitted value</a></li>
        <li><a href="?cmd=authenticatedCSRFRequest&_CSRF_value_=wrong_value&value=test+value">Execute command 'authenticatedCSRFRequest' with wrong parameters</a></li>
        <li><a href="?cmd=revokeCSRF">Revoke CSRF security layer</a></li>
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