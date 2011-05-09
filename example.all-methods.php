<?php

require ('raventools-api-php.class.php');

if (!defined('RAVEN_API_KEY')) {
  // Edit the line below with your API Key to use this script
  define('RAVEN_API_KEY', 'YOUR_API_KEY_HERE');
}

// Change these to match your report queries
$keyword = 'web design nashville';
$domain = 'www.centresource.com';
$start_date = '2011-01-01';
$end_date = '2011-01-31';


// Make sure the script has an API Key is valid.
if (! RavenTools::validateAPIKey(RAVEN_API_KEY) ) {
	die ("Thanks for testing out the library! You need to edit the <tt>./example.all-methods.php</tt> file to include your profile's API Key before this script will run. Just change <tt>YOUR_API_KEY_HERE</tt> to the correct value.");
}

$Raven = new RavenTools(RAVEN_API_KEY);

?>

<h1>Raven Tools API Test</h1>

<h2>Report Parameters</h3>
<ul>
  <li><strong>Domain:</strong> <?php echo $domain; ?>n</li>
  <li><strong>Keyword:</strong> <?php echo $keyword; ?></li>
  <li><strong>Start Date:</strong> <?php echo $start_date; ?></li>
  <li><strong>End Date:</strong> <?php echo $end_date; ?></li>
</ul>

<h2>Results</h2>
  <li><a href="#rank">Rank</a></li>
  <li><a href="#rank_all">Rank All</a></li>
  <li><a href="#domains">Domains</a></li>
  <li><a href="#rank_max_week">Rank Max Week</a></li>
  <li><a href="#engines">Engines</a></li>
  <li><a href="#profile_info">Profile Info</a></li>
  <li><a href="#domain_info">Domain Info</a></li>
  <li><a href="#competitors">Competitors</a></li>
  <li><a href="#keywords">Keywords</a></li>
</ul>

<h3>Test Results</h3>

<hr />

<h3 id="rank">Rank (method:rank)</h3>

<?php
$rank = $Raven->get('rank', array(
	'keyword'=>$keyword,
	'domain'=>$domain,
	'start_date'=>$start_date,
	'end_date'=>$end_date,
	'engine'=>'all')
	);
?>
<p>Request: <tt> <?php echo $Raven->request ?></tt></p>
<p>Response:</p>
<pre>
<?php var_dump($rank); ?>
</pre>

<p><a href="#top">Back to Top</a></p>

<hr />

<?php
$rank_all = $Raven->get('rank_all', array(
	'domain'=>$domain,
	'start_date'=>$start_date,
	'end_date'=>$end_date)
	);
?>
<h3 id="rank_all">Rank All (method:rank)</h3>
<p>Request: <tt> <?php echo $Raven->request; ?></tt></p>
<p>Response:</p>
<pre>
<?php var_dump($rank_all); ?>
</pre>

<p><a href="#top">Back to Top</a></p>

<hr />

<?php
$domains = $Raven->get('domains');
?>
<h3 id="domains">Domains (method:domains)</h3>
<p>Request: <tt> <?php echo $Raven->request ?></tt></p>
<p>Response:</p>
<pre>
<?php var_dump($domains); ?>
</pre>

<p><a href="#top">Back to Top</a></p>

<hr />

<?php
$rank_max_week = $Raven->get('rank_max_week', array(
	'domain'=>$domain,
	'keyword'=>$keyword)
	);
?>
<h3 id="rank_max_week">Rank Max Week (method:rank_max_week)</h3>
<p>Request: <tt> <?php echo $Raven->request; ?></tt></p>
<p>Response:</p>
<pre>
<?php var_dump($rank_max_week); ?>
</pre>

<p><a href="#top">Back to Top</a></p>

<hr />

<?php
$engines = $Raven->get('engines');
?>
<h3 id="engines">Engines (method:engines)</h3>';
<p>Request: <tt> <?php echo $Raven->request; ?></tt></p>
<p>Response:</p>
<pre>
<?php var_dump($engines); ?>
</pre>

<p><a href="#top">Back to Top</a></p>

<hr />

<?php
$profile_info = $Raven->get('profile_info');
?>
<h3 id="profile_info">Profile Info (method:profile_info)</h3>';
<p>Request: <tt> <?php echo $Raven->request; ?></tt></p>
<p>Response:</p>
<pre>
<?php var_dump($profile_info); ?>
</pre>

<p><a href="#top">Back to Top</a></p>

<hr />

<?php
$domain_info = $Raven->get('domain_info');
?>
<h3 id="domain_info">Domain Info (method:domain_info)</h3>';
<p>Request: <tt> <?php echo $Raven->request; ?></tt></p>
<p>Response:</p>
<pre>
<?php var_dump($rank); ?>
</pre>

<p><a href="#top">Back to Top</a></p>

<hr />

<?php
$competitors = $Raven->get('competitors', array('domain'=>$domain));
?>
<h3 id="competitors">Competitors (method:competitors)</h3>';
<p>Request: <tt> <?php echo $Raven->request; ?></tt></p>
<p>Response:</p>
<pre>
	<?php var_dump($rank); ?>
</pre>

<p><a href="#top">Back to Top</a></p>

<hr />

<?php
$keywords = $Raven->get('keywords', array('domain'=>$domain));
?>
<h3 id="keywords">Keywords (method:keywords)</h3>';
<p>Request: <tt> <?php echo $Raven->request; ?></tt></p>
<p>Response:</p>
<pre>
<?php var_dump($rank); ?>
</pre>

<p><a href="#top">Back to Top</a></p>