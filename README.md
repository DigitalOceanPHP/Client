DigitalOcean V2
===============

Let's consume the [DigitalOcean API V2](https://developers.digitalocean.com/v2/) ([issues](https://github.com/digitalocean/api-v2/issues) /
[changelog](https://developers.digitalocean.com/documentation/changelog/)) :)

[![Build Status](https://secure.travis-ci.org/toin0u/DigitalOceanV2.png)](http://travis-ci.org/toin0u/DigitalOceanV2)
[![Latest Stable Version](https://poser.pugx.org/toin0u/digitalocean-v2/v/stable.svg)](https://packagist.org/packages/toin0u/digitalocean-v2)
[![Total Downloads](https://poser.pugx.org/toin0u/digitalocean-v2/downloads.png)](https://packagist.org/packages/toin0u/digitalocean-v2)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5b4eac7e-c83b-4913-86e1-72950821757a/mini.png)](https://insight.sensiolabs.com/projects/5b4eac7e-c83b-4913-86e1-72950821757a)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/toin0u/DigitalOceanV2/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/toin0u/DigitalOceanV2/?branch=master)
[![License](https://poser.pugx.org/toin0u/digitalocean-v2/license.svg)](https://packagist.org/packages/toin0u/digitalocean-v2)

Status
------

API | Documentation | Specification tests
--- | ------------- | -------------------
[Account](https://developers.digitalocean.com/v2/#account) | [√](https://github.com/toin0u/DigitalOceanV2#account) | √
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
$ php composer.phar require toin0u/digitalocean-v2:~1.0
```

You then need to install **one** of the following:
```bash
$ php composer.phar require kriswallsmith/buzz:~0.10
$ php composer.phar require guzzle/guzzle:~3.7
$ php composer.phar require guzzlehttp/guzzle:~6.0
```

Or edit `composer.json` and add:

```json
{
    "require": {
        "toin0u/digitalocean-v2": "~1.0"
    }
}
```

And then add **one** of the following:

```json
{
    "require": {
        "kriswallsmith/buzz": "~0.10",
        "guzzle/guzzle": "~3.7",
        "guzzlehttp/guzzle" : "~6.0"
    }
}
```

### Using Laravel? ###

[Laravel DigitalOcean](https://github.com/GrahamCampbell/Laravel-DigitalOcean) by [Graham Campbell](https://github.com/GrahamCampbell) might interest you.

```json
{
    "require": {
        "graham-campbell/digitalocean": "~2.0"
    }
}
```

### Using Symfony2? ###

For Symfony2 users, there is no need to create separate bundle (But you could if you wanted to).Just install `toin0u/digitalocean-v2` via composer and define it as a service. For example:

```yaml
# YourBundle/Resources/config/services.yml
parameters:
    do.class.factory: DigitalOceanV2\DigitalOceanV2
    do.class.adapter: DigitalOceanV2\Adapter\GuzzleAdapter
    ....

services:
    do.adapter:
        class: %do.class.adapter%
        public: false
        arguments: [api_secret]

    do.factory:
        class: %do.class.factory%
        arguments: [@do.adapter]

    ....
```

Now you can use in container.

```php
$droplet = $this->container->get('do.factory')->droplet();
```

Or you could define your service api one by one using a class factory.

```yaml
parameters:
    ....
    do.class.api.droplet: DigitalOceanV2\Api\Droplet
    do.class.api.action: DigitalOceanV2\Api\Action
    do.class.api.domain: ...

services:
    ....

    do.droplet:
        class: %do.class.api.droplet%
        factory_service: do.factory
        factory_method: droplet

    do.action:
        class: %do.class.api.action%
        factory_service: do.factory
        factory_method: action

    do.domain:
        ....
```

And now you can use it in the container as

```php
$droplets = $this->container->get('do.droplet')->getAll();
```

> This is helpful for a child of `Symfony\Bundle\FrameworkBundle\Controller\Controller` user to use by `$this->get('do.droplet')->getAll()`

Adapter
-------

We provide a simple `BuzzAdapter`  which (at the moment) can be tweaked by injecting your own `Browser`
and `ListenerInterface`. By default a `Curl` client will be injected in `Browser` and the `BuzzOAuthListener`
will be used.

To wrap your own response message, please inject your own `ExceptionInterface` if needed (see `DigitalOceanV2\Exception\ResponseException` for more info).

If you use Guzzle, you can inject your own client to our `GuzzleAdapter`.

You can also build your own adapter by extending `AbstractAdapter` and implementing `AdapterInterface`.

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
$digitalocean = new DigitalOceanV2($adapter);

// ...
```

Entities
--------

Every entity has the `getUnknownProperties` method which will return an `array` of properties set with unknown
properties by the entity. This will prevent the library to
[fail](https://github.com/toin0u/DigitalOceanV2/pull/79#issuecomment-74075046). This should be removed when the API
will be released as stable.

Account
-------

```php
// ...
// return the account api
$account = $digitalocean->account();

// return the Account entity
$userInformation = $account->getUserInformation();
````

Action
------

```php
// ..
// return the action api
$action  = $digitalocean->action();

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

// return a collection of Droplet neighbor to Droplet entity 123
$droplets = $droplet->getNeighborsById(123);

// return a collection of Droplet that are running on the same physical hardware
$neighbors = $droplet->getAllNeighbors();

// return a collection of Upgrade entity
$upgrades = $droplet->getUpgrades();

// return the Droplet entity 123
$droplet123 = $droplet->getById(123);

// create and return the created Droplet entity
$created = $droplet->create('the-name', 'nyc1', '512mb', 449676388);

// create and return the created Droplet entity using an image slug
$created = $droplet->create('the-name', 'nyc1', '512mb', 'ubuntu-14-04-x64');

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

// reboot droplet 123 and return the Action entity
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
$image = $digitalocean->image();

// return a collection of Image entity
$images = $image->getAll();

// return a collection of distribution Image entity
$images = $image->getAll(['type' => 'distribution']);

// return a collection of application Image entity
$images = $image->getAll(['type' => 'application']);

// return a collection of private Image entity
$images = $image->getAll(['private' => true]);

// return a collection of private application Image entity
$images = $image->getAll(['type' => 'application', 'private' => true]);

// return the Image entity 123
$image123 = $image->getById(123);

// return the Image entity with the given image slug
$imageFoobar = $image->getBySlug('foobar');

// return the updated Image entity
$updatedImage = $image->update(123, 'new-name');

// delete the image 123
$image->delete(123);

// return the Action entity of the transferred image 123 to the given region slug
$transferredImage = $image->transfer(123, 'region-slug');

// return the Action entity 456 of the image 123
$actionImage = $image->getAction(123, 456);
```

Key
---

```php
// ..
// return the key api
$key = $digitalocean->key();

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
$region = $digitalocean->region();

// return a collection of Region entity
$regions = $region->getAll();
```

Size
----

```php
// ..
// return the size api
$size = $digitalocean->size();

// return a collection of Size entity
$sizes = $size->getAll();
```

RateLimit
---------

```php
// ..
// returns the rate limit api
$rateLimit = $digitalocean->rateLimit();

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

[Please open an issue in github](https://github.com/toin0u/DigitalOceanV2/issues)

Contributor Code of Conduct
---------------------------

As contributors and maintainers of this project, we pledge to respect all people
who contribute through reporting issues, posting feature requests, updating
documentation, submitting pull requests or patches, and other activities.

We are committed to making participation in this project a harassment-free
experience for everyone, regardless of level of experience, gender, gender
identity and expression, sexual orientation, disability, personal appearance,
body size, race, age, or religion.

Examples of unacceptable behavior by participants include the use of sexual
language or imagery, derogatory comments or personal attacks, trolling, public
or private harassment, insults, or other unprofessional conduct.

Project maintainers have the right and responsibility to remove, edit, or reject
comments, commits, code, wiki edits, issues, and other contributions that are
not aligned to this Code of Conduct. Project maintainers who do not follow the
Code of Conduct may be removed from the project team.

Instances of abusive, harassing, or otherwise unacceptable behavior may be
reported by opening an issue or contacting one or more of the project
maintainers.

This Code of Conduct is adapted from the [Contributor
Covenant](http:contributor-covenant.org), version 1.0.0, available at
[http://contributor-covenant.org/version/1/0/0/](http://contributor-covenant.org/version/1/0/0/)

License
-------

DigitalOceanV2 is released under the MIT License. See the bundled
[LICENSE](https://github.com/toin0u/DigitalOceanV2/blob/master/LICENSE) file for details.
