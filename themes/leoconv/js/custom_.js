$(document).ready( function(){

	$("#productsview a").click( function(){
		if( $(this).attr("rel") == "view-grid" ){
			$("#product_list").addClass("view-grid").removeClass("view-list");
			$(".icon-th-large").addClass("active");
			$(".icon-list-ul").removeClass("active");
		} else {
			$("#product_list").addClass("view-list").removeClass("view-grid");
			$(".icon-list-ul").addClass("active");
			$(".icon-th-large").removeClass("active");
		}
		return false;
	} );  
} );

function LeoWishlistCart(id, action, id_product, id_product_attribute, quantity)
{ 
	$.ajax({
		type: 'GET',
		url:	baseDir + 'modules/blockwishlist/cart.php',
		async: true,
		cache: false,
		data: 'action=' + action + '&id_product=' + id_product + '&quantity=' + quantity + '&token=' + static_token + '&id_product_attribute=' + id_product_attribute,
		success: function(data)
		{ 
			if (action == 'add') {
				var html = '<div class="notification alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a>' + data + '</div>';
				$("body").append( html );				
				$(".notification").show().delay(2000).fadeOut(600);				
   			}
		
			if($('#' + id).length != 0)
			{ 
				$('#' + id).slideUp('normal');
				document.getElementById(id).innerHTML = data;
				$('#' + id).slideDown('normal');
			}
		}
	});
}

//Detail-product

// Change the current product images regarding the combination selected
function refreshProductImages(id_product_attribute)
{
	$('#thumbs_list_frame').scrollTo('li:eq(0)', 700, {axis:'x'});
	$('#thumbs_list li').hide();
	id_product_attribute = parseInt(id_product_attribute);

	if (typeof(combinationImages) != 'undefined' && typeof(combinationImages[id_product_attribute]) != 'undefined')
	{
		for (var i = 0; i < combinationImages[id_product_attribute].length; i++)
			$('#thumbnail_' + parseInt(combinationImages[id_product_attribute][i])).show();
		if (parseInt($('#thumbs_list_frame >li:visible').length) < parseInt($('#thumbs_list_frame >li').length))
			$('#wrapResetImages').show('slow');
		else
			$('#wrapResetImages').hide('slow');
	}
	if (i > 0)
	{
		var thumb_height = $('#thumbs_list_frame >li').height()+parseInt($('#thumbs_list_frame >li').css('marginTop'));
		$('#thumbs_list_frame').height((parseInt((thumb_height)* i) + 3) + 'px'); //  Bug IE6, needs 3 pixels more ?
	}
	else
	{
		$('#thumbnail_' + idDefaultImage).show();
		displayImage($('#thumbnail_'+ idDefaultImage +' a'));
		if (parseInt($('#thumbs_list_frame >li').length) == parseInt($('#thumbs_list_frame >li:visible').length))
			$('#wrapResetImages').hide('slow');
	}
	$('#thumbs_list').trigger('goto', 0);
	serialScrollFixLock('', '', '', '', 0);// SerialScroll Bug on goto 0 ?
}
//To do after loading HTML
$(document).ready(function()
{
	if($('#thumbs_list') && typeof(serialScrollFixLock) != 'undefined' ){
		//init the serialScroll for thumbs
		$('#thumbs_list').serialScroll({
			items:'li:visible',
			prev:'a#view_scroll_left',
			next:'a#view_scroll_right',
			axis:'y',
			offset: -230,
			offset:0,
			start:0,
			stop:true,
			onBefore:serialScrollFixLock,
			duration:700,
			step: 2,
			lazy: true,
			lock: false,
			force:true,
			cycle:false
		});
		
		$('#thumbs_list').trigger('goto', 1);// SerialScroll Bug on goto 0 ?
		$('#thumbs_list').trigger('goto', 0);

		//hover 'other views' images management
		$('#views_block li a').hover(
			function(){displayImage($(this));},
			function(){}
		);

		//set jqZoom parameters if needed
		if (typeof(jqZoomEnabled) != 'undefined' && jqZoomEnabled)
		{
			$('img.jqzoom').jqueryzoom({
				xzoom: 200, //zooming div default width(default width value is 200)
				yzoom: 200, //zooming div default width(default height value is 200)
				offset: 21 //zooming div default offset(default offset value is 10)
				//position: "right" //zooming div position(default position value is "right")
			});
		}
		//add a link on the span 'view full size' and on the big image
		$('span#view_full_size, div#image-block img').click(function(){
			$('#views_block li a.shown').click();
		});

		//catch the click on the "more infos" button at the top of the page
		$('div#short_description_block p a.button').click(function(){
			$('#more_info_tab_more_info').click();
			$.scrollTo( '#more_info_tabs', 1200 );
		});

		// Hide the customization submit button and display some message
		$('p#customizedDatas input').click(function() {
			$('p#customizedDatas input').hide();
			$('#ajax-loader').fadeIn();
			$('p#customizedDatas').append(uploading_in_progress);
		});

		//init the price in relation of the selected attributes
		if (typeof productHasAttributes != 'undefined' && productHasAttributes)
			findCombination(true);
		else if (typeof productHasAttributes != 'undefined' && !productHasAttributes)
			refreshProductImages(0);

		$('a#resetImages').click(function() {
			refreshProductImages(0);
		});

		$('.thickbox').fancybox({
			'hideOnContentClick': true,
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic'
		});
		
		original_url = window.location+'';
		first_url_check = true;
		checkUrl();
		initLocationChange();
	}
});

/*bootstrap menu*/
$(window).ready( function(){
 $(document.body).on('click', '[data-toggle="dropdown"]' ,function(){
  if(!$(this).parent().hasClass('open') && this.href && this.href != '#'){
   window.location.href = this.href;
  }
 }); 



 $("#topnavigation .dropdown .caret").click( function() {
  $(this).parent().toggleClass('iopen'); 
 } );
//  $("#topnavigation .nav-collapse").OffCanvasMenu();
} );