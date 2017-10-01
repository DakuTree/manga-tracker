# Change Log
All notable changes to this project will be documented in this file.

## [2017-10-01]
### Added
- `[Backend]` Base_FoolSlide_Site_Model now has getChapterURL, getJSONTitleURL & getJSONUpdateURL functions.
  - Some FoolSlide sites (namely forks) don't have the API available on the same baseURL that the rest of the site is on. This allows us to overwrite them.
- Added support for RavensScans.
  - Closes #198.

### Fixed
- MangaFox should no longer load secure.footprint.net URLs wherever possible (This should avoid it getting caught by adblock).
- MangaFox & MangaHere should now properly fallback to an empty chapter list if it fails to load.
  - We still need a better solution here though..
- `[Backend]` addToContainer now prevents jQuery from preloading images.
- LHTranslation should work again.
  - Closes #210.

## [2017-09-30]
### Changed
- MangaFox/MangaHere now uses the mobile viewer to load the chapter list.
  - This should also fix part of #214.

## [2017-09-27]
### Added
- MangaHere now uses mobile reader for faster loading when possible.

### Fixed
- MangaFox & MangaHere now use https URLs.

## [2017-09-23]
### Added
- `[Backend]` Documentation for Base_Site_Model.

## [2017-09-22]
### Changes
- KissManga disabled alert now only shows when manually trying to update.
- Search now only checks the title, rather than the entire `<td>` element.
  - Closes #208

### Fixed
- It is now possible to change user options again.

## [2017-09-16]
### Changes
- LHTranslation now works on page URLs.

### Fixed
- LHTranslation should work again.

## [2017-09-15]
### Added
- Search metatag cheatsheet on the help page.

## [2017-09-09]
### Fixed
- Modifying / moving series will no longer trigger on window.onbeforeunload.

## [2017-09-07]
### Fixed
- Searching `mal:` then waiting while having a series containing `mal:` will no longer stop mal metatags from working.
  - Fixes #201.

## [2017-09-03]
### Added
- DigitalOcean referral link to the support us section on the about page.
  - Only really to be used if you're planning on using it anyway. Would prefer people spend their money on supporting the industry..

### Changed
- Last table header on dashboard now has default pointer.
- Table now has a loading icon that displays on initial load.
- Compiled CSS/JS is no longer committed & development now loads the uncompiled versions.
  - This should make it for easier for new devs to get started.
  - The compiled CSS/JS is still used on production, we're just no longer committing it to the repo.
  - LessJS is being used to load the less files.

### Fixed
- Highlighted rows no longer get their color overridden by the striped css.

## [2017-09-02]
### Added
- Added support for Pure Mashiro Scans.

### Changed
- `[Backend]` IPv6 addresses are no longer truncated in the DB.
- `[Backend]` Email addresses can now be 254 characters.

## [2017-09-01]
### Added
- Script to automatically generate & update all files required for adding support for a new FoolSlide site.

## [2017-08-30]
### Added
- Added support for Champion Scans.
- LHTranslation tests.

### Changed
- Trying to login with a non-existent identity should now return the "Incorrect Login" error, rather than no error.

### Fixed
- Logout now removes `remember_time` cookie.
- Fixed Mangazuki not updating when date string was different than expected.

## [2017-08-29]
### Added
- Checked metatag for filtering titles that have been checked.
  - Example: `checked:yes` would find everything that has been checked.
  - Example: `checked:no` would find everything that isn't checked.
- Confirm when moving to a new page while having checked rows.
- Confirm when moving selected rows to a new category.

### Changed
- `[Backend]` Updated Ion Auth.
- `[Backend]` Moved _most_ third party libs over to the third_party folder & autoloaded them from there.
  - Is this autoload packages option new, or am I just blind?

### Fixed
- Filters should work properly after updating the MAL ID or tags.

## [2017-08-26]
### Added
- `[Admin]` Added an easy CLI method to trigger an update for every single series on a site.

### Fixed
- Bato.to is no longer truncating the chapter URL.
  - Seems like bato.to no longer uses HTTPS urls on the chapter list, despite viewing the page in https. Weird.
  - SEE: #189
- FoolSlide custom updates now sorts by updated then created.
  - This fixes edge cases where series are uploaded in bulk in the wrong order. (HelveticaScans does this with Mousou Telepathy)

## [2017-08-25]
### Changed
- mangastream.com now redirects to readms.net
  - Closes: #187

### Fixed
- Login remember time should work properly now.

## [2017-08-24]
### Changed
- It is now possible to filter by multiple tags by separating tags with commas.
  - Previously needed to do: `tag:1 AND tag:2 AND tag:3` to search for multiple tags.
  - Now this works too: `tag:1,2,3`
  - Closes #181.
- `[Backend]` Site tests now have their own file, rather than bunching them all in a single file.
  - Also added a few new functions to remove duplication (namely `_testSiteSuccessRandom` & `skipTravisSSL`).
  - See: #157
- `[Backend]` Updated PHPUnit to 6.3.0.

## [2017-08-23]
### Fixed
- MerakiScans fixed.
  - Closes #185

## [2017-08-22]
### Added
- Support for Yummy Gummy Scans
  - Thanks to @Vexnyx (#182).

### Changed
- `[Backend]` Userscript setupFoolSlide function now auto-detects segments to remove.

## [2017-08-21]
### Added
- Support for mangazuki.co.
  - Thanks to @Vesnyx for the userscript code.

### Fixed
- FoolSlide sites should now properly show the formatted chapter on the dashboard when updating via the userscript.

## [2017-08-20]
### Added
- Tag metatag for filtering tags.
  - Example: `tag:complete` would find everything tagged as complete.
  - Example: `tag:complete AND mal:none` would find everything tagged as complete AND marked as not having a MAL id.

### Fixed
- Favouriting more than one chapter from the same series should work now.

## [2017-08-17]
### Added
- Site support for White Cloud Pavilion, World Three, and HotChocolateScans.

### Changed
- Userscript no longer runs on iframes.

### Fixed
- Userscript now properly recognizes decimal chapters on FallenAngelsScans.

## [2017-08-16]
### Added
- Site metatag for filtering a specific site.
  - Example: `site:mangafox.me` would return everything where mangafox.me is the site.
  - Closes: #156.
- MAL metatag now has `any`, `none`, and `notset` options for more filtering.
  - `mal:any` matches everything that has a MAL id.
  - `mal:none` matches everything that has been marked that it doesn't have an ID.
  - `mal:notset` matches everything that has yet to have an ID set.
- Login now has an option to set length of time to remember the user (from last login).

### Changed
- Userscript will now attempt to add to MAL if ID exists and the series is missing from your list.
  - See: #143
- DeathToll & Mangaichi icons are no longer invisible.

### Fixed
- Userscript now properly updates Batoto based chapter numbers on "My Status"
  - See: #169

## [2017-08-15]
### Changed
- User sessions now expire after 3 days, up from 1 day.
  - See: #170

### Fixed
- Userscript should now update "My Status" regardless if the chapter is latest or not.
  - Fixes: #169

## [2017-08-14]
### Added
- IPv6 Support.

### Changed
- `[Backend]` Custom updater now only logs missing titles if using following pages.
- `[Backend]` updateUnread now handles hiding icons & updating table sort.
- Userscript now updates "My Status" & unread info for table sort
  - Fixes: #153
  - Had to enable unsafeWindow for this which I've been wanting to avoid. Oh well.

### Fixed
- `[Backend]` Page loading will now call `setupViewerContainerError` if the ajax response is empty.
  - Fixes: #166
- Marking series as read should now properly set update unread status in table sort.
  - Fixes: #160
- Tablesorter is now no longer initialized twice.

## [2017-08-12]
### Added
- All FoolSlide sites (except Jaimini's Box) now use the custom updater!

### Changed
- `[Backend]` All FoolSlide sites now extend from a generic FoolSlide class.
  - This was mainly done to make it easier to mass-tweak FoolSlide sites in the future, as well as reduce a ton of duplication.
- `[Backend]` FoolSlide sites now use the API to grab new title data rather than checking the actual title page and parsing the DOM.
  - This should be much less error prone compared to parsing the DOM, _and_ it should work with much older versions of FoolSlide!
  - See: #147
- The userscript now waits for the topbar to load before attempting to load viewer.
  - Fixes #151.
- `[Backend]` Now using an alias for title ID when updating to avoid accidently screw-ups (Which did happen).
  - See: #152
- `[Backend]` Custom updater now requires the `tracker_sites.use_custom` column to be set to `Y` to allow updates.
  - This was already required elsewhere.

### Fixed
- Password reset should work again.
  - Thanks to @Vexnyx (#132).

## [2017-08-11]
### Changed
- Bumped the favicon unread count max count from 50 to 99.

### Fixed
- DynastyScans oneshots should now properly grab the title without also grabbing html.

## [2017-08-10]
### Fixed
- Striped rows should appear on non-default categories again.
- Changing sort direction in the nav dropdown should work properly now.
- Trying to sort by title when your default sort is "Unread (Alphabetical)" should now sort by ASC on first click, rather than DESC.
- Clicking header sort should now also automatically update the nav sort when possible.
  - Also added a fallback if you sort using a non-default way.
- My Status update time is now properly updated when the series is marked as read.

## [2017-08-09]
### Added
- Dashboard nav now has a dropdown options menu.
  - This has an alternate method to sorting your list that uses the same sorting methods as the backend does.
  - Also moved the Export List link to here.
- List search now has a clear button.

### Changed
- `[Backend]` Updated Composer dependencies.
- Added fallback for Tablesorter JS.
- Table headers now show sort direction.
- Both "My Status" & "Latest Release" table columns should now sort by date rather than Alphanumerically now.
- Lots of Dashboard HTML & LESS cleanup.
- Unread column now has fixed width.
- Moved to using Tablesorter for filtering, rather than using our own method.
  - This should work more or less the same as the previous search.
  - You can now search for titles with a specific MAL ID by using "mal:#ID#" (Replace #ID# with the ID).

### Fixed
- Fix for Fallen Angels Scans not showing images.
- Jaimini's Box no longer shows duplicate images.

## [2017-08-07]
### Added
- Added "_Unread (Latest Release)_" sort method.

### Changed
- Show a list of inactive series in the inactive alert.
- Sorting by latest release now sorts by the date of last update, rather than alphanumerically by chapter number.
- Old "_Unread_" sort method has been renamed to "_Unread (Alphabetical)_".

## [2017-08-06]
### Added
- Support for LHtranslation.

### Fixed
- Userscript image error container should properly appear again, and it should now work on sites that use `viewerCustomImageList`.

## [2017-08-03]
### Fixed
- Userscript page load counter no longer pops out then in before displaying.
- Userscript page load counter should now appear before attempting to load pages, rather than only after the first page load.

## [2017-08-02]
### Changed
- `[Backend]` Prepended 'trackr - ' to all userscript console calls for easier filtering.

### Fixed
- Page load counter should now appear on bato.to webtoons.
  - The page load counter was only added if page_count was set when we tried to setup the topbar, which wasn't the case with bato.to webtoons as it's set later.
  - Fixes #139.

## [2017-07-31]
### Changed
- `[Backend]` Moved missed userscript viewer CSS to userscript LESS file.
- Page number now shows as "page / total_pagecount".
- `[Backend]` Added cache busting for userscript CSS.
  - Tampermonkey doesn't seem to force a resource update when the userscript updates sadly..
- All sites should now auto-scroll to linked page where possible.
- `[Backend]` Moved to using Promises for handling when page loading ends.
- Auto tracking now happens after all pages have loaded by default, rather than as soon as possible.

## [2017-07-29]
### Added
- `[Backend]` Userscript now has a generic function for setting up FoolSlide sites.

### Changed
- `[Backend]` Moved the userscript CSS to a standalone file which is loaded via `@resource`.

### Fixed
- Unicode escape characters in URL now properly work.

## [2017-07-27]
### Added
- Support for Fallen Angels Scans.
  - Thanks to @Vexnyx (#132).
- Support for Mangaichi Scanlation Division
  - Thanks to @Vexnyx (#132).

### Fixed
- MangaFox/MangaHere should show the proper chapter in the topbar again.
- Page auto-scroll now only scrolls if page is higher than 1.

## [2017-07-26]
### Added
- Userscript now has zoom support!
  - Use '+' to zoom in, '-' to zoom out and '=' to reset zoom.
  - Thanks to @Vesnyx (#127).
- Support for MerakiScans.
- Added basic support for auto-scrolling to linked pages.
  - Currently only works for MangaFox, MangaHere & Bato.to.
  - See #128 for more info.

### Fixed
- Userscript search can now cancel without redirecting to search page.
- Striped rows work again when sorting.
- MangaCow element hiding fixed.

## [2017-07-24]
### Fixed
- Whiteout Scans & GameOfScanlation have icons again.

## [2017-07-23]
### Fixed
- Icons shouldn't be removed when going changing MAL id from none to anything else.

## [2017-07-22]
### Fixed
- Auto-tracking works on bato.to now.
- Striped rows should work on alternative tabs now.

## [2017-07-21]
### Added
- PHP Script to generate updated spritesheet and LESS.
  - This saves a bit of time when adding support for a new site.

### Changed
- `[Backend]` Updated Composer Dependencies.
  - Minimum PHP version is now 7.1.0.
  - Codeigniter was updated to 3.1.5.
- Tweaked light theme colors.

### Fixed
- All site PNGs should now be valid PNG.
  - These files were throwing libpng warnings. Simply fixed by running them under pngcrush.
- Rows should now be properly striped when searching. (Fixes #118)
- MAL none icon should be properly removed when changing to a new ID. (fixes #122)
- MAL icons no longer have underline.
- Can no longer submit multiple 0s as a MAL id.

## [2017-07-18]
### Fixed
- Bato.to series with a contributor/group containing the word "reader" should parse properly now.

## [2017-07-12]
### Fixed
- Sensescans should now auto-redirect when using the non-subdomain url (sensescans.com/reader/ rather than reader.sensescans.com).
- EGScans should now generate viewer correctly.
  - Thanks to @Vesnyx (#115).
- EGScans now auto-redirects if the URL contains any parameters.

## [2017-07-11]
### Added
- Title History now has a header containing the title.

### Fixed
- KissManga viewer should load properly again.
  - Thanks to @Vesnyx (#113).
  - Tracking is still disabled on KissManga due to the IP ban.

## [2017-07-10]
Moving away from using versions for updates going forward. Always felt a bit meaningless considering this is a website and not an app.  
Any notable milestones will still be labeled, if and when we have them.

### Changed
- More userscript documentation
  - Thanks to @Vesnyx (#110).

---------------------------------

## [1.7.12] 2017-07-09
### Added
- Support for ReadMangaToday.
  - Thanks to @Vesnyx (#108).

## [1.7.11] 2017-07-08
### Fixed
- Removed redundant codeblock.
  - Thanks to @Vesnyx (#106).

## [1.7.10] 2017-07-06
### Added
- Support for S2Scans.
  - Thanks to @Vesnyx (#104).

### Fixed
- MangaCow works should properly grab the chapter URL if it contains dots now.

## [1.7.9] 2017-07-05
### Added
- Failed attempts at trying to update a title is now logged in DB. Successful updates reset this to zero. Dashboard rows should also be highlighted in these cases.
  - SEE: #99
- Support for One Time Scans!

### Changed
- Death Toll Scans support has been re-enabled.

### Fixed
- MangaCow works with dashes now.
- URI tests should work properly again.

## [1.7.8] 2017-07-03 - 2017-07-04
### Added
- Support for WhiteoutScans.
  - Thanks to @Vesnyx (#98).
- `[Backend]` getTitleData now has a `?array` return type.
- `[Backend]` exit_ci() function for tests.

### Fixed
- Bato.to works again after switching to HTTPS.
- MangaCow now works on decimal chapters.

## [1.7.7] 2017-07-02
### Changed
- Favouriting now uses the same rate limit of everything else.
- Doubled the userscript rate limit from 500 > 1000.

### Fixed
- Userscript updates were incorrectly limited to a 250 rate limit. (Now uses the same 1000 as the rest of the userscript.)
- Userscript should properly show alert when update request fails for any reason.

## [1.7.6] 2017-06-30 - 2017-07-01
### Added
- Ability to set tags of multiple selected series.

### Changed
- Tweaked info on stats page.
- Manga Tracker header now notes if running development.
- Tag input is now hidden when hiding info dropdown.

### Fixed
- Alert should no longer pop up when trying to change the MAL ID a second time (and more) when the title did not have an ID prior.
- AMR Importing should work again.

## [1.7.5] 2017-06-14
### Fixed
- Userscript should now properly delay page loading for sites that have it set to delay.

## [1.7.4] 2017-06-10 - 2017-06-12
### Added
- `[Backend]` Added a bunch of tests for doCustomCheck.
- `[Backend]` Added isValidTitleURL/isValidChapter tests.

### Changed
- `[Backend]` Removed a bunch of duplication within doCustomCheck.
- doCustomCheck now does additional checks to see if the latest chapter is actually the latest chapter.
  - I would like to start using this with our normal updates as well, but I'm not sure if that is the best idea.
  - The normal updater ALWAYS uses what the latest update, regardless of the actual info. This avoids possible errors and such, but this would be handy...
- Rows are now highlighted on hover.

### Fixed
- Pointer should now properly be focused to end of input when clicking edit.

## [1.7.3] 2017-06-09
### Fixed
- Favicon properly changes when going from double to single digit unread.

## [1.7.2] 2017-06-08
### Fixed
- Userscript now works on mngcow series containing dashes.
- DOMDocument is now forced to parsing HTML as UTF8.
  - Surprisingly this isn't done by default.

## [1.7.1] 2017-06-06
### Changed
- Tag editor should now autofocus on input.
- `[Backend]` Scrutinizer-CI no longer checks migrations.
- `[Backend]` Userscript now throws error if tracking params are null/empty.

## [1.7.0] 2017-06-03
### Changed
- `[Backend]` Lots and lots of Userscript linting. Some jsdocs too.

### Fixed
- Current chapter text is properly updated on list when updating via the userscript.

## [1.6.25] 2017-06-01
### Added
- When updating a series through the userscript, it will also try and mark the chapter as read on your list if (if open) and if it's the latest chapter.
  - This basically just hides the update icons, and updates the favicon, as if you were marking it as read on the list itself. Small UX improvement.

### Fixed
- Tags are now properly changed under more info after editing.

## [1.6.24] 2017-05-24 - 2017-05-31
### Added
- Time error icons.

### Changed
- `[Backend]` Updated kenjis/ci-phpunit-test to 0.15.0.
- `[Backend]` Misc HTML cleanup.
- `[Backend]` History pages now use `<tbody>` for header row.
- `[Backend]` Tablesorter is now active everywhere by default.
- History pages now use tablesorter.
- Removed import HTML/JS.
  - It may come back at a later date, but only if it's needed. See 1.6.23 for more info.
- Linting.

## [1.6.23] 2017-05-22
### Added
- Export now also exports MAL id and tags.

### Changed
- Temp-disabled importing.
  - Importing to trackr.moe using an export FROM trackr.moe seems a bit pointless, and it hasn't been updated since release so stuff like tags and MAL id doesn't work.
  - Importing is handy to have if tech-savvy people are coming over from another platform, but outside of that not so much.
- Stats page now returns the site for the most followed series.
- Custom Updated sites are now only updated _manually_ every 72 hours (from 36 hours).
  - With our additional checks now in place, there is pretty much 0% chance of failure when following series, meaning series should always be updated via the custom updater.

### Fixed
- Public List now shows MAL icon & ignored chapters.
- Public List shouldn't try to set custom favicon.
- Public List shouldn't change favicon on timer update.
- Public List JSON no longer returns MAL icon.
- Tracker_List_Model->get() no longer returns HTML elements

## [1.6.22] 2017-05-19 - 2017-05-20
### Added
- Initial support for Webtoons custom updater.
  - At the moment this only auto-follows series, we don't actually update them.
  - The main issue with using a custom updater with Webtoons is that their subscriptions page doesn't provide latest chapter info. We can tell if something has updated, but that is about it.

### Changed
- MAL Sync now reports error when chapter number is higher than 1000.
  - As far as I know, no series has more than 1000 chapters, and this just avoids updating MAL with errors.

## [1.6.21] 2017-05-17 - 2017-05-18
### Changed
- MangaHere works again.
  - No longer seems to be running harmful JS & is no longer marked as deceptive by Chrome.
- `[Backend]` Custom updater now stores if a series has been properly followed or not.
- `[Backend]` Admin CLI now has an option to mass-follow series that haven't been followed properly.
  - I'll see about moving this to the admin panel at some point.
- `[Backend]` doCustomFollow now returns $titleData instead of $success.
  - Just makes updating a bit easier.
- `[Backend]` handleCustomFollow now also returns a successCallback to doCustomFollow. This allows us to attempt to confirm that the follow was successful.
- List should now scroll to top when changing category.

### Fixed
- `[Backend]` Caching should properly work on production again.
  - For some reason my cache folder had been deleted, meaning cache files weren't being created.

## [1.6.20] 2017-05-16
### Fixed
- MAL ID confirm shouldn't show "A MAL ID already exists for this series on our backend" if ID doesn't exist.
- MangaStream icons should show again.

## [1.6.19] 2017-05-15
### Added
- Favicon now updates to show unread count.
  - Note: This only shows unread series on the current page, not ones including in a new update. This may change later.

### Fixed
- Series header is now properly updated with new unread count when series is read/ignored.

## [1.6.18] 2017-05-13
### Added
- Userscript MAL sync success notification now has a link to the synced MAL page.
- New MAL Sync ID system.
  - No longer uses tags. Instead stores it separately which is a bit easier to work with. Old tag method will continue to work for the next month or so.
  - We can now tie MAL IDs to series on the backend. This means users won't have to set MAL IDs for series where this has been done. Pretty handy.
    - Sadly this is mostly a manual process at the moment as we need to make sure.
  - Existing MAL tags have been converted over to using the new system. New MAL tags added AFTER this will not be auto-converted, and will have to be done manually.

### Fixed
- API button is no longer clickable.

## [1.6.17] 2017-05-11
### Added
- Update status page which shows which series will be updated during the next update.
  - SEE: #17

### Changed
- `[Backend]` allow passing datetime format to getNextUpdateTime()

## [1.6.16] 2017-05-10
### Fixed
- Ignored chapters are now properly cleared when title update has a new chapter, and not every time.

## [1.6.15] 2017-05-09
### Added
- `[Backend]` DynastyScans now has a separate test for oneshots.
- Userscript now has a button for searching the current site (where possible).

### Changed
- `[Backend]` Site tests are now checked against one of five possible titles.
  - This is mainly done to make sure that the tests will work on different kinds of titles. These are only ran locally so the randomization here isn't much of a problem.

## [1.6.14] 2017-05-08
### Changed
- GameOfScanlation no longer shows "test" at top of page if AdBlock is enabled.

### Fixed
- Title history now shows the correct amount of pages in the pagination.
- `[Backend]` Pagination should no longer throw error because of missing titleID var.
- History pages now correctly redirect if page or TitleID is invalid.
- WebToons.com title URL should no longer contain trailing slash (Making it work again).

## [1.6.13] 2017-05-07
### Changed
- `[Backend]` Userscript now only injects FontAwesome into tracked sites.

## [1.6.12] 2017-05-06
### Changed
- MangaHere has been disabled as it has been marked as deceptive by Chrome, and also appears to be running unsafe Javascript.
  - A bit strange since I thought they were ran by the same folks as MangaFox, but maybe not?

### Fixed
- Refixed new series not being properly added. (Somehow this didn't get committed properly?)
- TamperMonkey dashboard should no longer throw a unsafe site error.
  - This was caused by mangahere.co being marked as unsafe, and since TamperMonkey autoloads favicons from @include sites.

## [1.6.11] 2017-05-05
### Added
- Admin Panel now has a list of all series tagged as complete, but not marked as such.
  - Useful for finding finished series for #4. Not a perfect solution, but it works.

### Changed
- Ignored updates should now be cleared on manual user update.

### Fixed
- Ignored updates should now be properly cleared on title update.

## [1.6.10] 2017-05-03
### Changed
- `[Backend]` Site Tests now log title_url on failure.
- DemonicScans has been disabled as the domain has been suspended.

### Fixed
- `[Backend]` doCustomFollow properly checks for status_code header. Apparently in_array doesn't check keys.

## [1.6.9] 2017-04-28 - 2017-05-02
### Added
- Basic Admin Panel.
  - At the moment this is just for triggering manual updates.

### Changed
- Userscript now gives feedback when attempting to update.
- `[Backend]` Failed CSRF now forces reload.
- `[Backend]` doCustomUpdate functionality has been moved to handleCustomUpdate. doCustomUpdate calls this and handles logging.
  - SEE: #85


### Fixed
- Userscript can now track properly after cancelling an update.

## [1.6.8] 2017-04-25
### Added
- History can now be exported (to either CSV or JSON).

### Changed
- History page now uses [simplePagination.js](http://flaviusmatis.github.io/simplePagination.js/) for pagination. Should be a bit better for those with a big history.

### Fixed
- Newly added series are no longer automatically marked as inactive.
  - This has been broken since the MAL Syncing update. Complete oversight on my part..

## [1.6.7] 2017-04-23
### Added
- List Search.
  - This is might still be a bit buggy but it works.

### Changed
- MangaFox page loading delay has changed from 750ms > 1000ms. This should hopefully help stop pages not loading due to bot protection.
- Delayed image loading by 100ms on sites with useCustomImageList enabled.
  - No reason to try and load every single page in one go. This should help avoid possible IP bans too.
- MangaFox now attempts to grab image URLs via the mobile one-page loader. Will fallback to old method if it fails.
  - This is much better/quicker as we only need to do one AJAX request to grab every image, rather than having to do a AJAX request for every single page.
  - This should also stop the amount of failed image loading as it bypasses having to load the actual web page (which is what has the bot protection).
- Help page now notes that the series must be added to MAL for syncing to work.

### Fixed
- New theme should properly show after changing theme.

## [1.6.6] 2017-04-22
### Changed
- Sync & Custom Update now works if volume is marked as LMT.

## [1.6.5] 2017-04-20 - 2017-04-21
### Added
- "Delete Selected" now has a confirm popup. It also alerts if no rows are selected.
- Ability to hide latest chapter, but not mark it as current.
  - This is useful for cases where the latest chapter isn't actually the latest (due to ordering, removal etc etc.).

### Changed
- Timer tooltip now mentions exceptions


## [1.6.4] 2017-04-17 - 2017-04-19
### Added
- Better errors if something goes wrong.
- mal:none metatag, if the series doesn't have a MAL entry (webtoons etc).
- More feedback when sync'ing.

### Changed
- `[Backend]` Site classes now have their own files, as per good PHP practice.
- An alert is now shown to the user if they try to update anything on the site but their session has expired.

### Fixed
- MAL Sync properly works with volumes marked with TBD/TBA/NA without preceding v.

## [1.6.3] 2017-04-14 - 2017-04-15
### Added
- `[Backend]` Tracker_Title_Model tests.

### Changed
- `[Backend]` Updated to PHPUnit v6.1.0.
- `[Backend]` Removed Codeception from Composer as it isn't currently used.
- `[Backend]` Travis no longer runs site tests.
  - On occasion Travis tests would fail due the odd site test failing (even though it was valid). These are still run daily on the local machine.
- `[Backend]` Moved tracker_sites migration data to a seperate JSON file.
  - This allows us to easily create site update migrations without scattering the data across multiple migrations.
  - We limit the columns allowed depending on when the migration was created as to make sure the data works when columns are added/changed.
- `[Backend]` Travis now outputs logs after every run, regardless of success. Logs are also filtered for useless DEBUG nonsense.

### Fixed
- `[Backend]` Complied JS/CSS path now uses APPPATH to avoid pathing errors.
- MAL Sync now works with volumes marked as TBD/TBA/NA.

## [1.6.2] 2017-04-13
### Fixed
- Series with a tag, but not a MAL metatag should work again.
  - I really should test this properly in future...

## [1.6.1] 2017-04-13
### Fixed
- MAL Sync should now grab formatted chapter number where possible.
- Series without a mal metatag now work properly again.

## [1.6.0] 2017-04-10 - 2017-04-12
### Added
- Partial MAL sync implementation!
  - This requires a few things to work properly! You need to be logged in on MAL, need to enable the "CSRF" mal sync option, and need to add a "mal:#ID#" tag to the series you want to allow syncing for.

### Changed
- You can now only have one MAL metatag per series.
- Duplicate and empty tags are now removed by default.
- Tooltips should no longer be partly hidden on certain sizes.

## [1.5.25] 2017-04-09
### Changed
- Travis log now only includes errors.
- DynastyScans test no longer runs on Travis (due to SSL).

### Fixed
- DynastyScans now uses HTTPS. (This should fix updating)

## [1.5.24] 2017-03-31
### Changed
- MangaStream now uses readms.net urls. The old mangastream URLs still work, but I'd rather not take any chances.

### Fixed
- MangaStream icon works again.
- MangaStream now selects correct chapter in chapter list when using https.

## [1.5.23] 2017-03-30
### Fixed
- MangaStream works again. (They changed domain for the reader)

## [1.5.22] 2017-03-20
### Added
- Public lists! These are disabled by default and have to be enabled via the options pages.
-- List is provided in both HTML and JSON format, and can be accessed at /list/USERNAME.FORMAT. This will 404 if the user does not have the option enabled.

### Changed
- Updated CodeIgniter to 3.1.4
- `[Backend]` User_Options->get() now has userID param.

## [1.5.21] 2017-03-11
### Changed
- Updated LICENSE.md

## [1.5.20] 2017-02-27
### Changed
- `[Backend]` Updated CI-PHPUnit-Test, PHPUnit, Codeception, normalize.css, font-awesome, bootstrap, cookieconsent & jquery-validation.
- Terms page is now linked from the footer.

## [1.5.19] 2017-02-25
### Added
- Proper Notice System (Issue #67) + Ability to hide notices permanently.

## [1.5.19] 2017-02-24
Starting to use "series" rather than "titles". Using "titles" to refer to followed series never felt right.

### Added
- Titles (actual titles, not series) are now updated once a month. Very rarely titles are changed on the site so it would be good to try and keep them up to date.
-- This also does a normal update, so it shouldn't waste too many requests.

## [1.5.18] 2017-02-23
### Added
- bato.to now uses the following page for updates like MangaFox, which pushes the update time for bato.to up to hourly!
-- Like MangaFox, if we can't guarantee that the new chapter is <actually> the new chapter, we won't update it, and it will be updated within 36~ hours instead.

### Changed
- `[Backend]` Reverted Favourites rework & replaced with a better method which works with history.
-- Series that are favourited are now auto-added to the users list and marked as inactive. This allows history to work properly and makes sure the titles don't count it when updating series.

## [1.5.17] 2017-02-22
### Changed
- `[Backend]` updateLatestChapters now properly uses group_start/group_end instead of trying to group where queries ourself.
- `[Backend]` updateLatestChapters should now properly no longer update series that aren't being tracked by anyone.

## [1.5.16] 2017-02-21
### Fixed
- HelveticaScans works again after the redesign.

## [1.5.15] 2017-02-08
### Added
- Added an icon for updated 3 days ago. This should help differentiate newly updated series from ones updated a week ago.

### Changed
- `[Backend]` get_time_class ... ago times are now predefined, rather than getting them on every single function call. Very minor speed increase.

## [1.5.14] 2017-02-04
### Fixed
- Favouriting should work again.

## [1.5.13] 2017-02-03
### Changed
- Updated CI-PHPUnit-Test to test PHP7.1 support.
-- Monkeypatching is still disabled due to speed.

## [1.5.12] 2017-01-30
### Added
- `[Backend]` Bug reports now have the users email set as the "Reply to" email if possible.

### Changed
- trackr.moe now sends all emails from no-reply@trackr.moe instead of admin@codeanimu.net
-- This is a bit late, but free google apps doesn't allow smtp to alias domains so I was a bit restricted with what I could do.
- Emails now note that they are sent from send-only address.
- `[Backend]` User list sort order is now stored in variable prior to usort. x3~ speed increase in page load.
-- This was a major oversight on my part. Assumed that since user options were cached that there would no speed difference, apparently not.

## [1.5.11] 2017-01-29
### Fixed
- Page loaded count no longer shows on sites with a custom viewer.

## [1.5.10] 2017-01-25
### Added
- Userscript now has a pages loaded counter.
-- This will also mention if any pages have failed to load, and provide a link to mass-reload all failed pages.

### Changed
- The example image/gif on the front page now uses the same theme as default.
- Removed the dev banner as it's no longer needed.

## [1.5.9] 2017-01-23
### Added
- Support for Easy Going Scans.
- Support for Death Toll Scans.

### Changed
- `[Backend]` Sites using FoolSlide can now properly load manga marked as adult.

## [1.5.8] 2017-01-22
### Changed
- `[Backend]` Manually committed boilerplate.css + removed from composer.
- `[Backend]` Moved from composer-asset-plugin to asset-packagist.
-- This is a massive speed increase when doing install/update & removes dependency on globally installed plugin.

## [1.5.7] 2017-01-16
### Changed
- `[Backend]` Use file caching instead of APC caching.
-- APC caching appears to not play nice when running multiple setups..

## [1.5.6] 2017-01-15
### Added
- Support for Demonic Scanlations.

### Changed
- `[Backend]` Site Model - Use get_class($this) to set $site, instead of setting it manually.
- `[Backend]` Updated site migration with site data.

## [1.5.5] 2017-01-14
### Added
- sitemap.xml
- Support for Doku Fansubs - https://kobato.hologfx.com/reader/
- File-based Cache Busting. This should make sure that everyone properly gets the latest files after an update.
- `[Backend]` Sites_Model now has loadSite function to reduce duplication.
- `[Backend]` Logs if we try call site class that doesn't exist.
- `[Backend]` getSiteDataFromURL now returns all siteData, instead of just id/site_class.

### Changed
- Updated Codeigniter to 3.1.3.
- `[Backend]` Now using __get to dynamically load site classes. This makes things a bit easier to develop with.
-- I'm not 100% sure if this is a good idea. Some people said to avoid, some people said it's fine. Problem with most of the research I found is it was from PHP 5.3-5.5 era, so it's a bit dated..

## [1.5.4] 2017-01-13
### Changed
- `[Backend]` updateCustom now includes site name with logs.
- `[Backend]` Updated userscript jQuery to 3.1.1

### Fixed
- API Key generation not working for new users again.
-- I need to stop breaking this...

## [1.5.3] 2017-01-12
### Added
- AMR Import Helper tool. This can be found @ /import_amr. This converts an AMR export file into a readable list which should make it easier to track things.
-- This is ultimately not the best solution, but it's WIP. See https://github.com/DakuTree/manga-tracker/issues/19#issuecomment-271768025 for more info.

## [1.5.2] 2017-01-10
### Added
- Option to disable loading all pages in single page.

### Changed
- `[Backend]` Userscript options are now stored under config.options rather than just config.

## [1.5.1] 2017-01-09
### Added
- Userscript tracking now tries to block accidental double-clicks when trying to track.
- Userscript now has fallback image if it fails to load the proper one. Clicking this will attempt to grab the image again.

### Fixed
- `[Backend]` Favouriting works again.

## [1.5.0] 2017-01-08
### Changed
- `[Backend]` Massive overhaul of the Tracker_Model. Everything has been split into sub-models that have their own files/classes.
-- Previously everything was accessible via $this->Tracker->method(), now it's $this->Tracker->module->method().
-- There will most likely be bugs, which is the reason for the beta1 version tag.
- `[Backend]` Disabled MonkeyPatching due to it causing all tests to be slow. This also stops site failure testing sadly.
-- Going to fix this so we don't need MonkeyPatching at later date.
- Increased MangaFox page delay up to .75s from .6s due to 503s still appearing..
- Temp-disabled favourite history. See #80.

## [1.4.21] - 2017-01-07
### Changed
- `[Backend]` setupViewerContainer function to remove more duplication.
- `[Backend]` MangaPanda now auto-fails after 25 attempts of trying to grab chapter list.

## [1.4.20] - 2017-01-06
### Added
- `[Backend]` setupViewer now has a useDelay param, which when passed an int will delay page loading by X ms.

### Changed
- MangaFox chapter URLs now point to /1.html, instead of just /. Not specifying a page auto-points to comments which doesn't work well on mobile.
- `[Backend]` Removed a bunch of needless recursion from the userscript.
- `[Backend]` Userscript now uses jQuery.ready for all sites.
- `[Backend]` Did a bunch of inspection recommendations for the userscript.

### Fixed
- MangaFox now loads pages properly again. Had to add a .6s delay between loading to makes things work nicely again.

## [1.4.19] - 2017-01-04
### Changed
- Twitter handle on about page/humans.txt now points to @DakuTree.

## [1.4.18] - 2017-01-03
### Added
- Bug Report page now also points user to posting issue on Github as alternative.

### Changed
- `[Backend]` Separated AND/OR WHERE SQL into multiple where/or_where function calls in updateLatestChapters.
- MangaFox title pages are now only checked once every 36-40~ hours. This is due to us now having hourly updates due to checking the following page instead.
-- This takes us from an __average__ of 5544-7392 requests to MangaFox a week to 2217-2464. An improvement of around 3327-4928 requests!
-- Still checking both as I'm not too sure how accurate the following list will be in the long run. The title pages act as kind of a confirmation that the chapter is valid.

## [1.4.18] - 2017-01-01
### Added
- `[Backend]` option for getTitleID to return data
-- This is bad code, but I'm planning on doing a massive rewrite of the entire Tracker_Model sometime soon.
- `[Backend]` doCustomCheck function to validate if it's OK to update.
- Added working custom updater for MangaFox! This pushes MangaFox updates to hourly! (From the prior 12hr~ limit)

### Changed
- Updated footer copyright note to 2017.
- `[Backend]` Disabled bato.to /myfollows updating until https://github.com/DakuTree/manga-tracker/issues/78#issuecomment-269833624 gets resolved.

## [1.4.17] - 2016-12-31
### Added
- Mangafox now auto-bookmarks series when they are added (No updating yet!)

## [1.4.16] - 2016-12-30
### Added
- `[Backend]` getTitleID now has a $create param (defaults to TRUE), which can be used to stop creating a title if it doesn't exist.
- `[Backend]` get_content can now be sent as POST (with fields).
- `[Backend]` getTitleData now has a $firstGet param (defaults to FALSE). This can be used if you want to call certain things only on the first run.
- Functionality to grab new title data from alt sources. This should eventually allow MUCH faster updates (12x faster~)
-- This has been implemented for bato.to, but isn't active due to reasons noted in https://github.com/DakuTree/manga-tracker/issues/78

## [1.4.15] - 2016-12-29
### Fixed
- Fixed updating. (Broke due to ambiguous column name & was also being halted due to DynastyScans returning BOOL for latest_chapter)

## [1.4.14] - 2016-12-28
### Added
- `[Backend]` Autocompletion for CI_Migration.
- Support for Jaimini's Box. - https://jaiminisbox.com/
- Userscript now uses the site favicon as an icon.

### Changed
- `[Backend]` tracker_titles now uses a status column instead of complete.
- One-shots/Ignored series are no longer updated.

### Fixed
- `[Backend]` Migrations not properly adding keys to existing tables (SEE: https://github.com/bcit-ci/CodeIgniter/issues/1729)

## [1.4.13] - 2016-12-26
### Fixed
- `[Backend]` Only set parseTitleDataDOM vars when we know $content is array.

## [1.4.13] - 2016-12-25
### Fixed
- MangaStream now has the proper site name (was MangaPanda by mistake).

## [1.4.12] - 2016-12-24
### Added
- Userscript now has uses a .meta.js file for updating, which is also auto-generated on .user.js change.
- Userscript now has uses a @downloadURL param.

## [1.4.11] - 2016-12-22
### Fixed
- Userscript now properly points the user to trackr.moe (and not tracker.moe) if the API key isn't setup.
- Userscript now works again for new users. WHOOPS.

### Changed
- `[Backend]` DynastyScans now uses parseTitleDataDOM for non-oneshots.
- `[Backend]` parseTitleDataDOM no longer requires passing a $site var, as it now uses $this->site which is always set.

## [1.4.10] - 2016-12-18
### Fixed
- `[Backend]` Series that fail now return properly on failure. Also more logging.

### Changed
- Tags now allow colons. This is for the eventual support of MAL sync'ing. See #42 for more info.

## [1.4.9] - 2016-12-17
### Fixed
- GameOfScanlation now works with manga which use /projects/ instead of /forums/ as their listing page.
- GameOfScanlation now works with manga using /fourms/, which must have broke with some recent changes..

## [1.4.8] - 2016-12-16
### Added
- Added remaining site fail tests (WebToons, GameOfScanlation & MangaCow)

### Changed
- `[Backend]` GameOfScanlation now uses parseTitleDataDOM.

## [1.4.7] - 2016-12-15
### Fixed
- Importing works now.

### Changed
- `[Backend]` Rewrote User Options set/set_db

## [1.4.6] - 2016-12-14
### Added
- `[Backend]` Logs are now sent to my email daily via email_logs.pl. This should mean quicker fixes for bugs hopefully.
- `[Backend]` _testSiteFailure test function to remove duplication.
- `[Backend]` Failure tests for FoolSlide sites.
- `[Backend]` More tests for MY_Form_validation.
- `[Backend]` _testSiteSuccess test function to remove even more duplication.

### Changed
- `[Backend]` Added robots.txt + removed noindex <meta> tag.
- `[Backend]` Updated Ion Auth lib + removed used of mcrypt_create_iv which is deprecated in 7.1.
- Next Update info hover is now aligned to left so it doesn't get cut off.
- Switched to our own time icons. I kinda prefer the old ones, but they were just taken from AMR.
- `[Backend]` MangaCow now uses parseTitleDataDOM.
- `[Backend]` parseTitleDataDOM now checks if $content is_array. curl can fail, but still return data which is weird.

## [1.4.5] - 2016-12-12
### Fixed
- `[Backend]` Bad URLs are no longer redirected to the non-existent error controller.

## [1.4.4] - 2016-12-10
### Changed
- `[Backend]` CodeIgniter no longer uses the composer autoloader. I actually have no idea why this was enabled to be begin with..
- `[Backend]` Moved inline composer php to it's own file. We can simply include this and call the functions we need.

## [1.4.3] - 2016-12-09
### Fixed
- Being unable to update manga. This would have been fixed sooner, but I have been without internet for 3 days! Sorry!
- FoOlSlide sites not working if title url contains a dash.

## [1.4.2] - 2016-12-06
### Changed
- `[Backend]` Curl now logs if it fails.
- `[Backend]` Travis now outputs logs on failure.
- `[Backend]` Sites using FoOlSlide now use parseFoolSlide.
- `[Backend]` site, titleFormat & chapterFormat vars, this removes the need for isValidTitleURL/isValidChapter.

### Fixed
- `[Backend]` SeaOtterScans failing due to forced https.

## [1.4.1] - 2016-12-05
### Added
- `[Backend]` Failure testing for Batoto, MangaPanda, MangaStream
- `[Backend]` cleanTitleDataDOM function. This is called on the html data prior to DOM parsing. In some cases, speeds up parsing.

### Changed
- `[Backend]` get_content & parseTitleDataDOM are now marked as final. These aren't meant to be overridden.

## [1.4.0] - 2016-12-04
### Version Bump
- 1.3.x was getting a bit much, and this is the start of a small Site_Model rewrite, so decided to bump it up to 1.4.x.

### Added
- `[Backend]` parseTitleDataDOM function to remove duplication, better error logging/detection
- `[Backend]` Failure testing for MangaFox & MangaHere.

### Changed
- `[Backend]` get_content now returns an array including both headers & content. This should allow better error checking.
- `[Backend]` parseTitleDataDOM function to help remove duplication from the si

## [1.3.28] - 2016-12-03
### Added
- `[Backend]` Tests for Mangafox failing to grab data.
- `[Backend]` More site error reporting.

### Changed
- `[Backend]` Re-enabled CURLOPT_SSL_VERIFYPEER when parsing sites.
- `[Backend]` Got rid of the unused config.init.
- `[Backend]` Composer update (for ci-phpunit-test 1.3.0).

## [1.3.27] - 2016-12-02
### Changed
- `[Backend]` PHP7.1 is out! Added some nullable return types, travis testing.

## [1.3.26] - 2016-11-29
### Fixed
- `[Backend]` Did various Scrutinizer-CI reccomendations (Missing semicolons, strict comparisons, alignment, etc.)

## [1.3.25] - 2016-11-27
### Fixed
- `[Backend]` Font Awesome works again, also updated to 4.7.0.
- `[Backend]` MangaPanda now properly shows the topbar.

## [1.3.25] - 2016-11-26
### Changed
- `[Backend]` PHP config is now backed up properly.

## [1.3.24] - 2016-11-25
### Fixed
- `[Backend]` Fixed auto-complete/inspection errors when accessing DOMDocument elements with [0] instead of ->item(0)
- Sites using FoOlSlide no longer auto-redirects to series page. Apparently a missing end slash caused this :|

## [1.3.23] - 2016-11-24
### Changed
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

### Changed
- `[Backend]` Added global https check to userscript to avoid duplicate code

## [1.3.20] - 2016-11-22
### Added
- Support for HelveticaScans - http://helveticascans.com/reader

## [1.3.19] - 2016-11-21
### Changed
- `[Backend]` User options are now cached. This should have some small speed improvements.
- `[Backend]` Added the DB part of https://github.com/terrylinooo/CodeIgniter-Multi-level-Cache.
  - Also did a few additional tweaks to change the folder structure.
- `[Backend]` Title history pages are now cached.

## [1.3.18] - 2016-11-20
### Changed
- Lots of userscript tweaks. Mostly PHPStorm inspection recommendations. jQuery selector prefixing, ES6 stuff and so on.

## [1.3.17] - 2016-11-18
### Added
- `[Backend]` Test for SeaOtterScans
- `[Backend]` Caching! Only for site stats at the moment, but it's a start!

### Changed
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

### Changed
- Linting for the userscript.

## [1.3.13] - 2016-11-03
### Added
- Theme support! This comes with the original light theme, and a new dark theme! Change it in the options!

### Changed
- `[Backend]` Even more LESS tweaking. Everything now uses theme vars. Got rid of more useless code aswell.

## [1.3.12] - 2016-10-29
### Changed
- `[Backend]` More LESS tweaking. Removed a bunch of unused code.

## [1.3.11] - 2016-10-27
### Changed
- `[Backend]` Lots of LESS tweaking. Initial work for allowing separate themes.

## [1.3.10] - 2016-10-25
### Added
- `[Backend]` Fallback for Cookieconsent & RespondJS.
- Added MangaCow to Supported Sites list on help page & removed the notice.

### Changed
- Changelog will now mark backend Changed with `[Backend]`. This will not be added to previous updates.
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

### Changed
- We're using Scrutinizer-CI now. Couldn't use before since repo was private.

## [1.1.0] - 2016-09-16
### Notice
- We went open-source! - https://github.com/DakuTree/manga-tracker

### Added
- Bato.to now logs errors if it fails while grabbing info.
- Default config file.

### Changed
- Backup script now uses perl instead of bash.
- LICENSE V3. Slight re-wording here and there.

### Fixes
- Bato.to series with the latest date marked as "An hour ago" no longer cause the updater to fail.

## [1.0.5] - 2016-09-15
### Changed
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

### Changed
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

### Changed
- Changed the padding on the alert.

## [1.0.2] - 2016-09-12
### Added
- Support for marking a site as disabled, as well as a way to notify the user of it.

### Changed
- KissManga disabled due to IP ban.

## [1.0.1] - 2016-09-11
### Changed
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
