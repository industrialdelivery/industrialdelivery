{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($images)}
<div class="widget-images block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<h4 class="title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</h4>
	{/if}
	<div class="block_content clearfix">
			<div class="images-list clearfix">	
			<div class="row">
		 	{foreach from=$images item=image name=images}
				<div class="image-item {if $columns == 5} col-md-2-4 {else} col-md-{12/$columns|intval}{/if} col-xs-12">
					<a class="fancybox" href= "{$image|escape:'html':'UTF-8'}">
						<img class="replace-2x img-responsive"  width='{$width|intval}' src="{$image}" alt=""/>
				</a>
				</div>
				{/foreach}
			</div>
		</div>
	</div>
</div>
{/if} 
<script type="text/javascript">
	$(document).ready(function() {
	$(".fancybox").fancybox({
		openEffect	: 'none',
		closeEffect	: 'none'
	});
});
</script>