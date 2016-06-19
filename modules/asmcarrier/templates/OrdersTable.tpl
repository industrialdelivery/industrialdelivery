
<style type="text/css">
    <!--
    #asmEnvios tr th,
    #asmEnvios2 tr th {
        text-align: center;
    }
    -->
</style>
<style type="text/css" media="print">
    <!--
	#asmEnvios2, .asmEnvios2 {
		width:100%;
	}
	.asmEnvios2 tr th{ 
		width:100%;
		padding: 10px; 
		text-align: center 
	}
    #asmEnvios2 tr.head td{
		width:33%;
        padding: 5px; 
		border: 1px solid #000;
		font-size: 11px;
    }
	#asmEnvios2 tr td {
		width:100%;
        padding: 5px; 
		font-size: 11px;
    }
    -->
</style>
<div class="content bootstrap">
{if $pedidos}


{*literal*}
<script type="text/javascript">
	// Variables
	var link_mail = "{$pedido.link_envio_mail}";
	var link_tag = "{$pedido.link_etiqueta}";
	
	$(document).ready(function() {
		// Print
		console.log(link_mail, link_tag);
		
		$("#barras").mousedown(function() {
			location.href= $link_mail;
		});
		$("#barras").mouseup(function() {
			location.href= $link_tag;
		});
		if ($(".datepicker").length > 0) {
				$(".datepicker").datepicker({
					prevText: '',
					nextText: '',
					altFormat: 'yy-mm-dd'
				});
			}
	});
</script>
{*/literal*}
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" {if $activetab != 'Manifest'}class="active"{/if}><a href="#Pedidos" aria-controls="Pedidos" role="tab" data-toggle="tab">Pedidos</a></li>
    <li role="presentation" {if $activetab == 'Manifest'}class="active"{/if}><a href="#Manifiesto" aria-controls="Manifiesto" role="tab" data-toggle="tab">Manifiesto de Carga</a></li>
</ul>
 <div class="tab-content">
    <div role="tabpanel" class="tab-pane{if $activetab != 'Manifest'} active{/if}" id="Pedidos">
    <div class="row">
        <div class="col-lg-12">
            {include file="$pagerTemplate" var=$paginacion}
        </div>
        <div class="col-lg-12">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-lg-12">

            <div class="alert alert-info">
                <p>En esta nueva versión del módulo puede <strong>cambiar los bultos por expedición</strong> en el mismo momento de generar la etiqueta; solo debe <strong>indicar el número de bultos en el pedido</strong> en el campo "Bultos".</p>
                <p>Si desea emplear la <strong>configuración que ha predefinido</strong> en el módulo de ASM <strong>deje el campo "Bultos" vacío.</strong></p>
            </div>
            {if $errores}
            <div class="alert alert-danger">
                <p>{$errores}</p>
            </div>
            {/if}
            <table class="table text-center table-striped table-responsive" id="asmEnvios">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Pedido</th>
                  <th>Usuario</th>
                  <th>Precio</th>
                  <th>Fecha Pedido</th>
                  <th>Fecha Envio</th>
                  <th>Código Envio</th>
                  <th>Seguimiento</th>
                  <th>Etiquetas</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
               {foreach from=$pedidos key=o item=pedido}
                   <tr>
                       <td>{$pedido.id_envio}</td>
                       <td>{$pedido.referencia}</td>
                       <td>{$pedido.firstname} {$pedido.lastname}</td>
                       <td>{$pedido.total_paid_real}</td>
                       <td>{$pedido.date_add}</td>
                       <td>{$pedido.fecha}</td>
                       <td>{$pedido.codigo_envio}</td>
                       <td>
                            {if $pedido.url_track}
                                <a href="{$pedido.url_track}" title="Seguimiento de envío" target="_blank">
                                    <i class="icon-screenshot"></i>
                                </a>
                                <!--<a href="{$pedido.link_envio_mail}"><img src="{$path_img_email}" title="Enviar por Email Seguimiento al Cliente" alt="Enviar Seguimiento al Cliente" /></a>-->
                            {/if}
                       </td>
                       <td>
                           {if $pedido.link_etiqueta && $pedido.codigo_envio}
                               <a href="{$pedido.link_etiqueta}" id="barras" title="Ver códigos de barras">
                                    <i class="icon-barcode"></i>
                               </a>
                           {else}
                               &nbsp;
                           {/if}
                       </td>
                       <td>
                           {if !$pedido.codigo_envio && $pedido.valid}
                               <form class="form-inline" role="form" name="Bultos" method="get" action="#">
                                    <div class="form-group">
                                        <label class="sr-only" for="asm_bultos_user">Bultos</label>
                                        <input type="number" id="asm_bultos_user" name="asm_bultos_user" title="Bultos" alt="Bultos" placeholder="Número de bultos" class="form-control" data-toggle="tooltip" data-placement="top" />
                                        <input type="submit" id="enviar" name="enviar" value="Enviar" title="Generar etiqueta" alt="Generar etiqueta" class="btn btn-primary btn-sm" />
                                        <input type="hidden" id="id_order_envio" name="id_order_envio" value="{$pedido.id_envio_order}" />
                                        <input type="hidden" id="option" name="option" value="etiqueta" />
                                        <input type="hidden" id="token" name="token" value="{$token}" />
                                        <input type="hidden" id="tab" name="tab" value="AdminAsm" />
                                    </div>
                               </form>
                           {else}
                               &nbsp;
                           {/if}
                       </td>
                   </tr>
               {/foreach}

              </tbody>
            </table>
        </div>
    </div>
    </div>
	<div role="tabpanel" class="tab-pane{if $activetab == 'Manifest'} active{/if}" id="Manifiesto">
		<div class="row">
			<div class="col-lg-12">&nbsp;</div>
		</div>
		<div class="row">
			<form action="#" method="post">
			<div class="col-lg-3">
				<div class="date_range row">
				
					<div class="input-group fixed-width-md pull-left">
						<input type="text" class="filter datepicker date-input form-control" id="local_0" name="local[0]"  placeholder="{l s='From'}" />
						<input type="hidden" id="date_0" name="date[0]" value="{$date_0}">
						<span class="input-group-addon">
							<i class="icon-calendar"></i>
						</span>
					</div>
					<div class="input-group fixed-width-md pull-left">
						<input type="text" class="filter datepicker date-input form-control" id="local_1" name="local[1]"  placeholder="{l s='To'}" />
						<input type="hidden" id="date_1" name="date[1]" value="{$date_1}">
						<span class="input-group-addon">
							<i class="icon-calendar"></i>
						</span>
					</div>
					<span class="pull-left">
					<button type="submit" id="submitFilterButton" name="submitFilter" class="btn btn-default"
					onclick="document.location.href='index.php?controller=AdminAsm&tab=Manifest&token={$token}&date_0='+$('#date_0').val()+'&date_1='+$('#date_1').val();"
					>
						<i class="icon-search"></i> {l s='Search'}
					</button>
				</span>
					<script>
						$(function() {
							var dateStart = parseDate($("#date_0").val());
							var dateEnd = parseDate($("#date_1").val());
							$("#local_0").datepicker("option", "altField", "#date_0");
							$("#local_1").datepicker("option", "altField", "#date_1");
							if (dateStart !== null){
								$("#local_0").datepicker("setDate", dateStart);
							}
							if (dateEnd !== null){
								$("#local_1").datepicker("setDate", dateEnd);
							}
						});
					</script>
				
				</div>
			</div>
			<div class="col-lg-9">
			{if $mpedidos}
			{literal}
				<span class="pull-right">
					<button type="button" class="btn btn-lg btn-primary"
					onclick="$('#asmEnvios2').tableExport({type:'pdf',escape:'false',pdfFontSize:10});"
					>PDF</button>
				
					<button type="button" class="btn btn-lg btn-primary"
					onclick="$('#asmEnvios2').tableExport({type:'excel',escape:'false',htmlContent:'true'});"
					>Excel</button>
				
					<button type="button" class="btn btn-lg btn-primary"
					onclick="$('#asmEnvios2').print({addGlobalStyles : true,prepend:'<p><img src=\'{/literal}{$module_base}{literal}AdminAsm.png\'/></p><table class=\'asmEnvios2\'><thead><tr><th>Manifiesto de carga</th></tr><tr><th>{/literal}{$today}{literal}</th></tr></thead></table>'});"
					>Imprimir</button>
				</span>
			{/literal}
			{/if}
			</div>
			</form>
		</div>
		<div class="row">
            <table class="table text-center table-striped table-responsive" id="asmEnvios2">
			<tbody>
			{foreach from=$mpedidos key=m item=mpedido}
				<tr class="head">
					<td>Codigo ASM: {$mpedido.codigo_envio}</td>
					<td>Referencia: {$mpedido.reference}</td>
					<td>Total: {$mpedido.total_paid_real}</td>
				</tr>
				<tr>
					<td colspan="3">
						Información de la entrega: 
					</td>
				</tr>
				<tr>
					<td colspan="3">
						{$mpedido.firstname} {$mpedido.lastname} {$mpedido.company}
					</td>
				</tr>
				<tr>
					<td colspan="3">
						{$mpedido.address1} {$mpedido.address2}
					</td>
				</tr>
				<tr>
					<td colspan="3">
						{$mpedido.postcode} - {$mpedido.city} ({$mpedido.statename})
					</td>
				</tr>
				<tr>
					<td colspan="3">
						{if $mpedido.phone_mobile}{$mpedido.phone_mobile}{else}{$mpedido.phone}{/if}
					</td>
				</tr>
				<tr class="separator">
					<td colspan="3">
					</td>
				</tr>
				{/foreach}
			</tbody>
			</table>
		</div>
    </div>
    </div>
{else}
    <div class="alert alert-warning">
        <h3>No hay ordenes de pedido para ASM</h3>
    </div>
{/if}
</div>
<script type="text/javascript" src="{$module_base}js/jQuery.print.js"></script>
<script type="text/javascript" src="{$module_base}js/tableExport.js"></script>
<script type="text/javascript" src="{$module_base}js/jquery.base64.js"></script>
<script type="text/javascript" src="{$module_base}js/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="{$module_base}js/jspdf/jspdf.js"></script>
<script type="text/javascript" src="{$module_base}js/jspdf/jspdf.plugin.addimage.js"></script>
<script type="text/javascript" src="{$module_base}js/jspdf/libs/base64.js"></script>