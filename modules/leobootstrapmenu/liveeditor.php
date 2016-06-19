<?php
/**
 *  Leo Theme for Prestashop 1.6.x
 *
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 */

$module_row = 0;

# validate module
$liveedit_action = true ? $liveedit_action : null;
$widgets = true ? $widgets : null;
$live_site_url = true ? $live_site_url : null;
$action_backlink = true ? $action_backlink : null;
$ajxgenmenu = true ? $ajxgenmenu : null;
$ajxmenuinfo = true ? $ajxmenuinfo : null;
$action_widget = true ? $action_widget : null;
$id_shop = true ? $id_shop : null;
$this_module = true ? $this_module : null;

?>
<?php if (1)
{ ?>

	<style type="text/css">
		#page-content{
			min-height: 1200px;
			width: 100%;
			padding-bottom: 100px
		}
	</style>

	<div id="page-content">
		<div id="menu-form"  style="display: none; left: 340px; top: 15px; max-width:600px" class="popover top out form-setting">
			<div class="arrow"></div>
			<div style="display: block;" class="popover-title">
				<?php echo $this_module->l('Sub Menu Setting'); ?>
				<span class="badge pull-right"><?php echo $this_module->l('Close');?></span>
			</div>
			<div class="popover-content"> 
				<form  method="post" action="<?php echo $liveedit_action; ?>"  enctype="multipart/form-data" >
					<div class="col-lg-12">	
						<table class="table table-hover">

							<tr>
								<td><?php echo $this_module->l('Create Submenu'); ?></td>
								<td>
									<select name="menu_submenu" class="menu_submenu">
										<option value="0"><?php echo $this_module->l('No'); ?></option>
										<option value="1"><?php echo $this_module->l('Yes'); ?></option>
									</select>
								</td>
							</tr>

							<tr>
								<td><?php echo $this_module->l('Submenu Width'); ?></td>
								<td>
									<input type="text" name="menu_subwidth" class="menu_subwidth"> 
								</td>
							</tr>
							<tr class="group-submenu">
								<td><?php echo $this_module->l('Group Submenu'); ?></td>
								<td>
									<div  id="submenu-form" >								
										<input type="hidden" name="submenu_id">
										<select name="submenu_group" class="submenu_group">
											<option value="0"><?php echo $this_module->l('No'); ?></option>
											<option value="1"><?php echo $this_module->l('Yes'); ?></option>
										</select>	
									</div>
								</td>
							</tr>
							<tr class="aligned-submenu">
								<td><?php echo $this_module->l('Align Submenu'); ?></td>
								<td>
									<div class="btn-group button-aligned">
										<button type="button" class="btn btn-default" data-option="aligned-left"><span class="icon icon-align-left"></span></button>
										<button type="button" class="btn btn-default" data-option="aligned-center"><span class="icon icon-align-center"></span></button>
										<button type="button" class="btn btn-default" data-option="aligned-right"><span class="icon icon-align-right"></span></button>
										<button type="button" class="btn btn-default" data-option="aligned-fullwidth"><span class="icon icon-align-justify"></span></button>
									</div>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<button type="button" class="add-row btn btn-success btn-sm"><?php echo $this_module->l('Add Row'); ?></button>
									<button type="button" class="remove-row btn btn-default  btn-sm"><?php echo $this_module->l('Remove Row'); ?></button>
									| <button type="button" class="add-col btn btn-success  btn-sm"><?php echo $this_module->l('Add Column'); ?></button>
								</td>
							</tr>
						</table>
						<input type="hidden" name="menu_id">
					</div>

				</form>
			</div>
		</div>


		<div id="column-form" style="display: none; left: 340px; top: 45px;" class="popover top   form-setting">
			<div class="arrow"></div>
			<div style="display: block;" class="popover-title">
				<?php echo $this_module->l('Column Setting'); ?> 
				<span class="badge pull-right"><?php echo $this_module->l('Close'); ?></span>
			</div>
			<div class="popover-content"> 
				<form    method="post" action="<?php echo $liveedit_action; ?>"  enctype="multipart/form-data" >
					<table class="table table-hover">
						<tr>
							<td><?php echo $this_module->l('Addition Class'); ?></td>
							<td>
								<input type="text" name="colclass"> 
							</td>
						</tr>
						<tr>
							<td><?php echo $this_module->l('Column Width'); ?></td>
							<td>
								<select class="colwidth" name="colwidth">
									<?php for ($i = 1; $i <= 12; $i++)
										{
										# validate module
									?>
										<option value="<?php echo $i; ?>">
											<?php echo $i; ?>
										</option>
									<?php 
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2">	<button type="button" class="remove-col btn btn-default  btn-sm"><?php echo $this_module->l('Remove Column'); ?></button> </td>
						</tr>	
					</table>
				</form>
			</div>
		</div>

		<div  id="widget-form" style="display: none; left: 340px;  min-width:400px" class="popover bottom   form-setting">
			<div class="arrow"></div>
			<div style="display: block;" class="popover-title">
				<?php echo $this_module->l('Widget Setting'); ?>
				<span class="badge pull-right"><?php echo $this_module->l('Close'); ?></span>
			</div>
			<div class="popover-content"> 
					<?php if (!empty($widgets))
					{ ?>
					<select name="inject_widget" class="inject_widget"> 
						<option value=""><?php echo $this_module->l(''); ?></option>
		<?php foreach ($widgets as $w)
		{
			#validate module
			?>
							<option value="<?php echo $w['key_widget']; ?>">
								<?php echo $w['name']; ?>
							</option>
		<?php
		}
		?>
					</select>
					<button type="button" id="btn-inject-widget" class="btn btn-primary btn-sm"><?php echo $this_module->l('Insert'); ?></button>
	<?php
	}
	?>
			</div>
		</div>

		<div id="content-s">
			<div class="container">
				<div class="page-header">
					<h1 ><?php echo $this_module->l('Live Megamenu Editor'); ?></h1>
				</div>

				<div class="bs-example">
					<div class="alert alert-danger fade in">
						<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
						<strong><?php echo $this_module->l('By using this tool, allow to create sub menu having multiple rows and  multiple columns. You can inject widgets inside columns or group sub menus in same level of parent.Note: Some configurations as group, columns width setting will be overrided'); ?></strong>  
					</div>
				</div>

			</div>
			<div id="pav-megamenu-liveedit">

				<div id="toolbar" class="container">
					<div id="menu-toolbars">

						<div>
							<div class="pull-right">
								<a   href="<?php echo Context::getContext()->link->getAdminLink('AdminLeotempcpWidgets').'&addleowidgets'; ?>&widgets=1" class="leo-modal-action btn btn-modeal btn-success btn-action">
									<?php echo $this_module->l('Create A Widget'); ?></a>
								- 
								<a   href="<?php echo $live_site_url; ?>" class="btn btn-modal btn-primary btn-sm btn-action" >
									<?php echo $this_module->l('Preview On Live Site'); ?></a> | 
								<a id="unset-data-menu" href="#" class="btn btn-danger btn-action"><?php echo $this_module->l('Reset Configuration'); ?></a>
								<button id="save-data-menu" class="btn btn-warning"><?php echo $this_module->l('Save'); ?></button>
							</div>
							<a id="save-data-back" class="btn btn-default" href="<?php echo $action_backlink; ?>">
								<?php echo $this_module->l('Back'); ?>
							</a>
						</div>

					</div>
				</div>

				<div class="container"><div class="megamenu-wrap">
						<div class="progress" id="leo-progress">
							<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 00%;">
								<span class="sr-only">60% Complete</span>
							</div>
						</div>
						<div id="megamenu-content">
						</div></div>
				</div>

			</div>
		</div>
	</div>



	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?php echo $this_module->l('Preview On Live Site'); ?></h4>
				</div>
				<div class="modal-body">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this_module->l('Close'); ?></button>
				</div>
			</div> 
		</div> 
	</div> 


	<script type="text/javascript">
		$(".btn-modal").click(function() {
			$('#myModal .modal-dialog ').css('width', 980);
			$('#myModal .modal-dialog ').css('height', 480);
			var a = $('<span class="glyphicon glyphicon-refresh"></span><iframe src="' + $(this).attr('href') + '" style="width:100%;height:100%; display:none"/>');
			$('#myModal .modal-body').html(a);

			$('#myModal').modal( );
			$('#myModal').attr('rel', $(this).attr('rel'));
			$(a).load(function() {

				$('#myModal .modal-body .glyphicon-refresh').hide();
				$('#myModal .modal-body iframe').show();
			});
			return false;
		});

		$('#myModal').on('hidden.bs.modal', function() {
			if ($(this).attr('rel') == 'refresh-page') {
				location.reload();
			}
		})


		var _action = '<?php echo str_replace('&amp;', '&', $ajxgenmenu); ?>';
		var _action_menu = '<?php echo str_replace('&amp;', '&', $ajxmenuinfo); ?>';
		var _action_widget = '<?php echo str_replace('&amp;', '&', $action_widget); ?>';
		var _id_shop = '<?php echo $id_shop; ?>';
		$("#megamenu-content").PavMegamenuEditor({'action': _action, 'action_menu': _action_menu, 'action_widget': _action_widget, 'id_shop': _id_shop});

	</script>
<?php
}
?>