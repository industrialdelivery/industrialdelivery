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

{capture name=path}{l s='Your shopping cart'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1 id="cart_title"><span>{l s='Shopping-cart summary'}</span></h1>

{if isset($account_created)}
	<p class="success">
		{l s='Your account has been created.'}
	</p>
{/if}
{assign var='current_step' value='summary'}
{include file="$tpl_dir./order-steps.tpl"}
{include file="$tpl_dir./errors.tpl"}

{if isset($empty)}
	<p class="warning"><i class="icon-info-sign"></i>{l s='Your shopping cart is empty.'}</p>
{elseif $PS_CATALOG_MODE}
	<p class="warning"><i class="icon-info-sign"></i>{l s='This store has not accepted your new order.'}</p>
{else}
	<script type="text/javascript">
	// <![CDATA[
	var currencySign = '{$currencySign|html_entity_decode:2:"UTF-8"}';
	var currencyRate = '{$currencyRate|floatval}';
	var currencyFormat = '{$currencyFormat|intval}';
	var currencyBlank = '{$currencyBlank|intval}';
	var txtProduct = "{l s='product' js=1}";
	var txtProducts = "{l s='products' js=1}";
	var deliveryAddress = {$cart->id_address_delivery|intval};
	// ]]>
	</script>
	<p style="display:none" id="emptyCartWarning" class="warning"><i class="icon-info-sign"></i>{l s='Your shopping cart is empty.'}</p>
{*
{if isset($lastProductAdded) AND $lastProductAdded}
	<div class="cart_last_product">
		<div class="cart_last_product_header">
			<div class="left">{l s='Last product added'}</div>
		</div>
		<a  class="cart_last_product_img" href="{$link->getProductLink($lastProductAdded.id_product, $lastProductAdded.link_rewrite, $lastProductAdded.category, null, null, $lastProductAdded.id_shop)|escape:'htmlall':'UTF-8'}"><img src="{$link->getImageLink($lastProductAdded.link_rewrite, $lastProductAdded.id_image, 'small_default')}" alt="{$lastProductAdded.name|escape:'htmlall':'UTF-8'}"/></a>
		<div class="cart_last_product_content">
			<p class="s_title_block"><a href="{$link->getProductLink($lastProductAdded.id_product, $lastProductAdded.link_rewrite, $lastProductAdded.category, null, null, null, $lastProductAdded.id_product_attribute)|escape:'htmlall':'UTF-8'}">{$lastProductAdded.name|escape:'htmlall':'UTF-8'}</a></p>
			{if isset($lastProductAdded.attributes) && $lastProductAdded.attributes}<a href="{$link->getProductLink($lastProductAdded.id_product, $lastProductAdded.link_rewrite, $lastProductAdded.category, null, null, null, $lastProductAdded.id_product_attribute)|escape:'htmlall':'UTF-8'}">{$lastProductAdded.attributes|escape:'htmlall':'UTF-8'}</a>{/if}
		</div>
		<br class="clear" />
	</div>
{/if}
*}  
<p class="ordercart-title">{l s='Your shopping cart contains:'} <span id="summary_products_quantity">{$productNumber} {if $productNumber == 1}{l s='product'}{else}{l s='products'}{/if}</span></p>
<div id="order-detail-content" class="table_block">
	<table id="cart_summary" class="std">
		<thead>
		</thead>
		<tfoot>
		{if $use_taxes}
			{if $priceDisplay}
				<tr class="cart_total_price ">
					<td>{if $display_tax_label}{l s='Total products (tax excl.)'}{else}{l s='Total products'}{/if}</td>
						<td class="price" id="total_product">{displayPrice price=$total_products}</td>
				</tr>
			{else}
				<tr class="cart_total_price">
					<td>{if $display_tax_label}{l s='Total products (tax incl.)'}{else}{l s='Total products'}{/if}</td>
					<td  class="price" id="total_product">{displayPrice price=$total_products_wt}</td>
				</tr>
			{/if}
		{else}
			<tr class="cart_total_price">
				<td >{l s='Total products'}</td>
				<td class="price" id="total_product">{displayPrice price=$total_products}</td>
			</tr>
		{/if}
			<tr class="cart_total_voucher" {if $total_wrapping == 0} style="display: none;"{/if}>
				<td >
				{if $use_taxes}
					{if $display_tax_label}{l s='Total gift wrapping (tax incl.):'}{else}{l s='Total gift-wrapping cost:'}{/if}
				{else}
					{l s='Total gift-wrapping cost:'}
				{/if}
				</td>
				<td  class="price-discount price" id="total_discount">
{if $use_taxes && !$priceDisplay}
					{assign var='total_discounts_negative' value=$total_discounts * -1}
				{else}
					{assign var='total_discounts_negative' value=$total_discounts_tax_exc * -1}
				{/if}
				{displayPrice price=$total_discounts_negative}
				</td>
			</tr>
			<tr class="cart_total_voucher" {if $total_wrapping == 0}style="display: none;"{/if}>
				<td >
				{if $use_taxes}
					{if $display_tax_label}{l s='Total gift-wrapping (tax incl.):'}{else}{l s='Total gift-wrapping:'}{/if}
				{else}
					{l s='Total gift-wrapping:'}
				{/if}
				</td>
				<td  class="price-discount price" id="total_wrapping">
                {if $use_taxes}
					{if $priceDisplay}
						{displayPrice price=$total_wrapping_tax_exc}
					{else}
						{displayPrice price=$total_wrapping}
					{/if}
				{else}
					{displayPrice price=$total_wrapping_tax_exc}
				{/if}
				</td>
			</tr>
			{if $total_shipping_tax_exc <= 0 && !isset($virtualCart)}
				<tr class="cart_total_delivery" style="{if !isset($carrier->id) || is_null($carrier->id)}display:none;{/if}">
					<td >{l s='Shipping'}</td>
					<td  class="price" id="total_shipping">{l s='Free Shipping!'}</td>
				</tr>
			{else}
				{if $use_taxes}
					{if $priceDisplay}
						<tr class="cart_total_delivery" {if $total_shipping_tax_exc <= 0} style="display:none;"{/if}>
							<td >{if $display_tax_label}{l s='Total shipping (tax excl.)'}{else}{l s='Total shipping'}{/if}</td>
							<td  class="price" id="total_shipping">{displayPrice price=$total_shipping_tax_exc}</td>
						</tr>
					{else}
						<tr class="cart_total_delivery"{if $total_shipping <= 0} style="display:none;"{/if}>
							<td >{if $display_tax_label}{l s='Total shipping (tax incl.)'}{else}{l s='Total shipping'}{/if}</td>
							<td  class="price" id="total_shipping" >{displayPrice price=$total_shipping}</td>
						</tr>
					{/if}
				{else}
					<tr class="cart_total_delivery"{if $total_shipping_tax_exc <= 0} style="display:none;"{/if}>
						<td >{l s='Total shipping'}</td>
						<td class="price" id="total_shipping" >{displayPrice price=$total_shipping_tax_exc}</td>
					</tr>
				{/if}
			{/if}
			<tr class="cart_total_price ">
				<td>{l s='Total (tax excl.)'}</td>
				<td class="price" id="total_price_without_tax">
                {displayPrice price=$total_price_without_tax}
                </td>
			</tr>
			<tr class="cart_total_tax">
				<td>{l s='Total tax'}</td>
				<td class="price" id="total_tax">{displayPrice price=$total_tax}</td>
			</tr>
			<tr class="cart_total_price cart_last_tr">
				{if $use_taxes}
				<td  class="total_price_container" id="total_price_container">
				{l s='Total'}
                </td>	
				<td class="price">	
				{displayPrice price=$total_price}
                </td>
				</td>
				{else}
				<td  class="total_price_container" id="total_price_container">
                {l s='Total'}
				</td>	
				<td class="price">
                {displayPrice price=$total_price_without_tax}
                </td>
				</td>
				{/if}
			</tr>

		</tfoot>
		<tbody>
		{foreach $products as $product}
			{assign var='productId' value=$product.id_product}
			{assign var='productAttributeId' value=$product.id_product_attribute}
			{assign var='quantityDisplayed' value=0}
			{assign var='odd' value=$product@iteration%2}
			{assign var='ignoreProductLast' value=isset($customizedDatas.$productId.$productAttributeId) || count($gift_products)}
			{* Display the product line *}
			{include file="./shopping-cart-product-line.tpl" productLast=$product@last productFirst=$product@first}
			{* Then the customized datas ones*}
			{if isset($customizedDatas.$productId.$productAttributeId)}
				{foreach $customizedDatas.$productId.$productAttributeId[$product.id_address_delivery] as $id_customization=>$customization}
					<tr id="product_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}" class="bordercolor product_customization_for_{$product.id_product}_{$product.id_product_attribute}_{$product.id_address_delivery|intval} {if $odd}odd{else}even{/if} customization alternate_item {if $product@last && $customization@last && !count($gift_products)}last_item{/if}">
						<td >
							{foreach $customization.datas as $type => $custom_data}
								{if $type == $CUSTOMIZE_FILE}
									<div class="customizationUploaded">
										<ul class="customizationUploaded">
											{foreach $custom_data as $picture}
												<li><img src="{$pic_dir}{$picture.value}_small" alt="" class="customizationUploaded" /></li>
											{/foreach}
										</ul>
									</div>
								{elseif $type == $CUSTOMIZE_TEXTFIELD}
									<ul class="typedText">
										{foreach $custom_data as $textField}
											<li>
												{if $textField.name}
													{$textField.name}
												{else}
													{l s='Text #'}{$textField@index+1}
												{/if}
												{l s=':'} {$textField.value}
											</li>
										{/foreach}
										
									</ul>
								{/if}

							{/foreach}
						</td>
						<td class="cart_quantity">
							{if isset($cannotModify) AND $cannotModify == 1}
								<span style="float:left">{if $quantityDisplayed == 0 AND isset($customizedDatas.$productId.$productAttributeId)}{$customizedDatas.$productId.$productAttributeId|@count}{else}{$product.cart_quantity-$quantityDisplayed}{/if}</span>
							{else}
								<div id="cart_quantity_button" class="cart_quantity_button" style="float:left">
																{if $product.minimal_quantity < ($customization.quantity -$quantityDisplayed) OR $product.minimal_quantity <= 1}
								<a rel="nofollow" class="cart_quantity_down" id="cart_quantity_down_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}" href="{$link->getPageLink('cart', true, NULL, "add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_address_delivery={$product.id_address_delivery}&amp;id_customization={$id_customization}&amp;op=down&amp;token={$token_cart}")}" title="{l s='Subtract'}">
									<img src="{$img_dir}icon/quantity_down.gif" alt="{l s='Subtract'}" width="23" height="24" />
								</a>
								{else}
								<a class="cart_quantity_down"  id="cart_quantity_down_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}" href="#" title="{l s='Subtract'}">
									<img src="{$img_dir}icon/quantity_down.gif" alt="{l s='Subtract'}" width="23" height="24" />
								</a>
								{/if}
                                		<input type="hidden" value="{$customization.quantity}" name="quantity_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_hidden"/>
								<input size="2" type="text" value="{$customization.quantity}" class="cart_quantity_input" name="quantity_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}"/>
                                <a rel="nofollow" class="cart_quantity_up" id="cart_quantity_up_{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}" href="{$link->getPageLink('cart', true, NULL, "add&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_address_delivery={$product.id_address_delivery}&amp;id_customization={$id_customization}&amp;token={$token_cart}")}" title="{l s='Add'}"><img src="{$img_dir}icon/quantity_up.gif" alt="{l s='Add'}" width="23" height="24" /></a>

								</div>
						
							{/if}
                            				{if isset($cannotModify) AND $cannotModify == 1}
							{else}
			<div class="div_cart_quantity_delete">
									<a rel="nofollow" class="cart_quantity_delete" id="{$product.id_product}_{$product.id_product_attribute}_{$id_customization}_{$product.id_address_delivery|intval}" href="{$link->getPageLink('cart', true, NULL, "delete&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_customization={$id_customization}&amp;id_address_delivery={$product.id_address_delivery}&amp;token={$token_cart}")}"></a>
								</div>
							{/if}
						</td>

			

					</tr>
					{assign var='quantityDisplayed' value=$quantityDisplayed+$customization.quantity}
				{/foreach}
				{* If it exists also some uncustomized products *}
				{if $product.quantity-$quantityDisplayed > 0}{include file="./shopping-cart-product-line.tpl" productLast=$product@last productFirst=$product@first}{/if}
			{/if}
		{/foreach}
		{assign var='last_was_odd' value=$product@iteration%2}
		{foreach $gift_products as $product}
			{assign var='productId' value=$product.id_product}
			{assign var='productAttributeId' value=$product.id_product_attribute}
			{assign var='quantityDisplayed' value=0}
			{assign var='odd' value=($product@iteration+$last_was_odd)%2}
			{assign var='ignoreProductLast' value=isset($customizedDatas.$productId.$productAttributeId)}
			{assign var='cannotModify' value=1}
			{* Display the gift product line *}
			{include file="./shopping-cart-product-line.tpl" productLast=$product@last productFirst=$product@first}
		{/foreach}
		</tbody>
	{if sizeof($discounts)}
		<tbody>
		{foreach $discounts as $discount}
			<tr class="cart_discount bordercolor {if $discount@last}last_item{elseif $discount@first}first_item{else}item{/if}" id="cart_discount_{$discount.id_discount}">
				<td class="cart_discount_name">{$discount.name}</td>
				<td class="cart_discount_price">
                <span class="title-th">{l s='Unit price'}:</span>
                <span class="price-discount price">
					{if !$priceDisplay}{displayPrice price=$discount.value_real*-1}{else}{displayPrice price=$discount.value_tax_exc*-1}{/if}
				</span>
                         <div class="clear"></div>
                <span class="title-th">{l s='Qty'}:</span>
              <div style=" float:left;">  1 </div>		
                {if strlen($discount.code)}
                			<div class="div_cart_quantity_delete">
                <a href="{if $opc}{$link->getPageLink('order-opc', true)}{else}{$link->getPageLink('order', true)}{/if}?deleteDiscount={$discount.id_discount}" class="price_discount_delete" title="{l s='Delete'}">
                </a>
                </div>
                {/if}
                         <div class="clear"></div>
                <span class="title-th">{l s='Total'}:</span>
                <span class="price-discount price total-pr">{if !$priceDisplay}{displayPrice price=$discount.value_real*-1}{else}{displayPrice price=$discount.value_tax_exc*-1}{/if}</span>
                </td>
			</tr>
		{/foreach}
		</tbody>
	{/if}
	</table>
</div>

            <div id="cart_voucher" class="table_block">
            				{if $voucherAllowed}
					{if isset($errors_discount) && $errors_discount}
						<ul class="error">
						{foreach $errors_discount as $k=>$error}
							<li>{$error|escape:'htmlall':'UTF-8'}</li>
						{/foreach}
						</ul>
					{/if}
					<form action="{if $opc}{$link->getPageLink('order-opc', true)}{else}{$link->getPageLink('order', true)}{/if}" method="post" id="voucher">
						<fieldset class="clearfix">
							<h4><label for="discount_name">{l s='Vouchers'}</label></h4>
							<p>
								<input type="text" class="discount_name" id="discount_name" name="discount_name" value="{if isset($discount_name) && $discount_name}{$discount_name}{/if}" />
							</p>
							<p class="submit"><input type="hidden" name="submitDiscount" /><input type="submit" name="submitAddDiscount" value="{l s='OK'}" class="btn btn-inverse" /></p>
						{if $displayVouchers}
							<h4 class="title_offers">{l s='Take advantage of our offers:'}</h4>
							<div id="display_cart_vouchers">
							{foreach $displayVouchers as $voucher}
								<span onclick="$('#discount_name').val('{$voucher.name}');return false;" class="voucher_name">{$voucher.name}</span> - {$voucher.description} <br />
							{/foreach}
							</div>
						{/if}
						</fieldset>
					</form>
				{/if}
            </div>

{if $show_option_allow_separate_package}
<p id="seperated_packag">
	<input type="checkbox" name="allow_seperated_package" id="allow_seperated_package" {if $cart->allow_seperated_package}checked="checked"{/if} />
	<label for="allow_seperated_package">{l s='Send available products first'}</label>
</p>
{/if}
{if !$opc}
	{if Configuration::get('PS_ALLOW_MULTISHIPPING')}
		<p id="seperated_packag">
			<input type="checkbox" {if $multi_shipping}checked="checked"{/if} id="enable-multishipping" />
			<label for="enable-multishipping">{l s='I would like to specify a delivery address for each individual product.'}</label>
		</p>
	{/if}
{/if}

<div id="HOOK_SHOPPING_CART">{$HOOK_SHOPPING_CART}</div>

{* Define the style if it doesn't exist in the PrestaShop version*}
{* Will be deleted for 1.5 version and more *}
{if !isset($addresses_style)}
	{$addresses_style.company = 'address_company'}
	{$addresses_style.vat_number = 'address_company'}
	{$addresses_style.firstname = 'address_name'}
	{$addresses_style.lastname = 'address_name'}
	{$addresses_style.address1 = 'address_address1'}
	{$addresses_style.address2 = 'address_address2'}
	{$addresses_style.city = 'address_city'}
	{$addresses_style.country = 'address_country'}
	{$addresses_style.phone = 'address_phone'}
	{$addresses_style.phone_mobile = 'address_phone_mobile'}
	{$addresses_style.alias = 'address_title'}
{/if}

{if ((!empty($delivery_option) AND !isset($virtualCart)) OR $delivery->id OR $invoice->id) AND !$opc}
<div class="order_delivery">
	{if !isset($formattedAddresses) || (count($formattedAddresses.invoice) == 0 && count($formattedAddresses.delivery) == 0) || (count($formattedAddresses.invoice.formated) == 0 && count($formattedAddresses.delivery.formated) == 0)}
		{if $delivery->id}
		<ul id="delivery_address" class="address item">
			<li class="address_title">{l s='Delivery address'}&nbsp;<span class="address_alias">({$delivery->alias})</span></li>
			{if $delivery->company}<li class="address_company">{$delivery->company|escape:'htmlall':'UTF-8'}</li>{/if}
			<li class="address_name">{$delivery->firstname|escape:'htmlall':'UTF-8'} {$delivery->lastname|escape:'htmlall':'UTF-8'}</li>
			<li class="address_address1">{$delivery->address1|escape:'htmlall':'UTF-8'}</li>
			{if $delivery->address2}<li class="address_address2">{$delivery->address2|escape:'htmlall':'UTF-8'}</li>{/if}
			<li class="address_city">{$delivery->postcode|escape:'htmlall':'UTF-8'} {$delivery->city|escape:'htmlall':'UTF-8'}</li>
			<li class="address_country">{$delivery->country|escape:'htmlall':'UTF-8'} {if $delivery_state}({$delivery_state|escape:'htmlall':'UTF-8'}){/if}</li>
		</ul>
		{/if}
		{if $invoice->id}
		<ul id="invoice_address" class="address alternate_item">
			<li class="address_title">{l s='Invoice address'}&nbsp;<span class="address_alias">({$invoice->alias})</span></li>
			{if $invoice->company}<li class="address_company">{$invoice->company|escape:'htmlall':'UTF-8'}</li>{/if}
			<li class="address_name">{$invoice->firstname|escape:'htmlall':'UTF-8'} {$invoice->lastname|escape:'htmlall':'UTF-8'}</li>
			<li class="address_address1">{$invoice->address1|escape:'htmlall':'UTF-8'}</li>
			{if $invoice->address2}<li class="address_address2">{$invoice->address2|escape:'htmlall':'UTF-8'}</li>{/if}
			<li class="address_city">{$invoice->postcode|escape:'htmlall':'UTF-8'} {$invoice->city|escape:'htmlall':'UTF-8'}</li>
			<li class="address_country">{$invoice->country|escape:'htmlall':'UTF-8'} {if $invoice_state}({$invoice_state|escape:'htmlall':'UTF-8'}){/if}</li>
		</ul>
		{/if}
	{else}
		{foreach from=$formattedAddresses key=k item=address}
			<ul class="address {if $address@last}last_item{elseif $address@first}first_item{/if} {if $address@index % 2}alternate_item{else}item{/if}">
				<li class="address_title">{if $k eq 'invoice'}{l s='Invoice address'}{elseif $k eq 'delivery' && $delivery->id}{l s='Delivery address'}{/if}{if isset($address.object.alias)}&nbsp;<span class="address_alias">({$address.object.alias})</span>{/if}</li>
				{foreach $address.ordered as $pattern}
					{assign var=addressKey value=" "|explode:$pattern}
					<li>
                    {foreach $addressKey as $key}   
						<span class="{if isset($addresses_style[$key])}{$addresses_style[$key]}{/if}">
							{if isset($address.formated[$key])}
								{$address.formated[$key]|escape:'htmlall':'UTF-8'}
							{/if}
						</span>
					{/foreach}
					</li>
				{/foreach}
				</ul>
		{/foreach}
            <div class="clearblock"></div>
	{/if}
</div>
{/if}
<p class="cart_navigation inner-top">
	{if !$opc}
		<a href="{if $back}{$link->getPageLink('order', true, NULL, 'step=1&amp;back={$back}')}{else}{$link->getPageLink('order', true, NULL, 'step=1')}{/if}" class="exclusive standard-checkout" title="{l s='Next'}">{l s='Next'} &raquo;</a>
		{if Configuration::get('PS_ALLOW_MULTISHIPPING')}
			<a href="{if $back}{$link->getPageLink('order', true, NULL, 'step=1&amp;back={$back}')}{else}{$link->getPageLink('order', true, NULL, 'step=1')}{/if}&amp;multi-shipping=1" class="multishipping-button multishipping-checkout exclusive" title="{l s='Next'}">{l s='Next'} &raquo;</a>
		{/if}
	{/if}
	<a href="{if (isset($smarty.server.HTTP_REFERER) && strstr($smarty.server.HTTP_REFERER, 'order.php')) || isset($smarty.server.HTTP_REFERER) && strstr($smarty.server.HTTP_REFERER, 'order-opc') || !isset($smarty.server.HTTP_REFERER)}{$link->getPageLink('index')}{else}{$smarty.server.HTTP_REFERER|escape:'htmlall':'UTF-8'|secureReferrer}{/if}" class="button_large" title="{l s='Continue shopping'}">&laquo; {l s='Continue shopping'}</a>
</p>
	{if !empty($HOOK_SHOPPING_CART_EXTRA)}
		<div class="clear"></div>
		<div class="cart_navigation_extra">
			<div id="HOOK_SHOPPING_CART_EXTRA">{$HOOK_SHOPPING_CART_EXTRA}</div>
		</div>
	{/if}
{/if}

