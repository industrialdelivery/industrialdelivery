{* Vista para los pedidos *}

{literal}
<style type="text/css">
.table tr td {padding: 2px; color: #000000;}
.table tr.small th {font-size:9px;}
.selectedRow {background: #dadada;}
</style>
{/literal}
    <p>hola</p>
{* if $pedidos != "" *}

	{* include pager template *}
	{include file="$pagerTemplate" var=$pager}
	{* include pager template *}

    <table class="table" cellspacing="0" cellpadding="0" style="width:700px;">
	  <thead>
	    <tr class="small">
	      <th>ID</th>
	      <th>Num. Pedido</th>
	      <th><p style="width:270px;">Usuario</p></th>
	      <th><p style="width:100px;">Precio</p></th>
	      <th><p style="width:170px;">Fecha</p></th>
	      <th><p style="width:270px;">Código Envio</p></th>
	      <th>Seguimiento</th>
	      <th>Etiqueta</th>
	      <th><p style="width:150px;">&nbsp;</p></th>
	    </tr>
	  </thead>
	  <tbody>

            <tr>
	           <td>{$pedido.id_order_envio}</td>
	           <td>{$pedido.num_pedido}</td>
	           <td>{$pedido.firstname} {$pedido.lastname}</td>
	           <td>{$pedido.total_paid_real}</td>
	           <td>{$pedido.date_add}</td>
	           <td>{$pedido.codigo_envio}</td>
	           <td><a href="{$pedido.url_track}">ver pedido</a></td>
	           <td>
	               {if $pedido.link_etiqueta}
	                   <a href="{$pedido.link_etiqueta}">Imprimir</a>
	               {else}
	                   &nbsp;
	               {/if}
	           </td>
	           <td>
                   {if $pedido.link_cancelar}
                       <a href="{$pedido.link_cancelar}">Cancelar</a>
                   {else}
                       &nbsp;
                   {/if}
	           </td>
	       </tr>
	  </tbody>
	</table>

    {* include pager template *}
    {include file="$pagerTemplate" var=$pager}
    {* include pager template *}

{* else *}

    <h3>No hay ordenes de pedido para ASM.</h3>

{* /if *}
