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

{capture name=path}<a href="{$link->getPageLink('my-account', true)}">{l s='My account'}</a><span class="navigation-pipe">{$navigationPipe}</span>{l s='Your personal information'}{/capture}

<div class="block_box_center">
<h1>{l s='Your personal information'}</h1>

{include file="$tpl_dir./errors.tpl"}

{if isset($confirmation) && $confirmation}
	<p class="success">
		{l s='Your personal information has been successfully updated.'}
		{if isset($pwd_changed)}<br />{l s='Your password has been sent to your email:'} {$email}{/if}
	</p>
{else}
	<h3>{l s='Please be sure to update your personal information if it has changed.'}</h3>
	<p class="required"><sup>* {l s='Required field'}</sup></p>
	<form action="{$link->getPageLink('identity', true)}" method="post" class="std form-horizontal">
		<fieldset>
			<div class="radio control-group">
				<label class="control-label">{l s='Title'}</label>
				<div class="controls">
				{foreach from=$genders key=k item=gender}
					<input type="radio" name="id_gender" id="id_gender{$gender->id}" value="{$gender->id|intval}" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == $gender->id}checked="checked"{/if} />
					<label for="id_gender{$gender->id}" class="top help-inline">{$gender->name}</label>
				{/foreach}
				</div>
			</div>
			<div class="required text control-group">
				<label for="firstname" class="control-label">{l s='First name'} <sup>*</sup></label>
				<div class="controls">
					<input type="text" id="firstname" name="firstname" value="{$smarty.post.firstname}" />
				</div>
			</div>
			<div class="required text control-group">
				<label for="lastname" class="control-label">{l s='Last name'} <sup>*</sup></label>
				<div class="controls">
					<input type="text" name="lastname" id="lastname" value="{$smarty.post.lastname}" />
				</div>
			</div>
			<div class="required text control-group">
				<label for="email" class="control-label">{l s='Email'} <sup>*</sup></label>
				<div class="controls">
					<input type="text" name="email" id="email" value="{$smarty.post.email}" />
				</div>
			</div>
			<div class="required text control-group">
				<label for="old_passwd" class="control-label">{l s='Current Password'} <sup>*</sup></label>
				<div class="controls">
					<input type="password" name="old_passwd" id="old_passwd" />
				</div>
			</div>
			<div class="password control-group"> 
				<label for="passwd" class="control-label">{l s='New Password'}</label>
				<div class="controls">
					<input type="password" name="passwd" id="passwd" />
				</div>
			</div>
			<div class="password control-group">
				<label for="confirmation" class="control-label">{l s='Confirmation'}</label>
				<div class="controls">
					<input type="password" name="confirmation" id="confirmation" />
				</div>
			</div>
			<div class="select control-group">
				<label class="control-label">{l s='Date of Birth'}</label>
				<div class="controls">
					<select name="days" id="days" class="span2">
						<option value="">-</option>
						{foreach from=$days item=v}
							<option value="{$v}" {if ($sl_day == $v)}selected="selected"{/if}>{$v}&nbsp;&nbsp;</option>
						{/foreach}
					</select>
					{*
						{l s='January'}
						{l s='February'}
						{l s='March'}
						{l s='April'}
						{l s='May'}
						{l s='June'}
						{l s='July'}
						{l s='August'}
						{l s='September'}
						{l s='October'}
						{l s='November'}
						{l s='December'}
					*}
					<select id="months" name="months" class="span2">
						<option value="">-</option>
						{foreach from=$months key=k item=v}
							<option value="{$k}" {if ($sl_month == $k)}selected="selected"{/if}>{l s=$v}&nbsp;</option>
						{/foreach}
					</select>
					<select id="years" name="years" class="span2">
						<option value="">-</option>
						{foreach from=$years item=v}
							<option value="{$v}" {if ($sl_year == $v)}selected="selected"{/if}>{$v}&nbsp;&nbsp;</option>
						{/foreach}
					</select>
				</div>
			</div>
			{if $newsletter}
			<div class="checkbox control-group">
				<div class="controls">
					<input type="checkbox" id="newsletter" name="newsletter" value="1" {if isset($smarty.post.newsletter) && $smarty.post.newsletter == 1} checked="checked"{/if} />
					<label class="inline-help" for="newsletter">{l s='Sign up for our newsletter!'}</label>
				</div>
			</div>
			<div class="checkbox control-group">
				<div class="controls">
					<input type="checkbox" name="optin" id="optin" value="1" {if isset($smarty.post.optin) && $smarty.post.optin == 1} checked="checked"{/if} />
					<label class="inline-help" for="optin">{l s='Receive special offers from our partners!'}</label>
				</div>
			</div>
			{/if}
			<div class="submit control-group">
				<div class="controls">
					<input type="submit" class="button" name="submitIdentity" value="{l s='Save'}" />
				</div>
			</div>
			<div id="security_informations">
				<div class="controls">
					{l s='[Insert customer data privacy clause here, if applicable]'}
				</div>
			</div>
		</fieldset>
	</form>
{/if}

<ul class="footer_links">
	<li><a href="{$link->getPageLink('my-account', true)}"><img src="{$img_dir}icon/my-account.gif" alt="" class="icon" /></a><a href="{$link->getPageLink('my-account', true)}">{l s='Back to your account'}</a></li>
	<li class="f_right"><a href="{$base_dir}"><img src="{$img_dir}icon/home.gif" alt="" class="icon" /> {l s='Home'}</a></li>
</ul>
</div>