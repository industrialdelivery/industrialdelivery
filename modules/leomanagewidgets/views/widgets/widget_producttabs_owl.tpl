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
        <ul id="productTabs" class="list-inline text-center widget-tabs">
            {if $special}	
                <li><a href="#{$myTab|escape:'html':'UTF-8'}special" data-toggle="tab">{l s='Special' mod='leomanagewidgets'}</a></li>
				{/if}
                {if $newproducts}	
                <li><a href="#{$myTab|escape:'html':'UTF-8'}newproducts" data-toggle="tab"><span></span>{l s='New Arrivals' mod='leomanagewidgets'}</a></li>
                        {/if}
                        {if $bestseller}	
                <li><a href="#{$myTab|escape:'html':'UTF-8'}bestseller" data-toggle="tab"><span></span>{l s='Best Seller' mod='leomanagewidgets'}</a></li>
                        {/if}
                        {if $featured}	
                <li><a href="#{$myTab|escape:'html':'UTF-8'}featured" data-toggle="tab"><span></span>{l s='Featured Products' mod='leomanagewidgets'}</a></li>
                        {/if}
        </ul>

        <div id="product_tab_content">
            <div class="product_tab_content tab-content">
                {if $special}	
                    <div class="tab-pane" id="{$myTab|escape:'html':'UTF-8'}special">
                        {$products=$special}{$tabname="{$myTab|escape:'html':'UTF-8'}-special"}
                        {include file='./products_owl.tpl'}
                    </div>
                {/if}
                {if $newproducts}		  
                    <div class="tab-pane" id="{$myTab|escape:'html':'UTF-8'}newproducts">
                        {$products=$newproducts} {$tabname="{$myTab|escape:'html':'UTF-8'}-newproducts"}
                        {include file='./products_owl.tpl'}
                    </div>
                {/if}	
                {if $bestseller}		  
                    <div class="tab-pane" id="{$myTab|escape:'html':'UTF-8'}bestseller">
                        {$products=$bestseller} {$tabname="{$myTab|escape:'html':'UTF-8'}-bestseller"}
                        {include file='./products_owl.tpl'}
                    </div>   
                {/if}	
                {if $featured}		  
                    <div class="tab-pane" id="{$myTab|escape:'html':'UTF-8'}featured">
                        {$products=$featured} {$tabname="{$myTab|escape:'html':'UTF-8'}-featured"}
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
	$("#{$myTab|escape:'html':'UTF-8'} ul.widget-tabs li").first().addClass("active");
	$("#{$myTab|escape:'html':'UTF-8'} .tab-content .tab-pane").first().addClass("active");
</script>