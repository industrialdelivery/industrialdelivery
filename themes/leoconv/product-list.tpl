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

{if isset($products)}
	<!-- Products list -->
	<div id="product_list" class="view-grid"><div class="rows-fluid">
	{foreach from=$products item=product name=products} 
		{if $product@iteration%3==1}
        <div class="row-fluid">
        {/if}
		<div class="p-item span4 product_block ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if $smarty.foreach.products.index % 3}alternate_item{else}item{/if}  ">
			 		<div class="product-container clearfix">	 
						<div class="center_block">
							<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_image" title="{$product.name|escape:'htmlall':'UTF-8'}">
								<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" />
								{if isset($product.new) && $product.new == 1}<span class="new">{l s='New'}</span>{/if}
							</a>  
							{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}<span class="on_sale">{l s='On sale!'}</span>
							{elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}<span class="discount">{l s='Sale Off!'}</span>{/if}
						
						</div>
						<div class="right_block">
							
							<p class="s_title_block"><a href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'|truncate:35:'...'}</a></p>
							<p class="product_desc">
								{$product.description_short|strip_tags:'UTF-8'|truncate:80:'...'}
							</p>

							<p class="availability">
								{if ($product.allow_oosp || $product.quantity > 0)}
									{l s='Available'}
								{elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}
									{l s='Product available with different options'}
								{else}
									<span class="outofstock">
									{l s='Out of stock'}
									</span>
								{/if}
							</p>
							
							{if isset($product.online_only) && $product.online_only}<span class="online_only">{l s='Online only'}</span>{/if}
							
							<!-- <a href="#" id="wishlist_button{$product.id_product}" title="{l s='Add to wishlist'}" class="btn-add-wishlist button" onclick="LeoWishlistCart('wishlist_block_list', 'add', '{$product.id_product}', $('#idCombination').val(), 1 ); return false;"><i class="icon-heart icon-white">&nbsp;</i>{l s='wishlist'}</a> -->
							
							{if isset($comparator_max_item) && $comparator_max_item}
								<p class="compare">
									<input type="checkbox" class="comparator" id="comparator_item_{$product.id_product}" value="comparator_item_{$product.id_product}" {if isset($compareProducts) && in_array($product.id_product, $compareProducts)}checked="checked"{/if} /> 
									<label for="comparator_item_{$product.id_product}">{l s='Select to compare'}</label>
								</p>
							{/if}
							
							{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
							<div class="price-cart">
								<div class="content_price">
									{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}<span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span> {/if}
									{if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
									
									{/if}
								</div>
								
								{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
									{if ($product.allow_oosp || $product.quantity > 0)}
										{if isset($static_token)}
											<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)}" title="{l s='Add to cart'}"><span></span>{l s='Add to cart'}</a>
										{else}
											<a class="button ajax_add_to_cart_button exclusive" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}", false)}" title="{l s='Add to cart'}"><span></span>{l s='Add to cart'}</a>
										{/if}						 
									{/if}
								{/if} 	
							</div>
							{/if}
						</div>
					</div> 
			</div> 
			{if $product@iteration%3==0||$smarty.foreach.products.last}
			</div>
			{/if} 
	{/foreach}
	</div></div>
	<!-- /Products list -->
{/if}
