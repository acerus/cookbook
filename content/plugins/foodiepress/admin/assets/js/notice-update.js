
(function ( $ ) {
	"use strict";

	$(function () {

		$(document).on( 'click', '.foodiepress-notice .notice-dismiss', function() {

		    $.ajax({
		        url: ajaxurl,
		        data: {
		            action: 'foodiepress_dismiss_notice'
		        }
		       
		    })

		})

    });

}(jQuery));


