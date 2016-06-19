/**
 * @copyright Commercial License By LeoTheme.Com 
 * @email leotheme.com
 * @visit http://www.leotheme.com
 */
(function($) {
	$.fn.PavMegaMenuList = function(opts) {
		// default configuration
		var config = $.extend({}, {
			action:null, 
			addnew:null, 
			confirm_del:'Are you sure delete this?'
		}, opts);

		function checkInputHanlder(){
			var _updateMenuType = function(){
				$(".menu-type-group").parent().parent().hide();
				if($("[id^=url_type_]").closest('.form-group').find('.translatable-field').length)
					$("[id^=url_type_]").closest('.form-group').parent().parent().hide();
				else
					$("[id^=url_type_]").closest('.form-group').hide();
				if($("[id^=content_text_]").closest('.form-group').find('.translatable-field').length)
					$("[id^=content_text_]").closest('.form-group').parent().parent().hide();
				else
					$("[id^=content_text_]").closest('.form-group').hide();	
				if( $("#menu_type").val() =='html' ){
					if($("[id^=content_text_]").closest('.form-group').find('.translatable-field').length)
						$("[id^=content_text_]").closest('.form-group').parent().parent().show();
					else
						$("[id^=content_text_]").closest('.form-group').show();	
				}else if( $("#menu_type").val() =='url' ){
					if($("[id^=url_type_]").closest('.form-group').find('.translatable-field').length)
						$("[id^=url_type_]").closest('.form-group').parent().parent().show();
					else
						$("[id^=url_type_]").closest('.form-group').show();
				}
				else {
					$("#"+$("#menu_type").val()+"_type").parent().parent().show();
				}
			};
			_updateMenuType(); 
			$("#menu_type").change(  _updateMenuType );

			var _updateSubmenuType = function(){
				if( $("#type_submenu").val() =='html' ){
					$("[for^=submenu_content_text_]").parent().show();
				}else{
					$("[for^=submenu_content_text_]").parent().hide();
				}
			};
			_updateSubmenuType();
			$("#type_submenu").change(  _updateSubmenuType );

		}

		function manageTreeMenu(){
			if($('ol').hasClass("sortable")){
				$('ol.sortable').nestedSortable({
						forcePlaceholderSize: true,
						handle: 'div',
						helper:	'clone',
						items: 'li',
						opacity: .6,
						placeholder: 'placeholder',
						revert: 250,
						tabSize: 25,
						tolerance: 'pointer',
						toleranceElement: '> div',
						maxLevels: 4,

						isTree: true,
						expandOnHover: 700,
						startCollapsed: true
					});
				
				$('#serialize').click(function(){
					var serialized = $('ol.sortable').nestedSortable('serialize');
				 	var text = $(this).val();
				 	var $this  = $(this);
				 	$(this).val( $(this).data('loading-text') );
					$.ajax({
						type: 'POST',
						url: config.action+"&doupdatepos=1&rand="+Math.random(),
						data : serialized+'&updatePosition=1' 
					}).done( function (msg) {
						 $this.val( msg );
					} );
				});
				
				$('#show_cavas').click(function(){
					var show_cavas = $( "select.show_cavas option:selected" ).val();
					var text = $(this).val();
					var $this  = $(this);
				 	$(this).val( $(this).data('loading-text') );
					$.ajax({
						type: 'POST',
						url: config.action+"&show_cavas=1&rand="+Math.random(),
						data : 'show='+show_cavas+'&updatecavas=1' 
					}).done( function (msg) {
						 $this.val( msg );
					}	
					);
				});
				$('#addcategory').click(function(){
					location.href=config.addnew;
				});
			}	
		}
	 	/**
	 	 * initialize every element
	 	 */
		this.each(function() {  
	 		$(".quickedit",this).click( function(){  
	 			location.href=config.action+"&id_btmegamenu="+$(this).attr('rel').replace("id_","");
	 		} );

	 		$(".quickdel",this).click( function(){  
	 			if( confirm(config.confirm_del) ){
	 				location.href=config.action+"&dodel=1&id_btmegamenu="+$(this).attr('rel').replace("id_","");
	 			}
	 			
	 		} );

	 		manageTreeMenu();
	 		checkInputHanlder();




		});

		return this;
	};
	
})(jQuery);


jQuery(document).ready(function(){
 	
 	$("#widgetds a.btn").fancybox( {'type':'iframe'} );
 	$(".leo-modal-action, #widgets a.btn").fancybox({
	 	'type':'iframe',
	 	'width':950,
	 	'height':500,
	 	afterLoad:function(   ){
	 		 hideSomeElement();
			$('.fancybox-iframe').load( hideSomeElement );
	 	},
 		afterClose: function (event, ui) {  
			location.reload();
		},	
	});
	
});
 var hideSomeElement = function(){
    $('body',$('.fancybox-iframe').contents()).find("#header").hide();
    $('body',$('.fancybox-iframe').contents()).find("#footer").hide();
    $('body',$('.fancybox-iframe').contents()).find(".page-head, #nav-sidebar ").hide();
    $('body',$('.fancybox-iframe').contents()).find("#content.bootstrap").css( 'padding',0).css('margin',0);


 };

jQuery(document).ready(function(){
    if($("#image-images-thumbnails img").length){
	$("#image-images-thumbnails").append('<a class="del-img btn color_danger" href="javascript:voice(0)"><i class="icon-remove-sign"></i> delete image</a>');
    }
    $(".del-img").click(function(){
        if (confirm('Are you sure to delete this image?')) {
            $(this).parent().parent().html('<input type="hidden" value="1" name="delete_icon"/>');
        }
    });
    $(".leobootstrapmenu td").attr('onclick','').unbind('click');
});