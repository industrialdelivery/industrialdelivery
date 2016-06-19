{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($images) && $images}
<div class="widget-images">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="menu-title">
		{$widget_heading}
	</div>
	{/if}
	<div class="widget-inner clearfix">
		<div class="images-list clearfix">	
			<div class="row">
				{foreach from=$images item=image name=images}
				<div class="image-item col-md-{$columns} col-xs-12">
					<img class="replace-2x img-responsive" src="{$image}"  alt=""/>
				</div>
				{/foreach}
			</div>
		</div>
	</div>
</div>
{/if} 