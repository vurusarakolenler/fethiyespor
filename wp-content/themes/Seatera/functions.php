<?php
/**
 * Seatera functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 */

// Define file directories
define('VH_HOME', get_template_directory());
define('VH_FUNCTIONS', get_template_directory() . '/functions');
define('VH_GLOBAL', get_template_directory() . '/functions/global');
define('VH_WIDGETS', get_template_directory() . '/functions/widgets');
define('VH_CUSTOM_PLUGINS', get_template_directory() . '/functions/plugins');
define('VH_ADMIN', get_template_directory() . '/functions/admin');
define('VH_ADMIN_IMAGES', get_template_directory_uri() . '/functions/admin/images');
define('VH_METABOXES', get_template_directory() . '/functions/admin/metaboxes');
define('VH_SIDEBARS', get_template_directory() . '/functions/admin/sidebars');

// Define theme URI
define('VH_URI', get_template_directory_uri() .'/');
define('VH_GLOBAL_URI', VH_URI . 'functions/global');

define('THEMENAME', 'Seatera');
define('SHORTNAME', 'VH');

define('TESTENVIRONMENT', FALSE);

add_action('after_setup_theme', 'vh_setup');
add_filter('widget_text', 'do_shortcode');

// Set max content width
if (!isset($content_width)) {
	$content_width = 900;
}

if (!function_exists('vh_setup')) {

	function vh_setup() {

		// Load Admin elements
		require_once(VH_ADMIN . '/theme-options.php');
		require_once(VH_ADMIN . '/admin-interface.php');
		require_once(VH_ADMIN . '/menu-custom-field.php');
		require_once(VH_FUNCTIONS . '/get-the-image.php');
		require_once(VH_METABOXES . '/layouts.php');
		require_once(VH_METABOXES . '/contact_map.php');
		require_once(VH_SIDEBARS . '/multiple_sidebars.php');

		// Widgets list
		$widgets = array (
			VH_WIDGETS . '/contactform.php',
			VH_WIDGETS . '/googlemap.php',
			VH_WIDGETS . '/social_links.php',
			VH_WIDGETS . '/advertisement.php',
			VH_WIDGETS . '/recent-posts-plus.php',
			VH_WIDGETS . '/fast-flickr-widget.php',
		);

		// Load Widgets
		load_files($widgets);

		// Load global elements
		require_once(VH_GLOBAL . '/wp_pagenavi/wp-pagenavi.php');

		// TGM plugins activation
		require_once(VH_FUNCTIONS . '/tgm-activation/class-tgm-plugin-activation.php');

		register_taxonomy( 'event_categories',
			array (
				0 => 'movies',
			),
			array( 
				'hierarchical' => true, 
				'label' => 'Event Categories',
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array('slug' => ''),
				'singular_label' => 'Event Category'
			) 
		);

		register_taxonomy( 'offers_categories',
			array (
				0 => 'offers',
			),
			array( 
				'hierarchical' => true, 
				'label' => 'Offer Categories',
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array('slug' => ''),
				'singular_label' => 'Offer Category'
			) 
		); 

		// Extend Visual Composer
		if (defined('WPB_VC_VERSION')) {
			require_once(VH_FUNCTIONS . '/visual_composer_extended.php');
		}

		// Shortcodes list
		$shortcodes = array (
			//VH_SHORTCODES . '/test.php'
		);

		// Load shortcodes
		load_files($shortcodes);

		// This theme styles the visual editor with editor-style.css to match the theme style.
		add_editor_style();

		// Add default posts and comments RSS feed links to <head>.
		add_theme_support('automatic-feed-links');

		// If theme is activated them send to options page
		if (is_admin() && isset($_GET['activated'])) {
			wp_redirect(admin_url('admin.php?page=themeoptions'));
		}

		
	}
}


// Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
if(function_exists('vc_set_as_theme')) vc_set_as_theme();

// Add quote post format support
add_theme_support( 'post-formats', array( 'quote' ) );

// Load Widgets
function load_files ($files) {
	foreach ($files as $file) {
		require_once($file);
	}
}

if (function_exists('add_theme_support')) {
	add_theme_support('post-thumbnails');

	// Default Post Thumbnail dimensions
	set_post_thumbnail_size(150, 150);
}

function the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '...';
	} else {
		echo $excerpt;
	}
}

add_action( 'init', 'vh_create_post_type' );
function vh_create_post_type() {
	register_post_type( 'movies',
		array(
		'labels' => array(
			'name' => __( 'Movies', "vh" ),
			'singular_name' => __( 'Movie', "vh" )
		),
		'taxonomies' => array('event_categories'),
		'rewrite' => array('slug'=>'movies','with_front'=>false),
		'public' => true,
		'has_archive' => true,
		'supports' => array(
			'title',
			'editor',
			'revisions',
			'thumbnail',
			'comments',
			'post-templates'
			)
		)
	);
	register_post_type( 'special_offers',
	array(
	'labels' => array(
		'name' => __( 'Offers', "vh" ),
		'singular_name' => __( 'Offer', "vh" )
	),
	'taxonomies' => array('offers_categories'),
	'rewrite' => array('slug'=>'offers','with_front'=>false),
	'public' => true,
	'has_archive' => true,
	'supports' => array(
		'title',
		'editor',
		'revisions',
		'thumbnail',
		'comments',
		'post-templates'
		)
	)
);
}

function vh_test_f() {
	$offers_categorie = get_terms('offers_categories');
	$offer_categories = array();
	foreach ($offers_categorie as $value) {
		$offer_categories[$value->term_taxonomy_id] = $value->slug;
	}
	return $offer_categories;
}
add_action('wp_loaded','vh_test_f');

function comment_count( $count ) {
	if ( ! is_admin() ) {
		global $id;

		$comments = get_comments('status=approve&post_id=' . $id);
		$separate_comments = separate_comments($comments);

		$comments_by_type = &$separate_comments;
		return count($comments_by_type['comment']);
	} else {
		return $count;
	}
}
add_filter('get_comments_number', 'comment_count', 0);

function tgm_cpt_search( $query ) {
	if ( $query->is_search )
		$query->set( 'post_type', array( 'page', 'post', 'movies' ) );
	return $query;
}
add_filter( 'pre_get_posts', 'tgm_cpt_search' );

// Add new image sizes
if ( function_exists('add_image_size')) {
	add_image_size('gallery-small', 90, 90, true); // gallery-small gallery size
	add_image_size('app-image-large', 900, 310, true); // app-image-large image size
	add_image_size('staff-image', 520, 346, true); // staff-image image size
	add_image_size('large-image', 1200, 525, true); // large-image image size
	add_image_size('thank-you-image', 250, 380, true); // thank you page image
	add_image_size('spotlight', 340, 520, true); // spotlight image size
	add_image_size('in_theatres', 200, 310, true); // showing in theatres image size
	add_image_size('offer', 540, 320, true); // offer image size
	add_image_size('movie_list', 500, 760, true); // movie list image size

	# Gallery image Cropped sizes
	add_image_size('gallery-large', 270, 270, true); // gallery-large gallery size
	add_image_size('gallery-medium', 125, 125, true); // gallery-medium gallery size
}

// Public JS scripts
if (!function_exists('vh_scripts_method')) {
	function vh_scripts_method() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('master', get_template_directory_uri() . '/js/master.js', array('jquery'), '', TRUE);
		wp_enqueue_script('isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array('jquery', 'master'), '', TRUE);
		wp_enqueue_script('jquery-ui-tabs');
		
		wp_enqueue_script('google-maps', '//maps.googleapis.com/maps/api/js?sensor=false', array(), '3', false);
		wp_enqueue_script('jquery.pushy', get_stylesheet_directory_uri() . '/js/nav/pushy.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.mousewheel', get_stylesheet_directory_uri() . '/js/jquery.mousewheel.min.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.jcarousel', get_stylesheet_directory_uri() . '/js/jquery.jcarousel.pack.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.classie', get_stylesheet_directory_uri() . '/js/classie.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.kinetic', get_stylesheet_directory_uri() . '/js/smoothscroll/jquery.kinetic.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.smoothdivscroll', get_stylesheet_directory_uri() . '/js/smoothscroll/jquery.smoothDivScroll-1.3.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.snap.svg-min', get_stylesheet_directory_uri() . '/js/snap.svg-min.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.svgLoader', get_stylesheet_directory_uri() . '/js/svgLoader.js', array('jquery'), '', TRUE);

		wp_enqueue_script( 'jquery-ui-dialog' );

		wp_enqueue_script( 'jquery-ui-datepicker' );

		wp_enqueue_script('jquery.cookie', get_stylesheet_directory_uri() . '/js/jquery.cookie.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.dots', get_stylesheet_directory_uri() . '/js/dots.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.debouncedresize', get_stylesheet_directory_uri() . '/js/jquery.debouncedresize.js', array('jquery'), '', TRUE);

		wp_enqueue_script('jquery.dotdotdot', get_stylesheet_directory_uri() . '/js/jquery.dotdotdot.min.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.dropdown', get_stylesheet_directory_uri() . '/js/modernizr.custom.79639.js', array('jquery'), '', TRUE);
		wp_enqueue_script('jquery.easing', get_stylesheet_directory_uri() . '/js/jquery.easing.1.3.min.js', array('jquery'), '', TRUE);

	
		if ( is_singular() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		wp_localize_script( 'master', 'ajax_login_object', array( 
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'redirecturl'    => home_url(),
			'loadingmessage' => __('Sending user info, please wait...', "vh" )
		));
		wp_localize_script( 'master', 'my_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		if ( get_option('vh_date_format') == false ) {
			$date_format = 'mm.dd.yy';
		} else {
			$date_format = get_option('vh_date_format');
		}

		if ( get_option('vh_date_separator') == false ) {
			$date_separator = '.';
		} else {
			$date_separator = get_option('vh_date_separator');
		}

		if ( get_option('vh_upcomming_time') == false ) {
			$upcomming_time = '';
		} else {
			$upcomming_time = get_option('vh_upcomming_time');
		}

		if ( get_option('vh_time_format') == false ) {
			$time_format = '24h';
		} else {
			$time_format = get_option('vh_time_format');
		}

		wp_localize_script( 'master', 'date_format', array( 
			'date_format' => $date_format,
			'date_separator' => $date_separator,
			'upcomming_time' => $upcomming_time,
			'time_format' => $time_format
		));
	}
}
add_action('wp_enqueue_scripts', 'vh_scripts_method');

// Public CSS files
if (!function_exists('vh_style_method')) {
	function vh_style_method() {
		wp_enqueue_style('master-css', get_stylesheet_directory_uri() . '/style.css');
		wp_enqueue_style('vh-normalize', get_stylesheet_directory_uri() . '/css/normalize.css');

		wp_enqueue_style('js_composer_front');
		wp_enqueue_style('prettyphoto');

		wp_enqueue_style('vh-responsive', get_stylesheet_directory_uri() . '/css/responsive.css');
		wp_enqueue_style('pushy', get_stylesheet_directory_uri() . '/css/nav/pushy.css');

		wp_enqueue_style('component', get_stylesheet_directory_uri() . '/css/component.css');

		wp_enqueue_style('jquery-ui-dialog', get_stylesheet_directory_uri() . '/css/jquery-ui-1.10.4.custom.min.css');

		// Load google fonts
		if (file_exists(TEMPLATEPATH . '/css/gfonts.css')) {
			wp_enqueue_style('front-gfonts', get_template_directory_uri() . '/css/gfonts.css');
		}
	}
}
add_action('wp_enqueue_scripts', 'vh_style_method');

function ajax_login() {

	// First check the nonce, if it fails the function will break
	check_ajax_referer( 'ajax-login-nonce', 'security' );

	// Nonce is checked, get the POST data and sign user on
	$info                  = array();
	$info['user_login']    = $_POST['username'];
	$info['user_password'] = $_POST['password'];
	$info['remember']      = true;

	$user_signon = wp_signon( $info, false );
	if ( is_wp_error($user_signon) ){
		echo json_encode(array('loggedin' => false, 'message' => __('Wrong username or password.', "vh" )));
	} else {
		echo json_encode(array('loggedin' => true, 'message' => __('Login successful, redirecting...', "vh" )));
	}

	die();
}

// Enable the user with no privileges to run ajax_login() in AJAX
add_action( 'wp_ajax_nopriv_ajaxlogin', 'ajax_login' );

function localization() {
	$lang = get_template_directory() . '/languages';
	load_theme_textdomain('vh', $lang);
}
add_action('after_setup_theme', 'localization');

/* Filter categories */
function filter_categories($list) {

	$find    = '(';
	$replace = '[';
	$list    = str_replace( $find, $replace, $list );
	$find    = ')';
	$replace = ']';
	$list    = str_replace( $find, $replace, $list );

	return $list;
}
add_filter('wp_list_categories', 'filter_categories');

if (file_exists(VH_ADMIN . '/featured-images/featured-images.php')) {
	require_once(VH_ADMIN . '/featured-images/featured-images.php');
}

if( class_exists( 'vhFeaturedImages' ) ) {
		$args = array(
			'id'        => 'event-poster',
			'post_type' => 'movies', // Set this to post or page
			'labels'    => array(
				'name'   => 'Event poster',
				'set'    => 'Set event poster',
				'remove' => 'Remove event poster',
				'use'    => 'Use as event poster',
			)
		);

		new vhFeaturedImages( $args );
}

// Custom Login Logo
function vh_login_logo() {
	$login_logo = get_option('vh_login_logo');

	if ($login_logo != false) {
		echo '
	<style type="text/css">
		#login h1 a { background-image: url("' . $login_logo . '") !important; }
	</style>';
	}
}
add_action('login_head', 'vh_login_logo');

add_action( 'wp_ajax_nopriv_load-filter', 'prefix_load_cat_posts' );
add_action( 'wp_ajax_load-filter', 'prefix_load_cat_posts' );
function prefix_load_cat_posts() {
	$cat_id = sanitize_text_field($_POST['cat']);
	$theatre = sanitize_text_field($_POST['theatres']);
	$post_date = sanitize_text_field($_POST['date']);
	$time = sanitize_text_field($_POST['time']);
	$limit = sanitize_text_field($_POST['event_limit']);
	$show_all = sanitize_text_field($_POST['show_all_events']);
	$initial_load = sanitize_text_field($_POST['initial_load']);
	$output = '';
	global $post;

	if ( $post_date == '' || $post_date == 'NaN.NaN.NaN' ) {
		$post_date = date('m.d.Y');
	}

	$current_date = explode(".", $post_date);

	if ( $limit == '' ) {
		$limit = -1;
	}

	if ( $show_all == '1' ) {
		$args = array(
			'numberposts' => -1,
			'post_type' => 'movies',
			'event_categories' => $cat_id,
			'posts_per_page' => $limit
			);
	} else {
		$args = array(
			'numberposts' => -1,
			'post_type' => 'movies',
			'event_categories' => $cat_id,
			'meta_query' => array(
					array(
						'key' => 'movie_time_values',
						'value' => $post_date,
						'compare' => 'LIKE'
					)
				),
			'posts_per_page' => $limit
			);
	}

	$the_query = new WP_Query( $args );



	if( $the_query->have_posts() ) {
		$output .= '<ul>';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			try {
				$dt = new DateTime('',new DateTimeZone(get_option('timezone_string')));
			} catch(Exception $e) {
				if ( strlen($e->getMessage()) > 1 ) {
					$dt = new DateTime('',new DateTimeZone('UTC'));
				}
			}

			if ( get_option('vh_time_format') == false || get_option('vh_time_format') == '24h' ) {
				$now = strtotime($dt->format('H:00'));
				$now = date('H:00',$now);
			} else {
				$now = strtotime($dt->format('h:00 a'));
				$now = date('h:00 a',$now);
			}

			$values = trim(get_post_meta( get_post()->ID, 'movie_time_values', true ),',');
			$values_arr = json_decode('{"events":['.$values.']}',true);

			foreach ( $values_arr as $value ) {
				foreach ( $value as $info ) {
					$movie_date = explode(".", $info['date']);
					if ( $post_date != '' && (( strtotime($movie_date["2"]."-".$movie_date["0"]."-".$movie_date["1"]) == strtotime($current_date["2"]."-".$current_date["0"]."-".$current_date["1"]) && $show_all != '1' ) || ( strtotime($movie_date["2"]."-".$movie_date["0"]."-".$movie_date["1"]) >= strtotime($current_date["2"]."-".$current_date["0"]."-".$current_date["1"]) && $show_all == '1' )) ) {
						if ( $theatre == '' ) {
							$info_time = explode(';', $info['time']);
							foreach ( $info_time as $info_time_value ) {
								if ( ( $time != '' && strtotime($info_time_value) >= strtotime($time) && $show_all != '1' ) || $show_all == '1' ) {
									$output .= '<li class="vc_span4" id="post-'. get_the_ID() .'">';
									$output .= '<div class="movie_image shadows">';
										$output .= '<div class="bottom_line"></div>';
										if ( kd_mfi_get_featured_image_id( 'event-poster', 'movies' ) != '' ) {
											$attachment_id = kd_mfi_get_featured_image_id( 'event-poster', 'movies' );
											$image = wp_get_attachment_image_src( $attachment_id, 'in_theatres' );
											$output .= '<a href="' . get_permalink( get_the_ID() ) . '"><img src="'. $image[0] .'" width="100" height="155"></a>';
										} elseif ( has_post_thumbnail( get_post()->ID ) ) {
											$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_post()->ID ), 'in_theatres' );
											$output .= '<a href="' . get_permalink( get_the_ID() ) . '"><img src="'. $image[0] .'" width="100" height="155"></a>';
										}
										$output .= '</div>';
										$output .= '<div class="movie_container">';
											$output .= '<div class="inner_top_container">';
												$output .= '<div class="movie_title"><a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></div>';
														if(function_exists('the_ratings')) {
															$rating_images = substr_replace(the_ratings_results($post->ID), '', strlen(the_ratings_results($post->ID))-5);
															$rating_value = number_format(floatval(substr(the_ratings_results(get_the_ID()), strlen(the_ratings_results(get_the_ID()))-5)), 1);
															if ( $rating_value < 10 ) {
																$rating_value = ' ' . $rating_value;
															}
															$output .= '<div class="event_list_rating">' . $rating_images . $rating_value . '</div>';
														}
												
											$output .= '</div>';
											$output .= '<div class="inner_bottom_container">';
												$output .= '<div class="movie_time icon-clock">';
													$times_count = 1;
													$times_new_array = array();
													foreach ( $info_time as $info_time_values ) {
														if ( ( strtotime($info_time_values) >= strtotime($now) && $show_all != '1' ) || $show_all == '1' ) {
															$times_new_array[] .= $info_time_values;
														}
													}
													foreach ( $times_new_array as $times_new_array_value ) {
														if ( count($times_new_array) == 1 || $times_count == count($times_new_array) ) {
															
															
															if ( get_option('vh_time_format') == false || get_option('vh_time_format') == '24h' ) {
																$output .= $times_new_array_value.' ';
															} else {
																$output .= date('h:i a', strtotime($times_new_array_value)).' ';
															}
														} else {
															if ( get_option('vh_time_format') == false || get_option('vh_time_format') == '24h' ) {
																$output .= $times_new_array_value.'; ';
															} else {
																$output .= date('h:i a', strtotime($times_new_array_value)).'; ';
															}
														}
														$times_count++;
													}
													$output .= '</div>';
												$output .= '<div class="movie_cinema">';
													$output .= $info['theatre'];
												$output .= '</div>';
												$output .= '<div class="movie_length">'. vh_convertToHoursMins(get_post_meta( get_the_ID(), 'movies_length', true ), __('%2d hours %2d minutes', "vh" )) .'</div>';
											$output .= '</div>';
										$output .= '</div>';
									$output .= '</li>';
									break;
								}
							}
						} elseif ( $info['theatre'] == $theatre ) {
							$info_time = explode(';', $info['time']);
							foreach ( $info_time as $info_time_value ) {
								if ( ( $time != '' && strtotime($info_time_value) >= strtotime($time) && $show_all != '1' ) || $show_all == '1' ) {
									$output .= '<li class="vc_span4" id="post-'. get_the_ID() .'">';
									$output .= '<div class="movie_image shadows">';
										$output .= '<div class="bottom_line"></div>';
										if ( kd_mfi_get_featured_image_id( 'event-poster', 'movies' ) != '' ) {
											$attachment_id = kd_mfi_get_featured_image_id( 'event-poster', 'movies' );
											$image = wp_get_attachment_image_src( $attachment_id, 'in_theatres' );
											$output .= '<a href="' . get_permalink( get_the_ID() ) . '"><img src="'. $image[0] .'" width="100" height="155"></a>';
										} elseif ( has_post_thumbnail( get_post()->ID ) ) {
											$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_post()->ID ), 'in_theatres' );
											$output .= '<a href="' . get_permalink( get_the_ID() ) . '"><img src="'. $image[0] .'" width="100" height="155"></a>';
										}
										$output .= '</div>';
										$output .= '<div class="movie_container">';
											$output .= '<div class="inner_top_container">';
												$output .= '<div class="movie_title"><a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></div>';
													if(function_exists('the_ratings')) {
															$rating_images = substr_replace(the_ratings_results($post->ID), '', strlen(the_ratings_results($post->ID))-5);
															$rating_value = number_format(floatval(substr(the_ratings_results(get_the_ID()), strlen(the_ratings_results(get_the_ID()))-5)), 1);
															if ( $rating_value < 10 ) {
																$rating_value = ' ' . $rating_value;
															}
															$output .= '<div class="event_list_rating">' . $rating_images . $rating_value . '</div>';
														}
											$output .= '</div>';
											$output .= '<div class="inner_bottom_container">';
													$output .= '<div class="movie_time icon-clock">';
													$times_count = 1;
													$times_new_array = array();
													foreach ( $info_time as $info_time_values ) {
														if ( ( strtotime($info_time_values) >= strtotime($now) && $show_all != '1' ) || $show_all == '1' ) {
															$times_new_array[] .= $info_time_values;
														}
													}
													foreach ( $times_new_array as $times_new_array_value ) {
														if ( count($times_new_array) == 1 || $times_count == count($times_new_array) ) {
															if ( get_option('vh_time_format') == false || get_option('vh_time_format') == '24h' ) {
																$output .= $times_new_array_value.' ';
															} else {
																$output .= date('h:i a', strtotime($times_new_array_value)).' ';
															}
														} else {
															if ( get_option('vh_time_format') == false || get_option('vh_time_format') == '24h' ) {
																$output .= $times_new_array_value.'; ';
															} else {
																$output .= date('h:i a', strtotime($times_new_array_value)).'; ';
															}
														}
														$times_count++;
													}
													$output .= '</div>';
												$output .= '<div class="movie_cinema">';
												$output .= $info['theatre'];
												$output .= '</div>';
												$output .= '<div class="movie_length">'. vh_convertToHoursMins(get_post_meta( get_the_ID(), 'movies_length', true ), __('%2d hours %2d minutes', "vh" )) .'</div>';
											$output .= '</div>';
										$output .= '</div>';
									$output .= '</li>';
									break;
								}
							}
						}
					}
				}
			}
		}
		$output .= '</ul>';
	}

	echo $output;
	
	wp_reset_query(); 
	die(1);
}

add_action( 'wp_ajax_nopriv_event-filter', 'vh_load_event_tickets' );
add_action( 'wp_ajax_event-filter', 'vh_load_event_tickets' );
function vh_load_event_tickets() {
	$post_date = sanitize_text_field($_POST['date']);
	$post_id = sanitize_text_field($_POST['post_id']);
	$show_all = sanitize_text_field($_POST['all_tickets']);
	$count = 1;

	if ( $post_date == '' ) {
		$post_date = date('m.d.Y');
	}

	$current_date = explode(".", $post_date);

	$values = trim(get_post_meta( $post_id, 'movie_time_values', true ),',');
	$values_arr = json_decode('['.$values.']',true);

	$output = '';
	$output .= '<ul>';
	foreach ($values_arr as $value) {
		$movie_date = explode(".", $value['date']);
		if ( (( strtotime($movie_date["2"]."-".$movie_date["0"]."-".$movie_date["1"]) == strtotime($current_date["2"]."-".$current_date["0"]."-".$current_date["1"]) && $show_all != '1' ) || ( strtotime($movie_date["2"]."-".$movie_date["0"]."-".$movie_date["1"]) >= strtotime($current_date["2"]."-".$current_date["0"]."-".$current_date["1"]) && $show_all == '1' )) ) {
			$times = explode(';',$value['time']);
			$place = explode(';',$value['place']);
			$ticket = explode(';',$value['ticket']);

			foreach ($times as $times_key => $times_value) {
				$combined[$times_key] = $times_value;
			}

			foreach ($place as $place_key => $place_value) {
				$combined[$place_key] .= ';'.$place_value;
			}

			foreach ($ticket as $ticket_key => $ticket_value) {
				$combined[$ticket_key] .= ';'.$ticket_value;
			}

			foreach ($ticket as $ticket_key => $ticket_value) {
				if ( !empty($value['button_text']) ) {
					$button_text = $value['button_text'];
				} else {
					$button_text = '';
				}

				if ( !empty($value['button_link']) ) {
					$button_link = $value['button_link'];
				} else {
					$button_link = '';
				}

				$combined[$ticket_key] .= ';'.$button_text.';'.$button_link;
			}

			foreach ($combined as $combined_value) {
				$needed_info = explode(';', $combined_value);

				$new_button_text = '';
				if ( $needed_info[3] == '' ) {
					$new_button_text = __('Buy tickets','vh');
				} else {
					$new_button_text = $needed_info[3];
				}

				$permalink_structure = get_option('permalink_structure');

				if ( $permalink_structure == '' ) {
					$url_symbol = '&';
				} else {
					$url_symbol = '?';
				}

				$new_button_link = '';
				if ( $needed_info[4] == '' ) {
					$new_button_link = get_permalink( get_post()->ID ) . $url_symbol . 'event_id='.$needed_info[2].'&movie='.get_post()->ID;
				} else {
					$new_button_link = $needed_info[4];
				}

				if ( get_option('vh_time_format') == false || get_option('vh_time_format') == '24h' ) {
					$output .= '<li><div class="time icon-clock">'.$needed_info[0].'</div><div class="ticket hover_right"><a href="'.$new_button_link.'" class="icon-ticket">'.$new_button_text.'</a></div><div class="event_auditory"><strong>' . __('Venue:', 'vh') . '</strong> ' . $value['theatre'].', '.$needed_info[1].'</div></li>';
				} else {
					$output .= '<li><div class="time icon-clock">'.date('h:i a', strtotime($needed_info[0])).'</div><div class="ticket hover_right"><a href="'.$new_button_link.'" class="icon-ticket">'.$new_button_text.'</a></div><div class="event_auditory"><strong>' . __('Venue:', 'vh') . '</strong> ' . $value['theatre'].', '.$needed_info[1].'</div></li>';
				}
			}
		}
	}
	$output .= '</ul>';
	
	echo $output;
	
	die(1);
}

function parse_urls( $text, $maxurl_len = 35, $target = '_self' ) {
	if ( preg_match_all('/((ht|f)tps?:\/\/([\w\.]+\.)?[\w-]+(\.[a-zA-Z]{2,4})?[^\s\r\n\(\)"\'<>\,\!]+)/si', $text, $urls) ) {
		$offset1 = ceil(0.65 * $maxurl_len) - 2;
		$offset2 = ceil(0.30 * $maxurl_len) - 1;

		foreach (array_unique($urls[1]) AS $url) {
			if ($maxurl_len AND strlen($url) > $maxurl_len) {
				$urltext = substr($url, 0, $offset1) . '...' . substr($url, -$offset2);
			} else {
				$urltext = $url;
			}

			$text = str_replace($url, '<a href="' . $url . '" target="' . $target . '" title="' . $url . '">' . parse_url($urltext, PHP_URL_HOST) . '</a>', $text);
		}
	}

	return $text;
}

add_action( 'wp_ajax_nopriv_movies-filter', 'vh_load_movies_list' );
add_action( 'wp_ajax_movies-filter', 'vh_load_movies_list' );
function vh_load_movies_list() {
	$cat_id = sanitize_text_field($_POST['cat']);
	$theatre = sanitize_text_field($_POST['theatres']);
	$post_date = sanitize_text_field($_POST['date']);
	$sorting = sanitize_text_field($_POST['sorting']);
	$show_all = sanitize_text_field($_POST['list_all_events']);
	global $post;
	$output = '<script type="text/javascript">
	var $container = jQuery("#movies_list_content .event_container").isotope({
		layoutMode: "fitRows",
		transformsEnabled: true,
			getSortData: {
				popularity : function ( $elem ) {
					return parseInt($elem.find(".event_list_rating").text());
				},
				release : function ( $elem ) {
					
					var release_val = $elem.find(".info.event_release").text();
					if ( release_val == "" ) {
						release_val = "January 1, 1970";
					}
					return Date.parse(release_val);
				},
				comments : function ( $elem ) {
					return parseInt( $elem.find(".comments").text());
				}
			},
			sortBy: "'.$sorting.'",
			sortAscending: false,
			animationOptions: {
				duration: 250,
				easing: "swing",
				queue: true
			},
			animationEngine : "jquery"
	});
	jQuery(".wrapper-dropdown-6 .dropdown li").click(function(e) {
		var sortValue = jQuery(this).find("input[type=hidden]").attr("data-sort-value");
			$container.isotope({
				sortBy: sortValue,
				sortAscending: false
			});
	});

	jQuery( ".event_list.isotope-item .movie_list_image" ).mouseenter(function() {
		jQuery(this).find(".bottom_line").show().animate({
			width: "100%",
			left: "0%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery( ".event_list.isotope-item .movie_list_image" ).mouseleave(function() {
		jQuery(this).find(".bottom_line").show().animate({
			width: "0%",
			left: "50%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery(document).ajaxComplete(function() {
		var main = 0;
		var sidebar_height = 0;
		var sidebar = "";
		if ( jQuery(".page-wrapper").hasClass("page-sidebar-right") ) {
			sidebar_height = jQuery(".sidebar-right").height();
			sidebar = jQuery(".sidebar-right");
			main = jQuery(".sidebar-right-pull").height();
		} else {
			sidebar_height = jQuery(".sidebar-left").height();
			sidebar = jQuery(".sidebar-left");
			main = jQuery(".sidebar-left-pull").height();
		};

		if ( jQuery(window).width() > 750 && main > sidebar.height() ) {
			sidebar.height(main);
		} else {
			sidebar.height("auto");
		}
	});
	</script>';

	if ( $post_date == '' || $post_date == 'NaN.NaN.NaN' ) {
		$post_date = date('m.d.Y');
	}

	$current_date = explode(".", $post_date);

	if ( $show_all == '1' ) {
		$args = array(
		'numberposts' => -1,
		'post_type' => 'movies',
		'event_categories' => $cat_id,
		'posts_per_page' => -1
		);
	} else {
		$args = array(
		'numberposts' => -1,
		'posts_per_page' => -1,
		'post_type' => 'movies',
		'event_categories' => $cat_id,
		'meta_query' => array(
				array(
					'key' => 'movie_time_values',
					'value' => $post_date,
					'compare' => 'LIKE'
				)
			)
		);
	}

	$the_query = new WP_Query( $args );

	if( $the_query->have_posts() ) {
		$output .= '<ul class="event_container">';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();

			try {
				$dt = new DateTime('',new DateTimeZone(get_option('timezone_string')));
			} catch(Exception $e) {
				if ( strlen($e->getMessage()) > 1 ) {
					$dt = new DateTime('',new DateTimeZone('UTC'));
				}
			}


			$now = strtotime($dt->format('H:00'));
			$now = date('H:00',$now);

			$values = trim(get_post_meta( get_post()->ID, 'movie_time_values', true ),',');
			$values_arr = json_decode('{"events":['.$values.']}',true);

			foreach ( $values_arr as $value ) {
				foreach ( $value as $info ) {
					$movie_date = explode(".", $info['date']);
					if ( $post_date != '' && (( strtotime($movie_date["2"]."-".$movie_date["0"]."-".$movie_date["1"]) == strtotime($current_date["2"]."-".$current_date["0"]."-".$current_date["1"]) && $show_all != '1' ) || ( strtotime($movie_date["2"]."-".$movie_date["0"]."-".$movie_date["1"]) >= strtotime($current_date["2"]."-".$current_date["0"]."-".$current_date["1"]) && $show_all == '1' )) ) {
						if ( $theatre == '' ) {
									$output .= '<li class="event_list vc_span12" id="post-'. get_the_ID() .'">';
									$output .= '<div class="movie_list_image shadows">';
										$output .= '<div class="bottom_line"></div>';
										if ( kd_mfi_get_featured_image_id( 'event-poster', 'movies' ) != '' ) {
											$attachment_id = kd_mfi_get_featured_image_id( 'event-poster', 'movies' );
											$image = wp_get_attachment_image_src( $attachment_id, 'movie_list' );
											$output .= '<a href="' . get_permalink( get_the_ID() ) . '"><img src="'. $image[0] .'"></a>'; 
										} elseif ( has_post_thumbnail( get_post()->ID ) ) {
											$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_post()->ID ), 'movie_list' );
											$output .= '<a href="' . get_permalink( get_the_ID() ) . '"><img src="'. $image[0] .'"></a>'; 
										}
										$output .= '</div>';
										$output .= '<div class="movie_list_container">';
										$output .= '<div class="movie_title"><a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></div>';
														if(function_exists('the_ratings')) {
															$rating_images = substr_replace(the_ratings_results($post->ID), '', strlen(the_ratings_results($post->ID))-5);
															$rating_value = number_format(floatval(substr(the_ratings_results(get_the_ID()), strlen(the_ratings_results(get_the_ID()))-5)), 1);
															if ( $rating_value < 10 ) {
																$rating_value = ' ' . $rating_value;
															}
															$output .= '<div class="event_list_rating">' . $rating_images . $rating_value . '</div>';
														}
														$tc = wp_count_comments(get_the_ID()); 
														$output .= '<span class="comments icon-comment">' . $tc->total_comments . '</span>';
											$output .= '<div class="overview-container">
															<div class="main_side_left">';
												$categories = wp_get_post_terms( get_the_ID(), 'event_categories',array("fields" => "names") );
												$categories_count = count($categories);
												$categories_val = 1;
												$categories_string = '';
												foreach ($categories as $value) {
													if ( $categories_val == $categories_count ) {
														$categories_string = $categories_string.$value;
													} else {
														$categories_string = $categories_string.$value.', ';
													}
													$categories_val++;
												}

												if ( $categories_string != '' ) {
													$output .= '<div class="event_list_item icon-tags"><div class="title left">'.__('Category:','vh').'</div><div class="info event_category">' . $categories_string . '</div><div class="clearfix"></div></div>';
												}
												if ( get_post_meta( get_the_ID(), 'event_release', true ) != '' ) {
													$output .= '<div class="event_list_item icon-calendar"><div class="title left">'.__('Release date:','vh').'</div><div class="info event_release">' . get_post_meta( get_the_ID(), 'event_release', true ) . '</div><div class="clearfix"></div></div>';
												}
												if ( get_post_meta( get_the_ID(), 'movies_length', true ) != '' ) {
													$output .= '<div class="event_list_item icon-clock"><div class="title left">'.__('Duration:','vh').'</div><div class="info movies_length">' . vh_convertToHoursMins(get_post_meta( get_the_ID(), 'movies_length', true ), __('%2d hours %2d minutes', "vh" )) . '</div><div class="clearfix"></div></div>';
												}

											$output .= '</div>
											<div class="main_side_right">';
													if ( get_post_meta( get_the_ID(), 'event_director', true ) != '' ) {
														$output .= '<div class="event_list_item icon-user"><div class="title right">'.__('Director:','vh').'</div><div class="info event_director">' . get_post_meta( get_the_ID(), 'event_director', true ) . '</div><div class="clearfix"></div></div>';
													}
													if ( get_post_meta( get_the_ID(), 'event_actors', true ) != '' ) {
														$output .= '<div class="event_list_item icon-users"><div class="title right">'.__('Actors:','vh').'</div><div class="info event_actors">' . get_post_meta( get_the_ID(), 'event_actors', true ) . '</div><div class="clearfix"></div></div>';
													}
													if ( get_post_meta( get_the_ID(), 'event_box_office', true ) != '' ) {
														$output .= '<div class="event_list_item icon-dollar"><div class="title right">'.__('Box Office:','vh').'</div><div class="info event_box_office">' . get_post_meta( get_the_ID(), 'event_box_office', true ) . '</div><div class="clearfix"></div></div>';
													}

											$output .= '</div>
											<div class="clearfix"></div>
											</div><!--end of overview-container-->';
													$output .= '<div class="event_buttons">';
													$output .= '<div class="button_red"><a href="'.get_permalink(get_the_ID()).'#tickets'.'" class="vh_button red icon-ticket hover_right">'. __('Buy tickets','vh').'</a></div>';
													$youtube = explode('=',get_post_meta( get_the_ID(), 'event_trailer', true ));
													if ( $youtube[0] != '' ) {
														if ( $youtube[0] != '' ) {
															$youtube_url = $youtube['1'];
														} else {
															$youtube_url = '';
														}
														$output .= '<div class="button_yellow"><a href="#" class="vh_button yellow icon-play-1 hover_right">'. __('Watch trailer','vh').'</a><input type="hidden" value="'. $youtube_url.'"></input></div>';
													}
												$output .= '</div>';
										$output .= '</div>';
									$output .= '</li>';
									break;
								
							
						} elseif ( $info['theatre'] == $theatre ) {
									$output .= '<li class="vc_span12" id="post-'. get_the_ID() .'">';
									$output .= '<div class="movie_list_image shadows">';
										$output .= '<div class="bottom_line"></div>';
										if ( kd_mfi_get_featured_image_id( 'event-poster', 'movies' ) != '' ) {
											$attachment_id = kd_mfi_get_featured_image_id( 'event-poster', 'movies' );
											$image = wp_get_attachment_image_src( $attachment_id, 'movie_list' );
											$output .= '<a href="' . get_permalink( get_the_ID() ) . '"><img src="'. $image[0] .'"></a>'; 
										} elseif ( has_post_thumbnail( get_post()->ID ) ) {
											$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_post()->ID ), 'movie_list' );
											$output .= '<a href="' . get_permalink( get_the_ID() ) . '"><img src="'. $image[0] .'"></a>'; 
										}
										$output .= '</div>';
										$output .= '<div class="movie_list_container">';
										$output .= '<div class="movie_title"><a href="' . get_permalink( get_the_ID() ) . '">' . get_the_title() . '</a></div>';
														if(function_exists('the_ratings')) {
															$rating_images = substr_replace(the_ratings_results($post->ID), '', strlen(the_ratings_results($post->ID))-5);
															$rating_value = number_format(floatval(substr(the_ratings_results(get_the_ID()), strlen(the_ratings_results(get_the_ID()))-5)), 1);
															if ( $rating_value < 10 ) {
																$rating_value = ' ' . $rating_value;
															}
															$output .= '<div class="event_list_rating">' . $rating_images . $rating_value . '</div>';
														}
														$tc = wp_count_comments(get_the_ID()); 
														$output .= '<span class="comments icon-comment">' . $tc->total_comments . '</span>';
											$output .= '<div class="overview-container">
															<div class="main_side_left">';
												$categories = wp_get_post_terms( get_the_ID(), 'event_categories',array("fields" => "names") );
												$categories_count = count($categories);
												$categories_val = 1;
												$categories_string = '';
												foreach ($categories as $value) {
													if ( $categories_val == $categories_count ) {
														$categories_string = $categories_string.$value;
													} else {
														$categories_string = $categories_string.$value.', ';
													}
													$categories_val++;
												}

												if ( $categories_string != '' ) {
													$output .= '<div class="event_list_item icon-tags"><div class="title left">'.__('Category:','vh').'</div><div class="info event_category">'.$categories_string.'</div><div class="clearfix"></div></div>';
												}
												if ( get_post_meta( get_the_ID(), 'event_release', true ) != '' ) {
													$output .= '<div class="event_list_item icon-calendar"><div class="title left">'.__('Release date:','vh').'</div><div class="info event_release">'. get_post_meta( get_the_ID(), 'event_release', true ).'</div><div class="clearfix"></div></div>';
												}
												if ( get_post_meta( get_the_ID(), 'movies_length', true ) != '' ) {
													$output .= '<div class="event_list_item icon-clock"><div class="title left">'.__('Duration:','vh').'</div><div class="info movies_length">'. vh_convertToHoursMins(get_post_meta( get_the_ID(), 'movies_length', true ), __('%2d hours %2d minutes', "vh" )).'</div><div class="clearfix"></div></div>';
												}

											$output .= '</div>
											<div class="main_side_right">';
													if ( get_post_meta( get_the_ID(), 'event_director', true ) != '' ) {
														$output .= '<div class="event_list_item icon-user"><div class="title right">'.__('Director:','vh').'</div><div class="info event_director">'. get_post_meta( get_the_ID(), 'event_director', true ).'</div><div class="clearfix"></div></div>';
													}
													if ( get_post_meta( get_the_ID(), 'event_actors', true ) != '' ) {
														$output .= '<div class="event_list_item icon-users"><div class="title right">'.__('Actors:','vh').'</div><div class="info event_actors">'. get_post_meta( get_the_ID(), 'event_actors', true ).'</div><div class="clearfix"></div></div>';
													}
													if ( get_post_meta( get_the_ID(), 'event_box_office', true ) != '' ) {
														$output .= '<div class="event_list_item icon-dollar"><div class="title right">'.__('Box Office:','vh').'</div><div class="info event_box_office">'. get_post_meta( get_the_ID(), 'event_box_office', true ).'</div><div class="clearfix"></div></div>';
													}
													
											$output .= '</div>
											<div class="clearfix"></div>
											</div><!--end of overview-container-->';
													$output .= '<div class="event_buttons">';
													$output .= '<div class="button_red"><a href="'.get_permalink(get_the_ID()).'#tickets'.'" class="vh_button red icon-ticket hover_right">'. __('Buy tickets','vh').'</a></div>';
													$youtube = explode('=',get_post_meta( get_the_ID(), 'event_trailer', true ));
													if ( $youtube[0] != '' ) {
														if ( $youtube[0] != '' ) {
															$youtube_url = $youtube['1'];
														} else {
															$youtube_url = '';
														}
														$output .= '<div class="button_yellow"><a href="#" class="vh_button yellow icon-play-1 hover_right">'. __('Watch trailer','vh').'</a><input type="hidden" value="'. $youtube_url.'"></input></div>';
													}
												$output .= '</div>';
										$output .= '</div>';
									$output .= '</li>';
									break;
						}
					}
				}
			}

		}
		$output .= '</ul>';
	}

	echo $output;
	
	wp_reset_query(); 
	die(1);
}

function get_movie_theatres() {
		query_posts(array(
			'post_type' => 'movies',
			'posts_per_page' => -1
		));

		if ( !have_posts() ) {
			return;
		}

		while(have_posts()) : the_post();
			$values = trim(get_post_meta( get_post()->ID, 'movie_time_values', true ),',');
			$values_arr = json_decode('['.$values.']',true);
			foreach ($values_arr as $value) {
				$theatre_arr[] = $value['theatre'];
			}

		endwhile;

		$output = '';
		$output .= '<div id="dd" class="wrapper-dropdown-1 dropdown_list" tabindex="1">
						<span>'.__('All theatres','vh').'</span>
						<ul class="dropdown">';
						
		$output .= '<li><a href="#">'.__('All theatres','vh').'</a><input type="hidden" value=""></li>';
		foreach ( array_unique($theatre_arr) as $theatre ) {
			$output .= '<li><a href="#">'.$theatre.'</a><input type="hidden" value="'.$theatre.'"></li>';
		}

		$output .= '</ul>
				</div>';

		wp_reset_query();

	return $output;
}

function get_movie_upcoming_dates($count,$class='') {
	// $today = date('m.d.Y');
	// $dates = array($today);
	// $day[] = date('l');

	// if ( $count == '' ) {
	// 	$count = 7;
	// }

	// if ( $class != '' ) {
	// 	$class = ' '.$class;
	// }

	// for ($i=1; $i < $count ; $i++) {
	// 	$dates[] = Date('m.d.Y', strtotime("+" . $i . " days"));
	// 	$day[] = Date('l', strtotime("+" . $i . " days"));
	// }

	// $output .= '<div id="dd2" class="wrapper-dropdown-2 dropdown_list'.$class.'" tabindex="2">
	// 				<span>'.$day[0].','.$dates[0].'</span>
	// 				<ul class="dropdown"><div class="dropdown_spike"></div><span class="shadows"></span>';
	// $i = 0;
	// foreach ( $dates as $date ) {
	// 	if ( $i == 0 ) {
	// 		$output .= '<li class="active"><a href="#">'.$day[$i].','.$date.'</a><input type="hidden" value="'.$date.'"></li>';
	// 	} else {
	// 		$output .= '<li><a href="#">'.$day[$i].','.$date.'</a><input type="hidden" value="'.$date.'"></li>';
	// 	}
	// 	$i++;
	// }

	// $output .= '</ul>
	// 		</div>';

	$output = '';
	$output .= '<div class="datepicker-container"><input type="text" id="movie-datepicker" placeholder="'.__('Date', 'vh').'"></div>';

	return $output;
}

function vh_get_event_dates( $max_count = 7 ) {
	// $values = trim(get_post_meta( get_post()->ID, 'movie_time_values', true ),',');
	// $values_arr = json_decode('['.$values.']',true);

	// foreach ($values_arr as $value) {
	// 	$times[] = $value['date'];
	// }

	// $output = '';
	// $output .= '<div id="dd5" class="wrapper-dropdown-5 dropdown_list" tabindex="1">
	// 				<span>' . $times[0] . '</span>
	// 				<ul class="dropdown"><div class="dropdown_spike"></div><span class="shadows"></span>';
	// $i = 0;
	// foreach ( array_unique($times) as $date ) {
	// 	if ( $i == 0 ) {
	// 		$output .= '<li class="active"><a href="#">'.$date.'</a><input type="hidden" value="'.$date.'"></li>';
	// 	} elseif ( $i < $max_count ) {
	// 		$output .= '<li><a href="#">'.$date.'</a><input type="hidden" value="'.$date.'"></li>';
	// 	}
	// 	$i++;
	// }

	// $output .= '</ul>
	// 		</div>';

	$output = '';
	$output .= '<div class="datepicker-container"><input type="text" id="movie-datepicker" placeholder="'.__('Date', 'vh').'"></div>';

	return $output;
}

function get_movie_upcoming_times($count) {
	$output = '';
	try {
		$dt = new DateTime('',new DateTimeZone(get_option('timezone_string')));
	} catch(Exception $e) {
		if ( strlen($e->getMessage()) > 1 ) {
			$dt = new DateTime('',new DateTimeZone('UTC'));
		}
	}

	if ( get_option('vh_time_format') == false || get_option('vh_time_format') == '24h' ) {
		$today = strtotime($dt->format('H:00'));
	} else {
		$today = strtotime($dt->format('h:00 a'));
	}
	
	if ( $count == '' ) {
		$count = 8;
	} else {
		$count = $count + 1;
	};
	
	// $todays_date = date('d.m.Y',time());
	// var_dump($GLOBALS['vh_movies_selected_date']);
	// // $output = '<script>console.log(jQuery("#next_date").val())</script>';

	for ($i=1; $i < $count ; $i++) {

		$next = strtotime($dt->format('H:00'))+3600*$i;
		$next = date('H:00',$next);
		if ($next == '23:00') {
			$time[] = $next;
			break;
		}
		
		$time[] = $next;
	}

	$output .= '<div id="dd3" class="wrapper-dropdown-3 dropdown_list" tabindex="3">
					<span>'.__('Upcomming','vh').'</span>
					<ul class="dropdown">';
	$i = 0;
	foreach ( $time as $time_next ) {
		if ( get_option('vh_time_format') == false || get_option('vh_time_format') == '24h' ) {
			if ( $i == 0 ) {

				$output .= '<li class="active"><a href="#">' . __('Starting with ', 'vh') . '<span>'.$time_next.'</span></a><input type="hidden" value="'.$time_next.'"></li>';
			} else {
				$output .= '<li><a href="#">' . __('Starting with ', 'vh') . '<span>'.$time_next.'</span></a><input type="hidden" value="'.$time_next.'"></li>';
			}
		} else {
			if ( $i == 0 ) {
				$output .= '<li class="active"><a href="#">' . __('Starting with ', 'vh') . '<span>'.date('h:00 a',strtotime($time_next)).'</span></a><input type="hidden" value="'.$time_next.'"></li>';
			} else {
				$output .= '<li><a href="#">' . __('Starting with ', 'vh') . '<span>'.date('h:00 a',strtotime($time_next)).'</span></a><input type="hidden" value="'.$time_next.'"></li>';
			}
		}
		$i++;
	}

	$output .= '</ul>
			</div>';

	return $output;
}

function get_movie_categories() {
	$values = get_terms('event_categories');
	foreach ($values as $value) {
		$categories[] = array('name'=>$value->name,'slug'=>$value->slug);
	}

	$output = '';
	$output .= '<div id="dd4" class="wrapper-dropdown-4 dropdown_list" tabindex="4">
					<span>'.__('All genres','vh').'</span>
					<ul class="dropdown">';
	$output .= '<li><a href="#">'.__('All genres','vh').'</a><input type="hidden" value=""></li>';
	foreach ( $categories as $cat ) {
		$output .= '<li><a href="#">'.$cat['name'].'</a><input type="hidden" value="'.$cat['slug'].'"></li>';
	}

	$output .= '</ul>
			</div>';

	return $output;
}

function get_most_rated_events($mode = '', $min_votes = 0, $limit = 10, $chars = 0, $display = true) {
	global $wpdb;
	$ratings_max = intval(get_option('postratings_max'));
	$ratings_custom = intval(get_option('postratings_customrating'));
	$output = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "$wpdb->posts.post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	if($ratings_custom && $ratings_max == 2) {
		$order_by = 'ratings_score';
	} else {
		$order_by = 'ratings_average';
	}

	$temp2 = $temp3 = $temp4 = '';
	$post_id = "%POST_URL%";
	$temp = '<li>';
	$temp3 .= '<div class="main_event_widget_container">
	<a href="%POST_URL%" title="%POST_TITLE%">%POST_TITLE%</a>';
	$temp2 .= '</div><div class="right_event_widget_container">
	<a href="%POST_URL%" class="icon-play-1" title="%POST_TITLE%">';
	$temp4 .= '</a>
	<a href="%POST_URL%'.'#tickets'.'" class="icon-ticket" title="%POST_TITLE%"></a></div>
	</li>';

	$most_rated = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (t1.meta_value+0.00) AS ratings_average, (t2.meta_value+0.00) AS ratings_users, (t3.meta_value+0.00) AS ratings_score FROM $wpdb->posts LEFT JOIN $wpdb->postmeta AS t1 ON t1.post_id = $wpdb->posts.ID LEFT JOIN $wpdb->postmeta As t2 ON t1.post_id = t2.post_id LEFT JOIN $wpdb->postmeta AS t3 ON t3.post_id = $wpdb->posts.ID WHERE t1.meta_key = 'ratings_average' AND t2.meta_key = 'ratings_users' AND t3.meta_key = 'ratings_score' AND $wpdb->posts.post_password = '' AND $wpdb->posts.post_date < '".current_time('mysql')."' AND $wpdb->posts.post_status = 'publish' AND t2.meta_value >= $min_votes AND $where ORDER BY ratings_users DESC, $order_by DESC LIMIT $limit");
	if($most_rated) {
		$i = 1;
		foreach ($most_rated as $post) {
			$output .= expand_ratings_template($temp, $post, null, $chars, false);
			if ( $i == 10 ) {
				$output .= '<span class="event_widget_number last">'.$i.'</span>';
			} else {
				$output .= '<span class="event_widget_number">'.$i.'</span>';
			}
			$post_id = url_to_postid(expand_ratings_template($post_id, $post, null, $chars, false));
			$output .= expand_ratings_template($temp3, $post, null, $chars, false);
			
			if(function_exists('the_ratings')) {
				$rating_images = substr_replace(the_ratings_results($post->ID), '', strlen(the_ratings_results($post->ID))-5);
				$rating_value = number_format(floatval(substr(the_ratings_results(get_the_ID()), strlen(the_ratings_results(get_the_ID()))-5)), 1);
				if ( $rating_value < 10 ) {
					$rating_value = ' ' . $rating_value;
				}
				$output .= '<div class="event_list_rating">' . $rating_images . $rating_value . '</div>';
			}
			$output .= expand_ratings_template($temp2, $post, null, $chars, false);
			$youtube = explode('=',get_post_meta( $post->ID, 'event_trailer', true ));
			if ( $youtube[0] != '' ) {
				$youtube_url = $youtube['1'];
			} else {
				$youtube_url = '';
			}
			$output .= '<input type="hidden" value="'.$youtube_url.'"></input>';
			$output .= expand_ratings_template($temp4, $post, null, $chars, false)."\n";
			$i++;
		}
	} else {
		$output = '<li>'.__('N/A', 'vh').'</li>'."\n";
	}

	if($display) {
		echo $output;
	} else {
		return $output;
	}
}

function vh_get_movies_list_sorting() {
	$output = '';
	$output .= '<div id="dd6" class="wrapper-dropdown-6 dropdown_list" tabindex="1">
					<span>'.__('Release date','vh').'</span>
					<ul class="dropdown">';

			$output .= '<li class="active"><a href="#">'.__('Release date','vh').'</a><input type="hidden" value="release" data-sort-value="release"></li>';
			$output .= '<li><a href="#">'.__('Popularity','vh').'</a><input type="hidden" value="popularity" data-sort-value="popularity"></li>';
			$output .= '<li><a href="#">'.__('Comments','vh').'</a><input type="hidden" value="comments" data-sort-value="comments"></li>';

	$output .= '</ul>
			</div>';

	return $output;
}

function vh_ldc_like_counter_p( $text="Likes: ",$post_id=NULL ) {
	global $post;
	$ldc_return = '';

	if( empty($post_id) ) {
		$post_id = $post->ID;
	}

	if ( function_exists('get_post_ul_meta') ) {
		$ldc_return = "<span class='ldc-ul_cont_likes icon-heart' onclick=\"alter_ul_post_values(this,'$post_id','like')\" >".$text."<span>".get_post_ul_meta($post_id,"like")."</span></span>";
	}

	return $ldc_return;
}

function admin_movies_javascript(){
	global $post;
	if( is_admin()) {
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script('jquery-ui-autocomplete');
		wp_enqueue_script('jquery.tags', get_stylesheet_directory_uri() . '/js/tag-it.min.js', array('jquery', 'jquery-ui-autocomplete'), '', TRUE);
	}
}
add_action('admin_print_scripts', 'admin_movies_javascript');

// Register ui styles for properties
function admin_movies_styles(){
	global $post;
	if(  is_admin() ) {
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('tags', get_stylesheet_directory_uri() . '/css/jquery.tagit.css');
	}
}
add_action('admin_print_styles', 'admin_movies_styles');

// Admin CSS
function vh_admin_css() {
	wp_enqueue_style( 'vh-admin-css', get_template_directory_uri() . '/functions/admin/css/wp-admin.css' );
}

add_action('admin_head','vh_admin_css');

// Sets the post excerpt length to 40 words.
function vh_excerpt_length($length) {
	return 39;
}
add_filter('excerpt_length', 'vh_excerpt_length');

// Returns a "Continue Reading" link for excerpts
function vh_continue_reading_link() {
	return ' </p><p><a href="' . esc_url(get_permalink()) . '" class="read_more_link">' . __('Read more', 'vh') . '</a>';
}

function my_widget_class($params) {

	// its your widget so you add  your classes
	$classe_to_add = (strtolower(str_replace(array(' '), array(''), $params[0]['widget_name']))); // make sure you leave a space at the end
	$classe_to_add = 'class=" '.$classe_to_add . ' ';
	$params[0]['before_widget'] = str_replace('class="', $classe_to_add, $params[0]['before_widget']);

	return $params;
}
add_filter('dynamic_sidebar_params', 'my_widget_class');

add_filter('widget_title', 'vh_my_title');
function vh_my_title($title) {
	global $vh_is_footer;

	// Only in footer

	// if ( $vh_is_footer === TRUE ) {
		$title_parts = explode(' ', $title);
		$title = $title_parts[0].' '.'<span>';

		for ($i=1; $i < count($title_parts); $i++) { 
			$title .= ' ' . $title_parts[$i];
		}
		$title .= '</span>';
	// }
	
	return $title;
}

add_action( 'load-post.php', 'vh_metabox_setup' );
add_action( 'load-post-new.php', 'vh_metabox_setup' );

function vh_metabox_setup() {

	/* Add meta boxes on the 'add_meta_boxes' hook. */
	add_action( 'add_meta_boxes', 'vh_add_metabox' );

	/* Save post meta on the 'save_post' hook. */
	add_action( 'save_post', 'movies_save_metabox', 10, 2 );
	add_action( 'save_post', 'offers_save_metabox', 10, 2 );
}

function vh_convertToHoursMins($time, $format = '%d:%d') {
	settype($time, 'integer');
	if ($time < 1) {
		return;
	}
	$hours = floor($time / 60);
	$minutes = ($time % 60);
	return sprintf($format, $hours, $minutes);
}

function movies_save_metabox( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['movies_nonce'] ) || !wp_verify_nonce( $_POST['movies_nonce'], basename( __FILE__ ) ) )
	return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
	return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value   = ( isset( $_POST['movie_time_values'] ) ? sanitize_text_field( $_POST['movie_time_values'] ) : '' );
	$new_meta_value2  = ( isset( $_POST['movies_length'] ) ? sanitize_text_field( $_POST['movies_length'] ) : '' );
	$new_meta_value3  = ( isset( $_POST['event_country'] ) ? sanitize_text_field( $_POST['event_country'] ) : '' );
	$new_meta_value4  = ( isset( $_POST['event_year'] ) ? sanitize_text_field( $_POST['event_year'] ) : '' );
	$new_meta_value5  = ( isset( $_POST['event_release'] ) ? sanitize_text_field( $_POST['event_release'] ) : '' );
	$new_meta_value6  = ( isset( $_POST['event_director'] ) ? sanitize_text_field( $_POST['event_director'] ) : '' );
	$new_meta_value7  = ( isset( $_POST['event_actors'] ) ? sanitize_text_field( $_POST['event_actors'] ) : '' );
	$new_meta_value8  = ( isset( $_POST['event_restriction'] ) ? sanitize_text_field( $_POST['event_restriction'] ) : '' );
	$new_meta_value9  = ( isset( $_POST['event_box_office'] ) ? sanitize_text_field( $_POST['event_box_office'] ) : '' );
	$new_meta_value10 = ( isset( $_POST['event_trailer'] ) ? sanitize_text_field( $_POST['event_trailer'] ) : '' );
	$new_meta_value11 = ( isset( $_POST['event_ticket'] ) ? sanitize_text_field( $_POST['event_ticket'] ) : '' );
	$new_meta_value12 = ( isset( $_POST['event_language'] ) ? sanitize_text_field( $_POST['event_language'] ) : '' );
	$new_meta_value13 = ( isset( $_POST['event_writers'] ) ? sanitize_text_field( $_POST['event_writers'] ) : '' );
	$new_meta_value14 = ( isset( $_POST['event_official_sites'] ) ? sanitize_text_field( $_POST['event_official_sites'] ) : '' );
	$new_meta_value15 = ( isset( $_POST['event_imdb_url'] ) ? sanitize_text_field( $_POST['event_imdb_url'] ) : '' );
	$new_meta_value16 = ( isset( $_POST['event_imdb_rating'] ) ? sanitize_text_field( $_POST['event_imdb_rating'] ) : '' );

	/* Get the meta key. */
	$meta_key   = 'movie_time_values';
	$meta_key2  = 'movies_length';
	$meta_key3  = 'event_country';
	$meta_key4  = 'event_year';
	$meta_key5  = 'event_release';
	$meta_key6  = 'event_director';
	$meta_key7  = 'event_actors';
	$meta_key8  = 'event_restriction';
	$meta_key9  = 'event_box_office';
	$meta_key10 = 'event_trailer';
	$meta_key11 = 'event_ticket';
	$meta_key12 = 'event_language';
	$meta_key13 = 'event_writers';
	$meta_key14 = 'event_official_sites';
	$meta_key15 = 'event_imdb_url';
	$meta_key16 = 'event_imdb_rating';

	/* Get the meta value of the custom field key. */
	$meta_value   = get_post_meta( $post_id, $meta_key, true );
	$meta_value2  = get_post_meta( $post_id, $meta_key2, true );
	$meta_value3  = get_post_meta( $post_id, $meta_key3, true );
	$meta_value4  = get_post_meta( $post_id, $meta_key4, true );
	$meta_value5  = get_post_meta( $post_id, $meta_key5, true );
	$meta_value6  = get_post_meta( $post_id, $meta_key6, true );
	$meta_value7  = get_post_meta( $post_id, $meta_key7, true );
	$meta_value8  = get_post_meta( $post_id, $meta_key8, true );
	$meta_value9  = get_post_meta( $post_id, $meta_key9, true );
	$meta_value10 = get_post_meta( $post_id, $meta_key10, true );
	$meta_value11 = get_post_meta( $post_id, $meta_key11, true );
	$meta_value12 = get_post_meta( $post_id, $meta_key12, true );
	$meta_value13 = get_post_meta( $post_id, $meta_key13, true );
	$meta_value14 = get_post_meta( $post_id, $meta_key14, true );
	$meta_value15 = get_post_meta( $post_id, $meta_key15, true );
	$meta_value16 = get_post_meta( $post_id, $meta_key16, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
	add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
	update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
	delete_post_meta( $post_id, $meta_key, $meta_value );

	if ( $new_meta_value2 && '' == $meta_value2 )
	add_post_meta( $post_id, $meta_key2, $new_meta_value2, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value2 && $new_meta_value2 != $meta_value2 )
	update_post_meta( $post_id, $meta_key2, $new_meta_value2 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value2 && $meta_value2 )
	delete_post_meta( $post_id, $meta_key2, $meta_value2 );

	if ( $new_meta_value3 && '' == $meta_value3 )
	add_post_meta( $post_id, $meta_key3, $new_meta_value3, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value3 && $new_meta_value3 != $meta_value3 )
	update_post_meta( $post_id, $meta_key3, $new_meta_value3 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value3 && $meta_value3 )
	delete_post_meta( $post_id, $meta_key3, $meta_value3 );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value4 && '' == $meta_value4 )
	add_post_meta( $post_id, $meta_key4, $new_meta_value4, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value4 && $new_meta_value4 != $meta_value4 )
	update_post_meta( $post_id, $meta_key4, $new_meta_value4 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value4 && $meta_value4 )
	delete_post_meta( $post_id, $meta_key4, $meta_value4 );

	if ( $new_meta_value5 && '' == $meta_value5 )
	add_post_meta( $post_id, $meta_key5, $new_meta_value5, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value5 && $new_meta_value5 != $meta_value5 )
	update_post_meta( $post_id, $meta_key5, $new_meta_value5 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value5 && $meta_value5 )
	delete_post_meta( $post_id, $meta_key5, $meta_value5 );

	if ( $new_meta_value6 && '' == $meta_value6 )
	add_post_meta( $post_id, $meta_key6, $new_meta_value6, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value6 && $new_meta_value6 != $meta_value6 )
	update_post_meta( $post_id, $meta_key6, $new_meta_value6 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value6 && $meta_value6 )
	delete_post_meta( $post_id, $meta_key6, $meta_value6 );

	if ( $new_meta_value7 && '' == $meta_value7 )
	add_post_meta( $post_id, $meta_key7, $new_meta_value7, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value7 && $new_meta_value7 != $meta_value7 )
	update_post_meta( $post_id, $meta_key7, $new_meta_value7 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value7 && $meta_value7 )
	delete_post_meta( $post_id, $meta_key7, $meta_value7 );

	if ( $new_meta_value8 && '' == $meta_value8 )
	add_post_meta( $post_id, $meta_key8, $new_meta_value8, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value8 && $new_meta_value8 != $meta_value8 )
	update_post_meta( $post_id, $meta_key8, $new_meta_value8 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value8 && $meta_value8 )
	delete_post_meta( $post_id, $meta_key8, $meta_value8 );

	if ( $new_meta_value9 && '' == $meta_value9 )
	add_post_meta( $post_id, $meta_key9, $new_meta_value9, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value9 && $new_meta_value9 != $meta_value9 )
	update_post_meta( $post_id, $meta_key9, $new_meta_value9 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value9 && $meta_value9 )
	delete_post_meta( $post_id, $meta_key9, $meta_value9 );

	if ( $new_meta_value10 && '' == $meta_value10 )
	add_post_meta( $post_id, $meta_key10, $new_meta_value10, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value10 && $new_meta_value10 != $meta_value10 )
	update_post_meta( $post_id, $meta_key10, $new_meta_value10 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value10 && $meta_value10 )
	delete_post_meta( $post_id, $meta_key10, $meta_value10 );

	if ( $new_meta_value11 && '' == $meta_value11 )
	add_post_meta( $post_id, $meta_key11, $new_meta_value11, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value11 && $new_meta_value11 != $meta_value11 )
	update_post_meta( $post_id, $meta_key11, $new_meta_value11 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value11 && $meta_value11 )
	delete_post_meta( $post_id, $meta_key11, $meta_value11 );

	if ( $new_meta_value12 && '' == $meta_value12 )
	add_post_meta( $post_id, $meta_key12, $new_meta_value12, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value12 && $new_meta_value12 != $meta_value12 )
	update_post_meta( $post_id, $meta_key12, $new_meta_value12 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value12 && $meta_value12 )
	delete_post_meta( $post_id, $meta_key12, $meta_value12 );

	if ( $new_meta_value13 && '' == $meta_value13 )
	add_post_meta( $post_id, $meta_key13, $new_meta_value13, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value13 && $new_meta_value13 != $meta_value13 )
	update_post_meta( $post_id, $meta_key13, $new_meta_value13 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value13 && $meta_value13 )
	delete_post_meta( $post_id, $meta_key13, $meta_value13 );

	if ( $new_meta_value14 && '' == $meta_value14 )
	add_post_meta( $post_id, $meta_key14, $new_meta_value14, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value14 && $new_meta_value14 != $meta_value14 )
	update_post_meta( $post_id, $meta_key14, $new_meta_value14 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value14 && $meta_value14 )
	delete_post_meta( $post_id, $meta_key14, $meta_value14 );

	if ( $new_meta_value15 && '' == $meta_value15 )
	add_post_meta( $post_id, $meta_key15, $new_meta_value15, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value15 && $new_meta_value15 != $meta_value15 )
	update_post_meta( $post_id, $meta_key15, $new_meta_value15 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value15 && $meta_value15 )
	delete_post_meta( $post_id, $meta_key15, $meta_value15 );

	if ( $new_meta_value16 && '' == $meta_value16 )
	add_post_meta( $post_id, $meta_key16, $new_meta_value16, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value16 && $new_meta_value16 != $meta_value16 )
	update_post_meta( $post_id, $meta_key16, $new_meta_value16 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value16 && $meta_value16 )
	delete_post_meta( $post_id, $meta_key16, $meta_value16 );
}

function offers_save_metabox( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['offer_nonce'] ) || !wp_verify_nonce( $_POST['offer_nonce'], basename( __FILE__ ) ) )
	return $post_id;

	/* Get the post type object. */
	$post_type = get_post_type_object( $post->post_type );

	/* Check if the current user has permission to edit the post. */
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
	return $post_id;

	/* Get the posted data and sanitize it for use as an HTML class. */
	$new_meta_value  = ( isset( $_POST['offer_metabox'] ) ? sanitize_text_field( $_POST['offer_metabox'] ) : '' );
	$new_meta_value2 = ( isset( $_POST['offer_external'] ) ? sanitize_text_field( $_POST['offer_external'] ) : '' );
	$new_meta_value3 = ( isset( $_POST['offer_link_text'] ) ? sanitize_text_field( $_POST['offer_link_text'] ) : '' );

	/* Get the meta key. */
	$meta_key  = 'offer_save';
	$meta_key2 = 'offer_link';
	$meta_key3 = 'offer_link_text';

	/* Get the meta value of the custom field key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );
	$meta_value2 = get_post_meta( $post_id, $meta_key2, true );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value && '' == $meta_value )
	add_post_meta( $post_id, $meta_key, $new_meta_value, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value && $new_meta_value != $meta_value )
	update_post_meta( $post_id, $meta_key, $new_meta_value );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value && $meta_value )
	delete_post_meta( $post_id, $meta_key, $meta_value );

	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value2 && '' == $meta_value2 )
	add_post_meta( $post_id, $meta_key2, $new_meta_value2, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value2 && $new_meta_value2 != $meta_value2 )
	update_post_meta( $post_id, $meta_key2, $new_meta_value2 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value2 && $meta_value2 )
	delete_post_meta( $post_id, $meta_key2, $meta_value2 );


	/* If a new meta value was added and there was no previous value, add it. */
	if ( $new_meta_value3 && '' == $meta_value3 )
	add_post_meta( $post_id, $meta_key3, $new_meta_value3, true );

	/* If the new meta value does not match the old value, update it. */
	elseif ( $new_meta_value3 && $new_meta_value3 != $meta_value3 )
	update_post_meta( $post_id, $meta_key3, $new_meta_value3 );

	/* If there is no new meta value but an old value exists, delete it. */
	elseif ( '' == $new_meta_value3 && $meta_value3 )
	delete_post_meta( $post_id, $meta_key3, $meta_value3 );
}

function vh_add_metabox() {

	add_meta_box(
		'movies_metabox',                                   // Unique ID
		esc_html__( 'Advanced event fields', 'vh' ),  // Title
		'movies_metabox_function',                          // Callback function
		'movies',                                           // Admin page (or post type)
		'normal',                                           // Context
		'high'                                              // Priority
	);

	add_meta_box(
		'offer_metabox',                                   // Unique ID
		esc_html__( 'Advanced offer fields', 'vh' ),  // Title
		'offer_metabox_function',                          // Callback function
		'special_offers',                                  // Admin page (or post type)
		'normal',                                          // Context
		'high'                                             // Priority
	);
}

function movies_metabox_function( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'movies_nonce' ); ?>

	<script>jQuery(document).ready(function(){
		jQuery("#datepicker").datepicker({
			dateFormat: 'mm.dd.yy',
			onSelect: function(dateText, inst) { 
				jQuery('.movie_date_fields').show();
			}
		});
		var old_object = jQuery('#movie_time_values').val();
			if ( old_object.substr(old_object.length - 1) == ',') {
				var objj = old_object.slice(0,-1);
				var objz = jQuery.parseJSON('['+objj+']');
				var new_object = '';
				jQuery.each(objz, function() {
					var old_theatre = this["theatre"];
					if ( old_theatre == undefined ) {
						old_theatre = '';
					};
					var old_date = this["date"];
					if ( old_date == undefined ) {
						old_date = '';
					};
					var old_time = this["time"];
					if ( old_time == undefined ) {
						old_time = '';
					};
					var old_place = this["place"];
					if ( old_place == undefined ) {
						old_place = '';
					};
					var old_ticket = this["ticket"];
					if ( old_ticket == undefined ) {
						old_ticket = '';
					};
					var old_button_text = this["button_text"];
					if ( old_button_text == undefined ) {
						old_button_text = '';
					};
					var old_button_link = this["button_link"];
					if ( old_button_link == undefined ) {
						old_button_link = '';
					};
					new_object += '{"theatre":"'+old_theatre+'","date":"'+old_date+'","time":"'+old_time+'","place":"'+old_place+'","ticket":"'+old_ticket+'","button_text":"'+old_button_text+'","button_link":"'+old_button_link+'"},';
				});
				jQuery('#movie_time_values').val(new_object.slice(0,-1));
				jQuery('#publish').click();
			};
		jQuery('#movie_time_send').click(function() {
			var time = jQuery('#movie_time').val();
			var date = jQuery('#datepicker').val();
			var theatre = jQuery('#movie_theatre').val();
			var place = jQuery('#movie_place').val();
			var ticket = jQuery('#event_ticket').val();
			var button_text = jQuery('#buy_tickets').val();
			var button_link = jQuery('#buy_tickets_link').val();
			theatre = theatre.replace(/,/g,' ');
			date = date.replace(/,/g,' ');
			time = time.replace(/,/g,' ');
			place = place.replace(/,/g,' ');
			ticket = ticket.replace(/,/g,' ');
			button_text = button_text.replace(/,/g,' ');
			button_link = button_link.replace(/,/g,' ');

			var tag = theatre+', '+date+', '+time+', '+place+', '+ticket+', '+button_text+', '+button_link;
			var info = ',{"theatre":"'+theatre+'","date":"'+date+'","time":"'+time+'","place":"'+place+'","ticket":"'+ticket+'","button_text":"'+button_text+'","button_link":"'+button_link+'"}';

			jQuery("#myTags").tagit("createTag", tag);
			jQuery('#movie_time_values').val(jQuery('#movie_time_values').val()+info);
			jQuery('#datepicker').val('');
			jQuery('#movie_time').val('');
			jQuery('#movie_theatre').val('');
			jQuery('#movie_place').val('');
			jQuery('#event_ticket').val('');
			jQuery('#buy_tickets').val('');
			jQuery('#buy_tickets_link').val('');
			jQuery('.movie_date_fields').hide();
		});
		jQuery("#myTags").tagit({
			afterTagRemoved: function(event, ui) {
				var tag = ui.tagLabel;
				tag = tag.replace(/,\s+/g,',');
				var tag_array = tag.split(',');

				var middle_tag_to_replace = ',{"theatre":"'+tag_array[0]+'","date":"'+tag_array[1]+'","time":"'+tag_array[2]+'","place":"'+tag_array[3]+'","ticket":"'+tag_array[4]+'","button_text":"'+tag_array[5]+'","button_link":"'+tag_array[6]+'"},';
				var first_tag_to_replace = '{"theatre":"'+tag_array[0]+'","date":"'+tag_array[1]+'","time":"'+tag_array[2]+'","place":"'+tag_array[3]+'","ticket":"'+tag_array[4]+'","button_text":"'+tag_array[5]+'","button_link":"'+tag_array[6]+'"},';
				var last_tag_to_replace = ',{"theatre":"'+tag_array[0]+'","date":"'+tag_array[1]+'","time":"'+tag_array[2]+'","place":"'+tag_array[3]+'","ticket":"'+tag_array[4]+'","button_text":"'+tag_array[5]+'","button_link":"'+tag_array[6]+'"}';
				var only_tag_to_replace = '{"theatre":"'+tag_array[0]+'","date":"'+tag_array[1]+'","time":"'+tag_array[2]+'","place":"'+tag_array[3]+'","ticket":"'+tag_array[4]+'","button_text":"'+tag_array[5]+'","button_link":"'+tag_array[6]+'"}';
				var date_old = jQuery('#movie_time_values').val();
			
				if ( date_old.indexOf(middle_tag_to_replace) > 1 ) {
					console.log(date_old.indexOf(middle_tag_to_replace));
					jQuery('#movie_time_values').val(date_old.replace(middle_tag_to_replace,','));
				} else if( date_old.indexOf(first_tag_to_replace) == 0 ) {
					jQuery('#movie_time_values').val(date_old.replace(first_tag_to_replace,''));
					console.log(date_old.indexOf(first_tag_to_replace));
				} else if( date_old.indexOf(last_tag_to_replace) > 1 ) {
					jQuery('#movie_time_values').val(date_old.replace(last_tag_to_replace,''));
					console.log(date_old.indexOf(last_tag_to_replace));
				} else if( date_old.indexOf(only_tag_to_replace) == 0 ) {
					jQuery('#movie_time_values').val(date_old.replace(only_tag_to_replace,''));
					console.log(date_old.indexOf(only_tag_to_replace));
				}
			}
		});
		jQuery('#movie_data_clear').click(function() {
			if (confirm('All event data will be permanently deleted, do you still want to do it?')) {
				jQuery("#movie_time_values").val('');
				jQuery("#publishing-action input[type=submit]").click();
			}
		});
	});
	</script>

	<p>
		<label for="movie_datepicker"><?php _e( "When will this event be held?", 'vh' ); ?></label><br />
		<input type="text" id="datepicker" name="movie_datepicker">
		<div class="movie_date_fields">
			<label for="movie_time" id="movie_time_label"><?php _e( "At what times will this event be held? Example - 09:00;13:00;15:00", 'vh' ); ?></label><br />
			<input class="widefat" type="text" id="movie_time" name="movie_time"><br />
			<label for="movie_theatre" id="movie_theatre_label"><?php _e( "Where will this event be held? Note: values are case sensitive.", 'vh' ); ?></label><br />
			<input class="widefat" type="text" id="movie_theatre" name="movie_theatre"><br />
			<label for="movie_place" id="movie_place_label"><?php _e( "Specific place of this event. Example - Auditory 11;Auditory 9;Auditory 13", 'vh' ); ?></label><br />
			<input class="widefat" type="text" id="movie_place" name="movie_place"><br />
			<label for="event_ticket" id="event_ticket_label"><?php _e( "Row seats shortcode IDs. Example - 1;13;17", 'vh' ); ?></label><br />
			<input class="widefat" type="text" id="event_ticket" name="event_ticket"><br />
			<label for="buy_tickets" id="buy_tickets_label"><?php _e( "\"Buy tickets\" button text. Leave empty for default", 'vh' ); ?></label><br />
			<input class="widefat" type="text" id="buy_tickets" name="buy_tickets"><br />
			<label for="buy_tickets_link" id="buy_tickets_link_label"><?php _e( "\"Buy tickets\" button link. Leave empty for default", 'vh' ); ?></label><br />
			<input class="widefat" type="text" id="buy_tickets_link" name="buy_tickets_link"><br />
			<input type="button" id="movie_time_send" value="Set event data">
		</div>
			<input type="hidden" name="movie_time_values" id="movie_time_values" id="movie_time_values" value="<?php echo esc_attr( get_post_meta( $object->ID, 'movie_time_values', true ) ); ?>">

		<ul id="myTags">
			<?php
			$info = trim(get_post_meta( $object->ID, 'movie_time_values', true ),',');
			$info_arr = json_decode('['.$info.']',true);

			foreach ($info_arr as $value) {
				if ( !empty($value['button_text']) ) {
					$button_text = $value['button_text'];
				} else {
					$button_text = '';
				}

				if ( !empty($value['button_link']) ) {
					$button_link = $value['button_link'];
				} else {
					$button_link = '';
				}
				echo '<li>'.$value['theatre'].', '.$value['date'].', '.$value['time'].', '.$value['place'].', '.$value['ticket'].', '.$button_text.', '.$button_link.'</li>';
			}
			?>
		</ul>
	</p>

	<p>
		<input type="button" id="movie_data_clear" value="Clear all events">
	</p>

	<p>
		<label for="movies_length"><?php _e( "Event length in minutes.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="movies_length" id="movies_length" value="<?php echo esc_attr( get_post_meta( $object->ID, 'movies_length', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_country"><?php _e( "Event country.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_country" id="event_country" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_country', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_year"><?php _e( "Event year.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_year" id="event_year" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_year', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_release"><?php _e( "Release date.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_release" id="event_release" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_release', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_director"><?php _e( "Director.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_director" id="event_director" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_director', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_actors"><?php _e( "Actors.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_actors" id="event_actors" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_actors', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_restriction"><?php _e( "Age restriction.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_restriction" id="event_restriction" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_restriction', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_box_office"><?php _e( "Box office. Example $1 017 003 568", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_box_office" id="event_box_office" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_box_office', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_writers"><?php _e( "Writers.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_writers" id="event_writers" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_writers', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_official_sites"><?php _e( "Official sites. Example http://www.example.com", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_official_sites" id="event_official_sites" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_official_sites', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_language"><?php _e( "Language.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_language" id="event_language" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_language', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_imdb_url"><?php _e( "IMDb URL.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_imdb_url" id="event_imdb_url" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_imdb_url', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_imdb_rating"><?php _e( "IMDb rating.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_imdb_rating" id="event_imdb_rating" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_imdb_rating', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="event_trailer"><?php _e( "Event youtube video link.", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="event_trailer" id="event_trailer" value="<?php echo esc_attr( get_post_meta( $object->ID, 'event_trailer', true ) ); ?>" size="30" />
	</p>

<?php }

function offer_metabox_function( $object, $box ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'offer_nonce' ); ?>

	<p>
		<label for="offer_metabox"><?php _e( "Enter special offer custom text. Example: Save 30%", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="offer_metabox" id="offer_metabox" value="<?php echo esc_attr( get_post_meta( $object->ID, 'offer_save', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="offer_external"><?php _e( "External link. Example: http://example.com", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="offer_external" id="offer_external" value="<?php echo esc_attr( get_post_meta( $object->ID, 'offer_link', true ) ); ?>" size="30" />
	</p>

	<p>
		<label for="offer_link_text"><?php _e( "External link text", 'vh' ); ?></label>
		<br />
		<input class="widefat" type="text" name="offer_link_text" id="offer_link_text" value="<?php echo esc_attr( get_post_meta( $object->ID, 'offer_link_text', true ) ); ?>" size="30" />
	</p>
<?php }

function vh_wp_tag_cloud_filter($return, $args) {
	return '<div class="tag_cloud_' . $args['taxonomy'] . '">' . $return . '</div>';
}
add_filter('wp_tag_cloud', 'vh_wp_tag_cloud_filter', 10, 2);

add_filter( 'tribe_events_the_previous_month_link', 'vh_tribe_previous_month_filter' );

// Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
function vh_page_menu_args($args) {
	$args['show_home'] = true;
	return $args;
}
add_filter('wp_page_menu_args', 'vh_page_menu_args');

function vh_set_tag_cloud_sizes($args) {
	$args['smallest'] = 10;
	$args['largest']  = 17;

	return $args;
}
add_filter('widget_tag_cloud_args','vh_set_tag_cloud_sizes');

// function vh_tag_cloud_class($tag_string, $args) {
// 	foreach ( $args as $key => $value ) {
// 		$tag_string = preg_replace("/tag-link-(" . $value->id . ")/", $value->slug, $tag_string);
// 	}
// 	return $tag_string;
// }
// add_filter('wp_generate_tag_cloud', 'vh_tag_cloud_class', 10, 3);

// Register menus
function register_vh_menus () {
	register_nav_menus(
		array (
			'primary-menu' => __('Primary Menu', 'vh')
		)
	);
}
add_action('init', 'register_vh_menus');

// Adds classes to the array of body classes.
function vh_body_classes($classes) {
	if (is_singular() && !is_home()) {
		$classes[] = 'singular';
	}

	if ( !is_front_page() ) {
		$classes[] = 'not_front_page';
	}

	if (is_search()) {
		$search_key = array_search('search', $classes);
		if ($search_key !== false) {
			unset($classes[$search_key]);
		}
	}

	// Color scheme class
	$vh_color_scheme = get_theme_mod( 'vh_color_scheme');

	if ( !empty($vh_color_scheme) ) {
		$classes[] = $vh_color_scheme;
	}

	// If blog shortcode
	global $post;
	if (isset($post->post_content) && false !== stripos($post->post_content, '[blog')) {
		$classes[] = 'page-template-blog';
	}

	// Breadcrumbs class
	$disable_breadcrumb = get_option('vh_breadcrumb') ? get_option('vh_breadcrumb') : 'false';
	if (!is_home() && !is_front_page() && $disable_breadcrumb == 'false') {
		$classes[] = 'has_breadcrumb';
	}

	return $classes;
}
add_filter('body_class', 'vh_body_classes');

function vh_css_settings() {

	// Vars
	$css        = array();
	$custom_css = get_option('vh_custom_css');

	// Custom CSS
	if(!empty($custom_css)) {
		array_push($css, $custom_css);
	}

	echo "
		<!-- Custom CSS -->
		<style type='text/css'>\n";

	if(!empty($css)) {
		foreach($css as $css_item) {
			echo $css_item . "\n";
		}
	}

	$fonts[SHORTNAME . "_primary_font_dark"] = ' html .main-inner p, .ac-device .description, .pricing-table .pricing-content .pricing-desc-1, body .vc_progress_bar .vc_single_bar .vc_label, .page-wrapper .member-desc, .page-wrapper .member-position, .page-wrapper .main-inner ul:not(.ui-tabs-nav) li, .page-wrapper .bg-style-2 p';
	$fonts[SHORTNAME . "_sidebar_font_dark"] = ' .sidebar-inner, .seatera-contactform.widget input:not(.btn), .seatera-recentpostsplus.widget .news-item p, .wrapper .text.widget p, .seatera-fastflickrwidget.widget, .widget li, .search.widget .sb-search-input, .widget .content-form .textarea.input-block-level';
	$fonts[SHORTNAME . "_headings_font_h1"]  = ' .wrapper h1';
	$fonts[SHORTNAME . "_headings_font_h2"]  = ' .page-wrapper h2, h2, .content .entry-title, .teaser_grid_container .post-title';
	$fonts[SHORTNAME . "_headings_font_h3"]  = ' .wrapper h3';
	$fonts[SHORTNAME . "_headings_font_h4"]  = ' .wrapper h4';
	$fonts[SHORTNAME . "_headings_font_h5"]  = ' .wrapper h5';
	$fonts[SHORTNAME . "_headings_font_h6"]  = ' .wrapper h6';
	$fonts[SHORTNAME . "_links_font"]        = ' .wpb_wrapper a, #author-link a';
	$fonts[SHORTNAME . "_widget"]            = ' .wrapper .sidebar-inner .item-title-bg h4, .wrapper .sidebar-inner .widget-title, .wrapper h3.widget-title a';
	$fonts[SHORTNAME . "_page_title"]        = ' body .wrapper .page-title h1';

	// Custom fonts styling
	foreach ($fonts as $key => $font) {
		$output                 = '';
		$current['font-family'] = get_option($key . '_font_face');
		$current['font-size']   = get_option($key . '_font_size');
		$current['line-height'] = get_option($key . '_line_height');
		$current['color']       = get_option($key . '_font_color');
		$current['font-weight'] = get_option($key . '_weight');

		foreach ($current as $kkey => $item) {

			if ( $key == SHORTNAME . '_widget' ) {
				if (!empty($item)) {

					if ($kkey == 'font-size' || $kkey == 'line-height') {
						$ending = 'px';
					} else if ($kkey == 'color') {
						$before = '#';
					} else if ($kkey == 'font-family') {
						$before = "'";
						$ending = "'";
						$item   = str_replace("+", " ", $item);
					} else if ($kkey == 'font-weight' && $item == 'italic') {
						$kkey = 'font-style';
					} else if ($kkey == 'font-weight' && $item == 'bold_italic') {
						$kkey = 'font-style';
						$item = 'italic; font-weight: bold';
					}


					$output .= " " . $kkey . ": " . $before . $item . $ending . ";";
				}

			}

			$ending = '';
			$before = '';
			if (!empty($item) && $key != SHORTNAME . '_widget') {

				if ($kkey == 'font-size' || $kkey == 'line-height') {
					$ending = 'px';
				} else if ($kkey == 'color') {
					$before = '#';
				} else if ($kkey == 'font-family') {
					$before = "'";
					$ending = "'";
					$item   = str_replace("+", " ", $item);
				} else if ($kkey == 'font-weight' && $item == 'italic') {
					$kkey = 'font-style';
				} else if ($kkey == 'font-weight' && $item == 'bold_italic') {
					$kkey = 'font-style';
					$item = 'italic; font-weight: bold';
				}


				$output .= " " . $kkey . ": " . $before . $item . $ending . ";";
			}
		}


		if ( !empty($output) && !empty($font) && $key != SHORTNAME . '_widget' ) {
			echo $font . ' { ' . $output . ' }';
		}
		if ( !empty($output) && !empty($font) && $key == SHORTNAME . '_widget' ) {
			echo '@media (min-width: 1200px) { ' . $font . ' { ' . $output . ' } } ';
		}
	}

	echo "</style>\n";

}
add_action('wp_head','vh_css_settings', 99);

if (!function_exists('vh_posted_on')) {

	// Prints HTML with meta information for the current post.
	function vh_posted_on() {
		printf(__('<span>Posted: </span><a href="%1$s" title="%2$s" rel="bookmark">%4$s</a><span class="by-author"> by <a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span>', 'vh'),
			esc_url(get_permalink()),
			esc_attr(get_the_time()),
			esc_attr(get_the_date('c')),
			esc_html(get_the_date()),
			esc_url(get_author_posts_url(get_the_author_meta('ID'))),
			sprintf(esc_attr__('View all posts by %s', 'vh'), get_the_author()),
			esc_html(get_the_author())
		);
	}
}

function clear_nav_menu_item_id($id, $item, $args) {
	return "";
}
add_filter('nav_menu_item_id', 'clear_nav_menu_item_id', 10, 3);

function add_nofollow_cat( $text ) {
	$text = str_replace('rel="category"', "", $text);
	return $text;
}
add_filter( 'the_category', 'add_nofollow_cat' );

function ajax_contact() {
	if(!empty($_POST)) {
		$sitename = get_bloginfo('name');
		$siteurl  = home_url();
		$to       = isset($_POST['contact_to'])? trim($_POST['contact_to']) : '';
		$name     = isset($_POST['contact_name'])? trim($_POST['contact_name']) : '';
		$email    = isset($_POST['contact_email'])? trim($_POST['contact_email']) : '';
		$content  = isset($_POST['contact_content'])? trim($_POST['contact_content']) : '';

		$error = false;
		$error = ($to === '' || $email === '' || $content === '' || $name === '') ||
				 (!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $email)) ||
				 (!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $to));

		if($error == false) {
			$subject = "$sitename message from $name";
			$body    = "Site: $sitename ($siteurl) \n\nName: $name \n\nEmail: $email \n\nMessage: $content";
			$headers = "From: $name ($sitename) <$email>\r\nReply-To: $email\r\n";
			$sent    = wp_mail($to, $subject, $body, $headers);

			// If sent
			if ($sent) {
				echo 'sent';
				die();
			} else {
				echo 'error';
				die();
			}
		} else {
			echo _e('Please fill all fields!', 'vh');
			die();
		}
	}
}
add_action('wp_ajax_nopriv_contact_form', 'ajax_contact');
add_action('wp_ajax_contact_form', 'ajax_contact');

function addhttp($url) {
	if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
		$url = "http://" . $url;
	}
	return $url;
}

function checkShortcode($string) {
	global $post;
	if (isset($post->post_content) && false !== stripos($post->post_content, $string)) {
		return true;
	} else {
		return false;
	}
}

// custom comment fields
function vh_custom_comment_fields($fields) {
	global $post, $commenter;

	$fields['author'] = '<div class="comment_auth_email"><p class="comment-form-author">
							<input id="author" name="author" type="text" class="span4" placeholder="' . __( 'Name', 'vh' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" aria-required="true" size="30" />
						 </p>';

	$fields['email'] = '<p class="comment-form-email">
							<input id="email" name="email" type="text" class="span4" placeholder="' . __( 'Email', 'vh' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" aria-required="true" size="30" />
						</p></div>';

	$fields['url'] = '<p class="comment-form-url">
						<input id="url" name="url" type="text" class="span4" placeholder="' . __( 'Website', 'vh' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />
						</p>';

	$fields = array( $fields['author'], $fields['email'] );
	return $fields;
}
add_filter( 'comment_form_default_fields', 'vh_custom_comment_fields' );

if ( ! function_exists( 'vh_comment' ) ) {
	/**
	 * Template for comments and pingbacks.
	 *
	 * To override this walker in a child theme without modifying the comments template
	 * simply create your own ac_comment(), and that function will be used instead.
	 *
	 * Used as a callback by wp_list_comments() for displaying the comments.
	 *
	 */
	function vh_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php _e( 'Pingback:', 'vh' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'vh' ), '<span class="edit-link button blue">', '</span>' ); ?></p>
		<?php
				break;
			default :
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment">
				<div class="comment-meta">
					<div class="comment-author vcard shadows">
						<?php
							$avatar_size = 70;
							echo get_avatar( $comment, $avatar_size );							
						?>
					</div><!-- .comment-author .vcard -->
				</div>

				<div class="comment-content">
					<?php echo '<h2>' . get_comment_author_link() . '</h2>' ?>
						<?php if ( $comment->comment_approved == '0' ) : ?>
						<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'vh' ); ?></em>
					<?php endif; ?>
					<?php comment_text(); ?>
					<div class="reply-edit-container">
						<span class="reply">
							<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'vh' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						</span><!-- end of reply -->
						<?php edit_comment_link( __( 'Edit', 'vh' ), '<span class="edit-link button blue">', '</span>' ); ?>
					</div>
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>
			</div><!-- end of comment -->

		<?php
				break;
		endswitch;
	}
}

function vh_breadcrumbs() {

	$disable_breadcrumb = get_option('vh_breadcrumb') ? get_option('vh_breadcrumb') : 'false';
	$delimiter          = get_option('vh_breadcrumb_delimiter') ? get_option('vh_breadcrumb_delimiter') : '<span class="delimiter">></span>';
	$custom_post = get_option('vh_breadcrumb_custom', 'movies:375');
	$array = explode(':',$custom_post);
	$array2 = array_chunk($array,2);
	$simple = 'true';

	$home   = 'Home'; // text for the 'Home' link
	$before = '<span class="current">'; // tag before the current crumb
	$after  = '</span>'; // tag after the current crumb

	if (!is_home() && !is_front_page() && $disable_breadcrumb == 'false') {
		global $post;
		$homeLink = home_url();

		$output = '<div class="breadcrumb">';
		$output .= '<a href="' . $homeLink . '">' . $home . '</a> ' . $delimiter . ' ';

		if (is_category()) {
			global $wp_query;
			$cat_obj   = $wp_query->get_queried_object();
			$thisCat   = $cat_obj->term_id;
			$thisCat   = get_category($thisCat);
			$parentCat = get_category($thisCat->parent);
			if ($thisCat->parent != 0)
				$output .= get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' ');
			$output .= $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
		} elseif (is_day()) {
			$output .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			$output .= '<a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			$output .= $before . get_the_time('d') . $after;
		} elseif (is_month()) {
			$output .= '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			$output .= $before . get_the_time('F') . $after;
		} elseif (is_year()) {
			$output .= $before . get_the_time('Y') . $after;
		} elseif (is_single() && !is_attachment()) {
			if (get_post_type() != 'post') {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				foreach ($array2 as $value) {
					if ( get_post_type() == $value[0] ) {
						$output .= '<a href="' . get_permalink( $value[1] ) . '">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
						$simple = 'false';
					}
				}
				if ($simple == 'true') {
					$output .= '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				}
				// $output .= '<a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a> ' . $delimiter . ' ';
				$output .= $before . get_the_title() . $after;
			} else {
				$cat = get_the_category();
				$cat = $cat[0];
				$output .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
				$output .= $before . get_the_title() . $after;
			}
		} elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) {
			$post_type = get_post_type_object(get_post_type());
			$output .= $before . $post_type->labels->singular_name . $after;
		} elseif (is_attachment()) {
			$parent = get_post($post->post_parent);
			$cat    = get_the_category($parent->ID);
			if ( isset($cat[0]) ) {
				$cat = $cat[0];
			}

			//$output .= get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
			$output .= '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a> ' . $delimiter . ' ';
			$output .= $before . get_the_title() . $after;
		} elseif (is_page() && !$post->post_parent) {
			$output .= $before . get_the_title() . $after;
		} elseif (is_page() && $post->post_parent) {
			$parent_id   = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page          = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id     = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			foreach ($breadcrumbs as $crumb) {
				$output .= $crumb . ' ' . $delimiter . ' ';
			}
			$output .= $before . get_the_title() . $after;
		} elseif (is_search()) {
			$output .= $before . 'Search results for "' . get_search_query() . '"' . $after;
		} elseif (is_tag()) {
			$output .= $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
		} elseif (is_author()) {
			global $vh_author;
			$userdata = get_userdata($vh_author);
			$output .= $before . 'Articles posted by ' . get_the_author() . $after;
		} elseif (is_404()) {
			$output .= $before . 'Error 404' . $after;
		}

		if (get_query_var('paged')) {
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
				$output .= ' (';
			$output .= __('Page', 'vh') . ' ' . get_query_var('paged');
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author())
				$output .= ')';
		}

		$output .= '</div>';

		return $output;
	}
}

/*
 * This theme supports custom background color and image, and here
 * we also set up the default background color.
 */
add_theme_support( 'custom-background', array(
	'default-color' => 'fff'
) );

/**
 * Add postMessage support for the Theme Customizer.
 */
function vh_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	// $wp_customize->add_section( 'color_scheme_section', array(
	// 	'title'    => __( 'Color Scheme', 'vh' ),
	// 	'priority' => 35,
	// ) );

	// $wp_customize->add_setting( 'vh_color_scheme', array(
	// 	'default'   => 'default-color-scheme',
	// 	'transport' => 'postMessage'
	// ) );

	// $wp_customize->add_control( new Customize_Scheme_Control( $wp_customize, 'vh_color_scheme', array(
	// 	'label'    => 'Choose color scheme',
	// 	'section'  => 'color_scheme_section',
	// 	'settings' => 'vh_color_scheme',
	// ) ) );
}
add_action( 'customize_register', 'vh_customize_register' );

/**
 * Binds CSS and JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function vh_customize_preview_js_css() {
	wp_enqueue_script( 'vh-customizer-js', get_template_directory_uri() . '/functions/admin/js/theme-customizer.js', array( 'jquery', 'customize-preview' ), '', true );
}
add_action( 'customize_preview_init', 'vh_customize_preview_js_css' );

if (class_exists('WP_Customize_Control')) {
	class Customize_Scheme_Control extends WP_Customize_Control {
		public $type = 'radio';

		public function render_content() {
		?>
			<style>

				/* Customizer */
				.input_hidden {
					position: absolute;
					left: -9999px;
				}

				.radio-images img {
					margin-right: 4px;
					border: 2px solid #fff;
				}

				.radio-images img.selected {
					border: 2px solid #888;
					border-radius: 5px;
				}

				.radio-images label {
					display: inline-block;
					cursor: pointer;
				}
			</style>
			<script type="text/javascript">
				jQuery('.radio-images input:radio').addClass('input_hidden');
				jQuery('.radio-images img').live('click', function() {
					jQuery('.radio-images img').removeClass('selected');
					jQuery(this).addClass('selected');
				});
			</script>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class="radio-images">
				<input type="radio" class="input_hidden" name="vh_color_scheme" <?php $this->link(); ?> id="default-color-scheme" value="default-color-scheme" />
				<label for="default-color-scheme">
					<img src="<?php echo get_template_directory_uri() . '/functions/admin/images/schemes/color-scheme-default.png'; ?>"<?php echo ( $this->value() == 'default-color-scheme' ) ? ' checked="checked" class="selected"' : ''; ?> style="width: 50px; height: 50px;" alt="Default Color Scheme" />
				</label>
				<input type="radio" class="input_hidden" name="vh_color_scheme" <?php $this->link(); ?> id="green-color-scheme" value="green-color-scheme" />
				<label for="green-color-scheme">
					<img src="<?php echo get_template_directory_uri() . '/functions/admin/images/schemes/color-scheme-green.png'; ?>"<?php echo ( $this->value() == 'green-color-scheme' ) ? ' checked="checked" class="selected"' : ''; ?> style="width: 50px; height: 50px;" alt="Green Color Scheme" />
				</label>
				<input type="radio" class="input_hidden" name="vh_color_scheme" <?php $this->link(); ?> id="red-color-scheme" value="red-color-scheme" />
				<label for="red-color-scheme">
					<img src="<?php echo get_template_directory_uri() . '/functions/admin/images/schemes/color-scheme-red.png'; ?>"<?php echo ( $this->value() == 'red-color-scheme' ) ? ' checked="checked" class="selected"' : ''; ?> style="width: 50px; height: 50px;" alt="Red Color Scheme" />
				</label>
				<input type="radio" class="input_hidden" name="vh_color_scheme" <?php $this->link(); ?> id="yellow-color-scheme" value="yellow-color-scheme" />
				<label for="yellow-color-scheme">
					<img src="<?php echo get_template_directory_uri() . '/functions/admin/images/schemes/color-scheme-yellow.png'; ?>"<?php echo ( $this->value() == 'yellow-color-scheme' ) ? ' checked="checked" class="selected"' : ''; ?> style="width: 50px; height: 50px;" alt="Yellow Color Scheme" />
				</label>
				<input type="radio" class="input_hidden" name="vh_color_scheme" <?php $this->link(); ?> id="gray-color-scheme" value="gray-color-scheme" />
				<label for="gray-color-scheme">
					<img src="<?php echo get_template_directory_uri() . '/functions/admin/images/schemes/color-scheme-gray.png'; ?>"<?php echo ( $this->value() == 'gray-color-scheme' ) ? ' checked="checked" class="selected"' : ''; ?> style="width: 50px; height: 50px;" alt="Gray Color Scheme" />
				</label>
			</div>
		<?php
		}
	}
}

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function vh_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
		array(
			'name'     				=> 'Seatera Functionality', // The plugin name
			'slug'     				=> 'seatera-plugin', // The plugin slug (typically the folder name)
			'source'   				=> get_stylesheet_directory() . '/functions/tgm-activation/plugins/seatera-plugin.zip', // The plugin source
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'Like Dislike Lite', // The plugin name
			'slug'     				=> 'like-dislike-counter-for-posts-pages-and-comments', // The plugin slug (typically the folder name)
			'source'   				=> get_stylesheet_directory() . '/functions/tgm-activation/plugins/like-dislike-counter-for-posts-pages-and-comments.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'               => 'WPBakery Visual Composer', // The plugin name
			'slug'               => 'js_composer', // The plugin slug (typically the folder name)
			'source'             => get_stylesheet_directory() . '/functions/tgm-activation/plugins/js_composer.zip', // The plugin source
			'required'           => true, // If false, the plugin is only 'recommended' instead of required
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url'       => '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'     				=> 'Slider Revolution', // The plugin name
			'slug'     				=> 'revslider', // The plugin slug (typically the folder name)
			'source'   				=> get_stylesheet_directory() . '/functions/tgm-activation/plugins/revslider.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'               => 'WP-PostRatings', // The plugin name
			'slug'               => 'wp-postratings', // The plugin slug (typically the folder name)
			'source'             => get_stylesheet_directory() . '/functions/tgm-activation/plugins/wp-postratings.zip', // The plugin source
			'required'           => false, // If false, the plugin is only 'recommended' instead of required
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url'       => '', // If set, overrides default API URL and points to an external URL
		),
		array(
			'name'               => 'Row Seats Core', // The plugin name
			'slug'               => 'row-seats-core', // The plugin slug (typically the folder name)
			'source'             => get_stylesheet_directory() . '/functions/tgm-activation/plugins/row-seats-core.zip', // The plugin source
			'required'           => true, // If false, the plugin is only 'recommended' instead of required
			'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url'       => '', // If set, overrides default API URL and points to an external URL
		)
	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> 'vh',         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> false,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', 'vh' ),
			'menu_title'                       			=> __( 'Install Plugins', 'vh' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'vh' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'vh' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'vh' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'vh' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'vh' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'vh_register_required_plugins' );