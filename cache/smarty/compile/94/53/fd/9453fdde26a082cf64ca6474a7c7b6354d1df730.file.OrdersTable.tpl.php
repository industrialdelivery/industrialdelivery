<?php /* Smarty version Smarty-3.1.19, created on 2016-02-20 19:15:57
         compiled from "/home/miscal5/industrialdelivery.net/public_html/modules/asmcarrier/templates/OrdersTable.tpl" */ ?>
<?php /*%%SmartyHeaderCode:184915764756c8ad5d7a68f5-38693126%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9453fdde26a082cf64ca6474a7c7b6354d1df730' => 
    array (
      0 => '/home/miscal5/industrialdelivery.net/public_html/modules/asmcarrier/templates/OrdersTable.tpl',
      1 => 1455984268,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '184915764756c8ad5d7a68f5-38693126',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'pedidos' => 0,
    'pedido' => 0,
    'activetab' => 0,
    'paginacion' => 0,
    'errores' => 0,
    'path_img_email' => 0,
    'token' => 0,
    'date_0' => 0,
    'date_1' => 0,
    'mpedidos' => 0,
    'module_base' => 0,
    'today' => 0,
    'mpedido' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_56c8ad5d866481_04159109',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56c8ad5d866481_04159109')) {function content_56c8ad5d866481_04159109($_smarty_tpl) {?>
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
<?php if ($_smarty_tpl->tpl_vars['pedidos']->value) {?>



<script type="text/javascript">
	// Variables
	var link_mail = "<?php echo $_smarty_tpl->tpl_vars['pedido']->value['link_envio_mail'];?>
";
	var link_tag = "<?php echo $_smarty_tpl->tpl_vars['pedido']->value['link_etiqueta'];?>
";
	
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

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" <?php if ($_smarty_tpl->tpl_vars['activetab']->value!='Manifest') {?>class="active"<?php }?>><a href="#Pedidos" aria-controls="Pedidos" role="tab" data-toggle="tab">Pedidos</a></li>
    <li role="presentation" <?php if ($_smarty_tpl->tpl_vars['activetab']->value=='Manifest') {?>class="active"<?php }?>><a href="#Manifiesto" aria-controls="Manifiesto" role="tab" data-toggle="tab">Manifiesto de Carga</a></li>
</ul>
 <div class="tab-content">
    <div role="tabpanel" class="tab-pane<?php if ($_smarty_tpl->tpl_vars['activetab']->value!='Manifest') {?> active<?php }?>" id="Pedidos">
    <div class="row">
        <div class="col-lg-12">
            <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['pagerTemplate']->value), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('var'=>$_smarty_tpl->tpl_vars['paginacion']->value), 0);?>

        </div>
        <div class="col-lg-12">&nbsp;</div>
    </div>
    <div class="row">
        <div class="col-lg-12">

            <div class="alert alert-info">
                <p>En esta nueva versión del módulo puede <strong>cambiar los bultos por expedición</strong> en el mismo momento de generar la etiqueta; solo debe <strong>indicar el número de bultos en el pedido</strong> en el campo "Bultos".</p>
                <p>Si desea emplear la <strong>configuración que ha predefinido</strong> en el módulo de ASM <strong>deje el campo "Bultos" vacío.</strong></p>
            </div>
            <?php if ($_smarty_tpl->tpl_vars['errores']->value) {?>
            <div class="alert alert-danger">
                <p><?php echo $_smarty_tpl->tpl_vars['errores']->value;?>
</p>
            </div>
            <?php }?>
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
               <?php  $_smarty_tpl->tpl_vars['pedido'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['pedido']->_loop = false;
 $_smarty_tpl->tpl_vars['o'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['pedidos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['pedido']->key => $_smarty_tpl->tpl_vars['pedido']->value) {
$_smarty_tpl->tpl_vars['pedido']->_loop = true;
 $_smarty_tpl->tpl_vars['o']->value = $_smarty_tpl->tpl_vars['pedido']->key;
?>
                   <tr>
                       <td><?php echo $_smarty_tpl->tpl_vars['pedido']->value['id_envio'];?>
</td>
                       <td><?php echo $_smarty_tpl->tpl_vars['pedido']->value['referencia'];?>
</td>
                       <td><?php echo $_smarty_tpl->tpl_vars['pedido']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['pedido']->value['lastname'];?>
</td>
                       <td><?php echo $_smarty_tpl->tpl_vars['pedido']->value['total_paid_real'];?>
</td>
                       <td><?php echo $_smarty_tpl->tpl_vars['pedido']->value['date_add'];?>
</td>
                       <td><?php echo $_smarty_tpl->tpl_vars['pedido']->value['fecha'];?>
</td>
                       <td><?php echo $_smarty_tpl->tpl_vars['pedido']->value['codigo_envio'];?>
</td>
                       <td>
                            <?php if ($_smarty_tpl->tpl_vars['pedido']->value['url_track']) {?>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['pedido']->value['url_track'];?>
" title="Seguimiento de envío" target="_blank">
                                    <i class="icon-screenshot"></i>
                                </a>
                                <!--<a href="<?php echo $_smarty_tpl->tpl_vars['pedido']->value['link_envio_mail'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['path_img_email']->value;?>
" title="Enviar por Email Seguimiento al Cliente" alt="Enviar Seguimiento al Cliente" /></a>-->
                            <?php }?>
                       </td>
                       <td>
                           <?php if ($_smarty_tpl->tpl_vars['pedido']->value['link_etiqueta']&&$_smarty_tpl->tpl_vars['pedido']->value['codigo_envio']) {?>
                               <a href="<?php echo $_smarty_tpl->tpl_vars['pedido']->value['link_etiqueta'];?>
" id="barras" title="Ver códigos de barras">
                                    <i class="icon-barcode"></i>
                               </a>
                           <?php } else { ?>
                               &nbsp;
                           <?php }?>
                       </td>
                       <td>
                           <?php if (!$_smarty_tpl->tpl_vars['pedido']->value['codigo_envio']&&$_smarty_tpl->tpl_vars['pedido']->value['valid']) {?>
                               <form class="form-inline" role="form" name="Bultos" method="get" action="#">
                                    <div class="form-group">
                                        <label class="sr-only" for="asm_bultos_user">Bultos</label>
                                        <input type="number" id="asm_bultos_user" name="asm_bultos_user" title="Bultos" alt="Bultos" placeholder="Número de bultos" class="form-control" data-toggle="tooltip" data-placement="top" />
                                        <input type="submit" id="enviar" name="enviar" value="Enviar" title="Generar etiqueta" alt="Generar etiqueta" class="btn btn-primary btn-sm" />
                                        <input type="hidden" id="id_order_envio" name="id_order_envio" value="<?php echo $_smarty_tpl->tpl_vars['pedido']->value['id_envio_order'];?>
" />
                                        <input type="hidden" id="option" name="option" value="etiqueta" />
                                        <input type="hidden" id="token" name="token" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
" />
                                        <input type="hidden" id="tab" name="tab" value="AdminAsm" />
                                    </div>
                               </form>
                           <?php } else { ?>
                               &nbsp;
                           <?php }?>
                       </td>
                   </tr>
               <?php } ?>

              </tbody>
            </table>
        </div>
    </div>
    </div>
	<div role="tabpanel" class="tab-pane<?php if ($_smarty_tpl->tpl_vars['activetab']->value=='Manifest') {?> active<?php }?>" id="Manifiesto">
		<div class="row">
			<div class="col-lg-12">&nbsp;</div>
		</div>
		<div class="row">
			<form action="#" method="post">
			<div class="col-lg-3">
				<div class="date_range row">
				
					<div class="input-group fixed-width-md pull-left">
						<input type="text" class="filter datepicker date-input form-control" id="local_0" name="local[0]"  placeholder="<?php echo smartyTranslate(array('s'=>'From'),$_smarty_tpl);?>
" />
						<input type="hidden" id="date_0" name="date[0]" value="<?php echo $_smarty_tpl->tpl_vars['date_0']->value;?>
">
						<span class="input-group-addon">
							<i class="icon-calendar"></i>
						</span>
					</div>
					<div class="input-group fixed-width-md pull-left">
						<input type="text" class="filter datepicker date-input form-control" id="local_1" name="local[1]"  placeholder="<?php echo smartyTranslate(array('s'=>'To'),$_smarty_tpl);?>
" />
						<input type="hidden" id="date_1" name="date[1]" value="<?php echo $_smarty_tpl->tpl_vars['date_1']->value;?>
">
						<span class="input-group-addon">
							<i class="icon-calendar"></i>
						</span>
					</div>
					<span class="pull-left">
					<button type="submit" id="submitFilterButton" name="submitFilter" class="btn btn-default"
					onclick="document.location.href='index.php?controller=AdminAsm&tab=Manifest&token=<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
&date_0='+$('#date_0').val()+'&date_1='+$('#date_1').val();"
					>
						<i class="icon-search"></i> <?php echo smartyTranslate(array('s'=>'Search'),$_smarty_tpl);?>

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
			<?php if ($_smarty_tpl->tpl_vars['mpedidos']->value) {?>
			
				<span class="pull-right">
					<button type="button" class="btn btn-lg btn-primary"
					onclick="$('#asmEnvios2').tableExport({type:'pdf',escape:'false',pdfFontSize:10});"
					>PDF</button>
				
					<button type="button" class="btn btn-lg btn-primary"
					onclick="$('#asmEnvios2').tableExport({type:'excel',escape:'false',htmlContent:'true'});"
					>Excel</button>
				
					<button type="button" class="btn btn-lg btn-primary"
					onclick="$('#asmEnvios2').print({addGlobalStyles : true,prepend:'<p><img src=\'<?php echo $_smarty_tpl->tpl_vars['module_base']->value;?>
AdminAsm.png\'/></p><table class=\'asmEnvios2\'><thead><tr><th>Manifiesto de carga</th></tr><tr><th><?php echo $_smarty_tpl->tpl_vars['today']->value;?>
</th></tr></thead></table>'});"
					>Imprimir</button>
				</span>
			
			<?php }?>
			</div>
			</form>
		</div>
		<div class="row">
            <table class="table text-center table-striped table-responsive" id="asmEnvios2">
			<tbody>
			<?php  $_smarty_tpl->tpl_vars['mpedido'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mpedido']->_loop = false;
 $_smarty_tpl->tpl_vars['m'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['mpedidos']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['mpedido']->key => $_smarty_tpl->tpl_vars['mpedido']->value) {
$_smarty_tpl->tpl_vars['mpedido']->_loop = true;
 $_smarty_tpl->tpl_vars['m']->value = $_smarty_tpl->tpl_vars['mpedido']->key;
?>
				<tr class="head">
					<td>Codigo ASM: <?php echo $_smarty_tpl->tpl_vars['mpedido']->value['codigo_envio'];?>
</td>
					<td>Referencia: <?php echo $_smarty_tpl->tpl_vars['mpedido']->value['reference'];?>
</td>
					<td>Total: <?php echo $_smarty_tpl->tpl_vars['mpedido']->value['total_paid_real'];?>
</td>
				</tr>
				<tr>
					<td colspan="3">
						Información de la entrega: 
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<?php echo $_smarty_tpl->tpl_vars['mpedido']->value['firstname'];?>
 <?php echo $_smarty_tpl->tpl_vars['mpedido']->value['lastname'];?>
 <?php echo $_smarty_tpl->tpl_vars['mpedido']->value['company'];?>

					</td>
				</tr>
				<tr>
					<td colspan="3">
						<?php echo $_smarty_tpl->tpl_vars['mpedido']->value['address1'];?>
 <?php echo $_smarty_tpl->tpl_vars['mpedido']->value['address2'];?>

					</td>
				</tr>
				<tr>
					<td colspan="3">
						<?php echo $_smarty_tpl->tpl_vars['mpedido']->value['postcode'];?>
 - <?php echo $_smarty_tpl->tpl_vars['mpedido']->value['city'];?>
 (<?php echo $_smarty_tpl->tpl_vars['mpedido']->value['statename'];?>
)
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<?php if ($_smarty_tpl->tpl_vars['mpedido']->value['phone_mobile']) {?><?php echo $_smarty_tpl->tpl_vars['mpedido']->value['phone_mobile'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['mpedido']->value['phone'];?>
<?php }?>
					</td>
				</tr>
				<tr class="separator">
					<td colspan="3">
					</td>
				</tr>
				<?php } ?>
			</tbody>
			</table>
		</div>
    </div>
    </div>
<?php } else { ?>
    <div class="alert alert-warning">
        <h3>No hay ordenes de pedido para ASM</h3>
    </div>
<?php }?>
</div>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['module_base']->value;?>
js/jQuery.print.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['module_base']->value;?>
js/tableExport.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['module_base']->value;?>
js/jquery.base64.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['module_base']->value;?>
js/jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['module_base']->value;?>
js/jspdf/jspdf.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['module_base']->value;?>
js/jspdf/jspdf.plugin.addimage.js"></script>
<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['module_base']->value;?>
js/jspdf/libs/base64.js"></script><?php }} ?>
