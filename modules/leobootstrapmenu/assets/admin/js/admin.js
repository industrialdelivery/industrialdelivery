/**
 * @copyright Commercial License By LeoTheme.Com 
 * @email leotheme.com
 * @visit http://www.leotheme.com
 */
jQuery(document).ready(function(){
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
		$('.leo_load').css('display','block');
		$.ajax({
			async : false,
			type: 'POST',
			dataType:'json',
			url: ajaxUrlBmenu,
			data : serialized+'&updatePosition=1', 
			success : function (r) {
				$('.leo_load').hide();
			}
		});
	});
	
	$(".quickedit").click( function(){
		var id = $(this).attr("rel").replace("id_","");
		window.location.href= base_url_bmenu+"&id_btmegamenu="+id;
		/*
		$.post( base_url_bmenu, {
			"id":id,	
			"rand":Math.random()},
			function(data){
				$("#megamenu-form").html( data );
			});
		*/
	} );
	
	$(".quickdel").click( function(){
		if( confirm(confirm_text) ){
			var id = $(this).attr("rel").replace("id_","");
			window.location.href= base_url_bmenu+"&id_btmegamenu="+id+'&deleteMenu=1';
		}
	} );
});