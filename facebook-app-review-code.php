<?php

				// If Facebook Feed should be displayed
				$port['facebook'] = get_post_meta($port['id'],'comoportfolio-facebook',true);
				$fbappId = get_post_meta($port['id'],'comoportfolio-facebook-appId',true);
				$fbappSecret = get_post_meta($port['id'],'comoportfolio-facebook-secret',true);
				$pageid = get_post_meta($port['id'],'comoportfolio-facebook-pageid',true);
				
				if ($port['facebook'] && $fbappId && $fbappSecret && $pageid) {
					// include the facebook sdk
					require_once(plugin_dir_path( __FILE__ ) .'assets/facebook-php-sdk-master/facebook.php');
					
					$port['fbDisplayName'] = get_post_meta($port['id'],'comoportfolio-facebook-displayname',true);
					$port['fbPageLink'] = get_post_meta($port['id'],'comoportfolio-facebook-pagelink',true);
					
					// Facebook Feed Icon
					$port['facebook-icon'] = get_post_meta($port['id'],'comoportfolio-facebook-icon',true);
					$port['facebook-icon-alt'] = get_post_meta($iconMetaID,'_wp_attachment_image_alt',true);
					
					// Connect to Facebook app
					
					$config = array();
					$config['appId'] = $fbappId;
					$config['secret'] = $fbappSecret;
					$config['fileUpload'] = false; // optional
					$facebook = new Facebook($config);
					$pagefeed = $facebook->api("/" . $pageid . "/feed/?fields=type,message,id,story,link");
					$port['facebook'] = $pagefeed; 
					
					// Get facebook Feed Template
					if ($fbtemp) {
						include((file_exists($fbtemp)) ? $fbtemp : plugin_dir_path( __FILE__ ) .'templates/facebook-default.php');
					} else {
						include(plugin_dir_path( __FILE__ ) .'templates/facebook-default.php');
					}
					$port['facebook'] = $feedDisplay;
				}
				$portfolio_array[] = $port;



$feedDisplay = ''; 
$i = 0;

$custIcon = (($port['facebook-icon']) ? '<img src="'. $port['facebook-icon'] .'" class="feed-icon img-responsive" alt="'. $port['icon-hover-alt'] .'">' : '');

foreach($pagefeed['data'] as $post) {
	
	if ($post['type'] == 'status' || $post['type'] == 'link' || $post['type'] == 'photo' || $post['type'] == 'video') {
		
		$date_source = strtotime($post['created_time']);
		$timestamp = date('Y-m-d H:i:s', $date_source);
		$postDate = $timestamp;
		$postDate = date('M jS', (strtotime($post['created_time'])));
		
		$feedDisplay .= '<div class="row post">
							<div class="col-xs-2 col-sm-3 feed-icon"><a href="'. $port['fbPageLink'] .'" target="_blank" title="View on Facebook">'. $custIcon .'</a></div>
							<div class="col-xs-10 col-sm-9 feed-content">
								<header class="row">
									<span class="col-xs-8 meta user"><a href="'. $port['fbPageLink'] .'" target="_blank" title="View on Facebook">'. $port['fbDisplayName'] .'</a></span>
									<span class="col-xs-4 meta date">'. $postDate .'</span>
									<span class="col-xs-12"><hr /></span>
								</header>
								<div class="message">';
		//$feedDisplay .= '<p>'. $post['type'] .'</p>'; 
		// check if post type is a status
     	if ($post['type'] == 'status') {
        	$feedDisplay .= (($post['story']) ? $post['story'] : (($post['message']) ? $post['message'] : ''));
    	}
                        
    	// check if post type is a link
     	if ($post['type'] == 'link') {
			$feedDisplay .= (($post['story']) ? $post['story'] : (($post['message']) ? $post['message'] : ''));
			$feedDisplay .= (($post['link']) ? '<br><a href="' . $post['link'] . '" target="_blank">Visit link &rarr;</a>' : '');
    	}
                        
     	// check if post type is a photo
     	if ($post['type'] == 'photo') {
			$feedDisplay .= (($post['story']) ? $post['story'] : (($post['message']) ? $post['message'] : ''));
			$feedDisplay .= (($post['link']) ? '<br><a href="' . $post['link'] . '" target="_blank">View photo &rarr;</a>' : '');
    	}
		
		// check if post type is a video
     	if ($post['type'] == 'video') {
			$feedDisplay .= (($post['story']) ? $post['story'] : (($post['message']) ? $post['message'] : ''));
			$feedDisplay .= (($post['link']) ? '<br><a href="' . $post['link'] . '" target="_blank">View video &rarr;</a>' : '');
    	}
		
		$feedDisplay .= '		</div><!-- /message -->
							</div><!-- /col -->
						</div><!-- /post -->'; 
    	$i++;              
    }
   	if ($i == 3) { break; }
}   
$feedDisplay = '<div class="facebook-feed">' . ((!empty($feedDisplay)) ? $feedDisplay : 'Our facebook feed is unavailable right now.' ) . '</div><!-- /facebook-feed -->';  

?>