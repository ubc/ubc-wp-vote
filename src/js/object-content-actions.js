(function() {

    // Helpers
    function lockAllActions() {
        var actions = document.querySelectorAll( '.ubc_wp-vote__action, .ubc-wp-vote__star-rating .ubc-wp-vote_star_rating--current .star' );
        actions.forEach(action => {
            action.disabled = true;
        });
    }

    function unLockAllActions() {
        var actions = document.querySelectorAll( '.ubc_wp-vote__action, .ubc-wp-vote__star-rating .ubc-wp-vote_star_rating--current .star' );
        actions.forEach(action => {
            action.disabled = false;
        });
    }

    function getNodeIndex (element) {
        return Array.from(element.parentNode.childNodes).indexOf(element);
      }

    // Vote
    document.addEventListener('click', function ( event ) {

        if ( event.target.matches('.ubc-wp-vote__thumbs-up') ) {
            event.preventDefault();
            onThumbsUp( event.target );
        }

        if ( event.target.matches('.ubc-wp-vote__thumbs-down') ) {
            event.preventDefault();
            onThumbsDown( event.target );
        }

    }, false);

    function onThumbsUp( element ) {
        var rootNode = element.closest('.ubc-wp-vote__thumbs');
        var objectId = rootNode.dataset.id;
        var objectType = rootNode.dataset.type;
        var totalNode = rootNode.querySelector('.ubc-wp-vote_thumbs-up-total');
        
        var data = {
            action: 'ubc_ctlt_wp_vote_upvote',
            'object_id': objectId,
            'object_type': objectType,
            security: ubc_ctlt_wp_vote.ajax_nonce
        };

        lockAllActions();

        jQuery.post( ubc_ctlt_wp_vote.ajax_url, data )
            .done( response => {
                element.classList.toggle('active');
                totalNode.innerHTML = element.classList.contains('active') ? parseInt( totalNode.innerHTML ) + 1 : parseInt( totalNode.innerHTML ) - 1;
            })
            .fail( error => {
                alert( error.responseText );
            })
            .always( () => {
                unLockAllActions();
            });
    }

    function onThumbsDown( element ) {
        var rootNode = element.closest('.ubc-wp-vote__thumbs');
        var objectId = rootNode.dataset.id;
        var objectType = rootNode.dataset.type;
        var totalNode = rootNode.querySelector('.ubc-wp-vote_thumbs-up-total');

        var data = {
            action: 'ubc_ctlt_wp_vote_downvote',
            'object_id': objectId,
            'object_type': objectType,
            security: ubc_ctlt_wp_vote.ajax_nonce
        };

        lockAllActions();

        jQuery.post( ubc_ctlt_wp_vote.ajax_url, data )
            .done( response => {
                element.classList.toggle('active');
                totalNode.innerHTML = element.classList.contains('active') ? parseInt( totalNode.innerHTML ) + 1 : parseInt( totalNode.innerHTML ) - 1;
            })
            .fail( error => {
                alert( error.responseText );
            })
            .always( () => {
                unLockAllActions();
            });
    }

    // Star Rating
    document.addEventListener('click', function ( event ) {

        if ( event.target.matches('.ubc-wp-vote__star-rating .ubc-wp-vote_star_rating--current .star') ) {
            event.preventDefault();
            var rootNode = event.target.closest('.ubc-wp-vote__star-rating');
            var objectId = rootNode.dataset.id;
            var objectType = rootNode.dataset.type;

            var ratingNode = rootNode.querySelector('.ubc-wp-vote__rating');
            var newCurrentRating = getNodeIndex( event.target );
            var newRatingAverage = parseFloat( ratingNode.dataset.current_average ) === 0 ? ( parseFloat( ratingNode.dataset.overall_average ) * parseInt( ratingNode.dataset.overall_count ) + newCurrentRating ) / ( parseInt( ratingNode.dataset.overall_count ) + 1 ) : ( parseFloat( ratingNode.dataset.overall_average ) * parseInt( ratingNode.dataset.overall_count ) + newCurrentRating - parseInt( ratingNode.dataset.current_average ) ) / parseInt( ratingNode.dataset.overall_count );

            var data = {
                action: 'ubc_ctlt_wp_vote_rating',
                'object_id': objectId,
                'object_type': objectType,
                'vote_data': newCurrentRating,
                security: ubc_ctlt_wp_vote.ajax_nonce
            };

            lockAllActions();

            jQuery.post( ubc_ctlt_wp_vote.ajax_url, data )
            .done( response => {
                rootNode.querySelector('.ubc-wp-vote_star_rating--current').innerHTML = response;
                ratingNode.innerHTML = newRatingAverage;
            })
            .fail( error => {
                alert( error.responseText );
            })
            .always( () => {
                unLockAllActions();
            });
        }

    }, false);

})();