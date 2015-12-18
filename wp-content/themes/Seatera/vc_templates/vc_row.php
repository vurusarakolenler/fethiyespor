<?php
$output = $el_class = '';
extract(shortcode_atts(array(
	'el_class'        => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'font_color'      => '',
    'padding'         => '',
    'margin_bottom'   => '',
	'type' 			  => '',
    'css' => ''
), $atts));

wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style('js_composer_custom_css');

$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row '.get_row_css_class().$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);

$row_style = $this->buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);

if ( $type ) {
	$bg_cover = $bg_attachment = '';

	$bg_cover = apply_filters( 'dt_sanitize_flag', $bg_cover );
	$bg_attachment = in_array( $bg_attachment, array( 'false', 'fixed', 'true' ) ) ? $bg_attachment : 'false';

	$style = array();
	$style[] = 'background-size: auto';
	$style = implode(';', $style);

	$custom_classes   = array( 'full-bg' );
	$custom_classes[] = 'bg-style-' . esc_attr($type);
	$custom_classes[] = $el_class;

	if ( $style ) {
		$style = wp_kses( $style, array() );
		$style = ' style="' . esc_attr($style) . '"';
	}

	$output .= '<div class="' . esc_attr(implode(' ', $custom_classes)) . '"' . $style . '>';
}

$output .= '<div class="'.$css_class.'" ' . $row_style . '>';
$output .= wpb_js_remove_wpautop($content);
$output .= '</div>'.$this->endBlockComment('row');

if ( $type ) {
	$output .= '</div>';
}

echo $output;