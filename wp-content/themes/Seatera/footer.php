<?php
/**
 * The template for displaying the footer.
 */

global $vh_is_footer;
$vh_is_footer = true;

// Footer copyright
$copyrights = get_option('vh_footer_copyright') ? get_option('vh_footer_copyright') : '&copy; [year], Seatera by <a href="http://cohhe.com">Cohhe</a>';
$copyrights = str_replace( '[year]', date('Y'), $copyrights);

// Scroll to top option
$scroll_to_top = filter_var(get_option('vh_scroll_to_top'), FILTER_VALIDATE_BOOLEAN);
wp_reset_query();
?>
			</div><!--end of main-->
		</div><!--end of wrapper-->
		<div class="footer-wrapper">
			<div class="footer-container wpb_row vc_row-fluid <?php if ( !is_home() && !is_front_page() ) { echo 'not_front_page';}?>">
				<?php if ( is_home() || is_front_page() ) { ?>
					<div class="footer-content">
						<?php
							// How many footer columns to show?
							$footer_columns = get_option( 'vh_footer_columns' );
							if ( $footer_columns == false ) {
								$footer_columns = 4;
							}
						?>
						<div class="footer-links-container columns_count_<?php echo $footer_columns; ?>">
							<?php get_sidebar( 'footer' ); ?>
							<div class="clearfix"></div>
						</div><!--end of footer-links-container-->
					</div>
				<?php } ?>
				<div class="footer-inner vc_span12">
					<div class="footer_info">
						<div class="copyright"><?php echo $copyrights; ?></div>
					</div>
					<?php if ( (bool)$scroll_to_top != false ) { ?>
						<div class="scroll-to-top icon-up-open-big hover_up"></div>
					<?php } ?>
				</div>
				
			</div>
		</div>
		<?php
			$fixed_menu    = filter_var(get_option('vh_fixed_menu'), FILTER_VALIDATE_BOOLEAN);
			$tracking_code = get_option( 'vh_tracking_code' ) ? get_option( 'vh_tracking_code' ) : '';
			if ( !empty( $tracking_code ) ) { ?>
				<!-- Tracking Code -->
				<?php
				echo '
					' . $tracking_code;
			}
		?>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>