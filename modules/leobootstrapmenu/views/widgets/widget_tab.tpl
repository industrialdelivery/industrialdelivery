{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($tabs)}
<div class="widget-tab">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="menu-title">
		{$widget_heading}
	</div>
	{/if}
	<div class="widget-inner">	
		<div id="tabs{$id}" class="panel-group">
			

			<ul class="nav nav-tabs">
			  {foreach $tabs as $key => $ac}  
			  <li class="{if $key==0}active{/if}"><a href="#tab{$id}{$key}" >{$ac.header}</a></li>
			  {/foreach}
			</ul>

			<div class="tab-content">
			 	{foreach $tabs as $key => $ac}
				  <div class="tab-pane{if $key==0} active{/if} " id="tab{$id}{$key}">{$ac.content}</div>
			 	{/foreach}
	 		</div>

	</div></div>
</div>
{/if}


