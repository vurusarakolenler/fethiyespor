<?php
/*
* Template Name: Thank you template
*/
get_header();
?>

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
						global $wpdb;
						$ticket_id = esc_attr($_GET['id']);
						if ( $ticket_id == '' ) {
							$ticket_id = '-1';
						}

						if ( $ticket_id == '-1' ) {
							_e('We can&#39;t display a ticket if a valid ticket ID isn&#39;t provided.','vh');
						} else {
						$results = $wpdb->get_results( 'SELECT * FROM rst_payment_transactions WHERE tx_str = ' . $ticket_id, OBJECT );
						$seat_count = count(explode(',', $results[0]->seat_numbers));

						$values = trim(get_post_meta( esc_attr($_GET['movie']), 'movie_time_values', true ),',');
						$values_arr = json_decode('['.$values.']',true);

						foreach ($values_arr as $value) {
								$times = explode(';',$value['time']);
								$place = explode(';',$value['place']);
								$ticket = explode(';',$value['ticket']);
								$theatre = $value['theatre'];

								foreach ($times as $times_key => $times_value) {
									$combined[$times_key] = $times_value;
								}

								foreach ($place as $place_key => $place_value) {
									$combined[$place_key] .= ';'.$place_value;
								}

								foreach ($ticket as $ticket_key => $ticket_value) {
									$combined[$ticket_key] .= ';'.$ticket_value;
								}

								foreach ($combined as $combined_key => $combined_value) {
									$combined[$combined_key] = $combined_value.';'.$theatre;
								}
								$movie_time = $movie_theatre = $movie_auditory = '';
								foreach ($combined as $combined_value) {
									$needed_info = explode(';', $combined_value);
									if ( $needed_info[2] == esc_attr(isset($_GET['event_id'])) ) {
										$movie_time = $needed_info[0];
										$movie_auditory = explode(' ', $needed_info[1]);
										$movie_theatre = $needed_info[3];
									}
									
								}
							
						}
						$seat_num = implode(',',explode(',', str_replace($results[0]->ticket_no.'-', '', $results[0]->seat_numbers)));

						$rst_paypal_options = get_option(RSTPLN_PPOPTIONS);
						$symbol = $rst_paypal_options['currencysymbol'];
						$symbol = get_option('rst_currencysymbol');
						
						//print $symbol."------------".$rst_options['rst_currency'];

						$symbols = array(
							"0" => "$",
							"1" => "&pound;",
							"2" => "&euro;",
							"3" => "&#3647;",
							"4" => "&#8362;",
							"5" => "&yen;");

						$symbol = $symbols[$symbol];
						$img = wp_get_attachment_image_src( get_post_thumbnail_id(esc_attr($_GET['movie'])), 'thank-you-image' );
						?>
						<h1 class="thank_you_title"><?php _e('Thank you', 'vh')?></h1>
						<div class="wpb_alert wpb_content_element vc_alert_square wpb_alert-success thank_you">
						<?php if ( get_option('vh_time_format') == false || get_option('vh_time_format') == '24h' ) { ?>
								<div class="messagebox_text"><p><?php _e('You have successfully purchased ', 'vh' ); echo $seat_count; _e(' tickets to a movie ', 'vh' ); echo $results[0]->show_name . ', ' . date('j.m.Y',strtotime($results[0]->show_date)) . ', ' . $movie_time . '.'; ?></p>
						<?php } else { ?>
								<div class="messagebox_text"><p><?php _e('You have successfully purchased ', 'vh' ); echo $seat_count; _e(' tickets to a movie ', 'vh' ); echo $results[0]->show_name . ', ' . date('j.m.Y',strtotime($results[0]->show_date)) . ', ' . date('h:i a', strtotime($movie_time)) . '.'; ?></p>
						<?php } ?>
							</div>
						</div>
						<div class="thank_you_container">
							<?php if ( kd_mfi_get_featured_image_id( 'event-poster', 'movies', esc_attr($_GET['movie']) ) != '' ) {
								$attachment_id = kd_mfi_get_featured_image_id( 'event-poster', 'movies', esc_attr($_GET['movie']) );
								$image = wp_get_attachment_image_src( $attachment_id, 'thank-you-image' ); ?>
								<div class="thank_you_movie shadows">
									<img src=" <?php echo $image[0]; ?>">
								</div>
							<?php } elseif ( has_post_thumbnail( esc_attr($_GET['movie']) ) ) {
								$image = wp_get_attachment_image_src( get_post_thumbnail_id( esc_attr($_GET['movie']) ), 'thank-you-image' ); ?>
								<div class="thank_you_movie shadows">
									<img src="<?php echo $image[0]; ?>">
								</div>
							<?php } ?>

							<div class="thank_you_info">
								<div class="thank_you_top">
									<div class="thank_you_info_entry">
										<div class="info_entry_title main"><?php _e('Movie:', 'vh')?></div>
										<div class="info_entry_value main"><?php echo $results[0]->show_name; ?></div>
									</div>
									<div class="clearfix"></div>
									<div class="thank_you_info_entry">
										<div class="info_entry_title main"><?php _e('Seats:', 'vh')?></div>
										<div class="info_entry_value main"><?php echo $seat_num; ?></div>
									</div>
									<div class="clearfix"></div>
									<div class="thank_you_info_entry">
										<div class="info_entry_title main"><?php _e('Price:', 'vh')?></div>
										<div class="info_entry_value main"><?php echo $symbol.$results[0]->gross; ?></div>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="thank_you_bottom">
									<div class="thank_you_info_entry">
										<div class="info_entry_title"><?php _e('Ticket number:', 'vh')?></div>
										<div class="info_entry_value"><?php echo $results[0]->ticket_no; ?></div>
									</div>
									<div class="clearfix"></div>
									<div class="thank_you_info_entry">
										<div class="info_entry_title"><?php _e('Date:', 'vh')?></div>
										<div class="info_entry_value"><?php echo date('j.m.Y',strtotime($results[0]->show_date)); ?></div>
									</div>
									<div class="clearfix"></div>
									<div class="thank_you_info_entry">
										<div class="info_entry_title"><?php _e('Time:', 'vh')?></div>
										<?php if ( get_option('vh_time_format') == false || get_option('vh_time_format') == '24h' ) { ?>
											<div class="info_entry_value"><?php echo $movie_time; ?></div>
										<?php } else { ?>
											<div class="info_entry_value"><?php echo date('h:i a', strtotime($movie_time)); ?></div>
										<?php } ?>
										
									</div>
									<div class="clearfix"></div>
									<div class="thank_you_info_entry">
										<div class="info_entry_title"><?php _e('Cinema:', 'vh')?></div>
										<div class="info_entry_value"><?php echo $movie_theatre; ?></div>
									</div>
									<div class="clearfix"></div>
									<div class="thank_you_info_entry">
										<div class="info_entry_title"><?php _e('Auditory:', 'vh')?></div>
										<div class="info_entry_value"><?php echo isset($movie_auditory[1]); ?></div>
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
						<?php } ?>
						<?php 
						while (have_posts()) {
							the_post();
							the_content();
						}
						?>
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