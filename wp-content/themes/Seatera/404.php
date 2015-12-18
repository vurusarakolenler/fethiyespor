<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage Jobera
 */
get_header();

	$title_404 = get_option(SHORTNAME . '_404_title', "This is somewhat embarrassing, isn't it?");
	$title_msg = get_option(SHORTNAME . '_404_message', "It seems we can't find what you're looking for. Perhaps searching, or one of the links below, can help.");
?>
<div class="page-<?php echo LAYOUT; ?> page-wrapper search-no-results">
	<div class="clearfix"></div>
	<div class="page_info">
		<div class="page-title">
			<h1>W-P-L-O-C-K-E-R-.-C-O-M - <?php echo $title_404; ?></h1>
		</div>
	</div>
	<div class="content wpb_row vc_row-fluid">
		<?php wp_reset_postdata(); ?>
		<div class="<?php echo LAYOUT; ?>-pull">
			<div class="main-content <?php echo (LAYOUT != 'sidebar-no') ? 'vc_span9' : 'vc_span12'; ?>">
				<div class="main-inner">
					<div class="wpb_row vc_row-fluid">
						<div class="vc_span12">
							<p><?php echo $title_msg; ?></p>
							<?php require("searchform.php"); ?>
							<p>&nbsp;</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $vh_is_in_sidebar = false; ?>
		<div class="clearfix"></div>
	</div><!--end of content-->
	<div class="clearfix"></div>
</div><!--end of page-wrapper-->
<?php get_footer();