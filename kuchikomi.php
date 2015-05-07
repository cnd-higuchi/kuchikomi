<?php
/*
Plugin Name: Kuchikomi
Version: 0.1-alpha
Description: PLUGIN DESCRIPTION HERE
Author: YOUR NAME HERE
Author URI: YOUR SITE HERE
Plugin URI: PLUGIN SITE HERE
Text Domain: kuchikomi
Domain Path: /languages
*/

class kuchikomi {

	public function __construct() {
		add_filter( 'comment_form_defaults', array( $this, 'comment_form_defaults') );
		add_filter( 'comment_form_field_comment', array( $this, 'comment_form_field_comment') );
		add_filter( 'comment_form_submit_button', array( $this, 'comment_form_submit_button'), 10, 2 );
		add_action( 'admin_menu', array( $this, 'admin_menu') );
		add_action( 'admin_init', array( $this, 'admin_init') );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts') );
	}

	public function admin_menu () {
		add_options_page('kuchikomi', 'kuchikomi', 'manage_options', basename(__FILE__), array( $this, 'admin_page') );
	}	

	public function admin_init () {
		if ( isset( $_POST['kuchikomi-chk'] ) && $_POST['kuchikomi-chk'] ){
			if ( check_admin_referer( 'kuchikomi-key', 'kuchikomi-chk' ) ){
				$options = array();
				$ii = 0;
				for($i = 0; $i < count($_POST['kuchikomi_custom']); $i++) {
					if ( isset( $_POST['kuchikomi_custom'][$i]['type'] ) && $_POST['kuchikomi_custom'][$i]['type'] ){
						$options[$ii]['type'] = $_POST['kuchikomi_custom'][$i]['type'];
						$options[$ii]['slug'] = $_POST['kuchikomi_custom'][$i]['slug'];
						$options[$ii]['label'] = $_POST['kuchikomi_custom'][$i]['label'];
						$ii++;
					}
				}
				update_option( 'kuchikomi_options', $options );

				//f5対策
				wp_safe_redirect( menu_page_url( 'kuchikomi', false ) );
			}
		}
	}	

	public function admin_enqueue_scripts( $hook ) {
		wp_enqueue_script(
			'kuchikomi_admin.js',
			plugins_url( 'js/kuchikomi_admin.js', __FILE__ ),
			array( 'jquery' ),
			'0.1',
			false
		);
	}

	public function admin_page() {
		include(dirname(__FILE__).'/admin/index.php');
	}

	public function is_selected($options, $i, $element, $tgt_value) {
		$return = '';
		if ( isset($options[$i][$element]) ) {
			if ( $options[$i][$element] == $tgt_value ) {
				$return = 'selected="selected"';
			}
		}
		return $return;
	}

	public function comment_form_defaults( $defaults ) {
		$defaults['title_reply'] = 'クチコミをのこす';
		return $defaults;
	}

	public function comment_form_field_comment( $comment ) {
		$comment = '
			<p class="comment-form-title">
				<label for="title">タイトル</label>
				<input id="title" name="title" type="text" value="" size="30" />
			</p>
			<p class="comment-form-rating">
				<label for="rating">評価</label>
				<span class="stars">☆☆☆☆☆</span>
			</p>
			<p class="comment-form-type">
				<label for="type">肌質</label>
				<select id="type" name="type">
					<option value="乾燥肌">乾燥肌</option>
					<option value="脂性肌・オイリー肌">脂性肌・オイリー肌</option>
					<option value="敏感肌">敏感肌</option>
					<option value="混合肌">混合肌</option>
					<option value="アトピー肌">アトピー肌</option>
					<option value="ニキビ肌">ニキビ肌</option>
					<option value="デリケート肌">デリケート肌</option>
					<option value="かさかさ肌">かさかさ肌</option>
					<option value="秋肌">秋肌</option>
					<option value="枯れ肌">枯れ肌</option>
					<option value="年齢肌">年齢肌</option>
				</select>
			</p>
			<p class="comment-form-age">
				<label for="age">年齢</label>
				<input id="age" name="age" type="number" value="" size="3" />歳
			</p>'
			.$comment;
		return $comment;
	}
	public function comment_form_submit_button( $submit_button, $args ) {
		$submit_button = sprintf(
			$args['submit_button'],
			esc_attr( $args['name_submit'] ),
			esc_attr( $args['id_submit'] ),
			esc_attr( $args['class_submit'] ),
			'クチコミを投稿する'
		);
		return $submit_button;
	}
}
$kuchikomi = new kuchikomi();
