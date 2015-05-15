<?php
/*
*/

class Kuchikomi_Walker_Comment extends Walker_Comment {
		/**
	 * Output a single comment.
	 *
	 * @access protected
	 * @since 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param object $comment Comment to display.
	 * @param int    $depth   Depth of comment.
	 * @param array  $args    An array of arguments.
	 */
	protected function comment( $comment, $depth, $args ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		$post_title = get_the_title();
?>
		<div class="smp_none">
			<div class="item_kuchikomi_main_box_inner clearfix">
				<div class="item_kuchikomi_image_left">
					<div class="item_kuchikomi_image_left_box">
						<?php if ( 0 != $args['avatar_size'] ) echo '<div class="image">'.get_avatar( $comment, $args['avatar_size'], array(), '', array('class' => 'switch') ).'</div>'; ?>
						<?php printf( '<p class="name">%s</p>', get_comment_author_link() ); ?>
						<p class="day">
							<?php printf( _x( '%1$s', '1: date' ), get_comment_date() ); ?>
						</p>
					</div>
				</div>
				<div class="item_kuchikomi_main_right">
					<?php $this->kuchikomi_list_comment_meta( $comment, 'pc' ); ?>
					<div class="main_text">
						<?php comment_text(); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="pc_none">
			<div class="item_kuchikomi_main_box_inner cf">
				<div class="item_kuchikomi_image_left">
					<div class="item_kuchikomi_image_left_box">
						<?php if ( 0 != $args['avatar_size'] ) echo '<div class="image">'.get_avatar( $comment, $args['avatar_size'], array(), '', array('class' => 'switch') ).'</div>'; ?>
						<?php printf( '<p class="name">%s</p>', get_comment_author_link() ); ?>
						<p class="day">
							<?php printf( _x( '%1$s', '1: date' ), get_comment_date() ); ?>
						</p>
					</div>
				</div>
				<div class="item_kuchikomi_main_right">
					<?php $this->kuchikomi_list_comment_meta( $comment, 'sp' ); ?>
					<div class="main_text">
						<?php comment_text(); ?>
					</div>
				</div>
			</div>
		</div>
<?php
	}

	/**
	 * Output a comment in the HTML5 format.
	 *
	 * @access protected
	 * @since 3.6.0
	 *
	 * @see wp_list_comments()
	 *
	 * @param object $comment Comment to display.
	 * @param int    $depth   Depth of comment.
	 * @param array  $args    An array of arguments.
	 */
	protected function html5_comment( $comment, $depth, $args ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		$post_title = get_the_title();
?>
		<div class="smp_none">
			<div class="item_kuchikomi_main_box_inner clearfix">
				<div class="item_kuchikomi_image_left">
					<div class="item_kuchikomi_image_left_box">
						<?php if ( 0 != $args['avatar_size'] ) echo '<div class="image">'.get_avatar( $comment, $args['avatar_size'], array(), '', array('class' => 'switch') ).'</div>'; ?>
						<?php printf( '<p class="name">%s</p>', get_comment_author_link() ); ?>
						<p class="day">
							<?php printf( _x( '%1$s', '1: date' ), get_comment_date() ); ?>
						</p>
					</div>
				</div>
				<div class="item_kuchikomi_main_right">
					<div class="main_text">
						<?php $this->kuchikomi_list_comment_meta( $comment, 'pc' ); ?>
						<p><?php comment_text(); ?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="pc_none">
			<div class="item_kuchikomi_main_box_inner cf">
				<div class="item_kuchikomi_image_left">
					<div class="item_kuchikomi_image_left_box">
						<?php if ( 0 != $args['avatar_size'] ) echo '<div class="image">'.get_avatar( $comment, $args['avatar_size'], array(), '', array('class' => 'switch') ).'</div>'; ?>
						<?php printf( '<p class="name">%s</p>', get_comment_author_link() ); ?>
						<p class="day">
							<?php printf( _x( '%1$s', '1: date' ), get_comment_date() ); ?>
						</p>
					</div>
				</div>
				<div class="item_kuchikomi_main_right">
					<?php $this->kuchikomi_list_comment_meta( $comment, 'sp' ); ?>
					<div class="main_text">
						<?php comment_text(); ?>
					</div>
				</div>
			</div>
		</div>
<?php
	}

	public function kuchikomi_list_comment_meta( $comment, $mode='pc' ) {
		$comment_ID = $comment->comment_ID;
		$options = get_option( 'kuchikomi_options' );
		for ( $i = 0; $i < count($options); $i++ ) {
			$meta_key_title   = $options[$i]['slug'];
			$get_comment_meta_value  = get_comment_meta( $comment_ID, $meta_key_title, true );
			if($get_comment_meta_value) {
				if( $options[$i]['type'] == 'text' ) {
					$meta_title = esc_attr($get_comment_meta_value);
				} elseif( $options[$i]['type'] == 'select' ) {
					$meta_age = esc_attr($get_comment_meta_value);
				} elseif( $options[$i]['type'] == 'rating' ) {
					$stars = '';
					for ($j = 0; $j < 5; $j++) {
						if ( $j < $get_comment_meta_value ) {
							$stars .= "★";
						} else {
							$stars .= "☆";
						}
					}
					$meta_rating = "<span>{$stars}</span>{$get_comment_meta_value}";
				} elseif( $options[$i]['type'] == 'checkbox' ) {
					$meta_skins = array();
					foreach($get_comment_meta_value as $row) {
						$meta_skins[] = "<span>{$row}</span>";
					}
					$meta_skin = implode(' ', $meta_skins);
					
				}
			}
		}
		
		$echo = '';
		if ( $mode == 'pc' ) {
			$echo = "
				<div class=\"main_info_inner cf\">
					<div class=\"main_info\">
						<h4>{$meta_title}</h4>
						<p class=\"info_detail\">{$meta_skin}{$meta_age}</p>
					</div>
					<div class=\"main_evaluation\">
						<p>{$meta_rating}</p>
					</div>
				</div>\n";
		} else {
			$echo = "
				<div class=\"main_info_inner cf\">
					<div class=\"main_info\">
						<h4>{$meta_title}</h4>
					</div>
					<div class=\"cf\">
						<div class=\"main_evaluation\">
							<p>{$meta_rating}</p>
						</div>
						<div class=\"info_detail\">
							<p>{$meta_skin}{$meta_age}</p>
						</div>
					</div>
				</div>\n";
		}
		echo $echo;
	}
}
