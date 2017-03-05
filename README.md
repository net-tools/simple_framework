# net-tools/simple_framework

## Composer library to create simple web applications

This package defines a framework that can be used to create simple web applications. The end-user can focus on the "business" coding part, and forget about sanitizing user submitted-data, responding to XMLHttpRequest and so on.


## Setup instructions

To install net-tools/simple_framework package, just require it through composer : `require net-tools/simple_framework:^1.0.0`.


## How to use ?

### Framework preview

The framework focuses on commands, that is to say PHP code "responding" to a request sent to the application. The request could be a GET/POST request (possibly with file uploads) or a XMLHttpRequest. The command "answers" to the request with a returned value that can be selected among :

- PHP value (any kind of data type) : used when the command only does some back-office stuff, such as computing something and returning the result of the computation
- JSON string : used to answer to a XMLHttpRequest
- File download : so that the end-user can download some data from your application (either a file content or some string generated on-the-fly)
- HTML content : used to answer with some formatting to be outputed later on screen (for example, the commands generates a list of products)

This is a simple framework, and you may say that generating view content should not be mixed with computations. You are correct, but the goal is to create a simple framework with basic stuff.


### Create command classes

To answer to a command (such as a GET/POST request), you just have to create a class named on the command name, and inherit from Command class :

```php
use \Nettools\Simple_Framework\Command;
use \Nettools\Simple_Framework\Request;
use \Nettools\Simple_Framework\Application;


class Test extends Command
{
    public function execute(Request $req, Application $app)
    {
        return $this->returnHTML("Command <b>'test'</b> is called with parameter {$req->param} !");
    }
}
```

As you may guess, this command returns HTML content. This content is not outputted to screen yet. This is done later, in your page template. 

The string returned will contain the value of querystring 'param', which is accessible through the Request $req object. This object is filled with all GET/POST parameters, as PHP properties. Data is sanitized before being set to the Request object.

-----

If you want to answer to XMLHttpRequests, return a JSON value with either on the the following lines :

```php
return $this->returnJson('{"value":"'. $req->param . '"}');        // string
return $this->returnJson(array('value' => $req->param));           // associative array
return $this->returnJson((object)array('value' => $req->param));   // object litteral
```

