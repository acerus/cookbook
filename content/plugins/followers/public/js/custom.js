//used only on widgets.php
jQuery(document).ready(function ($) {

    $(document.body).on('change', '#widgets-right .icontype', function () {
        var type = $(this).val();
        if(type) {
            var here = $(this).parent().parent();
            var fieldname = here.find('input#services');
            var service = here.find(".icontype option[value='"+type+"']").text();
            here.find('#socialicons').append('<p><label>'+service+'</label><input type="hidden" class="social-name" value="'+service+'"/></p>');
            var service_name = [];
            here.find('.social-name').each(function (i){
                var service = $(this).val();
                service_name[service_name.length] = service;
            });
            fieldname.val(service_name.join(", "));
           
        }
    });

    $( "#widgets-right #socialicons" ).sortable({
        items: "> p",
        placeholder: "ui-state-highlight",
        revert:300,
        over: function(e, ui) { sortableIn = 1; },
        out: function(e, ui) { sortableIn = 0; },
        stop: function(e, ui) { 
             var here = ui.item.offsetParent();
             var fieldname = here.find('input#services');
             var service_name = [];
                here.find('.social-name').each(function (i){
                    var service = $(this).val();
                    service_name[service_name.length] = service;
                });
                fieldname.val(service_name.join(", "));
           
        },
        beforeStop: function (event, ui) {
            newItem = ui.item;
            if (sortableIn == 0) {
              ui.item.remove();
          }
      },

    });

});

