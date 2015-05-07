jQuery(function($) {
	$('.kuchikomi_custom_type').change(function() {
		var $selectedVal = $(this).val();
		alert($selectedVal);
	});
});
