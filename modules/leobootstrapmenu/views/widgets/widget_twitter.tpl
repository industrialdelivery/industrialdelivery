{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($username)}
<div class="widget-twitter">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<div class="menu-title">
		{$widget_heading}
	</div>
	{/if}
	<div class="widget-inner">
		<a class="twitter-timeline" data-dnt="true" data-theme="{$theme}" data-link-color="#FFFFFF" width="{$width}" height="{$height}" data-chrome="{$chrome}" data-border-color="#{$border_color}" lang="EN" data-tweet-limit="{$count}" data-show-replies="{$show_replies}" href="https://twitter.com/{$username}"  data-widget-id="{$twidget_id}">Tweets by @{$username}</a>
		{$js}
	</div>
</div>
{/if} 