{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if $product}
	{if $colors}
		{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction}
			{if $product.specific_prices.reduction_type == 'percentage'}
					{math assign='sale' equation='x*100' x=$product.specific_prices.reduction}
			{else}
					{math assign='sale' equation='(x/y)*100' x=$product.specific_prices.reduction y=$product.price_without_reduction}
			{/if}
			{foreach from=$colors item=color key=k}	
				{if $k >= $sale }
					<div>
						{$color}
					</div>
					{break}
				{/if}
			{/foreach}
		{/if}
	{/if}		
{/if}