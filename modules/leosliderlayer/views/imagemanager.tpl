{*
 *  Leo Prestashop SliderShow for Prestashop 1.6.x
 *
 * @package   leosliderlayer
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}
<div id="livethemeeditor">
<form  enctype="multipart/form-data" action="{$actionURL}" id="form" method="post">
<div id="pav-customize" class="pav-customize">
	<div class="btn-show">Customize <span class="icon-wrench"></span></div>
	<div class="wrapper"><div id="customize-form">
		<p>	 
			<span class="badge">Theme: {$themeName}</span>   <a class="label label-default pull-right" href="{$backLink}">Back</a>  
		</p>	 	

	<div class="buttons-group">
		<input type="hidden" id="action-mode" name="action-mode">	
		<a onclick="$('#action-mode').val('save-edit');$('#form').submit();" class="btn btn-primary btn-xs" href="#" type="submit">Submit</a>
		<a onclick="$('#action-mode').val('save-delete');$('#form').submit();" class="btn btn-danger btn-xs show-for-existed" href="#" type="submit">Delete</a>
	</div>

	<hr>
	<div class="groups">
		<div class="form-group pull-left">
			<label>Edit for</label>	
			<select id="saved-files" name="saved_file">
				<option value="">create new</option>
				{foreach $profiles as $profile}
				<option value="{$profile}">{$profile}</option>
				{/foreach}
			</select> 
		</div>
		<div class="form-group">
			<label class="show-for-notexisted">or  save new</label><label class="show-for-existed">And Rename File To</label>
			<input type="text" name="newfile">
		</div>	

		<div class="clearfix" id="customize-body">
				<ul class="nav nav-tabs">
				  {foreach $xmlselectors as $for => $output}
		       	  <li><a href="#tab-{$for}">{$for}</a></li> 
	       	      {/foreach}  
		        </ul>
		        <div class="tab-content" > 
		        	 {foreach $xmlselectors as $for => $items}
		            <div class="tab-pane" id="tab-{$for}">

		            	{if !empty($items)}
		            	<div class="accordion"  id="custom-accordion">
		            	{foreach $items as $group}
		            	   <div class="accordion-group">
	                            <div class="accordion-heading">
	                              <a class="accordion-toggle" data-toggle="collapse" data-parent="#custom-accordion" href="#collapse{$group.match}">
	                               		{$group.header}	 
	                              </a>
	                            </div>

	                            <div id="collapse{$group.match}" class="accordion-body collapse">
	                              <div class="accordion-inner clearfix">
	                              	{foreach $group.selector as $item}
									
									  {if isset($item.type)&&$item.type=="image"}	
									  <div class="form-group background-images"> 
											<label>{$item.label}</label>
											<a class="clear-bg label label-success" href="#">Clear</a>
											<input value="" type="hidden" name="customize[{$group.match}][]" data-match="{$group.match}" type="text" class="input-setting" data-selector="{$item.selector}" data-attrs="background-image">

											<div class="clearfix"></div>
											 <p><em style="font-size:10px">Those Images in folder YOURTHEME/img/patterns/</em></p>
											<div class="bi-wrapper clearfix">
											{foreach $patterns as $pattern}
											<div style="background:url('{$backgroundImageURL}{$pattern}') no-repeat center center;" class="pull-left" data-image="{$backgroundImageURL}{$pattern}" data-val="../../img/patterns/{$pattern}">

											</div>
											{/foreach}
	                                    </div>
	                                  </div>
	                                  {elseif $item.type=="fontsize"}
	                                   <div class="form-group">
	                                   	<label>{$item.label}</label>
	                                  	<select name="customize[{$group.match}][]" data-match="{$group.match}" type="text" class="input-setting" data-selector="{$item.selector}" data-attrs="{$item.attrs}">
											<option value="">Inherit</option>
											{for $i=9 to 16}
											<option value="{$i}">{$i}</option>
											{/for}
										</select>	<a href="#" class="clear-bg label label-success">Clear</a>
	                                  </div>
	                                  {else}
	                                  <div class="form-group">
										<label>{$item.label}</label>
										<input value="" size="10" name="customize[{$group.match}][]" data-match="{$group.match}" type="text" class="input-setting" data-selector="{$item.selector}" data-attrs="{$item.attrs}"><a href="#" class="clear-bg label label-success">Clear</a>
									</div>
	                                  {/if}


									{/foreach}
	                              </div>
	                            </div>
		                    </div>          	
		            	{/foreach}
		           		 </div>
		            	{/if}
		            </div>

	           		{/foreach}




		        </div>    	
		    </div>    


	</div>

</div></div></div>
</form>


	<div id="main-preview">
		<iframe src="{$siteURL}" ></iframe> 
	</div>



</div>

<script type="text/javascript">
$('#myTab a').click(function (e) {
	e.preventDefault();
	$(this).tab('show');
})
$('#myTab a:first').tab('show'); 
$("#custom-accordion .accordion-group:first .accordion-body").addClass('in');
</script>

 <script type="text/javascript">

/**
 * BACKGROUND-IMAGE SELECTION
 */
$(".background-images").each( function(){
	var $parent = this;
	var $input  = $(".input-setting", $parent ); 
	$(".bi-wrapper > div",this).click( function(){
		 $input.val( $(this).data('val') ); 
		 $('.bi-wrapper > div', $parent).removeClass('active');
		 $(this).addClass('active');

		 if( $input.data('selector') ){  
			$($input.data('selector'),$("#main-preview iframe").contents()).css( $input.data('attrs'),'url('+ $(this).data('image') +')' );
		 }
	} );
} ); 

$(".clear-bg").click( function(){
	var $parent = $(this).parent();
	var $input  = $(".input-setting", $parent ); 
	if( $input.val('') ) {
		if( $parent.hasClass("background-images") ) {
			$('.bi-wrapper > div',$parent).removeClass('active');	
			$($input.data('selector'),$("#main-preview iframe").contents()).css( $input.data('attrs'),'none' );
		}else {
			$input.attr( 'style','' )	
		}
		$($input.data('selector'),$("#main-preview iframe").contents()).css( $input.data('attrs'),'inherit' );

	}	
	$input.val('');
} );

	
/**
 *  FORM SUBMIT
 */
 $( "#form" ).submit( function(){ 
	$('.input-setting').each( function(){
		if( $(this).data("match") ) {
			var val = $(this).data('selector')+"|"+$(this).data('attrs');
			$(this).parent().append('<input type="hidden" name="customize_match['+$(this).data("match")+'][]" value="'+val+'"/>');
		}	 
	} );
	return true; 
} );
$("#main-preview iframe").ready(function(){ 
	 $('.accordion-group input.input-setting').each( function(){
	 	 var input = this;
	 	 $(input).attr('readonly','readonly');
	 	 $(input).ColorPicker({
	 	 	onChange:function (hsb, hex, rgb) {
	 	 		$(input).css('backgroundColor', '#' + hex);
	 	 		$(input).val( hex );
	 	 		if( $(input).data('selector') ){  
					$("#main-preview iframe").contents().find($(input).data('selector')).css( $(input).data('attrs'),"#"+$(input).val() )
				}
	 	 	}
	 	 });
 	} );
	 $('.accordion-group select.input-setting').change( function(){
		var input = this; 
			if( $(input).data('selector') ){  
			var ex = $(input).data('attrs')=='font-size'?'px':"";	
			$("#main-preview iframe").contents().find($(input).data('selector')).css( $(input).data('attrs'), $(input).val() + ex);
		}
	 } );
})
 
$(".show-for-existed").hide();
$("#saved-files").change( function() {
	if( $(this).val() ){  
		$(".show-for-notexisted").hide();
		$(".show-for-existed").show();
	}else {
		$(".show-for-notexisted").show();
		$(".show-for-existed").hide();
	}
	var url  = '{$customizeFolderURL}'+$(this).val()+".json?rand"+Math.random();

	$.getJSON( url, function(data) {
		var items = data;
			if( items ){
				$('#customize-body .accordion-group').each( function(){
					var i = 0;
					$("input, select", this).each( function(){
						if( $(this).data('match') ){ 
							if( items[$(this).data('match')] && items[$(this).data('match')][i] ){ 
								var el = items[$(this).data('match')][i];
							 	$(this).val( el.val );
							 	if( el.val== '') {
							 		$(this).css('background',"inherit");
							 	}
							 	else { 
							 		$(this).css('background',"#"+el.val);
							 	}
							 	$(this).ColorPickerSetColor(el.val );
							}
							i++;
						}
					} );
					 
				});
			}
		});

	$("#main-preview iframe").contents().find("#customize-theme").remove();
	if( $(this).val() ){
		var _link = $('<link rel="stylesheet" href="" id="customize-theme">');
		_link.attr('href', '{$customizeFolderURL}'+$(this).val()+".css?rand="+Math.random() );
		$("#main-preview iframe").contents().find("head").append( _link );
	}
});
</script>