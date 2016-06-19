{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<div class="row group-row" data-original-title="{l s='You can drag this group to other hook' mod='leomanagewidgets'}"{if isset($item_group)}id="group_{$item_group.id|escape:'html':'UTF-8'}"{/if}>
    {*add + edit + insert column for group*}
    <div class="group-panel col-lg-12">
        <div class="pull-left">
            <a title="{l s='Click here to change group status' mod='leomanagewidgets'}" class="leo-group-status label-tooltip leo-tool" data-value="{$item_group.active|escape:'html':'UTF-8'}">
                <span class="status-enable"{if $item_group.active != 1} style="display:none;"{/if}>{l s='Enable' mod='leomanagewidgets'}</span><span class="status-disable"{if $item_group.active == 1} style="display:none;"{/if}>{l s='Disable' mod='leomanagewidgets'}</span>
            </a>
            <a href="javascript:void(0);" class="leo-group-btn leo-edit-group label-tooltip leo-tool" data-original-title="{l s='Click here to Edit group' mod='leomanagewidgets'}">
                 <span class="status-edit">{l s='Edit' mod='leomanagewidgets'}</span>
            </a>
            <a style="color:#D9534F" class="leo-group-btn leo-remove-group label-tooltip leo-tool" data-confirm="{l s='Are you sure you want to delete this group?' mod='leomanagewidgets'}" data-original-title="{l s='Click here to delete group' mod='leomanagewidgets'}" href="javascript:void(0)">
                <span class="status-delete">{l s='Delete' mod='leomanagewidgets'}</span>
            </a>
        </div>
        <div class="pull-right">
            <button type="button" class="btn btn-default leobtn-width dropdown-toggle btn-add-group btn-add-col" tabindex="-1" data-toggle="dropdown">
                {l s='Insert A Column' mod='leomanagewidgets'} <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                {foreach from=$leo_width item=itemWidth}
                <li>
                    <a class="leo-add-column" data-width="{$itemWidth|intval}" href="javascript:void(0);" tabindex="-1">                                          
                        <span class="leo-width-val leo-w-{if $itemWidth|strpos:"."}{$itemWidth|replace:'.':'-'|escape:'html':'UTF-8'}{else}{$itemWidth|intval}{/if}">{$itemWidth|escape:'html':'UTF-8'}/12 - ( {math equation="x/y*100" x=$itemWidth y=12 format="%.2f"} % )</span>
                    </a>
                </li>
                {/foreach}
            </ul>
        </div>
    </div>
    <div class="column-list col-lg-12">
        {if isset($item_group) && isset($item_group.columns)}
            {foreach $item_group.columns item="itemColumn"}
                <div id="column_{$itemColumn.id|escape:'html':'UTF-8'}" class="column-row{if isset($itemColumn.col_value)}{$itemColumn.col_value|escape:'html':'UTF-8'}{/if}">
                    <div class="leo-column">
                        <div class="leo-column-action pull-left">
                            <a title="{l s='Click here to change column status' mod='leomanagewidgets'}" class="leo-column-status" data-value="{$itemColumn.active|escape:'html':'UTF-8'}">
                                <span class="status-enable"{if $itemColumn.active != 1} style="display:none;"{/if}>&nbsp;</span><span class="status-disable"{if $itemColumn.active == 1} style="display:none;"{/if}>&nbsp;</span>
                            </a>
                            <a class="leo-edit-column"><span class="status-edit">&nbsp;</span></a>
                            <a style="color:#fff" class="leo-delete-column" data-for="delete" data-confirm="{l s='Are you sure you want to delete this column?' mod='leomanagewidgets'}" href="javascript:void(0)">
                            <span class="status-delete">&nbsp;</span></a>
                        </div>
                        <div class="leo-action-top pull-right">
                            <button type="button" class="leo-cog dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                <span class="width-val"></span><span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right">
                                {foreach from=$leo_width item=itemWidth}
                                <li>
                                    <a class="leo-change-width" data-width="{$itemWidth|intval}" href="javascript:void(0);" tabindex="-1">                                          
                                        <span class="leo-width-val leo-w-{if $itemWidth|strpos:"."}{$itemWidth|replace:'.':'-'|escape:'html':'UTF-8'}{else}{$itemWidth|intval}{/if}">{$itemWidth|intval}/12 - ( {math equation="x/y*100" x=$itemWidth y=12 format="%.2f"} % )</span>
                                    </a>
                                </li>
                                {/foreach}
                            </ul>
                            <div class="action-sign">
                                <a class="width-action plus-sign" href="#" data-action="1"></a>
                                <a class="width-action minus-sign" href="#" data-action="-1"></a>
                            </div>
                        </div>
                        <div class="leo-column-row clear">
						{if isset($itemColumn.rows)}
							{foreach $itemColumn.rows item = "itemRow"}
                            <div class="leo-column_title {if $itemRow.type eq '0'}widget{else}module{/if}" id="row_{$itemRow.id|escape:'html':'UTF-8'}">
                            <a title="{l s='Click here to change row status' mod='leomanagewidgets'}" class="leo-row-status" data-value="{$itemRow.active|escape:'html':'UTF-8'}">
                                <span class="status-enable" {if $itemRow.active != 1} style="display:none;"{/if}>&nbsp;</span><span class="status-disable"{if $itemRow.active == 1} style="display:none;"{/if}>&nbsp;</span>
                            </a>
                            <a style="color:#D9534F" class="leo-delete-row" data-confirm="{l s='Are you sure you want to delete this column?' mod='leomanagewidgets'}" data-for="delete" href="javascript:void(0)"><span class="status-delete">&nbsp;</span></a>
                            <a class="leo-edit-row" href="javascript:void(0)"><span class="status-edit">&nbsp;</span></a>
							<a class="leo-edit-widget" data-for="widget" href="javascript:void(0);">{if $itemRow.type eq '0'}{$itemRow.name|escape:'html':'UTF-8'}{else}{$itemRow.module_name|escape:'html':'UTF-8'}{/if}</a>
        						</div>                        
							{/foreach}
						{/if}
                        </div>
                        <div class="leo-column_btn"><a class="btn-add-row btn-add-widget" title="{l s='Click here to add a new row' mod='leomanagewidgets'}" href="javascript:void(0)" data-action="1">
                        {l s='Add widgets' mod='leomanagewidgets'}</a></div>
                    </div>
                </div>
            {/foreach}
        {/if}
    </div>
</div>