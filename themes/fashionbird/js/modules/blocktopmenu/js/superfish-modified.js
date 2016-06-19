$(function() {
		if ($(document.body).width() < 1199){

	
/* Mobile */
$('#menu-wrap').prepend('<div id="menu-trigger">Category <span class="menu-icon"></span></div>');		

$('#menu-trigger').toggle(
function() {
$(this).next('#menu-custom').slideToggle("slow"),{
duration: 'slow',
easing: 'linear'
};
$(this).addClass('menu-custom-icon');
},
function() {
$(this).next('#menu-custom').slideToggle("slow"),{
duration: 'slow',
easing: 'linear'
};
$(this).removeClass('mobile-close-2');
$(this).removeClass('menu-custom-icon');
}
)


$ ('.main-mobile-menu ul ul').addClass('menu-mobile-2'); 
$ ('#menu-custom ul ').addClass('menu-mobile-2'); 
$('#menu-custom  li').has('.menu-mobile-2').prepend('<span class="open-mobile-2"></span>');
$("#menu-custom   .open-mobile-2").toggle(
function() {
$(this).next().next('.menu-mobile-2').slideToggle("slow"),{
duration: 'slow',
easing: 'linear'
};
$(this).addClass('mobile-close-2');
},
function() {
$(this).next().next('.menu-mobile-2').slideToggle("slow"),{
duration: 'slow',
easing: 'linear'
};
$(this).removeClass('mobile-close-2');
}
)

	}
}); 


 


