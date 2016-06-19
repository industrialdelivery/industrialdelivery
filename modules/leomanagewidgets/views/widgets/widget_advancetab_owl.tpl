{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<div id="{$myTab|escape:'html':'UTF-8'}" class="block products_block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<h4 class="widget-heading title_block">
		{$widget_heading|escape:'html':'UTF-8'}
	</h4>
	{/if}
	<div class="block_content">	
		{if $tabs}	
			<ul  class="nav nav-tabs">
				{foreach $tabs as $tab}
					<li><a href="#{$myTab|escape:'html':'UTF-8'}{$tab.id_tab|escape:'html':'UTF-8'}" data-toggle="tab">
						{if $tab.icon && $tab.icon != 'none'}
							<img alt="" src="{$path|escape:'html':'UTF-8'}{$tab.icon|escape:'html':'UTF-8'}" />{$tab.title|escape:'html':'UTF-8'}
						{else}
							{$tab.title|escape:'html':'UTF-8'}
						{/if}	
					</a></li>
				{/foreach}	
                        </ul>
			<div id="product_tab_content">
                            <div class="product_tab_content tab-content">
				{foreach $tabs as $tab}
                                <div class="tab-pane" id="{$myTab|escape:'html':'UTF-8'}{$tab.id_tab|escape:'html':'UTF-8'}">
					{$products=$tab.products}{$tabname="{$myTab|escape:'html':'UTF-8'}-{$tab.id_tab|escape:'html':'UTF-8'}"}
					{include file='./products_owl.tpl'}
                                </div>
                                        {assign var="call_owl_carousel" value="#{$myTab}{$tab.id_tab}"}
                                        {include file='./owl_carousel_config.tpl'}
				{/foreach}
                                <script>
                                       $("#{$myTab|escape:'html':'UTF-8'} .nav-tabs li").first().addClass("active");
                                       $("#{$myTab|escape:'html':'UTF-8'} .tab-content .tab-pane").first().addClass("active");
                               </script>
                            </div>
                        </div>
		{/if}	
        
		
	</div>
</div>


