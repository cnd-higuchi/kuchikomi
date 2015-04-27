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
		add_filter( 'comment_form_defaults', array( $this, 'kuchikomi_comment_form_defaults') );
		add_filter( 'comment_form_field_comment', array( $this, 'kuchikomi_comment_form_field_comment') );
		add_filter( 'comment_form_submit_button', array( $this, 'kuchikomi_comment_form_submit_button'), 10, 2 );
		add_action( 'admin_menu', array( $this, 'my_plugin_menu') );
	}

	public function my_plugin_menu () {
		add_options_page('kuchikomi', 'kuchikomi', 'manage_options', basename(__FILE__), array( $this, 'admin_page') );
	}	

	public function admin_page() {
		include(dirname(__FILE__).'/admin/index.php');
	}

	public function test() {
		echo 'test';
	}

	public function kuchikomi_comment_form_defaults( $defaults ) {
		$defaults['title_reply'] = 'クチコミをのこす';
		return $defaults;
	}

	public function kuchikomi_comment_form_field_comment( $comment ) {
		$comment = '
			<p class="comment-form-title">
			<label for="title">タイトル</label>
			<input id="title" name="title" type="text" value="" size="30" />
			</p>'.$comment;
		return $comment;
	}
	public function kuchikomi_comment_form_submit_button( $submit_button, $args ) {
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
