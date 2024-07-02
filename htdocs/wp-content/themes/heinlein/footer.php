		<footer class="site-footer bg--gold-dunkel py-3 px-md-5">
			<div class="d-flex">
			<?php 
				if ( is_active_sidebar( 'footer' )):
					dynamic_sidebar( 'footer' );
				endif; 
			?>
			</div>
		</footer>

		<div id="block-stickysocialbanner" class="">
			<div>
				<a href="https://www.youtube.com/@heinleinplastik-technik923" rel=" noopener" target="_blank">
					<img alt="Heinlein auf Youtube" src="/wp-content/themes/heinlein/assets/images/icons/icon-social-youtube.svg" width="70">
				</a>
			</div>
			<div>
				<a href="https://www.instagram.com/heinlein_plastik_technik/" rel=" noopener" target="_blank">
					<img alt="Heinlein auf Instagram" src="/wp-content/themes/heinlein/assets/images/icons/icon-social-insta.svg" width="70">
				</a>
			</div>
			<div>
				<a href="https://www.linkedin.com/company/heinlein-plastik-technik-gmbh/" rel=" noopener" target="_blank">
					<img alt="Heinlein auf Linkedin" src="/wp-content/themes/heinlein/assets/images/icons/icon-social-linkedin.svg" width="70">
				</a>
			</div>
		</div>

		<?php wp_footer(); ?>

	</body>
</html>