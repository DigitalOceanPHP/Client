CHANGELOG
=========

0.3.0 (2014-xx-xx)
------------------

- Added minSize for the image entity.
- Changed the droplet size to sizeSlug.
- Added Support for Guzzle 5.0.

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
