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
{if isset($orderProducts) && count($orderProducts)}
<section class="crossselling page_product_box">
	<h3 >{l s='Customers who bought this product also bought:' mod='crossselling'}<span class="icon-toggle"></span></h3>
    	<div id="block-crossselling-slider" class="carusel-inner responsive toggle_content">
                	<ul id="carouselproductcross" class="carousel-ul">
                    {foreach from=$orderProducts item='orderProduct' name=orderProduct}
                    <li class="item">
                        <a href="{$orderProduct.link}" title="{$orderProduct.name|htmlspecialchars}" class="lnk_img">
                        <img src="{$link->getImageLink($orderProduct.link_rewrite, $orderProduct.id_image, 'medium_default')}" alt="{$orderProduct.name|htmlspecialchars}" /></a>
                        <a class="product_link" href="{$orderProduct.link}" title="{$orderProduct.name|htmlspecialchars}">{$orderProduct.name|truncate:15:'...'|escape:'htmlall':'UTF-8'}</a>
                        {if $crossDisplayPrice AND $orderProduct.show_price == 1 AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}
                            <p class="price_display">
                                <span class="price">{convertPrice price=$orderProduct.displayed_price}</span>
                            </p>
                        {else}
                        {/if}
                    </li>
                    {/foreach}
                </ul> 
                             <div class="clearfix"></div>
            	<a class="prev" id="cross_prev" href="#"></a>
				<a class="next" id="cross_next" href="#"></a>
         </div>        
</section>
{/if}
<script type="text/javascript">
	$("#carouselproductcross").carouFredSel({
		debug: true,
		circular: true,
        infinite: true,
		responsive: true,
		align   : "center",
		width: '100%',
	    auto	: {
    		play	: 1,
	    	timeoutDuration :20000
	    },
		
	    items	: {
					
		visible: {
					min: 2,
					max: 6
					 },
					 width:167,
		
		},		
		scroll	: {
			items	: 1,
			pauseOnHover:false
		},
		prev	: {
			button	: "#cross_prev"
		},
		next	: {
			button	: "#cross_next"
		},
		swipe: {
					onMouse: true,
					onTouch: true
			   }
	});
</script>