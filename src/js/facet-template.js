
(function() {
    document.addEventListener('change', function ( event ) {
        // update and trigger rating number range field when dropdown changes and update posts rendered.
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
        // Update category filter once a category in a post is clicked.
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

        // Show filter in mobile once 'reset filter' button is clicked.
        if ( event.target.matches('.facet-template__toggle') && window.innerWidth < 768 ) {
            event.preventDefault();

            var filters = document.querySelectorAll( '.ubc-wp-vote__facetwp-filters' );

            if ( ! filters[0] ) {
                return;
            }

            filters[0].classList.add( 'show' );
        }

        // Hide filter in mobile once 'close' button is clicked.
        if ( event.target.matches('.facet-template__filter-close') && window.innerWidth < 768 ) {
            event.preventDefault();

            var filters = document.querySelectorAll( '.ubc-wp-vote__facetwp-filters' );

            if ( ! filters[0] ) {
                return;
            }

            filters[0].classList.remove( 'show' );
        }

    }, false);

    // Fixed positioning 'reset filter' toggle as user scroll.
    document.addEventListener('scroll', function ( event ) {

        if ( window.innerWidth < 768 ) {
            rePositionFilterToggle();
        }

    }, false);

    // Fixed positioning 'reset filter' toggle as user scroll.
    window.addEventListener('load', (event) => {

        if ( window.innerWidth < 768 ) {
            var toggleButton = document.querySelectorAll( '.facet-template__toggle' );
            if ( toggleButton[0] ) {
                toggleButton = toggleButton[0];
                window.facetTemplateFilterButtonOffset = toggleButton.offsetTop;
                
            }
            rePositionFilterToggle();
        }

      });

    // Hide filter in mobile if one of the filter changed. Not sure if the functionality is needed, remove the comment when needed.
    // Code from https://facetwp.com/documentation/developers/javascript/facetwp-refresh/

    /* jQuery(document).on('facetwp-refresh', function() {
        var filters = document.querySelectorAll( '.ubc-wp-vote__facetwp-filters' );
        if ( window.innerWidth < 768 && filters[0] && filters[0].classList.contains( 'show' ) ) {
            filters[0].classList.remove( 'show' );
        }
     }); */

     function rePositionFilterToggle() {
        var toggleButton = document.querySelectorAll( '.facet-template__toggle' );
        if ( toggleButton[0] ) {            
            if ( window.scrollY > window.facetTemplateFilterButtonOffset && ! toggleButton[0].classList.contains( 'fixed' ) ) {
                toggleButton[0].classList.add( 'fixed' );
            }
            if ( window.scrollY < window.facetTemplateFilterButtonOffset && toggleButton[0].classList.contains( 'fixed' ) ) {
                toggleButton[0].classList.remove( 'fixed' );
            }
        }
     }
})();
