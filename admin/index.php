<div class="wrap">
	<h2>クチコミ</h2>
	<form id="kuchikomi-form" method="post" action="">
		<?php wp_nonce_field( 'my-nonce-key', 'kuchikomi' ); ?>
		<p>
			タイトル<input type="text" name="kuchikomi" value="<?php echo esc_attr( get_option( 'test' ) ); ?>" />
		</p>
		<p>
			<input type="submit" value="<?php echo esc_attr( __( 'Save', 'default' ) ); ?>" class="button button-primary button-large" />
		</p>
	</form>
</div>
