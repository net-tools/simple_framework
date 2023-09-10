# net-tools/simple_framework

## Composer library to create simple web applications

This package defines a framework that can be used to create simple web applications. The end-user can focus on the "business" coding part, and forget about sanitizing user submitted-data, responding to XMLHttpRequest and so on.


## Setup instructions

To install net-tools/simple_framework package, just require it through composer : `require net-tools/simple_framework:^1.0.0`.


## Sample files

There's a `samples` subdirectory in the package with a very simple app. Please read first this readme and then you may refer to this sample file.


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

If you want to answer to XMLHttpRequests, return a JSON value with either on the the following lines. The `returnJson()` method of `Command` class is smart enough to allow different data types and then converting them internally to a JSON-formatted string.

```php
return $this->returnJson('{"value":"'. $req->param . '"}');        // string
return $this->returnJson(array('value' => $req->param));           // associative array
return $this->returnJson((object)array('value' => $req->param));   // object litteral
```

---

To respond to a command with a download, use either the `returnFileDownload()` or `returnStringDownload()` depending on whether the data is contained in a file or a string generated on-the-fly :

```php
// downloading a file with Mimetype 'text/plain', the browser will suggest the name 'test.txt' as filename with 'my file content' as data downloaded.
return $this->returnStringDownload("my file content", 'test.txt', 'text/plain'); 

// downloading a file from path '/tmp/compute.bin', with Mimetype 'application/octet-stream' ; when saved, the browser will suggest 'data.bin' as filename
return $this->returnFileDownload('/tmp/compute.bin', 'data.bin', 'application/octet-stream');
``` 

#### Handling file uploads

If you want to handle files uploaded by user, use the `getFileUpload()` method of `Request` class to fetch a specific `FileUploadRequest` object describing the file uploaded :

```php
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

As you can see, the name of the command should be set in the `cmd` URI querystring parameter. Other parameters are meant to be used during the command execution (such as `$req->param` for `param` querystring value).

Requests can also be sent with POST verb or XMLHttpRequest from Javascript. 

The first two examples on this page (HTML response and XMLHttpResponse) would output :

`Command <b>'test'</b> is called with parameter 'hello world' !`

and

`{"value":"hello world"}`


### Launch application to handle requests

When sending commands to your application with an HTTP request such as `index.php?cmd=test&param=hello+world`, you need to launch the application framework so that it could handle request and return output from command execution :

```php
<?php
namespace Myapp;

use \Nettools\Simple_Framework\WebApplication;
use \Nettools\Simple_Framework\Registry;


// créer l'application
$app = new WebApplication(
        // user namespace for commands
        '\\Myapp\\Commands', 
    
        // registry for config data
        new Registry()
    );

// launch app and get the returned result in $output
$output = $app->run();
?>
<html>
  <body>
  Command output as HTML or php value : <?php echo $output; ?>.
  </body>
</html>
```

The `WebApplication` object is created so that the application could run. Its first parameter is the namespace where to look for command classes (please refer to the first examples here, the namespace for the commands is `Myapp\Commands`), and the second parameter is a `Registry` object used to store config data (to keep the example simple, the registry is empty ; we don't need any config data for this test). If you need to handle sensitive config data, create your registry (with `Config\ConfigObject`, `JsonFile`, or `Json`) and then encapsulate it in a top-level `Config\PrivateConfig` registry (see specific sample file).

Then, the `run()` method is called on the `Application $app` object : the command refered by the `cmd` querystring parameter is searched in the `Myapp\Commands` namespace, invoked, and its results set into `$output`.

For web applications (which will probably be the case), some returned values should be sent immediately to the browser (`Json`, `Download`, either `FileDownload` or `StringDownload`) ; the process to do that output or not (depending on the returned value type) is handled by the classes in `ReturnedValuesOutput` sub-namespace. If a class named `WebController_xxxx`, where xxxx stands for the returned value class (`Download`, `Json`, etc.), this class is responsible for the output. If no class is found for a returned value, there's no output to browser, and the value will be returned by `$app->run()`.

Please refer to classes in the `ReturnedValues` sub-namespace of `Nettools\Simple_Framework` for a complete list of acceptable returned values (all inheriting from `ReturnedValues\Value`).

In other cases, the command returns a value, which is fetched from `$app->run()` execution. In most cases this will be some HTML content or a primitive PHP type (string, int, etc.), that you can include or use in your page template later : `echo $output` will cast the `ReturnedValues\Value` object to a string.


### Handling error cases and exceptions

When an exception occurs or when you want a command to fail on purpose, the framework will do some specific stuff.


#### Exceptions : the framework handles then

Exceptions thrown (and not catched by your code) during code execution are catched in the `run()` method of `Controller` object and a specific screen with all required data for debugging is displayed (and the script is halted). For your information, this debugging data is formatted by `Nettools\Core\ExceptionHandlers\SimpleExceptionHandler` (you may refer to the Nettools\Core package to read more documentation : http://net-tools.ovh/api-reference/net-tools/Nettools/Core/ExceptionHandlers.html ).

You may set another exception handler (for example, you don't want debug data displayed on screen and prefer sending debug data to the webadmin by email) ; to do so, when creating the `$app` object, create a `Registry` named `appcfg` and set its `application->exceptionHandler` value to a handler of your choice (however, it must inherit from `Nettools\Core\ExceptionHandlers\ExceptionHandler`, such as `Nettools\Core\ExceptionHandlers\PublicExceptionHandler` which display on screen a short message about exception, with no debug data ; debug data is sent to postmaster@yourdomain.com as email body and attachment with stack trace).


#### Error cases : failures

Sometimes, you want to say that a command execution has not succeeded. To do so, during your command `execute` call, call the `fail()` on `$this` with the appropriate error message :

```php
class Failed extends Command
{
    public function execute(Request $req, Application $app)
    {
        $this->fail('Something went wrong');
    }
}
```

The application controller will then process the error : 

- if the request has been sent with GET/POST, the `run()` method of `Application` will return a ReturnedValues\StringValue object with an unsuccessful state. It's up to you to detect the unsuccessful answer and do any appropriate stuff by calling the `isSuccessful()` method of the returned value : `$output = $app->run(); if ( !$output->isSuccessful() ) echo "error : " . $output;`

- if the request has been sent through a XMLHttpRequest, the `run()` method of `Application` (in fact, it's the `run()` method of `Controller` object) will automatically output a Json string on stdout and halt the script : `{"statut":false,"message":"Something went wrong"}`


If you want to answer with an error feedback other than a string, you can't use the fail mechanism. However, you can reply to the command with a ReturnedValues\Value with an unsuccessful state ; all *returnXXX* methods of `Command` class have a second parameter to set the state of the execution (successful/true by default).

```php
class ErrorOccured extends Command
{
    public function execute(Request $req, Application $app)
    {
        return $this->returnPHP(array('msg'=>'Error message', 'line'=>134, 'severity'=>4), false);
    }
}
```


### Security features

The framework can be used with no security features at all (except request being sanitized) or can be configured to send request with a special computed token identifying the user (thus preventing unauthorized requests being made on behalf of other users).

The token is created through a secret (to be configured in the `appcfg` registry, in `controller->userSecurityHandlers` array of security handlers) and the current user ID in the request (can be any request parameter ; by default, it's the `i` parameter). It's passed in the request, along with other parameters, as a `h` parameter (by default, but can be named with anything else). See appropriate sample for use case.

This token-based security feature **only** prevents hackers to issue request for another user ID without being authorized (logged), since they don't know how to compute the token value (don't know the secret nor the hash process). 

However, it **does not** prevent the request to be issued several times (the token is not a nonce value), nor does it prevent the request to be vulnerable to CSRF attacks.

In future releases, CSRF security features and nonce token may be implemented.



## PHPUnit

To test with PHPUnit, point the -c configuration option to the /phpunit.xml configuration file.

