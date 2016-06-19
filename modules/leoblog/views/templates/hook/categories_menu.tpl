{*
 *  Leo Prestashop SliderShow for Prestashop 1.6.x
 *
 * @package   leosliderlayer
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<!-- Block categories module -->
    {if $tree}
    <div id="categories_blog_menu" class="block blog-menu">
      <h4 class="title_block">{if isset($currentCategory)}{$currentCategory->title|escape:'html':'UTF-8'}{else}{l s='Blog Categories' mod='leoblog'}{/if}</h4>
        <div class="block_content">
            {$tree}{* HTML form , no escape necessary *}
        </div>
    </div>
    {/if}
    <!-- /Block categories module -->
