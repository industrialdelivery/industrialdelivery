{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<div id="idTab68">
{if $leoGroup}
    {foreach from=$leoGroup item=groups key=typeGroup}
        {if $groups}
            {foreach from=$groups item=group}
                {if $typeGroup==1}
<div class="row {$group.class|escape:'html':'UTF-8'}" {if isset($group.background) && $group.background}style="background-color: {$group.background|escape:'html':'UTF-8'}"{/if}>
                    {if isset($group.title) && $group.title}
				<h4 class="title_block">{$group.title|escape:'html':'UTF-8'}</h4>
                    {/if}
                {/if}
                {if isset($group.columns) && $group.columns}
                    {foreach from=$group.columns item=column}
                        {if $column.active}
    <div class="widget{$column.col_value|escape:'html':'UTF-8'}{if $column.class} {$column.class|escape:'html':'UTF-8'}{/if}" {if isset($column.background) && $column.background}style="background-color: {$column.background|escape:'html':'UTF-8'}"{/if}>
        {if isset($column.content)}{$column.content}{* HTML form , no escape necessary *}{/if}
    </div>
                        {/if}
                    {/foreach}
                {/if}
                {if $typeGroup==1}
</div>
                {/if}
            {/foreach}
        {/if}
    {/foreach}
{/if}
</div>