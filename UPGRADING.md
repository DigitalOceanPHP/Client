# UPGRADING GUIDE

## 3.3 to 4.0

Version 4.0 requires PHP 7.2-8.0 and any HTTP client and HTTP client factory implementation. We are now decoupled from any HTTP messaging client by using [PSR-7](https://www.php-fig.org/psr/psr-7/), [PSR-17](https://www.php-fig.org/psr/psr-17/), [PSR-18](https://www.php-fig.org/psr/psr-18/), and [HTTPlug](https://httplug.io/). You can visit [HTTPlug for library users](https://docs.php-http.org/en/latest/httplug/users.html) to get more information about installing HTTPlug related packages. There are many good choices for HTTP clients, such as Guzzle 7. Upgrading from version 3.0 should be easy, once you have picked an HTTP client, as all of our other changes are purely internal.

## 2.3 to 3.0

Version 3.0 requires PHP 7.1-7.4 and either Guzzle 6, Guzzle 7, or Buzz 0.16. We recommend using Guzzle 7 if you are running PHP 7.1, and Guzzle 7 if you are using PHP 7.2+. Version 3.0 provides some great internal changes since 2.3, which will allow us to fully move to PSR-7, PSR-17, and PSR-18 in version 4.0. We recommend everyone using version 2.3 upgrades to version 3.0 as soon as possible. New features and breaking changes are documented in the change log. 

### New Client Class

The `DigitalOceanV2\DigitalOceanV2` class has been renamed to `DigitalOceanV2\Client`, and you no longer need to provide an HTTP client when you create an instance. We will automatically discover one based on what you have installed! Moreover, authentication is simplified. Just call the `authenticate` method on the client, and you're ready to go.

#### 2.3 Code:

```php
$adapter = new DigitalOceanV2\Adapter\BuzzAdapter('your_access_token');

$client = new DigitalOceanV2\DigitalOceanV2($adapter);
```

#### 3.0 Code:

```php
$client = new DigitalOceanV2\Client();

$client->authenticate('your_access_token');
```

### Result Pager

In version 2.3, we used to provide a way to set the page and per page parameters to the `getAll` API methods, defaulting the per page value to 200. In version 3.0, we no longer interfeer with this query parameter, and instead provide a proper way to page through results. Our new result pager class with temporarily set the per page value to 100 while it does its work, rather than the API classes doing this, however you can customise this number when you create the result pager.

#### 2.3 Code:

```php
// get the first 200 droplets as an array
$droplets = $client->droplet()->getAll();

// get stuck...
```

#### 3.0 Code:

```php
// get the first 20 droplets as an array
$droplets = $client->droplet()->getAll();

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
