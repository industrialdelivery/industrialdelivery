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
{extends file="helpers/form/form.tpl"}

{block name="field"}
    {if $input.type == 'file_lang'}
        <div class="row">
            {foreach from=$languages item=language}

                {if $languages|count > 1}
                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                    {/if}
                    <div class="col-lg-6">
                        <div class="upload-img-form">
                            <img id="thumb_slider_thumbnail_{$language.id_lang}" width="50" class="{if !$fields_value[$input.name][$language.id_lang]}nullimg{/if}" alt="" src="{$psBaseModuleUri}{$fields_value[$input.name][$language.id_lang]}"/>
                            <input id="{$input.name}_{$language.id_lang}" type="hidden" name="{$input.name}_{$language.id_lang}" class="hide" value="{$fields_value[$input.name][$language.id_lang]}" />
                            <br>
                            <a onclick="image_upload('{$input.name}_{$language.id_lang}', 'thumb_slider_thumbnail_{$language.id_lang}');" href="javascript::void(0);">{l s='Browse' mod='leosliderlayer' mod='leosliderlayer'}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
                            <a onclick="$('#thumb_slider_thumbnail_{$language.id_lang}').attr('src', '');$('#thumb_slider_thumbnail_{$language.id_lang}').addClass('nullimg'); $('#{$input.name}_{$language.id_lang}').attr('value', '');" href="javascript::void(0);">{l s='Clear' mod='leosliderlayer'}</a>
                        </div>
                        <br/>
                    </div>
                    {if $languages|count > 1}
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=lang}
                                    <li><a href="javascript:hideOtherLanguage({$lang.id_lang});" tabindex="-1">{$lang.name}</a></li>
                                {/foreach}
                            </ul>
                        </div>
                    {/if}
                    {if $languages|count > 1}
                    </div>
                {/if}
                <script>
                    $(document).ready(function() {
                        $('#{$input.name}_{$language.id_lang}-selectbutton').click(function(e) {
                            $('#{$input.name}_{$language.id_lang}').trigger('click');
                        });
                        $('#{$input.name}_{$language.id_lang}').change(function(e) {
                            var val = $(this).val();
                            var file = val.split(/[\\/]/);
                            $('#{$input.name}_{$language.id_lang}-name').val(file[file.length - 1]);
                        });
                    });
                </script>
                <input id="slider-image_{$language.id_lang}" type="hidden" name="image_{$language.id_lang}" class="hide" value="{$fields_value["image"][$language.id_lang]}" />
            {/foreach}
        </div>
    {/if}
    {if $input.type == 'group_background'}
        <div class="col-lg-9">
            <div class="upload-img-form">
               <img id="img_{$input.id}" width="50" class="{if !{$fields_value[$input.name]}}nullimg{/if}" alt="{l s='Group Back-ground' mod='leosliderlayer'}" src="{$psBaseModuleUri}{$fields_value[$input.name]}"/>
               <input id="{$input.id}" type="hidden" name="group[background_url]" class="hide" value="{$fields_value[$input.name]}" />
               <br>
               <a onclick="background_upload('{$input.id}', 'img_{$input.id}','{$ajaxfilelink}', '{$psBaseModuleUri}');" href="javascript:void(0);">{l s='Browse' mod='leosliderlayer'}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
               <a onclick="$('#img_{$input.id}').attr('src', '');$('#img_{$input.id}').addClass('nullimg'); $('#{$input.id}').attr('value', '');" href="javascript:void(0);">{l s='Clear' mod='leosliderlayer'}</a>
           </div>
            <p>{l s='Click to upload or select a back-ground' mod='leosliderlayer'}</p>
        </div>
    {/if}
    {if $input.type == 'group_button' && $input.id_group}
        <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
                <div class="btn-group pull-right">
                    <a class="btn btn-default {if $languages|count > 1}dropdown-toggle {else}group-preview {/if}color_danger" href="{$previewLink}&id_group={$input.id_group}"><i class="icon-eye-open"></i> {l s='Preview Group' mod='leosliderlayer'}</a>
                    {if $languages|count > 1}
                    
                    <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                        <span class="caret"></span>&nbsp;
                    </button>
                    <ul class="dropdown-menu">
                        {foreach from=$languages item=language}
                        <li>
                            {$arrayParam = ['secure_key' => $msecure_key, 'id_group' => $input.id_group]}
                            <a href="{$link->getModuleLink('leosliderlayer','preview', $arrayParam, null, $language.id_lang)}" class="group-preview">
                                <i class="icon-eye-open"></i> {l s='Preview For' mod='leosliderlayer'} {$language.name}
                            </a>
                        </li>
                        {/foreach}
                    </ul>
                    {/if}
                </div>
                
                <button class="btn btn-default dash_trend_right" name="submitGroup" id="module_form_submit_btn" type="submit">
                        <i class="icon-save"></i> {l s='Save' mod='leosliderlayer'}
                </button>
                <a class="btn btn-default color_success" href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&showsliders=1&id_group={$input.id_group}"><i class="icon-film"></i> {l s='Manages Sliders' mod='leosliderlayer'}</a>
                <a class="btn btn-default" href="{$exportLink}&id_group={$input.id_group}"><i class="icon-eye-open"></i> {l s='Export Group and sliders' mod='leosliderlayer'}</a>
                <a class="btn btn-default" href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&deletegroup=1&id_group={$input.id_group}" onclick="if (confirm('{l s='Delete Selected Group?' mod='leosliderlayer'}')) {
                            return true;
                        } else {
                            event.stopPropagation();
                            event.preventDefault();
                        }
                        ;" title="{l s='Delete' mod='leosliderlayer'}" class="delete">
                    <i class="icon-trash"></i> {l s='Delete' mod='leosliderlayer'}
                </a>
            </div>
        </div>


    {/if}
    {if $input.type == 'slider_button'}
        <div class="form-group">
            <div class="col-lg-9 col-lg-offset-3">
                <a class="btn btn-default dash_trend_right" href="javascript:void(0)" onclick="return $('#module_form').submit();"><i class="icon-save"></i> {l s='Save Slider' mod='leosliderlayer'}</a>
            </div>
        </div>
    {/if}
    {if $input.type == 'sperator_form'}
        <div class="{if isset($input.class)}{$input.class}{else}alert alert-info{/if}">{$input.text}</div>
    {/if}
    {if $input.type == 'video_config'}
        <div class="row">
            {foreach from=$languages item=language}
                {if $languages|count > 1}
                    <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                    {/if}
                    <div class="col-lg-6">
                        <div class="radiolabel">
                            <lable>{l s='Video Type' mod='leosliderlayer'}</lable>
                            <select name="usevideo_{$language.id_lang}" class="">
                                <option {if isset($fields_value["usevideo"][$language.id_lang]) && $fields_value["usevideo"][$language.id_lang] && $fields_value["usevideo"][$language.id_lang] eq "0"}selected="selected"{/if} value="0">{l s='No' mod='leosliderlayer'}</option>
                                <option {if isset($fields_value["usevideo"][$language.id_lang]) && $fields_value["usevideo"][$language.id_lang] && $fields_value["usevideo"][$language.id_lang] eq "youtube"}selected="selected"{/if} value="youtube">{l s='Youtube' mod='leosliderlayer'}</option>
                                <option {if isset($fields_value["usevideo"][$language.id_lang]) && $fields_value["usevideo"][$language.id_lang] && $fields_value["usevideo"][$language.id_lang] eq "vimeo"}selected="selected"{/if} value="vimeo">{l s='Vimeo' mod='leosliderlayer'}</option>
                            </select>
                        </div>
                        <div class="radiolabel">
                            <lable>{l s='Video ID' mod='leosliderlayer'}</lable>
                            <input id="videoid_{$language.id_lang}" name="videoid_{$language.id_lang}" type="text" {if isset($fields_value["videoid"][$language.id_lang]) && $fields_value["videoid"][$language.id_lang]} value="{$fields_value["videoid"][$language.id_lang]}"{/if}/>
                            <div class="input-group col-lg-2">
                            </div>
                            <div class="input-group col-lg-2">
                                <lable>{l s='Auto Play' mod='leosliderlayer'}</lable>
                                <select name="videoauto_{$language.id_lang}">
                                    <option value="1" {if isset($fields_value["videoauto"][$language.id_lang]) && $fields_value["videoauto"][$language.id_lang] == 1}selected="selected"{/if}>{l s='Yes' mod='leosliderlayer'}</option>
                                    <option value="0" {if isset($fields_value["videoauto"][$language.id_lang]) && $fields_value["videoauto"][$language.id_lang] == 0}selected="selected"{/if}>{l s='No' mod='leosliderlayer'}</option>
                                </select>
                                
                            </div>
                        </div>
                    </div>
                    {if $languages|count > 1}
                        <div class="col-lg-2">
                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                {$language.iso_code}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                {foreach from=$languages item=lang}
                                    <li><a href="javascript:hideOtherLanguage({$lang.id_lang});" tabindex="-1">{$lang.name}</a></li>
                                    {/foreach}
                            </ul>
                        </div>
                    {/if}
                    {if $languages|count > 1}
                    </div>
                {/if}
            {/foreach}   
        </div>
        <input type="hidden" id="current_language" name="current_language" value="{$id_language}"/>
    {/if}
    {if $input.type == 'col_width'}
        <div class="col-lg-9">
            <input type='hidden' class="col-val {$input.class}" name='{$input.name}' value='{$fields_value[$input.name]}'/>
            <button type="button" class="btn btn-default leobtn-width dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                <span class="leo-width-val">{$fields_value[$input.name]|replace:'-':'.'}/12</span><span class="leo-width leo-w-{$fields_value[$input.name]}"> </span><span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                {foreach from=$leo_width item=itemWidth}
                <li>
                    <a class="leo-w-option" data-width="{$itemWidth}" href="javascript:void(0);" tabindex="-1">                                          
                        <span class="leo-width-val">{$itemWidth|replace:'-':'.'}/12</span><span class="leo-width leo-w-{$itemWidth}"> </span>
                    </a>
                </li>
                {/foreach}
            </ul>
        </div>
    {/if}
    {if $input.type == 'group_class'}
        <div class="col-lg-9">
            <div class="well">
                <p> 
                    <input type="text" class="group-class" value="{$fields_value[$input.name]}" name="{$input.name}"/><br />
                    {l s='insert new or select classes for toggling content across viewport breakpoints' mod='leosliderlayer'}<br />
                    <ul class="leo-col-class">
                        {foreach from=$hidden_config key=keyHidden item=itemHidden}
                        <li>
                            <input type="checkbox" name="col_{$keyHidden}" value="1">
                            <label class="choise-class">{$itemHidden}</label>
                        </li>
                        {/foreach}
                    </ul>
                </p>
            </div>
        </div>
    {/if}
    {if $input.type == 'color_lang'}
        <div class="row">
            {foreach from=$languages item=language}
                    {if $languages|count > 1}
                            <div class="translatable-field lang-{$language.id_lang}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                    {/if}
                            <div class="col-lg-6">
                                <div class="col-md-4">
                                    <a href="javascript:void(0)" class="btn btn-default btn-update-slider">
                                                <i class="icon-upload"></i> {l s='Select slider background' mod='leosliderlayer'}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                            <input type="color"
                                            data-hex="true"
                                            {if isset($input.class)}class="{$input.class}"
                                            {else}class="color mColorPickerInput"{/if}
                                            name="{$input.name}_{$language.id_lang}"
                                            value="{$fields_value[$input.name]|escape:'html':'UTF-8'}" />
                                    </div>
                                </div>
                            </div>
                    {if $languages|count > 1}
                            <div class="col-lg-2">
                                    <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                            {$language.iso_code}
                                            <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                            {foreach from=$languages item=lang}
                                            <li><a href="javascript:hideOtherLanguage({$lang.id_lang});" tabindex="-1">{$lang.name}</a></li>
                                            {/foreach}
                                    </ul>
                            </div>
                    {/if}
                    {if $languages|count > 1}
                            </div>
                    {/if}
            {/foreach}
        </div>
    {/if}
    {$smarty.block.parent}
{/block}