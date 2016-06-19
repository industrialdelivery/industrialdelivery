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


<div class="products_block exclusive leomanagerwidgets special-hover block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<h4 class="title_block page-subheading">
		{$widget_heading}
	</h4>
	{/if}
	<div class="block_content nopadding">	
		{$tabname="{$tab}"}
		{if !empty($products)}
			{if !empty($products)}
				<div class="carousel slide" id="{$tabname}">
					{if count($products)>$itemsperpage}	 
					 	<a class="carousel-control left" href="#{$tabname}"   data-slide="prev">&lsaquo;</a>
						<a class="carousel-control right" href="#{$tabname}"  data-slide="next">&rsaquo;</a>
					{/if}

					<div class="carousel-inner">
						{$mproducts=array_chunk($products,$itemsperpage)}
						{foreach from=$mproducts item=products name=mypLoop}
							<div class="item {if $smarty.foreach.mypLoop.first}active{/if}">
								<div class="grid">
									{foreach from=$products item=product name=products}
										<!-- {if $product@iteration%$columnspage==1&&$columnspage>1}
											<div class="row">
										{/if} -->
										<div class="row">
											<div class="ajax_block_product product_block col-md-12 col-sm-12 col-xs-12 col-sp-12 {if $smarty.foreach.products.last}last_item{/if}">
											
												{include file="$tpl_dir./sub/product-item/special-product-item.tpl"}
											</div>		
										</div>
										<!-- {if ($product@iteration%$columnspage==0||$smarty.foreach.products.last)&&$columnspage>1}
											</div>
										{/if} -->	
									{/foreach}
								</div>
						</div>		
					{/foreach}
					</div>
				</div>
			{/if}
		{/if}
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#{$tabname}').each(function(){
        $(this).carousel({
            pause: 'hover',
            interval: {$interval}
        });
    });
});
</script>
 