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
use \Nettools\Simple_Framework\Config\PrivateConfig;



// we have to include our commands ; of course, on a real application, you would use an autoload 
// mechanism for your own files
include __DIR__ . '/Commands/Test1.php';
include __DIR__ . '/Commands/Upload.php';
include __DIR__ . '/Commands/Failed.php';
include __DIR__ . '/Commands/Bad_code.php';




// create application suitable for web gui
$app = new WebApplication(
        // user namespace
        '\\Myapp\\Commands', 
    
        // registry
        new Registry(
			[
				'usercfg'	=> new PrivateConfig(new ConfigObject((object)['cfg1' => 'value1', 'private1' => 'secret']))
			]
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
    <p>In this sample, we have 3 commands : one for GET request (HTML response), one for file uploading (and response as download), one for a failing command :</p>
    <ul>
        <li><a href="?cmd=test1&value=hello+world">Execute command 'test1'</a></li>
        <li>
            <form method="post" action="?cmd=upload" enctype="multipart/form-data">
                <input type="file" name="upload">
                <input type="submit" value="Send the file">
            </form>
        </li>
        <li><a href="?cmd=failed">Failing command</a></li>
        <li><a href="?cmd=bad_code">Command throwing a PHP exception (bad programming, calling a method on NULL value)</a></li>
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