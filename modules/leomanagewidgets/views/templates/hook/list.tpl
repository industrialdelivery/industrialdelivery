{*
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
*}

<div class="panel">
	<h3>
		<i class="icon-list-ul"></i>
		{l s='Slides list' mod='leomanagewidgets'}
        <span class="panel-heading-action">
			<a id="desc-product-new" class="list-toolbar-btn" href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=homeslider&addSlide=1">
				<label>
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new" data-html="true">
						<i class="process-icon-new "></i>
					</span>
				</label>
			</a>
		</span>
    </h3>
    <div id="slidesContent" style="width: 400px; margin-top: 30px;">
        <ul id="slides">
            {foreach from=$slides item=slide}
                <li id="slides_{$slide.id_slide|intval}">
                    <strong>#{$slide.id_slide|intval}</strong>
					{$slide.title|escape:'html':'UTF-8'}
                    <p style="float: right">
                        {$slide.status|escape:'html':'UTF-8'}
                        <a class="btn btn-primary" href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=homeslider&id_slide={$slide.id_slide|intval}">
							{l s='Edit' mod='leomanagewidgets'}
						</a>
                        <a class="btn btn-danger" href="{$link->getAdminLink('AdminModules')|escape:'html':'UTF-8'}&configure=homeslider&delete_id_slide={$slide.id_slide|intval}">
							{l s='Delete' mod='leomanagewidgets'}
						</a>
                    </p>
                </li>
            {/foreach}
        </ul>
    </div>
</div>