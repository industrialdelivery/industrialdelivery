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
 
 <div id="custhtmlcarosel{$id}" class="block nopadding">
    {if isset($widget_heading)&&!empty($widget_heading)}
    <h4 class="title_block">
        {$widget_heading}
    </h4>
    {/if}
	<div class="block_content">
		<div class="carousel slide carousel-inner carousel-fade">
			<div id="custhtmlcarosel{$random_number}">
			{foreach from=$customercarousel  name="mypLoop" key=key item=item}
				<div class="item {if $smarty.foreach.mypLoop.index == $startSlide}active{/if}">
				<h4 class="title-custhtml">{$item.title}</h4>
            		{$item.content}   					
				</div>
			{/foreach}   
			</div>
		</div>
	</div>
</div>
    
{assign var="call_owl_carousel" value="#custhtmlcarosel{$random_number}"}
{include file='./owl_carousel_config.tpl'}