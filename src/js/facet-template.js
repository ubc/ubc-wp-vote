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

    document.addEventListener('click', function ( event ) {

        if ( event.target.matches('.ubc-wp-vote__facet-category') ) {
            event.preventDefault();

            var catFilter = document.querySelectorAll( '.facetwp-facet[data-name="category"]' );

            if ( ! catFilter[0] ) {
                return;
            }

            catFilter = catFilter[0].querySelector( '.facetwp-dropdown' );
            catFilter.value = event.target.dataset.cat;
            FWP.refresh();
        }

    }, false);
})();
