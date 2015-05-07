<div class="wrap">
	<h2>クチコミ</h2>
	<form id="kuchikomi-form" method="post" action="">
		<?php wp_nonce_field( 'kuchikomi-key', 'kuchikomi-chk' ); ?>
		
		<?php $options = get_option( 'kuchikomi_options' ); ?>
		<?php for ( $i = 0; $i <= count($options); $i++ ) { ?>
			<h3 class="title">ITEM<?php echo $i; ?></h3>
			<p>
				タイプ
				<select name="kuchikomi_custom[<?php echo $i; ?>][type]">
					<option value="">選択してください</option>
					<option value="text" <?php echo $this->is_selected($options, $i, 'type', 'text'); ?>>テキスト</option>
					<option value="select" <?php echo $this->is_selected($options, $i, 'type', 'select'); ?>>セレクトボックス</option>
					<option value="checkbox" <?php echo $this->is_selected($options, $i, 'type', 'checkbox'); ?>>チェックボックス</option>
					<option value="rating" <?php echo $this->is_selected($options, $i, 'type', 'rating'); ?>>評価</option>
				</select>
			</p>
<?php /* ?>
		<p>
			スラッグ<input type="text" name="slug[]" value="<?php echo esc_attr( get_option( 'slug[]' ) ); ?>" />
		</p>
		<p>
			ラベル<input type="text" name="title[]" value="<?php echo esc_attr( get_option( 'slug[]' ) ); ?>" />
		</p>
<?php */ ?>
		<?php } ?>
		<p>
			<input type="submit" value="<?php echo esc_attr( __( 'Save', 'default' ) ); ?>" class="button button-primary button-large" />
		</p>
	</form>
</div>
