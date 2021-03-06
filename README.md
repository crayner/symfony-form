# Form Extension
Symfony 4 Form Extension Bundle

Version
-------
0.3.32

Development ONLY
----------------

This package is currently under development and is also a learning tool for myself.  Please use with EXTREME caution.   I will remove this warning when I am satisfied it is ready for release.


Installation
============

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require hillrange/symfony-form
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require hillrange/symfony-form "~0.0"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/Bundles.php` file of your project:

```php
<?php

return [
    //...
    //
    Hillrange\Form\HillrangeFormBundle::class => ['all' => true],
];

```
Features
--------
###[React Form Display](Resources/doc/react.md)
Display a form using react-bootstrap-symfony

ToDo
----


