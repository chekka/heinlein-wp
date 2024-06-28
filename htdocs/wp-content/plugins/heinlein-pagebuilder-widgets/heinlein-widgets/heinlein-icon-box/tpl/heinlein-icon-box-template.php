<?php
	$icon_orientation =	wp_kses_post($instance['icon_orientation']);
   $icon_position =		wp_kses_post($instance['icon_position']);
	$icon_type =			wp_kses_post($instance['icon_type']);
	$icon_color =			wp_kses_post($instance['icon_color']);
	$media =			   	wp_kses_post($instance['media']);
	$external_url =		wp_kses_post($instance['external_url']);
	$target_blank = 		wp_kses_post($instance['target_blank']);
	$btn_text =				wp_kses_post($instance['section_btn']['button_text']);
	$btn_ziel =				sow_esc_url($instance['section_btn']['button_ziel']);
	$vermittlerID =		wp_kses_post($instance['section_btn']['button_vermittlerID']);
   $btn_type =				wp_kses_post($instance['section_btn']['button_type']);
	$btn_color =			wp_kses_post($instance['section_btn']['button_color']);
	$is_popup =				wp_kses_post($instance['section_popup']['popup_button']);
	$popup_content =		wp_kses_post($instance['section_popup']['popup_content']);
	$editor =				wp_kses_post($instance['tinymce_editor']);
?>

<?php
if ( $btn_type == 'text-arrow'){
    $flex =  'justify-content-between align-items-center ';
    $margin = 'm-0';
} else {
    $margin_top = 'mt-auto pb-3';
}
?>

<?php if($media != '' || $external_url != ''): ?>
	<?php if($external_url != ''): $href = $external_url; elseif($media != ''): $href = wp_get_attachment_url($media); endif ?>
<a href="<?php echo $href; ?>"<?php if($target_blank == 1): ?> target="_blank" rel="noopener"<?php endif; ?>>
<?php endif ?>
	<div class="d-flex <?php echo $icon_position; ?> <?php echo $icon_orientation; ?> h-100 <?php echo $flex; if($is_popup == true){ echo ' popup '; }?>">
		<div class="card-icon icon-<?php echo $icon_color; ?>">
			<?php if($icon_type == "download"): ?>
				<svg class="svg-color-fill" viewBox="0 0 10.0575 10.05875"><g transform="translate(-306.39982,-384.47569)"><path d="m 311.42857,394.34694 0,0.1875 c 2.7775,-0.001 5.02875,-2.2525 5.02875,-5.02875 0,-2.7775 -2.25125,-5.02875 -5.02875,-5.03 -2.7775,0.001 -5.02875,2.2525 -5.02875,5.03 0,2.77625 2.25125,5.0275 5.02875,5.02875 l 0,-0.1875 0,-0.1875 c -2.57,-0.005 -4.64875,-2.085 -4.65375,-4.65375 0.005,-2.57 2.08375,-4.65 4.65375,-4.655 2.57,0.005 4.65,2.085 4.65375,4.655 -0.004,2.56875 -2.08375,4.64875 -4.65375,4.65375 l 0,0.1875" style="fill-opacity:1;fill-rule:nonzero;stroke:none"></path><path d="m 311.23982,390.93569 0.375,0 0,-4.71632 -0.375,0 0,4.71632 z" style="fill-opacity:1;fill-rule:nonzero;stroke:none"></path><path d="m 312.85232,389.17194 -1.42625,1.495 -1.39375,-1.4375 -0.26875,0.26125 1.665,1.71625 1.695,-1.77625 -0.27125,-0.25875" style="fill-opacity:1;fill-rule:nonzero;stroke:none"></path><path d="m 309.80357,392.49444 3.27979,0 0,-0.375 -3.27979,0 0,0.375 z" style="fill-opacity:1;fill-rule:nonzero;stroke:none"></path></g></svg>
			<?php endif ?>
			<?php if($icon_type == "external-link"): ?>
				<svg id="icon--external-link" x="0px" y="0px" width="72px" height="72px" viewBox="0 0 72 72" overflow="visible" xml:space="preserve">
						<circle fill="none" stroke="#434D6E" stroke-width="2" stroke-miterlimit="10" cx="36" cy="36" r="35"/>
						<path fill="none" stroke="#434D6E" stroke-width="2" stroke-linecap="round" d="M40.6,23.7l13,12.3l-13,12.3 M17.9,36h35.7"/>
				</svg>
			<?php endif ?>
		</div>

		<?php if ($editor != ''){ ?>
			<div class="card-body pr-4">
					<?php if ($editor != ''){ echo '<div class="editor hyphens">' . $editor . '</div>'; } ?>
			</div>
		<?php } ?>

		<?php if($btn_ziel != "" || $is_popup == true){ ?>
			<div class="lvw-callout__actions pr-4 text-right <?php echo $margin_top ?>">
				<a href="<?php if($is_popup == false){ echo $btn_ziel; } else { echo '#'; } ?>" class="<?php if($is_popup == true){ echo 'popup '; } ?><?php echo $btn_type; ?><?php echo $btn_color; ?> <?php if($vermittlerID){ echo $vermittlerIDbutton; }?>"><?php if($btn_text != ''){ echo $btn_text; } else { echo '&nbsp;'; } ?></a>
			</div>
		<?php } ?>
		<?php if($is_popup == true){ ?>
			<div class="popup-content hyphens"><?php echo $popup_content; ?></div>
		<?php } ?>
	</div>
<?php if($media != '' || $external_url != ''): ?>
</a>
<?php endif ?>