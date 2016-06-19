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

<!-- Block tags module -->
<section id="tags_block_left" class="block tags_block column_box">
	<h4><span>{l s='Tags' mod='blocktags'}</span><span class="column_icon_toggle"></span></h4>
        <p class="block_content toggle_content">
            {if $tags}
                {foreach from=$tags item=tag name=myLoop}
                    <a href="{$link->getPageLink('search', true, NULL, "tag={$tag.name|urlencode}")}" title="{l s='More about' mod='blocktags'} {$tag.name|escape:html:'UTF-8'}" class="{$tag.class} {if $smarty.foreach.myLoop.last}last_item{elseif $smarty.foreach.myLoop.first}first_item{else}item{/if}"><i class="icon-tags"></i>{$tag.name|escape:html:'UTF-8'}</a>
                {/foreach}
            {else}
                {l s='No tags specified yet' mod='blocktags'}
            {/if}
        </p>
</section>
<!-- /Block tags module -->
