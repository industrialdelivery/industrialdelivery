{*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{include file="$tpl_dir./breadcrumb.tpl"}
{include file="$tpl_dir./errors.tpl"}
{if isset($category)}
	{if $category->id AND $category->active}
		<h1>
        <span>
			{strip}
				{$category->name|escape:'htmlall':'UTF-8'}
				{if isset($categoryNameComplement)}
					{$categoryNameComplement|escape:'htmlall':'UTF-8'}
				{/if}
				<strong class="category-product-count">
					{include file="$tpl_dir./category-count.tpl"}
				</strong>
			{/strip}
           </span>
		</h1>
        <div class="row_category clearfix">
		{if $scenes}
			<!-- Scenes -->
			{include file="$tpl_dir./scenes.tpl" scenes=$scenes}

		{else}
			<!-- Category image -->
			{if $category->id_image}
			<div class="align_center category_image ">
				<img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')}" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage"  />
			</div>
			{/if}

		{/if}
        		{if $category->description}
			{if strlen($category->description) > 480}
			<p class="cat_desc clearfix" id="category_description_short">{$category->description|truncate:900}&nbsp;<span onclick="$('#category_description_short').hide(); $('#category_description_full').show();" class="lnk_more_cat">{l s='More'} </span></p>
			<p class="cat_desc clearfix" id="category_description_full" style="display:none">{$category->description}<span onclick="$('#category_description_short').show(); $('#category_description_full').hide();" class="lnk_more_cat close_cat">{l s='Hide'} </span></p>
			{else}
			<p class="cat_desc clearfix">{$category->description}</p>
			{/if}
        
            		{/if}
                        </div>
		{if isset($subcategories)}
		<!-- Subcategories -->
		<div id="subcategories" class="titled_box ">
			<h2>{l s='Subcategories'}</h2>
			<ul class="clearfix">
			{foreach from=$subcategories item=subcategory name=subcategories}
				<li class="shop_box {if $smarty.foreach.subcategories.iteration is div by 5}product_list_5{/if} {if $smarty.foreach.subcategories.iteration is div by 4}product_list_4{/if} {if $smarty.foreach.subcategories.iteration is div by 3}product_list_3{/if} {if $smarty.foreach.subcategories.iteration is div by 4}product_list_4{/if}">
					<a class="" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$subcategory.name|escape:'htmlall':'UTF-8'}">
						{if $subcategory.id_image}
							<img src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'category_default')}" alt="" />
						{else}
						<img src="{$img_cat_dir}default-medium_default.jpg" alt="" />
						{/if}
					
					</a>
              <a class="lnk_more_sub" href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$subcategory.name|escape:'htmlall':'UTF-8'}">{$subcategory.name|escape:'htmlall':'UTF-8'|truncate:15:'...'}		</a>
				</li>
			{/foreach}
			</ul>
		</div>


		{/if}

		{if $products}
            <div class="sortPagiBar shop_box_row shop_box_row clearfix">
            {include file="./product-sort.tpl"}
            {include file="./nbr-product-page.tpl"}
            </div>
            {include file="./product-list.tpl" products=$products}
            <div class="bottom_pagination shop_box_row  clearfix">
            {include file="./product-compare.tpl"}
            {include file="./pagination.tpl"}
            </div>
        {/if}
	{elseif $category->id}
		<p class="warning">{l s='This category is currently unavailable.'}</p>
	{/if}
{/if}
