<?php

/*
Plugin Name: Como Portfolio
Plugin URI: http://www.comocreative.com/
Version: 2.01
Author: Como Creative LLC
Description: Plugin designed to work with the Como Themes to enable and easy Portfolio Section. 
Shortcode: [comoportfolio featured=TRUE/FALSE template=TEMPLATE NAME twitter=TWITTER TEMPLATE facebook="FACEBOOK TEMPLATE orderby=DATE/TITLE/MENU_ORDER order=ASC/DESC].  
Custom templates can be created in your theme in a folder named "como-portfolio" 
*/

session_start();
defined('ABSPATH') or die('No Hackers!');

// Include plugin updater.
require_once( trailingslashit( plugin_dir_path( __FILE__ ) ) . 'includes/updater.php' );

/* ##################### Define Portfolio Item Post Type ##################### */
if ( ! function_exists('comoportfolio_post_type') ) {
	function comoportfolio_post_type() {
		$labels = array(
			'name'                  => _x('Portfolio', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x('Portfolio Item', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __('Portfolio', 'text_domain' ),
			'name_admin_bar'        => __('Portfolio Item', 'text_domain' ),
			'archives'              => __('Portfolio Item Archives', 'text_domain' ),
			'parent_item_colon'     => __('Parent Portfolio Item:', 'text_domain' ),
			'all_items'             => __('All Portfolio', 'text_domain' ),
			'add_new_item'          => __('Add New Portfolio Item', 'text_domain' ),
			'add_new'               => __('Add New', 'text_domain' ),
			'new_item'              => __('New Portfolio Item', 'text_domain' ),
			'edit_item'             => __('Edit Portfolio Item', 'text_domain' ),
			'update_item'           => __('Update Portfolio Item', 'text_domain' ),
			'view_item'             => __('View Portfolio Item', 'text_domain' ),
			'search_items'          => __('Search Portfolio', 'text_domain' ),
			'not_found'             => __('Not found', 'text_domain' ),
			'not_found_in_trash'    => __('Not found in Trash', 'text_domain' ),
			'featured_image'        => __('Portfolio Item Image', 'text_domain' ),
			'set_featured_image'    => __('Set Portfolio Item image', 'text_domain' ),
			'remove_featured_image' => __('Remove Portfolio Item image', 'text_domain' ),
			'use_featured_image'    => __('Use as Portfolio Item image', 'text_domain' ),
			'insert_into_item'      => __('Insert into Portfolio', 'text_domain' ),
			'uploaded_to_this_item' => __('Uploaded to this Portfolio Item', 'text_domain' ),
			'items_list'            => __('Portfolio Item list', 'text_domain' ),
			'items_list_navigation' => __('Portfolio Item list navigation', 'text_domain' ),
			'filter_items_list'     => __('Filter Portfolio list', 'text_domain' ),
		);
		$args = array(
			'label'                 => __('Portfolio Item', 'text_domain' ),
			'description'           => __('Portfolio to be displayed on website', 'text_domain' ),
			'labels'                => $labels,
			'supports'              => array('title','editor','excerpt','thumbnail','page-attributes','custom-fields'),
			'taxonomies'			=> array(),
			'hierarchical'          => true,
			'public'                => true,
			'show_in_rest' 			=> true,
			"rest_base" 			=> "",
        	"rest_controller_class" => "WP_REST_Posts_Controller",
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-admin-page',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'portfolio', $args );
	}
	add_action( 'init', 'comoportfolio_post_type', 0 );
}

// Project Type Taxonomy 
add_action( 'init', 'create_project_tax', 0 );
function create_project_tax() {
	$labels = array(
		'name'              => _x( 'Project Type', 'taxonomy general name' ),
		'singular_name'     => _x( 'Project Type', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Project Types' ),
		'all_items'         => __( 'All Project Types' ),
		'parent_item'       => __( 'Parent Project Type' ),
		'parent_item_colon' => __( 'Parent Project Type:' ),
		'edit_item'         => __( 'Edit Project Type' ),
		'update_item'       => __( 'Update Project Type' ),
		'add_new_item'      => __( 'Add New Project Type' ),
		'new_item_name'     => __( 'New Project Type' ),
		'menu_name'         => __( 'Project Type' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'project-type' ),
	);
	register_taxonomy('project-type', array('portfolio'), $args );
}
add_action( 'restrict_manage_posts', 'project_restrict_manage_posts');

function project_restrict_manage_posts() {
	global $typenow;
	$taxonomy = 'project-type';
	if( $typenow != "page" && $typenow != "post" ){
		$filters = array($taxonomy);
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Show All ". $tax_name ."</option>";
			foreach ($terms as $term) { echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; }
			echo "</select>";
		}
	}
}

// Development Taxonomy 
add_action( 'init', 'create_development_tax', 0 );
function create_development_tax() {
	$singular = 'Development Tool'; 
	$plural = 'Development Tools'; 
	$labels = array(
		'name'              => _x( $plural, 'taxonomy general name' ),
		'singular_name'     => _x( $singular, 'taxonomy singular name' ),
		'search_items'      => __( 'Search Development Tools' ),
		'all_items'         => __( 'All Development Types' ),
		'parent_item'       => __( 'Parent '. $singular ),
		'parent_item_colon' => __( 'Parent '. $singular .':' ),
		'edit_item'         => __( 'Edit ', $singular ),
		'update_item'       => __( 'Update '. $singular ),
		'add_new_item'      => __( 'Add New '. $singular ),
		'new_item_name'     => __( 'New '. $singular ),
		'menu_name'         => __( $plural ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'development-tool' ),
	);
	register_taxonomy('development-tool', array('portfolio'), $args );
}
add_action( 'restrict_manage_posts', 'development_restrict_manage_posts');

function development_restrict_manage_posts() {
	global $typenow;
	$taxonomy = 'development-tool';
	if( $typenow != "page" && $typenow != "post" ){
		$filters = array($taxonomy);
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Show All ". $tax_name ."</option>";
			foreach ($terms as $term) { echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; }
			echo "</select>";
		}
	}
}

// Client Type Taxonomy 
add_action( 'init', 'create_client_tax', 0 );
function create_client_tax() {
	$labels = array(
		'name'              => _x( 'Client Type', 'taxonomy general name' ),
		'singular_name'     => _x( 'Client Type', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Client Types' ),
		'all_items'         => __( 'All Client Types' ),
		'parent_item'       => __( 'Parent Client Type' ),
		'parent_item_colon' => __( 'Parent Client Type:' ),
		'edit_item'         => __( 'Edit Client Type' ),
		'update_item'       => __( 'Update Client Type' ),
		'add_new_item'      => __( 'Add New Client Type' ),
		'new_item_name'     => __( 'New Client Type' ),
		'menu_name'         => __( 'Client Type' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'client-type' ),
	);
	register_taxonomy('client-type', array('portfolio'), $args );
}
add_action( 'restrict_manage_posts', 'client_restrict_manage_posts');

function client_restrict_manage_posts() {
	global $typenow;
	$taxonomy = 'client-type';
	if( $typenow != "page" && $typenow != "post" ){
		$filters = array($taxonomy);
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Show All ". $tax_name ."</option>";
			foreach ($terms as $term) { echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; }
			echo "</select>";
		}
	}
}

// Developed For Taxonomy 
add_action( 'init', 'create_developedfor_tax', 0 );
function create_developedfor_tax() {
	$labels = array(
		'name'              => _x( 'Developed For', 'taxonomy general name' ),
		'singular_name'     => _x( 'Developed For', 'taxonomy singular name' ),
		'search_items'      => __( 'Search Developed For' ),
		'all_items'         => __( 'All Developed For' ),
		'parent_item'       => __( 'Parent Developed For' ),
		'parent_item_colon' => __( 'Parent Developed For:' ),
		'edit_item'         => __( 'Edit Developed For' ),
		'update_item'       => __( 'Update Developed For' ),
		'add_new_item'      => __( 'Add New Developed For' ),
		'new_item_name'     => __( 'New Developed For' ),
		'menu_name'         => __( 'Developed For' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'developed-for' ),
	);
	register_taxonomy('developed-for', array('portfolio'), $args );
}

add_action( 'restrict_manage_posts', 'developedfor_restrict_manage_posts');
function developedfor_restrict_manage_posts() {
	global $typenow;
	$taxonomy = 'developed-for';
	if( $typenow != "page" && $typenow != "post" ){
		$filters = array($taxonomy);
		foreach ($filters as $tax_slug) {
			$tax_obj = get_taxonomy($tax_slug);
			$tax_name = $tax_obj->labels->name;
			$terms = get_terms($tax_slug);
			echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
			echo "<option value=''>Show All ". $tax_name ."</option>";
			foreach ($terms as $term) { echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; }
			echo "</select>";
		}
	}
}

// Development Tool Type Extra Fields
if (!class_exists('add_devTools_logo_field') ) {
	  class add_devTools_logo_field {
		public function __construct() {
		 //
		}
		/**
		 * Initialize the class and start calling our hooks and filters
		 */
		 public function init() {
		 // Image actions
		 add_action( 'development-tool_add_form_fields', array( $this, 'add_category_image' ), 10, 2 );
		 add_action( 'created_development-tool', array( $this, 'save_category_image' ), 10, 2 );
		 add_action( 'development-tool_edit_form_fields', array( $this, 'update_category_image' ), 10, 2 );
		 add_action( 'edited_development-tool', array( $this, 'updated_category_image' ), 10, 2 );
		 add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
		 add_action( 'admin_footer', array( $this, 'add_script' ) );
	   }
	   public function load_media() {
		 if( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != 'development-tool' ) {
		   return;
		 }
		 wp_enqueue_media();
	   }
	   /**
		* Add a form field in the new category page
		*/
	   public function add_category_image( $taxonomy ) { ?>
		 <div class="form-field term-group">
		   <label for="development-tool-image"><?php _e( 'Image', 'como-bands' ); ?></label>
		   <input type="hidden" id="development-tool-image" name="development-tool-image" class="custom_media_url" value="">
		   <div id="category-image-wrapper"></div>
		   <p>
			 <input type="button" class="button button-secondary development_tool_tax_media_button" id="development_tool_tax_media_button" name="development_tool_tax_media_button" value="<?php _e( 'Add Image', 'showcase' ); ?>" />
			 <input type="button" class="button button-secondary development_tool_tax_media_remove" id="development_tool_tax_media_remove" name="development_tool_tax_media_remove" value="<?php _e( 'Remove Image', 'showcase' ); ?>" />
		   </p>
		 </div>
	   <?php }
	   /**
		* Save the form field
		*/
	   public function save_category_image( $term_id, $tt_id ) {
		 if( isset( $_POST['development-tool-image'] ) && '' !== $_POST['development-tool-image'] ){
		   add_term_meta( $term_id, 'development-tool-image', absint( $_POST['development-tool-image'] ), true );
		 }
		}
		/**
		 * Edit the form field
		 */
		public function update_category_image( $term, $taxonomy ) { ?>
		  <tr class="form-field term-group-wrap">
			<th scope="row">
			  <label for="development-tool-image"><?php _e( 'Image', 'como-bands' ); ?></label>
			</th>
			<td>
			  <?php $image_id = get_term_meta( $term->term_id, 'development-tool-image', true ); ?>
			  <input type="hidden" id="development-tool-image" name="development-tool-image" value="<?php echo esc_attr( $image_id ); ?>">
			  <div id="category-image-wrapper">
				<?php if( $image_id ) { ?>
				  <?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
				<?php } ?>
			  </div>
			  <p>
				<input type="button" class="button button-secondary development_tool_tax_media_button" id="development_tool_tax_media_button" name="development_tool_tax_media_button" value="<?php _e( 'Add Image', 'showcase' ); ?>" />
				<input type="button" class="button button-secondary development_tool_tax_media_remove" id="development_tool_tax_media_remove" name="development_tool_tax_media_remove" value="<?php _e( 'Remove Image', 'showcase' ); ?>" />
			  </p>
			</td>
		  </tr>
	   <?php }
	   /**
		* Update the form field value
		*/
	   public function updated_category_image( $term_id, $tt_id ) {
		 if( isset( $_POST['development-tool-image'] ) && '' !== $_POST['development-tool-image'] ){
		   update_term_meta( $term_id, 'development-tool-image', absint( $_POST['development-tool-image'] ) );
		 } else {
		   update_term_meta( $term_id, 'development-tool-image', '' );
		 }
	   }
	   /**
		* Enqueue styles and scripts
		*/
	   public function add_script() {
		 if( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != 'development-tool' ) {
		   return;
		 } ?>
		 <script> jQuery(document).ready( function($) {
		   _wpMediaViewsL10n.insertIntoPost = '<?php _e( "Insert", "como-bands" ); ?>';
		   function ct_media_upload(button_class) {
			 var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;
			 $('body').on('click', button_class, function(e) {
			   var button_id = '#'+$(this).attr('id');
			   var send_attachment_bkp = wp.media.editor.send.attachment;
			   var button = $(button_id);
			   _custom_media = true;
			   wp.media.editor.send.attachment = function(props, attachment){
				 if( _custom_media ) {
				   $('#development-tool-image').val(attachment.id);
				   $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
				   $('#category-image-wrapper .custom_media_image' ).attr( 'src',attachment.url ).css( 'display','block' );
				 } else {
				   return _orig_send_attachment.apply( button_id, [props, attachment] );
				 }
			   }
			   wp.media.editor.open(button); return false;
			 });
		   }
		   ct_media_upload('.development_tool_tax_media_button.button');
		   $('body').on('click','.development_tool_tax_media_remove',function(){
			 $('#development-tool-image').val('');
			 $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
		   });
			$(document).ajaxComplete(function(event, xhr, settings) {
			 var queryStringArr = settings.data.split('&');
			 if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
			   var xml = xhr.responseXML;
			   $response = $(xml).find('term_id').text();
			   if($response!=""){
				 // Clear the thumb image
				 $('#category-image-wrapper').html('');
			   }
			  }
			});
		  });
		</script>
	   <?php }
	  }
	$add_devTools_logo_field = new add_devTools_logo_field();
	$add_devTools_logo_field->init(); 
}

// Developed for Type Extra Fields
if (!class_exists('add_devFor_logo_field') ) {
	  class add_devFor_logo_field {
		public function __construct() {
		 //
		}
		/**
		 * Initialize the class and start calling our hooks and filters
		 */
		 public function init() {
		 // Image actions
		 add_action( 'developed-for_add_form_fields', array( $this, 'add_category_image' ), 10, 2 );
		 add_action( 'created_developed-for', array( $this, 'save_category_image' ), 10, 2 );
		 add_action( 'developed-for_edit_form_fields', array( $this, 'update_category_image' ), 10, 2 );
		 add_action( 'edited_developed-for', array( $this, 'updated_category_image' ), 10, 2 );
		 add_action( 'admin_enqueue_scripts', array( $this, 'load_media' ) );
		 add_action( 'admin_footer', array( $this, 'add_script' ) );
	   }
	   public function load_media() {
		 if( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != 'developed-for' ) {
		   return;
		 }
		 wp_enqueue_media();
	   }
	   /**
		* Add a form field in the new category page
		*/
	   public function add_category_image( $taxonomy ) { ?>
		 <div class="form-field term-group">
		   <label for="developed-for-image"><?php _e( 'Image', 'como-bands' ); ?></label>
		   <input type="hidden" id="developed-for-image" name="developed-for-image" class="custom_media_url" value="">
		   <div id="category-image-wrapper"></div>
		   <p>
			 <input type="button" class="button button-secondary developed_for_tax_media_button" id="developed_for_tax_media_button" name="developed_for_tax_media_button" value="<?php _e( 'Add Image', 'showcase' ); ?>" />
			 <input type="button" class="button button-secondary developed_for_tax_media_remove" id="developed_for_tax_media_remove" name="developed_for_tax_media_remove" value="<?php _e( 'Remove Image', 'showcase' ); ?>" />
		   </p>
		 </div>
	   <?php }
	   /**
		* Save the form field
		*/
	   public function save_category_image( $term_id, $tt_id ) {
		 if( isset( $_POST['developed-for-image'] ) && '' !== $_POST['developed-for-image'] ){
		   add_term_meta( $term_id, 'developed-for-image', absint( $_POST['developed-for-image'] ), true );
		 }
		}
		/**
		 * Edit the form field
		 */
		public function update_category_image( $term, $taxonomy ) { ?>
		  <tr class="form-field term-group-wrap">
			<th scope="row">
			  <label for="developed-for-image"><?php _e( 'Image', 'como-bands' ); ?></label>
			</th>
			<td>
			  <?php $image_id = get_term_meta( $term->term_id, 'developed-for-image', true ); ?>
			  <input type="hidden" id="developed-for-image" name="developed-for-image" value="<?php echo esc_attr( $image_id ); ?>">
			  <div id="category-image-wrapper">
				<?php if( $image_id ) { ?>
				  <?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
				<?php } ?>
			  </div>
			  <p>
				<input type="button" class="button button-secondary developed_for_tax_media_button" id="developed_for_tax_media_button" name="developed_for_tax_media_button" value="<?php _e( 'Add Image', 'showcase' ); ?>" />
				<input type="button" class="button button-secondary developed_for_tax_media_remove" id="developed_for_tax_media_remove" name="developed_for_tax_media_remove" value="<?php _e( 'Remove Image', 'showcase' ); ?>" />
			  </p>
			</td>
		  </tr>
	   <?php }
	   /**
		* Update the form field value
		*/
	   public function updated_category_image( $term_id, $tt_id ) {
		 if( isset( $_POST['developed-for-image'] ) && '' !== $_POST['developed-for-image'] ){
		   update_term_meta( $term_id, 'developed-for-image', absint( $_POST['developed-for-image'] ) );
		 } else {
		   update_term_meta( $term_id, 'developed-for-image', '' );
		 }
	   }
	   /**
		* Enqueue styles and scripts
		*/
	   public function add_script() {
		 if( ! isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != 'developed-for' ) {
		   return;
		 } ?>
		 <script> jQuery(document).ready( function($) {
		   _wpMediaViewsL10n.insertIntoPost = '<?php _e( "Insert", "como-bands" ); ?>';
		   function ct_media_upload(button_class) {
			 var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;
			 $('body').on('click', button_class, function(e) {
			   var button_id = '#'+$(this).attr('id');
			   var send_attachment_bkp = wp.media.editor.send.attachment;
			   var button = $(button_id);
			   _custom_media = true;
			   wp.media.editor.send.attachment = function(props, attachment){
				 if( _custom_media ) {
				   $('#developed-for-image').val(attachment.id);
				   $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
				   $('#category-image-wrapper .custom_media_image' ).attr( 'src',attachment.url ).css( 'display','block' );
				 } else {
				   return _orig_send_attachment.apply( button_id, [props, attachment] );
				 }
			   }
			   wp.media.editor.open(button); return false;
			 });
		   }
		   ct_media_upload('.developed_for_tax_media_button.button');
		   $('body').on('click','.developed_for_tax_media_remove',function(){
			 $('#developed-for-image').val('');
			 $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
		   });
			$(document).ajaxComplete(function(event, xhr, settings) {
			 var queryStringArr = settings.data.split('&');
			 if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
			   var xml = xhr.responseXML;
			   $response = $(xml).find('term_id').text();
			   if($response!=""){
				 // Clear the thumb image
				 $('#category-image-wrapper').html('');
			   }
			  }
			});
		  });
		</script>
	   <?php }
	  }
	$add_devFor_logo_field = new add_devFor_logo_field();
	$add_devFor_logo_field->init(); 
}

/* ##################### Portfolio Item Info Meta Box ##################### */

function comoportfolio_custom_meta() {
    add_meta_box('comoportfolio_meta', __('Additional Portfolio Item Info','como-textdomain'),'comoportfolio_meta_callback','portfolio','normal','high');
}
add_action( 'add_meta_boxes', 'comoportfolio_custom_meta' );

function comoportfolio_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'comoportfolio_nonce' );
    $comoportfolio_stored_meta = get_post_meta( $post->ID );
    ?>
 
	<p><label for="comoportfolio-website" class="comometa-row-title"><?php _e( 'Portfolio Website', 'como-textdomain' )?></label>
  	<span class="comometa-row-content"><input type="text" name="comoportfolio-website" id="comoportfolio-website" value="<?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-website'] ) ) echo $comoportfolio_stored_meta['comoportfolio-website'][0]; ?>" /></span></p>
    
    <p><label for="comoportfolio-featured" class="comometa-row-title"><?php _e( 'Featured', 'como-textdomain' )?></label>
    <span class="comometa-row-content"><input type="checkbox" name="comoportfolio-featured" id="comoportfolio-featured" value="yes" <?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-featured'] ) ) checked( $comoportfolio_stored_meta['comoportfolio-featured'][0], 'yes' ); ?> /> <?php _e( 'Feature this client on the Portfolio page', 'comoportfolio-featured' )?></span></p>
    
    <p><label for="comoportfolio-icon" class="comometa-row-title"><?php _e( 'Portfolio Item Logo', 'como-textdomain' )?></label>
    <span class="comometa-row-content upload-field"><?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-icon'] ) ) echo '<img src="'. $comoportfolio_stored_meta['comoportfolio-icon'][0] .'">'; ?><input type="text" name="comoportfolio-icon" id="comoportfolio-icon" value="<?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-icon'] ) ) echo $comoportfolio_stored_meta['comoportfolio-icon'][0]; ?>" /> <input type="button" id="comoportfolio-icon-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'como-textdomain' )?>" /></span></p>
    
    <p><label for="comoportfolio-icon-hover" class="comometa-row-title"><?php _e( 'Portfolio Logo Hover', 'como-textdomain' )?></label>
    <span class="comometa-row-content upload-field"><?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-icon-hover'] ) ) echo '<img src="'. $comoportfolio_stored_meta['comoportfolio-icon-hover'][0] .'">'; ?><input type="text" name="comoportfolio-icon-hover" id="comoportfolio-icon-hover" value="<?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-icon-hover'] ) ) echo $comoportfolio_stored_meta['comoportfolio-icon-hover'][0]; ?>" /> <input type="button" id="comoportfolio-icon-hover-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'como-textdomain' )?>" /></span></p>

	<p class="file-upload">
        <label for="comoportfolio-video-mp4" class="comometa-row-title"><?php _e( 'Portfolio Video MP4', 'como-textdomain' )?></label>
        <span class="comometa-row-content upload-field">
			<input type="text" name="comoportfolio-video-mp4" id="comoportfolio-video-mp4" class="como-upload-field" value="<?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-video-mp4'] ) ) echo $comoportfolio_stored_meta['comoportfolio-video-mp4'][0]; ?>" />
			<input type="hidden" name="comoportfolio-video-mp4-id" id="comoportfolio-video-mp4-id" class="como-upload-id-field" value="<?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-video-mp4-id'] ) ) echo $comoportfolio_stored_meta['comoportfolio-video-mp4-id'][0]; ?>" />
			
			<?php
				if (!empty($comoportfolio_stored_meta['comoportfolio-video-mp4'][0])) {
					$upload1class = 'hidden';
					$remove1class = ''; 
				} else {
					$upload1class = '';
					$remove1class = 'hidden';
				}
			?>
			<input type="button" class="remove-upload-button <?=$remove1class?>" value="<?php _e( 'Remove File', 'como-textdomain' )?>" />
			<input type="button" class="button meta-upload-button <?=$upload1class?>" value="<?php _e( 'Choose or Upload a File', 'como-textdomain' )?>" />
		</span>
    </p>

	<p class="file-upload">
        <label for="comoportfolio-video-webm" class="comometa-row-title"><?php _e( 'Portfolio Video WEBM', 'como-textdomain' )?></label>
        <span class="comometa-row-content upload-field">
			<input type="text" name="comoportfolio-video-webm" id="comoportfolio-video-webm" class="como-upload-field" value="<?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-video-webm'] ) ) echo $comoportfolio_stored_meta['comoportfolio-video-webm'][0]; ?>" />
			<input type="hidden" name="comoportfolio-video-webm-id" id="comoportfolio-video-webm-id" class="como-upload-id-field" value="<?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-video-webm-id'] ) ) echo $comoportfolio_stored_meta['comoportfolio-video-webm-id'][0]; ?>" />
			
			<?php
				if (!empty($comoportfolio_stored_meta['comoportfolio-video-webm'][0])) {
					$upload1class = 'hidden';
					$remove1class = ''; 
				} else {
					$upload1class = '';
					$remove1class = 'hidden';
				}
			?>
			<input type="button" class="remove-upload-button <?=$remove1class?>" value="<?php _e( 'Remove File', 'como-textdomain' )?>" />
			<input type="button" class="button meta-upload-button <?=$upload1class?>" value="<?php _e( 'Choose or Upload a File', 'como-textdomain' )?>" />
		</span>
    </p>

	<p class="file-upload">
        <label for="comoportfolio-video-ogv" class="comometa-row-title"><?php _e( 'Portfolio Video OVG/OGG', 'como-textdomain' )?></label>
        <span class="comometa-row-content upload-field">
			<input type="text" name="comoportfolio-video-ogv" id="comoportfolio-video-ogv" class="como-upload-field" value="<?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-video-ogv'] ) ) echo $comoportfolio_stored_meta['comoportfolio-video-ogv'][0]; ?>" />
			<input type="hidden" name="comoportfolio-video-ogv-id" id="comoportfolio-video-ogv-id" class="como-upload-id-field" value="<?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-video-ogv-id'] ) ) echo $comoportfolio_stored_meta['comoportfolio-video-ogv-id'][0]; ?>" />
			
			<?php
				if (!empty($comoportfolio_stored_meta['comoportfolio-video-webm'][0])) {
					$upload1class = 'hidden';
					$remove1class = ''; 
				} else {
					$upload1class = '';
					$remove1class = 'hidden';
				}
			?>
			<input type="button" class="remove-upload-button <?=$remove1class?>" value="<?php _e( 'Remove File', 'como-textdomain' )?>" />
			<input type="button" class="button meta-upload-button <?=$upload1class?>" value="<?php _e( 'Choose or Upload a File', 'como-textdomain' )?>" />
		</span>
    </p>

	<p><label for="comoportfolio-video-file-link" class="comometa-row-title"><?php _e( 'Video Link', 'como-docs' )?></label>
  	<span class="comometa-row-content"><input type="text" name="comoportfolio-video-file-link" id="comoportfolio-video-file-link" value="<?php if ( isset ( $comoportfolio_stored_meta['comoportfolio-video-file-link'] ) ) echo $comoportfolio_stored_meta['comoportfolio-video-file-link'][0]; ?>" /></span></p>
    
    <input type="hidden" name="comoupdate_flag" value="true" />
    
    <?php 
}

function comoport_init() {
	//wp_enqueue_media();
	add_action( 'admin_enqueue_scripts', 'comoportfolio_image_enqueue' );
	add_action( 'admin_enqueue_scripts', 'comoImgUpload_enqueue' );
	add_action( 'admin_enqueue_scripts', 'como_fileupload_engueue' );
}
add_action('admin_init','comoport_init', 1);

function como_fileupload_engueue() {
	global $typenow;
    if ($typenow == 'portfolio') {
		wp_enqueue_media();
		wp_register_script('meta-file-upload', plugin_dir_url( __FILE__ ) . '/js/document-upload.js', array('jquery'));
		wp_localize_script('meta-file-upload', 'meta_image',
			array(
				'title' => __( 'Choose or Upload a File', 'como-textdomain' ),
				'button' => __( 'Use this file', 'como-textdomain' ),
			)
		);
		wp_enqueue_script( 'meta-file-upload' );
	}
}

function comoportfolio_image_enqueue() {
    global $typenow;
    if( $typenow == 'portfolio' ) {
		wp_enqueue_media();
        // Registers script for logo upload
        wp_register_script( 'meta-comoportfolio-icon', plugin_dir_url( __FILE__ ) . 'js/meta-box-portfolio-icon.js', array( 'jquery' ) );
        wp_localize_script( 'meta-comoportfolio-icon', 'meta_image',
            array(
                'title' => __( 'Choose or Upload an Image', 'como-textdomain' ),
                'button' => __( 'Use this image', 'como-textdomain' ),
            )
        );
        wp_enqueue_script( 'meta-comoportfolio-icon' );
		
		// Registers script for logo hover upload
		wp_register_script( 'meta-comoportfolio-icon-hover', plugin_dir_url( __FILE__ ) . 'js/meta-box-portfolio-icon-hover.js', array( 'jquery' ) );
        wp_localize_script( 'meta-comoportfolio-icon-hover', 'meta_image',
            array(
                'title' => __( 'Choose or Upload an Image', 'como-textdomain' ),
                'button' => __( 'Use this image', 'como-textdomain' ),
            )
        );
        wp_enqueue_script( 'meta-comoportfolio-icon-hover' );
		
		// Registers script for Twitter Icon upload
		/*wp_register_script( 'meta-comoportfolio-twitter-icon', plugin_dir_url( __FILE__ ) . 'js/meta-box-portfolio-twitter-icon.js', array( 'jquery' ) );
        wp_localize_script( 'meta-comoportfolio-twitter-icon', 'meta_image',
            array(
                'title' => __( 'Choose or Upload an Image', 'como-textdomain' ),
                'button' => __( 'Use this image', 'como-textdomain' ),
            )
        );
        wp_enqueue_script( 'meta-comoportfolio-twitter-icon' );
		
		// Registers script for Facebook Icon upload
		wp_register_script( 'meta-comoportfolio-facebook-icon', plugin_dir_url( __FILE__ ) . 'js/meta-box-portfolio-facebook-icon.js', array( 'jquery' ) );
        wp_localize_script( 'meta-comoportfolio-facebook-icon', 'meta_image',
            array(
                'title' => __( 'Choose or Upload an Image', 'como-textdomain' ),
                'button' => __( 'Use this image', 'como-textdomain' ),
            )
        );
        wp_enqueue_script( 'meta-comoportfolio-facebook-icon' );*/
    }
}

// Register Social Toggle Scripts 
//wp_register_script( 'meta-comoportfolio-social-toggle', plugin_dir_url( __FILE__ ) . 'js/meta-box-portfolio-social-toggle.js', array( 'jquery' ) );
//wp_enqueue_script('meta-comoportfolio-social-toggle');

// Register Meta Fields for REST API
add_action( 'rest_api_init', 'register_portfolio_meta_fields');
function register_portfolio_meta_fields(){
	$metaVars = array(
		array('key'=>'comoportfolio-website','type'=>'string','desc'=>'Portfolio Website'),
		array('key'=>'comoportfolio-featured','type'=>'string','desc'=>'Portfolio Featured'),
		array('key'=>'comoportfolio-icon','type'=>'string','desc'=>'Portfolio Icon'),
		array('key'=>'comoportfolio-icon-hover','type'=>'string','desc'=>'Portfolio Icon Hover'),
		array('key'=>'comoportfolio-video-mp4','type'=>'string','desc'=>'Portfolio Video MP4'),
		array('key'=>'comoportfolio-video-mp4-id','type'=>'string','desc'=>'Portfolio Video MP4 ID'),
		array('key'=>'comoportfolio-video-webm','type'=>'string','desc'=>'Portfolio Video WEBM'),
		array('key'=>'comoportfolio-video-webm-id','type'=>'string','desc'=>'Portfolio Video WEBM ID'),
		array('key'=>'comoportfolio-video-ogv','type'=>'string','desc'=>'Portfolio Video OGV'),
		array('key'=>'comoportfolio-video-ogv-id','type'=>'string','desc'=>'Portfolio Video OVG ID'),
		array('key'=>'comoportfolio-video-file-link','type'=>'string','desc'=>'Portfolio Video File Link')
	);
	foreach ($metaVars as $var) {
		register_post_meta(
			'portfolio',
			$var['key'],
			array(
				'auth_callback' => '__return_true',
				'default'       => __( '', $var['key'] ),
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => $var['type'],
				'description'	=> $var['desc']
			)
		);
	}
}

// Allow REST API to sort by Featured
add_filter( 'rest_portfolio_query', 'filter_portfolio_by_featured', 999, 2 );
function filter_portfolio_by_featured( $args, $request ) {
	if ( ! isset( $request['comoportfolio-featured'] )  ) {
		return $args;
	}
	
	$source_value = sanitize_text_field( $request['comoportfolio-featured'] );
	$source_meta_query = array(
		'key' => 'comoportfolio-featured',
		'value' => $source_value
	);
	
	if ( isset( $args['meta_query'] ) ) {
		$args['meta_query']['relation'] = 'AND';
		$args['meta_query'][] = $source_meta_query;
	} else {
		$args['meta_query'] = array();
		$args['meta_query'][] = $source_meta_query;
	}
	
	return $args;
}

// Add Order By to REST API
add_filter( 'rest_post_collection_params', 'comoportfolio_add_rest_orderby_params', 10, 1 );
function comoportfolio_add_rest_orderby_params( $params ) {
    $params['orderby']['enum'][] = 'menu_order';
    return $params;
}

// Saves the Portfolio Item Info Section meta input
function comoportfolio_meta_save( $post_id ) {
	
	// Only do this if our custom flag is present
    if (isset($_POST['comoupdate_flag'])) {
		
		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'comoportfolio_nonce' ] ) && wp_verify_nonce( $_POST[ 'comoportfolio_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}

		// Specify Meta Variables to be Updated
		$metaVars = array('comoportfolio-website','comoportfolio-featured','comoportfolio-icon','comoportfolio-icon-hover', 'comoportfolio-video-mp4', 'comoportfolio-video-mp4-id', 'comoportfolio-video-webm', 'comoportfolio-video-webm-id', 'comoportfolio-video-ogv', 'comoportfolio-video-ogv-id', 'comoportfolio-video-file-link');
		$checkboxVars = array('comoportfolio-featured');
		
		// Update Meta Variables
		foreach ($metaVars as $var) {
			if (in_array($var,$checkboxVars)) {
				//if (isset($_POST[$var])) {
				if (array_key_exists($var,$_POST)) {
					update_post_meta($post_id, $var, 'yes');
				} else {
					update_post_meta($post_id, $var, '');
				}
			} else {
				//if(isset($_POST[$var])) {
				if (array_key_exists($var,$_POST)) {
					update_post_meta($post_id, $var, $_POST[$var]);
				} else {
					update_post_meta($post_id, $var, '');
				}
			}
		}
	}
}
add_action( 'save_post', 'comoportfolio_meta_save' );

// Adds the meta box stylesheet when appropriate 
function portfolios_admin_styles(){
    global $typenow;
    if($typenow == 'portfolio') {
		wp_enqueue_style('portfolio_meta_box_styles', plugin_dir_url( __FILE__ ) .'css/admin.min.css');
    }
}
add_action('admin_print_styles', 'portfolios_admin_styles');


/* ############## Secondary Image Meta Box ###################### */

// Include image upload script
function comoImgUpload_enqueue() {
    global $typenow;
    if( $typenow == 'portfolio' ) {
		wp_enqueue_media();
        // Registers and enqueues the required javascript.
        wp_register_script( 'como-img-upload', plugin_dir_url( __FILE__ ) . '/js/image-upload.js', array( 'jquery' ) );
        wp_localize_script( 'como-img-upload', 'meta_image',
            array(
                'title' => 'Choose or Upload an Image',
                'button' => 'Use this image',
            )
        );
        wp_enqueue_script( 'como-img-upload' );
   }
}

// Create Secondary Portfolio image metabox
function comoSecondayrPortfolioImg( $post ) {
    wp_nonce_field( 'comoSecondayrPortfolioImg_submit', 'comoSecondayrPortfolioImg_nonce' );
    $comoSecondayrPortfolioImg_stored_meta = get_post_meta($post->ID); 
	
	global $post;
	
	// Get WordPress' media upload URL
	$upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );
	
	// See if there's a media id already saved as post meta
	$comoPortfolio_secondaryImg_id = ((isset($comoSecondayrPortfolioImg_stored_meta['comoportfolio-secondary-img'][0])) ? $comoSecondayrPortfolioImg_stored_meta['comoportfolio-secondary-img'][0] : '');
	
	// Get the image src
	$comoPortfolio_secondaryImg_src = (($comoPortfolio_secondaryImg_id) ? wp_get_attachment_image_src( $comoPortfolio_secondaryImg_id, 'full') : '');
	
	// For convenience, see if the array is valid
	$have_comoPortfolio_secondary_img = is_array( $comoPortfolio_secondaryImg_src );
	?>
	<div class="image-upload">
		<!-- Your image container, which can be manipulated with js -->
		<div class="custom-img-container">
			<?php if ( $have_comoPortfolio_secondary_img ) : ?>
				<img src="<?=$comoPortfolio_secondaryImg_src[0]?>" alt="" style="max-width:100%;" />
			<?php endif; ?>
		</div>

		<!-- Your add & remove image links -->
		<p class="hide-if-no-js">
			<a class="upload-img <?php if ( $have_comoPortfolio_secondary_img  ) { echo 'hidden'; } ?>" 
			   href="<?=$upload_link?>">
				<?php _e('Set Secondary Portfolio image') ?>
			</a>
			<a class="delete-img <?php if ( ! $have_comoPortfolio_secondary_img  ) { echo 'hidden'; } ?>" 
			  href="#">
				<?php _e('Remove this image') ?>
			</a>
		</p>
		<!-- A hidden input to set and post the chosen image id -->
		<input class="image-id-hidden" id="comoportfolio-secondary-img" name="comoportfolio-secondary-img" type="hidden" value="<?=esc_attr($comoPortfolio_secondaryImg_id)?>" />
		<input type="hidden" name="comoImgupdate_flag" value="true" />
	</div>
<?php    
}

// Add Secondary Profile image metabox to the back end of Custom Header posts 
function comoSecondayrPortfolioImg_metabox() {
    add_meta_box( 'comoSecondayrPortfolioImg', 'Secondary Profile Image', 'comoSecondayrPortfolioImg', 'portfolio', 'side', 'low' );
}
add_action( 'add_meta_boxes', 'comoSecondayrPortfolioImg_metabox', 1);

// Save Secondary Profile image 
function save_comoSecondayrPortfolioImg( $post_id ) {
	// Only do this if our custom flag is present
    if (isset($_POST['comoImgupdate_flag'])) {
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'comoSecondayrPortfolioImg_nonce' ] ) && wp_verify_nonce( $_POST[ 'comoSecondayrPortfolioImg_nonce' ], 'comoSecondayrPortfolioImg_submit' ) ) ? 'true' : 'false';
		// Exits script depending on save status
		if ($is_autosave || $is_revision || !$is_valid_nonce) {
			return;
		}
		// Checks for input and sanitizes/saves if needed
		if (isset($_POST['comoportfolio-secondary-img'])) {
			update_post_meta( $post_id, 'comoportfolio-secondary-img', $_POST[ 'comoportfolio-secondary-img' ] );
		}
	}
}
add_action( 'save_post', 'save_comoSecondayrPortfolioImg' );

// Custom Image Sizes
add_action( 'after_setup_theme', 'comoportfolio_img_sizes' );
function comoportfolio_img_sizes() {
	add_image_size( 'portfolio-logo', 350); // (not-cropped)
}

/* ##################### Shortcode to Show Portfolio ##################### */

// Get Meta item ID by Meta Key
if (!function_exists('get_mid_by_key')) {
	function get_mid_by_key( $post_id, $meta_key ) {
		global $wpdb;
		$mid = $wpdb->get_var( $wpdb->prepare("SELECT meta_id FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s", $post_id, $meta_key) );
		if( $mid != '' )
			return (int)$mid;
		return false;
	}
}

// Get an attachment ID given a URL 
if (!function_exists('get_attachment_id')) {
	function get_attachment_id( $url ) {
		$attachment_id = 0;
		if ($url) {
			$dir = wp_upload_dir();
			if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
				$file = basename( $url );
				$query_args = array(
					'post_type'   => 'attachment',
					'post_status' => 'inherit',
					'fields'      => 'ids',
					'meta_query'  => array(
						array(
							'value'   => $file,
							'compare' => 'LIKE',
							'key'     => '_wp_attachment_metadata',
						),
					)
				);
				$query = new WP_Query( $query_args );
				if ( $query->have_posts() ) {
					foreach ( $query->posts as $post_id ) {
						$meta = wp_get_attachment_metadata( $post_id );
						$original_file       = basename( $meta['file'] );
						$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
						if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
							$attachment_id = $post_id;
							break;
						}
					}
				}
			}
		}
		return $attachment_id;
	}
}

// Usage: [comoportfolio featured=TRUE/FALSE template=TEMPLATE NAME twitter=TWITTER TEMPLATE facebook="FACEBOOK TEMPLATE orderby=DATE/TITLE/MENU_ORDER order=ASC/DESC]
class ComoFeaturedPortfolio_Shortcode {
	static $add_script;
	static $add_style;
	static function init() {
		add_shortcode('comoportfolio', array(__CLASS__, 'handle_shortcode'));
		//add_action('init', array(__CLASS__, 'register_script'));
		//add_action('wp_footer', array(__CLASS__, 'print_script'));
	}
	
	static function handle_shortcode($atts) {
		self::$add_style = true;
		self::$add_script = true;
		
		$portfolio_featured = (isset($atts['featured']) ? $atts['featured'] : 'all');
		$portfolio_template = (isset($atts['template']) ? $atts['template'] : 'default');
		$limit = (isset($atts['limit']) ? $atts['limit'] : -1);
		$orderby = (isset($atts['orderby']) ? $atts['orderby'] : 'menu_order');
		$order = (isset($atts['order']) ? $atts['order'] : 'ASC');
		
		if (($portfolio_featured == 'featured') || ($portfolio_featured == 'true')) {
			$args = array('post_type'=>'portfolio','post_status'=>'publish','meta_query'=>array(array('key'=>'comoportfolio-featured','value'=>'yes')),'posts_per_page'=>-1,'orderby'=>$orderby,'order'=>$order,'posts_per_page'=>$limit);
		//} elseif ($member_type) { $args['tax_query'] = array(array('taxonomy'=>'member-type','field'=>'slug','terms'=>$member_type)); 
		} else {
			$args = array('post_type'=>'portfolio','post_status'=>'publish','posts_per_page'=>-1,'orderby'=>$orderby,'order'=>$order,'posts_per_page'=>$limit);
		}
		$query = new WP_Query( $args );
		
		if ($query->have_posts()) { 
			unset($portfolio_array);
			while ($query->have_posts()) {
				$query->the_post(); 
				unset($port);
				$port['id'] = get_the_ID();
				$meta = get_post_meta($port['id']);
									  
				$port['title'] = get_the_title($port['id']);
				$port['date'] = get_the_date('Y-m-d', $port['id']);
				$port['website'] = (($meta['comoportfolio-website']) ? $meta['comoportfolio-website'][0] : '');
				$port['featured'] = (($meta['comoportfolio-featured']) ? $meta['comoportfolio-featured'][0] : '');
				$port['image'] = get_post_thumbnail_id($port['id']);
				$port['secondary-image'] = (($meta['comoportfolio-secondary-img']) ? $meta['comoportfolio-secondary-img'][0] : '');
				
				// Logo Normal
				$port['icon-link'] = (($meta['comoportfolio-icon']) ? $meta['comoportfolio-icon'][0] : '');
				$iconMetaID = get_attachment_id($port['icon-link']);
				$iconMeta = wp_get_attachment_metadata($iconMetaID);
				$port['icon'] = $iconMeta;
				$port['icon-alt'] = ((isset($meta['_wp_attachment_image_alt'])) ? $meta['_wp_attachment_image_alt'][0] : '');
				
				// Logo Hover
				$port['icon-hover-link'] = (($meta['comoportfolio-icon-hover']) ? $meta['comoportfolio-icon-hover'][0] : '');
				$iconHoverMetaID = get_attachment_id($port['icon-hover-link']);
				$iconHoverMeta = wp_get_attachment_metadata($iconHoverMetaID);
				$port['icon-hover'] = $iconHoverMeta;
				$port['icon-hover-alt'] = ((isset($port['icon-hover'])) ? get_post_meta($iconHoverMetaID,'_wp_attachment_image_alt',true) : '');
				
				// Video
				$port['video-mp4'] = ((isset($meta['comoportfolio-video-mp4'])) ? $meta['comoportfolio-video-mp4'][0] : '');
				$port['video-ogv'] = ((isset($meta['comoportfolio-video-ovg'])) ? $meta['comoportfolio-video-ogv'][0] : '');
				$port['video-webm'] = ((isset($meta['comoportfolio-video-webm'])) ? $meta['comoportfolio-video-webm'][0] : '');
				$port['video-link'] = ((isset($meta['comoportfolio-video-file-link'])) ? $meta['comoportfolio-video-file-link'][0] : '');
				$port['has-video'] = (($port['video-mp4'] || $port['video-ogv'] || $port['video-webm'] || $port['video-link']) ? true : false); 
				
				$port['link'] = get_permalink();
				$port['excerpt'] = wpautop(get_the_excerpt());
				$port['content'] = get_the_content();
				$portfolio_array[] = $port;
			}
			
			if ($portfolio_template) {
				$temp = ''; 
				$temp = (is_child_theme() ? get_stylesheet_directory() : get_template_directory() ) . '/como-portfolio/'. $portfolio_template .'.php';
				include((file_exists($temp)) ? $temp : plugin_dir_path( __FILE__ ) .'templates/default.php');
			} else {
				include(plugin_dir_path( __FILE__ ) .'templates/default.php');
			}
			$comoportfolio = $portDisplay;
		}
		if ($comoportfolio) { echo $comoportfolio; }
	}
	
	// Register & Print Scripts
	/*static function register_script() {
		wp_register_script('comoportfolios_script', plugins_url('js/comoportfolios.js', __FILE__), array('jquery'), '1.0', true);
	}
	static function print_script() {
		if ( ! self::$add_script )
			return;
		wp_print_scripts('comoportfolios_script');
	}*/
}
ComoFeaturedPortfolio_Shortcode::init();