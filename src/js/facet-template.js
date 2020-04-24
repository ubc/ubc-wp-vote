// Scripts from facetwp to add facet labels above filters https://facetwp.com/add-labels-above-each-facet/.
(function( $ ) {
	$(document).on('facetwp-loaded', function() {
		$('.facetwp-facet').each(function() {
			var $facet = $(this);
			var facet_name = $facet.attr('data-name');
			var facet_label = FWP.settings.labels[facet_name];

			if ($facet.closest('.facet-wrap').length < 1 && $facet.closest('.facetwp-flyout').length < 1) {
				$facet.wrap('<div class="facet-wrap"></div>');
				$facet.before('<span class="facet-label">' + facet_label + '</span>');
			}
		});
	});
})(jQuery);

// update and trigger rating number range field when dropdown changes and update posts rendered.
(function() {
    document.addEventListener('change', function ( event ) {

        if ( event.target.matches('.ubc_wp_vote_dropdown_rating') ) {
            var parentNode = event.target.parentNode;
            var currentValue = event.target.value;
            var min = parentNode.querySelector( '.facetwp-number-min' );
            var max = parentNode.querySelector( '.facetwp-number-max' );
            var submit = parentNode.querySelector( '.facetwp-submit' );

            if ( '0' === currentValue ) {
                min.value = null;
                max.value = null;
            } else {
                min.value = parseInt( currentValue );
                max.value = 5;
            }

            submit.click();
        }

    }, false);
})();
