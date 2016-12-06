# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
- N/A

## [1.4.1] - 2016-12-05
### Added
- `[Backend]` Failure testing for Batoto, MangaPanda, MangaStream
- `[Backend]` cleanTitleDataDOM function. This is called on the html data prior to DOM parsing. In some cases, speeds up parsing.

### Changes
- `[Backend]` get_content & parseTitleDataDOM are now marked as final. These aren't meant to be overridden.

## [1.4.0] - 2016-12-04
### Version Bump
- 1.3.x was getting a bit much, and this is the start of a small Site_Model rewrite, so decided to bump it up to 1.4.x.

### Added
- `[Backend]` parseTitleDataDOM function to remove duplication, better error logging/detection
- `[Backend]` Failure testing for MangaFox & MangaHere.

### Changes
- `[Backend]` get_content now returns an array including both headers & content. This should allow better error checking.
- `[Backend]` parseTitleDataDOM function to help remove duplication from the si

## [1.3.28] - 2016-12-03
### Added
- `[Backend]` Tests for Mangafox failing to grab data.
- `[Backend]` More site error reporting.

### Changes
- `[Backend]` Re-enabled CURLOPT_SSL_VERIFYPEER when parsing sites.
- `[Backend]` Got rid of the unused config.init.
- `[Backend]` Composer update (for ci-phpunit-test 1.3.0).

## [1.3.27] - 2016-12-02
### Changes
- `[Backend]` PHP7.1 is out! Added some nullable return types, travis testing.

## [1.3.26] - 2016-11-29
### Fixed
- `[Backend]` Did various Scrutinizer-CI reccomendations (Missing semicolons, strict comparisons, alignment, etc.)

## [1.3.25] - 2016-11-27
### Fixed
- `[Backend]` Font Awesome works again, also updated to 4.7.0.
- `[Backend]` MangaPanda now properly shows the topbar.

## [1.3.25] - 2016-11-26
### Changes
- `[Backend]` PHP config is now backed up properly.

## [1.3.24] - 2016-11-25
### Fixed 
- `[Backend]` Fixed auto-complete/inspection errors when accessing DOMDocument elements with [0] instead of ->item(0)
- Sites using FoOlSlide no longer auto-redirects to series page. Apparently a missing end slash caused this :|

## [1.3.23] - 2016-11-24
### Changes
- `[Backend]` Userscript now uses === instead of == in some cases.
- `[Backend]` Added more files to .gitignore

### Fixed 
- bato.to chapter numbers containing dashes no longer get trimmed (previous: "13-2"/"13 - 2" > "13" | now: > "13-2")

## [1.3.22] - 2016-11-24
### Added
- An actual front page! It looks like ass but it's better than auto-redirecting to the login.

## [1.3.21] - 2016-11-23
### Added
- Support for SenseScans - http://reader.sensescans.com

### Changes
- `[Backend]` Added global https check to userscript to avoid duplicate code

## [1.3.20] - 2016-11-22
### Added
- Support for HelveticaScans - http://helveticascans.com/reader

## [1.3.19] - 2016-11-21
### Changes
- `[Backend]` User options are now cached. This should have some small speed improvements.
- `[Backend]` Added the DB part of https://github.com/terrylinooo/CodeIgniter-Multi-level-Cache.
  - Also did a few additional tweaks to change the folder structure.
- `[Backend]` Title history pages are now cached.

## [1.3.18] - 2016-11-20
### Changes
- Lots of userscript tweaks. Mostly PHPStorm inspection recommendations. jQuery selector prefixing, ES6 stuff and so on.

## [1.3.17] - 2016-11-18
### Added
- `[Backend]` Test for SeaOtterScans
- `[Backend]` Caching! Only for site stats at the moment, but it's a start!

### Changes
- `[Backend]` Site_Model class (and related functions) are now marked as abstract. This should avoid possible errors later down the line.

### Fixed
- `[Backend]` auto_track now uses bool value.
- `[Backend]` Dev & Prod API keys are now separate.
- `[Backend]` Google Analytics cookie should no longer be sent to static subdomains.
- `[Backend]` Updated Codeigniter to 3.1.2.
- `[Backend]` Fixed MangaStream test not working with non-numeric chapters

## [1.3.16] - 2016-11-17
### Fixed
- Fixed auto_track not working.
- Fixed auto_track not showing as enabled on options page.

## [1.3.15] - 2016-11-16
### Fixed
- Fixed FireFox support which was broke during linting.
- SeaOtterScans is now listed on the help page.

## [1.3.14] - 2016-11-14
### Added
- Support for Sea Otter Scans reader (http://reader.seaotterscans.com/)

### Changes
- Linting for the userscript.

## [1.3.13] - 2016-11-03
### Added
- Theme support! This comes with the original light theme, and a new dark theme! Change it in the options!

### Changes
- `[Backend]` Even more LESS tweaking. Everything now uses theme vars. Got rid of more useless code aswell.

## [1.3.12] - 2016-10-29
### Changes
- `[Backend]` More LESS tweaking. Removed a bunch of unused code.

## [1.3.11] - 2016-10-27
### Changes
- `[Backend]` Lots of LESS tweaking. Initial work for allowing separate themes.

## [1.3.10] - 2016-10-25
### Added
- `[Backend]` Fallback for Cookieconsent & RespondJS.
- Added MangaCow to Supported Sites list on help page & removed the notice.

### Changes
- Changelog will now mark backend changes with `[Backend]`. This will not be added to previous updates.
- Updated CookieConsent lib from 1.0.9 > 3.0.2.
- `[Backend]` Updated various composer packages.
- `[Backend]` Moved sprite icons to seperate LESS file.

### Fixed
- `[Backend]` .gitkeep file is now regenerated after composer update.

## [1.3.9] - 2016-10-22
### Added
- Daily Test for MangaCow

## [1.3.8] - 2016-10-21
### Added
- Support for MangaCow (mngcow.co)

### Fixed
- bato.to now works when using https
- Custom categories should work now! Never noticed these didn't work for others but myself...
- Sticky header should now work properly on small window heights.

## [1.3.7] - 2016-10-18
### Added
- Default list order option

## [1.3.6] - 2016-10-16
### Added
- Title history can now be found under the "More Info" toggle.

### Fixed
- KireiCake should now work on the non-page URLs.

## [1.3.5] - 2016-10-15
### Changed
- Bug Reports should now include APIKEY/USERID

## [1.3.4] - 2016-10-14
### Changed
- Bato.to title url no longer includes the title name, and redirects are followed when grabbing new chapters.
  - This should stop duplicate series caused by series being renamed.
  - Format: TITLE-rID:--:LANG > ID:--:LANG

## [1.3.3] - 2016-10-13
### Changed
- Inactive users who login with inactive series are now notified if any series have been marked as inactive, and that they will update during next update.

## [1.3.2] - 2016-10-12
### Added
- Site stats page!

### Fixed
- GameOfScanlation being unable to update if update time element was <abbr> instead of <span>
- Series which have been marked as inactive are now automatically updated if a new user starts tracking it.

## [1.3.1] - 2016-10-08
### Added
- Favicon will now "go dark" to alert the user that an update has occured.

### Fixed
- Make sure sticky header appears if page is refreshed while scrolled.

## [1.3.0] - 2016-10-04
### Added
- Ability to favourite chapters! (This does require the series to be tracked first though!)
  - History has also been added for this.
  - At the moment, the only way to remove a favourite is to go the the chapter page & click favourite again. Will try and add another method soon.

### Fixed
- CI garbage collection "should" work properly now.
- Upload/POST limits should now apply to production as well (for some reason I had this only set on dev).

## [1.2.1] - 2016-09-29
### Added
- Added a proper favicon! Cropped from: http://danbooru.donmai.us/posts/584295
  - Apparently the old generic production favicon was around 100KB, this is only 5KB, so big bandwidth savings here!

### Fixed
- Unable to scroll on certain small screen heights.
- Topbar not showing on Kireicake due to https now being forced.

## [1.2.0] - 2016-09-27
### Added
- User History page!
  - Design is pretty much WIP, but it will do for now.

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
