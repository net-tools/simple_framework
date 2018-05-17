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
include __DIR__ . '/Commands/Login.php';
include __DIR__ . '/Commands/LoginRedirect.php';
include __DIR__ . '/Commands/LoginHome.php';




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


?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Simple_Framework sample</title>
</head>
<body>
    <p>In this sample, we have 3 commands which does the login of a user with an ID.
		The first one only initialize the security layer, 
		the second one redirects the user with appropriate security parameters in the POST payload redirect, 
		the third one is called by 'loginRedirect' command after login ; if called from the sample file, it will
		fail since there won't be any security identifier in the request.</p>
    <ul>
        <li><a href="?cmd=login">Execute command 'login'</a></li>
        <li><a href="?cmd=loginRedirect">Execute command 'loginRedirect'</a></li>
        <li><a href="?cmd=loginHome">Execute command 'loginHome'</a></li>
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
    <div>
    ====
    </div>
</body>
</html>