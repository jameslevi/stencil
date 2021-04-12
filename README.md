# Stencil
Is a simple PHP Class templating library.

![](https://img.shields.io/badge/packagist-v1.0.0-informational?style=flat&logo=<LOGO_NAME>&logoColor=white&color=2bbc8a) ![](https://img.shields.io/badge/license-MIT-informational?style=flat&logo=<LOGO_NAME>&logoColor=white&color=2bbc8a)

# Installation
You can install via composer *"composer require jameslevi/stencil"*.

# Getting Started
1. Include the composer autoloader in your project. If you are using frameworks such as laravel, you don't need to do this step.
```php
<?php

if(file_exists(__DIR__.'/vendor/autoload.php'))
{
    require __DIR__.'/vendor/autoload.php';
}
```
2. Basic PHP class templating.
```php
<?php

use Stencil\Stencil;

// Instantiate a new Stencil object.
$php = new Stencil("UserController");

// Declare the namespace of the PHP class.
$php->setNamespace("App\Controller");

// Indicate the class name of the PHP class.
$php->setClassname("UserController");

// Extend to a parent class.
$php->extends("Controller");

// Generate the PHP file.
$php->generate(__DIR__ . "/");
```
3. The code above will generate PHP file with content like this.
```php
<?php

namespace App\Controller;

class UserController extends Controller
{
}
```
# Import Class Implementation
You can use *"use"* method to import classes in to your template.
```php
$php->use("Carbon\Carbon");
```
You can also set alias for your imported class to avoid naming conflict.
```php
$php->use("Carbon\Carbon", "MyDateTime");
```
This command will generate code like this.
```php
use Carbon\Carbon as MyDateTime;
```
# Extend Classes
You can extend class using *"extends"* method. Just make sure you imported your parent class.
```php
// Import the carbon class and use the alias MyDateTime.
$php->use("Carbon\Carbon", "MyDateTime");

// Extend MyDateTime to the class.
$php->extends("MyDateTime");
```
# Implement Interfaces
You can implement one or more interface classes using *"implement"* method.
```php
// Declare the class name.
$php->setClassname("MyClass");

// Import your interfaces.
$php->use("MyInterface1");
$php->use("MyInterface2");

// Implement the interfaces.
$php->implement("MyInterface1");
$php->implement("MyInterface2");
```
The example above will generate code like this.
```php
use MyInterface1;
use MyInterface2;

class MyClass implements MyInterface1, MyInterface2
{
}
```
# Abstract Class
You can set your class as an abstract class.
```php
// Declare the class name.
$php->setClassName("MyAbstractClass");

// Set class as an abstract class.
$php->setAsAbstract();
```
The example above will generate code like this.
```php
abstract class MyAbstractClass
{
}
```
# Raw Content
You can add content in each line using *"raw"* method. You can use *"setIndention"* method to set tab spaces in the beginning of each line.
```php
// Declare class name.
$php->setClassname("MyClass");

$php->setIndention(1);

// Add new raw line.
$php->raw("private $my_property1;");
$php->raw("private $my_property2;");
```
The example above will generate code like this.
```php
class MyClass
{
    private $my_property1;
    private $my_property2;
}
```
# Line Breaks
You can add single line break using *"lineBreak"* method.
```php
$php->lineBreak();
```
# Constants
You can declare constant values in your class using *"addConstant"* method.
```php
$php->addConstant("PI", 3.14);
```
The example above will generate code like this.
```php
const PI = 3.14;
```
# Variables
You can declare class variables with public, private and protected visibility.
```php
$php->addVariable("name", "public", "Juan Dela Cruz");
$php->addVariable("nickname", "private");
```
The above example will generate code like this.
```php
public $name = "Juan Dela Cruz";
private $nickname;
```
# Non-Static Variables
Variables that are only accessible when class is instantiated.
```php
// Add public variable.
$php->addPublicVariable("name", "Juan Dela Cruz");

// Add private variable.
$php->addPrivateVariable("age", 30);

// Add protected variable.
$php->addProtectedVariable("bank_id");
```
The above example will generate code like this.
```php
public $name = "Juan Dela Cruz";
private $age = 30;
protected $bank_id;
```
# Static Variables
Variables that are accessible even without instantiating a class.
```php
// Add public static variable.
$php->addPublicStaticVariable("name", "Juan Dela Cruz");

// Add private static variable.
$php->addPrivateStaticVariable("age", 30);

// Add protected static variable.
$php->addProtectedStaticVariable("ank_id");
```
The above example will generate code like this.
```php
public static $name = "Juan Dela Cruz";
private static $age = 30;
protected static $bank_id;
```
# Single Line Comment
You can add single line comment using *"addLineComment"* method.
```php
$php->addLineComment("This is a single line comment.");
```
The above example will generate code like this.
```php
// This is a single line comment.
```
# Block Comment for Variables
You also add multi-line comments using *"addComment"* method.
```php
// Add variable comment.
$php->addComment(Comment::makeVar("string", "The name of the author of stencil."));

// Add line break.
$php->lineBreak();

// Add new public variable.
$php->addPublicVariable("name", "James Levi Crisostomo");
```
The above example will generate code like this.
```php
/**
 * The name of the author of stencil.
 *
 * @var string
 */
 
public $name = "James Levi Crisostomo";
```
# Method Functions
You can add methods for your class using *"addMethod"* method.
```php
// Instantiate a new method object.
$method = new \Stencil\Method("addUser");

// Add the new method in your template.
$php->addMethod($method);
```
The above example will generate code like this.
```php
public function addUser()
{
}
```
# Static Methods
You can also set if method is static using *"setAsStatic"* method.
```php
// Instantiate a new method object.
$method = new \Stencil\Method("addUser");

// Set method as static.
$method->setAsStatic();

// Add the new method in your template.
$php->addMethod($method);
```
The above example will generate code like this.
```php
public static function addUser()
{
}
```
# Method Arguments
You can add multiple arguments in your method using *"addParam"* method.
```php
// Instantiate a new method object.
$method = new \Stencil\Method("addUser");

// Add method parameters.
$method->addParam("a");
$method->addParam("b", 1, "int");

// Add the new method in your template.
$php->addMethod($method);
```
The above example will generate code like this.
```php
public function addUser($a, int $b = 1)
{
}
```
# Method Content
You can add content in to your method using *"raw"* method.
```php
// Instantiate a new method object.
$method = new \Stencil\Method("addUser");

// Set the indention in the beginning of each line.
$method->setIndention(1);

// Set the raw content.
$method->raw("return null;");

// Add new method in your template.
$php->addMethod($method);
```
The above example will generate code like this.
```php
public function addUser()
{
    return null;
}
```
# Abstract Method
You can also add abstract method in to your abstract class using *"setAsAbstract"* method.
```php
// Instantiate a new method object.
$method = new \Stencil\Method("addUser");

// Set method as an abstract method.
$method->setAsAbstract();

// Add new method in your template.
$php->addMethod($method);
```
The above example will generate code like this.
```php
abstract public function addUser();
```
# Block Comments for Methods
You can add block comments using *"addComment"* method.
```php
// Instantiate a new method comment object.
$comment = \Stencil\Comment::makeMethod("This comment is for adding user method.");

// Set the method parameters.
$comment->addIntegerParam("x");
$comment->addIntegerParam("y");

// Set the return data type of the method.
$comment->setReturnType("string");

// Instantiate a new method object.
$method = \Stencil\Method::makePublic("addUser");

// Set the method parameters.
$method->addIntegerParam("x");
$method->addIntegerParam("y");

// Set the indention value.
$method->setIndention(1);

// Set the return value of the method.
$method->raw("return 'Hello World';");

// Add the comment in your template.
$php->addComment($comment);

// Add line break between the comment and method.
$php->lineBreak();

// Add the new method in your template.
$php->addMethod($method);
```
The above example will generate code like this.
```php
/**
 * This comment is for adding user method.
 * 
 * @param  int $x
 * @param  int $y
 * @return string
 */
 
public function addUser(int $x, int $y)
{
    return 'Hello World';
}
```
# Constructor Method
You can also add constructor to your class using *"makeConstructor"* static method.
```php
$php->addMethod(Method::makeConstructor()->addParam("a"));
```
The above example will generate code like this.
```php
public function __construct($a)
{
}
```
# Contribution
For issues, concerns and suggestions, you can email James Crisostomo via nerdlabenterprise@gmail.com.
# License
This package is an open-sourced software licensed under [MIT](https://opensource.org/licenses/MIT) License.
