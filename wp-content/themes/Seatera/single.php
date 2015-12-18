<?php
/**
 * Single template file.
 */
get_header();

$layout_type = get_post_meta(get_the_id(), 'layouts', true);

if(empty($layout_type)) {
	$layout_type = get_option('vh_layout_style') ? get_option('vh_layout_style') : 'full';
}

$img       = wp_get_attachment_image_src( get_post_thumbnail_id(), 'offer-image-large' );
$span_size = 'vc_span9';
?>
<div class="page-<?php echo LAYOUT; ?> page-wrapper">
	<div class="clearfix"></div>
	<div class="page_info">
	<?php
	if ( get_post_type( $post ) == 'post' ) { ?>
		<div class="page-title"> <?php
		echo '<h1 class="blog_title">' . __( 'Blog', 'vh' ) . '</h1></div>' .
		vh_breadcrumbs();
	} elseif ( !is_front_page() && !is_home() ) { ?>
		<div class="page-title">
			<?php echo  the_title( '<h1>', '</h1>' );?>
		</div>
		<?php echo vh_breadcrumbs(); ?>
	<?php } ?>
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
		<div class="<?php echo LAYOUT; ?>-pull">
			<div class="main-content <?php echo (LAYOUT != 'sidebar-no') ? 'vc_span9' : 'vc_span12'; ?>">
				<div class="main-inner">
					<div class="wpb_row vc_row-fluid">
						<?php
						if ( have_posts() ) {
							while ( have_posts() ) {
								the_post();
								get_template_part( 'content', 'single' ); 
								if ( get_post_type( $post ) == 'post' ) { ?>
									<div class="clearfix"></div>
									<div class="comments_container">
											<nav class="nav-single blog">
												<?php
												$prev_post = get_previous_post();
												$next_post = get_next_post();
												if (!empty( $prev_post )) { ?>
													<div class="hover_left nav_button left"><a href="<?php echo get_permalink( $prev_post->ID ); ?>" class="prev_blog_post icon-left-open-big hover_left"></a></div>
												<?php }
												if (!empty( $next_post )) { ?>
												  <div class="hover_right nav_button right"><a href="<?php echo get_permalink( $next_post->ID ); ?>" class="next_blog_post icon-right-open-big hover_right"></a></div>
												<?php } ?>
												<div class="clearfix"></div>
											</nav><!-- .nav-single -->
										<div class="clearfix"></div>
										<?php
										comments_template( '', true ); ?>
									</div>
									<?php
								}
							}
						} else {
							echo '
								<h2>Nothing Found</h2>
								<p>Sorry, it appears there is no content in this section.</p>';
						}
						?>
					</div>
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