<script type="text/javascript">
	$(document).ready(function() {
		$( "#formulario" ).load(document.formulario1.submit());
	});
</script>

<div class="content bootstrap">
    <ol class="breadcrumb">
        <li>{$volver}</li>
    </ol>
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading"> <i class="icon-barcode"></i>  ETIQUETA PDF</div>
                <div class="panel-body">
                    <p>Pulsando en el siguiente enlace / botón podrá visualizar las <strong>etiquetas en formato PDF</strong> de su envío:</p>
                    <a href="{$download_pdf}" class="btn btn-primary" title="Etiqueta PDF" target="_blank">PDF ></a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="icon-barcode"></i>  ETIQUETA HTML</div>
                <div class="panel-body">
                    <p>Pulsando en el siguiente enlace / botón podrá visualizar las <strong>etiquetas en formato HTM</strong>L de su envío:</p>
                    <a href="{$ventana_etiqueta}" class="btn btn-primary" title="Etiqueta HTML" target="_blank">HTML ></a>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="icon-envelope"></i>  OBSERVACIONES CORREO ELECTRÓNICO</div>
                <div class="panel-body">
                    {if $formulario}
                        <p>A continuación podrá editar el mensaje que, junto con el código de seguimiento, se enviará al Cliente.</p>
                        <form method="post" action="{$url_formulario}" id="formulario1" role="form">
                            <div class="form-group">
                                <label class="sr-only" for="mensaje">Mensaje:</label>
                                <textarea  rows="12" cols="79" name="mensaje" id="txt_msg" placeholder="En el siguiente campo podrá facilitar observaciones adicionales a su Cliente. Este mensaje lo recibirá el usuario por correo electrónico.">{$mensaje}</textarea>
                                <p>&nbsp;</p>
                                <input type="submit" value="Enviar" title="Enviar" alt="Enviar" class="btn btn-primary" />
                            </div>
                        </form>
                    {else}
                        {if $error}
                            <div class="alert alert-danger">
                                <p>
                                    {$error}
                                </p>
                            </div>
                        {else}
                            <div class="alert alert-success">
                                <p>
                                    {$resultado}
                                </p>
                            </div>
                        {/if}
                    {/if}
                </div>
            </div>
        </div>
    </div>
</div>
