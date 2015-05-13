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

	public $plugin_ver = '0.1';
	public function __construct() {
		include_once( 'class-kuchikomi-walker-comment.php' );
		add_filter( 'comment_form_defaults', array( $this, 'comment_form_defaults') );
		add_filter( 'comment_form_field_comment', array( $this, 'comment_form_field_comment') );
		add_filter( 'comment_form_submit_button', array( $this, 'comment_form_submit_button'), 10, 2 );
		add_filter( 'wp_list_comments_args', array( $this, 'wp_list_comments_args') );
		add_action( 'admin_menu', array( $this, 'admin_menu') );
		add_action( 'admin_init', array( $this, 'admin_init') );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts') );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts') );
		add_action( 'add_meta_boxes_comment', array( $this, 'add_meta_boxes_comment' ) );
		add_action( 'comment_post', array( $this, 'update_comment_field' ) );
		add_action( 'edit_comment', array( $this, 'update_comment_field' ) );
	}

	public function wp_list_comments_args($r) {

		$r['walker'] = new Kuchikomi_Walker_Comment();

		return $r;
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
						if( isset($_POST['kuchikomi_custom'][$i]['type']) ) {
							$options[$ii]['type'] = $_POST['kuchikomi_custom'][$i]['type'];
						}
						if( isset($_POST['kuchikomi_custom'][$i]['slug']) ) {
							$options[$ii]['slug'] = $_POST['kuchikomi_custom'][$i]['slug'];
						}
						if( isset($_POST['kuchikomi_custom'][$i]['label']) ) {
							$options[$ii]['label'] = $_POST['kuchikomi_custom'][$i]['label'];
						}
						if( isset($_POST['kuchikomi_custom'][$i]['options']) ) {
							$options[$ii]['options'] = $_POST['kuchikomi_custom'][$i]['options'];
						}
						$ii++;
					}
				}
				update_option( 'kuchikomi_options', $options );

				//f5対策
				wp_safe_redirect( menu_page_url( 'kuchikomi', false ) );
			}
		}
	}	

	public function wp_enqueue_scripts( $hook ) {
		wp_enqueue_script(
			'kuchikomi.js',
			plugins_url( 'assets/js/kuchikomi.js', __FILE__ ),
			array( 'jquery' ),
			$this->plugin_ver,
			false
		);
		wp_register_style(
			'kuchikomi.css',
			plugins_url( 'assets/css/kuchikomi.css', __FILE__ ),
			array(),
			$this->plugin_ver,
			'all'
		);
		wp_enqueue_style(
			'kuchikomi.css'
		);
	}

	public function admin_enqueue_scripts( $hook ) {
		wp_enqueue_script(
			'kuchikomi.js',
			plugins_url( 'assets/js/kuchikomi.js', __FILE__ ),
			array( 'jquery' ),
			$this->plugin_ver,
			false
		);
		wp_enqueue_script(
			'kuchikomi_admin.js',
			plugins_url( 'assets/js/kuchikomi_admin.js', __FILE__ ),
			array( 'jquery' ),
			$this->plugin_ver,
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

	public function get_option_value($options, $i, $element) {
		$return = '';
		if ( isset($options[$i][$element]) ) {
			$return = $options[$i][$element];
		}
		return $return;
	}

	public function comment_form_defaults( $defaults ) {
		$defaults['title_reply'] = 'クチコミをのこす';
		return $defaults;
	}

	public function add_meta_boxes_comment() {
		global $comment;
		$comment_ID = $comment->comment_ID;
		$custom_key         = 'post_reviews_date';
		$noncename          = $custom_key . '_noncename' ;
		echo '<input type="hidden" name="' . $noncename . '" id="' . $noncename . '" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />' . "\n";
		$options = get_option( 'kuchikomi_options' );
		$items = '';
		for ( $i = 0; $i < count($options); $i++ ) {
			$meta_key_title   = $options[$i]['slug'];
			$get_comment_meta_value  = get_comment_meta( $comment_ID, $meta_key_title, true );
			if( $options[$i]['type'] == 'text') {
				$get_comment_meta_value = esc_attr($get_comment_meta_value);
				echo "
					<div><b><label for=\"comment-title\">{$options[$i]['label']}</label></b></p><p><input id=\"{$meta_key_title}\" name=\"{$meta_key_title}\" type=\"text\" value=\"{$get_comment_meta_value}\" size=\"56\" maxlength=\"30\"/></div>\n";
			} elseif( $options[$i]['type'] == 'rating' ) {
				$get_comment_meta_value = esc_attr($get_comment_meta_value);
				echo "
					<div class=\"comment-form-{$options[$i]['slug']}\">\n
						<label for=\"{$options[$i]['slug']}\">{$options[$i]['label']}</label>\n
						<span class=\"kuchikomi_rating 1star\">★</span>\n
						<span class=\"kuchikomi_rating 2stars\">☆</span>\n
						<span class=\"kuchikomi_rating 3stars\">☆</span>\n
						<span class=\"kuchikomi_rating 4stars\">☆</span>\n
						<span class=\"kuchikomi_rating 5stars\">☆</span>\n
						<input type=\"hidden\" id=\"{$options[$i]['slug']}\" name=\"{$options[$i]['slug']}\" class=\"kuchikomi_rating_value\" value=\"{$get_comment_meta_value}\" />\n
					</div>\n";
			} elseif( $options[$i]['type'] == 'select' ) {
				$html_option = '<option value="">選択してください</option>';
				$html_options_array = explode(':', $options[$i]['options']);
				foreach($html_options_array as $row) {
					if( $row == $get_comment_meta_value ) {
						$html_option .= "<option value=\"{$row}\" selected=\"selected\">{$row}</option>";
					} else {
						$html_option .= "<option value=\"{$row}\">{$row}</option>";
					}
				}
				echo "
					<div class=\"comment-form-{$options[$i]['slug']}\">
						<label for=\"{$options[$i]['slug']}\">肌質</label>
						<select id=\"{$options[$i]['slug']}\" name=\"{$options[$i]['slug']}\">
							{$html_option}
						</select>
					</div>";
			} elseif( $options[$i]['type'] == 'checkbox' ) {
				$html_option = '';
				$html_options_array = explode(':', $options[$i]['options']);
				$j = 0;
				foreach($html_options_array as $row) {
					if( in_array($row, $get_comment_meta_value) ) {
						$html_option .= "<li><input type=\"checkbox\" id=\"{$options[$i]['slug']}{$j}\" name=\"{$options[$i]['slug']}[]\" value=\"{$row}\" checked=\"checked\" /><label for=\"{$options[$i]['slug']}{$j}\">{$row}</label></li>";
					} else {
						$html_option .= "<li><input type=\"checkbox\" id=\"{$options[$i]['slug']}{$j}\" name=\"{$options[$i]['slug']}[]\" value=\"{$row}\" /><label for=\"{$options[$i]['slug']}{$j}\">{$row}</label></li>";
					}
					$j++;
				}
				echo "
					<div class=\"comment-form-{$options[$i]['slug']}\">
						<ul class=\"kuchikomi clearfix\">
							{$html_option}
						</ul>
					</div>";
			}
		}
	}

	public function comment_form_field_comment( $comment ) {
		$options = get_option( 'kuchikomi_options' );
		$items = '';
		for ( $i = 0; $i < count($options); $i++ ) {
			if( $options[$i]['type'] == 'text' ) {
				$items .= "
					<div class=\"comment-form-{$options[$i]['slug']}\">
						<label for=\"{$options[$i]['slug']}\">{$options[$i]['label']}</label>
						<input id=\"{$options[$i]['slug']}\" name=\"{$options[$i]['slug']}\" type=\"text\" value=\"\" size=\"30\" />
					</div>";
			} elseif( $options[$i]['type'] == 'rating' ) {
				$items .= "
					<div class=\"comment-form-{$options[$i]['slug']}\">
						<label for=\"{$options[$i]['slug']}\">{$options[$i]['label']}</label>
						<span class=\"kuchikomi_rating 1star\">★</span>
						<span class=\"kuchikomi_rating 2stars\">☆</span>
						<span class=\"kuchikomi_rating 3stars\">☆</span>
						<span class=\"kuchikomi_rating 4stars\">☆</span>
						<span class=\"kuchikomi_rating 5stars\">☆</span>
						<input type=\"hidden\" id=\"{$options[$i]['slug']}\" name=\"{$options[$i]['slug']}\" class=\"kuchikomi_rating_value\" value=\"1\" />
					</div>";
			} elseif( $options[$i]['type'] == 'select' ) {
				$html_option = '<option value="">選択してください</option>';
				$html_options_array = explode(':', $options[$i]['options']);
				foreach($html_options_array as $row) {
					$html_option .= "<option value=\"{$row}\">{$row}</option>";
				}
				$items .= "
					<div class=\"comment-form-{$options[$i]['slug']}\">
						<label for=\"{$options[$i]['slug']}\">肌質</label>
						<select id=\"{$options[$i]['slug']}\" name=\"{$options[$i]['slug']}\">
							{$html_option}
						</select>
					</div>";
			} elseif( $options[$i]['type'] == 'checkbox' ) {
				$html_option = '';
				$html_options_array = explode(':', $options[$i]['options']);
				$j = 0;
				foreach($html_options_array as $row) {
					$html_option .= "<li><input type=\"checkbox\" id=\"{$options[$i]['slug']}{$j}\" name=\"{$options[$i]['slug']}[]\" value=\"{$row}\" /><label for=\"{$options[$i]['slug']}{$j}\">{$row}</label></li>";
					$j++;
				}
				$items .= "
					<div class=\"comment-form-{$options[$i]['slug']}\">
						<label>{$options[$i]['label']}</label>
						<ul class=\"kuchikomi clearfix\">
							{$html_option}
						</ul>
					</div>";
			}
		}
		/*
		$comment = '
			<p class="comment-form-title">
				<label for="title">タイトル</label>
				<input id="title" name="title" type="text" value="" size="30" />
			</p>
			<p class="comment-form-rating">
				<label for="rating">評価</label>
				<span class="stars">☆☆☆☆☆</span>
				<input id="rating" name="rating" type="number" step="0.1" min="1.0" max="5.0" />
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
		 */
		$comment = $items . $comment;
		return $comment;
	}

	public function update_comment_field( $comment_id ) {
		if ( !$comment = get_comment( $comment_id ) ) {
			return false;
		}
		$options = get_option( 'kuchikomi_options' );
		for ( $i = 0; $i < count($options); $i++ ) {
			$custom_key_title = $options[$i]['slug'];
			$get_comment_title = $_POST[$custom_key_title];
			if ( '' == get_comment_meta( $comment_id, $custom_key_title ) ) {
				add_comment_meta( $comment_id, $custom_key_title, $get_comment_title, true );
			} else if ( $get_comment_title != get_comment_meta( $comment_id, $custom_key_title ) ) {
				update_comment_meta( $comment_id, $custom_key_title, $get_comment_title );
			} else if ( '' == $get_comment_title ) {
				delete_comment_meta( $comment_id, $custom_key_title );
			}
		}
	    return false;
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

	public function the_kuchikomi_form_input( $slug ) {
	}

}
$kuchikomi = new kuchikomi();
