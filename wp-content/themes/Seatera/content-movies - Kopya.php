<?php
/**
 * The template for displaying content in the single-movies.php template
 *
 * @package WordPress
 * @subpackage Seatera
 */

global $vh_blog_image_layout;

$show_sep       = FALSE;
$style          = '';
$clear          = '';
$excerpt        = get_the_excerpt();
$top_left       = "";
$small_image    = FALSE;
$post_date_d    = get_the_date( 'd. M' );
$post_date_m    = get_the_date( 'Y' );
$is_author_desc = '';
$post_id = $post->ID;

$show_date = isset( $show_date ) ? $show_date : NULL;

if ( get_the_author_meta( 'description' ) ) { 
	$is_author_desc = ' is_author_desc';
}

// Determine blog image size
if ( LAYOUT == 'sidebar-no' ) {
	$clear     = ' style="float: none;"';
	$img_style = ' style="margin-left: 0;"';
} else {
	$small_image = TRUE;
	$img_style   = ' style="margin-left: 0;"';
}
$img           = wp_get_attachment_image_src( get_post_thumbnail_id(), 'movie_list' );
$entry_utility = '';

$entry_utility .= '
	<div class="entry-top-utility">';
	if ( 'post' == get_post_type() ) {

		$entry_utility .= '<div class="blog_like_dislike"><span class="post_dislikes icon-heart-broken"></span>';
		$entry_utility .= '<span class="post_likes icon-heart"></span></div>';


		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( __( ', ', 'vh' ) );
		if ( $categories_list ) {
			$entry_utility .= '
			<div class="category-link">
			<i class="entypo_icon icon-folder"></i>
			' . sprintf( __( '<span class="%1$s"></span> %2$s', 'vh' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
			$show_sep = TRUE;
			$entry_utility .= '
			</div>';
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', __( ', ', 'vh' ) );
		if ( $tags_list ) {
			$style = '';
			$entry_utility .= '
			<div class="tag-link"' . $style . '>
			<i class="entypo_icon icon-tags"></i>
			' . sprintf( __( '<span class="%1$s"></span> %2$s', 'vh' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
			$show_sep = true;
			$entry_utility .= '
			</div>';
		}
	}
	if ( $show_sep ) {
		$entry_utility .= '
		<div class="sep">&nbsp;</div>';
	}
	$entry_utility .= '
	<div class="clearfix"></div>
	</div>';
?>

<div class="entry no_left_margin first-entry <?php echo $is_author_desc; ?> <?php if ( !isset($img[0]) ) { echo ' no-image'; } ?><?php echo (LAYOUT != 'sidebar-no') ? ' vc_span12' : ' vc_span12'; ?>">
	<div class="entry-image vh_animate_when_almost_visible with_full_image <?php echo $vh_blog_image_layout . $is_author_desc; ?>"<?php echo $clear; ?>>
		<?php
		$i                 = 2;
		$posts_slideshow   = ( get_option('vh_posts_slideshow_number') ) ? get_option('vh_posts_slideshow_number') : 5;
		$attachments_count = 1;

		while( $i <= $posts_slideshow ) {
			$attachment_id = kd_mfi_get_featured_image_id( 'featured-image-' . $i, 'post' );
			if( $attachment_id ) {
				$attachments_count = ++$attachments_count;
			}
			$i++;
		}
		?>
		<div class="main_top">
			<div class="image_wrapper event shadows">
				<?php if ( kd_mfi_get_featured_image_id( 'event-poster', 'movies' ) != '' ) {
					$attachment_id = kd_mfi_get_featured_image_id( 'event-poster', 'movies' );
					$image = wp_get_attachment_image_src( $attachment_id, 'movie_list' ); ?>
					<img src="<?php echo $image[0]; ?> "<?php echo $img_style; ?> class="open_entry_image" alt="" />
				<?php } elseif ( has_post_thumbnail( get_post()->ID ) ) { ?>
					<img src="<?php echo $img[0]; ?> "<?php echo $img_style; ?> class="open_entry_image" alt="" />
				<?php } ?>
			</div>
			<div class="event_main_side">
				<div class="page_title event"><?php echo get_the_title(); ?></div>
				<?php if(function_exists('the_ratings')) { the_ratings(); } ?>
				<div class="overview-container">
					<div class="main_side_left">
						<?php 
						$categories        = wp_get_post_terms( get_the_ID(), 'event_categories',array("fields" => "names") );
						$categories_count  = count($categories);
						$categories_val    = 1;
						$categories_string = '';
						foreach ($categories as $value) {
							if ( $categories_val == $categories_count ) {
								$categories_string = $categories_string.$value;
							} else {
								$categories_string = $categories_string.$value.', ';
							}
							$categories_val++;
						}
							?>
							<?php if ( get_post_meta( get_the_ID(), 'event_country', true ) != '' ) { ?>
								<div class="event_list_item icon-globe"><div class="title left"><?php _e('Maç Yeri:','vh'); ?></div><div class="info event_country"><?php echo get_post_meta( get_the_ID(), 'event_country', true ); ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'event_year', true ) != '' ) { ?>
								<div class="event_list_item icon-circle-notch"><div class="title left"><?php _e('Year:','vh'); ?></div><div class="info event_year"><?php echo get_post_meta( get_the_ID(), 'event_year', true ); ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( $categories_string != '' ) { ?>
								<div class="event_list_item icon-tags"><div class="title left"><?php _e('Kategori:','vh'); ?></div><div class="info event_category"><?php echo $categories_string; ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'event_release', true ) != '' ) { ?>
								<div class="event_list_item icon-calendar"><div class="title left"><?php _e('Maç Tarihi:','vh'); ?></div><div class="info event_release"><?php echo get_post_meta( get_the_ID(), 'event_release', true ); ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'movies_length', true ) != '' ) { ?>
								<div class="event_list_item icon-clock"><div class="title left"><?php _e('Duration:','vh'); ?></div><div class="info movies_length"><?php echo vh_convertToHoursMins(get_post_meta( get_the_ID(), 'movies_length', true ), __('%2d hours %2d minutes', "vh" )); ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'event_writers', true ) != '' ) { ?>
								<div class="event_list_item icon-pencil"><div class="title right"><?php _e('Writers:','vh'); ?></div><div class="info event_writers"><?php echo get_post_meta( get_the_ID(), 'event_writers', true ); ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'event_imdb_rating', true ) != '' ) { ?>
								<div class="event_list_item icon-star">
									<div class="title right">
										<?php if ( get_post_meta( get_the_ID(), 'event_imdb_url', true ) != '' ) { ?>
											<a href="<?php echo get_post_meta( get_the_ID(), 'event_imdb_url', true ); ?>">
										<?php }
										_e('IMDb:','vh');
										if ( get_post_meta( get_the_ID(), 'event_imdb_url', true ) != '' ) { ?>
											</a>
										<?php } ?>
									</div>
									<div class="info event_imdb_rating">
										<?php echo get_post_meta( get_the_ID(), 'event_imdb_rating', true ); ?>
									</div>
									<div class="clearfix"></div>
								</div>
							<?php } ?>
					</div>
					<div class="main_side_right">
							<?php if ( get_post_meta( get_the_ID(), 'event_director', true ) != '' ) { ?>
								<div class="event_list_item icon-user"><div class="title right"><?php _e('Hakem:','vh'); ?></div><div class="info event_director"><?php echo get_post_meta( get_the_ID(), 'event_director', true ); ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'event_actors', true ) != '' ) { ?>
								<div class="event_list_item icon-users"><div class="title right"><?php _e('Actors:','vh'); ?></div><div class="info event_actors"><?php echo get_post_meta( get_the_ID(), 'event_actors', true ); ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'event_restriction', true ) != '' ) { ?>
								<div class="event_list_item icon-child"><div class="title right"><?php _e('Age restriction:','vh'); ?></div><div class="info event_restriction"><?php echo get_post_meta( get_the_ID(), 'event_restriction', true ); ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'event_box_office', true ) != '' ) { ?>
								<div class="event_list_item icon-dollar"><div class="title right"><?php _e('Ücret:','vh'); ?></div><div class="info event_box_office"><?php echo get_post_meta( get_the_ID(), 'event_box_office', true ); ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'event_language', true ) != '' ) { ?>
								<div class="event_list_item icon-font"><div class="title left"><?php _e('Language:','vh'); ?></div><div class="info event_language"><?php echo get_post_meta( get_the_ID(), 'event_language', true ); ?></div><div class="clearfix"></div></div>
							<?php } ?>
							<?php if ( get_post_meta( get_the_ID(), 'event_official_sites', true ) != '' ) { ?>
								<div class="event_list_item icon-link"><div class="title right"><?php _e('Official sites:','vh'); ?></div><div class="info event_official_sites"><?php echo parse_urls(get_post_meta( get_the_ID(), 'event_official_sites', true )); ?></div><div class="clearfix"></div></div>
							<?php } ?>
					</div>
					<div class="clearfix"></div>
				</div><!--end of overview-container-->
				<div class="event_buttons">
					<div class="button_red"><a href="#" class="vh_button red icon-ticket hover_right"><?php _e('Rezervasyon','vh'); ?></a></div>
					<?php
					$youtube = explode('=',get_post_meta( get_post()->ID, 'event_trailer', true ));
					if ( $youtube[0] != '' ) {
						if ( $youtube[0] != '' ) {
							$youtube_url = $youtube['1'];
						} else {
							$youtube_url = '';
						} ?>
						<div class="button_yellow"><a href="#" class="vh_button yellow icon-play-1 hover_right"><?php _e('Maç videosu','vh'); ?></a><input type="hidden" value="<?php echo $youtube_url; ?>"></input></div>
					<?php } ?>
				</div>
			</div>
			<div class="open_event_social">
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
					var js, fjs = d.getElementsByTagName(s)[0];
					if (d.getElementById(id)) return;
					js = d.createElement(s); js.id = id;
					js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.0";
					fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
				<div class="fb-like" data-href="<?php echo get_post()->guid; ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div>
				<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				<!-- Place this tag where you want the +1 button to render. -->
				<div class="g-plusone" data-size="medium" data-href="<?php echo get_post()->guid; ?>"></div>

				<!-- Place this tag after the last +1 button tag. -->
				<script type="text/javascript" src="https://apis.google.com/js/platform.js"></script>
			</div>
		</div>
		<div class="entry-content">
				<?php 
					echo '<div class="title-and-utility event';
					if ( $show_date == 'false' ) { echo ' no_left_margin'; };
					echo '">';
					echo $entry_utility;
					echo '<div class="clearfix"></div>';
					echo '</div>';
				?>
			<div class="clearfix"></div>
			<?php
			if ( is_search() ) {
				the_excerpt();
				if( empty($excerpt) )
					echo 'No excerpt for this posting.';

			} else {
				the_content(__('Read more', 'vh'));
				wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'vh' ) . '</span>', 'after' => '</div>', 'link_before' => '<span class="page-link-wrap">', 'link_after' => '</span>', ) );
			}
			?>
		</div>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
	<?php
	// If a user has filled out their description, show a bio on their entries
	if ( get_post_type( $post ) == 'post' && get_the_author_meta( 'description' ) ) { ?>
	<div id="author-info">
		<div class="avatar_box">
			<div id="author-avatar shadows">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'vh_author_bio_avatar_size', 70 ) ); ?>
			</div>
		</div><!-- end of author-avatar -->
		<div id="author-description">
			<div class="author-name"><?php printf( esc_attr__( 'Author: %s', 'vh' ), get_the_author() ); ?></div>
			<div class="clearfix"></div>
			<p><?php the_author_meta( 'description' ); ?></p>
			<div id="author-link">
				<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
					<?php printf( __( 'View all posts', 'vh' ), get_the_author() ); ?>
				</a>
			</div><!-- end of author-link	-->
		</div><!-- end of author-description -->
		<div class="clearfix"></div>
	</div><!-- end of entry-author-info -->
	<?php } ?>
</div>