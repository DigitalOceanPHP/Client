CHANGELOG
=========

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
- Small cosmetic changes to please scutinizer-ci

0.4.0 (2014-12-23)
------------------

- Added the ability to set userdata when creating a droplet.
- Added Account api
- Fixed CS to be PSR-2 complient
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

- Inital stable release (all API classes are tested)
