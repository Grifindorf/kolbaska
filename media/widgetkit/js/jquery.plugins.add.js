/**
 * Created by hellios on 22.07.16.
 */
$widgetkit.load('/media/widgetkit/widgets/lightbox/js/lightbox.js').done(function(){
    jQuery(function($){
        setTimeout(function() {
            $('a[data-lightbox]').lightbox({"titlePosition":"float","transitionIn":"fade","transitionOut":"fade","overlayShow":1,"overlayColor":"#777","overlayOpacity":0.7});
        }, 500);
    });
});