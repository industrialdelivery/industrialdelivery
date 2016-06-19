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

<div class="block products_block exclusive leomanagerwidgets special-hover nopadding">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<h4 class="title_block">
		{$widget_heading}
	</h4>
	{/if}
	<div class="block_content">	
		{$tabname="{$tab}"}
			<div id="{$tab}">
				{if !empty($products)}
					{$mproducts=array_chunk($products,$owl_rows)}
					{foreach from=$mproducts item=products name=mypLoop}
						<div class="item {if $smarty.foreach.mypLoop.first}active{/if}">
							<ul class="product_list grid">
								{foreach from=$products item=product name=products}
								
									<li class="ajax_block_product product_block {if $smarty.foreach.products.first}list{else}{/if}{if $smarty.foreach.products.last}last_item{/if}">
									<!-- special-product-item.tpl -->
										{include file="$tpl_dir./special-product-item.tpl"}
										<!-- End -->
									</li>		
									
								{/foreach}                              
							</ul>
						</div>
					{/foreach}
				{/if}
			</div>
	</div>
</div>
{assign var="call_owl_carousel" value="#{$tab}"}
{include file='./owl_carousel_config.tpl'}