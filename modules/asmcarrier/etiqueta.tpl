<table style="border: 0px;">
<tr>
	<td style="width:100px;"><img src="{$path_img_logo}" /></td>
	<td><span style="color: #119BBB;font-size: 24px;">ASM Transporte Urgente</span></td>
</tr>
</table>
<p>&nbsp;</p>

{if $errores}
	{$errores}
    <p>&nbsp;</p>
{else}
	<a href="{$download_pdf}" target="_blank" title="Ver etiqueta en PDF" ><h3>Ver etiqueta en PDF</h3></a>
	<p>&nbsp;</p>
    <a href="{$ventana_etiqueta}" target="_blank" title="Ver etiqueta en ventana nueva" ><h3>Ver etiqueta en ventana nueva</h3></a>
    <p>&nbsp;</p>
{/if}
<p>&nbsp;</p>
<p>{$volver}</p>
