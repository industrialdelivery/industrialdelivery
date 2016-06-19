{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($haccordions)}
<div id="h-accordion" class="h-accordion">
    {if isset($widget_heading)&&!empty($widget_heading)}
    <h4 class="title_block">
      {$widget_heading|escape:'html':'UTF-8'}
    </h4>
    {/if}
        <ul id="haccordions{$id|escape:'html':'UTF-8'}">
            {foreach $haccordions as $key => $hac}
            <li>
              <a class="title-hac" href="#haccordion{$id|escape:'html':'UTF-8'}{$key|escape:'html':'UTF-8'}">{$hac.title|escape:'html':'UTF-8'}</a>
              {$hac.content}{* HTML form , no escape necessary *}
            </li>
            {/foreach}
        </ul>
</div>

<script type="text/javascript">
  $(document).ready(function() {
      activeItem = $("#haccordions{$id|escape:'html':'UTF-8'} li:first");
      $(activeItem).addClass('active');   
      $("#haccordions{$id|escape:'html':'UTF-8'} li").hover(function() {
          $(activeItem).css('width', '160px');
          $(this).css('width', '850px');
          activeItem = this;
      }); 
  });
</script>

{/if}





