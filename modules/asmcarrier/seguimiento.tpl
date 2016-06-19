<table style="border: 0px;">
<tr>
	<td style="width:100px;"><img src="{$path_img_logo}" /></td>
	<td><span style="color: #119BBB;font-size: 24px;">ASM Transporte Urgente</span></td>
</tr>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
{* imprimir formulario *}
{if $formulario}
<h2>Editar mensaje a enviar con el código de seguimiento</h2>
	<form method="post" action="{$url_formulario}">
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
	{if $error}
		{$error}
	{else}
		{$resultado}
	{/if}
{/if}
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>{$volver}</p>

<div id="cargado"></div>