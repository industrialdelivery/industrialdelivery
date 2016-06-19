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

<!-- MODULE Home Featured Products -->
<section class="block homefeatured">
  <h4>{l s='Featured products' mod='homefeatured'}</h4>
  {if isset($products) AND $products}
  <ul class="products row">
    {foreach from=$products item=product name=homeFeaturedProducts}
    <li class="ajax_block_product span3 "> <a class="product_image" href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product_image"> <img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}"  alt="{$product.name|escape:html:'UTF-8'}" /> </a>
      <h5><a  class="product_link" href="{$product.link}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:25:'...'|escape:'htmlall':'UTF-8'}</a></h5>
      {if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
      <p class="price_container"><span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span></p>
      {else}{/if}
      <p class="product_descr" href="{$product.link}" title="{l s='More' mod='homefeatured'}">{$product.description_short|strip_tags|truncate:100:'...'}</p>
      {if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity == 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}
      {if ($product.quantity > 0 OR $product.allow_oosp)} <a class="exclusive ajax_add_to_cart_button" rel="ajax_id_product_{$product.id_product}" href="{$link->getPageLink('cart')}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='homefeatured'}">{l s='Add to cart' mod='homefeatured'}</a> {else} <span class="exclusive">{l s='Add to cart' mod='homefeatured'}</span> {/if}
      {else}
      {/if} <a class="button" href="{$product.link}" title="{l s='View' mod='homefeatured'}"><span>{l s='View' mod='homefeatured'}</span></a> 
     </li>
    {/foreach}
  </ul>
  {else}
  <p>{l s='No featured products' mod='homefeatured'}</p>
  {/if} 
</section>
<!-- /MODULE Home Featured Products --> 
