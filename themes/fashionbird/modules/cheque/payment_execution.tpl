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

{capture name=path}{l s='Check payment' mod='cheque'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1><span>{l s='Order summary' mod='cheque'}</span></h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if isset($nbProducts) && $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.'}</p>
{else}
<div class="titled_box">
<h2><span>{l s='Check payment' mod='cheque'}</span></h2>
</div>
<form action="{$link->getModuleLink('cheque', 'validation', [], true)}" method="post">
		<div class="box-payment-style">
        <img src="{$img_dir}/cheque.jpg" alt="{l s='Check' mod='cheque'}" width="86" height="54"  />
<em >			{l s='You have chosen to pay by check.' mod='cheque'}
		{l s='Here is a short summary of your order:' mod='cheque'}</em><br/>
		<i class="icon-angle-right"></i>{l s='The total amount of your order comes to:' mod='cheque'}
		<span id="amount" class="price">{displayPrice price=$total}</span>
		{if $use_taxes == 1}
			{l s='(tax incl.)' mod='cheque'}
		{/if}<br />
		<i class="icon-angle-right"></i> {if isset($currencies) && $currencies|@count > 1}
			{l s='We accept several currencies to receive payments by check.' mod='cheque'}
		{l s='Choose one of the following:' mod='cheque'}
			<select id="currency_payement" name="currency_payement" onchange="setCurrency($('#currency_payement').val());">
			{foreach from=$currencies item=currency}
				<option value="{$currency.id_currency}" {if isset($currencies) && $currency.id_currency == $cust_currency}selected="selected"{/if}>{$currency.name}</option>
			{/foreach}
			</select>
		{else}
			{l s='We allow the following currencies to be sent by check:' mod='cheque'}&nbsp;<b>{$currencies.0.name}</b><br />
			<input type="hidden" name="currency_payement" value="{$currencies.0.id_currency}" />
		{/if}
	<i class="icon-angle-right"></i>{l s='Check owner and address information will be displayed on the next page.' mod='cheque'}<br />
		<i class="icon-angle-right"></i>{l s='Please confirm your order by clicking \'I confirm my order\'' mod='cheque'}.
        </div>
	<p class="cart_navigation">
		<input type="submit" name="submit" value="{l s='I confirm my order' mod='cheque'}" class="exclusive_large" />
		<a href="{$link->getPageLink('order', true, NULL, "step=3")}" class="button_large">{l s='Other payment methods' mod='cheque'}</a>
	</p>
</form>
{/if}
