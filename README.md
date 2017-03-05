# net-tools/simple_framework

## Composer library to create simple web applications

This package defines a framework that can be used to create simple web applications. The end-user can focus on the "business" coding part, and forget about sanitizing user submitted-data, responding to XMLHttpRequest and so on.


## Setup instructions

To install net-tools/simple_framework package, just require it through composer : `require net-tools/simple_framework:^1.0.0`.


## How to use ?

### Framework preview

The framework focuses on commands, that is to say PHP code "responding" to a request sent to the application. The request could be a GET/POST request (possibly with file uploads) or a XMLHttpRequest. The command "answers" to the request with a returned value that can be selected among :

- **PHP value** (any kind of data type) : used when the command only does some back-office stuff, such as computing something and returning the result of the computation
- **JSON string** : used to answer to a XMLHttpRequest
- **File download** : so that the end-user can download some data from your application (either a file content or some string generated on-the-fly)
- **HTML content** : used to answer with some formatting to be outputed later on screen (for example, the commands generates a list of products)

This is a simple framework, and you may say that generating view content should not be mixed with computations. You are correct, but the goal is to create a simple framework with basic stuff.


### Create command classes

#### Common cases 

To answer to a command (such as a GET/POST request), you just have to create a class named on the command name, and inherit from `Command` class :

```php
namespace Myapp\Commands;

use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;


class Test extends Command
{
    public function execute(Request $req, Application $app)
    {
        return $this->returnHTML("Command <b>'test'</b> is called with parameter '{$req->param}' !");
    }
}
```

As you may guess, this command returns HTML content. This content is not outputted to screen yet. This is done later, in your page template. 

The string returned will contain the value of querystring 'param', which is accessible through the `Request $req` object. This object is filled with all GET/POST parameters, as PHP properties. Data is sanitized before being set to the `Request` object.

---

If you want to answer to XMLHttpRequests, return a JSON value with either on the the following lines. The `returnJson` method of `Command` class is smart enough to allow different data types and then converting them internally to a JSON-formatted string.

```php
return $this->returnJson('{"value":"'. $req->param . '"}');        // string
return $this->returnJson(array('value' => $req->param));           // associative array
return $this->returnJson((object)array('value' => $req->param));   // object litteral
```

---

To respond to a command with a download, use either the `returnFileDownload` or `returnStringDownload` depending on whether the data is contained in a file or a string generated on-the-fly :

```php
// downloading a file with Mimetype 'text/plain', the browser will suggest the name 'test.txt' as filename with 'my file content' as data downloaded.
return $this->returnStringDownload("my file content", 'test.txt', 'text/plain'); 

// downloading a file from path '/tmp/compute.bin', with Mimetype 'application/octet-stream' ; when saved, the browser will suggest 'data.bin' as filename
return $this->returnFileDownload('/tmp/compute.bin', 'data.bin', 'application/octet-stream');
``` 

#### Handling file uploads

If you want to handle files uploaded by user, use the `getFileUpload` method of `Request` class to fetch a specific `FileUploadRequest` object describing the file uploaded :

```php
class Upload extends Command
{
    public function execute(Request $req, Application $app)
    {
        // the input named 'upload' should always be in the request, even if no file has been submitted.
        // $f will contain a FileUploadRequest object.
        if ( $f = $req->getFileUpload('upload') )
            // if a file has been submitted
            if ( $f->uploaded() )
            {
                // we erase the temp file, this is just a test
                unlink($f->tmp_name);
                return $this->returnString('File was sent');
            }
            
            // if no file has been submitted
            else if ( $f->no_file() )
                return $this->returnString('The user has not uploaded a file');
            
            // unknown other error
            else
                return $this->returnString('Upload error');
        else
            return $this->returnString('Field upload does not exist');
    }
}
```


### Send commands

To execute the commands defined before, you have to send a HTTP request to the URI of the application. If 'index.php' is the application file :

```
index.php?cmd=test&param=hello+world
```

The first two examples here (HTML response and XMLHttpResponse) would output :

`Command <b>'test'</b> is called with parameter 'hello world' !`

and

`{"value":"hello world"}`

As you can see, the name of the command should be set in the `cmd` URI querystring parameter. Other parameters are meant to be used during the command execution (such as `$req->param` for `param` querystring value).


