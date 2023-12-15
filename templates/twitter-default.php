<?php

// Default Twitter Feed Template and Specifications

$twitter_template['enable_cache']        = true;
$twitter_template['cache_dir']           = get_stylesheet_directory() .'/comostrap-portfolio/cache/twitter/'; // Where on the server to save cached tweets
$twitter_template['cachetime']           = 60 * 60; // Seconds to cache feed (60 * 60 = 1 hour).
$twitter_template['tweets_to_retrieve']  = 10; // Specifies the number of tweets to try and fetch, up to a maximum of 200
$twitter_template['tweets_to_display']   = 3; // Number of tweets to display
$twitter_template['ignore_replies']      = true; // Ignore @replies
$twitter_template['ignore_retweets']     = true; // Ignore retweets
$twitter_template['twitter_style_dates'] = false; // Use twitter style dates e.g. 2 hours ago
$twitter_template['twitter_date_text']   = array('seconds', 'minutes', 'about', 'hour', 'ago');
$twitter_template['date_format']         = '%I:%M %p %b %e%O'; // The defult date format e.g. 12:08 PM Jun 12th. See: http://php.net/manual/en/function.strftime.php
$twitter_template['date_lang']           = null; // Language for date e.g. 'fr_FR'. See: http://php.net/manual/en/function.setlocale.php
$twitter_template['twitter_template']    = '<ul id="twitter">{tweets}</ul>';
$twitter_template['tweet_template']      = '<li class="tweet"><span class="meta"><a href="{link}">{date}</a></span><span class="status">{tweet}</span></li>';
$twitter_template['error_template']      = '<li><span class="status">Our twitter feed is unavailable right now.</span> <span class="meta"><a href="{link}">Follow us on Twitter</a></span></li>';
$twitter_template['debug']               = false;

?>