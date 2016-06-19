{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{function name=menu level=0}
  <ul class="level{$level}">
  {foreach $data as $category}
    {if isset($category.children) && is_array($category.children)}
      <li class="cate_{$category.id_category}" ><a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'html':'UTF-8'}"><span {if {$category.id_category} == {$id_root}} style="display:none"{/if}>{$category.name}{if isset($category.image)}<span {if  {$showicons} == 0 || ({$level} gt 0 && {$showicons} == 2)} style="display:none"{/if}><img height = '20px' src='{$category["image"]}' alt='{$category["name"]}'></span>{/if}</span></a>
        {menu data=$category.children level=$level+1}</li>
    {else}
      <li class="cate_{$category.id_category}"><a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'html':'UTF-8'}">{$category.name}{if isset($category.image)}<span {if {$showicons} == 0 || ({$level} gt 0 && {$showicons} == 2)} style="display:none"{/if}><img height = '10px' src='{$category["image"]}' alt='{$category["name"]}'></span>{/if}</a></li>
    {/if}
  {/foreach}
  </ul>
{/function}

{if isset($categories)}
<div class="widget-category_image block">
	{if isset($widget_heading)&&!empty($widget_heading)}
	<h4 class="menu-title">
		{$widget_heading}
	</h4>
	{/if}
	<div class="block_content">
    {foreach from = $categories   key=key item =cate}
			{menu data=$cate}
    {/foreach}
    <div id="view_all_wapper" style="display:none">
        <span class ="view_all"><a href="javascript:void(0)">{l s='View all' mod='leobootstrapmenu'}</a></span>
    </div> 
	</div>
</div>
{/if}
<script type="text/javascript">
{literal} 
  jQuery(document).ready(function(){
    var limit = {/literal}{if $limit}{$limit}{else}5{/if}{literal};
    var level = {/literal}{if $cate_depth}{$cate_depth}{else}0{/if}{literal};
    // $("ul.level" + level + " li").remove();
    $("ul.level0").each(function() {
      $(this).find("ul.level" + level + " li").remove();
      var element = $(this).find("ul.level" + (level - 1) + " li").length;
      var count = 0;
      if(level > 0) {
        $(this).find("ul.level" + (level - 1) + " >li").each(function(){
          count = count + 1;
          if(count > limit){
            $(this).remove();
          }
        });
      }
    });
  });
{/literal}
</script>

