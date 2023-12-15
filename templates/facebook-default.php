<?php

// Default Facebook Feed Template 

$feedDisplay = ''; 

$i = 0;
foreach($pagefeed['data'] as $post) {
	
	//$postInfo = ''; 
	//foreach ($post as $key => $val) {
	//	$postInfo = $key .': '. $val .'<br>';
	//}
	//$feedDisplay .= '<p>'. $postInfo.'</p>';
	
	if ($post['type'] == 'status' || $post['type'] == 'link' || $post['type'] == 'photo') {
    	// open up an fb-update div
     	$feedDisplay .= '<div class="fb-update">';
                        
     	// post the time
     	if ($post['type'] == 'status') {
        	$feedDisplay .= '<h2>Status updated: ' . date('jS M, Y', (strtotime($post['created_time']))) . '</h2>';
         	if (empty($post['story']) === false) {
          		$feedDisplay .= '<div>' . $post['story'] . '</div>';
         	} elseif (empty($post['message']) === false) {
            	$feedDisplay .= '<div>' . $post['message'] . '</div>';
          	}
    	}
                        
    	// check if post type is a link
     	if ($post['type'] == 'link') {
     		$feedDisplay .= '<h2>Link posted on: ' . date('jS M, Y', (strtotime($post['created_time']))) . '</h2>';
         	$feedDisplay .= '<div>' . $post['name'] . '</div>';
			if (empty($post['message']) === false) {
            	$feedDisplay .= '<div>' . $post['message'] . '</div>';
          	}
        	$feedDisplay .= '<a href="' . $post['link'] . '" target="_blank">' . $post['link'] . '</a>';
    	}
                        
     	// check if post type is a photo
     	if ($post['type'] == 'photo') {
        	$feedDisplay .= '<h2>Photo posted on: ' . date('jS M, Y', (strtotime($post['created_time']))) . '</h2>';
  			if (empty($post['story']) === false) {
            	$feedDisplay .= '<div>' . $post['story'] . '</div>';
    		} elseif (empty($post['message']) === false) {
          		$feedDisplay .= '<p>' . $post['message'] . '</p>';
          	}
            $feedDisplay .= '<p><a href="' . $post['link'] . '" target="_blank">View photo &rarr;</a></p>';
    	}
                    
        $feedDisplay .= '</div>'; // close fb-update div
    	$i++; // add 1 to the counter if our condition for $post['type'] is met                
    }
   	if ($i == 3) { break; }
}            
$feedDisplay .= '</div>';

?>