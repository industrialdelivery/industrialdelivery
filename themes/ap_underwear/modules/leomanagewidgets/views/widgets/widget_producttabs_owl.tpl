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
        <ul id="productTabs" class="list-inline text-center widget-tabs nav nav-tabs">
            {if $special}	
                <li><a href="#{$myTab}special" data-toggle="tab">{l s='Special' mod='leomanagewidgets'}</a></li>
                {/if}
                {if $newproducts}	
                <li><a href="#{$myTab}newproducts" data-toggle="tab"><span></span>{l s='New Arrivals' mod='leomanagewidgets'}</a></li>
                        {/if}
                        {if $bestseller}	
                <li><a href="#{$myTab}bestseller" data-toggle="tab"><span></span>{l s='Best Seller' mod='leomanagewidgets'}</a></li>
                        {/if}
                        {if $featured}	
                <li><a href="#{$myTab}featured" data-toggle="tab"><span></span>{l s='Featured Products' mod='leomanagewidgets'}</a></li>
            {/if}
        </ul>

        <div id="product_tab_content">
            <div class="product_tab_content tab-content">
                {if $special}	
                    <div class="tab-pane" id="{$myTab}special">
                        {$products=$special}{$tabname="{$myTab}-special"}
                        {include file='./products_owl.tpl'}
                    </div>
                {/if}
                {if $newproducts}		  
                    <div class="tab-pane" id="{$myTab}newproducts">
                        {$products=$newproducts} {$tabname="{$myTab}-newproducts"}
                        {include file='./products_owl.tpl'}
                    </div>
                {/if}	
                {if $bestseller}		  
                    <div class="tab-pane" id="{$myTab}bestseller">
                        {$products=$bestseller} {$tabname="{$myTab}-bestseller"}
                        {include file='./products_owl.tpl'}
                    </div>   
                {/if}	
                {if $featured}		  
                    <div class="tab-pane" id="{$myTab}featured">
                        {$products=$featured} {$tabname="{$myTab}-featured"}
                        {include file='./products_owl.tpl'}
                    </div>   
                {/if}	

            </div></div>


    </div>
</div>
<!-- /MODULE Block specials -->

{assign var="call_owl_carousel" value="#{$myTab}special"}
{include file='./owl_carousel_config.tpl'}


{assign var="call_owl_carousel" value="#{$myTab}newproducts"}
{include file='./owl_carousel_config.tpl'}


{assign var="call_owl_carousel" value="#{$myTab}bestseller"}
{include file='./owl_carousel_config.tpl'}


{assign var="call_owl_carousel" value="#{$myTab}featured"}
{include file='./owl_carousel_config.tpl'}

<script>
    $("#{$myTab} ul.widget-tabs li").first().addClass("active");
    $("#{$myTab} .tab-content .tab-pane").first().addClass("active");
</script>