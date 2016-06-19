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
            </div>
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
    <small>{$asm_version}</small>
</fieldset>
{/if}