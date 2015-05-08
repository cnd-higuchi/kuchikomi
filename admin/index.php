<div class="wrap">
	<h2>クチコミ</h2>
	<form id="kuchikomi-form" method="post" action="">
		<?php wp_nonce_field( 'kuchikomi-key', 'kuchikomi-chk' ); ?>
		
		<?php $options = get_option( 'kuchikomi_options' ); ?>
		<?php
			if ( !$options ) {
				$cnt=1;
			} else {
				$cnt=count($options) + 1;
			}
		?>
		<?php for ( $i = 0; $i < $cnt; $i++ ) { ?>
			<h3 class="title">ITEM<?php echo $i; ?></h3>
			<table class="form-table">
				<tr class="kuchikomi_field_<?php echo $i; ?> kuchikomi_all_field_<?php echo $i; ?>">
					<th scope="row">タイプ</th>
					<td>
						<select name="kuchikomi_custom[<?php echo $i; ?>][type]" class="kuchikomi_custom_type" data-index="<?php echo $i; ?>">
							<option value="">選択してください</option>
							<option value="text" <?php echo $this->is_selected($options, $i, 'type', 'text'); ?>>テキスト</option>
							<option value="select" <?php echo $this->is_selected($options, $i, 'type', 'select'); ?>>セレクトボックス</option>
							<option value="checkbox" <?php echo $this->is_selected($options, $i, 'type', 'checkbox'); ?>>チェックボックス</option>
							<option value="rating" <?php echo $this->is_selected($options, $i, 'type', 'rating'); ?>>評価</option>
						</select>
					</td>
				</tr>
				<tr class="kuchikomi_field_<?php echo $i; ?> kuchikomi_all_field_<?php echo $i; ?>">
					<th scope="row">スラッグ</th>
					<td>
						<input type="text" name="kuchikomi_custom[<?php echo $i; ?>][slug]" value="<?php echo esc_attr( $this->get_option_value( $options, $i, 'slug' ) ); ?>" />
					</td>
				</tr>
				<tr class="kuchikomi_field_<?php echo $i; ?> kuchikomi_all_field_<?php echo $i; ?>">
					<th scope="row">ラベル</th>
					<td>
						<input type="text" name="kuchikomi_custom[<?php echo $i; ?>][label]" value="<?php echo esc_attr( $this->get_option_value( $options, $i, 'label' ) ); ?>" />
					</td>
				</tr>
				<tr class="kuchikomi_field_<?php echo $i; ?> kuchikomi_select_field_<?php echo $i; ?> kuchikomi_checkbox_field_<?php echo $i; ?>">
					<th scope="row">オプション</th>
					<td>
						<textarea name="kuchikomi_custom[<?php echo $i; ?>][options]"><?php echo esc_attr( $this->get_option_value( $options, $i, 'options' ) ); ?></textarea>
					</td>
				</tr>
			</table>
		<?php } ?>
		<p>
			<input type="submit" value="<?php echo esc_attr( __( 'Save', 'default' ) ); ?>" class="button button-primary button-large" />
		</p>
		</table>
	</form>
</div>
