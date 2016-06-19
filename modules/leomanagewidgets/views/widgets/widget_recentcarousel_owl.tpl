{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{$tabname="{$tab|escape:'html':'UTF-8'}"}
<div class="block products_block exclusive leomanagerwidgets">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<h4 class="title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</h4>
	{/if}
	<div class="block_content">	
		{if !empty($products)}
                    <div id="{$tab|escape:'html':'UTF-8'}" class="owl-carousel owl-theme">
			{include file='./products_owl.tpl'}
                    </div>
		{else}
   			<p class="alert alert-info">{l s='No products at this time.' mod='leomanagewidgets'}</p>	
		{/if}
	</div>
</div>


{assign var="call_owl_carousel" value="#{$tab}"}
{include file='./owl_carousel_config.tpl'}
