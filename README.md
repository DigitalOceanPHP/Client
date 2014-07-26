DigitalOcean V2
===============

Let's consume the [DigitalOcean API V2](https://developers.digitalocean.com/v2/) :)

This libray is in *work in progress* as well as the *API*.

[![Build Status](https://secure.travis-ci.org/toin0u/DigitalOceanV2.png)](http://travis-ci.org/toin0u/DigitalOceanV2)
[![Latest Stable Version](https://poser.pugx.org/toin0u/digitalocean-v2/v/stable.svg)](https://packagist.org/packages/toin0u/digitalocean-v2)
[![Total Downloads](https://poser.pugx.org/toin0u/digitalocean-v2/downloads.png)](https://packagist.org/packages/toin0u/digitalocean-v2)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5b4eac7e-c83b-4913-86e1-72950821757a/mini.png)](https://insight.sensiolabs.com/projects/5b4eac7e-c83b-4913-86e1-72950821757a)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/toin0u/DigitalOceanV2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/toin0u/DigitalOceanV2/?branch=master)

Status
------

API | Documentation | Specification tests
--- | ------------- | -------------------
[Actions](https://developers.digitalocean.com/v2/#actions) | [√](https://github.com/toin0u/DigitalOceanV2#action) | √
[Domain records](https://developers.digitalocean.com/v2/#domain-records) | [√](https://github.com/toin0u/DigitalOceanV2#domain-record) | √
[Domains](https://developers.digitalocean.com/v2/#domains) | [√](https://github.com/toin0u/DigitalOceanV2#domain) | √
[Droplet actions](https://developers.digitalocean.com/v2/#droplet-actions) | [√](https://github.com/toin0u/DigitalOceanV2#droplet) | √
[Droplets](https://developers.digitalocean.com/v2/#droplets) | [√](https://github.com/toin0u/DigitalOceanV2#droplet) | √
[Image actions](https://developers.digitalocean.com/v2/#image-actions) | [√](https://github.com/toin0u/DigitalOceanV2#image) | √
[Images](https://developers.digitalocean.com/v2/#images) | [√](https://github.com/toin0u/DigitalOceanV2#image) | √
[Keys](https://developers.digitalocean.com/v2/#keys) | [√](https://github.com/toin0u/DigitalOceanV2#key) | √
[Regions](https://developers.digitalocean.com/v2/#regions) | [√](https://github.com/toin0u/DigitalOceanV2#region) | √
[Rate Limit](https://developers.digitalocean.com/#rate-limit) | [√](https://github.com/toin0u/DigitalOceanV2#rate-limit) | √
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
        "toin0u/digitalocean-v2": "0.1.*"
    }
}
```

Finally run:

```bash
$ php composer.phar require toin0u/digitalocean-v2
```

Adapter
-------

We provide a simple `BuzzAdapter` at the moment which can be tweekable by injecting your own `Browser`
and `ListenerInterface`. By default a `Curl` client will be injected in `Browser` and the `BuzzOAuthListener`
will be used.

You can also make your own adapter by extending `AbstractAdapter` and implementing `AdapterInterface`.


Example
-------

```php
<?php

require 'vendor/autoload.php';

use DigitalOceanV2\Adapter\BuzzAdapter;
use DigitalOceanV2\DigitalOceanV2;

// create an adapter with your access token which can be
// generated at https://cloud.digitalocean.com/settings/applications
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

```php
// ..
// return the domain api
$domain = $digitalocean->domain();

// return a collection of Domain entity
$domains = $domain->getAll();

// return the Domain entity 'foo.dk'
$domainFooDk = $domain->getByName('foo.dk');

// return the created Domain named 'bar.dk' and pointed to ip '127.0.0.1'
$created = $domain->create('bar.dk', '127.0.0.1');

// delete the domain named 'baz.dk'
$domain->delete('baz.dk');

```

Domain Record
-------------

```php
// ..
// return the domain record api
$domainRecord = $digitalocean->domainRecord();

// return a collection of DomainRecord entity of the domain 'foo.dk'
$domainRecords = $domainRecord->getAll('foo.dk');

// return the DomainRecord entity 123 of the domain 'foo.dk'
$domainRecord123 = $domainRecord->getById('foo.dk', 123);

// return the created DomainRecord entity of the domain 'bar.dk'
$created = $domainRecord->create('bar.dk', 'AAAA', 'bar-name', '2001:db8::ff00:42:8329');

// return the updated DomainRecord entity 123 of the domain 'baz.dk'
$updated = $domainRecord->update('baz.dk', 123, 'new-name');

// delete domain record 123 of the domain 'qmx.dk'
$domainRecord->delete('qmx.dk', 123);
```

Droplet
-------

```php
// ..
// return the droplet api
$droplet = $digitalocean->droplet();

// return a collection of Droplet entity
$droplets = $droplet->getAll();

// return the Droplet entity 123
$droplet123 = $droplet->getById(123);

// create and return the created Droplet entity
$created = $droplet->create('the-name', 'nyc1', '512mb', 449676388);

// delete the droplet 123
$droplet->delete(123);

// return a collection of Kernel entity
$kernels = $droplet->getAvailableKernels(123);

// return a collection of Image entity
$images = $droplet->getSnapshots(123);

// return a collection of Image entity
$backups = $droplet->getBackups(123);

// return a collection og Action entity of the droplet 123
$actions = $droplet->getActions(123);

// return the Action entity 456 of the droplet 123
$action123 = $droplet->getActionById(123, 456);

// delete droplet 123 and return the Action entity
$rebooted = $droplet->reboot(123);

// power cycle droplet 123 and return the Action entity
$powerCycled = $droplet->powerCycle(123);

// shutdown droplet 123 and return the Action entity
$shutdown = $droplet->shutdown(123);

// power off droplet 123 and return the Action entity
$powerOff = $droplet->powerOff(123);

// power on droplet 123 and return the Action entity
$powerOn = $droplet->powerOn(123);

// reset password droplet 123 and return the Action entity
$passwordReseted = $droplet->passwordReset(123);

// resize droplet 123 with the image 789 and return the Action entity
$resized = $droplet->resize(123, 789);

// restore droplet 123 with the image 789 and return the Action entity
$restored = $droplet->restore(123, 789);

// rebuild droplet 123 with image 789 and return the Action entity
$rebuilt = $droplet->rebuild(123, 789);

// rename droplet 123 to 'new-name' and return the Action entity
$renamed = $droplet->rename(123, 'new-name');

// take a snapshot of droplet 123 and name it 'my-snapshot'. Returns the an Action entity
$snapshot = $droplet->snapshot(123, 'my-snapshot');

// change kernel to droplet 123 with kernel 321 and return the Action entity
$kernelChanged = $droplet->changeKernel(123, 321);

// enable IPv6 to droplet 123 and return the Action entity
$ipv6Enabled = $droplet->enableIpv6(123);

// disable backups to droplet 123 and return the Action entity
$backupsDisabled = $droplet->disableBackups(123);

// enable private networking to droplet 123 and return the Action entity
$privateNetworkingEnabled = $droplet->enablePrivateNetworking(123);
```

Image
-----

```php
// ..
// return the image api
$image = $digitalOcean->image();

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
$key = $digitalOcean->key();

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
$region = $digitalOcean->region();

// return a collection of Region entity
$regions = $region->getAll();
```

Size
----

```php
// ..
// return the size api
$size = $digitalOcean->size();

// return a collection of Size entity
$sizes = $size->getAll();
```

RateLimit
---------

```php
// ..
// returns the rate limit api
$rateLimit = $digitalOcean->rateLimit();

// returns the rate limit returned by the latest request
$currentLimit = $rateLimit->getRateLimit();
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

Changelog
---------

Please see [CHANGELOG](https://github.com/toin0u/DigitalOceanV2/blob/master/CHANGELOG.md) for details.

Credits
-------

* [Antoine Corcy](https://twitter.com/toin0u)
* [Yassir Hannoun](https://twitter.com/yassirh)
* [Liverbool](https://github.com/liverbool)
* [All contributors](https://github.com/toin0u/DigitalOceanV2/contributors)

Support
-------

[Please open an issues in github](https://github.com/toin0u/DigitalOceanV2/issues)

License
-------

DigitalOceanV2 is released under the MIT License. See the bundled
[LICENSE](https://github.com/toin0u/DigitalOceanV2/blob/master/LICENSE) file for details.


Using a framework?
-------

If you are using a framework and looking for a wrapper for this library. the following projects might interest you.

* [Laravel-DigitalOcean](https://github.com/GrahamCampbell/Laravel-DigitalOcean) a Laraval wrapper By [Graham Campbell](https://github.com/GrahamCampbell)
