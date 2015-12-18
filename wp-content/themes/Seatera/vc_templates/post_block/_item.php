<?php
$block = $block_data[0];
$settings = $block_data[1];
?>
<?php if($block === 'title'): ?>
	<?php if (empty($post->thumbnail)): ?>
		<div class="post-title_nothumbnail">
			<div class="post_info_text">
				<?php 
				$tc = wp_count_comments($post->id); 
				echo '<span class="comments icon-comment">' . $tc->total_comments . '</span>';
				if ( function_exists('get_post_ul_meta') ) {
					echo '<span class="blog_likes icon-heart">' . get_post_ul_meta($post->id,"like") . '</span>';
				}
				?>
			</div>
			<div class="clearfix"></div>
			<h2 class="post-title">
				<?php echo !empty($settings[0]) && $settings[0]!='no_link' ? $this->getLinked($post, $post->title, $settings[0], 'link_title') : $post->title ?>
			</h2>
			<div class="clearfix"></div>
		</div>
	<?php else: ?>
		<h2 class="post-title">
			<?php echo !empty($settings[0]) && $settings[0]!='no_link' ? $this->getLinked($post, $post->title, $settings[0], 'link_title') : $post->title ?>
		</h2>
	<?php endif ?>
<?php elseif($block === 'image' && !empty($post->thumbnail)): ?>
		<div class="post-thumb">
				<div class="post_info_img">
					<?php 
					$tc = wp_count_comments($post->id); 
					echo '<span class="comments icon-comment">' . $tc->total_comments . '</span>'; 
					if ( function_exists('get_post_ul_meta') ) {
						echo '<span class="blog_likes icon-heart">' . get_post_ul_meta($post->id,"like") . '</span>';
					}
					?>
				</div>
			<div class="post-thumb-img-wrapper shadows">
				<div class="bottom_line"></div>
				<?php echo !empty($settings[0]) && $settings[0]!='no_link' ? $this->getLinked($post, $post->thumbnail, $settings[0], 'link_image') : $post->thumbnail ?>
			</div>
		</div>
<?php elseif($block === 'text'): ?>
		<div class="entry-content">
			<?php if ( is_front_page() ) {
						echo '<div class="blog_time top icon-clock">'.get_the_date('j.n.Y').'</div>';						
			} ?>
			<?php echo !empty($settings[0]) && $settings[0]==='text' ?  $post->content : $post->excerpt; ?>
			<!-- <div class="blog_postedby"><?php echo human_time_diff(get_the_time('U',$post->id),current_time('timestamp')) . ' ago, by ' . the_author_posts_link($post->id); ?></div> -->
		</div>
<?php elseif($block === 'link'): ?>
		<div class="read_more"><a href="<?php echo $post->link ?>" class="vc_read_more hover_right" title="<?php echo esc_attr(sprintf(__( 'Permalink to %s', "vh" ), $post->title_attribute)); ?>"<?php echo $this->link_target ?>><?php _e('Read more', "vh") ?></a></div>
			<?php
			if ( !is_front_page() ) {
				echo '<div class="blog_author"><div class="blog_time icon-clock">'.get_the_date('j.n.Y').'</div>';
				echo '<span class="author icon-user"><a href="'.get_author_posts_url( get_post_field( 'post_author', $post->id ) ).'">'.__('by', 'vh').' '.get_userdata( get_post_field( 'post_author', $post->id ) )->display_name.'</a></span></div>';
			}
			?>
<?php endif; ?>