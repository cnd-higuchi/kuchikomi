jQuery(function($) {
	$('.kuchikomi_custom_type').each(function(index, element) {
		var $selected_type = $(element).val();
		var $index = $(element).attr('data-index');
		display_form($index, $selected_type);
	});
	$('.kuchikomi_custom_type').change(function() {
		var $selected_type = $(this).val();
		var $index = $(this).attr('data-index');
		display_form($index, $selected_type);
	});

	function display_form(index, type) {
		$('.kuchikomi_field_' + index).hide();
		$('.kuchikomi_all_field_' + index).show();
		if(type == 'text'){
			$('.kuchikomi_text_field_' + index).show();
		} else if(type == 'select'){
			$('.kuchikomi_select_field_' + index).show();
		} else if(type == 'checkbox'){
			$('.kuchikomi_checkbox_field_' + index).show();
		} else if(type == 'rating'){
			$('.kuchikomi_rating_field_' + index).show();
		}
	}
});
