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
<div class="typos bannercontainer">
    <div class="note"> 
            {l s='NOTE' mod='leosliderlayer'}: <p>{l s='These Below Typos are getting in the file' mod='leosliderlayer'}:<a href="{$typoDir}" target="_blank">{$typoDir}</a>
            <br>{l s='you can open this file and add yours css style and it will be listed in here!!!' mod='leosliderlayer'}</p>
            <p>{l s='To Select One, You Click The Text Of Each Typo' mod='leosliderlayer'}</p>
    </div>

    <div class="typos-wrap">	
        {foreach $captions as $caption}
            <div class="typo {if $caption=='cus_color'}typo-big{/if}"><div class="tp-caption {$caption}" data-class="{$caption}">{l s='Use This Caption Typo' mod='leosliderlayer'}</div></div>
        {/foreach}
     </div>
</div>  
<script type="text/javascript">
$('div.typo').live('click', function() {  
        if(parent.$('#{$field}').val())
            parent.$('#{$field}').val(parent.$('#{$field}').val()+" "+$("div", this).attr("data-class") );
        else
            parent.$('#{$field}').val($("div", this).attr("data-class") );
        parent.$('#dialog').dialog('close');
        parent.$('#dialog').remove();	
});
</script>