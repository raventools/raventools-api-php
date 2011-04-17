<?php

require ('raventools-php.class.php');

$api_key = ''; // Set your API key here
$Raven = new RavenTools($api_key);

$keyword = 'web design nashville';
$domain = 'www.centresource.com';
$start_date = '2011-01-01';
$end_date = '2011-01-31';

print '<h1>Raven Tools API Test</h1>';

print '<h2>Report Parameters</h3>';
print '<ul>';
print '  <li><strong>Domain:</strong> ' . $domain . '</li>';
print '  <li><strong>Keyword:</strong> ' . $keyword . '</li>';
print '  <li><strong>Start Date:</strong> ' . $start_date . '</li>';
print '  <li><strong>End Date:</strong> ' . $end_date . '</li>';
print '</ul>';

print '<h2>Results</h2>';
print '  <li><a href="#rank">Rank</a></li>';
print '  <li><a href="#rank_all">Rank All</a></li>';
print '  <li><a href="#domains">Domains</a></li>';
print '  <li><a href="#rank_max_week">Rank Max Week</a></li>';
print '  <li><a href="#engines">Engines</a></li>';
print '  <li><a href="#profile_info">Profile Info</a></li>';
print '  <li><a href="#domain_info">Domain Info</a></li>';
print '  <li><a href="#competitors">Competitors</a></li>';
print '  <li><a href="#keywords">Keywords</a></li>';
print '</ul>';

print '<h3>Test Results</h3>';

print '<hr />';

$rank = $Raven->get('rank', array('keyword'=>$keyword,'domain'=>$domain,'start_date'=>$start_date,'end_date'=>$end_date));
print '<h3 id="rank">Rank (method:rank)</h3>';
print '<p>Request: <tt>' . $Raven->request . '</tt></p>';
print '<p>Response:</p>';
print '<pre>';
var_dump($rank);
print '</pre>';
print '<p><a href="#top">Back to Top</a></p><hr />';

$rank_all = $Raven->get('rank_all', array('domain'=>$domain,'start_date'=>$start_date,'end_date'=>$end_date));
print '<h3 id="rank_all">Rank All (method:rank)</h3>';
print '<p>Request: <tt>' . $Raven->request . '</tt></p>';
print '<p>Response:</p>';
print '<pre>';
var_dump($rank_all);
print '</pre>';
print '<p><a href="#top">Back to Top</a></p><hr />';

$domains = $Raven->get('domains');
print '<h3 id="domains">Domains (method:domains)</h3>';
print '<p>Request: <tt>' . $Raven->request . '</tt></p>';
print '<p>Response:</p>';
print '<pre>';
var_dump($domains);
print '</pre>';
print '<p><a href="#top">Back to Top</a></p><hr />';

$rank_max_week = $Raven->get('rank_max_week', array('domain'=>$domain,'keyword'=>$keyword));
print '<h3 id="rank_max_week">Rank Max Week (method:rank_max_week)</h3>';
print '<p>Request: <tt>' . $Raven->request . '</tt></p>';
print '<p>Response:</p>';
print '<pre>';
var_dump($rank_max_week);
print '</pre>';
print '<p><a href="#top">Back to Top</a></p><hr />';
print '<p><a href="#top">Back to Top</a></p><hr />';

$engines = $Raven->get('engines');
print '<h3 id="engines">Engines (method:engines)</h3>';
print '<p>Request: <tt>' . $Raven->request . '</tt></p>';
print '<p>Response:</p>';
print '<pre>';
var_dump($engines);
print '</pre>';
print '<p><a href="#top">Back to Top</a></p><hr />';

$profile_info = $Raven->get('profile_info');
print '<h3 id="profile_info">Profile Info (method:profile_info)</h3>';
print '<p>Request: <tt>' . $Raven->request . '</tt></p>';
print '<p>Response:</p>';
print '<pre>';
var_dump($profile_info);
print '</pre>';
print '<p><a href="#top">Back to Top</a></p><hr />';

$domain_info = $Raven->get('domain_info');
print '<h3 id="domain_info">Domain Info (method:domain_info)</h3>';
print '<p>Request: <tt>' . $Raven->request . '</tt></p>';
print '<p>Response:</p>';
print '<pre>';
var_dump($rank);
print '</pre>';
print '<p><a href="#top">Back to Top</a></p><hr />';

$competitors = $Raven->get('competitors', array('domain'=>$domain));
print '<h3 id="competitors">Competitors (method:competitors)</h3>';
print '<p>Request: <tt>' . $Raven->request . '</tt></p>';
print '<p>Response:</p>';
print '<pre>';
var_dump($rank);
print '</pre>';
print '<p><a href="#top">Back to Top</a></p><hr />';

$keywords = $Raven->get('keywords', array('domain'=>$domain));
print '<h3 id="keywords">Keywords (method:keywords)</h3>';
print '<p>Request: <tt>' . $Raven->request . '</tt></p>';
print '<p>Response:</p>';
print '<pre>';
var_dump($rank);
print '</pre>';
print '<p><a href="#top">Back to Top</a></p><hr />';