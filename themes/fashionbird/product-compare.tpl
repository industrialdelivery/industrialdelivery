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

{if $comparator_max_item}
<script type="text/javascript">
// <![CDATA[
	var min_item = '{l s='Please select at least one product' js=1}';
	var max_item = '<div id="myModal" class="modal hide fade notification notification_warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>{l s='You cannot add more than %d product(s) to the product comparison' sprintf=$comparator_max_item js=1}</div>';

//]]>
</script>

	<form class="form_compare" method="post" action="{$link->getPageLink('products-comparison')}" onsubmit="true">
		<p>
		<input type="submit" id="bt_compare" class="button" value="{l s='Compare'}" />
		<input type="hidden" name="compare_product_list" class="compare_product_list" value="" />
		</p>
	</form>
{/if}

