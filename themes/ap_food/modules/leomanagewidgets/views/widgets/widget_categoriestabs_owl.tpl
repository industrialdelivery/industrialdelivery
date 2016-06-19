{**
*  Leo Prestashop Theme Framework for Prestashop 1.5.x
*
* @package   leotempcp
* @version   3.0
* @author    http://www.leotheme.com
* @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
*               <info@leotheme.com>.All rights reserved.
* @license   GNU General Public License version 2
*
**}

<!-- MODULE Block specials -->
<div id="{$myTab}" class="block products_block exclusive nopadding">
    {if isset($widget_heading)&&!empty($widget_heading)}
        <h4 class="title_block">
            {$widget_heading}
        </h4>
    {/if}
    <div class="block_content">	
        {if !empty($leocategories )}
            <ul class="list-inline widget-tabs text-center">
                {foreach $leocategories as $category}
                    <li><a href="#{$myTab}{$category.id}" data-toggle="tab">{$category.name}</a></li>
                {/foreach}
            </ul>
            <div class="tab-content">
                {foreach $leocategories as $category}
                    <div class="tab-pane" id="{$myTab}{$category.id}">
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
    $("#{$myTab} ul.widget-tabs li").first().addClass("active");
    $("#{$myTab} .tab-content .tab-pane").first().addClass("active");
</script>