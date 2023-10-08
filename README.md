# DigitalOcean PHP API Client

We present a modern [DigitalOcean API v2](https://developers.digitalocean.com/documentation/v2/) client for PHP.

![Banner](https://user-images.githubusercontent.com/2829600/86969008-fcc6d180-c164-11ea-9864-5ffd9caf2c6b.png)

<p align="center">
<a href="https://github.com/DigitalOceanPHP/Client/actions?query=workflow%3ATests"><img src="https://img.shields.io/github/actions/workflow/status/DigitalOceanPHP/Client/tests.yml?label=Tests&style=flat-square" alt="Build Status"></img></a>
<a href="https://github.styleci.io/repos/20703714"><img src="https://github.styleci.io/repos/20703714/shield" alt="StyleCI Status"></img></a>
<a href="LICENSE"><img src="https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square" alt="Software License"></img></a>
<a href="https://packagist.org/packages/toin0u/digitalocean-v2"><img src="https://img.shields.io/packagist/dt/toin0u/digitalocean-v2?style=flat-square" alt="Packagist Downloads"></img></a>
<a href="https://github.com/DigitalOceanPHP/Client/releases"><img src="https://img.shields.io/github/release/DigitalOceanPHP/Client?style=flat-square" alt="Latest Version"></img></a>
</p>

Check out the [change log](CHANGELOG.md), [releases](https://github.com/DigitalOceanPHP/Client/releases), [security policy](https://github.com/DigitalOceanPHP/Client/security/policy), [license](LICENSE), [code of conduct](.github/CODE_OF_CONDUCT.md), and [contribution guidelines](.github/CONTRIBUTING.md).


## Installation

This version supports [PHP](https://php.net) 7.4-8.3. To get started, simply require the project using [Composer](https://getcomposer.org). You will also need to install packages that "provide" [`psr/http-client-implementation`](https://packagist.org/providers/psr/http-client-implementation) and [`psr/http-factory-implementation`](https://packagist.org/providers/psr/http-factory-implementation).

### Standard Installation

```bash
$ composer require "toin0u/digitalocean-v2:^4.8" \
  "guzzlehttp/guzzle:^7.8" "http-interop/http-factory-guzzle:^1.2"
```

### Framework Integration

#### Laravel:

```bash
$ composer require "graham-campbell/digitalocean:^10.2"
```

We are decoupled from any HTTP messaging client by using [PSR-7](https://www.php-fig.org/psr/psr-7/), [PSR-17](https://www.php-fig.org/psr/psr-17/), [PSR-18](https://www.php-fig.org/psr/psr-18/), and [HTTPlug](https://httplug.io/). You can visit [HTTPlug for library users](https://docs.php-http.org/en/latest/httplug/users.html) to get more information about installing HTTPlug related packages. The framework integration [graham-campbell/gitlab](https://github.com/GrahamCampbell/Laravel-GitLab) is by [Graham Campbell](https://github.com/GrahamCampbell) and [dunglas/digital-ocean-bundle](https://github.com/dunglas/DunglasDigitalOceanBundle) is by [Kévin Dunglas](https://github.com/dunglas).

## Upgrading

If you are upgrading from version 2.3 to 3.0, or from 3.2 to 4.0, you can check out our [upgrading guide](UPGRADING.md). We highly recommend upgrading as soon as possible.

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

Version 3.0 also has a built-in paginator, and can be used on any of the APIs which return collections. By default, the pager will internally attempt to fetch 100 entries in each request, however this can be configured by passing a 2nd parameter to the constructor. We have included an example below which will fetch all droplets:

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
```

### Action

```php
// return the action api
$action  = $client->action();

// return a collection of Action entity
$actions = $action->getAll();

// return the Action entity 123
$action123 = $action->getById(123);
```

### App

```php
// return the app api
$app = $client->app();

// return a collection of Apps
$apps = $app->getAll();

// return the App entity 123
$app123 = $app->getById(123);

// create a new App
$spec = [
    "name" => "sample-golang",
    "services" => [
        [
            "name" => "web",
            "github" => [
              "repo" => "digitalocean/sample-golang",
              "branch" => "branch"
            ],
            "run_command" => "bin/sample-golang",
            "environment_slug" => "go",
            "instance_size_slug" => "basic-xxs",
            "instance_count" => 2,
            "routes" => [
                [
                    "path" => "/"
                ]
            ]
        ]
    ],
    "region" => "ams"
];
$app = $app->create($spec);

// update an App with App entity 123
$app = $app->update(123, $spec);

// delete an App with App entity 123
$app->remove(123);

// retrieve App deployments with App entity 123
$deployments = $app->getAppDeployments(123);

// return an App deployment with App entity 123, deployment ID of 456
$deployment = $app->getAppDeployment(123,456);

// create an App deployment with App entity 123
$deployment = $app->createAppDeployment(123);

// cancel an App deployment
$deployment = $app->cancelAppDeployment(123,456);

// retrieve deployment logs by component for entity 123, deployment ID of 456, component name of "test_component"
$logs = $app->getDeploymentLogs(123,456,"test_component");

// retrieve aggregate deployment logs for entity 123, deployment ID of 456
$logs = $app->getAggregateDeploymentLogs(123,456);

// retrieve App regions
$regions = $app->getRegions();

// retrieve App tiers
$tiers = $app->getTiers();

// return an App tier by slug with slug name of "test_slug"
$tier = $app->getTierBySlug("test_slug");

// retrieve App instance sizes
$instance_sizes = $app->getInstanceSizes();

// return App instance size by slug with slug name of "test_slug"
$instance_size = $app->getInstanceSizeBySlug("test_slug");
```

### Database

```php
// return the database api
$database = $client->database();

// return a collection of DatabaseCluster entity
$clusters = $database->getAllClusters();

// return a collection of DatabaseCluster entity filtered by 'tag-name'
$clusters = $database->getAllClusters('tag-name');

// return the DatabaseCluster entity '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$myCluster = $database->getClusterById('405427f6-393a-4744-817a-2ec6c1b2e2c2');

// return the created DatabaseCluster entity
$createdCluster = $database->createCluster('my-redis-cluster', 'redis', 'db-s-1vcpu-1gb', 'fra1', 1);

// return the created DatabaseCluster entity with optional parameters
$anotherCluster = $database->createCluster('my-redis-cluster', 'redis', 'db-s-1vcpu-1gb', 'fra1', 1, "5", ['tag-1', 'tag-2'], 'daf994c2-1a34-4a94-beca-f43d09f63eb6');

// resize database cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$database->resize('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'db-s-1vcpu-2gb', 2);

// migrate database cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2' to the 'lon1' region
$database->migrate('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'lon1');

// remove database cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$database->remove('405427f6-393a-4744-817a-2ec6c1b2e2c2');

// return the DatabaseRules entity of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$rules = $database->getFirewallRules('405427f6-393a-4744-817a-2ec6c1b2e2c2')

// update firewall rules of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$database->updateFirewallRules('405427f6-393a-4744-817a-2ec6c1b2e2c2', [
    ["type" => 'ip_addr', "value" => '192.168.1.1'],
    ["type" => 'droplet', "value" => '163973392'],
]);

// update maintenance window of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$database->updateMaintenanceWindow('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'thursday', "21:00");

// return a collection of DatabaseBackup entity of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$backups = $database->getBackups('405427f6-393a-4744-817a-2ec6c1b2e2c2');

// create a new database cluster based on the 'origin-cluster' cluster backup and return a DatabaseCluster entity for the newly created cluster
$restored = $database->createClusterFromBackup('cluster-from-backup', ["database_name" => 'origin-cluster', "backup_created_at" => '2020-09-14T08:11:29Z'], 'mysql', 'db-s-1vcpu-1gb', 'fra1', 1, null, ['tag-1', 'tag-2']);

// return a collection of DatabaseReplica entity for cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$replicas = $database->getAllReplicas('405427f6-393a-4744-817a-2ec6c1b2e2c2');

// return the DatabaseReplica enitity named 'my-replica' of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$replica = $database->getReplicaByName('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-replica');

// return the created DatabaseReplica entity of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$createdReplica = $database->createReplica('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-replica', 'db-s-1vcpu-1gb');

// remove replica named 'my-replica' of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$database->removeReplica('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-replica');

// return a collection of DatabaseUser entity for cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$users = $database->getAllUsers('405427f6-393a-4744-817a-2ec6c1b2e2c2');

// return the DatabaseUser entity named 'my-user' of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$user = $database->getUserByName('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-user');

// return the created DatabaseUser entity named 'my-user' of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$createdUser = $database->createUser('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-user');

// return the created DatabaseUser entity named 'my-user' of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2' with optional mysql auth plugin
$createdUser2 = $database->createUser('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-user-2', 'mysql_native_password');

// return the updated DatabaseUser entity named 'my-user' of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$updatedUser = $database->updateUserMysqlAuthMethod('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-user-2', 'caching_sha2_password');

// remove user named 'my-user' of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$database->removeUser('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-user');

// return a collection of Database entity of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$dbs = $database->getAllDatabases('405427f6-393a-4744-817a-2ec6c1b2e2c2');

// return the Database entity named 'my-database' of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$db = $database->getDatabaseByName('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-database');

// return the created Database entity named 'my-database' of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$createdDb = $database->createDatabase('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-database');

// remove the database named 'my-database' of cluster '405427f6-393a-4744-817a-2ec6c1b2e2c2'
$database->removeDatabase('405427f6-393a-4744-817a-2ec6c1b2e2c2', 'my-database');


// ** Only for PostgreSQL Database Clusters ** //

// return a collection of DatabasePool entity of cluster 'cd2184a9-8f05-49f4-9700-293efb81c7fd'
$pools = $database->getAllConnectionPools('cd2184a9-8f05-49f4-9700-293efb81c7fd');

// return the DatabasePool entity named 'my-pool' of cluster 'cd2184a9-8f05-49f4-9700-293efb81c7fd'
$pool = $database->getConnectionPoolByName('cd2184a9-8f05-49f4-9700-293efb81c7fd', 'my-pool');

// return the created DatabasePool entity for cluster 'cd2184a9-8f05-49f4-9700-293efb81c7fd'
$createdPool = $database->createConnectionPool('cd2184a9-8f05-49f4-9700-293efb81c7fd', 'my-pool', 'transaction', 1, 'defaultdb', 'doadmin');

// remove pool named 'my-pool' of cluster 'cd2184a9-8f05-49f4-9700-293efb81c7fd'
$pools = $database->removeConnectionPool('cd2184a9-8f05-49f4-9700-293efb81c7fd', 'my-pool');


// ** Only for Redis Database Clusters ** //

// return an object with a 'evictionPolicy' key for cluster '3a9e419c-e38e-40ef-8f56-09b4254b80e2'
$policy = $database->getEvictionPolicy('3a9e419c-e38e-40ef-8f56-09b4254b80e2');

// update eviction policy for cluster '3a9e419c-e38e-40ef-8f56-09b4254b80e2'
$database->updateEvictionPolicy('3a9e419c-e38e-40ef-8f56-09b4254b80e2', 'allkeys_random');


// ** Only for MySQL Database Clusters ** //

// return an object with a 'sqlMode' key for cluster 'd448b69d-3d06-411a-8ac7-c16132ba0f1e'
$mode = $database->getSqlMode('d448b69d-3d06-411a-8ac7-c16132ba0f1e');

// update sql mode for cluster 'd448b69d-3d06-411a-8ac7-c16132ba0f1e'
$database->updateSqlMode('d448b69d-3d06-411a-8ac7-c16132ba0f1e', 'ANSI,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION,NO_ZERO_DATE,NO_ZERO_IN_DATE');
```

### CDN Endpoint

```php
// return the cdn endpoint api
$cdnEndpoint = $client->cdnEndpoint();

// return a collection of CdnEndpoint entity
$cdnEndpoints = $cdnEndpoint->getAll();

// return the CdnEndpoint entity '8d077fd4-e67e-409c-b927-aa92dfaf0e69'
$cdnEndpoint8d077fd4 = $cdnEndpoint->getById('8d077fd4-e67e-409c-b927-aa92dfaf0e69');

// return the created CdnEndpoint with origin 'fake-cdn.nyc3.digitaloceanspaces.com', ttl 1800, certificate id '892071a0-bb95-49bc-8021-3afd67a210bf' and custom domain 'fake-cdn.example.com'
$created = $cdnEndpoint->create('fake-cdn.nyc3.digitaloceanspaces.com', 1800, '892071a0-bb95-49bc-8021-3afd67a210bf', 'fake-cdn.example.com');

// return the CdnEndpoint with id '8d077fd4-e67e-409c-b927-aa92dfaf0e69' updated with ttl 3600, certificate id '08dad2a2-a588-4558-976a-18fc36076520' and custom domain 'fake-cdn-2.example.com'
$updated = $cdnEndpoint->update('8d077fd4-e67e-409c-b927-aa92dfaf0e69', 3600, '08dad2a2-a588-4558-976a-18fc36076520', 'fake-cdn-2.example.com');

// remove the cdnEndpoint identified by '8d077fd4-e67e-409c-b927-aa92dfaf0e69'
$cdnEndpoint->remove('8d077fd4-e67e-409c-b927-aa92dfaf0e69');

// purge the cache of cdnEndpoint identified by '8d077fd4-e67e-409c-b927-aa92dfaf0e69'
$cdnEndpoint->purgeCache('8d077fd4-e67e-409c-b927-aa92dfaf0e69');

// purge the following files in the cache of cdnEndpoint identified by '8d077fd4-e67e-409c-b927-aa92dfaf0e69'
$cdnEndpoint->purgeCache('8d077fd4-e67e-409c-b927-aa92dfaf0e69', ["assets/img/hero.png", "assets/css/*"]);
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

// remove all droplets with a given tag
$droplet->removeTagged('awesome');

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

### Firewall

```php
// return the firewall api
$firewall = $client->firewall();

// return the Firewall entity 'abc-123'
$firewall_abc123 = $firewall->getById('abc-123');

// create a firewall with some defaults, assign it to a droplet, and return the Firewall entity
$inboundRules = [
    ['protocol' => 'tcp', 'ports' => '22', 'sources' => ['addresses' => ['0.0.0.0/0', '::/0']]],
    ['protocol' => 'tcp', 'ports' => 'all', 'sources' => ['addresses' => ['0.0.0.0/0', '::/0']]],
    ['protocol' => 'udp', 'ports' => 'all', 'sources' => ['addresses' => ['0.0.0.0/0', '::/0']]],
    ['protocol' => 'icmp', 'sources' => ['addresses' => ['0.0.0.0/0', '::/0']]],
];
$outboundRules = [
    ['protocol' => 'tcp', 'ports' => 'all', 'destinations' => ['addresses' => ['0.0.0.0/0', '::/0']]],
    ['protocol' => 'udp', 'ports' => 'all', 'destinations' => ['addresses' => ['0.0.0.0/0', '::/0']]],
    ['protocol' => 'icmp', 'destinations' => ['addresses' => ['0.0.0.0/0', '::/0']]],
];

$dropletId = 123;
$firewall = $firewall->create(
    strval($dropletId) . '-firewall', $inboundRules, $outboundRules, [$dropletId]
);

// Add inbound rule to firewall id abc-123 from an array of address sources.
$firewallId = 'abc-123';
$type = 'inbound_rules';
$protocol = 'tcp';
$ports = '22';
$addresses = ['0.0.0.0/0', '::/0'];

if ($type == 'inbound_rules') {
    $locations = 'sources'
} elseif($type == 'outbound_rules'){
    $locations = 'destinations';
}

$rules[$type] = [
    ['protocol' => $protocol, 'ports' => $ports, $locations => ['addresses' => $addresses])
);
$firewall->addRules($firewallId, $rules);

// remove above rule
$firewall->removeRules($firewallId, $rules)

// remove firewall id 123-abc
$firewall->remove('123-abc');
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

### Monitoring

```php
// return the monitoring api
$monitoring = $client->monitoring();

// return a collection of alerts
$alerts = $monitoring->getAlerts();

// return alert uuid 123e4567-e89b-12d3-a456-426655440000
$alert = $monitoring->getAlert('123e4567-e89b-12d3-a456-426655440000');

// return inbound bandwidth metrics on the public interface of droplet id 123 from the past hour
$bandwidth = $monitoring->getDropletBandwidth(
    '123', 
    time() - 3600, 
    time(),
)->data;

// return outbound droplet bandwidth metrics on the private interface of droplet id 123 from the past hour
$bandwidth = $monitoring->getDropletBandwidth(
    '123', 
    time() - 3600, 
    time(),
    'outbound',
    'private'
)->data;

// Get current available storage for droplet id 123
$freeStorage = $monitoring->getDropletFilesystemFree(
    '123',
    time(),
    time()
)->data;
```

### Project Resources
```php
// return the project resources api
$projectResources = $client->projectResource();

// return the resources of the specified project
$projectResource = $projectResources->getProjectResources('1a111a1a-1aa1-1a1a-11a1-1111a11a11a1');

// assign a list of resources to the specified project
$projectResource = $projectResources->assignResources('1a111a1a-1aa1-1a1a-11a1-1111a11a11a1', ['do:droplet:123456789']);

// return the resources of the default project
$projectResource = $projectResources->getDefaultProjectResources();

// assign a list of resources to the default project
$projectResource = $projectResources->assignResourcesToDefaultProject(['do:droplet:123456789']);
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

### VPC

```php
// return the VPC api
vpc = $client->vpc();

// returns the all VPCs
vpcs = $vpc->getAll();
```


## Contributing

We will gladly receive issue reports and review and accept pull requests, in accordance with our [code of conduct](.github/CODE_OF_CONDUCT.md) and [contribution guidelines](.github/CONTRIBUTING.md)!

```
$ make install
$ make test
```


## Security

If you discover a security vulnerability within this package, please send an email to hello@gjcampbell.co.uk. All security vulnerabilities will be promptly addressed. You may view our full security policy [here](https://github.com/DigitalOceanPHP/Client/security/policy).


## License

DigitalOcean PHP API Client is licensed under [The MIT License (MIT)](LICENSE).
