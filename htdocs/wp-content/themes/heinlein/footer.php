		<footer class="site-footer bg--gold-dunkel py-3 px-md-5">
			<div class="d-flex">
			<?php 
				if ( is_active_sidebar( 'footer' )):
					dynamic_sidebar( 'footer' );
				endif; 
			?>
			</div>
		</footer>

		<?php wp_footer(); ?>

	</body>
</html>