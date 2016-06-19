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

{capture name=path}{l s='My account'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}

<h1><span>{l s='My account'}</span></h1>
{if isset($account_created)}
	<p class="success">
		{l s='Your account has been created.'}
	</p>
{/if}
<div class="titled_box">
<h2><span>{l s='Welcome to your account. Here you can manage al of your personal information and orders. '}</span></h2>
<ul class="myaccount_lnk_list">
	{if $has_customer_an_address}
	<li><a href="{$link->getPageLink('address', true)}" title="{l s='Add my first address'}"><img src="{$img_dir}icon/addrbook.png" alt="{l s='Add my first address'}" class="icon" /> {l s='Add my first address'}</a></li>
	{/if}
	<li><a href="{$link->getPageLink('history', true)}" title="{l s='Orders'}"><img src="{$img_dir}icon/order.png" alt="{l s='Orders'}" class="icon" /> {l s='Order history and details '}</a></li>
	{if $returnAllowed}
		<li><a href="{$link->getPageLink('order-follow', true)}" title="{l s='Merchandise returns'}"><img src="{$img_dir}icon/return.png" alt="{l s='Merchandise returns'}" class="icon" /> {l s='My merchandise returns'}</a></li>
	{/if}
	<li><a href="{$link->getPageLink('order-slip', true)}" title="{l s='Credit slips'}"><img src="{$img_dir}icon/slip.png" alt="{l s='Credit slips'}" class="icon" /> {l s='My credit slips'}</a></li>
	<li><a href="{$link->getPageLink('addresses', true)}" title="{l s='Addresses'}"><img src="{$img_dir}icon/addrbook.png" alt="{l s='Addresses'}" class="icon" /> {l s='My addresses'}</a></li>
	<li><a href="{$link->getPageLink('identity', true)}" title="{l s='Information'}"><img src="{$img_dir}icon/userinfo.png" alt="{l s='Information'}" class="icon" /> {l s='My personal information'}</a></li>
	{if $voucherAllowed}
		<li><a href="{$link->getPageLink('discount', true)}" title="{l s='Vouchers'}"><img src="{$img_dir}icon/voucher.png" alt="{l s='Vouchers'}" class="icon" /> {l s='My vouchers'}</a></li>
	{/if}
	{$HOOK_CUSTOMER_ACCOUNT}
</ul>
</div>
<ul class="footer_links">
<li><a href="{$base_dir}" title="{l s='Home'}"><i class="icon-home"></i>{l s='Home'}</a></li>
</ul>