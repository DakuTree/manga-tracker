# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
- N/A

## [1.1.6] - 2016-09-26
### Fixed
- Fixed GameOfScanlation not working after some HTML was changed causing parsing issues.

## [1.1.5] - 2016-09-24
### Added
- User History (Adding/editing/removing series, editing tags & changing categories)
  - At the moment this is being logged on the DB side. I'll get around to adding something the user can view at a later date.

### Changed
- Rows are no longer removed from the DB when a user stops tracking a series, they are instead simply marked as inactive and hidden from the user.
  - This was done to allow user history to work without doing an entire revamp of the backend.

## [1.1.4] - 2016-09-22
### Added
- Sticky list header. No more needing to scroll to top to see update time!.

## [1.1.3] - 2016-09-21
### Added 
- Test for Game of Scanlation.

## [1.1.2] - 2016-09-20
### Added
- Added support for gameofscanlation.moe

### Fixes
- Userscript should work on KireiCake again. Forgot to update @include to https.

## [1.1.1] - 2016-09-19
### Added
- Started tracking when titles get updated. In the short-term this is useless, in the long term it should allow us to automate changing update-times!
  - No plans on making this data public at the moment.

### Changes
- We're using Scrutinizer-CI now. Couldn't use before since repo was private.

## [1.1.0] - 2016-09-16
### Notice
- We went open-source! - https://github.com/DakuTree/manga-tracker

### Added
- Bato.to now logs errors if it fails while grabbing info.
- Default config file.

### Changes
- Backup script now uses perl instead of bash.
- LICENSE V3. Slight re-wording here and there.

### Fixes
- Bato.to series with the latest date marked as "An hour ago" no longer cause the updater to fail.

## [1.0.5] - 2016-09-15
### Changes
- Perl scripts now output start time (so we can actually know if they are working).
- Titles with a disabled site now have a warning icon to try and better notify the user of the issue.
- Titles can now be updated if they haven't updated in the past 12 hours, instead of 14.
  - After a bit of testing, 14 hours seemed a bit slow. We'll see if 12 hours any more noticable. 
- Titles with no active users (not logged in 5 days) are now slowed to 120 hour updates.
  - This is pretty much a partial-fix. I'd like to be able to mark titles which are slowed if a user tracking them ever comes back at some point.
- Titles with no users at all (Tracked at some point then removed) are now completely stopped tracking.

## [1.0.4] - 2016-09-14
### Added
- Terms/Cookie/Privacy policy page. Also re-added the terms requirement on signup.

### Changes
- Moved to Perl for backend scripting. Much easier to securely use config files with this.
  - This is part of a slow move to eventually allow the project to go open-source.
- All email should now be coming from no-reply@trackr.moe instead of admin@codeanimu.net.

### Fixes
- Disabled KissManga testing (as it is not supported for now, and was causing tests to fail)
- Fixed KireiCake not working due to move to HTTPS-only. Links for the site should now also use https instead of http (even though it redirected)
- Fixed a signup test not working due to new signup redirection.

## [1.0.3] - 2016-09-13
### Added
- Added a help page. This includes a "Getting Started" tutorial & supported sites.
  - New users are now redirected to this page after signup.
  - The page can be found again be clicking "Need Help?" on the dashboard.

### Fixes
- MangaFox & Dynastyscans now properly decode HTML entities in titles. Existing titles have been fixed too.

### Changes
- Changed the padding on the alert.

## [1.0.2] - 2016-09-12
### Added
- Support for marking a site as disabled, as well as a way to notify the user of it.

### Changes
- KissManga disabled due to IP ban.

## [1.0.1] - 2016-09-11
### Changes
- Reenabled username AJAX check

### Fixed
- Bato.to not working with https

## [1.0.0] - 2016-09-10
### Added
- Initial release.
  - Support for:
    - MangaFox
    - Bato.to (with multi-lang support)
    - DynastyScans (beta)
    - MangaHere
    - MangaStream
    - MangaPanda
    - WebToons.com
    - KissManga.com
    - KireiCake.com
  - Category support (Reading, On-Hold, Plan to Read & 3 custom categories)
  - (Basic) Tagging support.
  - Site Options (Custom Categories, Default Category, Toggle Live Countdown Timer)
  - Userscript Options (Track on page-load).
  - Import/Export from site.
  - Bug Reporting (via userscript)
