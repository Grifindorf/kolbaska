/**
 * Created by hellios on 22.07.16.
 */
$ = jQuery;
$(window).scroll(function(){
    $('.header').css('left',-$(window).scrollLeft());
});