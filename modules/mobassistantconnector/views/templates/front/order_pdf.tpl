{*
*	This file is part of Mobile Assistant Connector.
*
*   Mobile Assistant Connector is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Mobile Assistant Connector is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Mobile Assistant Connector.  If not, see <http://www.gnu.org/licenses/>.
*
*  @author    eMagicOne <contact@emagicone.com>
*  @copyright 2014-2015 eMagicOne
*  @license   http://www.gnu.org/licenses   GNU General Public License
*}

<div style="font-size: 8pt; color: #444">

<table>
    <tr><td>&nbsp;</td></tr>
</table>

<!-- ADDRESSES -->
<table style="width: 100%">
    <tr>
        <td style="width: 17%"></td>
        <td style="width: 83%">
            {if !empty($delivery_address)}
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50%">
                            <span style="font-weight: bold; font-size: 10pt; color: #9E9F9E">{l s='Delivery Address' mod='mobassistantconnector' pdf='true'}</span><br />
                            {$delivery_address}
                        </td>
                        <td style="width: 50%">
                            <span style="font-weight: bold; font-size: 10pt; color: #9E9F9E">{l s='Billing Address' mod='mobassistantconnector' pdf='true'}</span><br />
                            {$invoice_address}
                        </td>
                    </tr>
                </table>
            {else}
                <table style="width: 100%">
                    <tr>

                        <td style="width: 50%">
                            <span style="font-weight: bold; font-size: 10pt; color: #9E9F9E">{l s='Billing & Delivery Address.' mod='mobassistantconnector' pdf='true'}</span><br />
                            {$invoice_address}
                        </td>
                        <td style="width: 50%">

                        </td>
                    </tr>
                </table>
            {/if}
        </td>
    </tr>
</table>
<!-- / ADDRESSES -->

<div style="line-height: 1pt">&nbsp;</div>

<!-- PRODUCTS TAB -->
<table style="width: 100%">
<tr>
<td style="width: 17%; padding-right: 7px; text-align: right; vertical-align: top; font-size: 7pt;">
    <!-- CUSTOMER INFORMATION -->
    <b>{l s='Order Number:' mod='mobassistantconnector' pdf='true'}</b><br />
    {$order->getUniqReference()}<br />
    <br />
    <b>{l s='Order Date:' mod='mobassistantconnector' pdf='true'}</b><br />
    {dateFormat date=$order->date_add full=0}<br />
    <br />
    <b>{l s='Payment Method:' mod='mobassistantconnector' pdf='true'}</b><br />
    {$order->payment}
    <br /><br />
    {if isset($carrier)}
        <b>{l s='Carrier:' mod='mobassistantconnector' pdf='true'}</b><br />
        {$carrier->name}<br />
        <br />
    {/if}
    <!-- / CUSTOMER INFORMATION -->
</td>
<td style="width: 83%; text-align: right">
    <table style="width: 100%; font-size: 8pt;">
        <tr style="line-height:4px;">
            <td style="text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 10px; font-weight: bold; width: 50%">{l s='Product / Reference' mod='mobassistantconnector' pdf='true'}</td>
            <!-- unit price tax excluded is mandatory -->
            <td style="background-color: #4D4D4D; color: #FFF; text-align: right; font-weight: bold; width: 15%">
                {l s='Unit Price' mod='mobassistantconnector' pdf='true'}
                {if $tax_excluded_display}
                    {l s='(Tax Excl.)' mod='mobassistantconnector' pdf='true'}
                {else}
                    {l s='(Tax Incl.)' mod='mobassistantconnector' pdf='true'}
                {/if}
            </td>
            <td style="background-color: #4D4D4D; color: #FFF; text-align: center; font-weight: bold; width: 10%">{l s='Qty' mod='mobassistantconnector' pdf='true'}</td>
            <td style="background-color: #4D4D4D; color: #FFF; text-align: right; font-weight: bold; width: {if !$tax_excluded_display}15%{else}25%{/if}">
                {l s='Total' mod='mobassistantconnector' pdf='true'}
                {if $tax_excluded_display}
                    {l s='(Tax Excl.)' mod='mobassistantconnector' pdf='true'}
                {else}
                    {l s='(Tax Incl.)' mod='mobassistantconnector' pdf='true'}
                {/if}
            </td>
        </tr>
        <!-- PRODUCTS -->
        {foreach $order_details as $order_detail}
            {cycle values='#FFF,#DDD' assign=bgcolor}
            <tr style="line-height:6px;background-color:{$bgcolor};">
                <td style="text-align: left; width: 50%">{$order_detail.product_name}{if isset($order_detail.product_reference) && !empty($order_detail.product_reference)} ({l s='Reference:' mod='mobassistantconnector' pdf='true'} {$order_detail.product_reference}){/if}</td>
                <!-- unit price tax excluded is mandatory -->
                <td style="text-align: right; width: 15%; white-space: nowrap;">
                    {if $tax_excluded_display}
                        {displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_excl}
                    {else}
                        {displayPrice currency=$order->id_currency price=$order_detail.unit_price_tax_incl}
                    {/if}
                </td>
                <td style="text-align: center; width: 10%">{$order_detail.product_quantity}</td>
                <td style="text-align: right;  width: {if !$tax_excluded_display}15%{else}25%{/if}; white-space: nowrap;">
                    {if $tax_excluded_display}
                        {displayPrice currency=$order->id_currency price=$order_detail.total_price_tax_excl}
                    {else}
                        {displayPrice currency=$order->id_currency price=$order_detail.total_price_tax_incl}
                    {/if}
                </td>
            </tr>
            {foreach $order_detail.customizedDatas as $customizationPerAddress}
                {foreach $customizationPerAddress as $customizationId => $customization}
                    <tr style="line-height:6px;background-color:{$bgcolor};">
                        <td style="line-height:3px; text-align: left; width: 45%; vertical-align: top">
                            <blockquote>
                                {if isset($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) && count($customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_]) > 0}
                                    {foreach $customization.datas[$smarty.const._CUSTOMIZE_TEXTFIELD_] as $customization_infos}
                                        {$customization_infos.name}: {$customization_infos.value}
                                        {if !$smarty.foreach.custo_foreach.last}<br />
                                        {else}
                                            <div style="line-height:0.4pt">&nbsp;</div>
                                        {/if}
                                    {/foreach}
                                {/if}

                                {if isset($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) && count($customization.datas[$smarty.const._CUSTOMIZE_FILE_]) > 0}
                                    {count($customization.datas[$smarty.const._CUSTOMIZE_FILE_])} {l s='image(s)' mod='mobassistantconnector' pdf='true'}
                                {/if}
                            </blockquote>
                        </td>
                        {if !$tax_excluded_display}
                            <td style="text-align: right;"></td>
                        {/if}
                        <td style="text-align: right; width: 10%"></td>
                        <td style="text-align: center; width: 10%; vertical-align: top">({$customization.quantity})</td>
                        <td style="width: 15%; text-align: right;"></td>
                    </tr>
                {/foreach}
            {/foreach}
        {/foreach}
        <!-- END PRODUCTS -->
    </table>

    <table style="width: 100%">
        {if (($order->total_paid_tax_incl - $order->total_paid_tax_excl) > 0)}
            <tr style="line-height:5px;">
                <td style="width: 83%; text-align: right; font-weight: bold">{l s='Product Total (Tax Excl.)' mod='mobassistantconnector' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">{displayPrice currency=$order->id_currency price=$order->total_products}</td>
            </tr>

            <tr style="line-height:5px;">
                <td style="width: 83%; text-align: right; font-weight: bold">{l s='Product Total (Tax Incl.)' mod='mobassistantconnector' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">{displayPrice currency=$order->id_currency price=$order->total_products_wt}</td>
            </tr>
        {else}
            <tr style="line-height:5px;">
                <td style="width: 83%; text-align: right; font-weight: bold">{l s='Product Total' mod='mobassistantconnector' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">{displayPrice currency=$order->id_currency price=$order->total_products}</td>
            </tr>
        {/if}

        {if $order->total_discounts_tax_incl > 0}
            <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold">{l s='Total Vouchers' mod='mobassistantconnector' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">-{displayPrice currency=$order->id_currency price=($order->total_discounts_tax_incl)}</td>
            </tr>
        {/if}

        {if $order->total_wrapping_tax_incl > 0}
            <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold">{l s='Wrapping Cost' mod='mobassistantconnector' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">
                    {if $tax_excluded_display}
                        {displayPrice currency=$order->id_currency price=$order->total_wrapping_tax_excl}
                    {else}
                        {displayPrice currency=$order->id_currency price=$order->total_wrapping_tax_incl}
                    {/if}
                </td>
            </tr>
        {/if}

        {if $order->total_shipping_tax_incl > 0}
            <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold">{l s='Shipping Cost' mod='mobassistantconnector' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">
                    {if $tax_excluded_display}
                        {displayPrice currency=$order->id_currency price=$order->total_shipping_tax_excl}
                    {else}
                        {displayPrice currency=$order->id_currency price=$order->total_shipping_tax_incl}
                    {/if}
                </td>
            </tr>
        {/if}

        {if ($order->total_paid_tax_incl - $order->total_paid_tax_excl) > 0}
            <tr style="line-height:5px;">
                <td style="text-align: right; font-weight: bold">{l s='Taxes' mod='mobassistantconnector' pdf='true'}</td>
                <td style="width: 17%; text-align: right;">{displayPrice currency=$order->id_currency price=($order->total_paid_tax_incl - $order->total_paid_tax_excl)}</td>
            </tr>
        {/if}

        <tr style="line-height:5px;">
            <td style="text-align: right; font-weight: bold">{l s='Total' mod='mobassistantconnector' pdf='true'}</td>
            <td style="width: 17%; text-align: right;">{displayPrice currency=$order->id_currency price=$order->total_paid_tax_incl}</td>
        </tr>

    </table>

</td>
</tr>
</table>
<!-- / PRODUCTS TAB -->

<div style="line-height: 1pt">&nbsp;</div>
</div>
