{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

{extends file="helpers/form/form.tpl"}
{block name="field"}
    {if $input.type == 'hook_list'}
        <div class="col-md-12">
            <div class="alert alert-success">
                <a href="http://www.leotheme.com/support/prestashop-16x-guides.html">{l s='Click Here to see Module Guide' mod='leomanagewidgets'}</a>
            </div>
        </div>
        <div class="col-md-8 leo-redirect">
            <div class="alert alert-success">{l s='Click on hook you want to config' mod='leomanagewidgets'}</div>
            <ol class="breadcrumb">
                <li class="active">HEADER</li>
                <li><a data-element="displaybanner" href="#">displayBanner</a></li>
                <li><a data-element="displaytop" href="#">displayTop</a></li>
                <li><a data-element="displaynav" href="#">displayNavigation</a></li>
                <li><a data-element="displaytopcolumn" href="#">displayTopColumn</a></li>
            </ol>
            <ol class="breadcrumb" href="#">
                <li class="active" href="#">CONTENT</li>
                <li><a data-element="displayleftcolumn" href="#">displayLeftColumn</a></li>
                <li><a data-element="displayhome" href="#">displayHome</a></li>
                <li><a data-element="displayhometabcontent" href="#">displayHomeTabContent</a></li>
                <li><a data-element="displayrightcolumn" href="#">displayRightColumn</a></li>
            </ol>
            <ol class="breadcrumb" href="#">
                <li class="active" href="#">FOOTER</li>
                <li><a data-element="displayfooter" href="#">displayFooter</a></li>
            </ol>
            
            <ol class="breadcrumb" href="#">
                <li class="active" href="#">PRODUCT PAGE</li>
                <li><a data-element="displayrightcolumnproduct" href="#">displayRightColumnProduct</a></li>
                <li><a data-element="displayleftcolumnproduct" href="#">displayLeftColumnProduct</a></li>
                <li><a data-element="producttab" href="#">productTab</a></li>
            </ol>    
             <br>       
            <input type="hidden" id="data_forms" name="data_form" value=""/>
            <input type="hidden" id="data_delete" name="data_delete" value=""/>
        </div>
        <div class="col-md-4 leo-guide">
            <div class="alert alert-success">{l s='How to use groups' mod='leomanagewidgets'}</div>
            <div class="form">
                <div class="row"><b class="title">{l s='Group' mod='leomanagewidgets'}</b></div>
                <div class="row group-wrap">
                    <div class="col-md-6">
                        <div class="column-widget">
                            <span>{l s='COLUMN IN GROUP' mod='leomanagewidgets'}</span>
                            <span>{l s='display in-line with other column of group' mod='leomanagewidgets'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="column-widget">
                            <span>{l s='COLUMN IN GROUP' mod='leomanagewidgets'}</span>
                            <span>{l s='display in-line with other column of group' mod='leomanagewidgets'}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="alert alert-success leo-alert">{l s='Please Select monitor size to configuration' mod='leomanagewidgets'}</div>
            <div class="leo-explain">
                <b>{l s='Default' mod='leomanagewidgets'}</b>
                {l s='Default monitor' mod='leomanagewidgets'} <br>
				<i>{l s='Use current monitor size' mod='leomanagewidgets'}</i><br>
                <b>{l s='Large' mod='leomanagewidgets'}</b>
                {l s='Large devices Desktops (≥1200px)' mod='leomanagewidgets'} <br>
				<i>{l s='27in Monitor' mod='leomanagewidgets'} - {l s='17in Workstation' mod='leomanagewidgets'} - {l s='15in Macbook Pro' mod='leomanagewidgets'} - {l s='11in Macbook Air' mod='leomanagewidgets'}</i><br>
                <b>{l s='Medium' mod='leomanagewidgets'}</b>
                {l s='Medium devices Desktops (≥992px)' mod='leomanagewidgets'} <br>
				<i>{l s='iPad (Landscape)' mod='leomanagewidgets'}</i><br>
                <b>{l s='Small' mod='leomanagewidgets'}</b>
                {l s='Small devices Tablets (≥768px)' mod='leomanagewidgets'} <br>
				<i>{l s='Nexus7 (Landscape)' mod='leomanagewidgets'} - {l s='iPad (Portrait)' mod='leomanagewidgets'}</i><br>
                <b>{l s='Extra small' mod='leomanagewidgets'}</b>
                {l s='Extra small devices Phones (≥481px)' mod='leomanagewidgets'} <br>
				<i>{l s='Nexus7 (Portrait)' mod='leomanagewidgets'}</i><br>
                <b>{l s='Mobile' mod='leomanagewidgets'}</b>
                {l s='Smart Phones (< 481px)' mod='leomanagewidgets'} <br>
				<i>{l s='iPhone (Landscape)' mod='leomanagewidgets'} - {l s='iPhone (Portrait)' mod='leomanagewidgets'}</i><br/>
            </div>
            <div class="btn-toolbar" role="toolbar">
                <div class="btn-group btn-group-lg leo-resize">
                    <button type="button" data-class="reset" data-width="auto" class="btn btn-default btn-success">{l s='Default' mod='leomanagewidgets'}</button>
                    <button type="button" data-class="col-lg" data-width="1200" class="btn btn-default">{l s='Large' mod='leomanagewidgets'}</button>
                    <button type="button" data-class="col-md" data-width="992" class="btn btn-default">{l s='Medium' mod='leomanagewidgets'}</button>
                    <button type="button" data-class="col-sm" data-width="768" class="btn btn-default">{l s='Small' mod='leomanagewidgets'}</button>
                    <button type="button" data-class="col-xs" data-width="603" class="btn btn-default">{l s='Extra small' mod='leomanagewidgets'}</button>
                    <button type="button" data-class="col-sp" data-width="480" class="btn btn-default">{l s='Mobile' mod='leomanagewidgets'}</button>
                </div>
            </div>
            <div style="display:none;" id="leo-mess" data-reduce="{l s='Minimum value of width is 1' mod='leomanagewidgets'}" data-increase="{l s='Maximum value of width is 12' mod='leomanagewidgets'}"></div>
        </div>
    {/if}
    {if $input.type == 'hook_data'}
        <div class="leo-heading">
            <div class="col-lg-6">
                <a href="#" id="{$input.name|escape:'html':'UTF-8'}">
					<i class="icon-cog"></i>
					{$input.name|escape:'html':'UTF-8'}
				</a>
            </div>
            <div class="col-lg-6 hwidget-form">
                <a title="" class="pull-right leo-close-open label-tooltip" data-toggle="tooltip"
                   data-original-title="{l s='Click here to close or open this form' mod='leomanagewidgets'}" data-status="1"
                   href="javascript:void(0)">
                    <i class="icon-sort-up"></i>
                </a>
            </div>
        </div>
        <div class="leo-content">
            <div id="{$input.name|escape:'html':'UTF-8'}_container" class="leohook" data-hook="{$input.name|escape:'html':'UTF-8'}">
                <div class="row leo-dm-group">
                    <div class="col-lg-12 dmgroup-container">
                        <div class="group-top row">
                            <div class="col-lg-12">
                                <div class="pull-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle btn-add-group" data-toggle="dropdown">
                                            {l s='Insert A Group' mod='leomanagewidgets'}
											<span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu pull-right list-group">
                                            {for $foo=0 to 6}
                                                <li>
                                                    <a href="javascript:void(0);" data-hook="{$input.name|escape:'html':'UTF-8'}" data-cols="{$foo|escape:'html':'UTF-8'}" class="leo-add-group">
                                                        {if $foo ==0}
                                                            {l s='Empty Group' mod='leomanagewidgets'}
                                                        {else if $foo==1}
                                                            {l s='%s column' sprintf=$foo mod='leomanagewidgets'}
                                                        {else}
                                                            {l s='%s columns' sprintf=$foo mod='leomanagewidgets'}
                                                        {/if}
                                                    </a>
                                                </li>
                                            {/for}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="group-content">
                            <div class="group-list" data-hook="{$input.name|escape:'html':'UTF-8'}">
                                {if isset($leo_group_list[$input.name]) && isset($leo_group_list[$input.name])}
                                    {foreach $leo_group_list[$input.name] item=itemGroup}
                                        {include file= './form_grouplist.tpl' type="1" hook_name=$input.name item_group=$itemGroup}
                                    {/foreach}
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {/if}
    {if $input.type == 'setting_form'}
        {*it will show default form or default group + row*}
        <div id="data_form" style="display:none;">
        {*Pop up for group*}
        <div class="group_form bootstrap" data-title="{l s='Group Form' mod='leomanagewidgets'}">
            <div class="row form-group">
                <label class="control-label col-lg-3">{l s='Active' mod='leomanagewidgets'}</label>
                <div class="col-lg-9">
                    <input class="groupactive_on" type="radio" checked="checked" value="1" name="group_active">
                    <label for="groupactive_on"> {l s='Yes' mod='leomanagewidgets'}</label>
                    <input class="groupactive_off" type="radio" value="0" name="group_active">
                    <label for="groupactive_off"> {l s='No' mod='leomanagewidgets'}</label>
                </div>
            </div>
            <div class="row form-group">
                <label class="control-label col-lg-3">{l s='Group Title' mod='leomanagewidgets'}</label>
                {foreach from=$languages item=language}
                    {if $languages|count > 1}
                        <div class="translatable-field lang-{$language.id_lang|escape:'html':'UTF-8'}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                    {/if}
                    <div class="col-lg-6">
                        <input id="group_title_{$language.id_lang|escape:'html':'UTF-8'}" type="text" name="group_title_{$language.id_lang|escape:'html':'UTF-8'}"/>
                    </div>
                    {if $languages|count > 1}
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code|escape:'html':'UTF-8'}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=lang}
                                    <li>
										<a href="javascript:hideOtherLanguage({$lang.id_lang|escape:'html':'UTF-8'});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a>
									</li>
                                {/foreach}
                            </ul>
                        </div>
                    {/if}
                    {if $languages|count > 1}
                        </div>
                    {/if}
                {/foreach}
            </div>
            <div class="row form-group">
                <label class="control-label col-lg-3">{l s='Group Class' mod='leomanagewidgets'}</label>
                <div class="col-lg-6">
                    <input type="text" class="" value="" class="group_class" name="group_class">
                </div>
            </div>
            <hr/>
            <div class="row form-group">
                <label class="control-label col-lg-3">{l s='Skin Animate Load:' mod='leomanagewidgets'}</label>
                <div class="col-lg-6">
                    <select class="group_skin_animate" name="group_skin_animate">
                        <option value="">{l s='' mod='leomanagewidgets'}</option>
                        {foreach from=$skin_animate_load key=group item=i}
                            <optgroup>{$i['name']|escape:'html':'UTF-8'}</optgroup>
                            {foreach from=$i['items'] item=j}
                                <option value="{$j|escape:'html':'UTF-8'}">{$j|escape:'html':'UTF-8'}</option>
                            {/foreach}
                        {/foreach}
                    </select>
                </div>
                <p class="control-label col-lg-12">{l s = 'Choose animation type.' mod='leomanagewidgets'}</p>
            </div>
            <div class="row form-group">
                <label class="control-label col-lg-3">{l s='AnimateOffset:' mod='leomanagewidgets'}</label>
                <div class="col-lg-6">
                    <input type="text" class="animate_offset" name="animate_offset" value=""/>
                </div>
                <p class="control-label col-lg-12"> {l s='Animate Offset (Ex: 10 to 100).' mod='leomanagewidgets'}</p>
            </div>
            <div class="row form-group">
                <label class="control-label col-lg-3">{l s='DelayAnimate:' mod='leomanagewidgets'}</label>
                <div class="col-lg-6">
                    <input type="text" class="delay_animate" name="delay_animate" value=""/>
                </div>
                <p class="control-label col-lg-12">{l s='Effect delay (Ex: 0, 0.5, 1, 1.5 ..).' mod='leomanagewidgets'}</p>
            </div>
            <hr/>
            <div class="row form-group">
                <label class="control-label col-lg-3">{l s='Background style:' mod='leomanagewidgets'}</label>
                <div class="col-lg-6">
                    <select name="background_style" class="background_style">
                        <option value="" selected="selected">{l s='None' mod='leomanagewidgets'}</option>
                        <option value="static">{l s='Static' mod='leomanagewidgets'}</option>
                        <option value="fixed">{l s='Fixed' mod='leomanagewidgets'}</option>
                        <option value="parallax">{l s='Parallax' mod='leomanagewidgets'}</option>
                        <!--<option value="fparallax">{l s='Fade Parallax' mod='leomanagewidgets'}</option>
                        <option value="sparallax">{l s='Scale Parallax' mod='leomanagewidgets'}</option>
                        <option value="fsparallax">{l s='Fade & Scale Parallax' mod='leomanagewidgets'}</option>-->
                        <option value="mouseparallax">{l s='Mouse Parallax' mod='leomanagewidgets'}</option>
                        <option value="video">{l s='Video' mod='leomanagewidgets'}</option>
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <label class="control-label col-lg-3">{l s='Background full width:' mod='leomanagewidgets'}</label>
                <div class="col-lg-6">
                    <select name="background_style_fullwidth" class="">
                        <option value="0" selected="selected">{l s='No' mod='leomanagewidgets'}</option>
                        <option value="1">{l s='Yes' mod='leomanagewidgets'}</option>
                    </select>
                </div>
            </div>
            <!-- Background Image Style Block -->
            <div class="group_background_image" style="display:none;">
                <div class="row form-group">
                    <label class="control-label col-lg-3">{l s='Background-color:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <div class="input-group">
                            <input data-hex="true" class="leo-color" name="background_style_color" value=""/>
                        </div>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="control-label col-lg-3">{l s='Background image URL:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <input type="text" class="" name="background_style_image_url" value=""/>
                    </div>
                </div>
                <div class="row form-group group_background_image_position">
                    <label class="control-label col-lg-3">{l s='Background position:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <input type="text" class="" name="background_style_position" value=""/>
                        <p class="control-label col-lg-12">{l s='Set CSS value for the background image position. (Ex: center top, right bottom, 50% 50%, 100px 200px,..)' mod='leomanagewidgets'}</p>
                    </div>
                </div>
                <div class="row form-group">
                    <label class="control-label col-lg-3">{l s='Background repeat:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <select name="background_style_repeat" class="">
                            <option value="no-repeat" selected="selected">{l s='No repeat' mod='leomanagewidgets'}</option>
                            <option value="repeat">{l s='Repeat (horizontally & vertically)' mod='leomanagewidgets'}</option>
                            <option value="repeat-x">{l s='Repeat horizontally' mod='leomanagewidgets'}</option>
                            <option value="repeat-y">{l s='Repeat vertically' mod='leomanagewidgets'}</option>
                        </select>
                    </div>
                </div>
                <!-- Background parallax params block -->
                <div class="row form-group group_background_image_parallax" style="display:none">
                    <label class="control-label col-lg-3">{l s='Parallax speed:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <input type="text" class="fixed-width-sm" name="background_style_parallax_speed" value=""/>
                        <p class="control-label col-lg-12">{l s='Set the background speed, this is relative to the natural scroll speed (Ex: 0, 0.5, 1, 2).' mod='leomanagewidgets'}</p>
                    </div>
                </div>
                <div class="row form-group group_background_image_parallax" style="display:none">
                    <label class="control-label col-lg-3">{l s='Parallax offsets:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <input type="text" class="fixed-width-sm" name="background_style_parallax_offsetx" value=""/>
                        <p class="control-label col-lg-12"> {l s='Set the global alignment horizontal offset' mod='leomanagewidgets'}</p>
                        <input type="text" class="fixed-width-sm" name="background_style_parallax_offsety" value=""/>
                        <p class="control-label col-lg-12"> {l s='Set the global alignment vertical offset' mod='leomanagewidgets'}</p>
                    </div>
                </div>
                <!-- End Background parallax params block -->
                <!-- Background mouse parallax params block -->
                <div class="row form-group group_background_image_mouseparallax" style="display:none">
                    <label class="control-label col-lg-3">{l s='Parallax axis:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <select name="background_style_mouseparallax_axis" class="">
                            <option value="both" selected="selected">{l s='Both' mod='leomanagewidgets'}</option>
                            <option value="axis-x">{l s='Axis X (horizontally)' mod='leomanagewidgets'}</option>
                            <option value="axis-y">{l s='Axis Y (vertically)' mod='leomanagewidgets'}</option>
                        </select>
                        <p class="control-label col-lg-12">{l s='Select axis effect for this background.' mod='leomanagewidgets'}</p>
                    </div>
                </div>
                <div class="row form-group group_background_image_mouseparallax" style="display:none">
                    <label class="control-label col-lg-3">{l s='Parallax strength:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <input type="text" class="fixed-width-sm" name="background_style_mouseparallax_strength" value=""/>
                        <p class="control-label col-lg-12">{l s='Set the background speed, this is relative to the natural mouse speed (Ex: 0, 0.5, 1, 2).' mod='leomanagewidgets'}</p>
                    </div>
                </div>
                <div class="row form-group group_background_image_mouseparallax" style="display:none">
                    <label class="control-label col-lg-3">{l s='Parallax offsets:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <input type="text" class="fixed-width-sm" name="background_style_mouseparallax_offsetx" value=""/>
                        <p class="control-label col-lg-12"> {l s='Set the global alignment horizontal offset' mod='leomanagewidgets'}</p>
                        <input type="text" class="fixed-width-sm" name="background_style_mouseparallax_offsety" value=""/>
                        <p class="control-label col-lg-12"> {l s='Set the global alignment vertical offset' mod='leomanagewidgets'}</p>
                    </div>
                </div>
                <!-- End Background mouse parallax params block -->
            </div>
            <!-- End Background Image Style Block -->
            <!-- Background Video Style Block -->
            <div class="group_background_video" style="display:none;">
                <div class="row form-group">
                    <label class="control-label col-lg-3">{l s='Source type:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <select name="group_background_video_source" class="">
                            <option value="youtube" selected="selected">{l s='Youtube video' mod='leomanagewidgets'}</option>
                            <option value="vimeo">{l s='Vimeo video' mod='leomanagewidgets'}</option>
                            <option value="html5">{l s='HTML5' mod='leomanagewidgets'}</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group" id="youtube-video-type">
                    <label class="control-label col-lg-3">{l s='Youtube/Vimeo video ID:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <input type="text" class="" name="group_background_video_vid" value=""/>
                    </div>
                </div>
                <div class="row form-group" id="html5-video-type">
                    <label class="control-label col-lg-3" for="">{l s='Video URL:' mod='leomanagewidgets'}</label>
                    <div class="col-lg-6">
                        <input type="text" disabled="disabled" class="" name="group_background_video_mp4" value=""/>
                        <p class="control-label col-lg-12"> {l s='Mp4 video url' mod='leomanagewidgets'}</p>
                        <input type="text" disabled="disabled" class="" name="group_background_video_webm" value=""/>
                        <p class="control-label col-lg-12"> {l s='Webm video url' mod='leomanagewidgets'}</p>
                        <input type="text" disabled="disabled" class="" name="group_background_video_ogg" value=""/>
                        <p class="control-label col-lg-12"> {l s='Ogg video url' mod='leomanagewidgets'}</p>
                    </div>
                </div>
            </div>
            <!-- End Background Video Style Block -->
            <hr/>
            <div class="row">
                <button name="submitOptionsmodule" class="btn btn-defaults btn-savegroup btn-success" type="button">
                    <i class="icon-save"></i> {l s='Save' mod='leomanagewidgets'}
                </button>
            </div>
        </div>
        {*popup for column*}
        <div class="column-form bootstrap" data-title="{l s='Column Form' mod='leomanagewidgets'}">
			<div class="panel">
				<div class="row">
					<button name="submitOptionsColumn" data-action="1" class="btn btn-defaults btn-savecolumn btn-success"
							type="button">
						<i class="icon-save"></i> {l s='Save' mod='leomanagewidgets'}
					</button>
					{*<button name="submitOptionsColumn" class="btn btn-defaults btn-savecolumn btn-success" type="button">
						<i class="icon-save"></i> {l s='Save and Stay' mod='leomanagewidgets'}
					</button>*}
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="leo-form">
							<div class="row">
								<hr/>
							</div>
							<div class="row">
								<label class="control-label col-lg-3 col-md-3" for="">{l s='Active' mod='leomanagewidgets'}</label>
								<input type="radio" class="default-on" checked="checked" value="1" name="column_active">
								<label for="columnactive_on"> {l s='Yes' mod='leomanagewidgets'}</label>
								<input type="radio" class="default-off" value="0" name="column_active">
								<label for="columnactive_off"> {l s='No' mod='leomanagewidgets'}</label>
							</div>
							<div class="row">
								<label class="control-label col-lg-12" for="">{l s='Large devices Desktops' mod='leomanagewidgets'} <sub class="required">*</sub></label>
								<div class="col-lg-12">
									<input type='hidden' class="col-val" name='column_lg' value='6'/>
									<button type="button" class="btn btn-default leobtn-width dropdown-toggle" tabindex="-1" data-toggle="dropdown">
										<span class="width-val leo-w-6"> </span><span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										{foreach from=$leo_width item=itemWidth}
											<li>
												<a class="leo-w-option" data-width="{$itemWidth|intval}" href="javascript:void(0);" tabindex="-1">
													<span class="leo-width-val leo-w-{if $itemWidth|strpos:"."}{$itemWidth|replace:'.':'-'|escape:'html':'UTF-8'}{else}{$itemWidth|intval}{/if}">{$itemWidth|intval}/12 - ( {math equation="x/y*100" x=$itemWidth y=12 format="%.2f"} % )</span>
												</a>
											</li>
										{/foreach}
									</ul>
								</div>
								<div class="col-md-12">
									<p>
										{l s='Large devices Desktops (≥1200px)' mod='leomanagewidgets'}
										{l s='27in Monitor - 17in Workstation - 15in Macbook Pro - 11in Macbook Air' mod='leomanagewidgets'}
									</p>
								</div>
							</div>
							<div class="row">
								<label class="control-label col-lg-12" for="">{l s='Medium devices Desktops' mod='leomanagewidgets'} <sub class="required">*</sub></label>
								<div class="col-lg-12">
									<input type='hidden' class="col-val" name='column_md' value='6'/>
									<button type="button" class="btn btn-default leobtn-width dropdown-toggle" tabindex="-1" data-toggle="dropdown">
										<span class="leo-width-val leo-w-6">6/12</span><span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										{foreach from=$leo_width item=itemWidth}
											<li>
												<a class="leo-w-option" data-width="{$itemWidth|intval}" href="javascript:void(0);" tabindex="-1">
													<span class="leo-width-val leo-w-{if $itemWidth|strpos:"."}{$itemWidth|replace:'.':'-'|escape:'html':'UTF-8'}{else}{$itemWidth|intval}{/if}">{$itemWidth|intval}/12</span>
												</a>
											</li>
										{/foreach}
									</ul>
								</div>
								<div class="col-md-12">
									<p>
										{l s='Medium devices Desktops (≥992px)' mod='leomanagewidgets'}
										{l s='Nexus7 (Landscape) - iPad (Portrait)' mod='leomanagewidgets'}
									</p>
								</div>
							</div>
							<div class="row">
								<label class="control-label col-lg-12" for="">{l s='Small devices Tablets' mod='leomanagewidgets'} <sub class="required">*</sub></label>
								<div class="col-lg-12">
									<input type='hidden' class="col-val" data-width="{$itemWidth|intval}" name='column_sm' value='6'/>
									<button type="button" class="btn btn-default leobtn-width dropdown-toggle" tabindex="-1" data-toggle="dropdown">
										<span class="leo-width-val leo-w-6">6/12</span><span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										{foreach from=$leo_width item=itemWidth}
											<li>
												<a class="leo-w-option" data-width="{$itemWidth|intval}" href="javascript:void(0);" tabindex="-1">
													<span class="leo-width-val leo-w-{if $itemWidth|strpos:"."}{$itemWidth|replace:'.':'-'|escape:'html':'UTF-8'}{else}{$itemWidth|intval}{/if}">{$itemWidth|intval}/12</span>
												</a>
											</li>
										{/foreach}
									</ul>
								</div>
								<div class="col-md-12">
									<p>
										{l s='Small devices Tablets (≥768px)' mod='leomanagewidgets'}
										{l s='iPad (Landscape)' mod='leomanagewidgets'}
									</p>
								</div>
							</div>
							<div class="row">
								<label class="control-label col-lg-12" for="">{l s='Extra small devices' mod='leomanagewidgets'} <sub class="required">*</sub></label>
								<div class="col-lg-12">
									<input type='hidden' class="col-val" data-width="{$itemWidth|intval}" name='column_xs' value='6'/>
									<button type="button" class="btn btn-default leobtn-width dropdown-toggle" tabindex="-1" data-toggle="dropdown">
										<span class="leo-width-val leo-w-6">6/12</span><span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										{foreach from=$leo_width item=itemWidth}
											<li>
												<a class="leo-w-option" data-width="{$itemWidth|intval}" href="javascript:void(0);" tabindex="-1">
													<span class="leo-width-val leo-w-{if $itemWidth|strpos:"."}{$itemWidth|replace:'.':'-'|escape:'html':'UTF-8'}{else}{$itemWidth|intval}{/if}">{$itemWidth|intval}/12</span>
												</a>
											</li>
										{/foreach}
									</ul>
								</div>
								<div class="col-md-12">
									<p>
										{l s='Extra small devices Phones (≥481px)' mod='leomanagewidgets'}
										{l s='Nexus7 (Portrait)' mod='leomanagewidgets'}
									</p>
								</div>
							</div>
							<div class="row">
								<label class="control-label col-lg-12" for="">{l s='Smart Phone' mod='leomanagewidgets'} <sub class="required">*</sub></label>
								<div class="col-lg-12">
									<input type='hidden' class="col-val" name='column_sp' value='6'/>
									<button type="button" class="btn btn-default leobtn-width dropdown-toggle" tabindex="-1" data-toggle="dropdown">
										<span class="leo-width-val leo-w-12">12/12</span><span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										{foreach from=$leo_width item=itemWidth}
											<li>
												<a class="leo-w-option" data-width="{$itemWidth|intval}" href="javascript:void(0);"
												   tabindex="-1">
													<span class="leo-width-val leo-w-{if $itemWidth|strpos:"."}{$itemWidth|replace:'.':'-'|escape:'html':'UTF-8'}{else}{$itemWidth|intval}{/if}">{$itemWidth|intval}/12</span>
												</a>
											</li>
										{/foreach}
									</ul>
								</div>
								<div class="col-md-12">
									<p>
										{l s='Smart Phones (< 481px)' mod='leomanagewidgets'}
										{l s='iPhone (Landscape)-iPhone (Portrait)' mod='leomanagewidgets'}
									</p>
								</div>
							</div>
							<div class="row">
								<label class="control-label col-lg-12" for="">{l s='Background-color:' mod='leomanagewidgets'}</label>
								<div class="col-lg-12">
									<div class="input-group">
										<input type="text" data-hex="true" class="leo-color" value="" class="column_background" name="column_background"/>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="row"><label class="control-label col-lg-4" for="">{l s='Column Class:' mod='leomanagewidgets'}</label></div>
						<div class="well">
							<p>
								<input type="text" class="" value="" class="column_class" name="column_class"/><br/>
								{l s='insert new or select classes for toggling content across viewport breakpoints' mod='leomanagewidgets'}<br/>
							<ul class="leo-col-class">
								{foreach from=$hidden_config key=keyHidden item=itemHidden}
									<li>
										<input type="checkbox" name="col_{$keyHidden|escape:'html':'UTF-8'}" value="1">
										<label class="choise-class">{$itemHidden|escape:'html':'UTF-8'}</label>
									</li>
								{/foreach}
							</ul>
							</p>
						</div>
					</div>
					<div class="row">
						<label class="control-label col-lg-3" for="">{l s='Skin Animate Load:' mod='leomanagewidgets'}</label>
						<select class="skin_animate col-lg-6" name="skin_animate">
							<option value="">{l s='' mod='leomanagewidgets'}</option>
							{foreach from=$skin_animate_load key=group item=i}
								<optgroup>{$i['name']|escape:'html':'UTF-8'}</optgroup>
								{foreach from=$i['items'] item=j}
									<option value="{$j|escape:'html':'UTF-8'}">{$j|escape:'html':'UTF-8'}</option>
								{/foreach}
							{/foreach}
						</select>
						<label class="control-label col-lg-12">{l s = 'choose animation type.' mod='leomanagewidgets'}</label>
					</div>
					<br/>
					<div class="row">
						<label class="control-label col-lg-3" for="">{l s=' AnimateOffset:' mod='leomanagewidgets'}</label>
					   <div class="col-lg-6"><input type="text" class="animate_offset" name="animate_offset" value=""/></div>
						<label class="control-label col-lg-12">{l s='Animate Offset (Ex: 10 to 100).' mod='leomanagewidgets'}</label>
					</div>
					<br/>
					<div class="row">
						<label class="control-label col-lg-3" for="">{l s=' DelayAnimate:' mod='leomanagewidgets'}</label>
					   <div class="col-lg-6"><input type="text" class="delay_animate" name="delay_animate" value=""/></div>
						<label class="control-label col-lg-12">{l s='Effect delay (Ex: 0, 0.5, 1, 1.5 ..)' mod='leomanagewidgets'}</label>
					</div>
					<br/>
					<div class="row">
						<label class="control-label col-lg-6" for="">{l s='Select specific Controller:' mod='leomanagewidgets'}</label>
						<select name="column_specific" class="column_specific">
							<option value="all">{l s='All' mod='leomanagewidgets'}</option>
							<option value="index">{l s='Index' mod='leomanagewidgets'}</option>
							<option value="category">{l s='Category' mod='leomanagewidgets'}</option>
							<option value="product">{l s='Product' mod='leomanagewidgets'}</option>
							<option value="cms">{l s='CMS' mod='leomanagewidgets'}</option>
						</select>
					</div>
					<div class="row">
						<label class="control-label col-lg-6" for="">{l s='Controller ID:' mod='leomanagewidgets'}</label>
						<input type="text" class="column_controllerids" name="column_controllerids"/>
					</div>
					<br/>
					<div class="col-lg-12 showall">
						<div class="row"><label class="control-label col-lg-4 " for="">{l s='Exceptions Page:' mod='leomanagewidgets'}</label></div>
						<div class="well">
							<p>
								{l s='Please specify the files for which you do not want the widget to be displayed.' mod='leomanagewidgets'}<br/>
								{$exception_list}{* HTML form , no escape necessary *}
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<button name="submitOptionsColumn" data-action="1" class="btn btn-defaults btn-savecolumn btn-success" type="button">
						<i class="icon-save"></i> {l s='Save' mod='leomanagewidgets'}
					</button>
					{*<button name="submitOptionsColumn" class="btn btn-defaults btn-savecolumn btn-success" type="button">
						<i class="icon-save"></i> {l s='Save and Stay' mod='leomanagewidgets'}
					</button>*}
				</div>
			</div>
        </div>
        {*popup for row of column*}
        <div class="row-form bootstrap" data-title="{l s='Row Form' mod='leomanagewidgets'}">
            <div class="panel">
                <div class="row">
                    <button name="submitOptionsColumn" data-action="1" class="btn btn-defaults btn-saverow btn-success" type="button">
                        <i class="icon-save"></i> {l s='Save' mod='leomanagewidgets'}
                    </button>
                    {*<button name="submitOptionsColumn" class="btn btn-defaults btn-saverow btn-success" type="button">
                        <i class="icon-save"></i> {l s='Save and Stay' mod='leomanagewidgets'}
                    </button>*}
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="leo-form">
                            <div class="row">
                                <label class="control-label col-lg-3" for="">{l s='Column Type:' mod='leomanagewidgets'} <sub class="required">*</sub></label>
                                <select name="column_type" class="column_type">
                                    <option value="widget">{l s='Widget' mod='leomanagewidgets'}</option>
                                    <option value="module">{l s='Override Module' mod='leomanagewidgets'}</option>
                                </select>
                            </div>
                            <div class="row column_type_val column_type_widget">
                                <label class="control-label col-lg-12" for="">{l s='Select a Widget:' mod='leomanagewidgets'} <sub class="required">*</sub></label>
                                <div class="col-lg-12">
                                    <select name="column_key_widget" data-text="{l s='Please Select a widget' mod='leomanagewidgets'}">
                                        <option value="">{l s='--------- Select a widget ---------' mod='leomanagewidgets'}</option>
                                        {foreach $leo_widgets item=widgetTypeItem key="widgetTypeKey"}
                                            <optgroup label="{$widgetTypeKey|escape:'html':'UTF-8'}">
                                                {foreach $widgetTypeItem item=widgetItem}
                                                    <option value="{$widgetItem.id|escape:'html':'UTF-8'}">{$widgetItem.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                            </optgroup>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="row column_type_val column_type_module" style="display: none">
                                <label class="control-label col-lg-12" for="">{l s='Select a Module:' mod='leomanagewidgets'} <sub class="required">*</sub></label>
                                <div class="col-lg-12">
                                    <select class="column_module" name="column_module"
                                            data-text="{l s='Please Select a module' mod='leomanagewidgets'}">
                                        <option value="">{l s='--------- Select a Module ---------' mod='leomanagewidgets'}</option>
                                        {foreach $leo_modules item=moduleItem}
                                            {if $moduleItem.hook_list}
                                                <option value="{$moduleItem.name|escape:'html':'UTF-8'}" data-hook={$moduleItem.hook_list|escape:'html':'UTF-8'}>{$moduleItem.name|escape:'html':'UTF-8'}</option>
                                            {/if}
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="row column_type_val column_type_module" style="display: none">
                                <label class="control-label col-lg-12" for="">{l s='Select hook of module:' mod='leomanagewidgets'} <sub class="required">*</sub></label>
                                <div class="col-lg-12">
                                    <select class="list_hook" name="column_module_hook" data-text="{l s='Please Select a hook' mod='leomanagewidgets'}">
                                        <option value="">{l s='--------- Select a Hook ---------' mod='leomanagewidgets'}</option>
                                    </select>
                                    <!-- here -->
                                </div>
                            </div>
                            <div class="row column_type_val column_type_module" style="display: none">
                                <div class="col-lg-12 checkbox">
                                    <label class="control-label">
                                        <input type="checkbox" name="delete_module" value="0">
										{l s='Delete module in this hook' mod='leomanagewidgets'}
									</label>
                                </div>
                            </div>
                            <div class="alert alert-danger" style="margin-top: 10px">
								{l s='Please consider using this function. This function is only for advance user, It will load other module and display in column of leomanagewidget. With some module have ID in wrapper DIV, your site will have Javascript Conflicts. We will not support this error' mod='leomanagewidgets'}
							</div>
                            <div class="row">
								<hr/>
                            </div>
                            <div class="row">
                                <label class="control-label col-lg-3 col-md-3" for="">{l s='Active' mod='leomanagewidgets'}</label>
                                <input type="radio" class="default-on" checked="checked" value="1" name="row_active">
                                <label for="columnactive_on"> {l s='Yes' mod='leomanagewidgets'}</label>
                                <input type="radio" class="default-off" value="0" name="row_active">
                                <label for="columnactive_off"> {l s='No' mod='leomanagewidgets'}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {*Default Column*}
        <div id="default_column" class="column-row">
            <div class="leo-column unset-widget">
                <div class="leo-action-top pull-right">
                    <button type="button" class="leo-cog dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                        <div class="width-val"></div>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu pull-right">
                        {foreach from=$leo_width item=itemWidth}
                            <li>
                                <a class="leo-change-width" data-width="{$itemWidth|intval}" href="javascript:void(0);" tabindex="-1">
                                    <span class="leo-width-val leo-w-{if $itemWidth|strpos:"."}{$itemWidth|replace:'.':'-'|escape:'html':'UTF-8'}{else}{$itemWidth|intval}{/if}">{$itemWidth|intval}/12 - ( {math equation="x/y*100" x=$itemWidth y=12 format="%.2f"} % )</span>
                                </a>
                            </li>
                        {/foreach}
                    </ul>
                    <div class="action-sign">
                        <a class="width-action plus-sign" href="#" data-action="1"></a>
                        <a class="width-action minus-sign" href="#" data-action="-1"></a>
                    </div>
                </div>
                <div class="leo-column-action pull-left">
                    <a title="{l s='Click here to change column status' mod='leomanagewidgets'}" class="leo-column-status label-tooltip" data-value="1">
                        <span class="status-enable">&nbsp;</span><span class="status-disable" style="display:none;">&nbsp;</span>
                    </a>
                    <a class="leo-edit-column" href="javascript:void(0)"><span class="status-edit">&nbsp;</span></a>
                    <a style="color:#fff" class="leo-delete-column" data-confirm="{l s='Are you sure you want to delete this column?' mod='leomanagewidgets'}" data-for="delete" href="javascript:void(0)">
                        <span class="status-delete">&nbsp;</span>
					</a>
                </div>
                <div class="leo-column-row clear"></div>
                <div class="leo-column_btn">
                    <a class="btn-add-row btn-add-widget" title="{l s='Click here to add a new row' mod='leomanagewidgets'}" href="javascript:void(0)" data-action="1">{l s='Add widgets' mod='leomanagewidgets'}</a>
                </div>
            </div>
        </div>
        {*Default Row*}
        <div class="leo-column_title" id="default_row">
            <a title="{l s='Click here to change row status' mod='leomanagewidgets'}" class="leo-row-status label-tooltip" data-value="1">
                <span class="status-enable">&nbsp;</span>
				<span class="status-disable" style="display:none;">&nbsp;</span>
            </a>
            <a style="color:#D9534F" class="leo-delete-row" data-confirm="{l s='Are you sure you want to delete this column?' mod='leomanagewidgets'}" data-for="delete" href="javascript:void(0)">
				<span class="status-delete">&nbsp;</span>
			</a>
            <a class="leo-edit-row" href="javascript:void(0)"><span class="status-edit">&nbsp;</span></a>
            <a class="leo-edit-widget" data-for="widget" href="javascript:void(0);"></a>
        </div>
        {*Default group*}
        <div id="default_group" class="row group-row" data-original-title="{l s='You can drag this group to other hook' mod='leomanagewidgets'}" data-type="1">
            <div class="group-panel col-lg-12">
                <div class="pull-left">
                    <a title="{l s='Click here to change group status' mod='leomanagewidgets'}" class="leo-group-status label-tooltip leo-tool" data-value="1">
                        <span class="status-enable">{l s='Enable' mod='leomanagewidgets'}</span>
						<span class="status-disable" style="display:none;">{l s='Disable' mod='leomanagewidgets'}</span>
                    </a>
                    <a class="leo-group-btn leo-edit-group label-tooltip leo-tool" data-original-title="{l s='Click here to Edit group' mod='leomanagewidgets'}">
                        <span class="status-edit">{l s='Edit' mod='leomanagewidgets'}</span>
                    </a>
                    <a style="color:#D9534F" class="leo-group-btn leo-remove-group label-tooltip leo-tool" data-confirm="{l s='Are you sure you want to delete this group?' mod='leomanagewidgets'}" data-original-title="{l s='Click here to delete group' mod='leomanagewidgets'}" href="javascript:void(0)">
                        <span class="status-delete">{l s='Delete' mod='leomanagewidgets'}</span>
                    </a>
                </div>
                <div class="pull-right">
                    <button type="button" class="btn btn-default leobtn-width dropdown-toggle btn-add-group btn-add-col" tabindex="-1" data-toggle="dropdown">
                        {l s='Insert A Column' mod='leomanagewidgets'} 
						<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        {foreach from=$leo_width item=itemWidth}
                            <li>
                                <a class="leo-add-column" data-width="{$itemWidth|intval}" href="javascript:void(0);" tabindex="-1">
                                    <span class="leo-width-val leo-w-{if $itemWidth|strpos:"."}{$itemWidth|replace:'.':'-'|escape:'html':'UTF-8'}{else}{$itemWidth|intval}{/if}">{$itemWidth|intval}/12 - ( {math equation="x/y*100" x=$itemWidth y=12 format="%.2f"} % )</span>
                                </a>
                            </li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
                <div class="column-list col-lg-12"></div>
            </div>
            <script type="text/javascript">
                $(document).ready(function(){
                    var $leoManage = $(document).leomanagewidgets();
                    $leoManage.groupField = {$leo_groupField};{* HTML form , no escape necessary *}
                    $leoManage.columnField = {$leo_columnField};{* HTML form , no escape necessary *}
                    $leoManage.rowField = {$leo_rowField};{* HTML form , no escape necessary *}
                    $leoManage.setData('{$leo_json_data}');{* HTML form , no escape necessary *}
                    $leoManage.submitLink = '{$leo_submit_link}';{* HTML form , no escape necessary *}
                    $leoManage.widgetLink = '{$widget_link}&addleowidgets&type=popup';{* HTML form , no escape necessary *}
                    $leoManage.moduleLink = '{$module_link}';{* HTML form , no escape necessary *}
                $(".leo-edit-group").click(function(e){
                    $("select[name='background_style']").trigger("change");
                    $("select[name='group_background_video_source']").trigger("change");
                });
                $("select[name='background_style']").change(function(e){
                    var selectedValue = $(this).val();
                    if (selectedValue === 'video')
                        $(".group_background_video").css("display", "block");
                    else
                        $(".group_background_video").css("display", "none");
                    if ( selectedValue !== 'undefined' && selectedValue !== '' && selectedValue !== 'video') {
                        $(".group_background_image").css("display", "block");
                        if ( selectedValue === 'parallax')
                            $(".group_background_image_parallax").css("display","block");
                        else
                            $(".group_background_image_parallax").css("display","none");
                        if ( selectedValue === 'mouseparallax'){
                            $(".group_background_image_mouseparallax").css("display","block");
                            $(".group_background_image_position").css("display","none");
                        }
                        else{
                            $(".group_background_image_position").css("display","block");
                            $(".group_background_image_mouseparallax").css("display","none");
                        }
                    } else {
                        $(".group_background_image").css("display","none");
                    }
                });
                $("select[name='group_background_video_source']").change(function(e){
                    if ($(this).val() === 'html5') {
                        $("#youtube-video-type input").attr("disabled", true);
                        $("#html5-video-type input").attr("disabled", false);
                    }
                    else {
                        $("#html5-video-type input").attr("disabled", true);
                        $("#youtube-video-type input").attr("disabled", false);
                    }
                });
            });
            </script>
        </div>
    {/if}
    {$smarty.block.parent}
{/block}