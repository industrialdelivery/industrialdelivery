{*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{capture name=path}{l s='Product Comparison'}{/capture}

{include file="$tpl_dir./breadcrumb.tpl"}
<h1><span>{l s='Product Comparison'}</span></h1>
{if $hasProduct}
		{assign var='taxes_behavior' value=false}
		{if $use_taxes && (!$priceDisplay  || $priceDisplay == 2)}
			{assign var='taxes_behavior' value=true}
		{/if}
        <div class="row_compare_mobile">
<table id="product_comparison" class="std shop_table footable" >
   <thead>
	<tr class="comparison_header">
		<th  data-class="expand">{l s='Corporation'}</th>
		{foreach from=$products item=product name=for_products}
			<th data-hide="phone">   				
				  <a class="product_link" href="{$product->getLink()}" title="{$product->name|truncate:30:'...'|escape:'htmlall':'UTF-8'}">{$product->name|truncate:25:'...'|escape:'htmlall':'UTF-8'}</a>
					</th>
		{/foreach}
	</tr>
       </thead>
           <tbody>
		<td class="title_compare" >{l s='Features'}</td>
	
	{foreach from=$products item=product name=for_products}
		{assign var='replace_id' value=$product->id|cat:'|'}
		<td class="ajax_block_product">
		
			<div class="comparison_product_infos">
            <div class="product_image_div">
                            <a class="cmp_remove" href="{$link->getPageLink('products-comparison.php', true)}" rel="ajax_id_product_{$product->id}"></a>

			<a href="{$product->getLink()}" title="{$product->name|escape:html:'UTF-8'}" class="product_image" >
            		{if $product->on_sale}
                    
							<span class="on_sale"><img src="{$img_dir}onsale_{$lang_iso}.gif" alt="{l s='On sale'}" class="on_sale_img"/></span>
						{elseif $product->specificPrice AND $product->specificPrice.reduction}
							<span class="discount"><img src="{$img_dir}onsale_{$lang_iso}.gif" alt="{l s='On sale'}" class="on_sale_img"/></span>
						{/if}
				<img src="{$link->getImageLink($product->link_rewrite, $product->id_image, 'medium_default')}" alt="{$product->name|escape:html:'UTF-8'}"  />
                
                	
				
			</a>
         </div>
          
            	<p class="product_desc"><a class="product_descr" href="{$product->getLink()}" title="{l s='More'}">{$product->description_short|strip_tags|truncate:60:'...'}</a></p>
                	{if isset($product->show_price) && $product->show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}
					<span class="price">{convertPrice price=$product->getPrice($taxes_behavior)}</span>
					
			

						{if !empty($product->unity) && $product->unit_price_ratio > 0.000000}
								{math equation="pprice / punit_price"  pprice=$product->getPrice($taxes_behavior)  punit_price=$product->unit_price_ratio assign=unit_price}
							<p class="comparison_unit_price">{convertPrice price=$unit_price} {l s='per %s' sprintf=$product->unity|escape:'htmlall':'UTF-8'}</p>
						{else}
						&nbsp;
						{/if}
					{/if}
			<!-- availability -->
			<div class="comparison_availability_statut">
					{if !(($product->quantity <= 0 && !$product->available_later) OR ($product->quantity != 0 && !$product->available_now) OR !$product->available_for_order OR $PS_CATALOG_MODE)}
						<span id="availability_label">{l s='Availability:'}</span>
						<span id="availability_value"{if $product->quantity <= 0} class="warning-inline"{/if}>
							{if $product->quantity <= 0}
								{if $allow_oosp}
									{$product->available_later|escape:'htmlall':'UTF-8'}
								{else}
									{l s='This product is no longer in stock.'}
								{/if}
							{else}
								{$product->available_now|escape:'htmlall':'UTF-8'}
							{/if}
						</span>
					{/if}
			</div>
			<div class="row-compare-button">
				<a class="button" href="{$product->getLink()}" title="{l s='View'}">{l s='View'}</a>
			
				{if (!$product->hasAttributes() OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product->minimal_quantity == 1 AND $product->customizable != 2 AND !$PS_CATALOG_MODE}
					{if ($product->quantity > 0 OR $product->allow_oosp)}
							<a class="exclusive ajax_add_to_cart_button btn_add_cart" rel="ajax_id_product_{$product->id}" href="{$link->getPageLink('cart', true, NULL, "qty=1&amp;id_product={$product->id}&amp;token={$static_token}&amp;add")}" title="{l s='Add to cart'}"><span>{l s='Add to cart'}</span></a>
					{else}
						<span class="exclusive">{l s='Add to cart'}</span>
					{/if}
				{else}
					
				{/if}
                </div>
			</div>
		</td>
	{/foreach}
	</tr>
	{if $ordered_features}
	{foreach from=$ordered_features item=feature}
	<tr>
		{cycle values='comparison_feature_odd,comparison_feature_even' assign='classname'}
		<td class="{$classname}" >
			{$feature.name|escape:'htmlall':'UTF-8'}
		</td>
			{foreach from=$products item=product name=for_products}
				{assign var='product_id' value=$product->id}
				{assign var='feature_id' value=$feature.id_feature}
				{if isset($product_features[$product_id])}
					{assign var='tab' value=$product_features[$product_id]}
					<td  width="{$width}%" class="{$classname} comparison_infos">{if (isset($tab[$feature_id]))}{$tab[$feature_id]|escape:'htmlall':'UTF-8'}{/if}</td>
				{else}
					<td width="{$width}%" class="{$classname} comparison_infos"></td>
				{/if}
			{/foreach}
	</tr>
	{/foreach}
	{else}
	<tr>
		<td></td>
		<td colspan="{$products|@count + 1}">{l s='No features to compare'}</td>
	</tr>
	{/if}
	{$HOOK_EXTRA_PRODUCT_COMPARISON}
        </tbody>
</table>
</div>
{else}
	<p class="warning">{l s='There are no products selected for comparison'}</p>
{/if}

