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

{$tabname="{$tab}"}
<div class="block products_block exclusive leomanagerwidgets nopadding">
	{if isset($widget_heading)&&!empty($widget_heading)}
    <h4 class="title_block">
		{$widget_heading}
	</h4>
	{/if}
	<div class="block_content">	
		{if !empty($products)}
            <div id="{$tab}" class="owl-carousel owl-theme product_list grid">
				{include file='./products_owl.tpl'}
            </div>
		{else}
   			<p class="alert alert-info">{l s='No products at this time.' mod='leomanagewidgets'}</p>	
		{/if}
	</div>
</div>

{assign var="call_owl_carousel" value="#{$tab}"}
{include file='./owl_carousel_config.tpl'}
