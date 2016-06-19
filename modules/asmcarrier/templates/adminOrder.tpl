{if $version gte 1.6}
<!-- Versión >= 1.6-->
<div class="row">
    <div class="col-lg-7">
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-truck"></i>
                {$asm_lopeta}
            </div>
            <div class="alert alert-warning">
                <p>{$mensaje}</p>
            </div>
            <div class="alert alert-info">
                <p>{$bultos_info}</p>
                <p>{$bultos_info_b}</p>
            </div>
            <form id="asm_bultos" name="asm_bultos" title="" action="index.php" method="get" class="form-horizontal well hidden-print">
                <div class="form-group">
                    <label for="asm_bultos_user" class="control-label col-lg-1">
                        {$bultos_message}:
                    </label>
                    <div class="col-lg-11">
                        <div class="col-lg-2">
                            <input type="number" title="{$bultos_input_txt}" alt="{$bultos_input_txt}" id="asm_bultos_user" name="asm_bultos_user" pattern="[0-9]" class="form-control fixed-width-sm"  />
                        </div>
                        <div class="col-lg-10">
                            <input type="hidden" name="controller" value="{$bultos_controller}" />
                            <input type="hidden" name="id_order" value="{$bultos_id_order}" />
                            <input type="hidden" name="regenerar" value="{$bultos_regenerar}" />
                            <input type="hidden" name="vieworder" value="" />
                            <input type="hidden" name="token" value="{$bultos_token}" />
                            <input type="submit" title="{$bultos_btn}" alt="{$bultos_btn}" value="{$bultos_btn}" class="btn btn-primary" />
                        </div>
                    </div>
                </div>
            </form>
            <small>{$asm_version}</small>
        </div>
    </div>
</div>
{else}
<!-- Versión < 1.6-->
<fieldset class="space" style="clear:both">
    <legend><img src="../modules/asmcarrier/logo_asm.JPG" title="{$asm_lopeta}" alt="{$asm_lopeta}" width="80" class="middle" /> {$asm_lopeta}</legend>
    <p>{$mensaje}</p>
    <p>{$bultos_info}</p>
    <p>{$bultos_info_b}</p>
    <form id="asm_bultos" name="asm_bultos" title="" action="index.php" method="get">
        <label for="asm_bultos_user">
            {$bultos_message}:
        </label>
        <input type="number" title="{$bultos_input_txt}" alt="{$bultos_input_txt}" id="asm_bultos_user" name="asm_bultos_user" pattern="[0-9]"  />
        {if $version gte 1.5}
        <!-- Versión >= 1.5-->
        <input type="hidden" name="controller" value="{$bultos_controller}" />
        {else}
        <!-- Versión <= 1.4-->
        <input type="hidden" name="tab" value="{$bultos_controller}" />
        {/if}
        <input type="hidden" name="id_order" value="{$bultos_id_order}" />
        <input type="hidden" name="regenerar" value="{$bultos_regenerar}" />
        <input type="hidden" name="vieworder" value="" />
        <input type="hidden" name="token" value="{$bultos_token}" />
        <input type="submit" title="{$bultos_btn}" alt="{$bultos_btn}" value="{$bultos_btn}" />
    </form>
    <small>{$asm_version}</small>
</fieldset>
{/if}