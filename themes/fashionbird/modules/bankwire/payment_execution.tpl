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

{capture name=path}{l s='Bank-wire payment.' mod='bankwire'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1><span>{l s='Order summary' mod='bankwire'}</span></h1>

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

{if $nbProducts <= 0}
	<p class="warning">{l s='Your shopping cart is empty.' mod='bankwire'}</p>
{else}
<div class="titled_box">
<h2> <span>{l s='Bank-wire payment.' mod='bankwire'}</span></h2>
</div>
<form action="{$link->getModuleLink('bankwire', 'validation', [], true)}" method="post">
		<div class="box-payment-style">
	<img src="{$img_dir}/bankwire.jpg" alt="{l s='bank wire' mod='bankwire'}" width="86" height="54" style="float:left; margin: 0px 10px 5px 0px;" />
	<em >{l s='You have chosen to pay by bank wire.' mod='bankwire'}
	{l s='Here is a short summary of your order:' mod='bankwire'}</em><br />

	<i class="icon-angle-right"></i>  {l s='The total amount of your order is' mod='bankwire'}<br />
	<span id="amount" class="price">{displayPrice price=$total}</span>
	{if $use_taxes == 1}
    	{l s='(tax incl.)' mod='bankwire'}
    {/if}<br />
<i class="icon-angle-right"></i> 
	{if $currencies|@count > 1}
		{l s='We allow several currencies to be sent via bank wire.' mod='bankwire'}
		{l s='Choose one of the following:' mod='bankwire'}
		<select id="currency_payement" name="currency_payement" onchange="setCurrency($('#currency_payement').val());">
			{foreach from=$currencies item=currency}
				<option value="{$currency.id_currency}" {if $currency.id_currency == $cust_currency}selected="selected"{/if}>{$currency.name}</option>
			{/foreach}
		</select>
	{else}
		{l s='We allow the following currency to be sent via bank wire:' mod='bankwire'}&nbsp;<b>{$currencies.0.name}
		<input type="hidden" name="currency_payement" value="{$currencies.0.id_currency}" />
	{/if}
<br />

<i class="icon-angle-right"></i>{l s='Bank wire account information will be displayed on the next page.' mod='bankwire'}}<br />
<i class="icon-angle-right"></i><b>{l s='Please confirm your order by clicking "Place my order."' mod='bankwire'}.</b>
</div>
<p class="cart_navigation">
	<input type="submit" name="submit" value="{l s='Place my order' mod='bankwire'}" class="exclusive_large" />
	<a href="{$link->getPageLink('order', true, NULL, "step=3")}" class="button_large">{l s='Other payment methods' mod='bankwire'}</a>
</p>
</form>
{/if}
