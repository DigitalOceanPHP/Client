CHANGELOG
=========

2.2.0 (18/04/2017)
------------------

- Add support for snapshots
- Add support for volumes and tags
- Support droplet monitoring
- Add in tag_name filter
- Support the certificate api

2.1.2 (12/09/2016)
------------------

- Added support for Volumes (Block Storage)
- Added the size_gigabytes attribute to images
- Added the posibility to page through droplets - PR #141
- Improved the update method for domain records - PR #142

2.1.1 (02/04/2016)
------------------

- Fixed an issue with the buzz adapter
- Fixed creating multiple droplets

2.1.0 (2015-12-22)
------------------

- Unified exception handling in adapters
- Support using buzz without curl
- Added missing floatingIp method

2.0.0 (2015-12-21)
------------------

- Added guzzle 6 support (watch out for the adapter rename)
- Major cleanup of adapters
- Fixed content type handling
- Support updaing fields on domain records
- Fixed droplet entity networking issues
- Allow using custom endpoints
- Made the entity classes final
- Removed dynamic entity properties
- Updated the account entity with the latest properties
- Support creating multiple droplets at once
- Support converting images to snapshots
- Support the ability to enable droplet backups
- Added full floating ip support
- Improved exceptions
- Minor code cleanup

1.0.1 (2015-06-25)
------------------

- Fixed issues with null values
- Minor code cleanup

1.0.0 (2015-04-16)
------------------

- The API is now stable! https://www.digitalocean.com/company/blog/apiv2-officially-leaves-beta/
- Fixed typo

0.6.0 (2015-03-17)
------------------

- Added available property to size entity - fix #93
- Added type property to image entity - fix #94
- Added [BC break] fix action object embed a region object - fix #89
- Added droplet neighbors report - fix #80
- Added list of droplets that are scheduled to be upgraded - fix #81

0.5.2 (2015-02-26)
------------------

- Added images filtering and its specs
- Added user image filtering and its specs
- Updated specs and doc

0.5.1 (2015-02-23)
------------------

- Fixed droplet could not be created when the backup function was disabled (next_backup_window was null)
- Added specs

0.5.0 (2015-02-17)
------------------

- Added dynamic properties for all entities.
- Added specs.
- Updated doc.

0.4.2 (2015-02-12)
------------------

- Added nextBackupWindow property to Droplet entity.
- Improved specs.

0.4.1 (2014-12-23)
------------------

- Use travis docker
- Small cosmetic changes to please scrutinizer-ci

0.4.0 (2014-12-23)
------------------

- Added the ability to set userdata when creating a droplet.
- Added Account api
- Fixed CS to be PSR-2 compliant
- Added Code of Conduct

0.3.0 (2014-11-14)
------------------

- Added updateData to DomainRecord Api
- Added minDiskSize for the image entity.
- Changed the droplet size to sizeSlug.
- Added Support for Guzzle 5.

0.2.0 (2014-09-21)
------------------

- Allow using either guzzle or buzz.
- Support creating droplet with ssh fingerprints.
- Pagination fixes.

0.1.1 (2014-07-27)
------------------

- Fixed getAll methods. now they return all instead of the first 25.
- Added getActions for a droplet.
- New field createdAt added to the droplet entity.
- Improved the mapping of a droplet networks to the droplet entity.
- Fixed Droplet rename method.
- Added Snapshot to the droplet api.
- Dates are formatted in ISO 8601.
- Added droplet features to the droplet entity.
- Better exception handling.

0.1.0 (2014-07-05)
------------------

- Initial stable release (all API classes are tested)
