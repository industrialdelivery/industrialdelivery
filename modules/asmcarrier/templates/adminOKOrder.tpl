<script language="javascript">
    <!-- //
    /*$(document).ready (function () {
        window.open ("'.$path_download_pdf.'", "ASM: Etiqueta PDF","status=1,toolbar=1,scrollbars=1,menubar=1");
    });*/
    //-->
</script>

{if $version gte 1.6}
<!-- Versión >= 1.6-->
<style type="text/css">
    <!--
    #asm tr td {
        line-height:35px!important;
    }
    #asm tr td i {
        font-size: 16px;
    }
    -->
</style>
<div class="row">
    <div class="col-lg-7">
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-truck"></i>
                {$asm_lopeta}
            </div>
            {if $asm_state gte 4}
            <div class="alert alert-success">
                <p>{$asm_success_msg}</p>
            </div>
            {/if}
            <table class="table-responsive table text-center" id="asm">
                <thead>
                    <tr>
                        <th class="text-center">Pedido</th>
                        <th class="text-center">Nº Envío</th>
                        <th class="text-center">Código de envío</th>
                        <th class="text-center">PDF</th>
                        <th class="text-center">HTML</th>
                        <th class="text-center">Seguimiento</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{$referencia}</td>
                        <td>{$asm_n_envio}</td>
                        <td>{$asm_codigo_envio}</td>
                        <td><a href="{$asm_pdf_down}" title="{$asm_pdf_txt}" target="_blank"><i class="icon-download"></i></a></td>
                        <td><a href="{$asm_html_down}" title="{$asm_pdf_txt}" target="_blank"><i class="icon-download"></i></a></td>
                        <td><a href="{$asm_seguimiento_envio_url}" title="{$asm_seguimiento_envio}" target="_blank"><i class="icon-screenshot"></i></a></td>
                    </tr>
                </tbody>
            </table>
            <p>&nbsp;</p>
            <small>{$asm_version}</small>
        </div>
    </div>
</div>
{else}
<!-- Versión < 1.6-->
<fieldset class="space" style="clear:both">
    <legend><img src="../modules/asmcarrier/logo_asm.JPG" title="{$asm_lopeta}" alt="{$asm_lopeta}" width="80" class="middle" /> {$asm_lopeta}</legend>
    {if $asm_state gte 4}
        <div class="alert alert-success">
            <p>{$asm_success_msg}</p>
        </div>
    {/if}
    <table id="asm" class="table" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>Pedido</th>
            <th>Nº Envío</th>
            <th>Código de envío</th>
            <th>PDF</th>
            <th>HTML</th>
            <th>Seguimiento</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{$asm_pedido}</td>
            <td>{$asm_n_envio}</td>
            <td>{$asm_codigo_envio}</td>
            <td><a href="{$asm_pdf_down}" title="{$asm_pdf_txt}" target="_blank">{$asm_pdf_txt}</a></td>
            <td><a href="{$asm_html_down}" title="{$asm_pdf_txt}" target="_blank">{$asm_html_txt}</a></td>
            <td><a href="{$asm_seguimiento_envio_url}" title="{$asm_seguimiento_envio}" target="_blank">{$asm_seguimiento_envio}</a></td>
        </tr>
        </tbody>
    </table>
    <p>&nbsp;</p>
    <small>{$asm_version}</small>
</fieldset>
{/if}