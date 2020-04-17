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
        console.log(element.parentNode);
        var array = Array.from(element.parentNode.childNodes);
        console.log(array);
        return Array.from(element.parentNode.childNodes).indexOf(element);
      }

    // Vote
    document.addEventListener('click', function ( event ) {
        event.preventDefault();

        if ( event.target.matches('.ubc-wp-vote__thumbs-up') ) {
            onThumbsUp( event.target );
        }

        if ( event.target.matches('.ubc-wp-vote__thumbs-down') ) {
            onThumbsDown( event.target );
        }

    }, false);

    function onThumbsUp( element ) {
        var rootNode = element.closest('.ubc-wp-vote__thumbs');
        var objectId = rootNode.dataset.id;
        var objectType = rootNode.dataset.type;
        
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
        event.preventDefault();

        if ( event.target.matches('.ubc-wp-vote__star-rating .ubc-wp-vote_star_rating--current .star') ) {
            var rootNode = event.target.closest('.ubc-wp-vote__star-rating');
            var objectId = rootNode.dataset.id;
            var objectType = rootNode.dataset.type;

            var data = {
                action: 'ubc_ctlt_wp_vote_rating',
                'object_id': objectId,
                'object_type': objectType,
                'vote_data': getNodeIndex( event.target ),
                security: ubc_ctlt_wp_vote.ajax_nonce
            };

            lockAllActions();

            jQuery.post( ubc_ctlt_wp_vote.ajax_url, data )
            .done( response => {
                rootNode.querySelector('.ubc-wp-vote_star_rating--current').innerHTML = response;
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