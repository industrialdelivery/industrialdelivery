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

{capture name=path}{l s='Suppliers:'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1><span>{l s='Suppliers'}</span></h1>

{if isset($errors) AND $errors}
	{include file="$tpl_dir./errors.tpl"}
{else}
	<p class="clearfix title_manuf">{strip}
		<span class="title_shop">
			{if $nbSuppliers == 0}{l s='There are no suppliers.'}
			{else}
				{if $nbSuppliers == 1}
					{l s='There is %d supplier.' sprintf=$nbSuppliers}
				{else}
					{l s='There are %d suppliers.' sprintf=$nbSuppliers}
				{/if}
			{/if}
		</span>{/strip}
	</p>
    <div class="clear"></div>
{if $nbSuppliers > 0}
	<ul id="suppliers_list" class="mnf_sup_list clearfix">
	{foreach $suppliers_list as $supplier}
		<li class="shop_box clearfix {if $supplier@first}first_item{elseif $supplier@last}last_item{else}item{/if}">
				<!-- logo -->
				<div class="logo">
				{if $supplier.nb_products > 0}
				<a href="{$link->getsupplierLink($supplier.id_supplier, $supplier.link_rewrite)|escape:'htmlall':'UTF-8'}" title="{$supplier.name|escape:'htmlall':'UTF-8'}">
				{/if}
					<img src="{$img_sup_dir}{$supplier.image|escape:'htmlall':'UTF-8'}-brand.jpg" alt=""  />
				{if $supplier.nb_products > 0}
				</a>
				{/if}
				</div>
	<div class="left_side">
				<!-- name -->
				<h3>
					{if $supplier.nb_products > 0}
					<a class="product_link" href="{$link->getsupplierLink($supplier.id_supplier, $supplier.link_rewrite)|escape:'htmlall':'UTF-8'}">
					{/if}
					{$supplier.name|truncate:60:'...'|escape:'htmlall':'UTF-8'}
					{if $supplier.nb_products > 0}
					</a>
					{/if}
				</h3>
				<div>
				{if $supplier.nb_products > 0}
					<p class="product_desc">
				{/if}
						 <span>{$supplier.description|escape:'htmlall':'UTF-8'|strip_tags|truncate:160:'...'}</span>
                         <em class="des-small">{$supplier.description|escape:'htmlall':'UTF-8'|strip_tags|truncate:80:'...'}</em>
				{if $supplier.nb_products > 0}
				</p>
				{/if}
                
                				</div>
			</div>
                
						<div class="right_side">
				<p>
				{if $supplier.nb_products > 0}
					<a class="title_shop" href="{$link->getsupplierLink($supplier.id_supplier, $supplier.link_rewrite)|escape:'htmlall':'UTF-8'}">
				{/if}
					<span>{if $supplier.nb_products == 1}{l s='%d product' sprintf=$supplier.nb_products|intval}{else}{l s='%d products' sprintf=$supplier.nb_products|intval}{/if}</span>
				{if $supplier.nb_products > 0}
					</a>
				{/if}
				</p>


			{if $supplier.nb_products > 0}
				<a class="button" href="{$link->getsupplierLink($supplier.id_supplier, $supplier.link_rewrite)|escape:'htmlall':'UTF-8'}">{l s='view products'}</a>
			{/if}
			</div>
		</li>
	{/foreach}
	</ul>
	{include file="$tpl_dir./pagination.tpl"}
{/if}
{/if}
