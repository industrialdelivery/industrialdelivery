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
			<div id="custhtmlcarosel{$random_number|escape:'html':'UTF-8'}">
			{foreach from=$customercarousel  name="mypLoop" key=key item=item}
				<div class="item {if $smarty.foreach.mypLoop.index == $startSlide}active{/if}">{$item.content}{* HTML form , no escape necessary *}</div>
			{/foreach}   
			</div>
		</div>
	</div>
</div>
    
{assign var="call_owl_carousel" value="#custhtmlcarosel{$random_number}"}
{include file='./owl_carousel_config.tpl'}