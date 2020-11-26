# Stubless

[![Latest Stable Version](https://img.shields.io/packagist/v/shomisha/stubless)](https://packagist.org/packages/shomisha/stubless)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE)

Stubless is a package for generating PHP source code using a readable, fluent, object-oriented API, and without the usage of any stubs. 
It is ideal for generator commands and takes away the need for manipulating strings when programmatically generating PHP code.

Here is an example of what Stubless can do:

```php
<?php

require_once 'vendor/autoload.php';

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Shomisha\Stubless\Templates\Argument;
use Shomisha\Stubless\Templates\ClassMethod;
use Shomisha\Stubless\Templates\ClassTemplate;
use Shomisha\Stubless\Utilities\Importable;

$class = ClassTemplate::name('UsersController')->extends(new Importable(Controller::class))->setNamespace('App\Http\Controllers');

$requestArgument = Argument::name('request')->type(new Importable(Request::class));
$userArgument = Argument::name('user')->type(new Importable(User::class));
$userRequestArgument = Argument::name('request')->type(new Importable(UserRequest::class));

$class->withMethods([
	ClassMethod::name('index')->addArgument($requestArgument),
	ClassMethod::name('show')->withArguments([
		$requestArgument,
		$userArgument,
	]),
	ClassMethod::name('store')->addArgument($userRequestArgument),
	ClassMethod::name('update')->withArguments([
		$userRequestArgument,
		$userArgument,
	]),
	ClassMethod::name('destroy')->addArgument($userArgument),
]);

echo $class->print();
```

The following code would generate this PHP source code:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
    }

    public function show(Request $request, User $user)
    {
    }

    public function store(UserRequest $request)
    {
    }

    public function update(UserRequest $request, User $user)
    {
    }

    public function destroy(User $user)
    {
    }
}
```

**DISCLAIMER**: Stubless has been developed and optimised with class generation in mind and will be upgraded with the same mindset in the near future. 
At some point I might devote more attention into addressing non-object-oriented PHP generation, but that's further down the road and is not planned for right now.  

To learn more about how to use Stubless and all of its features check the [wiki pages](https://github.com/shomisha/stubless/wiki).