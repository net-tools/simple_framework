<?php

if ( $_REQUEST['app'] )
{

    $json = json_decode(file_get_contents(__DIR__ . '/res/patch.json'));
    if ( !$json )
        $error = "Can't find patch";
    else
    {
        // preparing file
        $tmp_path = sys_get_temp_dir() . '/' . uniqid('simple_frmk');
        if ( !mkdir($tmp_path) )
            $error = "Can't create temp folder";
        else
            try
            {
                mkdir ($tmp_path . '/app/');
                
                // copy from resource folder (template)
                shell_exec("cp -r " . __DIR__ . "/res/* $tmp_path/");
                                
                // delete patch file in temp dir
                unlink("$tmp_path/patch.json");
                
                // handle patching files with actual values
                foreach ( $json->files as $file )
                {
                    $f = file_get_contents($tmp_path . $file->name);
                    if ( !$file->camelCase )
                        foreach ( $file->expand as $var )
							if ( $file->preg_replace && $file->preg_replace->{$var} )
								$f = str_replace("%%$var%%", preg_replace($file->preg_replace->{$var}->pattern, $file->preg_replace->{$var}->replace, $_REQUEST[$var]), $f);
							else
                            	$f = str_replace("%%$var%%", $_REQUEST[$var], $f);
                    else
                        foreach ( $file->expand as $var )
							if ( $file->preg_replace && $file->preg_replace->{$var} )
							{
								$v = preg_replace($file->preg_replace->{$var}->pattern, $file->preg_replace->{$var}->replace, $_REQUEST[$var]);
                            	$f = str_replace("%%$var%%", strtoupper(substr($v,0,1)) . substr($v,1), $f);
							}
							else
                            	$f = str_replace("%%$var%%", strtoupper(substr($_REQUEST[$var],0,1)) . substr($_REQUEST[$var],1), $f);
					
					
                    
                    $fres = fopen($tmp_path . $file->name, 'w');
                    fwrite($fres, $f);
                    fclose($fres);
                }

	
				// rename default command file
                rename("$tmp_path/app/src/Commands/DefaultCmd.php", "$tmp_path/app/src/Commands/" . $_REQUEST['defaultcmd'] . ".php");	
                
				// rename app folder
                rename("$tmp_path/app", "$tmp_path/" . $_REQUEST['app']);	
				
               
                // creating zip file
                $ztmp = tempnam(sys_get_temp_dir(), 'z_simple_frmk') . '.zip';
                shell_exec("cd $tmp_path; zip -r $ztmp *");
                
                // force dwonload
				header("Content-Type: application/zip; name=\"" . $_REQUEST['app'] . ".zip\"");
				header("Content-Disposition: attachment; filename=\"" . $_REQUEST['app'] . ".zip\"");
				header("Expires: 0");
				header("Cache-Control: no-cache, must-revalidate");
				header("Pragma: no-cache"); 
                
				readfile($ztmp);
				unlink($ztmp);
                $die = true;
            }
            finally
            {
                shell_exec("rm -rf $tmp_path");
                if ( $ztmp )
                {
                    unlink($ztmp);
                    if ( $die ) 
                        die();
                }
            }            
    }
}


?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Simple_Framework deployment tool</title>
    <style>
        body{
            font-family: Gotham, "Helvetica Neue", Helvetica, Arial, "sans-serif";
            font-size:14px;
            margin-left:3em;
            margin-right: 3em;
        }
        
        h1 {
            text-align: center;
        }
        
        h2 {
            color:navy;
            margin-top:1.5em;
            border-top:1px dashed lightgray;
            padding-top:1.5em;
        }
        
        h2 + div {
            border-left: 3px solid lightgray;
            padding-left:0.5em;
        }
        
        form > h2:first-child {
            border-top:0;

        }
        
        h3 {
            color:red;
            margin-top:1.5em;
        }
        
        form input,
		form select{
            background-color: whitesmoke;
            border:1px solid darkgray;
            box-shadow: 0 0 4px lightgray;
            height:1.8em;
        }
        
        form input[type="text"]{
            padding-left:0.2em;
            padding-right:0.2em;
        }
        
        form input[type="submit"]{
            background-color: steelblue;
            border:1px solid dimgray;
            color:white;
            height:2.2em;
        }
        
        form label {
            width:15em;
            text-align: right;
            margin-right:1em;
            display: inline-block;
        }
                
        form div.actions {
            margin-bottom: 2em;
        }
        
        form p span.note {
            font-size:0.9em;
            font-style: italic;
            
        }
        
        form p span[title] {
            text-decoration: underline;
            text-decoration-style: dotted;
            text-decoration-color: gray;
        }
        
        form div.chemins input[type='text'] {width:20em;}
        form div.application input[type='text'],
        form div.webadmin input[type='text'] {width:25em;}
    </style>
</head>

<body>
    <h1>Simple_Framework deployment tool</h1>
    <form method="post" action="index.php">
        
        <h3><?php if ( $error ) echo htmlentities($error); ?></h3>

        <h2>Paths</h2>
        <div class="chemins">
            <p><label for="libc">Composer project path : </label><input required type="text" id="libc" name="libc" placeholder="/libc/" value="<?php echo $_REQUEST['libc'];?>"></p>
            <p><label></label><span class="note">net-tools/core, net-tools/simple_framework</span></p>
        </div>
        
		
        <h2>Application</h2>
        <div class="application">
            <p><label for="app">Application name : </label><input type="text" id="app" required name="app" placeholder="Testapp" value="<?php echo $_REQUEST['app'];?>"></p> 
            <p><label for="ns">Namespace : </label><input type="text" id="ns" required name="ns" placeholder="vendor\subns\app" value="<?php echo $_REQUEST['ns'];?>"></p> 
            <p><label for="defaultcmd">Default command : </label><input type="text" id="defaultcmd" required name="defaultcmd" placeholder="DefaultCommandClass" value="<?php echo $_REQUEST['defaultcmd'];?>"></p> 
            <p><label for="authreq">Authenticated requests : </label><select name="authreq" id="authreq" value="<?php echo $_REQUEST['authreq'];?>"><option value=''>No</option><option value='"HashSecurityHandler":["my secret"]'>Yes</option></select></p> 
        </div>
        
        <h2>Actions</h2>
        <div class="actions">
            <input type="submit" value="Create file">
        </div>
    </form>
</body>
</html>