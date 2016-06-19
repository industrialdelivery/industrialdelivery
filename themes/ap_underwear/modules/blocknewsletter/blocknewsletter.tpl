{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<!-- Block Newsletter module-->

<div id="newsletter_block_left" class="block inline">    
    <h4 class="title_block">{l s='Signup Newsletter ' mod='blocknewsletter'}</h4>
    <div class="block_content">
      
        <form action="{$link->getPageLink('index')|escape:'html'}" method="post" class="form-inline">
            <div class="form-group">
                <p class="form-control-static hidden-md hidden-sm hidden-xs">{l s='Stay-up-date with our lastest news. Signup now!' mod='blocknewsletter'}</p>
            </div>
            <div class="form-group form-newsletter">
                <div class="input-group">
                    <span class="input-group-addon hidden-xs"><i class="fa fa-paper-plane"></i></span>
                    <input class="inputNew newsletter-input form-control" id="newsletter-input" type="text" name="email" size="18" value="{if isset($value) && $value}{$value}{else}{l s='Enter your e-mail' mod='blocknewsletter'}{/if}" />
                    <span class="input-group-btn"><input type="submit" value="{l s='subscribe' mod='blocknewsletter'}" class="btn btn-outline" name="submitNewsletter" /></span>
                    <input type="hidden" name="action" value="0" />
                 </div>   
            </div>
        </form>
        {if isset($msg) && $msg}
            <p class="{if $nw_error}warning_inline{else}success_inline{/if}">{$msg}</p>
        {/if}
    </div>
    {hook h="displayBlockNewsletterBottom" from='blocknewsletter'}
</div>


<!-- /Block Newsletter module-->
{strip}
{if isset($msg) && $msg}
{addJsDef msg_newsl=$msg|@addcslashes:'\''}
{/if}
{if isset($nw_error)}
{addJsDef nw_error=$nw_error}
{/if}
{addJsDefL name=placeholder_blocknewsletter}{l s='Enter your e-mail' mod='blocknewsletter' js=1}{/addJsDefL}
{if isset($msg) && $msg}
    {addJsDefL name=alert_blocknewsletter}{l s='Newsletter : %1$s' sprintf=$msg js=1 mod="blocknewsletter"}{/addJsDefL}
{/if}
{/strip}