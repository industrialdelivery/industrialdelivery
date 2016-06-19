<script type="text/javascript">
$(document).ready(function () {
	var width = $(window).width(); 
		
	$('#permanentlinks').each(function(){
		$(this).find('a.leo-mobile').click(function(){
		 $('#').slideToggle('slow');

		});
	  });
	
  $(window).resize(function(){
		var width = $(window).width();
		if(width >= 600){	
			$("#form-permanentlinks").css("display","block");
			$(".leo-button").css("display","none");
		}
		else{
			$("#form-permanentlinks").css("display","none");
			$(".leo-button").css("display","block");
			
		}
	});	
	

});
</script>

<!-- Block permanent links module HEADER -->
<div id="permanentlinks">
	<div class="leo-button"><a class="leo-mobile">{l s='Information'  mod='blockpermanentlinks'}</a></div>
	<div id="form-permanentlinks">
		<div class="nav-item">
			<div class="item-top"> 
				{if $logged}
					<a href="{$link->getPageLink('index', true, NULL, "mylogout")}" title="{l s='Log me out' mod='blockpermanentlinks'}" class="logout" rel="nofollow">{l s='Log out' mod='blockpermanentlinks'}</a>
				{else}
					<a href="{$link->getPageLink('my-account', true)}" title="{l s='Login to your customer account' mod='blockpermanentlinks'}" class="login" rel="nofollow">{l s='Log in' mod='blockpermanentlinks'}</a>
				{/if}
			</div>
		</div>
		<div class="nav-item">
			<div class="item-top">
				<a href="{$link->getPageLink('my-account')}" title="{l s='My Account' mod='blockpermanentlinks'}">{l s='My Account' mod='blockpermanentlinks'}</a>
			</div>
		</div>
		<div class="nav-item" id="wishlist_block">
			<div class="item-top">
				<a href="{$link->getModuleLink('blockwishlist', 'mywishlist')}" title="{l s='My wishlists' mod='blockpermanentlinks'}">{l s='My Wishlist' mod='blockpermanentlinks'}</a>
			</div>
		</div>
	</div>
</div>
<!-- /Block permanent links module HEADER -->