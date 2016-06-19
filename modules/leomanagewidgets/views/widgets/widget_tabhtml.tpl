{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if isset($tabhtmls)}
<div id="tabhtml{$id|escape:'html':'UTF-8'}" class="widget-tab block">
    {if isset($widget_heading)&&!empty($widget_heading)}
    <h4 class="title_block">
      {$widget_heading|escape:'html':'UTF-8'}
    </h4>
    {/if}
    <div class="block_content"> 
        <div id="tabhtmls{$id|escape:'html':'UTF-8'}" class="panel-group">
            <ul class="nav nav-tabs">
              {foreach $tabhtmls as $key => $ac}  
              <li class="{if $key==0}active{/if}"><a href="#tabhtml{$id|escape:'html':'UTF-8'}{$key|escape:'html':'UTF-8'}" >{$ac.title|escape:'html':'UTF-8'}</a></li>
              {/foreach}
            </ul>

            <div class="tab-content">
                {foreach $tabhtmls as $key => $ac}
                  <div class="tab-pane{if $key==0} active{/if} " id="tabhtml{$id|escape:'html':'UTF-8'}{$key|escape:'html':'UTF-8'}">{$ac.content}{* HTML form , no escape necessary *}</div>
                {/foreach}
            </div>

    </div></div>
</div>
<script type="text/javascript">
$(document).ready(function() {
    $('#tabhtml{$id|escape:'html':'UTF-8'} .nav a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    })
});
</script>
{/if}





