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
*  @version  Release: $Revision$
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<script text="javascript">
{literal}
$('document').ready(function(){


	$('#sendEmail').click(function(){
		var datas = [];
		$('.send_friend_form_content').find('input').each(function(index){
			var o = {};
			o.key = $(this).attr('name');
			o.value = $(this).val();
			if (o.value != '')
				datas.push(o);
		});
		if (datas.length >= 3)
		{
			$.ajax({
				{/literal}url: "{$module_dir}sendtoafriend_ajax.php",{literal}
				post: "POST",
				data: {action: 'sendToMyFriend', secure_key: '{/literal}{$stf_secure_key}{literal}', friend: JSON.stringify(datas)},{/literal}{literal}
				dataType: "json",
					success: function(result){
					$('#send_friend_form').modal('hide');
				}
			});
		}
		else
			$('#send_friend_form_error').text("{/literal}{l s='You did not fill required fields' mod='sendtoafriend' js=1}{literal}");
	});
});
{/literal}
</script>
<li class="sendtofriend">
	<a  class="btn-send-friend" href="#send_friend_form"  role="button"  data-toggle="modal" ><i class="icon-envelope"></i>{l s='Send to a friend' mod='sendtoafriend'}</a>
</li>

	<div id="send_friend_form" class="modal hide fade" tabindex="-1" data-width="760">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h1 id="myModalLabel"><span>{l s='Send to a friend' mod='sendtoafriend'}</span></h1>
            </div>
            
             <div class="modal-body">
              <div class="row-fluid ">
<div class="span6 titled_box">
		
<h2><span>{$stf_product->name}</span></h2>
				<img src="{$link->getImageLink($stf_product->link_rewrite, $stf_product_cover, 'small_default')}"  alt="{$stf_product->name|escape:html:'UTF-8'}" />
				<div class="product_desc">
					
					<span class="send-desc">{$stf_product->description_short}</span>
				</div>

			</div>
			<div class="send_friend_form_content span6">
<div id="send_friend_form_error" ></div>
				<div class="form_container titled_box">
					<h2><span>{l s='Recipient' mod='sendtoafriend'} :</span></h2>
					<p class="text">
						<label for="friend_name">{l s='Name of your friend' mod='sendtoafriend'} <sup class="required">*</sup> :</label>
						<input id="friend_name" name="friend_name" type="text" value=""/>
					</p>
					<p class="text">
						<label for="friend_email">{l s='E-mail address of your friend' mod='sendtoafriend'} <sup class="required">*</sup> :</label>
						<input id="friend_email" name="friend_email" type="text" value=""/>
					</p>
					<p class="txt_required"><sup class="required">*</sup> {l s='Required fields' mod='sendtoafriend'}</p>
				</div>

			</div>
	</div></div>
    
    
 
		<div class="modal-footer">
					<input id="id_product_comment_send" name="id_product" type="hidden" value="{$stf_product->id}" />
				   <button class="btn btn-inverse" data-dismiss="modal" aria-hidden="true">Close</button>
					<input id="sendEmail" class="btn btn-inverse" name="sendEmail" type="submit" value="{l s='Send' mod='sendtoafriend'}" />
	</div>
</div>