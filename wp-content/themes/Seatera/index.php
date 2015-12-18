<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme.
*/
		get_header();
		if ( have_posts() ) {
			global $vh_blog_image_layout, $vh_from_home_page;

			$vh_blog_image_layout = $vh_from_home_page = TRUE;
		?>
		<div class="container">
			<div class="row">
				<div class="page-<?php echo LAYOUT; ?> page-wrapper">
					<div class="clearfix"></div>
					<div class="page_info">
						<div class="page-title">
							<h1><?php _e( 'News', 'vh' ); ?></h1>
						</div>
					</div>
					<div class="content wpb_row vc_row-fluid">
						<div class="<?php echo LAYOUT; ?>-pull">
							<div class="main-content <?php echo (LAYOUT != 'sidebar-no') ? 'vc_span9' : 'vc_span12'; ?>">
								<div class="main-inner">
									<?php 
										get_template_part( 'loop', get_post_format() );
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
				</div><!--end of shadow1-->
			</div><!--end of row-->
		</div><!--end of container-->
		<?php
		} else {
	?>
	<div class="container">
		<div class="row">
			<div class="span12">
				<div class="page-<?php echo LAYOUT; ?> page-wrapper">
					<div class="clearfix"></div>
					<div class="page-title">
						<h1><?php _e( 'Nothing Found!', 'vh' ); ?></h1>
					</div>
					<div class="content vc_row-fluid">
						<?php wp_reset_postdata(); ?>
						<div class="<?php echo LAYOUT; ?>-pull vc_span12">
							<div class="main-content vc_span12">
								<div class="main-inner">
									<p><?php _e( 'Sorry, nothing found!', 'vh' ); ?></p>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div><!--end of content-->
					<div class="clearfix"></div>
				</div><!--end of shadow1-->
			</div><!--end of span12-->
		</div><!--end of row-->
	</div><!--end of container-->
	<?php
		}
	?>
<?php get_footer();