LightFramework (Core)
=====================

> **Note:** This repository contains the core code of LightFramework. If you want to build an application using LightFramework, visit the main [LightFramework repository](https://github.com/arall/LightFramework).

Requirements
------------

LightFramework requires Composer to work. Simply install composer and run composer inside the base LightFramework directory.

    $ composer install

Also, it needs the following

- PHP 5.3+
- PDO
- mod_rewrite

Tests
-----

To run the test suite, you need PHPUnit (and also Selenium):

    $ php composer.phar install --dev
    $ vendor/bin/phpunit