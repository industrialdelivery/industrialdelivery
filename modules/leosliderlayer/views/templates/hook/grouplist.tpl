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
<fieldset>
    <div id="groupLayer" class="panel col-lg-12">
        <h3>{l s='Group List' mod='leosliderlayer'}</h3>
        <div class="alert alert-info"><a href="http://www.leotheme.com/guides/prestashop16/leo-slider-layer/" target="_blank">{l s='Click to see configuration guide' mod='leosliderlayer'}</a></div>
        <div class="table-responsive clearfix">
            <table class="table">
                <thead>
                    <tr class="nodrag nodrop">
                        <th class="center fixed-width-xs"></th>

                        <th class="">
                            <span class="title_box ">
                                {l s='Group Name' mod='leosliderlayer'}
                            </span>
                        </th>
                        <th class="center fixed-width-xs"> <span class="title_box ">{l s='Status' mod='leosliderlayer'}</span></th>

                        <th colspan="2">
                            <a href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&addNewGroup=1" class="btn btn-default">
                                <i class="icon-plus"></i> {l s='Add new Group' mod='leosliderlayer'}
                            </a>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$groups item=group}
                        <tr class=" odd">
                            <td class="text-center"><strong>#{$group.id_leosliderlayer_groups}</strong></td>
                            <td {if $group.id_leosliderlayer_groups != $curentGroup}onclick="document.location = '{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&editgroup=1&id_group={$group.id_leosliderlayer_groups}'"{/if} class="pointer">
                                {$group.title}
                            </td>
                            <td>
                                {$group.status}&nbsp;&nbsp;&nbsp;
                            </td>

                            <td>
                                <div class="btn-group-action">
                                    <div class="btn-group pull-right">
                                        {if $group.id_leosliderlayer_groups != $curentGroup}
                                            <a href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&editgroup=1&id_group={$group.id_leosliderlayer_groups}" title="{l s='Edit Group' mod='leosliderlayer'}" class="edit btn btn-default">
                                                <i class="icon-pencil"></i> {l s='Edit' mod='leosliderlayer'}
                                            </a>
                                        {else}
                                            <a href="#" title="{l s='Editting' mod='leosliderlayer'}" class="btn " style="color:#BBBBBB">
                                                {l s='Editting' mod='leosliderlayer'}
                                            </a>
                                        {/if}
                                        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <span class="caret"></span>&nbsp;
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&deletegroup=1&id_group={$group.id_leosliderlayer_groups}" onclick="if (confirm('{l s='Delete Selected Group?' mod='leosliderlayer'}')) {
                                        return true;
                                    } else {
                                        event.stopPropagation();
                                        event.preventDefault();
                                    }
                                    ;" title="{l s='Delete' mod='leosliderlayer'}" class="delete">
                                                    <i class="icon-trash"></i> {l s='Delete' mod='leosliderlayer'}
                                                </a>
                                                {if $languages|count > 1}
                                                    {foreach from=$languages item=language}
                                                        {$arrayParam = ['secure_key' => $msecure_key, 'id_group' => $group.id_leosliderlayer_groups]}
                                                        <a href="{$link->getModuleLink('leosliderlayer','preview', $arrayParam, null, $language.id_lang)}" class="group-preview">
                                                            <i class="icon-eye-open"></i> {l s='Preview For' mod='leosliderlayer'} {$language.name}
                                                        </a>
                                                    {/foreach}
                                                {else}
                                                    <a class="group-preview" href="{$previewLink}&id_group={$group.id_leosliderlayer_groups}">
                                                        <i class="icon-eye-open"></i> {l s='Preview Group' mod='leosliderlayer'}
                                                    </a>
                                                {/if}
                                                <a class="" target="_blank" href="{$exportLink}&id_group={$group.id_leosliderlayer_groups}">
                                                    <i class="icon-archive"></i> {l s='Export Group and sliders' mod='leosliderlayer'}
                                                </a>
                                                {*<a class="" target="_blank" href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&editgroup=1&correctGroup=1&id_group={$group.id_leosliderlayer_groups}">
                                                    <i class="icon-edit"></i> {l s='Correct Group Content' mod='leosliderlayer'}
                                                </a>*}
                                                <a class="" href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&editgroup=1&copylang=1&id_group={$group.id_leosliderlayer_groups}">
                                                    <i class="icon-edit"></i> {l s='Copy default language to other' mod='leosliderlayer'}
                                                </a>
                                            </li>
                                        </ul>

                                    </div>
                                </div>				
                            </td>
                            <td>
                                <a class="btn btn-default color_success" href="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&showsliders=1&id_group={$group.id_leosliderlayer_groups}"><i class="icon-film"></i> {l s='Manages Sliders' mod='leosliderlayer'}</a>
                            </td>
                        </tr> 
                    {/foreach}
            </table>
            <table class="table">
                <thead>
                    <tr class="nodrag nodrop">
                        <th class="center fixed-width-xs"></th>
                        <th class="">
                            <span class="title_box ">
                                {l s='Import Group' mod='leosliderlayer'}
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class=" odd">
                        <td colspan="2" style="margin-top:10px;padding:10px">
                            <form method="post" enctype="multipart/form-data" action="{$link->getAdminLink('AdminModules')}&configure=leosliderlayer&importGroup=1">
                                <div class="row">
                                        <div class="form-group">
                                                
                                                <input type="file" class="hide" name="import_file" id="import_file">
                                                <div class="dummyfile input-group">
                                                        <span class="input-group-addon"><i class="icon-file"></i></span>
                                                        <input type="text" readonly="" name="filename" class="disabled" id="import_file-name">
                                                        <span class="input-group-btn">
                                                                <button class="btn btn-default" name="submitAddAttachments" type="button" id="import_file-selectbutton">
                                                                        <i class="icon-folder-open"></i> {l s='Choose a file' mod='leosliderlayer'}
                                                                </button>
                                                        </span>
                                                </div>
                                                <p class="help-block color_danger">{l s='Please upload *.txt only' mod='leosliderlayer'}</p>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-4" for="title_group">
                                                    {l s='Overide group or not:' mod='leosliderlayer'}
                                            </label>
                                            <div class="input-group col-lg-3">
                                                    <span class="switch prestashop-switch">
                                                                                                                                                            <input type="radio" value="1" id="override_group_on" name="override_group">
                                                            <label for="override_group_on">
                                                                                                                                                                                    <i class="icon-check-sign color_success"></i> {l s='Yes' mod='leosliderlayer'}
                                                                                                                                                                    </label>
                                                                                                                                                            <input type="radio" checked="checked" value="0" id="override_group_off" name="override_group">
                                                            <label for="override_group_off">
                                                                                                                                                                                    <i class="icon-ban-circle color_danger"></i> {l s='No' mod='leosliderlayer'}
                                                                                                                                                                    </label>
                                                                                                                                                            <a class="slide-button btn btn-default"></a>
                                                    </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
						<div class="col-lg-12">
							<button class="btn btn-default dash_trend_right" name="importGroup" id="import_file_submit_btn" type="submit">
								 {l s='Import' mod='leosliderlayer'}
							</button>
													</div>
					</div>                                                                                                                            
                                </div>
                            </form>
                        </td>
                    </tr> 
                </tbody>
            </table>
        </div>
    </div>
</fieldset>
<script type="text/javascript">
    $(document).ready(function() {
        //import export fix
        $('#import_file-selectbutton').click(function(e){
                $('#import_file').trigger('click');
        });
        $('#import_file').change(function(e){
                var val = $(this).val();
                var file = val.split(/[\\/]/);
                $('#import_file-name').val(file[file.length-1]);
        });
        $('#import_file_submit_btn').click(function(e){
            if($("#import_file-name").val().indexOf(".txt") != -1){
                if($("override_group_on").is(":checked")) return confirm("{l s='Are you sure to override group?' mod='leosliderlayer'}");
                return true;
            }else{
                alert("{l s='Please upload txt file' mod='leosliderlayer'}");
                $('#import_file').val("");
                $('#import_file-name').val("");
                return false;
            }
	});		
        
        $(".group-preview").click(function() {
            eleDiv = $(this).parent().parent().parent();
            if ($(eleDiv).hasClass("open"))
                eleDiv.removeClass("open");

            var url = $(this).attr("href") + "&content_only=1";
            $('#dialog').remove();
            $('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe name="iframename2" src="' + url + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
            $('#dialog').dialog({
                title: 'Preview Management',
                close: function(event, ui) {

                },
                bgiframe: true,
                width: 1000,
                height: 500,
                resizable: false,
                draggable:false,
                modal: true
            });
            return false;
        });
    });
</script>
