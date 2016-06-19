{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{if $slides}	
{assign var="t_image" value="image_`$iso_code`"}
{assign var="t_thumb" value="thum_`$iso_code`"}
{assign var="t_title" value="title_`$iso_code`"}
{assign var="t_link" value="link_`$iso_code`"}
{assign var="t_description" value="description_`$iso_code`"}
<div class="container">
    <!-- main slider carousel -->
    <div class="row">
        <div class="col-md-12">
		<div style="clear:both"></div>
		<div class="lof-fullslider-wrapper" style="height:{$img_height|intval}px;width:{$img_width|intval}px;">
			<div class="fullslider-hero-holder" style="height:{$img_height|intval}px"></div>
			<div class="arrow-button">
				<a onclick="return false" id="btn_rt" class="lof-nextButton">{l s='Next' mod='leomanagewidgets'}</a>
				<a onclick="return false" id="btn_lt" class="lof-prevButton">{l s='Prev' mod='leomanagewidgets'}</a>
			</div>
			<div class="lof-carousel-holder">
				<div id="lof-fullslider-inner" class="lof-fullslider-inner">
					
					<ul>
						{foreach $slides as $slide name=item}
							{if isset($slide[$t_image])}
							<li style="width:{$img_width|intval}px;height:{$img_height|intval}px;">
								<!-- Image -->
								{if isset($slide[$t_image]) && $slide[$t_image]}
									<img src="{$pathimg|escape:'html':'UTF-8'}{$slide[$t_image]|escape:'html':'UTF-8'}" 
										 data-thumb="{if isset($slide[$t_thumb])}{$pathimg|escape:'html':'UTF-8'}{$slide[$t_thumb]|escape:'html':'UTF-8'}{/if}" 
										 {if isset($slide[$t_link])}onclick="window.open('{$slide[$t_link]|escape:'html':'UTF-8'}','_blank');"{/if} 
										 alt="" style="width:{$img_width|intval}px;height:{$img_height|intval}px" class="img-responsive">
								{/if}
								<div>
									  <div class="carousel-caption">
										{if  isset($slide[$t_title]) && $slide[$t_title]}<h3>{$slide[$t_title]|escape:'html':'UTF-8'}</h3>{/if}
										{if  isset($slide[$t_description]) && $slide[$t_description]}<p>{$slide[$t_description]}{* HTML form , no escape necessary *}</p>{/if}		
									  </div>
								</div>
							</li>
							{/if}
						{/foreach}
					</ul>
					
				</div>
			</div>
		</div>
		<div style="clear:both"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
   jQuery(function(){
		jQuery('#lof-fullslider-inner').fullSliderCarousel({
			transitionSpeed : {$transitionSpeed|rtrim|default:'800'|escape:'html':'UTF-8'},	// speed to next each slide
			displayTime : {$interval|rtrim|default:'4000'|escape:'html':'UTF-8'},				// time to show slide
			textholderHeight : .9,
			displayProgressBar : {$displayProgressBar|default:'1'|escape:'html':'UTF-8'},		// 1 show Progress Bar
			displayThumbnails: 1,
			displayThumbnailNumbers: {$displayThumbnailNumbers|default:'1'|escape:'html':'UTF-8'},	// show number slide
			displayThumbnailBackground: {$displayThumbnailBackground|default:'1'|escape:'html':'UTF-8'}1,
			moduleId: '',
			showCaptions: 1,
			autoPlay : {if $interval > 0}1{else}0{/if},					// 1 turn on timer
			thumbnailWidth: '{$thumb_width|rtrim|default:'20'|escape:'html':'UTF-8'}px',
			thumbnailHeight: '{$thumb_height|rtrim|default:'20'|escape:'html':'UTF-8'}px',
			thumbnailFontSize: '.7em'									// font number
		});
	});
</script>
{/if}  

