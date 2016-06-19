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
<section id="contact_block" class="block column_box">
	<h4 class="title_block"><span>{l s='Contact us' mod='blockcontact'}</span></h4>
	<div class="block_content clearfix">
			<h5>{l s='Our hotline is available 24/7' mod='blockcontact'}</h5>
			{if $telnumber != ''}<p class="tel"><span class="label">{l s='Phone:' mod='blockcontact'}</span>{$telnumber|escape:'htmlall':'UTF-8'}</p>{/if}
			{if $email != ''}<a class="button" href="mailto:{$email|escape:'htmlall':'UTF-8'}">{l s='Contact our hotline' mod='blockcontact'}</a>{/if}
	</div>
</section>
