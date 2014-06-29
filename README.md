DigitalOcean V2
===============

Let's consume the [DigitalOcean API V2](https://github.com/digitaloceancloud/api-v2-docs) :)

This libray is in *work in progress* as well as the *API*.

[![Build Status](https://secure.travis-ci.org/toin0u/DigitalOceanV2.png)](http://travis-ci.org/toin0u/DigitalOceanV2)
[![Latest Stable Version](https://poser.pugx.org/toin0u/digitalocean-v2/v/stable.svg)](https://packagist.org/packages/toin0u/digitalocean-v2)
[![Total Downloads](https://poser.pugx.org/toin0u/digitalocean-v2/downloads.png)](https://packagist.org/packages/toin0u/digitalocean-v2)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5b4eac7e-c83b-4913-86e1-72950821757a/mini.png)](https://insight.sensiolabs.com/projects/5b4eac7e-c83b-4913-86e1-72950821757a)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/toin0u/DigitalOceanV2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/toin0u/DigitalOceanV2/?branch=master)

TODO
----

API | Documentation | Specification tests
--- | ------------- | -------------------
[Actions](https://developers.digitalocean.com/v2/#actions) | [√](https://github.com/toin0u/DigitalOceanV2#action) | √
[Domain records](https://developers.digitalocean.com/v2/#domain-records) | - | -
[Domains](https://developers.digitalocean.com/v2/#domains) | - | -
[Droplet actions](https://developers.digitalocean.com/v2/#droplet-actions) | - | -
[Droplets](https://developers.digitalocean.com/v2/#droplets) | - | -
[Image actions](https://developers.digitalocean.com/v2/#image-actions) | [√](https://github.com/toin0u/DigitalOceanV2#image) | √
[Images](https://developers.digitalocean.com/v2/#images) | [√](https://github.com/toin0u/DigitalOceanV2#image) | √
[Keys](https://developers.digitalocean.com/v2/#keys) | [√](https://github.com/toin0u/DigitalOceanV2#key) | √
[Regions](https://developers.digitalocean.com/v2/#regions) | [√](https://github.com/toin0u/DigitalOceanV2#region) | √
[Sizes](https://developers.digitalocean.com/v2/#sizes) | [√](https://github.com/toin0u/DigitalOceanV2#size) | √

Installation
------------

This library can be found on [Packagist](https://packagist.org/packages/toin0u/digitalocean-v2).
The recommended way to install this is through [composer](http://getcomposer.org).

Run these commands to install composer, the library and its dependencies:

```bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar require toin0u/digitalocean-v2:@dev
```

Or edit `composer.json` and add:

```json
{
    "require": {
        "toin0u/digitalocean-v2": "@dev"
    }
}
```

Example
-------

```php
<?php

require 'vendor/autoload.php';

use DigitalOceanV2\Adapter\BuzzAdapter;
use DigitalOceanV2\DigitalOceanV2;

// create an adapter with your access token
$adapter = new BuzzAdapter('your_access_token');

// create a digital ocean object with the previous adapter
$digitalOcean = new DigitalOceanV2($adapter);

// ...
```

Action
------

```php
// ..
// return the action api
$action  = $digitalOcean->action();

// return a collection of Action entity
$actions = $action->getAll();

// return the Action entity 123
$action123 = $action->getById(123);
```

Domain
------

n/a

Domain Record
-------------

n/a

Droplet
-------

n/a

Image
-----

```php
// ..
// return the image api
$image  = $digitalOcean->image();

// return a collection of Image entity
$images = $image->getAll();

// return the Image entity 123
$image123 = $image->getById(123);

// return the Image entity with the given image slug
$imageFoobar = $image->getBySlug('foobar');

// return the updated Image entity
$updatedImage = $image->update(123, 'new-name');

// delete the image 123
$image->delete(123);

// return the Action entity of the transfered image 123 to the given region slug
$transferedImage = $image->transfer(123, 'region-slug');

// return the Action entity 456 of the image 123
$actionImage = $image->getAction(123, 456);
```

Key
---

```php
// ..
// return the key api
$key  = $digitalOcean->key();

// return a collection of Key entity
$keys = $key->getAll();

// return the Key entity 123
$key123 = $key->getById(123);

// return the Key entity with the given fingerprint
$key = $key->getByFingerprint('f5:de:eb:64:2d:6a:b6:d5:bb:06:47:7f:04:4b:f8:e2');

// return the created Key entity
$createdKey = $key->create('my-key', 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDPrtBjQaNBwDSV3ePC86zaEWu0....');

// return the updated Key entity
$updatedKey = $key->update(123, 'new-key-name');

// return void if deleted successfully
$key->delete(123);
```

Region
------

```php
// ..
// return the region api
$region  = $digitalOcean->region();

// return a collection of Region entity
$regions = $region->getAll();
```

Size
----

```php
// ..
// return the size api
$size  = $digitalOcean->size();

// return a collection of Size entity
$sizes = $size->getAll();
```

Specification tests
-------------------

Install [PHPSpec](http://www.phpspec.net/) [globally](https://getcomposer.org/doc/00-intro.md#globally)
with composer and run it in the project.

```bash
$ composer global require phpspec/phpspec:@stable
$ phpspec run -fpretty
```

Contributing
------------

Please see [CONTRIBUTING](https://github.com/toin0u/DigitalOceanV2/blob/master/CONTRIBUTING.md) for details.

Credits
-------

* [Antoine Corcy](https://twitter.com/toin0u)
* [Yassir Hannoun](https://twitter.com/yassirh)
* [All contributors](https://github.com/toin0u/DigitalOceanV2/contributors)

Support
-------

[Please open an issues in github](https://github.com/toin0u/DigitalOceanV2/issues)

License
-------

DigitalOceanV2 is released under the MIT License. See the bundled
[LICENSE](https://github.com/toin0u/DigitalOceanV2/blob/master/LICENSE) file for details.
