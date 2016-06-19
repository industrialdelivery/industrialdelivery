{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($username)}
<div class="widget-twitter block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<h4 class="title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</h4>
	{/if}
	<div class="block_content">
		<div id="leo-twitter{$twidget_id|escape:'html':'UTF-8'}" class="leo-twitter">
			<a class="twitter-timeline" data-dnt="true"  data-theme="{$theme|escape:'html':'UTF-8'}" data-link-color="{$link_color|escape:'html':'UTF-8'}" width="{$width|escape:'html':'UTF-8'}px" height="{$height|intval}px" data-chrome="{$chrome|escape:'html':'UTF-8'}" data-border-color="{$border_color|escape:'html':'UTF-8'}" lang="{$iso_code|escape:'html':'UTF-8'}" data-tweet-limit="{$count|escape:'html':'UTF-8'}" data-show-replies="{$show_replies|escape:'html':'UTF-8'}" href="https://twitter.com/{$username|escape:'html':'UTF-8'}"  data-widget-id="{$twidget_id|escape:'html':'UTF-8'}"  >Tweets by @{$username|escape:'html':'UTF-8'}</a>
			{$js}{* HTML form , no escape necessary *}
		</div>	
	</div>
</div>
{/if} 
<script type="text/javascript">
{literal}
// Customize twitter feed
var hideTwitterAttempts = 0;
function hideTwitterBoxElements() {
 setTimeout( function() {
  if ( $('[id*=leo-twitter{/literal}{$twidget_id|escape:'html':'UTF-8'}{literal}]').length ) {
   $('#leo-twitter{/literal}{$twidget_id|escape:'html':'UTF-8'}{literal} iframe').each( function(){
    var ibody = $(this).contents().find( 'body' );
	var show_scroll =  {/literal}{$show_scrollbar|escape:'html':'UTF-8'}{literal}; 
	var height =  {/literal}{$height|intval}{literal}+'px'; 
    if ( ibody.find( '.timeline .stream .h-feed li.tweet' ).length ) {
		ibody.find( '.e-entry-title' ).css( 'color', '{/literal}{$text_color|escape:'html':'UTF-8'}{literal}' );
		ibody.find( '.header .p-nickname' ).css( 'color', '{/literal}{$mail_color|escape:'html':'UTF-8'}{literal}' );
		ibody.find( '.p-name' ).css( 'color', '{/literal}{$name_color|escape:'html':'UTF-8'}{literal}' );
		if(show_scroll == 1){
			ibody.find( '.timeline .stream' ).css( 'max-height', height );
			ibody.find( '.timeline .stream' ).css( 'overflow-y', 'auto' );	
			ibody.find( '.timeline .twitter-timeline' ).css( 'height', 'inherit !important' );	
		}
    } else {
     $(this).hide();
    }
   });
  }
  hideTwitterAttempts++;
  if ( hideTwitterAttempts < 3 ) {
   hideTwitterBoxElements();
  }
 }, 1500);
}
// somewhere in your code after html page load
hideTwitterBoxElements();
{/literal}
</script>
