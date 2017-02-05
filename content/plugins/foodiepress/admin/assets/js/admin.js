(function ( $ ) {
	"use strict";

	$(function () {

		// ingredients managment
        function addIng() {
            var newElem = $('<tr class="ingridients-cont ing"><td><a title="Drag and drop rows to sort table" href="#" class="move">move</a></td><td><input name="cookingpressingridients_name[]" type="text" class="ingridient" value=""> </td><td><input name="cookingpressingridients_note[]" type="text" class="notes" value=""></td><td class="action"><a title="Delete ingridient" href="#" class="delete">Delete</a></td></tr>');
            newElem.appendTo('table#ingridients-sort');
        }

        $('.add_ingridient').click(function(e){
            e.preventDefault();
            addIng(); addIng(); addIng();
        })

        $('.add_separator').click(function(e){
            e.preventDefault();
            var newElem = $('<tr class="ingridients-cont separator"><td><a title="Drag and drop rows to sort table" href="#" class="move">move</a></td><td><input name="cookingpressingridients_name[]" type="text" class="ingridient"  value="" /></td><td><input name="cookingpressingridients_note[]" type="text" class="notes"  value="separator" /></td><td class="action"><a title="Delete ingridient" href="#" class="delete"><i class="icon icon-trash-o"></i></a></td></tr>');
            newElem.appendTo('table#ingridients-sort');
        })

        // remove ingredient
        $('#ingridients-sort .delete').live('click',function(e){
            e.preventDefault();
            $(this).parent().parent().remove();
        });

        // remove photo
        $('#remove_cpphoto').live('click',function(e){
            e.preventDefault();
            $('#foodiepress-photo-container img').remove();
            $('#cookingpressphoto').val('');
        });

       
        //sortable table
        var fixHelper =  function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index)
            {
              // Set helper cell sizes to match the original sizes
              $(this).width($originals.eq(index).width());
            });
            return $helper;
        };
        var deviceAgent = navigator.userAgent.toLowerCase();
        var agentID = deviceAgent.match(/(iphone|ipod|ipad)/);
        if (!agentID) {
            $('table#ingridients-sort tbody').sortable({
              helper: fixHelper
            });
        }


        var tags_json = foodiepress_script_vars.availabletags.replace(/&quot;/g, '"');
        // autocomplete tags
        $('#ingridients-sort input.ingridient').live('keyup.autocomplete', function(){
            $(this).autocomplete({
                source: jQuery.parseJSON(tags_json)
            });
        });

        var file_frame;

        $('#foodiepress-upload-button,.foodiepress-photo').on('click', function( event ){

        event.preventDefault();

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
          file_frame.open();
          return;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
          title: $( this ).data( 'uploader_title' ),
          button: {
            text: $( this ).data( 'uploader_button_text' ),
          },
          multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            var selection = file_frame.state().get('selection');
            selection.each(function(attachment) {
               $('.foodiepress-upload-photo').val(attachment.id);
               $.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType:'html',
                data: {
                    action: 'foodiepress_photo_update',
                    id: attachment.id

                },
                success:function(res) {
                    $('#foodiepress-photo-container').html(res);
                }
            });
            });
            // We set multiple to false so only get one image from the uploader

        });

        // Finally, open the modal
        file_frame.open();
        });
    });

}(jQuery));


