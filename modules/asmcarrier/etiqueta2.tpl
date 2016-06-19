<script type="text/javascript">
	$(document).ready(function() {
		$( "#formulario" ).load(document.formulario1.submit());
	});
</script>


<table style="border: 0px;" id="#formulario">
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

    
<div id="formulario">
{* imprimir formulario *}
{if $formulario}
<h2>Editar mensaje a enviar con el código de seguimiento</h2>
	<form method="post" action="{$url_formulario}" id="formulario1">
    	<fieldset style="width: 600px;">
			<legend style="cursor: pointer;"><img src="../img/admin/email_edit.gif"> Mensaje</legend>
			<div style="" id="message">
				<br><br>
				<textarea  rows="12" cols="79" name="mensaje" id="txt_msg">{$mensaje}</textarea>
				<br><br>
				<input type="submit" value="Enviar" name="submitMessage" class="button">
			</div>
		</fieldset>
	</form>
{else}
	<h3>Observación sobre el correo electrónico: </h3>
    {if $error}
		{$error}
	{else}
		{$resultado}
	{/if}
{/if}
</div>    
<p>&nbsp;</p>
<p>{$volver}</p>
