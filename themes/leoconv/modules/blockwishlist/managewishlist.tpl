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

{if $products}
	{if !$refresh}
	<div class="wishlistLinkTop">
		<a href="#" id="hideSendWishlist" class="button_account"  onclick="WishlistVisibility('wishlistLinkTop', 'SendWishlist'); return false;">{l s='Close send this wishlist' mod='blockwishlist'}</a>
		<ul class="clearfix display_list">
			<li>
				<a href="#" id="hideBoughtProducts" class="button_account"  onclick="WishlistVisibility('wlp_bought', 'BoughtProducts'); return false;">{l s='Hide products' mod='blockwishlist'}</a>
				<a href="#" id="showBoughtProducts" class="button_account"  onclick="WishlistVisibility('wlp_bought', 'BoughtProducts'); return false;">{l s='Show products' mod='blockwishlist'}</a>
			</li>
			{if count($productsBoughts)}
			<li>
				<a href="#" id="hideBoughtProductsInfos" class="button_account" onclick="WishlistVisibility('wlp_bought_infos', 'BoughtProductsInfos'); return false;">{l s='Hide bought product\'s info' mod='blockwishlist'}</a>
				<a href="#" id="showBoughtProductsInfos" class="button_account"  onclick="WishlistVisibility('wlp_bought_infos', 'BoughtProductsInfos'); return false;">{l s='Show bought product\'s info' mod='blockwishlist'}</a>
			</li>
			{/if}
		</ul>
		<p class="wishlisturl">Permalink : <input type="text" value="{$base_dir_ssl}modules/blockwishlist/view.php?token={$token_wish|escape:'htmlall':'UTF-8'}" style="width:540px;" readonly="readonly" /></p>
		<p class="submit">
			<a href="#" id="showSendWishlist" class="button_account exclusive btn" onclick="WishlistVisibility('wl_send', 'SendWishlist'); return false;">{l s='Send this wishlist' mod='blockwishlist'}</a>
		</p>
	{/if}
	<div class="wlp_bought">
		<div class="clearfix wlp_bought_list">
		{foreach from=$products item=product name=i}
		{if $product@iteration%3==1}
        <div class="row-fluid">
        {/if}
			<div id="wlp_{$product.id_product}_{$product.id_product_attribute}" class="span4 clearfix address {if $smarty.foreach.i.index % 2}alternate_{/if}item">
				<div class="wl_item">
					<a href="javascript:;" class="lnkdel" onclick="WishlistProductManage('wlp_bought', 'delete', '{$id_wishlist}', '{$product.id_product}', '{$product.id_product_attribute}', $('#quantity_{$product.id_product}_{$product.id_product_attribute}').val(), $('#priority_{$product.id_product}_{$product.id_product_attribute}').val());" title="{l s='Delete' mod='blockwishlist'}">&raquo; {l s='Delete' mod='blockwishlist'}</a>
					<div class="row-fluid clearfix"> 
						<div class="product_image ">
							<a href="{$link->getProductlink($product.id_product, $product.link_rewrite, $product.category_rewrite)}" title="{l s='Product detail' mod='blockwishlist'}">
								<img src="{$link->getImageLink($product.link_rewrite, $product.cover, 'medium_default')}" alt="{$product.name|escape:'htmlall':'UTF-8'}" />
							</a>
						</div>
						<div class="product_infos ">
							<p id="s_title" class="product_name">{$product.name|truncate:30:'...'|escape:'htmlall':'UTF-8'}</p>
							<div class="wishlist_product_detail">
							{if isset($product.attributes_small)}
								<a href="{$link->getProductlink($product.id_product, $product.link_rewrite, $product.category_rewrite)}" title="{l s='Product detail' mod='blockwishlist'}">{$product.attributes_small|escape:'htmlall':'UTF-8'}</a>
							{/if}

								<form class="form-horizontal form-horizontal-mini">
									<div class="control-group">
										<label class="control-label">
											{l s='Quantity' mod='blockwishlist'}:
										</label>
										<div class="controls">
											<input type="text" id="quantity_{$product.id_product}_{$product.id_product_attribute}" value="{$product.quantity|intval}" size="3"  />
										</div>
									</div>
									<div class="control-group">
										<label class="control-label">
											{l s='Priority' mod='blockwishlist'}:
										</label>
										<div class="controls">
											<select id="priority_{$product.id_product}_{$product.id_product_attribute}">
												<option value="0"{if $product.priority eq 0} selected="selected"{/if}>{l s='High' mod='blockwishlist'}</option>
												<option value="1"{if $product.priority eq 1} selected="selected"{/if}>{l s='Medium' mod='blockwishlist'}</option>
												<option value="2"{if $product.priority eq 2} selected="selected"{/if}>{l s='Low' mod='blockwishlist'}</option>
											</select>
										</div>
									</div>
								</form>
							</div>
						</div> 
					</div>
					<div class="btn_action">
						<a href="javascript:;" class="exclusive lnksave btn" onclick="WishlistProductManage('wlp_bought_{$product.id_product_attribute}', 'update', '{$id_wishlist}', '{$product.id_product}', '{$product.id_product_attribute}', $('#quantity_{$product.id_product}_{$product.id_product_attribute}').val(), $('#priority_{$product.id_product}_{$product.id_product_attribute}').val());" title="{l s='Save' mod='blockwishlist'}">{l s='Save' mod='blockwishlist'}</a>
					</div>
				</div>
			</div>
		{if $product@iteration%3==0||$smarty.foreach.i.last}
		</div>
		{/if}
		{/foreach}
		</div>
	</div>
	{if !$refresh}
	<form method="post" class="wl_send std hidden form-horizontal" onsubmit="return (false);">
		<fieldset>
			<div class="required control-group">
				<label for="email1" class="control-label">{l s='Email' mod='blockwishlist'}1 <sup>*</sup></label>
				<div class="controls">
					<input type="text" name="email1" id="email1" />
				</div>
			</div>
			{section name=i loop=11 start=2}
			<div class="control-group">
				<label for="email{$smarty.section.i.index}" class="control-label">{l s='Email' mod='blockwishlist'}{$smarty.section.i.index}</label>
				<div class="controls">
					<input type="text" name="email{$smarty.section.i.index}" id="email{$smarty.section.i.index}" />
				</div>
			</div>
			{/section}
			<p class="submit">
				<input class="button" type="submit" value="{l s='Send' mod='blockwishlist'}" name="submitWishlist" onclick="WishlistSend('wl_send', '{$id_wishlist}', 'email');" />
			</p>
			<p class="required">
				<sup>* {l s='Required field'}</sup>
			</p>
		</fieldset>
	</form>
	{if count($productsBoughts)}
	<table class="wlp_bought_infos hidden std">
		<thead>
			<tr>
				<th class="first_item">{l s='Product' mod='blockwishlist'}</td>
				<th class="item">{l s='Quantity' mod='blockwishlist'}</td>
				<th class="item">{l s='Offered by' mod='blockwishlist'}</td>
				<th class="last_item">{l s='Date' mod='blockwishlist'}</td>
			</tr>
		</thead>
		<tbody>
		{foreach from=$productsBoughts item=product name=i}
			{foreach from=$product.bought item=bought name=j}
			{if $bought.quantity > 0}
				<tr>
					<td class="first_item">
						<span style="float:left;"><img src="{$link->getImageLink($product.link_rewrite, $product.cover, 'small_default')}" alt="{$product.name|escape:'htmlall':'UTF-8'}" /></span>			
						<span style="float:left;">
							{$product.name|truncate:40:'...'|escape:'htmlall':'UTF-8'}
						{if isset($product.attributes_small)}
							<br /><i>{$product.attributes_small|escape:'htmlall':'UTF-8'}</i>
						{/if}
						</span>
					</td>
					<td class="item align_center">{$bought.quantity|intval}</td>
					<td class="item align_center">{$bought.firstname} {$bought.lastname}</td>
					<td class="last_item align_center">{$bought.date_add|date_format:"%Y-%m-%d"}</td>
				</tr>
			{/if}
			{/foreach}
		{/foreach}
		</tbody>
	</table>
	{/if}
	{/if}
{else}
	<p class="warning">{l s='No products' mod='blockwishlist'}</p>
{/if}