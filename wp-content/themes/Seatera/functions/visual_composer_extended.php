<?php
/*
* Extended Visual Composer plugin
*/

// Remove sidebar element
vc_remove_element("vc_widget_sidebar");
vc_remove_element("vc_images_carousel");
vc_remove_element("vc_toggle");
vc_remove_element("vc_tour");
vc_remove_element("vc_carousel");
vc_remove_element("vc_cta_button");

// Remove default WordPress widgets
vc_remove_element("vc_wp_search");
vc_remove_element("vc_wp_meta");
vc_remove_element("vc_wp_recentcomments");
vc_remove_element("vc_wp_calendar");
vc_remove_element("vc_wp_pages");
vc_remove_element("vc_wp_tagcloud");
vc_remove_element("vc_wp_custommenu");
vc_remove_element("vc_wp_text");
vc_remove_element("vc_wp_posts");
vc_remove_element("vc_wp_links");
vc_remove_element("vc_wp_categories");
vc_remove_element("vc_wp_archives");
vc_remove_element("vc_wp_rss");

vc_add_param("vc_row", array(
	"type"        => "dropdown",
	"class"       => "",
	"heading"     => __("Row style", "vh"),
	"admin_label" => true,
	"param_name"  => "type",
	"value"       => array(
		__( "Default", "vh" )           => "0",
		__( "Full Width - White", "vh" ) => "1"
	),
	"description" => ""
));

// Gap
vc_map( array(
	"name"     => __( "Gap", "vh" ),
	"base"     => "vh_gap",
	"icon"     => "icon-wpb-ui-gap-content",
	"class"    => "vh_vc_sc_gap",
	"category" => __( "by Seatera", "vh" ),
	"params"   => array(
		array(
			"type"        => "textfield",
			"class"       => "",
			"heading"     => __( "Gap height", "vh" ),
			"admin_label" => true,
			"param_name"  => "height",
			"value"       => "10",
			"description" => __( "In pixels", "vh" )
		)
	)
) );

$colors_arr = array(
	__("Red", "vh")    => "red",
	__("Blue", "vh")   => "blue",
	__("Yellow", "vh") => "yellow",
	__("Green", "vh")  => "green"
);

// Pricing table
wpb_map( array(
		"name"      => __( "Pricing Table", "vh" ),
		"base"      => "vh_pricing_table",
		"class"     => "vh-pricing-tables-class",
		"icon"      => "icon-wpb-ui-pricing_table-content",
		"category"  => __( "by Seatera", "vh" ),
		"params"    => array(
			array(
				"type"        => "dropdown",
				"heading"     => __("Color", "vh"),
				"param_name"  => "pricing_block_color",
				"value"       => $colors_arr,
				"description" => __("Pricing block color.", "vh")
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Title", "vh" ),
				"param_name"  => "pricing_title",
				"value"       => "",
				"description" => __( "Please add offer title.", "vh" )
			),
			array(
				"type"        => "textarea",
				"heading"     => __( "Description text 1", "vh" ),
				"param_name"  => "content1",
				"value"       => "",
				"description" => __( "Please add first part of your offer text.", "vh" )
			),
			array(
				"type"        => "textarea_html",
				"heading"     => __( "Description text 2", "vh" ),
				"param_name"  => "content2",
				"value"       => "",
				"description" => __( "Please add second part of your offer text.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Price", "vh" ),
				"param_name"  => "price",
				"value"       => "",
				"description" => __( "Please add offer price.", "vh" )
			),
			array(
				"type"        => "vc_link",
				"heading"     => __( "", "vh" ),
				"param_name"  => "button_link",
				"value"       => "",
				"description" => __( "Please add offer button link.", "vh" )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Extra class name", "vh" ),
				"param_name"  => "el_class",
				"value"       => "",
				"description" => __( "If you wish to style particular content element differently, then use this field to add a class name.", "vh" )
			)
		)
	)
);

// Update Buttons map
$colors_arr = array(__("Transparent", "vh") => "btn-transparent", __("Blue", "vh") => "btn-primary", __("Light Blue", "vh") => "btn-info", __("Green", "vh") => "btn-success", __("Yellow", "vh") => "btn-warning", __("Red", "vh") => "btn-danger", __("Inverse", "vh") => "btn-inverse");

$icons_arr = array(
	__("None", "vh")                     => "none",
	__("Address book icon", "vh")        => "wpb_address_book",
	__("Alarm clock icon", "vh")         => "wpb_alarm_clock",
	__("Anchor icon", "vh")              => "wpb_anchor",
	__("Application Image icon", "vh")   => "wpb_application_image",
	__("Arrow icon", "vh")               => "wpb_arrow",
	__("Asterisk icon", "vh")            => "wpb_asterisk",
	__("Hammer icon", "vh")              => "wpb_hammer",
	__("Balloon icon", "vh")             => "wpb_balloon",
	__("Balloon Buzz icon", "vh")        => "wpb_balloon_buzz",
	__("Balloon Facebook icon", "vh")    => "wpb_balloon_facebook",
	__("Balloon Twitter icon", "vh")     => "wpb_balloon_twitter",
	__("Battery icon", "vh")             => "wpb_battery",
	__("Binocular icon", "vh")           => "wpb_binocular",
	__("Document Excel icon", "vh")      => "wpb_document_excel",
	__("Document Image icon", "vh")      => "wpb_document_image",
	__("Document Music icon", "vh")      => "wpb_document_music",
	__("Document Office icon", "vh")     => "wpb_document_office",
	__("Document PDF icon", "vh")        => "wpb_document_pdf",
	__("Document Powerpoint icon", "vh") => "wpb_document_powerpoint",
	__("Document Word icon", "vh")       => "wpb_document_word",
	__("Bookmark icon", "vh")            => "wpb_bookmark",
	__("Camcorder icon", "vh")           => "wpb_camcorder",
	__("Camera icon", "vh")              => "wpb_camera",
	__("Chart icon", "vh")               => "wpb_chart",
	__("Chart pie icon", "vh")           => "wpb_chart_pie",
	__("Clock icon", "vh")               => "wpb_clock",
	__("Fire icon", "vh")                => "wpb_fire",
	__("Heart icon", "vh")               => "wpb_heart",
	__("Mail icon", "vh")                => "wpb_mail",
	__("Play icon", "vh")                => "wpb_play",
	__("Shield icon", "vh")              => "wpb_shield",
	__("Video icon", "vh")               => "wpb_video"
);

$target_arr = array(__("Same window", "vh") => "_self", __("New window", "vh") => "_blank");
$size_arr = array(__("Regular size", "vh") => "wpb_regularsize", __("Large", "vh") => "btn-large", __("Small", "vh") => "btn-small", __("Mini", "vh") => "btn-mini");

vc_map( array(
  "name" => __("Button", "vh"),
  "base" => "vc_button",
  "icon" => "icon-wpb-ui-button",
  "category" => __('Content', 'vh'),
  "params" => array(
	array(
	  "type" => "textfield",
	  "heading" => __("Text on the button", "vh"),
	  "holder" => "button",
	  "class" => "wpb_button",
	  "param_name" => "title",
	  "value" => __("Text on the button", "vh"),
	  "description" => __("Text on the button.", "vh")
	),
	array(
	  "type" => "textfield",
	  "heading" => __("URL (Link)", "vh"),
	  "param_name" => "href",
	  "description" => __("Button link.", "vh")
	),
	array(
	  "type" => "dropdown",
	  "heading" => __("Target", "vh"),
	  "param_name" => "target",
	  "value" => $target_arr,
	  "dependency" => Array('element' => "href", 'not_empty' => true)
	),
	array(
	  "type" => "dropdown",
	  "heading" => __("Color", "vh"),
	  "param_name" => "color",
	  "value" => $colors_arr,
	  "description" => __("Button color.", "vh")
	),
	array(
	  "type" => "dropdown",
	  "heading" => __("Icon", "vh"),
	  "param_name" => "icon",
	  "value" => $icons_arr,
	  "description" => __("Button icon.", "vh")
	),
	array(
	  "type" => "dropdown",
	  "heading" => __("Size", "vh"),
	  "param_name" => "size",
	  "value" => $size_arr,
	  "description" => __("Button size.", "vh")
	),
	array(
	  "type" => "textfield",
	  "heading" => __("Extra class name", "vh"),
	  "param_name" => "el_class",
	  "description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "vh")
	)
  ),
  "js_view" => 'VcButtonView'
) );

$event_categorie = get_terms('event_categories',array('fields'=>'names'));
foreach ($event_categorie as $value) {
	$event_categories[] = $value;
}
// Spotlight
vc_map( array(
	"name" => __("Spotlight", "vh" ),
	"base" => "vh_spotlight",
	"class" => "",
	"icon" => "icon-wpb-ui-gap-content",
	"category" => __( "by Seatera", "vh" ),

	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title", "vh" ),
			"param_name" => "spotlight_title",
			"value" => "",
			"description" => __("Enter title for this module.", "vh" )
			),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Category", "vh" ),
			"param_name" => "spot_category",
			"value" => "",
			"description" => __("Which category posts to show. Multiple categories separated with comma can be used.", "vh" )
			)
		)
) );

$offers_categorie = get_terms('offers_categories',array('fields'=>'names'));
$offer_categories = array();
foreach ($offers_categorie as $value) {
	$offer_categories[] = $value;
}
// Special offers
vc_map( array(
	"name" => __("Special offers", "vh" ),
	"base" => "vh_offers",
	"class" => "",
	"icon" => "icon-wpb-ui-gap-content",
	"category" => __( "by Seatera", "vh" ),

	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title", "vh" ),
			"param_name" => "offers_title",
			"value" => "",
			"description" => __("Enter title for this module.", "vh" )
			),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => __("Category", "vh" ),
			"param_name" => "offers_category",
			"value" => $offer_categories,
			"description" => __("Which category offers to show.", "vh" )
			),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Offer limit", "vh" ),
			"param_name" => "offers_limit",
			"value" => '0',
			"description" => __("How many offers to show from category listed above.", "vh" )
			),
		array(
			"type" => "textfield",
			"heading" => __("Carousel autoplay", "vh"),
			"param_name" => "offers_c_autoplay",
			"value" => 'false',
			"description" => __("Will carousel autoplay when page loads. You can use true/false/time in ms", "vh" )
			),
		array(
			"type" => "textfield",
			"heading" => __("Carousel speed", "vh"),
			"param_name" => "offers_c_speed",
			"value" => '2000',
			"description" => __("Carousel animation speed, default 2000ms", "vh" )
			)
		)
) );

// Theatres
vc_map( array(
	"name" => __("Showing in theatres", "vh" ),
	"base" => "vh_movies",
	"class" => "",
	"icon" => "icon-wpb-ui-gap-content",
	"category" => __( "by Seatera", "vh" ),

	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title", "vh" ),
			"param_name" => "theatres_title",
			"value" => "",
			"description" => __("Enter title for this module.", "vh" )
			),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Date count for dropdown.", "vh" ),
			"param_name" => "theatres_date",
			"value" => "",
			"description" => __("Enter how many dates to show at the dropdown. Leave blank for 7.", "vh" )
			),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Time count for dropdown.", "vh" ),
			"param_name" => "theatres_time",
			"value" => "",
			"description" => __("Enter how many time entries to show at the dropdown. Leave blank for 7.", "vh" )
			),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Event limit.", "vh" ),
			"param_name" => "theatres_limit",
			"value" => "",
			"description" => __("Limit how much events to show. Leave blank for no limit.", "vh" )
			),
		array(
			"type" => "checkbox",
			"holder" => "div",
			"class" => "",
			"heading" => __("Show all events.", "vh" ),
			"param_name" => "theatres_all_events",
			"value" =>  array( 'Yes, show all events' => 1),
			"description" => __("Removes datepicker at frontend and shows all events.", "vh" )
			)
		)
) );

// Event tickets
vc_map( array(
	"name" => __("Event tickets", "vh" ),
	"base" => "vh_event_tickets",
	"class" => "",
	"icon" => "icon-wpb-ui-gap-content",
	"category" => __( "by Seatera", "vh" ),

	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title", "vh" ),
			"param_name" => "event_title",
			"value" => "",
			"description" => __("Enter title for this module.", "vh" )
			),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Max dates displayed", "vh" ),
			"param_name" => "event_max_dates",
			"value" => "7",
			"description" => __("Maximum dates displayed at the dropdown.", "vh" )
			),
		array(
			"type" => "checkbox",
			"holder" => "div",
			"class" => "",
			"heading" => __("Show all tickets.", "vh" ),
			"param_name" => "event_tickets",
			"value" =>  array( 'Yes, show all tickets' => 1),
			"description" => __("Removes datepicker at frontend and shows all tickets.", "vh" )
			)
		)
) );

// Movie list
vc_map( array(
	"name" => __("Movies list", "vh" ),
	"base" => "vh_movies_list",
	"class" => "",
	"icon" => "icon-wpb-ui-gap-content",
	"category" => __( "by Seatera", "vh" ),

	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title", "vh" ),
			"param_name" => "movies_list_title",
			"value" => "",
			"description" => __("Enter title for this module.", "vh" )
			),
		array(
			"type" => "checkbox",
			"holder" => "div",
			"class" => "",
			"heading" => __("Show all events.", "vh" ),
			"param_name" => "movies_list_events",
			"value" =>  array( 'Yes, show all events' => 1),
			"description" => __("Removes datepicker at frontend and shows all events.", "vh" )
			)
		)
) );

// Image gellery
vc_map( array(
	"name" => __("Event Gallery", "vh"),
	"base" => "vh_image_gallery",
	"icon" => "icon-wpb-images-stack",
	"category" => __( "by Seatera", "vh" ),
	"description" => __('Responsive image gallery', 'vh'),
	"params" => array(
		array(
			"type" => "textfield",
			"heading" => __("Widget title", "vh"),
			"param_name" => "title",
			"description" => __("Enter text which will be used as widget title. Leave blank if no title is needed.", "vh")
		),
		array(
			"type" => "textfield",
			"heading" => __("Images/videos", "vh"),
			"param_name" => "images",
			"value" => "",
			"description" => __("Select images/videos from media library.", "vh")
		),
		array(
			"type" => "textfield",
			"heading" => __("Image/video size", "vh"),
			"param_name" => "img_size",
			"description" => __("Enter image size. Example: size in pixels: 200x100 (Width x Height). Leave empty to use '170x170' size.", "vh")
		),
		array(
			"type" => "dropdown",
			"heading" => __("On click", "vh"),
			"param_name" => "onclick",
			"value" => array(__("Open prettyPhoto", "vh") => "link_image", __("Do nothing", "vh") => "link_no", __("Open custom link", "vh") => "custom_link"),
			"description" => __("Define action for onclick event if needed.", "vh")
		),
		array(
			"type" => "exploded_textarea",
			"heading" => __("Custom links", "vh"),
			"param_name" => "custom_links",
			"description" => __('Enter links for each slide here. Divide links with linebreaks (Enter).', 'vh'),
			"dependency" => Array('element' => "onclick", 'value' => array('custom_link'))
		),
		array(
			"type" => "dropdown",
			"heading" => __("Custom link target", "vh"),
			"param_name" => "custom_links_target",
			"description" => __('Select where to open custom links.', 'vh'),
			"dependency" => Array('element' => "onclick", 'value' => array('custom_link')),
			'value' => $target_arr
		),
		array(
			"type" => "textfield",
			"heading" => __("Extra class name", "vh"),
			"param_name" => "el_class",
			"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "vh")
		)
	)
  ) );

 // post_carousel
vc_map( array(
	"name" => __("Post carousel", "vh" ),
	"base" => "vh_post_carousel",
	"class" => "",
	"icon" => "icon-wpb-ui-gap-content",
	"category" => __( "by Seatera", "vh" ),

	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title", "vh" ),
			"param_name" => "post_carousel_title",
			"value" => "",
			"description" => __("Enter title for this module.", "vh" )
			),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Categories", "vh" ),
			"param_name" => "post_carousel_categories",
			"value" => "",
			"description" => __("Which category posts to show.", "vh" )
			),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Post count", "vh" ),
			"param_name" => "post_carousel_count",
			"value" => "",
			"description" => __("How many posts to show.", "vh" )
			),
		array(
			"type" => "textfield",
			"heading" => __("Carousel autoplay", "vh"),
			"param_name" => "post_c_autoplay",
			"value" => 'false',
			"description" => __("Will carousel autoplay when page loads. You can use true/false/time in ms", "vh" )
			),
		array(
			"type" => "textfield",
			"heading" => __("Carousel speed", "vh"),
			"param_name" => "post_c_speed",
			"value" => '2000',
			"description" => __("Carousel animation speed, default 2000ms", "vh" )
			)
		)
) );

?>