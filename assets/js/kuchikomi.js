jQuery(function($) {
	$('.kuchikomi_rating').hover(
		function() {
			if( $(this).hasClass('1star')){
				display_stars(1);
			} else if( $(this).hasClass('2stars')){
				display_stars(2);
			} else if( $(this).hasClass('3stars')){
				display_stars(3);
			} else if( $(this).hasClass('4stars')){
				display_stars(4);
			} else if( $(this).hasClass('5stars')){
				display_stars(5);
			}
		},
		function() {
			$rating = $('.kuchikomi_rating_value').val();
			display_stars($rating);
		}
	);
	$('.kuchikomi_rating').on('click', function() {
		if( $(this).hasClass('1star')){
			$('.kuchikomi_rating_value').val(1);
		} else if( $(this).hasClass('2stars')){
			$('.kuchikomi_rating_value').val(2);
		} else if( $(this).hasClass('3stars')){
			$('.kuchikomi_rating_value').val(3);
		} else if( $(this).hasClass('4stars')){
			$('.kuchikomi_rating_value').val(4);
		} else if( $(this).hasClass('5stars')){
			$('.kuchikomi_rating_value').val(5);
		}
	});
	function display_stars(number) {
		if (number == '1') {
			$('.1star').html('★');
			$('.2stars').html('☆');
			$('.3stars').html('☆');
			$('.4stars').html('☆');
			$('.5stars').html('☆');
		} else if (number == '2') {
			$('.1star').html('★');
			$('.2stars').html('★');
			$('.3stars').html('☆');
			$('.4stars').html('☆');
			$('.5stars').html('☆');
		} else if (number == '3') {
			$('.1star').html('★');
			$('.2stars').html('★');
			$('.3stars').html('★');
			$('.4stars').html('☆');
			$('.5stars').html('☆');
		} else if (number == '4') {
			$('.1star').html('★');
			$('.2stars').html('★');
			$('.3stars').html('★');
			$('.4stars').html('★');
			$('.5stars').html('☆');
		} else if (number == '5') {
			$('.1star').html('★');
			$('.2stars').html('★');
			$('.3stars').html('★');
			$('.4stars').html('★');
			$('.5stars').html('★');
		}
	}
	$rating = $('.kuchikomi_rating_value').val();
	display_stars($rating);

	$('.kuchikomi_comment').hide();
	$('.btn_more').on('click', function() {
		var $comment_id = $(this).attr('data-commentid');
		$('.kuchikomi_comment[data-commentid = ' + $comment_id + ']').show();
		$('.kuchikomi_excerpt[data-commentid = ' + $comment_id + ']').hide();
		$(this).hide();
	});
});
