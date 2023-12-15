<?php

// Default Portfolio Display Template
// $portfolio_array = (id,image,title,website,icon,icon-meta,icon-alt,icon-hover,icon-hover-meta,icon-hover-alt,link,excerpt,content,twitter,facebook);

$portCount = count($portfolio_array);

$colClass = (($portCount %4 == 0) ? 'col-xs-12 col-sm-6 col-md-3 col-lg-3' : (($portCount %3 == 0)  ? 'col-xs-12 col-sm-6 col-md-4 col-lg-4' : (($portCount %2 == 0)  ? 'col-xs-12 col-sm-6 col-md-6' : 'col-xs-12 col-sm-12 col-md-12')));

$portDisplay = '';
$portDisplay .= '<div class="row services">';

foreach ($portfolio_array as $port) {
	$portDisplay .= '<div class="col '. $colClass .'">';
	if ($port['icon']) {
		$portDisplay .= (($port['link']) ? '<a href="'. $port['link'] .'" class="portfolio-icon-link">' : '');
		$portDisplay .= (($port['icon']) ? '<img src="'. $port['icon'] .'" class="portfolio-icon" alt="'. $port['title'] .'">' : '');
		$portDisplay .= (($port['link']) ? '</a>' : '');
		$endLink = '';
	} elseif ($port['link']) {
		$portDisplay .= (($port['link']) ? '<a href="'. $port['link'] .'" class="portfolio-title-link">' : '');
		$endLink = '</a>'; 
	}
	$portDisplay .= (($port['title']) ? '<h3 class="portfolio-title">'. $port['title'] .'</h3>' : '');
	$portDisplay .= $endLink;
	$portDisplay .= (($port['website']) ? '<h4>'. $port['website'] .'</h4>' : '');
	$portDisplay .= (($port['excerpt']) ? $port['excerpt'] : (($port['content']) ? $port['content'] : ''));
	$portDisplay .= '</div><!-- /col -->';
}

$portDisplay .= '</div><!-- /row -->'; 				

?>