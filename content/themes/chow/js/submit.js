/* ----------------- Start Document ----------------- */
/*global chow*/
(function($){
    "use strict";

    $(document).ready(function(){


    var tags_json = foodiepress_script_vars.availabletags.replace(/&quot;/g, '"');
        // autocomplete tags
    $('input.ingredient_name').live('keyup.autocomplete', function(){
        $(this).autocomplete({
            source: jQuery.parseJSON(tags_json)
        });
    });
 // ------------------ End Document ------------------ //
});

})(this.jQuery);

