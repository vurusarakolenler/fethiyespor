<?php

add_action('init', 'of_options');

if (!function_exists('of_options')) {

	function of_options() {
		global $of_options, $vh_fonts_default_options;
		$options = array();

		$themename = THEMENAME;

		// Populate siteoptions option in array for use in theme
		$of_options               = get_option('of_options');
		$GLOBALS['template_path'] = VH_FUNCTIONS;

		$of_categories     = array();
		$of_categories_obj = get_categories('hide_empty=0');
		foreach ($of_categories_obj as $of_cat) {
			$of_categories[$of_cat->cat_ID] = $of_cat->cat_name;
		}
		$categories_tmp = array_unshift($of_categories, "Select a category:");

		// Access the WordPress Pages via an Array
		$of_pages = array();
		$of_pages_obj = get_pages('sort_column=post_parent,menu_order');
		foreach ($of_pages_obj as $of_page) {
			$of_pages[$of_page->ID] = $of_page->post_name;
		}
		$of_pages_tmp = array_unshift($of_pages, "Select the Blog page:");

		// Footer Columns Array
		$footer_columns = array("1", "2", "3", "4");

		// Time format array
		$time_format = array("12h", "24h");

		// Date format array
		$date_format = array("mm.dd.yy", "dd.mm.yy", "yy.mm.dd");

		// Paths for "type" => "images"
		$layout_style = VH_ADMIN_IMAGES . '/layout/';
		$framesurl    = VH_ADMIN_IMAGES . '/image-frames/';

		// Access the WordPress Categories via an Array
		$exclude_categories = array();
		$exclude_categories_obj = get_categories('hide_empty=0');
		foreach ($exclude_categories_obj as $exclude_cat) {
			$exclude_categories[$exclude_cat->cat_ID] = $exclude_cat->cat_name;
		}

		$footer_style = array('default', 'modern');

		$vh_fonts_default_options = array(
			'vh_primary_font_dark_font_face'   => 'Lato',
			'vh_primary_font_dark_weight'      => 'normal',
			'vh_primary_font_dark_font_size'   => 16,
			'vh_primary_font_dark_line_height' => 26,
			'vh_primary_font_dark_bg'          => '#fff',

			'vh_sidebar_font_dark_font_face'   => 'Lato',
			'vh_sidebar_font_dark_weight'      => 'normal',
			'vh_sidebar_font_dark_font_size'   => 16,
			'vh_sidebar_font_dark_line_height' => 26,
			'vh_sidebar_font_dark_bg'          => '#fff',

			'vh_headings_font_h1_font_face'   => 'Lato',
			'vh_headings_font_h1_weight'      => '300',
			'vh_headings_font_h1_font_size'   => 32,
			'vh_headings_font_h1_line_height' => 38,
			'vh_headings_font_h1_bg'          => '#fff',

			'vh_headings_font_h2_font_face'   => 'Lato',
			'vh_headings_font_h2_weight'      => '300',
			'vh_headings_font_h2_font_size'   => 22,
			'vh_headings_font_h2_line_height' => 33,
			'vh_headings_font_h2_bg'          => '#fff',

			'vh_headings_font_h3_font_face'   => 'Lato',
			'vh_headings_font_h3_weight'      => '300',
			'vh_headings_font_h3_font_size'   => 18,
			'vh_headings_font_h3_line_height' => 29,
			'vh_headings_font_h3_bg'          => '#fff',

			'vh_headings_font_h4_font_face'   => 'Lato',
			'vh_headings_font_h4_weight'      => '300',
			'vh_headings_font_h4_font_size'   => 18,
			'vh_headings_font_h4_line_height' => 40,
			'vh_headings_font_h4_bg'          => '#fff',

			'vh_headings_font_h5_font_face'   => 'Lato',
			'vh_headings_font_h5_weight'      => 'bold',
			'vh_headings_font_h5_font_size'   => 14,
			'vh_headings_font_h5_line_height' => 25,
			'vh_headings_font_h5_bg'          => '#fff',
			
			'vh_headings_font_h6_font_face'   => 'Lato',
			'vh_headings_font_h6_weight'      => 'bold',
			'vh_headings_font_h6_font_size'   => 12,
			'vh_headings_font_h6_line_height' => 24,
			'vh_headings_font_h6_bg'          => '#fff',
			
			'vh_links_font_font_face'   => 'Lato',
			'vh_links_font_weight'      => 'normal',
			'vh_links_font_font_size'   => 16,
			'vh_links_font_line_height' => 26,
			'vh_links_font_bg'          => '#fff',

			'vh_widget_font_face'   => 'Lato',
			'vh_widget_weight'      => 'normal',
			'vh_widget_font_size'   => 26,
			'vh_widget_line_height' => 29,
			'vh_widget_bg'          => '#fff',

			'vh_page_title_font_face'   => 'Lato',
			'vh_page_title_weight'      => '300',
			'vh_page_title_font_size'   => 40,
			'vh_page_title_line_height' => 34,
			'vh_page_title_bg'          => 'rgba(0,0,0,0.8)');

		// General options
		$options[] = array(
			"name"       => "General settings",
			"type"       => "heading",
			"menu_class" => "icon-cog-alt");

		$options[] = array(
			"name"  => "Website Logo",
			"desc"  => "Upload a custom logo for your Website.",
			"id"    => SHORTNAME . "_sitelogo",
			"order" => "",
			"type"  => "upload");

		$options[] = array(
			"name"  => "Website logo is Retina ready",
			"desc"  => "You have to uplaod website logo which is 2x in dimensions. It will automatically scaled down for normal displays and prepared for High resolution displays.",
			"id"    => SHORTNAME . "_website_logo_retina",
			"order" => "false",
			"type"  => "checkbox");

		$options[] = array(
			"name"  => "Default Layout",
			"desc"  => "Select a layout style.<br />(full, left side sidebar, right side sidebar)",
			"id"    => SHORTNAME . "_layout_style",
			"order" => "full",
			"type"  => "font-icons",
 			"options" => array(
 				'left' => 'icon-th-list',
				'full'  => 'icon-menu',
				'right' => 'icon-th-list-right'));

		$options[] = array(
			"name"  => "Thank you page ID",
			"desc"  => "ID of thank you page for Row Seats plugin.",
			"id"    => SHORTNAME . "_thank_you_page",
			"order" => "",
			"type"  => "text");

		$options[] = array(
			"name"  => "Time format",
			"desc"  => "Which time format to use when displaying time.",
			"id"    => SHORTNAME . "_time_format",
			"order" => "",
			"type"    => "select",
			"options" => $time_format);

		$options[] = array(
			"name"  => "Date format",
			"desc"  => "Which date format to use when displaying dates.",
			"id"    => SHORTNAME . "_date_format",
			"order" => "",
			"type"    => "select",
			"options" => $date_format);

		$options[] = array(
			"name"  => "Upcomming starting time",
			"desc"  => "Default starting time for upcomming times when new day is selected. Example - 7:00",
			"id"    => SHORTNAME . "_upcomming_time",
			"order" => "",
			"type"    => "text",
			"options" => "");

		$options[] = array(
			"name"  => "Date separator",
			"desc"  => "Which separator to use between dates.",
			"id"    => SHORTNAME . "_date_separator",
			"order" => ".",
			"type"    => "text");

		$options[] = array(
			"name"  => "Login Screen Logo",
			"desc"  => "Upload a custom logo.",
			"id"    => SHORTNAME . "_login_logo",
			"order" => "",
			"type"  => "upload");

		$options[] = array(
			"name"  => "Favicon",
			"desc"  => "Upload an image to use as your favicon",
			"id"    => SHORTNAME . "_favicon",
			"order" => "",
			"type"  => "upload");

		$options[] = array(
			"name"  => "Tracking Code or any other JavaScript",
			"desc"  => "Google Analytics tracking code or any other JavaScript",
			"id"    => SHORTNAME . "_tracking_code",
			"order" => "",
			"type"  => "textarea");

		// Close this group
		$options[] = array(
			"name" => "",
			"type" => "group_close");

		// Header options
		$options[] = array(
			"name"       => "Header settings",
			"type"       => "heading",
			"menu_class" => "icon-sitemap");

		$options[] = array("name" => "Twitter url",
			"desc" => "Your Twitter account url",
			"id" => SHORTNAME . "_header_twitter_url",
			"order" => "",
			"type" => "text");

		$options[] = array("name" => "Flickr url",
			"desc" => "Your Flickr account url",
			"id" => SHORTNAME . "_header_flickr_url",
			"order" => "",
			"type" => "text");

		$options[] = array("name" => "Facebook url",
			"desc" => "Your Facebook account url",
			"id" => SHORTNAME . "_header_facebook_url",
			"order" => "",
			"type" => "text");

		$options[] = array("name" => "Google+ url",
			"desc" => "Your Google+ account url",
			"id" => SHORTNAME . "_header_google_url",
			"order" => "",
			"type" => "text");

		$options[] = array("name" => "Pinterest url",
			"desc" => "Your Pinterest account url",
			"id" => SHORTNAME . "_header_pinterest_url",
			"order" => "",
			"type" => "text");

		// Close this group
		$options[] = array(
			"name" => "",
			"type" => "group_close");


		// Breadcrumb settings
		$options[] = array(
			"name"       => "Breadcrumb settings",
			"type"       => "heading",
			"menu_class" => "icon-menu");

		$options[] = array(
			"name"  => "Disable Breadcrumbs",
			"desc"  => "Breadcrumbs are shown by default",
			"id"    => SHORTNAME . "_breadcrumb",
			"order" => "false",
			"type"  => "checkbox");

		$options[] = array(
			"name"  => "Breadcrumb Delimiter",
			"desc"  => "This is the symbol that will appear in between your breadcrumbs",
			"id"    => SHORTNAME . "_breadcrumb_delimiter",
			"order" => "",
			"type"  => "text");

		$options[] = array(
			"name"  => "Custom post types",
			"desc"  => "You can point to which page ID custom post types should redirect. Example: movies:375:special_offers:123, where movies is custom post type and 375 is the page ID.",
			"id"    => SHORTNAME . "_breadcrumb_custom",
			"order" => "movies:375",
			"type"    => "text");

		// Close this group
		$options[] = array(
			"name" => "",
			"type" => "group_close");

		// Twitter widget options
		$options[] = array(
			"name"       => "Twitter widget settings",
			"type"       => "heading",
			"menu_class" => "icon-twitter");

		$options[] = array(
			"name"  => "Consumer key",
			"desc"  => "Please enter your Twitter API consumer key",
			"id"    => SHORTNAME . "_twitter_consumer_key",
			"order" => "",
			"type"  => "text");

		$options[] = array(
			"name"  => "Consumer secret",
			"desc"  => "Please enter your Twitter API consumer secret",
			"id"    => SHORTNAME . "_twitter_consumer_secret",
			"order" => "",
			"type"  => "text");

		$options[] = array(
			"name"  => "User token",
			"desc"  => "Please enter your Twitter API User token",
			"id"    => SHORTNAME . "_twitter_user_token",
			"order" => "",
			"type"  => "text");

		$options[] = array(
			"name"  => "User secret",
			"desc"  => "Please enter your Twitter API User secret",
			"id"    => SHORTNAME . "_twitter_user_secret",
			"order" => "",
			"type"  => "text");

		// Close this group
		$options[] = array(
			"name" => "",
			"type" => "group_close");

		// CSS options
		$options[] = array(
			"name"       => "CSS settings",
			"type"       => "heading",
			"menu_class" => "icon-css");

		$options[] = array(
			"name"  => "Custom CSS",
			"desc"  => "Custom CSS to your website",
			"id"    => SHORTNAME . "_custom_css",
			"order" => "",
			"type"  => "textarea");

		// Close this group
		$options[] = array(
			"name" => "",
			"type" => "group_close");

		// Javascript options
		$options[] = array(
			"name"       => "JavaScript settings",
			"type"       => "heading",
			"menu_class" => "icon-code-1");

		$options[] = array(
			"name"  => "Google Maps API Key (v3)",
			"desc"  => "Enter your Google Maps API Key (v3)",
			"id"    => SHORTNAME . "_google_maps_api_key",
			"order" => "",
			"type"  => "text");

		// Close this group
		$options[] = array(
			"name" => "",
			"type" => "group_close");

		// Typography options
		$options[] = array(
			"name"       => "Typography",
			"type"       => "heading",
			"menu_class" => "icon-font");

		$options[] = array(
			"name"   => "Primary font",
			"desc"   => "Primary font dark style",
			"id"     => SHORTNAME . "_primary_font_dark",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "25",
			"min_ln" => "8",
			"max_ln" => "50",
			"color"  => "666666");

		$options[] = array(
			"name"   => "Sidebar font",
			"desc"   => "Sidebar font dark style",
			"id"     => SHORTNAME . "_sidebar_font_dark",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "25",
			"min_ln" => "8",
			"max_ln" => "50",
			"color"  => "666666");

		$options[] = array(
			"name"   => "Headings font (H1)",
			"desc"   => "Headings font style",
			"id"     => SHORTNAME . "_headings_font_h1",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "70",
			"min_ln" => "8",
			"max_ln" => "80",
			"color"  => "c50505");

		$options[] = array(
			"name"   => "Headings font (H2)",
			"desc"   => "Headings font style",
			"id"     => SHORTNAME . "_headings_font_h2",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "50",
			"min_ln" => "8",
			"max_ln" => "70",
			"color"  => "000000");

		$options[] = array(
			"name"   => "Headings font (H3)",
			"desc"   => "Headings font style",
			"id"     => SHORTNAME . "_headings_font_h3",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "35",
			"min_ln" => "8",
			"max_ln" => "50",
			"color"  => "000000");

		$options[] = array(
			"name"   => "Headings font (H4)",
			"desc"   => "Headings font style",
			"id"     => SHORTNAME . "_headings_font_h4",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "35",
			"min_ln" => "8",
			"max_ln" => "50",
			"color"  => "666666");

		$options[] = array(
			"name"   => "Headings font (H5)",
			"desc"   => "Headings font style",
			"id"     => SHORTNAME . "_headings_font_h5",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "35",
			"min_ln" => "8",
			"max_ln" => "50",
			"color"  => "666666");

		$options[] = array(
			"name"   => "Headings font (H6)",
			"desc"   => "Headings font style",
			"id"     => SHORTNAME . "_headings_font_h6",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "35",
			"min_ln" => "8",
			"max_ln" => "50",
			"color"  => "000000");

		$options[] = array(
			"name"   => "Normal links font",
			"desc"   => "Normal links font style",
			"id"     => SHORTNAME . "_links_font",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "35",
			"min_ln" => "8",
			"max_ln" => "50",
			"color"  => "cc0000");

		$options[] = array(
			"name"   => "Widget title font",
			"desc"   => "Widget title font style",
			"id"     => SHORTNAME . "_widget",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "35",
			"min_ln" => "8",
			"max_ln" => "50",
			"color"  => "c50505");

		$options[] = array(
			"name"   => "Page title font",
			"desc"   => "Page title font style",
			"id"     => SHORTNAME . "_page_title",
			"order"  => "",
			"type"   => "font",
			"min_px" => "8",
			"max_px" => "85",
			"min_ln" => "8",
			"max_ln" => "125",
			"color"  => "fff");

		// Close this group
		$options[] = array(
			"name" => "",
			"type" => "group_close");

		// Footer options
		$options[] = array(
			"name"       => "Footer settings",
			"type"       => "heading",
			"menu_class" => "icon-sitemap");

		$options[] = array(
			"name"    => "Footer Columns",
			"desc"    => "Enter number of columns you would like to display in the footer",
			"id"      => SHORTNAME . "_footer_columns",
			"order"   => "4",
			"type"    => "select",
			"options" => $footer_columns,
			"type"  => "select");

		$options[] = array(
			"name"  => "Footer copyright",
			"desc"  => "Add copyright text which you would like to display in the footer",
			"id"    => SHORTNAME . "_footer_copyright",
			"order" => "&copy; [year], Seatera by <a href='http://cohhe.com'>Cohhe</a>",
			"type"  => "text");

		$options[] = array(
			"name"  => "Scroll to top",
			"desc"  => "Show scroll to top image?",
			"id"    => SHORTNAME . "_scroll_to_top",
			"order" => "",
			"type"  => "checkbox");

		// Close this group
		$options[] = array(
			"name" => "",
			"type" => "group_close");

		// 404 page settings
		$options[] = array(
			"name"       => "404 page",
			"type"       => "heading",
			"menu_class" => "icon-attention-1");

		$options[] = array(
			"name"   => "404 Page Title",
			"desc"   => "Set the page title that is displayed on the 404 Error Page",
			"id"     => SHORTNAME . "_404_title",
			"order"  => "This is somewhat embarrassing, isn't it?",
			"type"   => "text",
			"slider" => "no");

		$options[] = array(
			"name"  => "404 Message",
			"desc"  => "Set the message that is displayed on the 404 Error Page",
			"id"    => SHORTNAME . "_404_message",
			"order" => "It seems we can't find what you're looking for. Perhaps searching, or one of the links below, can help.",
			"type"  => "textarea");

		update_option('of_template', $options);
		update_option('of_themename', $themename);
		update_option('of_shortname', SHORTNAME);
	}
}