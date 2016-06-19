{* Vista para los pedidos *}

{literal}
<style type="text/css">
.table tr td {padding: 2px; color: #000000;}
.table tr.small th {font-size:9px;text-align:center;}
.selectedRow {background: #dadada;}
</style>
{/literal}
<table style="border: 0px;">
<tr>
	<td style="width:100px;"><img src="{$path_img_logo}" /></td>
	<td><span style="color: #119BBB;font-size: 24px;">ASM Transporte Urgente</span></td>
</tr>
</table>

{if $pedidos}
{* include pager template *}
{include file="$pagerTemplate" var=$pager}
{* include pager template *}

    <table class="table" cellspacing="0" cellpadding="0" style="width:700px;">
	  <thead>
	    <tr class="small">
	      <th>ID</th>
	      <th><p style="width:70px;">Pedido</p></th>
	      <th><p style="width:120px;">Usuario</p></th>
	      <th><p style="width:60px;">Precio</p></th>
	      <th><p style="width:100px;">Fecha Pedido</p></th>
	      <th><p style="width:100px;">Fecha Envio</p></th>
	      <th><p style="width:230px;">Código Envio</p></th>
	      <th><p style="width:40px;">&nbsp;</p></th>
	      <th><p style="width:20px;">&nbsp;</p></th>
	      <th><p style="width:20px;">&nbsp;</p></th>
	    </tr>
	  </thead>
	  <tbody>
	   {foreach from=$pedidos key=o item=pedido}
	       <tr>
               <td>{$pedido.id_envio}</td>
               <td>{$pedido.num_pedido}</td>
               <td>{$pedido.firstname} {$pedido.lastname}</td>
               <td>{$pedido.total_paid_real}</td>
               <td>{$pedido.date_add}</td>
               <td>{$pedido.fecha}</td>
               <td>{$pedido.codigo_envio}</td>
               <td>
               		{if $pedido.url_track}
               			<a href="{$pedido.url_track}" target="_blank"><img src="{$path_img_track}" title="Seguimiento del Envio" alt="Seguimiento del Envio" /></a>
               			<a href="{$pedido.link_envio_mail}"><img src="{$path_img_email}" title="Enviar por Email Seguimiento al Cliente" alt="Enviar Seguimiento al Cliente" /></a>
               		{/if}
               </td>
               <td>
                   {if $pedido.link_etiqueta}
                       <a href="{$pedido.link_etiqueta}">
                       		<img src="{$path_img_cod_barras}" title="Imprimir Codigo Barras" alt="Imprimir Codigo Barras" />
                       </a>
                   {else}
                       &nbsp;
                   {/if}
               </td>
               <td>
                    &nbsp;
               </td>
           </tr>
	   {/foreach}

	  </tbody>
	</table>

{* include pager template *}
{include file="$pagerTemplate" var=$pager}
{* include pager template *}
{else}

    <h3>No hay ordenes de pedido para ASM.</h3>

{/if}
