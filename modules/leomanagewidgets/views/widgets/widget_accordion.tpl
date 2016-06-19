{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($accordions)}
<div class="widget-accordion block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<h4 class="title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</h4>
	{/if}
	<div class="block_content">	<div id="accordion{$id|escape:'html':'UTF-8'}" class="panel-group">
	 	{foreach $accordions as $key => $ac}
		
				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h4 class="panel-title">
				      <a href="#collapseAc{$id|escape:'html':'UTF-8'}{$key|escape:'html':'UTF-8'}" data-parent="#accordion{$id|escape:'html':'UTF-8'}" data-toggle="collapse" class="accordion-toggle collapsed">
				       	{$ac.header}{* HTML form , no escape necessary *}
				      </a>
				    </h4>
				  </div>
				  <div class="panel-collapse collapse {if $key==0} in {else} out{/if}" id="collapseAc{$id|escape:'html':'UTF-8'}{$key|escape:'html':'UTF-8'}"  >
				    <div class="panel-body">
				      {$ac.content}{* HTML form , no escape necessary *}
				    </div>
				  </div>
				</div>
			
	 	{/foreach}
	</div>	</div>
</div>
{/if}


