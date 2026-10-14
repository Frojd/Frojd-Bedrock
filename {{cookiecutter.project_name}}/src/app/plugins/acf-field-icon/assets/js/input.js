(function($){
	
	
	function initialize_field( $el ) {

		var $container = $el.find('.acf-field-icons').first();
		if (!$container.length || $container.data('icon-search-initialized')) {
			return;
		}
		$container.data('icon-search-initialized', true);

		var $input = $container.find('.acf-field-icons-search-input');
		var $labels = $container.find('.acf-field-icons-list > label');
		var $noResults = $container.find('.acf-field-icons-no-results');
		var $summaryPreview = $container.find('.acf-field-icons-summary-preview');
		var $summaryLabel = $container.find('.acf-field-icons-summary-label');
		var defaultLabel = $summaryLabel.text().trim();

		$input.on('input', function() {
			var query = $(this).val().toLowerCase().trim();
			var visible = 0;

			$labels.each(function() {
				var name = $(this).attr('data-icon-name') || '';
				var match = query === '' || name.indexOf(query) !== -1;
				$(this).toggle(match);
				if (match) visible++;
			});

			$noResults.prop('hidden', visible !== 0);
		});

		$container.on('change', 'input[type="radio"]', function() {
			var $radio = $(this);
			var $img = $radio.siblings('.acf-field-icon-item').find('.acf-field-icon-image');
			var bg = $img.length ? $img.css('background-image') : '';

			if ($radio.val() && bg && bg !== 'none') {
				$summaryPreview.css('background-image', bg).show();
				var iconName = $radio.closest('label').attr('data-icon-name') || '';
				$summaryLabel.text(iconName || defaultLabel);
			} else {
				$summaryPreview.css('background-image', '').hide();
				$summaryLabel.text(defaultLabel);
			}
		});

		if (!$summaryPreview.attr('style')) {
			$summaryPreview.hide();
		}

	}
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		/*
		*  ready append (ACF5)
		*
		*  These are 2 events which are fired during the page load
		*  ready = on page load similar to $(document).ready()
		*  append = on new DOM elements appended via repeater field
		*
		*  @type	event
		*  @date	20/07/13
		*
		*  @param	$el (jQuery selection) the jQuery element which contains the ACF fields
		*  @return	n/a
		*/
		
		acf.add_action('ready append', function( $el ){
			
			// search $el for fields of type 'icon'
			acf.get_fields({ type : 'icon'}, $el).each(function(){
				
				initialize_field( $(this) );
				
			});
			
		});
		
		
	} else {
		
		
		/*
		*  acf/setup_fields (ACF4)
		*
		*  This event is triggered when ACF adds any new elements to the DOM. 
		*
		*  @type	function
		*  @since	1.0.0
		*  @date	01/01/12
		*
		*  @param	event		e: an event object. This can be ignored
		*  @param	Element		postbox: An element which contains the new HTML
		*
		*  @return	n/a
		*/
		
		$(document).on('acf/setup_fields', function(e, postbox){
			
			$(postbox).find('.field[data-field_type="icon"]').each(function(){
				
				initialize_field( $(this) );
				
			});
		
		});
	
	
	}


})(jQuery);
