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
			{assign var='nbItemsPerLine' value=3}
			{assign var='nbLi' value=$products|@count}
			{math equation="nbLi/nbItemsPerLine" nbLi=$nbLi nbItemsPerLine=$nbItemsPerLine assign=nbLines}
<ul id="product_list" class="grid row">
{foreach from=$products item=product name=products}
    {math equation="(total%perLine)" total=$smarty.foreach.products.total perLine=$nbItemsPerLine assign=totModulo}
<li class="ajax_block_product span3 shop_box {if $smarty.foreach.products.iteration is div by 3 - 1} product_list_1{/if} {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{else}item{/if} num-{$smarty.foreach.products.iteration} clearfix">
		<a href="{$product.link|escape:'htmlall':'UTF-8'}" class="product_img_link" title="{$product.name|escape:'htmlall':'UTF-8'}">
        	<img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" alt="{$product.legend|escape:'htmlall':'UTF-8'}" />
        </a>
		<div class="center_block">
			<div class="product_flags clearfix">
				{if isset($product.new) && $product.new == 1}<span class="new title_shop">{l s='New'}!</span>{/if}
                {if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}             
                {if isset($product.available_for_order) && $product.available_for_order && !isset($restricted_country_mode)}
                <span class="availability title_shop">{if ($product.allow_oosp || $product.quantity > 0)}{l s='Available'}
                {elseif (isset($product.quantity_all_versions) && $product.quantity_all_versions > 0)}{l s='Product available with different options'}{else}{l s='Out of stock'}{/if}</span>{/if}
				{if isset($product.online_only) && $product.online_only}<span class="online_only title_shop">{l s='Online only!'}</span>{/if}
                {/if}  
			</div>
            <div class="clear"></div>
            <h3><a class="product_link" href="{$product.link|escape:'htmlall':'UTF-8'}" title="{$product.name|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'|truncate:25:'...'}</a></h3>
            <p class="product_desc">{$product.description_short|strip_tags:'UTF-8'|truncate:450:'...'}</p>
            <p class="product_desc mob">{$product.description_short|strip_tags:'UTF-8'|truncate:90:'...'}</p>
            <p class="product_desc mob2">{$product.description_short|strip_tags:'UTF-8'|truncate:180:'...'}</p>  
		</div>																				 
		<div class="right_block">
        	{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}<span class="on_sale title_shop"><img src="{$img_dir}onsale_{$lang_iso}.gif" alt="{l s='On sale'}" class="on_sale_img"/></span>
			{elseif isset($product.reduction) && $product.reduction && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}<span class="discount title_shop">{l s='Reduced price!'}</span>{/if}
			{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
            {if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
            <span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span>{/if}  
			{/if}	 
           {if isset($comparator_max_item) && $comparator_max_item && !isset($instant_search)}
				<p class="compare checkbox choices-thin clearfix">		
                <input type="checkbox"  class="comparator" id="comparator_item_{$product.id_product}" value="comparator_item_{$product.id_product}" 
                {if isset($compareProducts) && in_array($product.id_product, $compareProducts)}checked="checked"{/if} /> 
				<label for="comparator_item_{$product.id_product}">{l s='Select to compare'}</label></p>
			{/if}
            <div class="clear noneclass"></div>
				{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && 					$product.customizable != 2 && !$PS_CATALOG_MODE}
					{if ($product.allow_oosp || $product.quantity > 0)}
						{if isset($static_token)}
							<a class="ajax_add_to_cart_button exclusive btn_add_cart" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)}" title="{l s='Add to cart'}">
                            <span>{l s='Add to cart'}</span>
                           </a>
						{else}
							<a class="ajax_add_to_cart_button exclusive btn_add_cart" rel="ajax_id_product_{$product.id_product|intval}" href="{$link->getPageLink('cart',false, NULL, "add&amp;id_product={$product.id_product|intval}", false)} title="{l s='Add to cart'}">
                            <span>{l s='Add to cart'}</span>
                            </a>
						{/if}						
					{else}
						<span class="exclusive">{l s='Add to cart'}</span>
					{/if}
				{/if}
			<a class="button" href="{$product.link|escape:'htmlall':'UTF-8'}" title="{l s='View'}">{l s='View'}</a>          
		</div>
	</li>
	{/foreach}
<script>
(function($) {
$(function() {
	function createCookie(name,value,days) {
		if (days) {
			var date = new Date();
			date.setTime(date.getTime()+(days*24*60*60*1000));
			var expires = "; expires="+date.toGMTString();
		}
		else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	}
	function readCookie(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	}
	function eraseCookie(name) {
		createCookie(name,"",-1);
	}
	$('ul.product_view').each(function(i) {
		var cookie = readCookie('tabCookie'+i);
		if (cookie) $(this).find('li').eq(cookie).addClass('current').siblings().removeClass('current')
			.parents('#center_column').find('#product_list').addClass('list').removeClass('grid').eq(cookie).addClass('grid').removeClass('list');
	})
	$('ul.product_view').delegate('li:not(.current)', 'click', function(i) {
		$(this).addClass('current').siblings().removeClass('current')
		.parents('#center_column').find('#product_list').removeClass('grid').addClass('list').eq($(this).index()).addClass('grid').removeClass('list')	
		var cookie = readCookie('tabCookie'+i);
		if (cookie) $(this).find('#product_list').eq(cookie).removeClass('grid').addClass('list').siblings().removeClass('list')
		var ulIndex = $('ul.product_view').index($(this).parents('ul.product_view'));
		eraseCookie('tabCookie'+ulIndex);
		createCookie('tabCookie'+ulIndex, $(this).index(), 365);
	})
})
})(jQuery)
    </script>
    </ul>
{/if}