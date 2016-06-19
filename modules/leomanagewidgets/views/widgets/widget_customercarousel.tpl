{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<div id="custhtmlcarosel{$id|escape:'html':'UTF-8'}" class="block">
    {if isset($widget_heading)&&!empty($widget_heading)}
    <h4 class="title_block">
        {$widget_heading|escape:'html':'UTF-8'}
    </h4>
    {/if}
	<div class="block_content">
		<div class="carousel slide">
			{if $show_controls AND count($customercarousel )>1}
			<a class="carousel-control left" href="#custhtmlcarosel{$id|escape:'html':'UTF-8'}"   data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#custhtmlcarosel{$id|escape:'html':'UTF-8'}"  data-slide="next">&rsaquo;</a>
			{/if}
			<div class="carousel-inner">
			{foreach from=$customercarousel  name="mypLoop" key=key item=item}
				<div class="item item {if $smarty.foreach.mypLoop.index == $startSlide}active{/if}">{$item.content}{* HTML form , no escape necessary *}</div>
			{/foreach}   
		</div>
	</div>
	</div>
</div>
<script type="text/javascript">
{literal}
$(document).ready(function() {
    $('#custhtmlcarosel{/literal}{$id|escape:'html':'UTF-8'}{literal}').each(function(){
        $(this).carousel({
            pause: true,
            interval: {/literal}{$interval|intval}{literal}
        });
    });
     
});

{/literal}
</script>