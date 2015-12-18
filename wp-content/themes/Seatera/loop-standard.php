<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Jobera
 */

global $vh_from_home_page, $post;

$tc = 0;
$excerpt = get_the_excerpt();
$img = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large-image');

if ( $vh_from_home_page == TRUE || is_search() ) {
	$span_class_index = 'vc_span6';
} else {
	if(LAYOUT != 'sidebar-no') {
		$span_class_index = 'vc_span9';
	} else {
		$span_class_index = 'vc_span12';
	}
}
?>
	<li class="isotope-item <?php echo $span_class_index; ?>">
		<div class="post-grid-item-wrapper">
			<div  <?php post_class(); ?>>
				<?php if ( empty($img[0]) ) { ?>
					<h2 class="post-title_nothumbnail">
						<?php
						if ( get_post_type() == 'post' && !empty($post->ID) ) {
							echo '<span class="post_info_text">';
							$tc = wp_count_comments($post->ID); 
							echo '<span class="comments icon-comment">' . $tc->total_comments . '</span>';

								if ( function_exists('get_post_ul_meta') ) {
									echo '
									<span class="blog_likes icon-heart">' . get_post_ul_meta($post->ID, "like") . '</span>';
								}
								echo '
							</span>';
						}
						?>
						<a class="link_title" href="<?php echo get_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'vh'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a>
					</h2>
				<?php } else { ?>
					<div class="post-thumb">
						<?php
							if ( get_post_type() == 'post' && !empty($post->ID) ) {
								echo '<div class="post_info_img">';
								$tc = wp_count_comments($post->ID); 
								echo '<span class="comments icon-comment">' . $tc->total_comments . '</span>';

								if ( function_exists('get_post_ul_meta') ) {
									echo '
									<span class="blog_likes icon-heart">' . get_post_ul_meta($post->ID, "like") . '</span>';
								}
								echo '
								</div>';
							}
						?>
						<div class="post-thumb-img-wrapper shadows">
							<div class="bottom_line"></div>
							<a class="link_image" href="<?php echo get_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'vh'), the_title_attribute('echo=0')); ?>">
								<img src="<?php echo $img[0]; ?>" alt="">
							</a>
						</div>
					</div>
					<h2 class="post-title">
						<a class="link_title" href="<?php echo get_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'vh'), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a>
					</h2>
				<?php } ?>
				<div class="entry-content">
					<?php
						if ( is_search() ) {
							if( empty($excerpt) ) {
								_e( 'No excerpt for this posting.', 'vh' );
							} else {
								the_excerpt();
							}
						} else {
							the_excerpt(__('Read more', 'vh'));
						}
					?>
				</div>
				<div class="read_more" style="overflow: visible;"><a href="<?php echo get_permalink(); ?>" class="vc_read_more hover_right" title="<?php printf(esc_attr__('Permalink to %s', 'vh'), the_title_attribute('echo=0')); ?>"><?php _e( 'Read more', 'vh'); ?></a></div>
				<div class="blog_author"><div class="blog_time icon-clock"><?php echo get_the_date('j.n.Y'); ?></div>
				<span class="author icon-user"><a href="<?php echo get_author_posts_url( get_the_author_meta('ID') );?>"><?php echo __('by', 'vh') . ' ' . get_the_author_link(); ?></a></span></div>
			</div>
		</div>
	</li>