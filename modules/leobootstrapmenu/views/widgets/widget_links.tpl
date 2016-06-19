{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($links)}
<div class="widget-links">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="menu-title">
		{$widget_heading}
	</div>
	{/if}
	<div class="widget-inner">	
		<div id="tabs{$id}" class="panel-group">
			<ul class="nav-links">
				{foreach $links as $key => $ac}  
					<li ><a href="{$ac.link}" >{$ac.text}</a></li>
				{/foreach}
			</ul>
		</div>
	</div>
</div>
{/if}


