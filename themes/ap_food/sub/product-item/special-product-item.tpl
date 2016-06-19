{*
	************************
		Creat by leo themes
	*************************
*}
	{include file="$tpl_dir./layout/setting.tpl"}
	<div class="product-container product-block" itemscope itemtype="https://schema.org/Product">
		
			<div class="left-block">
				
				<div class="product-image-container image ImageWrapper">
					<div class="leo-more-info" data-idproduct="{$product.id_product}"></div>
					<a class="product_img_link"	href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url">
						<img class="replace-2x img-responsive" src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'food_home_default')|escape:'html':'UTF-8'}" alt="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" title="{if !empty($product.legend)}{$product.legend|escape:'html':'UTF-8'}{else}{$product.name|escape:'html':'UTF-8'}{/if}" itemprop="image" />
					</a>
					<div class="Buttons StyleC">
						{if isset($quick_view) && $quick_view}
							<a class="quick-view btn btn-inverse" href="{$product.link|escape:'html':'UTF-8'}" rel="{$product.link|escape:'html':'UTF-8'}" title="{l s='Quick view'}" >
								<i class="fa fa-expand"></i>
							</a>
						{/if}
					</div>	
					{if isset($product.on_sale) && $product.on_sale && isset($product.show_price) && $product.show_price && !$PS_CATALOG_MODE}
						<span class="label-sale label-warning label">{l s='Sale!'}</span>
					{/if}
					<div class="leo-more-cdown" data-idproduct="{$product.id_product}"></div>	
					<!--button -->
					<div class="functional-buttons clearfix">				
						{if ($product.id_product_attribute == 0 || (isset($add_prod_display) && ($add_prod_display == 1))) && $product.available_for_order && !isset($restricted_country_mode) && $product.minimal_quantity <= 1 && $product.customizable != 2 && !$PS_CATALOG_MODE}
							{if (!isset($product.customization_required) || !$product.customization_required) && ($product.allow_oosp || $product.quantity > 0)}
								{if isset($static_token)}
									<a class="cart button ajax_add_to_cart_button btn" href="{$link->getPageLink('cart',false, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;token={$static_token}", false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
										<i class="fa fa-plus-circle"></i>
										<span>{l s='Add to cart'}</span>
									</a>
								{else}
									<a class="cart button ajax_add_to_cart_button btn" href="{$link->getPageLink('cart',false, NULL, 'add=1&amp;id_product={$product.id_product|intval}', false)|escape:'html':'UTF-8'}" rel="nofollow" title="{l s='Add to cart'}" data-id-product="{$product.id_product|intval}">
										<i class="fa fa-plus-circle"></i>
										<span>{l s='Add to cart'}</span>
									</a>
								{/if}
							{else}
								<div class="cart btn btn-outline disabled" title="{l s='Out of stock'}">
									<i class="fa fa-plus-circle"></i>
									<span>{l s='Out of stock'}</span>
								</div>
							{/if}
						{/if}
						
						{if $ENABLE_WISHLIST}				
							{hook h='displayProductListFunctionalButtons' product=$product}				
						{/if}
						
						{if isset($comparator_max_item) && $comparator_max_item}				
							<a class="add_to_compare compare  btn btn-outline" href="{$product.link|escape:'html':'UTF-8'}" data-id-product="{$product.id_product}" title="{l s='Add to compare'}" >
								<i class="fa fa-retweet"></i>
								<span>{l s=' Add to compare'}</span>
							</a>										
						{/if}
					
				</div>

				</div>	
			</div>
			<div class="right-block">
				<div class="product-meta">
					<h5 itemprop="name" class="name">
						{if isset($product.pack_quantity) && $product.pack_quantity}{$product.pack_quantity|intval|cat:' x '}{/if}
						<a class="product-name" href="{$product.link|escape:'html':'UTF-8'}" title="{$product.name|escape:'html':'UTF-8'}" itemprop="url" >
							{$product.name|truncate:45:'...'|escape:'html':'UTF-8'}
						</a>
					</h5>
					<div class="product_price">
						{if (!$PS_CATALOG_MODE AND ((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}
						<div class="content_price price_fix">
							{if isset($product.show_price) && $product.show_price && !isset($restricted_country_mode)}
								{hook h="displayProductPriceBlock" product=$product type='before_price'}
								<span class="price product-price">
									{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}
								</span>
								{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}
									{hook h="displayProductPriceBlock" product=$product type="old_price"}
									<span class="old-price product-price">
										{displayWtPrice p=$product.price_without_reduction}
									</span>
									{hook h="displayProductPriceBlock" id_product=$product.id_product type="old_price"}
									{if $product.specific_prices.reduction_type == 'percentage'}
										<span class="price-percent-reduction">-{$product.specific_prices.reduction * 100}%</span>
									{/if}
								{/if}
								{hook h="displayProductPriceBlock" product=$product type="price"}
								{hook h="displayProductPriceBlock" product=$product type="unit_price"}
								{hook h="displayProductPriceBlock" product=$product type='after_price'}
							{/if}
						</div>
					{/if}					
					{if $page_name != "product"}
						{hook h='displayProductListReviews' product=$product}
					{/if}
					</div>
					<!-- <p class="product-desc" itemprop="description">
						{$product.description_short|strip_tags:'UTF-8'|truncate:360:'...'}
					</p> -->
				</div>	
			</div>	
			
		
	</div><!-- .product-container> -->

