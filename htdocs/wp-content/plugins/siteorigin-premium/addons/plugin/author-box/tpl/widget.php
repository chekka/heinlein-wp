<?php
if ( empty( $widget ) ) {
	return;
}
?>
<div
	class="sow-author-box-<?php echo esc_attr( $widget ); ?>"
	<?php echo ! empty( $container_css ) ? 'style="' . esc_attr( $container_css ) . '"' : ''; ?>
>
	<?php
	global $wp_widget_factory;

	$the_widget = $wp_widget_factory->widgets[ $widget_class ];
	$the_widget->widget( array(), $widget_settings );
	?>
</div>
