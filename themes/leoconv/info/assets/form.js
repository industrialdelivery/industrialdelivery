// JavaScript Document
$(document).ready( function(){

	$(".bgpattern").each( function(){
		var wrap = this;
		//$("#" + $("input",wrap).val()).addClass("active"); 
		$("div",this).click( function(){
		 	  $("input",wrap).val( $(this).attr("id").replace(/\.\w+$/,"") );
			  $("div",wrap).removeClass( "active" );
			  $(this).addClass("active");
		} );
	} );
	
	/*
	$(".bgpattern div").each( function(){ 
		$(this).click( function(){
			$('.bgpattern div').removeClass('active');
			$(this).addClass('active');
			 $('.bgpattern input.hdval').val( $(this).attr("id").replace(/\.\w+$/,"") );
		} );
	} );
	*/
} );