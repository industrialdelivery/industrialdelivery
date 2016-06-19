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

{include file="$tpl_dir./errors.tpl"}
 
{if isset($category)}
	{if $category->id AND $category->active} 
		{if $scenes || $category->description || $category->id_image}
		<div class="content_scene_cat block_box">
			{if $scenes}
				<!-- Scenes -->
				{include file="$tpl_dir./scenes.tpl" scenes=$scenes}
			{else}
				<!-- Category image -->
				{if $category->id_image}
				<div class="align_center">
					<img src="{$link->getCatImageLink($category->link_rewrite, $category->id_image, 'category_default')}" alt="{$category->name|escape:'htmlall':'UTF-8'}" title="{$category->name|escape:'htmlall':'UTF-8'}" id="categoryImage"  />
				</div>
				{/if}
			{/if}

			{if $category->description}
				<div class="cat_desc">
				{if strlen($category->description) > 120}
					<p id="category_description_short">{$category->description|truncate:120}</p>
					<p id="category_description_full" style="display:none">{$category->description}</p>
					<a href="#" onclick="$('#category_description_short').hide(); $('#category_description_full').show(); $(this).hide(); return false;" class="lnk_more">{l s='More'}</a>
				{else}
					<p>{$category->description}</p>
				{/if}
				</div>
			{/if}
		</div>
		{/if}  
		<div class="block_box_center">
			<h4 class="title-category">
				{strip}
					{$category->name|escape:'htmlall':'UTF-8'}
					{if isset($categoryNameComplement)}
						{$categoryNameComplement|escape:'htmlall':'UTF-8'}
					{/if}
				{/strip}
				<span class="fs11 resumecat category-product-count">
					/ {include file="$tpl_dir./category-count.tpl"}
				</span>
			</h4>
			
			{if isset($subcategories)}
			<!-- Subcategories -->
			<div id="subcategories">
				<h3>{l s='Subcategories'}</h3>
				<div class="inline_list">
				{foreach from=$subcategories item=subcategory name=subcategories}
					{if $subcategory@iteration%4==1}
					<div class="row-fluid">
					{/if} 
					<div class="span4">
						<div class="category-container product-container"> 
							<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$subcategory.name|escape:'htmlall':'UTF-8'}" class="img">
								{if $subcategory.id_image}
									<img src="{$link->getCatImageLink($subcategory.link_rewrite, $subcategory.id_image, 'large_default')}" alt=""/>
								{else}
									<img src="{$img_cat_dir}default-large_default.jpg" alt="" />
								{/if}
							</a>
							<a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}" class="cat_name s_title_block">{$subcategory.name|escape:'htmlall':'UTF-8'}</a>
							{if $subcategory.description}
								<p class="product_desc">{$subcategory.description|escape|truncate:85:'...':true}</p>
							{/if}
						</div>
					</div>
					{if $subcategory@iteration%4==0||$smarty.foreach.subcategories.last}
					</div>
					{/if}
				{/foreach}
				</div>
				<br class="clear"/>
			</div>
			{/if}

			{if $products}
			<div class="products-list">
				<div class="content_sortPagiBar">
					<div class="row-fluid sortPagiBar">                    
						<div class="span3 hidden-phone productsview">
							<div class="inner">
								<span>{l s='View as:'}&nbsp;&nbsp;</span>
							  <div id="productsview">
								<a href="#" rel="view-grid"><i class="icon-th-large active" ></i></a>
								<a href="#"  rel="view-list"><i class="icon-list"></i></a>
							  </div>
							</div>
						</div>
						<div class="span6 hidden-phone">
							<div class="inner">
								{include file="./product-sort.tpl"}
							</div>
						</div> 
						<div class="span3">
							<div class="inner">
								{include file="./product-compare.tpl"}
							</div>
						</div>
					</div>
				</div> 
				{include file="./product-list.tpl" products=$products} 
				<div class="content_sortPagiBar">
					<div class="row-fluid sortPagiBar">                    
						<div class="span3 hidden-phone productsview">
							<div class="inner">
								<span>{l s='View as:'}&nbsp;&nbsp;</span>
							  <div id="productsview">
								<a href="#" rel="view-grid"><i class="icon-th-large active" ></i></a>
								<a href="#"  rel="view-list"><i class="icon-list"></i></a>
							  </div>
							</div>
						</div>
						<div class="span6 hidden-phone">
							<div class="inner">
								{include file="./product-sort.tpl"}
							</div>
						</div> 
						<div class="span3">
							<div class="inner">
								{include file="./product-compare.tpl"}
							</div>
						</div>
					</div>
				</div> 
				
				{include file="./pagination.tpl"}
				
			</div>
			{/if}
		</div>
	{elseif $category->id}
		<p class="warning">{l s='This category is currently unavailable.'}</p>
	{/if}
{/if}
