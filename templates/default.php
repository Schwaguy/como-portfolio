<?php

// Default Portfolio Display Template
// $portfolio_array = (id,image,title,website,icon,icon-meta,icon-alt,icon-hover,icon-hover-meta,icon-hover-alt,link,excerpt,content,twitter,twitter-feed,facebook);

$portCount = count($portfolio_array);

$colClass = (($portCount %4 == 0) ? 'col-xs-12 col-sm-6 col-md-3 col-lg-3' : (($portCount %3 == 0)  ? 'col-xs-12 col-sm-6 col-md-4 col-lg-4' : (($portCount %2 == 0)  ? 'col-xs-12 col-sm-6 col-md-6' : 'col-xs-12 col-sm-12 col-md-12')));

$portDisplay = '';
$portLogos = ''; 
$portDetails = ''; 

foreach ($portfolio_array as $port) {
	
	//if ($port['icon']) {
		// Display Portfolio Logos
		$upload_path = substr($port['icon-link'], 0, strrpos($port['icon-link'], '/'));
		$portLogos .= '<a class="portfolio-logo show-on-scroll bounceIn-on-scroll no-repeat" href="#'. cleanLinkTitle($port['title']) .'" id="'. cleanLinkTitle($port['title']) .'-link" data-client="'. cleanLinkTitle($port['title']) .'">'; 
		$portLogos .= (($port['icon']) ? '<img src="'. $upload_path . '/'. $port['icon']['sizes']['portfolio-logo']['file'] .'" class="logo img-responsive" alt="'. $port['icon-alt'] .'">' : '');
		$portLogos .= (($port['icon-hover']) ? '<img src="'. $upload_path . '/'. $port['icon-hover']['sizes']['portfolio-logo']['file'] .'" class="hover-logo img-responsive" alt="'. $port['icon-hover-alt'] .'">' : '');
		$portLogos .= '</a>';
		
		// Build Portfolio Detail Boxes
		$portDetails .= '<div class="portfolio-detail" id="'. cleanLinkTitle($port['title']) .'">';
		//$portDetails .= '<a href="#" class="boxclose" data-client="'. cleanLinkTitle($port['title']) .'"></a>';
		
		/*if ($port['icon']['sizes']['portfolio-logo']) {
			$portDetails .= (($port['icon']) ? '<img src="'. $upload_path . '/'. $port['icon']['sizes']['portfolio-logo']['file'] .'" class="logo img-responsive" alt="'. $port['icon-alt'] .'">' : '');
		} else {
			$portDetails .= (($port['icon']) ? '<img src="'. $upload_path . '/'. $port['icon']['sizes']['portfolio-logo']['file'] .'" class="logo" alt="'. $port['icon-alt'] .'">' : '');
		}*/
		
		if ($port['image'] || $port['content']) {
			$portDetails .= '<div class="row portfolio-row portfolio-intro">';
			$portDetails .= '<div class="col-xs-12 col-sm-6 col-md-6">';
			if ($port['image']) {
				$portImg = get_the_post_thumbnail($port['id'],'large',array('class'=>'portfolio-detail-img img-responsive'));
				$portDetails .= $portImg;
			}
			$portDetails .= '</div>';
			$portDetails .= '<div class="col-xs-12 col-sm-6 col-md-6">';
			$portDetails .= (($port['content']) ? apply_filters('the_content', $port['content']) : ''); 
			$portDetails .= '</div>';
			$portDetails .= '</div><!-- /portfolio-intro -->';
		}
		if ($port['twitter'] || $port['facebook']) {
			$portDetails .= '<div class="row portfolio-row portfolio-social">';
			$portDetails .= '<div class="col-xs-12 col-sm-6 col-md-6">';
			$portDetails .= (($port['twitter']) ? '<header class="social-head"><i class="fa fa-twitter" aria-hidden="true"></i></header><div class="social-feed">'. $port['twitter']->get_tweet_list() .'</div>' : '');
			$portDetails .= '</div>';
			$portDetails .= '<div class="col-xs-12 col-sm-6 col-md-6">';
			$portDetails .= (($port['facebook']) ? '<header class="social-head"><i class="fa fa-facebook" aria-hidden="true"></i></header><div class="social-feed">'. $port['facebook'] .'</div>' : '');
			$portDetails .= '</div>';
			$portDetails .= '</div><!-- /portfolio-social -->';  
		}
		
		$portDetails .= '</div><!-- /portfolio-detail -->';
		
	//} 
	
}

//$portDisplay .= '<div id="portfolio-logos">'. $portLogos .'</div><!-- /portfolio-logos -->'; 
$portDisplay .= '<div id="portfolio-details">'. $portDetails .'</div><!-- /portfolio-details -->'; 	

function add_port_script() {
	echo '<script src="'. get_stylesheet_directory_uri() .'/js/portfolio.js"></script>';
}
add_action('wp_footer','add_port_script',100);			

?>