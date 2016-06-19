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

<!-- MODULE Home Block best sellers -->
<section class="block homefeatured">
	<h4>{l s='Top sellers' mod='blockbestsellers'}</h4>
	{if isset($best_sellers) AND $best_sellers}
  <ul class="products">
			{foreach from=$best_sellers item=product name=myLoop}
				<li class="ajax_block_product">
                			<a class="product_image" href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product_image"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}"  alt="{$product.name|escape:html:'UTF-8'}" /></a>
                     	      
					<h5><a class="product_link" href="{$product.link}" title="{$product.name|truncate:32:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:25:'...'|escape:'htmlall':'UTF-8'}</a></h5>
				
					{if !$PS_CATALOG_MODE}<p class="price_container"><span class="price">{$product.price}</span></p>{else}{/if}	
			<p class="product_descr" href="{$product.link}" title="{l s='More' mod='blockbestsellers'}">{$product.description_short|strip_tags|truncate:130:'...'}</p>
						 <div class="clear"></div>	
							<a class="exclusive ajax_add_to_cart_button" rel="ajax_id_product_{$product.id_product}" href="{$link->getPageLink('cart')}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='homefeatured'}">{l s='Add to cart' mod='homefeatured'}</a>
						<a class="button" href="{$product.link}" title="{l s='View' mod='blockbestsellers'}">{l s='View' mod='blockbestsellers'}</a>
				</li>
			{/foreach}
			</ul>
	{else}
		<p>{l s='No best sellers at this time' mod='blockbestsellers'}</p>
	{/if}
</section>
<!-- /MODULE Home Block best sellers -->
