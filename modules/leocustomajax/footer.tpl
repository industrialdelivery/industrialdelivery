{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<script type="text/javascript">
	var leoOption = {
		productNumber:{if $leo_customajax_pn}{$leo_customajax_pn}{else}0{/if},
		productInfo:{if $leo_customajax_img}{$leo_customajax_img}{else}0{/if},
		productTran:{if $leo_customajax_tran}{$leo_customajax_tran}{else}0{/if},
		productCdown: {if $leo_customajax_count}{$leo_customajax_count}{else}0{/if},
		productColor: {if $leo_customajax_acolor}{$leo_customajax_acolor}{else}0{/if},
	}
    $(document).ready(function(){	
		var leoCustomAjax = new $.LeoCustomAjax();
        leoCustomAjax.processAjax();
    });
</script>