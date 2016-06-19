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
<div class="alert alert-danger" id="slider-warning" style="display:none"></div>
<fieldset>
<div class="panel">
<div class="panel-heading">
	<i class="icon-list-ul"></i> {l s='Slides list' mod='leosliderlayer'}
	<span class="panel-heading-action">
		<a id="desc-product-new" class="list-toolbar-btn" href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&addNewSlider=1&id_group={$id_group}">
			<label><span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new" data-html="true"><i class="process-icon-new "></i></span></label>
		</a>
	</span>
</div>
        <div class="alert alert-info">{l s='Config of Group:' mod='leosliderlayer'} {$group_title} - <a href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&editgroup=1&id_group={$id_group}" alt={l s='Back to group' mod='leosliderlayer'}>{l s='Back to grop' mod='leosliderlayer'}</a></div>                    
	<div id="slidesContent" style="width: 500px; margin-top: 30px;">
		<ul id="slides">
		{foreach from=$slides item=slide}
			<li id="slides_{$slide.id_slide}">
				<strong>#{$slide.id_slide}</strong> {$slide.title|truncate:32:'...'|escape:'html':'UTF-8'}
				<div style="float: right;margin-top: -5px;">
					{$slide.status}
                                        <div class="btn-group">
                                            <a class="btn btn-default dropdown-toggle" href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&editSlider=1&id_slide={$slide.id_slide}&id_group={$id_group}"> 
                                                {if $slide.id_slide == $currentSliderID}
                                                    {l s='Editting' mod='leosliderlayer'}
                                                {else}
                                                    {l s='Action' mod='leosliderlayer'}
                                                {/if}
                                            </a>

                                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                                                <span class="caret"></span>&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" style="border: none">
                                                <li style="background-color:#fff;border: none">
                                                   <a class="" href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&editSlider=1&id_slide={$slide.id_slide}&id_group={$id_group}"> 
                                                       <i class="icon-edit"></i> {l s='Click to Edit' mod='leosliderlayer'}
                                                   </a>
                                                </li>
                                                <li style="background-color:#fff;border: none">
                                                    <a class="color_danger btn-actionslider" data-confirm="{l s='Are you sure you want to delete this slider?' mod='leosliderlayer'}" href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&leoajax=1&action=deleteSlider&id_slide={$slide.id_slide}"><i class="icon-remove-sign"></i> {l s='Delete This slider' mod='leosliderlayer'}</a>
                                                </li>
                                                <li style="background-color:#fff;border: none">
                                                   <a class="btn-actionslider" data-confirm="{l s='Are you sure you want to duplicate this slider?' mod='leosliderlayer'}" href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&leoajax=1&action=duplicateSlider&id_slide={$slide.id_slide}"> 
                                                       <i class="icon-film"></i> {l s='Duplicate This Slider' mod='leosliderlayer'}
                                                   </a>
                                                </li>
                                            </ul>
                                            
                                        </div>
                                        
                                        <div class="btn-group"> 
                                            <a class="btn btn-default {if $languages|count > 1}dropdown-toggle {else}slider-preview {/if}color_danger" href="{$previewLink}&id_group={$id_group}&id_slide={$slide.id_slide}"><i class="icon-eye-open"></i> {l s='Preview' mod='leosliderlayer'}</a>
                                            {if $languages|count > 1}

                                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                                                <span class="caret"></span>&nbsp;
                                            </button>
                                            <ul class="dropdown-menu" style="border: none">
                                                {foreach from=$languages item=language}
                                                <li style="background-color:#fff;border: none">
                                                    {$arrayParam = ['secure_key' => $msecure_key, 'id_group' => $id_group, 'id_slide'=>$slide.id_slide]}
                                                    <a href="{$link->getModuleLink('leosliderlayer','preview', $arrayParam, null, $language.id_lang)}" class="slider-preview">
                                                        <i class="icon-eye-open"></i> {l s='Preview For' mod='leosliderlayer'} {$language.name}
                                                    </a>
                                                </li>
                                                {/foreach}
                                            </ul>
                                            {/if}
                                        </div>
				</div>
			</li>
		{/foreach}
		</ul>
	</div>
</div>
</fieldset>
