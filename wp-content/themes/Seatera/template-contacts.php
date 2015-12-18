<?php
/*
* Template Name: Contacts template
*/
get_header();

$vh_map_title   = (get_post_meta( $post->ID, 'vh_map_title', true )) ? '<h1 class="contacts_title">' . addslashes(get_post_meta( $post->ID, 'vh_map_title', true )) . '</h1>' : '';
$vh_map_lat     = (get_post_meta( $post->ID, 'vh_map_lat', true )) ? get_post_meta( $post->ID, 'vh_map_lat', true ) : '';
$vh_map_long    = (get_post_meta( $post->ID, 'vh_map_long', true )) ? get_post_meta( $post->ID, 'vh_map_long', true ) : '';
?>
<script type="text/javascript">
(function() {
	//var map, gMap = google.maps;
	window.onload = function() {

	var secheltLoc = new google.maps.LatLng(<?php echo $vh_map_lat; ?>, <?php echo $vh_map_long; ?>);

		var myMapOptions = {
			 zoom: 15,
			 center: secheltLoc,
			 scrollwheel: false,
			 mapTypeId: google.maps.MapTypeId.ROADMAP,
			 disableDefaultUI: true
		};
		var theMap = new google.maps.Map(document.getElementById("map"), myMapOptions);

		//var myIcon = new google.maps.MarkerImage("<?php echo get_template_directory_uri(); ?>/images/map-marker.png", null, null, null, new google.maps.Size(22,35));

		var marker = new google.maps.Marker({
			map: theMap,
			draggable: true,
			position: new google.maps.LatLng(<?php echo $vh_map_lat; ?>, <?php echo $vh_map_long; ?>),
			visible: true,
			//icon: myIcon
		});

		var myOptions = {
			// content: boxText,
			 disableAutoPan: false
			,maxWidth: 0
			,pixelOffset: new google.maps.Size(-215, -50)
			,zIndex: null
			,alignBottom: true
			,boxStyle: {
				background: ""
				,opacity: 1
				,width: "426px"
				,padding: "50px 0 4px 0"
			}
			,closeBoxURL: ""
			,infoBoxClearance: new google.maps.Size(222, 100)
			,isHidden: false
			,pane: "floatPane"
			,enableEventPropagation: false
		};

		// google.maps.event.addListener(marker, "click", function (e) {
		// 	ib.open(theMap, this);
		// });

		// var ib = new InfoBox(myOptions);
		// ib.setContent('<div class="infobox"><a href="#">Full size map</a></div>');
		// ib.open(theMap, marker);
	}
})();

</script>
<?php

$img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large-image' );

if ( LAYOUT == 'sidebar-no' ) {
	$span_size = 'span12';
} else {
	$span_size = 'span9';
}

?>
<div class="page-<?php echo LAYOUT; ?> page-wrapper">
	<div class="clearfix"></div>
	<div class="page_info">
		<?php
		if ( !is_front_page() && !is_home() ) { ?>
			<div class="page-title">
				<?php echo  the_title( '<h1>', '</h1>' ); ?>
			</div>
		<?php } ?>
		<?php
		if ( !is_front_page() && !is_home() ) {
			echo vh_breadcrumbs();
		} ?>
	</div>
	<div class="content wpb_row vc_row-fluid">
		<?php
		wp_reset_postdata();
		if (LAYOUT == 'sidebar-left') {
		?>
		<div class="vc_span3 <?php echo LAYOUT; ?>">
			<div class="sidebar-inner">
			<?php
				global $vh_is_in_sidebar;
				$vh_is_in_sidebar = true;
				generated_dynamic_sidebar();
			?>
			</div>
		</div><!--end of sidebars-->
		<?php } ?>
		<div class="<?php echo LAYOUT; ?>-pull <?php echo (LAYOUT != 'sidebar-no') ? 'vc_span9' : 'vc_span12'; ?>">
			<div class="main-content">
				<?php
				if ( isset($img[0]) ) { ?>
					<div class="entry-image">
						<img src="<?php echo $img[0]; ?>" class="open_entry_image <?php echo $span_size; ?>" alt="" />
					</div>
				<?php } ?>
				<div class="main-inner">
					<?php
					if (have_posts ()) {
						while (have_posts()) {
							the_post();
							the_content();
						}
						?>
						<?php echo $vh_map_title; ?>
						<div id="map"></div>
						<?php
					} else {
						echo '
							<h2>Nothing Found</h2>
							<p>Sorry, it appears there is no content in this section.</p>';
					}
					?>
				</div>
			</div>
		</div>
		<?php
		if (LAYOUT == 'sidebar-right') {
		?>
		<div class="vc_span3 pull-right <?php echo LAYOUT; ?>">
			<div class="sidebar-inner">
			<?php
				global $vh_is_in_sidebar;
				$vh_is_in_sidebar = true;
				generated_dynamic_sidebar();
			?>
			<div class="clearfix"></div>
			</div>
		</div><!--end of span3-->
		<?php } ?>
		<?php $vh_is_in_sidebar = false; ?>
		<div class="clearfix"></div>
	</div><!--end of content-->
	<div class="clearfix"></div>
</div><!--end of page-wrapper-->
<?php get_footer();