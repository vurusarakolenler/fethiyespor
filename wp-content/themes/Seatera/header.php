<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
	<head>
		<meta content="True" name="HandheldFriendly">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>">
		<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<?php
			global $vh_class;
			$logo_size_html = '';

			// Get theme logo
			$logo = get_option('vh_sitelogo');
			if($logo == false) {
				$logo = get_template_directory_uri() . '/images/logo.png';
			}

			// Get header bg image
			$favicon = get_option('vh_favicon');
			if ($favicon == false) {
				$favicon = get_template_directory_uri() . '/images/favicon.ico';
			}

			$website_logo_retina_ready = filter_var(get_option('vh_website_logo_retina'), FILTER_VALIDATE_BOOLEAN);
			if ((bool)$website_logo_retina_ready != false) {
				$logo_size = getimagesize($logo);
				$logo_size_html = ' style="height: ' . ($logo_size[1] / 2) . 'px;" height="' . ($logo_size[1] / 2) . '"';
			}

			// Social icons
			$menu_header_twitter_url   = get_option( 'vh_header_twitter_url' );
			$menu_header_flickr_url    = get_option( 'vh_header_flickr_url' );
			$menu_header_facebook_url  = get_option( 'vh_header_facebook_url' );
			$menu_header_google_url    = get_option( 'vh_header_google_url' );
			$menu_header_pinterest_url = get_option( 'vh_header_pinterest_url' );

		?>
		<link rel="shortcut icon" href="<?php echo $favicon; ?>" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class($vh_class); ?>>
		<div class="vh_wrapper" id="vh_wrappers">
		<div class="main-body-color"></div>
		<div class="secondary-body-color"></div>
		<div class="overlay-hide"></div>
		<div class="pushy pushy-left">
			<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary-menu',
						'menu_class'     => 'responsive-menu',
						'depth'          => 2,
						'link_before'    => '',
						'link_after'     => ''
					)
				);
			?>
		</div>
		<div class="wrapper st-effect-3 w_display_none" id="container">
			<div class="main">
				<?php
					$website_logo_retina_ready = filter_var(get_option('vh_website_logo_retina'), FILTER_VALIDATE_BOOLEAN);
					if ((bool)$website_logo_retina_ready != false) {
					$logo_size = getimagesize($logo);
					}
				?>
				<header class="header wpb_row vc_row-fluid">
					<div class="top-header vc_span12">
						<div class="logo shadows vc_span3">
							<a href="<?php echo home_url(); ?>"><img src="<?php echo $logo; ?>"<?php echo $logo_size_html ; ?> alt="<?php bloginfo('name'); ?>" /></a>
						</div>
						<div class="top-menu-container">
							<?php
								wp_nav_menu(
									array(
										'theme_location'  => 'primary-menu',
										'menu_class'      => 'header-menu',
										'container'       => 'div',
										'container_class' => 'menu-style',
										'depth'           => 2,
										'link_before'     => '',
										'link_after'      => ''
									)
								);
							?>
						</div>
						<div class="header_search"><?php get_search_form(); ?></div>
						<div class="menu-btn icon-menu-1"></div>
						<div class="header-social-icons vc_span3">
						<?php if (!empty($menu_header_twitter_url)) { ?>
							<div class="header-icon twitter-icon hover_right"><a href="<?php echo $menu_header_twitter_url; ?>" class="icon-twitter-1"></a></div>
						<?php } ?>
						<?php if (!empty($menu_header_flickr_url)) { ?>
							<div class="header-icon flickr-icon hover_right"><a href="<?php echo $menu_header_flickr_url; ?>" class="icon-flickr"></a></div>
						<?php } ?>
						<?php if (!empty($menu_header_facebook_url)) { ?>
							<div class="header-icon facebook-icon hover_right"><a href="<?php echo $menu_header_facebook_url; ?>" class="icon-facebook"></a></div>
						<?php } ?>
						<?php if (!empty($menu_header_google_url)) { ?>
							<div class="header-icon google-icon hover_right"><a href="<?php echo $menu_header_google_url; ?>" class="icon-gplus"></a></div>
						<?php } ?>
						<?php if (!empty($menu_header_pinterest_url)) { ?>
							<div class="header-icon pinterest-icon hover_right"><a href="<?php echo $menu_header_pinterest_url; ?>" class="icon-pinterest"></a></div>
						<?php } ?>
					</div>
						<div class="clearfix"></div>
					</div>
				</header><!--end of header-->
				<div id="loader" class="pageload-overlay" data-opening="M 0,0 80,-10 80,60 0,70 0,0" data-closing="M 0,-10 80,-20 80,-10 0,0 0,-10">
					<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
						<path d="M 0,70 80,60 80,80 0,80 0,70"/>
					</svg>
				</div>
				<div class="clearfix"></div>
				<?php if ( is_page_template('template-front-page.php') && class_exists('UniteBaseClassRev') ) {
					putRevSlider('movies');
				} ?>
				<?php
					wp_reset_postdata();
					$layout_type = get_post_meta(get_the_id(), 'layouts', true);

					if ( is_archive() || is_search() || is_404() || ( get_post_type() == 'tribe_events' && !is_single() ) ) {
						$layout_type = 'full';
					} else if ( is_home() ) {

						// Get the ID of your posts page
						$id = get_option('page_for_posts');

						$layout_type = get_post_meta($id, 'layouts', true) ? get_post_meta($id, 'layouts', true) : 'full';
					} elseif (empty($layout_type)) {
						$layout_type = get_option('vh_layout_style') ? get_option('vh_layout_style') : 'full';
					}

					switch ($layout_type) {
						case 'right':
							define('LAYOUT', 'sidebar-right');
							break;
						case 'full':
							define('LAYOUT', 'sidebar-no');
							break;
						case 'left':
							define('LAYOUT', 'sidebar-left');
							break;
					}