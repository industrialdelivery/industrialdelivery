<?php
if (!defined('_CAN_LOAD_FILES_'))
	exit;

?>
<script type="text/javascript">
	var id_language = <?php echo $this->_defaultFormLanguage;?>;
</script>
<link rel="stylesheet" href="<?php echo __PS_BASE_URI__."modules/".$this->name."/assets/admin/form.css";?>" type="text/css" media="screen" charset="utf-8" />
<?php
if(_PS_VERSION_ < "1.5"){
?>
	<script type="text/javascript" src="../js/jquery/jquery.tablednd_0_5.js"></script>
	<script src="<?php echo __PS_BASE_URI__;?>js/jquery/jquery.fancybox-1.3.4.js" type="text/javascript"></script>	
<?php
}else{
?>
<?php
	$context = Context::getContext();
	$context->controller->addjQueryPlugin(array(
		'fancybox',
		'tablednd',
	));
}
?>
<script type="text/javascript" src="<?php echo __PS_BASE_URI__."modules/".$this->name."/assets/admin/form.js";?>"></script>
<script type="text/javascript" src="<?php echo __PS_BASE_URI__."modules/".$this->name."/assets/admin/dragdrop.js";?>"></script>
<script type="text/javascript">
	LofBlocksDnD('<?php echo $this->secure_key;?>');
</script>

<script type="text/javascript">
	$(document).ready( function(){
		$("#lofform").delegate(".lofaddnew-block-item", 'hover' ,function(){ 
			$(this).fancybox({
				"width"		: 980,
				"height"	: 550,	
				'type'		: 'iframe',
				"scrolling" : "yes",
				'titleShow'		: false
			});
		});
	});
</script>
<?php
	global $cookie,$employee;
	$yesNoLang = array("0"=>$this->l('No'),"1"=>$this->l('Yes'));
?>
<h3><?php echo $this->l('LOF Advance Footer Configuration');?></h3>
<div id="lofform">
<?php 
	global $lofPosition;
	$divLangName = '';
	foreach($lofPosition as $id_position){
		$blocks = LofBlock::getBlocks($id_position, $cookie->id_lang);
		if($blocks){
			foreach($blocks as $key=>$b){
				$divLangName .= 'title'.$b['id_loffc_block'].'¤';
			}
		}
	}
	$divLangName = rtrim($divLangName,'¤');
	foreach($lofPosition as $id_position){
		$blocks = LofBlock::getBlocks($id_position, $cookie->id_lang);
?>
	<fieldset class="loffieldset" id="lofposition-<?php echo $id_position;?>">
		<legend><img src="../img/admin/contact.gif" /><?php echo $this->l('Position').' '.$id_position; ?></legend>
	<?php if(count($blocks) < LOF_MODULE_ADVANCE_CUSTOM_LIMIT_BLOCK){ ?>
		<a onclick="return false;" class="lofaddnew-block-item lofaddnew-block-<?php echo $id_position;?>" href="<?php echo $link->getModuleLink("lofadvancecustom","popup",array('id_shop'=>(int)($this->context->shop->id),'id_position'=>$id_position,'addBlock'=>1,'bo_theme'=>$employee->bo_theme,'id_divposition'=>'lofposition-'.$id_position,'nb'=>count($blocks),'token'=>Tools::getValue('token'),'secure_key'=>$this->secure_key));?>" title="add"><img border="0" src="../img/admin/add.gif"/><?php echo $this->l('Add new block');?></a><br/>
	<?php
	}
	if($blocks){
		foreach($blocks as $key=>$b){
			$items = LofBlock::getItems($b['id_loffc_block'], $cookie->id_lang);
			$obj = new LofBlock($b['id_loffc_block']);
			echo ($key%3 == 0 ? '<div class="clear">&nbsp;</div>' : '');
		?>
			<div class="lof-block <?php echo ($key%3 == 2 ? 'lof-block-right' : '');?>">
				<form id="form-<?php echo $b['id_loffc_block'];?>" action="<?php echo $this->base_config_url.'&rand='.rand();?>" method="post" enctype="multipart/form-data">
					<div class="lof-load" style="display:none;"></div>
					<a class="lof-delete-block" style="display:none;" href="<?php echo $this->base_config_url.'&submitDeleteBlock&id_loffc_block='.$b['id_loffc_block'];?>">&nbsp;</a>
					<input type="hidden" value="<?php echo $b['id_loffc_block'];?>" name="id_loffc_block"/>
					<label><?php echo $this->l('Title:');?></label>
					<div class="margin-form">
					<?php
					foreach($this->_languages as $language)
						echo '<div id="title'.$b['id_loffc_block'].'_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $this->_defaultFormLanguage ? 'block' : 'none').'; float: left;">
							<input size="30" type="text" name="title_'.$language['id_lang'].'" value="'.htmlentities((isset($obj->{'title'}[(int)($language['id_lang'])]) ? $obj->{'title'}[(int)($language['id_lang'])] : ''), ENT_COMPAT, 'UTF-8').'" /><sup> *</sup>
						</div>';
					$this->displayFlags($this->_languages, $this->_defaultFormLanguage, $divLangName, 'title'.$b['id_loffc_block']);
					?>
					</div>
					<div class="clear space"></div>
					<label><?php echo $this->l('Show Title:');?></label>
					<div class="margin-form">
						<input type="radio" name="show_title" id="show_title_on" onclick="toggleDraftWarning(false);" value="1" <?php echo ($b['show_title'] ? 'checked="checked" ' : '');?>/>
						<label class="t" for="show_title_on"> <img src="../img/admin/enabled.gif" alt="<?php echo $this->l('Enabled');?>" title="<?php echo $this->l('Enabled');?>" /></label>
						<input type="radio" name="show_title" id="show_title_off" onclick="toggleDraftWarning(true);" value="0" <?php echo (!$b['show_title'] ? 'checked="checked" ' : '');?>/>
						<label class="t" for="show_title_off"> <img src="../img/admin/disabled.gif" alt="<?php echo $this->l('Disabled');?>" title="<?php echo $this->l('Disabled');?>" /></label>
					</div>
					<label><?php echo $this->l('Width:');?></label>
					<div class="margin-form">
						<input type="text" value="<?php echo $b['width'];?>" name="width" size="10" class="lof-width"/> <span>%</span>
						<p class="clear"><?php echo $this->l('enter a number < 100');?></p>
					</div>
					<div class="margin-form">
					<a href="javascript:void(0)" class="button" onclick="submitForm('form-<?php echo $b['id_loffc_block'];?>')"><?php echo $this->l('Update');?></a></div>
				</form>
				<a onclick="return false;" class="lofaddnew-block-item" href="<?php echo $link->getModuleLink("lofadvancecustom","popup",array('id_shop'=>(int)($this->context->shop->id),'id_loffc_block'=>$b['id_loffc_block'],'addItem'=>1,'bo_theme'=>$employee->bo_theme,'id_loftable'=>'loftable-'.$b['id_loffc_block'],'token'=>Tools::getValue('token'),'secure_key'=>$this->secure_key));?>" title="add"><img border="0" src="../img/admin/add.gif"/><?php echo $this->l('Add new item');?></a><br/>
				<table class="table loftable tableDnD" id="loftable-<?php echo $b['id_loffc_block'];?>">
					<thead>
					<tr class="nodrag nodrop">
						<th width="60%"><?php echo $this->l('Title');?></th>
						<th width="30%"><?php echo $this->l('Type');?></th>
						<th width="10%"><?php echo $this->l('Action');?></th>
					</tr>
					</thead>
					<tbody>
					<?php
					if($items){
						foreach($items as $key=>$item){
					?>
						<tr id="tr_<?php echo $b['id_loffc_block'];?>_<?php echo $item['id_loffc_block_item'];?>_<?php echo $item['position'];?>" class="tr_0_<?php echo $item['id_loffc_block_item'];?>_<?php echo $b['id_loffc_block'];?> <?php echo ($key%2 ? '' : 'alt_row');?>">
							<td class="dragHandle"><?php echo $item['title'];?></td>
							<td class="dragHandle"><?php echo $item['type'];?></td>
							<td>
							<a class="display lofaddnew-block-item" href="<?php echo $link->getModuleLink("lofadvancecustom","popup",array('id_shop'=>(int)($this->context->shop->id),'id_loffc_block'=>$b['id_loffc_block'],'addItem'=>1,'bo_theme'=>$employee->bo_theme,'id_loffc_block_item'=>$item['id_loffc_block_item'],'id_loftr'=>'tr_0_'.$item['id_loffc_block_item'].'_'.$b['id_loffc_block'],'token'=>Tools::getValue('token'),'secure_key'=>$this->secure_key));?>" title="edit">
								<img title="edit" alt="edit" src="../img/admin/edit.gif">
							</a>
							<a class="display" onclick="LofDelete('tr_0_<?php echo $item['id_loffc_block_item'];?>_<?php echo $b['id_loffc_block'];?>','loftable-<?php echo $b['id_loffc_block'];?>')" href="javascript:void(0)" title="delete">
								<img title="delete" alt="delete" src="../img/admin/delete.gif">
							</a>
							</td>
						</tr>
					<?php
						}
					}
					?>
					</tbody>
				</table>
			</div>
		<?php
		}
	}
	?>
	</fieldset>
	<div class="clear space"></div>
<?php
}
?>
</div>
<form action="<?php echo $this->base_config_url.'&rand='.rand().'';?>" method="post" id="lofform" enctype="multipart/form-data">
	<fieldset>
		<legend><img src="../img/admin/contact.gif" /><?php echo $this->l('Advance Footer Setting'); ?></legend>
		<div class="lof_config_wrrapper clearfix">
			<ul>
				<?php
				//echo $this->getParamValue("theme",'white'); die;
				echo $this->_params->selectTag("theme",$themes,$this->getParamValue("theme",'white'),$this->l('Theme - Layout'),'class="inputbox"', 'class="row" title="'.$this->l('Select a theme').'"');
				echo $this->_params->inputTag("class",$this->getParamValue("class","customfooter"),$this->l('Module Class'),'','class="row"','');
				?>
			</ul>
		</div>
		<input type="submit" name="submit" value="<?php echo $this->l('Save');?>" class="button" />
	</fieldset>
	<?php
	if (Shop::isFeatureActive()){
		$shops = Shop::getShops();
		//echo "<pre>".print_r($shops,1); die;
		$shops_arr = array();
		foreach($shops as $shop){
			$shops_arr[$shop['id_shop']] = $shop['name'];
		}
	?>
	<fieldset>
		<legend><img src="../img/admin/contact.gif" /><?php echo $this->l('Delete data form shop'); ?></legend>
		<div class="lof_config_wrrapper clearfix">
			<ul>
				<?php
				//echo $this->getParamValue("theme",'white'); die;
				echo $this->_params->selectTag("shops[]", $shops_arr,'',$this->l('Choose shops:'),'class="inputbox" multiple="multiple" size="10"', 'class="row" title="'.$this->l('Select a theme').'"', '', $this->l('choose shop to delete all data'));
				?>
			</ul>
		</div>
		<input type="submit" name="submitDeleteData" value="<?php echo $this->l('Submit');?>" class="button" />
	</fieldset>
	<?php
		
	}
	?>
</form>
<div class="clear space"></div>
<fieldset><legend><img src="../img/admin/comment.gif" alt="" title="" /><?php echo $this->l('Information');?></legend>    	
	<ul>
		 <li>+ <a target="_blank" href="http://landofcoder.com/prestashop/lof-mega-menu.html"><?php echo $this->l('Detail Information');?></li>
		 <li>+ <a target="_blank" href="http://landofcoder.com/supports/forum.html?id=94"><?php echo $this->l('Forum support');?></a></li>
		 <li>+ <a target="_blank" href="http://landofcoder.com/submit-request.html"><?php echo $this->l('Customization/Technical Support Via Email');?>.</a></li>
		 <li>+ <a target="_blank" href="http://landofcoder.com/prestashop/guides/lof-mega-menu"><?php echo $this->l('UserGuide ');?></a></li>
	</ul>
	<br />
	@copyright: <a href="http://landofcoder.com">LandOfCoder.com</a>
</fieldset>