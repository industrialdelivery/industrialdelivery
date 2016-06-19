{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<div id="google-maps" class="block">
{if isset($widget_heading)&&!empty($widget_heading)}
<h4 class="title_block">
	{$widget_heading|escape:'html':'UTF-8'}
</h4>
{/if}
{if $page_name !='stores'}
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=true&amp;amp;region={$iso_code|escape:'html':'UTF-8'}">var add_code_to_enable_cache_not_show_error_4435345345klcerkldfmkl=""</script>
<div id="map-canvas" style="width:{$width|intval}; height:{$height|intval};"></div>
<script type="text/javascript">
$(document).ready(function(){
{literal} 
	var latitude = {/literal}{$latitude|escape:'html':'UTF-8'}{literal};
	var longitude = {/literal}{$longitude|escape:'html':'UTF-8'}{literal};
	var zoom = {/literal}{$zoom|escape:'html':'UTF-8'}{literal}
	map = new google.maps.Map(document.getElementById('map-canvas'), {
		center: new google.maps.LatLng(latitude,longitude),
		zoom: zoom,
		mapTypeId: 'roadmap'
	});
	{/literal}{if isset($show_market) && $show_market == 1}{literal}
	var myLatlng = new google.maps.LatLng(latitude,longitude);
				var marker = new google.maps.Marker({
				            position: myLatlng,
      map: map,
				           });
	{/literal}{/if}{literal}
});
{/literal} 
</script>
			    {/if}

</div>
