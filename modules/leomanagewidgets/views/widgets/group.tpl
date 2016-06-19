{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($leoGroup) && $leoGroup}
    {foreach from=$leoGroup item=group}
        <div class="{if isset($group.class) && $group.class}{$group.class|escape:'html':'UTF-8'}{/if}" 
            {if isset($LEO_BG_STYLE_DATA[$group.id]['background_image_html']) && $LEO_BG_STYLE_DATA[$group.id]['background_image_html'] != ''}
                {$LEO_BG_STYLE_DATA[$group.id]['background_image_html']}{* HTML form , no escape necessary *}
            {/if}
        >
            {if isset($LEO_BG_STYLE_DATA[$group.id]['background_video_html']) && $LEO_BG_STYLE_DATA[$group.id]['background_video_html'] != ''}
                {$LEO_BG_STYLE_DATA[$group.id]['background_video_html']}{* HTML form , no escape necessary *}
            {/if}

            {if isset($group.title) && $group.title}
                <h4 class="title_block">{$group.title}{* HTML form , no escape necessary *}</h4>
            {/if}
            {if isset($group.columns) && $group.columns}
                {foreach from=$group.columns item=column}
                    {if $column.active}
                        <div class="widget{$column.col_value|escape:'html':'UTF-8'}{if isset($column.class) && $column.class} {$column.class|escape:'html':'UTF-8'}{/if}"
                            {if isset($column.background) && $column.background}style="background-color: {$column.background|escape:'html':'UTF-8'}"{/if}>
                            {if isset($column.rows)}
                                {foreach from=$column.rows item=row}
                                    {if isset($row.content)}{$row.content}{* HTML form , no escape necessary *}{/if}
                                {/foreach}
                            {/if}
                        </div>
                    {/if}
                {/foreach}
            {/if}
        </div>
    {/foreach}
{/if}