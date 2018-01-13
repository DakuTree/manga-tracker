<h1 id="gettingstarted">Getting Started</h1>
<div>
	<ol>
		<li>Install <a href="https://tampermonkey.net/">TamperMonkey</a> (Chrome) or <a href="https://addons.mozilla.org/en-GB/firefox/addon/tampermonkey/">TamperMonkey</a> (Firefox). (GreaseMonkey is currently not supported.)</li>
		<li>Install the trackr.moe <a href="<?=base_url('userscripts/manga-tracker.user.js')?>">userscript</a>. Make sure this is installed to Tampermonkey, and not directly to your browser.</li>
		<li><a href="<?=base_url('user/login')?>">Login</a> to the site.</li>
		<li>While logged in, visit <a href="<?=base_url('user/options')?>">user/options</a> and click "Generate/Reset". A new key should be shown. Refresh to verify the same API key appears again.</li>
		<li>Visit any manga on one of the supported sites and click the book icon to start tracking the series.</li>
	</ol>
</div>

<h1 id="malsyncing">MAL Syncing</h1>
<div>
	<p>MAL Syncing is a work in progress, so there may be bugs!</p>
	<ol>
		<li>Make sure you are logged on to MAL in the same browser you are accessing trackr.moe from.</li>
		<li>Set the MAL Sync options on the options page to "CSRF".</li>
		<li>While viewing your list, click "Set MAL ID" to set the MAL ID.</li>
		<li>Important: You must have the series added on MAL before we can update. We can't add series for you!</li>
		<li>Optional: Set a MAL ID as "none" or "0" to mark it as not having a MAL entry. This shows a NOMAL icon on the list, and mentions the lack of MAL entry when updating via the userscript.</li>
		<li>MAL Syncing is now setup!
	</ol>
	<br/>
	<p>Note: We know this might be a bit tedious, but it's the best method we can do at the moment.</p>
</div>

<h1 id="advancedsearching">Advanced searching</h1>
<div>
	<p>The search we use on the dashboard is powered by TableSorter which adds some various useful filters for advanced searching. A list of these can be found <a href="https://mottie.github.io/tablesorter/docs/example-widget-filter.html#notes">here</a>.</p>

	<p>In addition to these filters, we also have our own custom filters which I have listed below:</p>
	<ul>
		<li>
			<b>mal:<code>[ID]|any|none|notset</code></b>
			<br>Examples:
			<ul>
				<li><code>mal:123</code> - Returns everything that has <b>123</b> as the MAL ID.</li>
				<li><code>mal:any</code> - Returns everything that has the MAL ID set.</li>
				<li><code>mal:none</code> - Returns everything that has the MAL ID set as <b>none</b>.</li>
				<li><code>mal:notset</code> - Returns everything that does not have the MAL ID set.</li>
			</ul>
		</li>
		<br>
		<li>
			<b>site:<code>[DOMAIN]</code></b>
			<br>Examples:
			<ul>
				<li><code>site:mangafox.me</code> - Returns everything that followed on <b>MangaFox</b>.</li>
				<li><code>site:bato.to</code> - Returns everything that followed on <b>Batoto</b>.</li>
			</ul>
		</li>
		<br>
		<li>
			<b>tag:<code>[TAG]</code></b>
			<br>Examples:
			<ul>
				<li><code>tag:complete</code> - Returns everything that is tagged as <b>complete</b>.</li>
				<li><code>tag:complete,one-shot</code> - Returns everything that is tagged as <b>complete</b> and <b>one-shot</b>.</li>
			</ul>
		</li>
		<br>
		<li>
			<b>checked:<code>yes|no</code></b>
			<br>Examples:
			<ul>
				<li><code>checked:yes</code> - Returns everything that has been checked.</li>
				<li><code>checked:no</code> - Returns everything that has not been checked.</li>
			</ul>
		</li>
	</ul>
</div>

<h1 id="supportedsites">Supported Sites</h1>
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
		<li><del>Demonic Scanlations</del> - Domain has been disabled.</li>
		<li>Death Toll Scans</li>
		<li>Easy Going Scans</li>
		<li>Whiteout Scans</li>
		<li>One Time Scans!</li>
		<li>S2Scans</li>
		<li>ReadMangaToday</li>
		<li>MerakiScans</li>
		<li>FallenAngelsScans</li>
		<li>Mangaichi Scanlation Division</li>
		<li>LHtranslation</li>
		<li>White Cloud Pavilion</li>
		<li>World Three</li>
		<li>Hot Chocolate Scans</li>
		<li>Mangazuki</li>
		<li>Yummy Gummy Scans</li>
		<li>Champion Scans</li>
		<li>Pure Mashiro Scans</li>
		<li>Ravens Scans</li>
		<li>Cat Scans</li>
		<li>HatigarmScans</li>
		<li>Phoenix Serenade</li>
		<li>LOLScans</li>
		<li>MangaRock</li>
		<li>EvilFlowers</li>
		<li>ShoujoHearts</li>
		<li>TwistedHelScans</li>
		<li>Chibi Manga</li>
		<li>Psycho Play</li>
		<li>MangaKakalot</li>
		<li>DKThiasScans</li>
		<li>ForgottenScans</li>
		<li>SaikoScans</li>
		<li>ShoujoSense</li>
		<li>MangaTopia</li>
		<li>VortexScans</li>
		<li>Dokusha</li>
		<li>ElPsyCongroo</li>
		<li>Bangaqua</li>
		<li>DamnFeels</li>
		<li>AtelierDuNoir</li>
		<li>Lolitannia</li>
		<li>Riceballicious</li>
	</ul> <!--ENDOFSITES-->
</div>
