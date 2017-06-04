CONTRIBUTING
============

Contributions are **welcome** and be fully **credited** <3

Before submitting any pull request please make sure that the coding standards are respected and that all the specification tests are passing. 

Coding Standard
---------------
This library will use the [Symfony2 Coding Standard](http://symfony.com/doc/current/contributing/code/standards.html).

These conventions are enforced using the [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) tool. PHP-CS-Fixer is installed as a dev dependency and will therefore be available after running `composer install` or `composer update`.

``` bash
$ cd /path/to/DigitalOceanV2
$ ./vendor/bin/php-cs-fixer fix
```


Specification tests
-------------------

Install [PHPSpec](http://www.phpspec.net/) [globally](https://getcomposer.org/doc/00-intro.md#globally)
with composer and run it in the project.

```bash
$ composer global require phpspec/phpspec:@stable
$ phpspec run -fpretty
```

Generating documentation
------------------------

Before sending a pull request make sure you regenerate the documentation by running the following commmand:

```bash
$ php vendor/phpdocumentor/phpdocumentor/bin/phpdoc -d src/ -t documentation --cache-folder phpdoccache && rm -r phpdoccache/
```

**Happy coding** !
