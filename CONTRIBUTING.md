CONTRIBUTING
============

Contributions are **welcome** and be fully **credited** <3

Before submitting any pull request please make sure that the coding standards are respected and that all the specification tests are passing. 

Coding Standard
---------------
This library will use the [Symfony2 Coding Standard](http://symfony.com/doc/current/contributing/code/standards.html).

Specification tests
-------------------

Install [PHPSpec](http://www.phpspec.net/) [globally](https://getcomposer.org/doc/00-intro.md#globally)
with composer and run it in the project.

```bash
$ composer global require phpspec/phpspec:@stable
$ phpspec run -fpretty
```

Running test in docker container
--------------------------------
```bash
$ docker run -it --rm -v $(pwd):/var/www -w /var/www php bash
$ apt update && apt install -y git unzip
$ docker-php-ext-install zip
$ curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
$ composer global require phpspec/phpspec:@stable
$ phpspec run -fpretty
```

**Happy coding** !
