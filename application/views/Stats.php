<p>trackr.moe has a total of <b><?=$stats['total_users']?></b> users. Of that, only <b><?=$stats['validated_users']?></b> have generated an API-key, and <b><?=$stats['active_users']?></b> are currently active (Logged in during the past week).</p>

<p>The site is currently tracking a total of <b><?=$stats['total_titles']?></b> series across <b><?=$stats['total_sites']?></b> sites.<br>
Out of these:
<ul>
	<li><b><?=$stats['inactive_titles']?></b> have been marked as inactive (due to no active users)</li>
	<li><b><?=$stats['updated_titles']?></b> updated within the past 24 hours</li>
	<li><b><?=$stats['titles_tracked_more']?></b> are being tracked by more than one person.</li>
</ul>
<p>
The most popular site being tracked is <b><?=$stats['rank1_site']?></b> with <b><?=$stats['rank1_site_count']?></b> series tracked, followed by <b><?=$stats['rank2_site']?></b> with <b><?=$stats['rank2_site_count']?></b> then <b><?=$stats['rank3_site']?></b> with <b><?=$stats['rank3_site_count']?></b>.
<br>
The most followed series is <b><?=$stats['top_title_name']?></b> on <b><?=$stats['top_title_site']?></b> with a total of <b><?=$stats['top_title_count']?></b> followers.</p>

<p>Since 2016-09-19 there has been a total of <b><?=$stats['title_updated_count']?></b> new series/chapters, and a total of <b><?=$stats['user_updated_count']?></b> user events (This includes adding/updating/removing series, changing category/tags & adding/removing favourites).
<br>
The site has been live for a total of <b><?=$stats['live_time']?></b> since <b>2016-09-10 03:17:19</b>.</p>
