


	$(document).ready(function() {
$('.blockuserinfo a').on('click touchend', function(e) {
    var el = $(this);
    var link = el.attr('href');
    window.location = link;
});

});

	$(document).ready(function() {
$('.breadcrumb a').on('click touchend', function(e) {
    var el = $(this);
    var link = el.attr('href');
    window.location = link;
});
});

$(window).load(function () {
      $(function(){
      	 $("#contact_form #fileUpload, .customizableProductsFile .customization_block_input,  .compare .comparator,#layered_form #ul_layered_quantity_0 input,#layered_form #ul_layered_quantity_0 ckeckbox, #ul_layered_condition_0 input,ul_layered_id_attribute_group_3 input,#ul_layered_id_attribute_group_3 input").uniform();
	  });

        $('#layered_form select').coreUISelect({
            jScrollPane : {
                verticalDragMinHeight: 20,
                verticalDragMaxHeight: 20,
                showArrows : true
            }
     });
	 
	   $('#nb_item').coreUISelect({
            jScrollPane : {
                verticalDragMinHeight: 20,
                verticalDragMaxHeight: 20,
                showArrows : true
            }
     });
	 
  
});
     $(function(){
    $('body').tooltip({
        selector: "[rel=tooltip]",
        placement: "bottom" 
    });
});  
     $(function(){
    $('body .addhomefeatured').tooltip({trigger: 'manual'}).tooltip('show');
});  
     $(function(){
    $('.breadcrumb a').tooltip({
        placement: "bottom" 
    });
}); 
   
//   CLOUD ZOOM

$(document).ready(function() {
	if ($('#zoom').length) {
		$('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
	}
	
});

//   COOKIE AND TAB GRID-LIST
(function($) {
		$(function() {
			function createCookie(name,value,days) {
				if (days) {
					var date = new Date();
					date.setTime(date.getTime()+(days*24*60*60*1000));
					var expires = "; expires="+date.toGMTString();
				}
			else var expires = "";
				document.cookie = name+"="+value+expires+"; path=/";
			}
			function readCookie(name) {
				var nameEQ = name + "=";
				var ca = document.cookie.split(';');
				for(var i=0;i < ca.length;i++) {
					var c = ca[i];
					while (c.charAt(0)==' ') c = c.substring(1,c.length);
					if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
			}
			return null;
		}
	function eraseCookie(name) {
		createCookie(name,"",-1);
	}

//TAB GRID-LIST
$('ul.product_view').each(function(i) {
	var cookie = readCookie('tabCookie'+i);
	if (cookie) $(this).find('li').eq(cookie).addClass('current').siblings().removeClass('current')
		.parents('#center_column').find('#product_list').addClass('list').removeClass('grid').eq(cookie).addClass('grid').removeClass('list');
	})
	$('ul.product_view').delegate('li:not(.current)', 'click', function(i) {
	$(this).addClass('current').siblings().removeClass('current')
	.parents('#center_column').find('#product_list').removeClass('grid').addClass('list').eq($(this).index()).addClass('grid').removeClass('list')
	var cookie = readCookie('tabCookie'+i);
	if (cookie) $(this).find('#product_list').eq(cookie).removeClass('grid').addClass('list').siblings().removeClass('list')
	var ulIndex = $('ul.product_view').index($(this).parents('ul.product_view'));
	eraseCookie('tabCookie'+ulIndex);
	createCookie('tabCookie'+ulIndex, $(this).index(), 365);
	})
	})
})(jQuery)


//   TOGGLE FOOTER

$(window).load(function(){
	if ($(document.body).width()< 751){
		$('.modules .block h4').on('click', function(){
			$(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
		})
		$('.modules').addClass('accordion').find('.toggle_content').slideUp('fast');
		}else{
		$('.modules h4').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
		$('.modules').removeClass('accordion');
		}
  
});
var responsiveflag = false;
function accordion(status){	
		if(status == 'enable'){
			$('.modules .block h4').on('click', function(){
				$(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
			})
			$('.modules').addClass('accordion').find('.toggle_content').slideUp('fast');
		}else{
			$('.modules h4').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
			$('.modules').removeClass('accordion');
		}
	}		
function toDo(){
	   if ($(document.body).width() < 751 && responsiveflag == false){
		    accordion('enable');
			responsiveflag = true;		
		}
		else if ($(document.body).width() > 751){
			accordion('disable');
	        responsiveflag = false;
		}
}	
toDo();
$(window).resize(function(){toDo();});


//   PRODUCT CLOUD ZOOM DISABLE IMG

$(document).ready(function() {  
	$(function(){     
		$('#zoom1').parent().on('click',function(){
		 var perehod = $(this).attr("perehod");
		  if (perehod=="false") {
		   return true;
		   } else {
			return false;
		   }
		});     
	});
});

//   OTHER SCRIPT

$(document).ready(function() {
       $ ('#order_steps li:even').addClass ('even');
       $ ('#order_steps li:odd').addClass ('odd');
	   $ ('.list-order-step li').last().addClass ('last');
	   $ ('#featured_products .list_carousel li').last().addClass ('last');
	   $ ('#product_comments_block_tab > div').last().addClass('last');
	   $ ('#viewed-products_block_left ul li').last().addClass('last');
	   
	   
});





//   TOGGLE PAGE PRODUCT (TAB)

$(window).load(function(){
	if ($(document.body).width()< 480){
		$('.page_product_box h3').on('click', function(){
			$(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
		})
		$('.page_product_box').addClass('accordion');
	
		}else{
		$('.page_product_box h3').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
		$('.page_product_box').removeClass('accordion');
		}
  
});
var responsiveflag = false;
function accordion(status){	
		if(status == 'enable'){
			$('.page_product_box h3').on('click', function(){
				$(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
			})
			$('.page_product_box').addClass('accordion').find('.toggle_content').slideUp('fast');
		}else{
			$('.page_product_box h3').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
			$('.page_product_box').removeClass('accordion');
		}
	}		
function toDo(){
	   if ($(document.body).width() < 480 && responsiveflag == false){
		    accordion('enable');
			responsiveflag = true;
				
		}
		else if ($(document.body).width() > 480){
			accordion('disable');
	        responsiveflag = false;
		}
}	
toDo();
$(window).resize(function(){toDo();});

//   TOGGLE RIGHT COLUMN

$(window).load(function(){
	if ($(document.body).width() < 751){
		$('#right_column h4').on('click', function(){
			$(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
		})
		$('#right_column').addClass('accordion').find('.toggle_content').slideUp('fast');
		}else{
		$('#right_column h4').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
		$('#right_column').removeClass('accordion');
		}
  
});
var responsiveflag = false;
function accordion(status){	
		if(status == 'enable'){
			$('#right_column h4').on('click', function(){
				$(this).toggleClass('active').parent().find('.toggle_content').slideToggle('medium');
			})
			$('#right_column').addClass('accordion').find('.toggle_content').slideUp('fast');
		}else{
			$('#right_column h4').removeClass('active').off().parent().find('.toggle_content').slideDown('fast');
			$('#right_column').removeClass('accordion');
		}
	}		
function toDo(){
	   if ($(document.body).width() < 751 && responsiveflag == false){
		    accordion('enable');
			responsiveflag = true;
				
		}
		else if ($(document.body).width() > 751){
			accordion('disable');
	        responsiveflag = false;
		}
}	
toDo();
$(window).resize(function(){toDo();});


// language script
	
$(document).ready(function(){  

	   $('.inner-carrencies').on('click',function(event){
        event.stopPropagation();
        if ( $('.selected_language.mobile-open').length > 0 ) {
            $('.countries_ul:visible').slideToggle("slow");
            $('.selected_language').removeClass('mobile-open');
				$('.selected_language').parent().parent().removeClass('mobile-open');
				
        }
    }); 
				   $('.mobile-link-top h4').on('click',function(event){
     event.stopPropagation();
        if ( $('.selected_language.mobile-open').length > 0 ) {
            $('.countries_ul:visible').slideToggle("slow");
            $('.selected_language').removeClass('mobile-open');
				$('.selected_language').parent().parent().removeClass('mobile-open');
				$('.inner-carrencies').parent().parent().removeClass('mobile-open');
        }
    }); 
				   $('#header_user').on('click',function(event){
        event.stopPropagation();
        if ( $('.selected_language.mobile-open').length > 0 ) {
            $('.countries_ul:visible').slideToggle("slow");
            $('.selected_language').removeClass('mobile-open');
				$('.selected_language').parent().parent().removeClass('mobile-open');
				$('.inner-carrencies').parent().parent().removeClass('mobile-open');
        }
    }); 
	
	
	
	
	// mobile script language 
    $('.selected_language').click(function(event){
        event.stopPropagation();
        if ( $(this).hasClass('mobile-open') ) {
            $(this).removeClass('mobile-open');
			$(this).parent().parent().removeClass('mobile-open');	
            $(this).siblings('.countries_ul').stop(true, true).delay(400).slideUp(300),{
duration: 'slow',
easing: 'linear'
};
        } else {
            $('.selected_language.mobile-open').removeClass('.mobile-open').siblings('.countries_ul').stop(true, true).delay(400).slideUp(300),{
duration: 'slow',
easing: 'linear'
};
            $(this).addClass('mobile-open');
$(this).parent().parent().addClass('mobile-open');			
            $(this).siblings('.countries_ul').stop(true, true).slideDown(400),{
duration: 'slow',
easing: 'linear'
};
        }
    });  
});



// carrencies script
$(document).ready(function(){   

	   $('.selected_language').on('click',function(event){
        event.stopPropagation();
        if ( $('.inner-carrencies.mobile-open').length > 0 ) {
            $('.currencies_ul:visible').slideToggle("slow");
            $('.inner-carrencies').removeClass('mobile-open');
			$('.inner-carrencies').parent().parent().removeClass('mobile-open');
        }
    });
		   $('.mobile-link-top h4').on('click',function(event){
        event.stopPropagation();
        if ( $('.inner-carrencies.mobile-open').length > 0 ) {
            $('.currencies_ul:visible').slideToggle("slow");
            $('.inner-carrencies').removeClass('mobile-open');
			$('.inner-carrencies').parent().parent().removeClass('mobile-open');
        }
    }); 
	
			   $('#header_user').on('click',function(event){
        event.stopPropagation();
        if ( $('.inner-carrencies.mobile-open').length > 0 ) {
            $('.currencies_ul:visible').slideToggle("slow");
            $('.inner-carrencies').removeClass('mobile-open');
			$('.inner-carrencies').parent().parent().removeClass('mobile-open');
			
        }
    }); 
	
    $('.inner-carrencies').click(function(event){
        event.stopPropagation();
        if ( $(this).hasClass('mobile-open') ) {
            $(this).removeClass('mobile-open');
			$(this).parent().parent().removeClass('mobile-open');
            $(this).siblings('.currencies_ul').stop(true, true).delay(400).slideUp(300),{
duration: 'slow',
easing: 'linear'
};
        } else {
            $('.inner-carrencies.mobile-open').removeClass('.mobile-open').siblings('.currencies_ul').stop(true, true).delay(400).slideUp(300),{
duration: 'slow',
easing: 'linear'
};
            $(this).addClass('mobile-open');
			$(this).parent().parent().addClass('mobile-open');	
            $(this).siblings('.currencies_ul').stop(true, true).slideDown(400),{
duration: 'slow',
easing: 'linear'
};
        }
    }); 
});


// carrencies script 
$(document).ready(function(){  
	   $('.selected_language').on('click',function(event){
    event.stopPropagation();
        if ( $('.mobile-link-top h4.act').length > 0 ) {
            $('#mobilelink:visible').slideToggle("slow");
            $('.mobile-link-top h4').removeClass('act');
        }
    }); 
	
$('.inner-carrencies').on('click',function(event){
        event.stopPropagation();
        if ( $('.mobile-link-top h4.act').length > 0 ) {
            $('#mobilelink:visible').slideToggle("slow");
            $('.mobile-link-top h4').removeClass('act');
        }
		  });
$('#header_user').on('click',function(event){
        event.stopPropagation();
        if ( $('.mobile-link-top h4.act').length > 0 ) {
            $('#mobilelink:visible').slideToggle("slow");
            $('.mobile-link-top h4').removeClass('act');
        }	
    }); 
	
	
    $('.mobile-link-top h4').click(function(event){
        event.stopPropagation();
        if ( $(this).hasClass('act') ) {
            $(this).removeClass('act');
            $(this).siblings('#mobilelink').stop(true, true).delay(400).slideUp(300),{
duration: 'slow',
easing: 'linear'
};
        } else {
            $('.mobile-link-top h4.act').removeClass('.act').siblings('#mobilelink').stop(true, true).delay(400).slideUp(300),{
duration: 'slow',
easing: 'linear'
};
            $(this).addClass('act');
            $(this).siblings('#mobilelink').stop(true, true).slideDown(400),{
duration: 'slow',
easing: 'linear'
};
        }
    }); 


});




// carrencies script
$(document).ready(function(){   


	   $('.selected_language').on('click',function(event){
        event.stopPropagation();
        if ( $('#header_user.close-cart').length > 0 ) {
            $('#cart_block:visible').slideToggle("slow");
            $('#header_user').removeClass('close-cart');
        }
    }); 
	
		   $('.mobile-link-top h4').on('click',function(event){
        event.stopPropagation();
        if ( $('#header_user.close-cart').length > 0 ) {
            $('#cart_block:visible').slideToggle("slow");
            $('#header_user').removeClass('close-cart');
        }
    }); 
	
			   $('.inner-carrencies').on('click',function(event){
        event.stopPropagation();
        if ( $('#header_user.close-cart').length > 0 ) {
            $('#cart_block:visible').slideToggle("slow");
            $('#header_user').removeClass('close-cart');
        }
    }); 
	
    $('#header_user').click(function(event){
        event.stopPropagation();
        if ( $(this).hasClass('close-cart') ) {
            $(this).removeClass('close-cart');
            $(this).siblings('#cart_block').stop(true, true).delay(400).slideUp(300),{
duration: 'slow',
easing: 'linear'
};
        } else {
            $('#header_user.close-cart').removeClass('.close-cart').siblings('#cart_block').stop(true, true).delay(400).slideUp(300),{
duration: 'slow',
easing: 'linear'
};
            $(this).addClass('close-cart');
            $(this).siblings('#cart_block').stop(true, true).slideDown(400),{
duration: 'slow',
easing: 'linear'
};
        }
    }); 
});







