(function ( $ ) {
	"use strict";

	$(function () {

        $('.instructions ul li, ul.ingredients li:not(.separator)').click(function(){
            $(this).toggleClass('active')
        })

		// Place your public-facing JavaScript here


        $('.comment-form-rating .star').on( "click", function() {

          $('.star').removeClass('prevactive').removeClass('active');
          $(this).addClass('active').prevAll().addClass('prevactive');
          var stars =  $('.comment-form-rating .star.active').attr('id').substring( 4 );
          console.log(stars);
          $('#foodiepress-post-rating').val(stars);
        }).hover(
        function() {
            $('.star').removeClass('prevactive');
            $(this).addClass('prevactive').prevAll().addClass('prevactive');
        }, function() {
            $('.star').removeClass('prevactive');
            $('.star.active').prevAll().addClass('prevactive');
        });


        $(".foodiepress-add-to-fav").click( function(e) {
            e.preventDefault();
            var post_id = $(this).data("post_id");
            var nonce = $(this).data("nonce");

            $.ajax({
               type : "post",
               dataType : "json",
               url : foodiepress.ajaxurl,
               data : {action: "add_to_fav", post_id : post_id, nonce: nonce},
               success: function(response) {
                  if(response.type == "success") {
                     $(".foodiepress-add-to-fav").text(foodiepress.addedtolist)
                  }
                  else {
                     alert(response.message)
                  }
               }
            })   
        });



        $(".foodiepress-remove-fav").click( function(e) {
            e.preventDefault();
            var handler = $(this);
            var post_id = $(this).data("post_id");
            var nonce = $(this).data("nonce");

            $.ajax({
               type : "post",
               dataType : "json",
               url : foodiepress.ajaxurl,
               data : {action: "remove_fav", post_id : post_id, nonce: nonce},
               success: function(response) {
                  console.log(response);
                  if(response.type == "success") {
                      handler.closest('tr').fadeOut();
                  }
                  else {
                     alert(response.message)
                  }
               }
            })   
        });
	});

}(jQuery));


        