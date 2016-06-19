{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($links) && $links}
<div class="widget-links block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<h4 class="title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</h4>
	{/if}
	<div class="block_content">	
		<div id="tabs{$id|escape:'html':'UTF-8'}" class="panel-group">
			<ul class="nav-links">
			  {foreach $links as $key => $ac}  
			  <li ><a href="{$ac.link|escape:'html':'UTF-8'}" >{$ac.text}{* HTML form , no escape necessary *}</a></li>
			  {/foreach}
			</ul>
		</div>
	</div>
</div>
{/if}


