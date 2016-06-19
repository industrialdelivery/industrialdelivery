{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<div class="block products_block exclusive leomanagerwidgets special-hover">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<h4 class="widget-heading title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</h4>
	{/if}
	<div class="block_content">	
		{$tabname="{$tab|escape:'html':'UTF-8'}"}
                <div id="{$tab|escape:'html':'UTF-8'}">
		{if !empty($products)}
						{$mproducts=array_chunk($products,$owl_rows)}
						{foreach from=$mproducts item=products name=mypLoop}
							<div class="item {if $smarty.foreach.mypLoop.first}active{/if}">
								<ul class="product_list grid">
									{foreach from=$products item=product name=products}
									
											<li class="ajax_block_product product_block">
											<!-- special-product-item.tpl -->
												<div class="product-container {if $product.specific_prices.reduction >= 0.75} red_bg{elseif $product.specific_prices.reduction >= 0.50 } green_bg{elseif $product.specific_prices.reduction >= 0.25 } yellow_bg{/if}" itemscope itemtype="http://schema.org/Product">
													{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
														{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
															{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction}
																{if $product.specific_prices.reduction_type == 'percentage'}
																	<span class="hot product-label ">{l s='Save' mod='leomanagewidgets'}<br />-{$product.specific_prices.reduction * 100|escape:'html':'UTF-8'}<sup>%</sup></span>
																{/if}
															{/if}
														{/if}
													{/if}
													<div class="left-block">
														<div class="product-image-container">
															<a class="product_img_link"	href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
                                                                                                                            {if $owl_lazyLoad}
                                                                                                                                    <img class="replace-2x img-responsive lazyOwl" data-src= "{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" itemprop="image" />
                                                                                                                            {else}
                                                                                                                                    <img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" itemprop="image" />
                                                                                                                                {/if}
															</a>			
														</div>
													</div>
													<div class="right-block">
														{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
														<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="content_price price_fix">
															{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
																{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction}
																	<span class="old-price product-price">
																		{displayWtPrice p=$product.price_without_reduction}
																	</span>
																{/if}
																<span itemprop="price" class="price product-price">
																	{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
																</span>
																<meta itemprop="priceCurrency" content="{$priceDisplay|escape:'html':'UTF-8'}" />
															{/if}
														</div>
														{/if}
														<h5 itemprop="name">
															{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
															<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
																{$product.name|truncate:45:'...'|escape:'html':'UTF-8'}
															</a>
														</h5>
														{hook h='displayProductListReviews' product=$product}
														<p class="product-desc" itemprop="description">
															{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}{* HTML form , no escape necessary *}
														</p>
														<div itemprop="offers" itemscope itemtype="http://schema.org/Offer" class="content_price">
															{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
																{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction}
																	<span class="old-price product-price">
																		{displayWtPrice p=$product.price_without_reduction}
																	</span>
																{/if}
																<span itemprop="price" class="price product-price">
																	{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
																</span>
																<meta itemprop="priceCurrency" content="{$priceDisplay|escape:'html':'UTF-8'}" />
															{/if}
														</div>
														<div class="leo-more-cdown" rel="{$product.id_product|intval}"></div>
														<div class="button-container">
															{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
																{if ($product.allow_oosp || $product.quantity > 0)}
																	{if isset($static_token)}
																		<a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='leomanagewidgets'}" data-id-product="{$product.id_product|intval}">
																			<span>{l s='Add to cart' mod='leomanagewidgets'}</span>
																		</a>
																	{else}
																		<a class="button ajax_add_to_cart_button btn btn-default" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart' mod='leomanagewidgets'}" data-id-product="{$product.id_product|intval}">
																			<span>{l s='Add to cart' mod='leomanagewidgets'}</span>
																		</a>
																	{/if}						
																{else}
																	<span class="button ajax_add_to_cart_button btn btn-default disabled">
																		<span>{l s='Add to cart' mod='leomanagewidgets' mod='leomanagewidgets'}</span>
																	</span>
																{/if}
															{/if}
															{hook h='displayProductListFunctionalButtons' product=$product}				
															{if isset($quick_view) && $quick_view}
																<a class="quick-view button btn-tooltip" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}" data-original-title="{l s='Quick view' mod='leomanagewidgets'}">
																	<i class="icon-exchange"></i>
																</a>
															{/if}
														</div>
														{if isset($product.color_list)}
															<div class="color-list-container">{$product.color_list}{* HTML form , no escape necessary *} </div>
														{/if}
														<span class="btn-line"></span>
													</div>		
												</div>
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