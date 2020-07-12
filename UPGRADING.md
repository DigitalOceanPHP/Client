# UPGRADING GUIDE

## 2.3 to 3.0

Version 3.0 requires PHP 7.1-7.4 and either Guzzle 6, Guzzle 7, or Buzz 0.16. We recommend using Guzzle 7.1 if you are running PHP 7.1, and Guzzle 7 if you are using PHP 7.2+. Version 3.0 provides some great internal changes since 2.3, which will allow us to fully move to PSR-7, PSR-17, and PSR-18 in version 4.0 (due for release in 2021). We recommend everyone using version 2.3 upgrades to version 3.0 as soon as possible. New features and breaking changes are documented in the change log. 

### New Client Class

The `DigitalOceanV2\DigitalOceanV2` class has been renamed to `DigitalOceanV2\Client`, and you no longer need to provide an HTTP client when you create an instance. We will automatically discover one based on what you have installed! Moreover, authentication is simplified. Just call the `authenticate` method on the client, and you're ready to go.

#### 2.3 Code:

```php
$adapter = new DigitalOceanV2\Adapter\BuzzAdapter('your_access_token');

$digitalocean = new DigitalOceanV2\DigitalOceanV2($adapter);
```

#### 3.0 Code:

```php
$client = new DigitalOceanV2\Client();

$client->authenticate('your_access_token');
```

### Result Pager

In version 2.3, we used to provide a way to set the page and per page parameters to the `getAll` API methods, defaulting the per page value to 200. In version 3.0, we no longer interfeer with this query parameter, and instead provide a proper way to page through results.

#### 2.3 Code:

```php
// get the first 200 droplets as an array
$droplets = $droplet->getAll();

// get stuck...
```

#### 3.0 Code:

```php
// get the first 20 droplets as an array
$droplets = $droplet->getAll();

// create a new result pager
$pager = new DigitalOceanV2\ResultPager($client);

// get all your droplets as an array
$droplets = $pager->fetchAll($client->droplet(), 'getAll');

// get all droplets as a Generator which lazily yields
// new results as they become available
$droplets = $pager->fetchAllLazy($client->droplet(), 'getAll');
```

Note that `fetchAll` is implemented behind the scenes by simply calling `iterator_to_array` on `fetchAllLazy`. The
advantage to `fetchAllLazy` is you don't need to keep all the droplets in memory at the same time, however it is
possible for a request to fail after you have part processed some of the droplets, so choose whichever method is best
for your needs.
