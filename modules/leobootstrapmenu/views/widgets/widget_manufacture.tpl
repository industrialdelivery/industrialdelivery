{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

 <div class="widget-manufacture">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="menu-title">
		{$widget_heading}
	</div>
	{/if}
	<div class="widget-inner">
		{if manufacturers}
			<div class="manu-logo">
				{foreach from=$manufacturers item=manufacturer name=manufacturers}
				<a  href="{$link->getmanufacturerLink($manufacturer.id_manufacturer, $manufacturer.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{l s='view products' mod='leobootstrapmenu'}">
				<img src="{$manufacturer.image|escape:'htmlall':'UTF-8'}" alt=""> </a>
				{/foreach}
			</div>
			{else}
   			<p class="alert alert-info">{l s='No image logo at this time.' mod='leobootstrapmenu'}</p>
		{/if}
	</div>
</div>
 