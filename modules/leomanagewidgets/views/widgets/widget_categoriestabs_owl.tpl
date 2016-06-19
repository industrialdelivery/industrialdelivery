{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<!-- MODULE Block specials -->
<div id="{$myTab|escape:'html':'UTF-8'}" class="block products_block exclusive">
    {if isset($widget_heading)&&!empty($widget_heading)}
        <h4 class="title_block">
            {$widget_heading|escape:'html':'UTF-8'}
        </h4>
    {/if}
    <div class="block_content">
        {if !empty($leocategories )}
            <ul class="list-inline widget-tabs text-center">
                {foreach $leocategories as $category}
                    <li><a href="#{$myTab|escape:'html':'UTF-8'}{$category.id|intval}" data-toggle="tab">{$category.name|escape:'html':'UTF-8'}</a></li>
                {/foreach}
            </ul>
            <div class="tab-content">
                {foreach $leocategories as $category}
                    <div class="tab-pane" id="{$myTab|escape:'html':'UTF-8'}{$category.id|intval}">
							{$products=$category.products}
							{assign var="tabname" value="{$myTab}_{$category.id}"} 
							{include file='./products_owl.tpl'}
                    </div>
					{assign var="call_owl_carousel" value="#{$myTab}{$category.id}"}
					{include file='./owl_carousel_config.tpl'}
                {/foreach}
            </div>
        {/if}
    </div>
</div>
<!-- /MODULE Block specials -->

<script>
    $("#{$myTab|escape:'html':'UTF-8'} ul.widget-tabs li").first().addClass("active");
    $("#{$myTab|escape:'html':'UTF-8'} .tab-content .tab-pane").first().addClass("active");
</script>