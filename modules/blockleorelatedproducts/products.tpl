{if !empty($products)}
<div class=" carousel slide" id="{$tabname}">
	 {if count($products)>$itemsperpage}	
	<a class="carousel-control left" href="#{$tabname}"   data-slide="prev">&lsaquo;</a>
	<a class="carousel-control right" href="#{$tabname}"  data-slide="next">&rsaquo;</a>
	{/if}
	<div class="carousel-inner">
	{$mproducts=array_chunk($products,$itemsperpage)}
	{foreach from=$mproducts item=products name=mypLoop}
		<div class="item {if $smarty.foreach.mypLoop.first}active{/if}">
				{foreach from=$products item=product name=products}
				{if $product@iteration%$columnspage==1&&$columnspage>1}
				  <div class="row-fluid">
				{/if}
				<div class="span{$scolumn} product_block ajax_block_product {if $smarty.foreach.products.first}first_item{elseif $smarty.foreach.products.last}last_item{/if} {if $smarty.foreach.products.index % 2}alternate_item{else}p-item{/if} clearfix">
					<div class="product-container"><a href="{$product.link}" title="{$product.name|escape:html:'UTF-8'}" class="product_image"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'home_default')}" height="{$homeSize.height}" width="{$homeSize.width}" alt="{$product.name|escape:html:'UTF-8'}" />{if isset($product.new) && $product.new == 1}<span class="new">{l s='New' mod='blockleorelatedproducts'}</span>{/if}</a>
					<h5 class="s_title_block"><a href="{$product.link}" title="{$product.name|truncate:50:'...'|escape:'htmlall':'UTF-8'}">{$product.name|truncate:35:'...'|escape:'htmlall':'UTF-8'}</a></h5>
					<div class="product_desc"><a href="{$product.link}" title="{l s='More' mod='blockleorelatedproducts'}">{$product.description_short|strip_tags|truncate:65:'...'}</a></div>
					<div>
						<a class="lnk_more" href="{$product.link}" title="{l s='View' mod='blockleorelatedproducts'}">{l s='View' mod='blockleorelatedproducts'}</a>
						{if $product.show_price AND !isset($restricted_country_mode) AND !$PS_CATALOG_MODE}<p class="price_container"><span class="price">{if !$priceDisplay}{convertPrice price=$product.price}{else}{convertPrice price=$product.price_tax_exc}{/if}</span></p>{else}<div style="height:21px;"></div>{/if}
						
						{if ($product.id_product_attribute == 0 OR (isset($add_prod_display) AND ($add_prod_display == 1))) AND $product.available_for_order AND !isset($restricted_country_mode) AND $product.minimal_quantity == 1 AND $product.customizable != 2 AND !$PS_CATALOG_MODE}
							{if ($product.quantity > 0 OR $product.allow_oosp)}
							<a class="exclusive ajax_add_to_cart_button" rel="ajax_id_product_{$product.id_product}" href="{$link->getPageLink('cart')}?qty=1&amp;id_product={$product.id_product}&amp;token={$static_token}&amp;add" title="{l s='Add to cart' mod='blockleorelatedproducts'}">{l s='Add to cart' mod='blockleorelatedproducts'}</a>
							{else}
							<span class="exclusive">{l s='Add to cart' mod='blockleorelatedproducts'}</span>
							{/if}
						{else}
							<div style="height:23px;"></div>
						{/if}
					</div>
					</div>
				</div>
				
				{if ($product@iteration%$columnspage==0||$smarty.foreach.products.last)&&$columnspage>1}
					</div>
				{/if}
					
				{/foreach}
		</div>		
	{/foreach}
	</div>
</div>
{/if}