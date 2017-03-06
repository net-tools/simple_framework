<?php

namespace Myapp\Commands;


use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;




class Upload extends Command
{
    public function execute(Request $req, Application $app)
    {
        // the input named 'upload' should always be in the request, even if no file has been submitted.
        // $f will contain a FileUploadRequest object.
        if ( $f = $req->getFileUpload('upload') )
            // if a file has been submitted
            if ( $f->uploaded() )
                // we send back the temp file uploaded to the client !
                return $this->returnFileDownload($f->tmp_name, 'uploaded_file.dat', 'application/octet-stream');
            
            // if no file has been submitted
            else if ( $f->no_file() )
                return $this->fail('The user has not uploaded a file');
            
            // unknown other error
            else
                return $this->fail('Upload error');
        else
            return $this->returnString('Field upload does not exist', false);
    }
}


?>