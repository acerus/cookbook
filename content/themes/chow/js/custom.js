/* ----------------- Start Document ----------------- */
/*global chow*/
(function($){
    "use strict";

    $(document).ready(function(){



  /*----------------------------------------------------*/
  /*  Navigation
  /*----------------------------------------------------*/

    function menumobile(){
        var winWidth = $(window).width();
        if( winWidth < 973 ) {
            $('#navigation').removeClass('menu');
            $('#navigation li').removeClass('dropdown');
            $('#navigation').superfish('destroy');
        } else {
            $('#navigation').addClass('menu');
            $('#navigation').superfish({
                delay:       300,                               // one second delay on mouseout
                animation:   {opacity:'show', height:'show'},   // fade-in and slide-down animation
                speed:       200,                               // animation speed
                speedOut:    50                                 // out animation speed
            });
        }
    }

    $(window).resize(function (){
        menumobile();
    });
    menumobile();


  /*----------------------------------------------------*/
  /*  Royal Slider
  /*----------------------------------------------------*/



    // Recipe Slider
    $(".recipeSlider").royalSlider({

        imageScalePadding: 0,
        keyboardNavEnabled: true,

        navigateByClick: false,
        fadeinLoadedSlide: true,
        arrowsNavAutoHide: false,

        imageScaleMode: 'fill',
        arrowsNav: true,
        slidesSpacing: 0,

    });


    // Product Slider
    $(".productSlider").royalSlider({

        autoScaleSlider: true,
      autoScaleSliderWidth: 580,
      autoHeight: true,
      //autoScaleSliderHeight: 500,
      arrowsNavAutoHide: false,
      loop: false,
      slidesSpacing: 0,

      imageScaleMode: 'fill',
      imageAlignCenter:false,

      navigateByClick: false,
      numImagesToPreload:2,

    });


    // Alternative Homepage Slider
    $('#homeSliderAlt').royalSlider({
      arrowsNav: false,
      fadeinLoadedSlide: true,
      controlNavigationSpacing: 0,
      controlNavigation: 'thumbnails',

      thumbs: {
        autoCenter: false,
        fitInViewport: true,
        orientation: 'vertical',
        spacing: 0,
        paddingBottom: 0
      },
      keyboardNavEnabled: true,
      imageScaleMode: 'fill',
      imageAlignCenter:true,
      slidesSpacing: 0,
      loop: false,
      loopRewind: true,
      numImagesToPreload: 3,
      autoScaleSlider: true,
      autoScaleSliderWidth: 1180,
      autoScaleSliderHeight: 500,


    });



    /*----------------------------------------------------*/
    /*  Image Height Fix
    /*----------------------------------------------------*/
    function fixliststylebox(){
        var winWidth = $(window).width();
        if(winWidth > 973) {
           $( ".list-style .recipe-box-content" ).each(function() {
            var recipeHeight = $(this).outerHeight();
            $(this).parent().find('.thumbnail-holder').height(recipeHeight);
        });
       } else {
        $('.thumbnail-holder').css({
            height: 'auto'
        });
       }
    }


    $(window).load(function(){
        fixliststylebox();

    });
    $(window).resize(function (){
        fixliststylebox();

    });



    /*----------------------------------------------------*/
    /*  Tooltips
    /*----------------------------------------------------*/
    $(".tooltip.top").tipTip({
          defaultPosition: "top"
    });

    $(".tooltip.bottom").tipTip({
          defaultPosition: "bottom"
     });

     $(".tooltip.left").tipTip({
           defaultPosition: "left"
     });

    $(".tooltip.right").tipTip({
          defaultPosition: "right"
    });





  /*----------------------------------------------------*/
  /*  Mobile Navigation
  /*----------------------------------------------------*/

  var navigation = responsiveNav(".nav-collapse", {
        animate: true,                    // Boolean: Use CSS3 transitions, true or false
        transition: 284,                  // Integer: Speed of the transition, in milliseconds
        label: chow.menulabel,                    // String: Label for the navigation toggle
        insert: "before",                 // String: Insert the toggle before or after the navigation
        customToggle: "",                 // Selector: Specify the ID of a custom toggle
        closeOnNavClick: false,           // Boolean: Close the navigation when one of the links are clicked
        openPos: "relative",              // String: Position of the opened nav, relative or static
        navClass: "nav-collapse",         // String: Default CSS class. If changed, you need to edit the CSS too!
        navActiveClass: "js-nav-active",  // String: Class that is added to <html> element when nav is active
        jsClass: "js",                    // String: 'JS enabled' class which is added to <html> element
        init: function(){},               // Function: Init callback
        open: function(){},               // Function: Open callback
        close: function(){}               // Function: Close callback
    });



    /*----------------------------------------------------*/
    /*  Isotope
    /*----------------------------------------------------*/

    $(window).load(function(){
        var $container = $('.isotope,body.woocommerce .products');
        $container.isotope({ itemSelector: '.recipe-box, .isotope-box, .masonry-shop-item', layoutMode: chow.isotope });
    });



    /*----------------------------------------------------*/
    /*  Advanced Search
    /*----------------------------------------------------*/

    $('.adv-search-btn').on( "click", function(event) {
        var slideDuration = 200;

        if($(this).hasClass('opened')){
            $('.adv-search-btn').removeClass('active');
           $(this).removeClass('opened').find('i').addClass('fa-caret-down').removeClass('fa-caret-up');
           $('.extra-search-options').stop(true, true).fadeOut({ duration: slideDuration, queue: false }).slideUp(slideDuration);
        } else {
            $('.adv-search-btn').addClass('active');
            $(this).addClass('opened').find('i').addClass('fa-caret-up').removeClass('fa-caret-down');
            $('.extra-search-options').removeClass('closed').stop(true, true).fadeIn({ duration: slideDuration, queue: false }).css('display', 'none').slideDown(slideDuration);
        }
        event.preventDefault();
    });

    var config = {
      '.widget select'           : {disable_search_threshold: 10, width:"100%"},
      '.chosen-select'           : {disable_search_threshold: 10, width:"100%"},
      '.chosen-select-deselect'  : {allow_single_deselect:true, width:"100%"},
      '.chosen-select-no-single,.orderby' : {disable_search_threshold:10, width:"100%"},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    };
      
    
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
    


    /*----------------------------------------------------*/
    /*  Tabs
    /*----------------------------------------------------*/

    var $tabsNav    = $('.tabs-nav'),
     $tabsNavLis = $tabsNav.children('li');
    // $tabContent = $('.tab-content');

    $tabsNav.each(function() {
     var $this = $(this);

        $this.next().children('.tab-content').stop(true,true).hide()
        .first().show();

        $this.children('li').first().addClass('active').stop(true,true).show();
     });

    $tabsNavLis.on('click', function(e) {
         var $this = $(this);

         $this.siblings().removeClass('active').end()
        .addClass('active');

        $this.parent().next().children('.tab-content').stop(true,true).hide()
        .siblings( $this.find('a').attr('href') ).fadeIn();

        e.preventDefault();
    });



    /*----------------------------------------------------*/
    /*  Accordions
    /*----------------------------------------------------*/

    var $accor = $('.accordion');

     $accor.each(function() {
        $(this).addClass('ui-accordion ui-widget ui-helper-reset');
        $(this).find('h3').addClass('ui-accordion-header ui-helper-reset ui-state-default ui-accordion-icons ui-corner-all');
        $(this).find('div').addClass('ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom');
        $(this).find("div").hide().first().show();
        $(this).find("h3").first().removeClass('ui-accordion-header-active ui-state-active ui-corner-top').addClass('ui-accordion-header-active ui-state-active ui-corner-top');
        $(this).find("span").first().addClass('ui-accordion-icon-active');
    });

    var $trigger = $accor.find('h3');

    $trigger.on('click', function(e) {
        var location = $(this).parent();

        if( $(this).next().is(':hidden') ) {
            var $triggerloc = $('h3',location);
            $triggerloc.removeClass('ui-accordion-header-active ui-state-active ui-corner-top').next().slideUp(300);
            $triggerloc.find('span').removeClass('ui-accordion-icon-active');
            $(this).find('span').addClass('ui-accordion-icon-active');
            $(this).addClass('ui-accordion-header-active ui-state-active ui-corner-top').next().slideDown(300);
        }
         e.preventDefault();
    });



    /*----------------------------------------------------*/
    /*  Magnific Popup
    /*----------------------------------------------------*/

      $('body').magnificPopup({
        type: 'image',
        delegate: 'a.mfp-gallery',

        fixedContentPos: true,
        fixedBgPos: true,

        overflowY: 'auto',

        closeBtnInside: true,
        preloader: true,

        removalDelay: 0,
        mainClass: 'mfp-fade',

        gallery:{enabled:true},

        callbacks: {
          buildControls: function() {
            console.log('inside'); this.contentContainer.append(this.arrowLeft.add(this.arrowRight));
          }

        }
      });


      $('.popup-with-zoom-anim').magnificPopup({
        type: 'inline',

        fixedContentPos: false,
        fixedBgPos: true,

        overflowY: 'auto',

        closeBtnInside: true,
        preloader: false,

        midClick: true,
        removalDelay: 300,
        mainClass: 'my-mfp-zoom-in'
      });



      $('.wp-caption a').magnificPopup({
          type: 'image',
          mainClass: 'mfp-fade',
          closeOnContentClick: true,
          image: {
            verticalFit: true
          }
        });      

      $('.recipeSlider-single .rsSlide, .productSlider .rsSlide').magnificPopup({
          delegate: 'a',
          mainClass: 'mfp-fade',
          type: 'image',
          closeOnContentClick: true,
          image: {
            verticalFit: true
          }
        });


      $('.popup-youtube, .popup-vimeo, .popup-gmaps').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,

        fixedContentPos: false
      });



    /*----------------------------------------------------*/
    /*  Toggles
    /*----------------------------------------------------*/
    $(".toggle-container").hide();
    $(".trigger").toggle(function(){
          $(this).addClass("active");
    }, function () {
        $(this).removeClass("active");
    });
    $(".trigger").on( "click", function() {
         $(this).next(".toggle-container").slideToggle();
    });

     $(".trigger.opened").toggle(function(){
         $(this).removeClass("active");
     }, function () {
         $(this).addClass("active");
     });

    $(".trigger.opened").addClass("active").next(".toggle-container").show();




    function addIng() {
      var newElem = $('tr.ingredients-cont.ing:first').clone();
      newElem.find('input').val('');
      newElem.appendTo('table#ingredients-sort');
    }

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
    if ($("table#ingredients-sort").is('*')) {
      $('.add_ingredient').on( "click", function(e) {
        e.preventDefault();
        addIng();
      });

      $('.add_separator').on( "click", function(e) {
        e.preventDefault();
        var newElem = $('<tr class="ingredients-cont separator"><td class="icon"><i class="fa fa-arrows"></i></td><td><input name="ingredient_name[]" type="text" class="ingredient" placeholder="" /></td><td><input name="ingredient_note[]" type="text" class="notes" value="separator" placeholder="Separator" /></td><td class="action"><a title="Delete" class="delete" href="#"><i class="fa fa-remove"></i></a></td></tr>');
        newElem.appendTo('table#ingredients-sort');
      });
      // remove ingredient
      $('#ingredients-sort .delete').live('click',function(e){
        e.preventDefault();
        $(this).parent().parent().remove();
      });
        var deviceAgent = navigator.userAgent.toLowerCase();
        var agentID = deviceAgent.match(/(iphone|ipod|ipad)/);
        if (!agentID) {
          $('table#ingredients-sort tbody').sortable({
            forcePlaceholderSize: true,
            forceHelperSize: true,
            placeholder : 'sortableHelper',
            helper: fixHelper,
            zIndex: 999990,
            opacity: 0.6,
            tolerance: "pointer"
          });
        }
    }


    
    $("#chooseFiles").change(function () {
        $("#dvPreview").html("");
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
        if (regex.test($(this).val().toLowerCase())) {
            if ($.browser.msie && parseFloat($.browser.version) <= 9.0) {
                $(".recipe-gallery").show();
                $(".recipe-gallery")[0].filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = $(this).val();
            }
            else {
                if (typeof (FileReader) != "undefined") {
                    $(".recipe-gallery").show().empty();
                    $(".recipe-gallery").append("<img />");
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(".recipe-gallery img").attr("src", e.target.result);
                    };
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    alert("This browser does not support FileReader - the image won't be previewed but it will be uploaded.");
                }
            }
        } else {
            alert("Please upload a valid image file.");
        }
    });

    $('.stars a').on( "click", function() {
        $('.stars a').removeClass('prevactive');
        $(this).prevAll().addClass('prevactive');
      }).hover(
        function() {
          $('.stars a').removeClass('prevactive');
          $(this).addClass('prevactive').prevAll().addClass('prevactive');
        }, function() {
          $('.stars a').removeClass('prevactive');
          $('.stars a.active').prevAll().addClass('prevactive');
        }
      );


      $('.print-simple').on( "click", function() {
        window.print();
        return false;
    });

var pixelRatio = !!window.devicePixelRatio ? window.devicePixelRatio : 1;

    $(window).on("load", function() {
      if (pixelRatio > 1) {
        if(chow.retinalogo) {
          $('#logo img').attr('src',chow.retinalogo);
        }
        var winWidth = $(window).width();
        if( winWidth < 973 ) {
          $('#logo img').attr('src',chow.mobileretinalogo);
        } else {
          $('#logo img').attr('src',chow.retinalogo);
        }
      }
    });

    // Quantity buttons

  $( 'div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)' ).addClass( 'buttons_added' ).append( '<input type="button" value="+" class="plus" />' ).prepend( '<input type="button" value="-" class="minus" />' );
    $('.plus').val('\uf067');
    $('.minus').val('\uf068');
    $('.qty').attr('type','text');

  $( document ).on( 'click', '.plus, .minus', function() {

    // Get values
    var $qty    = $( this ).closest( '.quantity' ).find( '.qty' ),
      currentVal  = parseFloat( $qty.val() ),
      max     = parseFloat( $qty.attr( 'max' ) ),
      min     = parseFloat( $qty.attr( 'min' ) ),
      step    = $qty.attr( 'step' );

    // Format values
    if ( ! currentVal || currentVal === '' || currentVal === 'NaN' ) currentVal = 0;
    if ( max === '' || max === 'NaN' ) max = '';
    if ( min === '' || min === 'NaN' ) min = 0;
    if ( step === 'any' || step === '' || step === undefined || parseFloat( step ) === 'NaN' ) step = 1;

    // Change the value
    if ( $( this ).is( '.plus' ) ) {

      if ( max && ( max == currentVal || currentVal > max ) ) {
        $qty.val( max );
      } else {
        $qty.val( currentVal + parseFloat( step ) );
      }

    } else {

      if ( min && ( min == currentVal || currentVal < min ) ) {
        $qty.val( min );
      } else if ( currentVal > 0 ) {
        $qty.val( currentVal - parseFloat( step ) );
      }

    }

    // Trigger change event
    $qty.trigger( 'change' );

  });

  $('.responsive-table').stacktable();
  


 // ------------------ End Document ------------------ //
});

})(this.jQuery);

