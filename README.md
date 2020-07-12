# DigitalOcean PHP API Client

We present a modern [DigitalOcean API v2](https://developers.digitalocean.com/documentation/v2/) client for PHP.

![Banner](https://user-images.githubusercontent.com/2829600/86969008-fcc6d180-c164-11ea-9864-5ffd9caf2c6b.png)

<p align="center">
<a href="https://github.com/DigitalOceanPHP/Client/actions?query=workflow%3ATests"><img src="https://img.shields.io/github/workflow/status/DigitalOceanPHP/Client/Tests?label=Tests&style=flat-square" alt="Build Status"></img></a>
<a href="https://github.styleci.io/repos/20703714"><img src="https://github.styleci.io/repos/20703714/shield" alt="StyleCI Status"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square" alt="Software License"></img></a>
<a href="https://packagist.org/packages/toin0u/digitalocean-v2"><img src="https://img.shields.io/packagist/dt/toin0u/digitalocean-v2?style=flat-square" alt="Packagist Downloads"></img></a>
<a href="https://github.com/DigitalOceanPHP/Client/releases"><img src="https://img.shields.io/github/release/DigitalOceanPHP/Client?style=flat-square" alt="Latest Version"></img></a>
</p>

Check out the [change log](CHANGELOG.md), [releases](https://github.com/DigitalOceanPHP/Client/releases), [security policy](https://github.com/DigitalOceanPHP/Client/security/policy), [license](LICENSE), [code of conduct](.github/CODE_OF_CONDUCT.md), and [contribution guidelines](.github/CONTRIBUTING.md).


## Installation

This version supports [PHP](https://php.net) 7.1-7.4. To get started, simply require the project using [Composer](https://getcomposer.org). You will also need to install either [Guzzle](https://docs.guzzlephp.org) or [Buzz](https://github.com/kriswallsmith/Buzz) to enable us to send HTTP requests. Laravel users should install [graham-campbell/digitalocean](https://github.com/GrahamCampbell/Laravel-DigitalOcean) by [Graham Campbell](https://github.com/GrahamCampbell).

### Standard Installation

#### Using Guzzle 6:

```
$ composer require toin0u/digitalocean-v2:^3.0 guzzlehttp/guzzle:^6.3.1
```

#### Using Guzzle 7:

```
$ composer require toin0u/digitalocean-v2:^3.0 guzzlehttp/guzzle:^7.0.1
```

#### Using Buzz 0.16:

```
$ composer require toin0u/digitalocean-v2:^3.0 kriswallsmith/buzz:^0.16
```

### Framework Integration

#### Laravel 6+:

```
$ composer require graham-campbell/digitalocean:^7.0
```


## Upgrading

If you are upgrading from version 2.3 to 3.0, you can check out our [upgrading guide](UPGRADING.md). We highly recommend upgrading as soon as possible.

## Examples

As of version 3.0, we will will automatically discover an HTTP client to use, from what you have available. Simply create a new DigitalOcean client, provide your access token, then you're good to go:

```php
<?php

require_once 'vendor/autoload.php';

// create a new DigitalOcean client
$client = new DigitalOceanV2\Client();

// authenticate the client with your access token which can be
// generated at https://cloud.digitalocean.com/settings/applications
$client->authenticate('your_access_token');
```

Version 3.0 also has a built-in paginator, and can be used on any of the APIs which return collections. By default, the pager will internally attempt to fetch 200 entries in each request, however this can be configured by passing a 2nd parameter to the constructor. We have included an example below which will fetch all droplets:

```php
// create a new result pager
$pager = new DigitalOceanV2\ResultPager($client);

// get all droplets as an array
$droplets = $pager->fetchAll($client->droplet(), 'getAll');

// get all droplets as a Generator which lazily yields
// new results as they become available
$droplets = $pager->fetchAllLazy($client->droplet(), 'getAll');
```

### Account

```php
// return the account api
$account = $client->account();

// return the Account entity
$userInformation = $account->getUserInformation();
````

### Action

```php
// return the action api
$action  = $client->action();

// return a collection of Action entity
$actions = $action->getAll();

// return the Action entity 123
$action123 = $action->getById(123);
```

### Domain

```php
// return the domain api
$domain = $client->domain();

// return a collection of Domain entity
$domains = $domain->getAll();

// return the Domain entity 'foo.dk'
$domainFooDk = $domain->getByName('foo.dk');

// return the created Domain named 'bar.dk' and pointed to ip '127.0.0.1'
$created = $domain->create('bar.dk', '127.0.0.1');

// remove the domain named 'baz.dk'
$domain->remove('baz.dk');
```

### Domain Record

```php
// return the domain record api
$domainRecord = $client->domainRecord();

// return a collection of DomainRecord entity of the domain 'foo.dk'
$domainRecords = $domainRecord->getAll('foo.dk');

// return the DomainRecord entity 123 of the domain 'foo.dk'
$domainRecord123 = $domainRecord->getById('foo.dk', 123);

// return the created DomainRecord entity of the domain 'bar.dk'
$created = $domainRecord->create('bar.dk', 'AAAA', 'bar-name', '2001:db8::ff00:42:8329');

// return the DomainRecord entity 123 of the domain 'baz.dk' updated with new-name, new-data, priority 1, port 2, weight 3, flags 0, tag issue (name, data, priority, port, weight, flags and tag are nullable)
$updated = $domainRecord->update('baz.dk', 123, 'new-name', 'new-data', 1, 2, 3, 0, 'issue');

// remove domain record 123 of the domain 'qmx.dk'
$domainRecord->remove('qmx.dk', 123);
```

### Droplet

```php
// return the droplet api
$droplet = $client->droplet();

// return a collection of Droplet entity
$droplets = $droplet->getAll();

// return a collection of Droplet neighbor to Droplet entity 123
$droplets = $droplet->getNeighborsById(123);

// return a collection of Droplet that are running on the same physical hardware
$neighbors = $droplet->getAllNeighbors();

// return the Droplet entity 123
$droplet123 = $droplet->getById(123);

// create and return the created Droplet entity
$created = $droplet->create('the-name', 'nyc1', '512mb', 449676388);

// create and return the created Droplet entity using an image slug
$created = $droplet->create('the-name', 'nyc1', '512mb', 'ubuntu-14-04-x64');

// remove the droplet 123
$droplet->remove(123);

// return a collection of Kernel entity
$kernels = $droplet->getAvailableKernels(123);

// return a collection of Image entity
$images = $droplet->getSnapshots(123);

// return a collection of Image entity
$backups = $droplet->getBackups(123);

// return a collection of Action entity of the droplet 123
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

### Image

```php
// return the image api
$image = $client->image();

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

// remove the image 123
$image->remove(123);

// return the Action entity of the transferred image 123 to the given region slug
$transferredImage = $image->transfer(123, 'region-slug');

// return the Action entity 456 of the image 123
$actionImage = $image->getAction(123, 456);
```

### Key

```php
// return the key api
$key = $client->key();

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

// return void if removed successfully
$key->remove(123);
```

### Load Balancer

```php
// return the load balancer api
$loadBalancer = $client->loadbalancer();

//returns a collection of Load Balancer entities
$loadBalancers = $loadBalancer->getAll();

//return a Load Balancer entity by id
$myLoadBalancer = $loadBalancer->getById('506f78a4-e098-11e5-ad9f-000f53306ae1');

/**
* updates an existing load balancer, the method will except a LoadBalancer
* entity or a load balancer representation in array form, the digitial
* Ocean API requires a full representation of your load
* balancer, any attribute that is missing will
* be reset to it's default setting.
*/
$myUpdatedLoadBalancer = $loadBalancer->update('506f78a4-e098-11e5-ad9f-000f53306ae1', $myLoadBalancer);

//create a standard load balancer that listens on port 80 and 443 with ssl passthrough enabled
$myNewLoadBalancer = $loadBalancer->create('my-new-load-balancer', 'nyc1');
```

### Region

```php
// return the region api
$region = $client->region();

// return a collection of Region entity
$regions = $region->getAll();
```

### Size

```php
// return the size api
$size = $client->size();

// return a collection of Size entity
$sizes = $size->getAll();
```

### Tag

```php
// return the tag api
$tag = $client->tag();

// return a collection of Tag entity
$tags = $tag->getAll();

// return a Tag entity by name
$tag = $tag->getByName();

// create a tag
$tag = $tag->create('awesome');

// tag resources
$tag->tagResources('awesome', [["resource_id" => "9569411", "resource_type" => "droplet"]]);

// untag resources
$tag->untagResources('awesome', [["resource_id" => "9569411", "resource_type" => "droplet"]]);

// remove tag
$tag->remove('awesome');
```

### Volume

```php
// return the volume api
$volume = $client->volume();

// returns the all volumes
$volumes = $volume->getAll();

// returns the all volumes by region
$volumes = $volume->getAll('nyc1');

// returns volumes by name and region
$volumes = $volume->getByNameAndRegion('example', 'nyc1');

// returns a volume by id
$myvolume = $volume->getById('506f78a4-e098-11e5-ad9f-000f53306ae1');

// returns a volumes snapshots by volume id
$mySnapshots = $volume->getSnapshots('506f78a4-e098-11e5-ad9f-000f53306ae1');

// creates a volume
$myvolume = $volume->create('example', 'Block store for examples', 10, 'nyc1');

// removes a volume by id
$volume->remove('506f78a4-e098-11e5-ad9f-000f53306ae1');

// removes a volume by name and region
$volume->remove('example', 'nyc1');

// attach a volume to a Droplet 
$volume->attach('506f78a4-e098-11e5-ad9f-000f53306ae1', 123, 'nyc1');

// detach a volume from a Droplet 
$volume->detach('506f78a4-e098-11e5-ad9f-000f53306ae1', 123, 'nyc1');

// resize a volume 
$volume->resize('506f78a4-e098-11e5-ad9f-000f53306ae1', 20, 'nyc1');

// take a snapshot of volume and name it 'my-snapshot'. Returns the Snapshot entity
$snapshot = $volume->snapshot('506f78a4-e098-11e5-ad9f-000f53306ae1', 'my-snapshot');

// get a volume action by its id 
$volume->getActionById(123, '506f78a4-e098-11e5-ad9f-000f53306ae1');

// get all actions related to a volume
$volume->getActions('506f78a4-e098-11e5-ad9f-000f53306ae1');
```


## Contributing

We will gladly receive issue reports and review and accept pull requests, in accordance with our [code of conduct](.github/CODE_OF_CONDUCT.md) and [contribution guidelines](.github/CONTRIBUTING.md)!

```
$ make install
$ make test
```


## Security

If you discover a security vulnerability within this package, please send an email to Graham Campbell at graham@alt-three.com or Glenn Jacobs at glenn@neondigital.co.uk. All security vulnerabilities will be promptly addressed. You may view our full security policy [here](https://github.com/DigitalOceanPHP/Client/security/policy).


## License

DigitalOcean PHP API Client is licensed under [The MIT License (MIT)](LICENSE).
