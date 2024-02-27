		<footer class="site-footer">
			<div class="container">
			<?php 
				wp_nav_menu(array(
					'theme_location' => 'footer-menu',
					'container' => 'nav',
					'container_class' => 'footer-menu',
					'menu_class' => 'nav-menu',
					'menu_id' => 'primary-menu'
				));
			?>
			</div>
		</footer>

		<?php wp_footer(); ?>

	</body>
</html>