{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<!-- Block Newsletter module-->
<div id="newsletter_block_footer" class="leo-newsletter">
	<h4 class="menu-title">{l s='Newsletter' mod='leobootstrapmenu'}</h4>
	<div class="block_content">
 
		<form action="{$link->getPageLink('index')|escape:'html'}" method="post">
             {if $information}
             <div class="newsletter-info">{$information}</div>
             {/if}
             <div class="alert alert-danger hide">{l s='Newsletter: Invalid email address' mod='leobootstrapmenu'}</div>
		      <div class="input-group">
				<input   class="form-control"  id="newsletter-input-footer" type="text" name="email"  value="{if isset($value) && $value}{$value}{else}{l s='your e-mail' mod='leobootstrapmenu'}{/if}" />
				<input type="hidden" name="action" value="0" />
                <span class="input-group-btn">                
                     <button type="submit" class="btn btn-default" name="submitNewsletter" >{l s='Go!' mod='leobootstrapmenu'}</button>              
                </span>

			</div>
		</form>
	</div>
</div>
<!-- /Block Newsletter module-->
 


<script type="text/javascript">
    var placeholder = "{l s='your e-mail' mod='leobootstrapmenu' js=1}";
    {literal}
        $(document).ready(function() {
            $('#newsletter-input-footer').on({
                focus: function() {
                    if ($(this).val() == placeholder) {
                        $(this).val('');
                    }
                },
                blur: function() {
                    if ($(this).val() == '') {
                        $(this).val(placeholder);
                    }
                }
            });

            $("#newsletter_block_footer form").submit( function(){  
                if ( $('#newsletter-input-footer').val() == placeholder) {
                    $("#newsletter_block_footer .alert").removeClass("hide");
                    return false;
                }else {
                     $("#newsletter_block_footer .alert").addClass("hide");
                     return true;
                }
            } );
        });

    {/literal}
</script>