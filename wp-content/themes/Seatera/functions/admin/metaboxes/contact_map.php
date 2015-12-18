<?php
/*
 * Map on contact form options
 */

$config = array(
	'id'       => 'vh_map',
	'title'    => __('Google Map', 'vh'),
	'pages'    => array('page', 'post'),
	'context'  => 'normal',
	'priority' => 'high',
);

$options = array(array(
	'name' => __('Title', 'vh'),
	'id'   => 'vh_map_title',
	'type' => 'contact_map',
	'only' => 'page',
),array(
	'name' => __('Latitude', 'vh'),
	'id'   => 'vh_map_lat',
	'type' => 'contact_map',
	'only' => 'page',
),array(
	'name' => __('Longitude', 'vh'),
	'id'   => 'vh_map_long',
	'type' => 'contact_map',
	'only' => 'page',
));

$get_post    = isset( $_GET['post'] ) ? $_GET['post'] : NULL;
$get_post_id = isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : NULL;

$post_id       = !empty( $get_post ) ? $get_post : $get_post_id;
$template_file = get_post_meta( $post_id, '_wp_page_template', TRUE );

// Check for a template type
if ( $template_file == 'template-contacts.php' ) {
	require_once(VH_METABOXES . '/add_metaboxes.php');
	new create_meta_boxes($config, $options);
}