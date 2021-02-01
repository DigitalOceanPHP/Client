CHANGE LOG
==========


## 4.1.1 (UPCOMING)

* Fixed incorrect floating IP IDs


## 4.1.0 (25/01/2021)

* Added app platform API support


## 4.0.2 (25/01/2021)

* Fixed creating NS records


## 4.0.1 (02/01/2021)

* Fixed issues with headers


## 4.0.0 (22/12/2020)

* Added void return types to void methods
* Dropped support for PHP 7.1
* Switched to PSR-17 and PSR-18
* Made builder class final
* Re-worked pagination
* Added additional return type enforcement


## 3.2.2 (12/12/2020)

* Fixed type of `$sizeInGigabytes`


## 3.2.1 (27/11/2020)

* Allow empty response content


## 3.2.0 (27/11/2020)

* Added PHP 8.0 support


## 3.1.1 (27/11/2020)

* Fixed typos in versions


## 3.1.0 (26/10/2020)

* Added database API support
* Added partial firewall API support


## 3.0.1 (26/10/2020)

* Fixed tag type for create/update domain records


## 3.0.0 (22/07/2020)

* Added scalar parameter types


## 3.0.0-RC2 (16/07/2020)

* Reworked pagination again
* Corrected supported Guzzle versions
* Implemented let's encrypt certificate creation


## 3.0.0-RC1 (12/07/2020)

* Add support for the Tag API
* Added getTotal() to droplet API
* Added support for newer properties for volumes
* Support only Guzzle `^6.3.1` or `^7.0` or Buzz `^0.16`
* Reworked and renamed `DigitalOceanV2\DigitalOceanV2` to `DigitalOceanV2\Client`
* Moved `DigitalOceanV2\Adapter` to `DigitalOceanV2\HttpClient`
* Reworked and renamed `AdapterInterface` to `HttpClientInterface`
* Reworked and renamed `BuzzAdapter` to `BuzzHttpClient`
* Reworked and renamed `GuzzleHttpAdapter` to `GuzzleHttpClient`
* Added support for automatic discovery of Guzzle and Buzz
* Removed old droplet upgrades endpoint
* Removed wait for active: should be implemented at a higher level of abstraction
* Renamed delete* API methods to remove*
* Reworked rate limiting and pagination
* Encode URIs according to RFC 3986
* Support only PHP 7.1-7.4


## 2.3.0 (27/01/2019)

* Load Balancer API intergration
* Added the support for taking snapshots
* Add domain records TTL support
* Add support for the CAA DomainRecord type
* Add support for the wait parameter when creating a droplet
* Added possibility to specify snapshot_id on volume creation
* Drop support for Buzz less than version `0.16.0`
* Added official PHP 7 support
* Dropped official HHVM support


## 2.2.0 (18/04/2017)

* Add support for snapshots
* Add support for volumes and tags
* Support droplet monitoring
* Add in tag_name filter
* Support the certificate api


## 2.1.2 (12/09/2016)

* Added support for Volumes (Block Storage)
* Added the size_gigabytes attribute to images
* Added the posibility to page through droplets
* Improved the update method for domain records


## 2.1.1 (02/04/2016)

* Fixed an issue with the buzz adapter
* Fixed creating multiple droplets


## 2.1.0 (2015-12-22)

* Unified exception handling in adapters
* Support using buzz without curl
* Added missing floatingIp method


## 2.0.0 (2015-12-21)

* Added guzzle 6 support (watch out for the adapter rename)
* Major cleanup of adapters
* Fixed content type handling
* Support updaing fields on domain records
* Fixed droplet entity networking issues
* Allow using custom endpoints
* Made the entity classes final
* Removed dynamic entity properties
* Updated the account entity with the latest properties
* Support creating multiple droplets at once
* Support converting images to snapshots
* Support the ability to enable droplet backups
* Added full floating ip support
* Improved exceptions
* Minor code cleanup


## 1.0.1 (2015-06-25)

* Fixed issues with null values
* Minor code cleanup


## 1.0.0 (2015-04-16)

* First stable release
