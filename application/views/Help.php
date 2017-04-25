<h1>Getting Started</h1>
<div>
	<ol>
		<li>Install <a href="https://tampermonkey.net/">TamperMonkey</a> (Chrome) or <a href="https://addons.mozilla.org/en-gb/firefox/addon/greasemonkey/">GreaseMonkey</a> (Firefox).</li>
		<li>Install the trackr.moe <a href="<?=base_url('userscripts/manga-tracker.user.js')?>">userscript</a>. Make sure this is installed to Tampermonkey/Greasemonkey, and not directly to your browser.</li>
		<li><a href="<?=base_url('user/login')?>">Login</a> to the site.</li>
		<li>While logged in, visit <a href="<?=base_url('user/options')?>">user/options</a> and click "Generate new API key". A new key should be shown. Refresh to verify the same API key appears again.</li>
		<li>Visit any manga on one of the supported sites and click the book icon to start tracking the series.</li>
	</ol>
</div>

<h1>MAL Syncing</h1>
<div>
	<p>MAL Syncing is a work in progress, so there may be bugs!</p>
	<ol>
		<li>Make sure you are logged on to MAL in the same browser you are accessing trackr.moe from.</li>
		<li>Set the MAL Sync options on the options page to "CSRF".</li>
		<li>Add a "mal:#ID#" tag to series you want to sync. For example, One Piece would have the 'mal:13' tag.</li>
		<li>Important: You must have the series added on MAL before we can update. We can't add series for you!</li>
		<li>Optional: Use mal:none for series without a MAL entry (webtoons etc).</li>
		<li>MAL Syncing is now setup!
	</ol>
	<br/>
	<p>Note: We know this might be a bit tedious, but it's the best method we can do at the moment.</p>
</div>

<h1>Supported Sites</h1>
<div>
	<ul>
		<li>MangaFox</li>
		<li>Bato.to (with multi-lang support)</li>
		<li>DynastyScans (beta)</li>
		<li>MangaHere</li>
		<li>MangaStream</li>
		<li>MangaPanda</li>
		<li>WebToons.com</li>
		<li><del>KissManga.com</del> - KissManga has been temporarily disabled due to IP block. No ETA on fix.</li>
		<li>KireiCake.com</li>
		<li>GameOfScanlation.moe</li>
		<li>MangaCow</li>
		<li>SeaOtterScans</li>
		<li>HelveticaScans</li>
		<li>SenseScans</li>
		<li>JaiminisBox</li>
		<li>Doki Fansubs</li>
		<li>Demonic Scanlations</li>
		<li>Death Toll Scans</li>
		<li>Easy Going Scans</li>
	</ul>
</div>
