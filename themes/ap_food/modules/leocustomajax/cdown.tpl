{if $product}
<div class="deal-clock lof-clock-{$product.id_product}-detail list-inline">
	{if isset($product.js) && $product.js == 'unlimited'}
		<div class="labelexpired">{l s='Unlimited' mod='leocustomajax'}</div>
	{/if}
</div>
{if isset($product.js) && $product.js != 'unlimited'}	
	<script type="text/javascript">
		{literal}
		jQuery(document).ready(function($){{/literal}
			var text_d = '{l s='Days' mod='leocustomajax'}';
			var text_h = '{l s='Hours' mod='leocustomajax'}';
			var text_m = '{l s='Mins' mod='leocustomajax'}';
			var text_s = '{l s='Secs' mod='leocustomajax'}';
			$(".lof-clock-{$product.id_product}-detail").lofCountDown({literal}{{/literal}
				TargetDate:"{$product.js.month}/{$product.js.day}/{$product.js.year} {$product.js.hour}:{$product.js.minute}:{$product.js.seconds}",
				DisplayFormat:'<li>%%D%% <br/><span>'+text_d+'</span></li><li>%%H%% <br/><span>'+text_h+'</span></li><li>%%M%% <br/><span>'+text_m+'</span></li><li>%%S%% <br/><span>'+text_s+'</span></li>',
				FinishMessage: "{$product.finish}"
			{literal}
			});
		});
		{/literal}
	</script>
{/if}
{/if}
