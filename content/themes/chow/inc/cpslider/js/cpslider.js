  (function($){
    "use strict";
    $(document).ready(function(){

        try {
    // variable is undefined
        var rsi = $('#homeSlider').royalSlider({

            controlNavigation:'thumbnails',
            imageScaleMode: cpslidervars.imageScaleMode,
            arrowsNav: false,
            arrowsNavHideOnTouch: true,
            slidesSpacing: 0,
            
            autoPlay: {
              // autoplay options go gere
              enabled: cpslidervars.autoPlay,
              pauseOnHover: true,
              delay: cpslidervars.delay,
            },

            fullscreen: false,
            loop: cpslidervars.loop,

            thumbs: {
              firstMargin: false,
              paddingBottom: 0,
              spacing: 0
            },

            numImagesToPreload: cpslidervars.numImagesToPreload,
            thumbsFirstMargin: false,

            keyboardNavEnabled: true,
            navigateByClick: false,
            fadeinLoadedSlide: cpslidervars.fadeinLoadedSlide,
            transitionType: cpslidervars.transitionType,

            block: {
              fadeEffect: true,
              moveEffect: 'top',
              delay: 0
            }

        }).data('royalSlider');

      $('#slider-next').click(function() {
          rsi.next();
      });

      $('#slider-prev').click(function() {
          rsi.prev();
      });


      $('#homeSliderAlt').royalSlider({
          arrowsNav: false,
          fadeinLoadedSlide: cpslidervars.fadeinLoadedSlide,
          controlNavigationSpacing: 0,
          controlNavigation: 'thumbnails',
          transitionType: cpslidervars.transitionType,

          autoPlay: {
            // autoplay options go gere
              enabled: cpslidervars.autoPlay,
              pauseOnHover: true,
              delay: cpslidervars.delay,
          },

          thumbs: {
            autoCenter: false,
            fitInViewport: true,
            orientation: 'vertical',
            spacing: 0,
            paddingBottom: 0
          },
          keyboardNavEnabled: true,
          imageScaleMode: cpslidervars.imageScaleMode,
          imageAlignCenter:true,
          slidesSpacing: 0,
          loop: cpslidervars.loop,
          loopRewind: true,
          numImagesToPreload: cpslidervars.numImagesToPreload,
          autoScaleSlider: true,
          autoScaleSliderWidth: 1180,
          autoScaleSliderHeight: 500,
      });

} catch(e) { /* ignore */ }
/* ------------------ End Document ------------------ */
});

})(this.jQuery);
