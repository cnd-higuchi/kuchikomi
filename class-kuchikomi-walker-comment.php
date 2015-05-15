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
		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
?>
		<<?php echo $tag; ?> <?php comment_class( $this->has_children ? 'parent' : '' ); ?> id="comment-<?php comment_ID(); ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
		<?php endif; ?>
		<div class="comment-author vcard">
			<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			<?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>' ), get_comment_author_link() ); ?>
		</div>
		<?php if ( '0' == $comment->comment_approved ) : ?>
		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em>
		<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID, $args ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)' ), '&nbsp;&nbsp;', '' );
			?>
		</div>

		<?php $this->kuchikomi_list_comment_meta( $comment ); ?>
		<?php comment_text( get_comment_id(), array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>

		<?php
		comment_reply_link( array_merge( $args, array(
			'add_below' => $add_below,
			'depth'     => $depth,
			'max_depth' => $args['max_depth'],
			'before'    => '<div class="reply">',
			'after'     => '</div>'
		) ) );
		?>

		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
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
						<?php $this->kuchikomi_list_comment_meta( $comment ); ?>
						<p><?php comment_text(); ?></p>
					</div>
				</div>
			</div>
		</div>
<?php
	}

	public function kuchikomi_list_comment_meta( $comment ) {
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
		echo "
			<div class=\"main_info_inner cf\">
				<div class=\"main_info\">
					<h4>{$meta_title}</h4>
					<p class=\"info_detail\">{$meta_skin}{$meta_age}</p>
				</div>
				<div class=\"main_evaluation\">
					<p>{$meta_rating}</p>
				</div>
			</div>\n";
	}
}
