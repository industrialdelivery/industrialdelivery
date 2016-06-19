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

<script type="text/javascript">
// <![CDATA[
var idSelectedCountry = {if isset($smarty.post.id_state)}{$smarty.post.id_state|intval}{else}{if isset($address->id_state)}{$address->id_state|intval}{else}false{/if}{/if};
var countries = new Array();
var countriesNeedIDNumber = new Array();
var countriesNeedZipCode = new Array();
{foreach from=$countries item='country'}
	{if isset($country.states) && $country.contains_states}
		countries[{$country.id_country|intval}] = new Array();
		{foreach from=$country.states item='state' name='states'}
			countries[{$country.id_country|intval}].push({ldelim}'id' : '{$state.id_state}', 'name' : '{$state.name|addslashes}'{rdelim});
		{/foreach}
	{/if}
	{if $country.need_identification_number}
		countriesNeedIDNumber.push({$country.id_country|intval});
	{/if}
	{if isset($country.need_zip_code)}
		countriesNeedZipCode[{$country.id_country|intval}] = {$country.need_zip_code};
	{/if}
{/foreach}
$(function(){ldelim}
	$('.id_state option[value={if isset($smarty.post.id_state)}{$smarty.post.id_state|intval}{else}{if isset($address->id_state)}{$address->id_state|intval}{/if}{/if}]').attr('selected', true);
{rdelim});
{literal}
	$(document).ready(function() {
		$('#company').blur(function(){
			vat_number();
		});
		vat_number();
		function vat_number()
		{
			if ($('#company').val() != '')
				$('#vat_number').show();
			else
				$('#vat_number').hide();
		}
	});
{/literal}
//]]>
</script>

{capture name=path}{l s='Your addresses'}{/capture}

 
<h1>{l s='Your addresses'}</h1>

<h3>
{if isset($id_address) && (isset($smarty.post.alias) || isset($address->alias))}
	{l s='Modify address'} 
	{if isset($smarty.post.alias)}
		"{$smarty.post.alias}"
	{else}
		{if isset($address->alias)}"{$address->alias}"{/if}
	{/if}
{else}
	{l s='To add a new address, please fill out the form below.'}
{/if}
</h3>

{include file="$tpl_dir./errors.tpl"}

<p class="required"><sup>* {l s='Required field'}</sup></p>

<form action="{$link->getPageLink('address', true)}" method="post" class="std  form-horizontal" id="add_address">
	<fieldset>
		<h3>{if isset($id_address)}{l s='Your address'}{else}{l s='New address'}{/if}</h3>
		<div class="required text dni control-group">
			<label for="dni"  class="control-label">{l s='Identification number'} <sup>*</sup></label>
			<div class="controls">
				<input type="text" class="text" name="dni" id="dni" value="{if isset($smarty.post.dni)}{$smarty.post.dni}{else}{if isset($address->dni)}{$address->dni}{/if}{/if}" />
				<span class="form_info help-inline">{l s='DNI / NIF / NIE'}</span>
			</div>
		</div>
	{assign var="stateExist" value="false"}
	{foreach from=$ordered_adr_fields item=field_name}
		{if $field_name eq 'company'}
			<div class="text control-group">
			<input type="hidden" name="token" value="{$token}" />
			<label for="company"  class="control-label">{l s='Company'}</label>
			<div class="controls">
				<input type="text" id="company" name="company" value="{if isset($smarty.post.company)}{$smarty.post.company}{else}{if isset($address->company)}{$address->company}{/if}{/if}" />
			</div>
		</div>
		{/if}
		{if $field_name eq 'vat_number'}
			<div id="vat_area">
				<div id="vat_number">
					<div class="text control-group">
						<label for="vat_number"  class="control-label">{l s='VAT number'}</label>
						<div class="controls">
							<input type="text" class="text" name="vat_number" value="{if isset($smarty.post.vat_number)}{$smarty.post.vat_number}{else}{if isset($address->vat_number)}{$address->vat_number}{/if}{/if}" />
						</div>
					</div>
				</div>
			</div>
		{/if}
		{if $field_name eq 'firstname'}
		<div class="required text control-group">
			<label for="firstname" class="control-label">{l s='First name'} <sup>*</sup></label>
			<div class="controls">
				<input type="text" name="firstname" id="firstname" value="{if isset($smarty.post.firstname)}{$smarty.post.firstname}{else}{if isset($address->firstname)}{$address->firstname}{/if}{/if}" />
			</div>
		</div>
		{/if}
		{if $field_name eq 'lastname'}
		<div class="required text control-group">
			<label for="lastname" class="control-label">{l s='Last name'} <sup>*</sup></label>
			<div class="controls">
				<input type="text" id="lastname" name="lastname" value="{if isset($smarty.post.lastname)}{$smarty.post.lastname}{else}{if isset($address->lastname)}{$address->lastname}{/if}{/if}" />
			</div>
		</div>
		{/if}
		{if $field_name eq 'address1'}
		<div class="required text control-group">
			<label for="address1" class="control-label">{l s='Address'} <sup>*</sup></label>
			<div class="controls">
				<input type="text" id="address1" name="address1" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{else}{if isset($address->address1)}{$address->address1}{/if}{/if}" />
			</div>
		</div>
		{/if}
		{if $field_name eq 'address2'}
		<div class="required text control-group">
			<label for="address2" class="control-label">{l s='Address (Line 2)'}</label>
			<div class="controls">
				<input type="text" id="address2" name="address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{else}{if isset($address->address2)}{$address->address2}{/if}{/if}" />
			</div>
		</div>
		{/if}
		{if $field_name eq 'postcode'}
		<div class="required postcode text control-group">
			<label for="postcode" class="control-label">{l s='Zip / Postal Code'} <sup>*</sup></label>
			<div class="controls">
				<input type="text" id="postcode" name="postcode" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{else}{if isset($address->postcode)}{$address->postcode}{/if}{/if}" onkeyup="$('#postcode').val($('#postcode').val().toUpperCase());" />
			</div>
		</div>
		{/if}
		{if $field_name eq 'city'}
		<div class="required text control-group">
			<label for="city" class="control-label">{l s='City'} <sup>*</sup></label>
			<div class="controls">
				<input type="text" name="city" id="city" value="{if isset($smarty.post.city)}{$smarty.post.city}{else}{if isset($address->city)}{$address->city}{/if}{/if}" maxlength="64" />
			</div>
		</div>
		{*
			if customer hasn't update his layout address, country has to be verified
			but it's deprecated
		*}
		{/if}
		{if $field_name eq 'Country:name' || $field_name eq 'country'}
		<div class="required select control-group">
			<label for="id_country" class="control-label">{l s='Country'} <sup>*</sup></label>
			<div class="controls">
				<select id="id_country" name="id_country">{$countries_list}</select>
			</div>
		</div>
		{if $vatnumber_ajax_call}
		<script type="text/javascript">
		var ajaxurl = '{$ajaxurl}';
		{literal}
				$(document).ready(function(){
					$('#id_country').change(function() {
						$.ajax({
							type: "GET",
							url: ajaxurl+"vatnumber/ajax.php?id_country="+$('#id_country').val(),
							success: function(isApplicable){
								if(isApplicable == "1")
								{
									$('#vat_area').show();
									$('#vat_number').show();
								}
								else
								{
									$('#vat_area').hide();
								}
							}
						});
					});
				});
		{/literal}
		</script>
		{/if}
		{/if}
		{if $field_name eq 'State:name'}
		{assign var="stateExist" value="true"}
		<div class="required id_state select control-group">
			<label for="id_state" class="control-label">{l s='State'} <sup>*</sup></label>
			<div class="controls">
				<select name="id_state" id="id_state">
					<option value="">-</option>
				</select>
			</div>
		</div>
		{/if}
		{/foreach}
		{if $stateExist eq "false"}
		<div class="required id_state select control-group">
			<label for="id_state" class="control-label">{l s='State'} <sup>*</sup></label>
			<div class="controls">
				<select name="id_state" id="id_state">
					<option value="">-</option>
				</select>
			</div>
		</div>
		{/if}
		<div class="textarea control-group">
			<label for="other" class="control-label">{l s='Additional information'}</label>
			<div class="controls">
				<textarea id="other" name="other" cols="26" rows="3">{if isset($smarty.post.other)}{$smarty.post.other}{else}{if isset($address->other)}{$address->other}{/if}{/if}</textarea>
			</div>
		</div>
		{if isset($one_phone_at_least) && $one_phone_at_least}
			<div class="inline-infos required control-group">
				<div class="controls">
					{l s='You must register at least one phone number.'} <sup>*</sup>
				</div>
			</div>
		{/if}
		<div class="text control-group">
			<label for="phone" class="control-label">{l s='Home phone'}</label>
			<div class="controls">
				<input type="text" id="phone" name="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{else}{if isset($address->phone)}{$address->phone}{/if}{/if}" />
			</div>
		</div>
		<div class="{if isset($one_phone_at_least) && $one_phone_at_least}required {/if}text control-group">
			<label for="phone_mobile" class="control-label">{l s='Mobile phone'}{if isset($one_phone_at_least) && $one_phone_at_least}{/if}</label>
			<div class="controls">
				<input type="text" id="phone_mobile" name="phone_mobile" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{else}{if isset($address->phone_mobile)}{$address->phone_mobile}{/if}{/if}" />
			</div>
		</div>
		<div class="required text control-group" id="adress_alias">
			<label for="alias" class="control-label">{l s='Please assign an address title for future reference.'} <sup>*</sup></label>
			<div class="controls">
				<input type="text" id="alias" name="alias" value="{if isset($smarty.post.alias)}{$smarty.post.alias}{else if isset($address->alias)}{$address->alias}{elseif !$select_address}{l s='My address'}{/if}" />
			</div>
		</div>
	</fieldset>
	<p class="submit2">
		{if isset($id_address)}<input type="hidden" name="id_address" value="{$id_address|intval}" />{/if}
		{if isset($back)}<input type="hidden" name="back" value="{$back}" />{/if}
		{if isset($mod)}<input type="hidden" name="mod" value="{$mod}" />{/if}
		{if isset($select_address)}<input type="hidden" name="select_address" value="{$select_address|intval}" />{/if}
		<input type="submit" name="submitAddress" id="submitAddress" value="{l s='Save'}" class="button btn" />
	</p>
</form>
