{**
 *  Leo Prestashop Theme Framework for Prestashop 1.5.x
 *
 * @package   leotempcp
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 *
 **}
 
<div id="custhtmlcarosel{$id}" class="block custhtmlcarosel">
    {if isset($widget_heading)&&!empty($widget_heading)}
    <h4 class="widget-heading page-subheading">
        {$widget_heading}
    </h4>
    {/if}
    <div class="block_content">
        <div class="carousel slide" data-ride="carousel">
            {if $show_controls AND count($customercarousel )>1}
        	<a class="carousel-control left" href="#custhtmlcarosel{$id}"   data-slide="prev">&lsaquo;</a>
        	<a class="carousel-control right" href="#custhtmlcarosel{$id}"  data-slide="next">&rsaquo;</a>
            {/if}
            <div class="carousel-inner">
            {foreach from=$customercarousel  name="mypLoop" key=key item=item}
                <div class="item {if $smarty.foreach.mypLoop.index == $startSlide}active{/if}">{$item.content}</div>
            {/foreach}   
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
{literal}
$(document).ready(function() {
    $('#custhtmlcarosel{/literal}{$id}{literal}').each(function(){
        $(this).carousel({
            pause: 'hover', 
            interval: {/literal}{$interval}{literal}
        });
    });
     
});

{/literal}
</script>