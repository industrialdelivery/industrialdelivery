<?php
/**
 * Leo Slideshow Module
 * 
 * @version		$Id: file.php $Revision
 * @package		modules
 * @subpackage	$Subpackage.
 * @copyright	Copyright (C) September 2012 LeoTheme.Com <@emai:leotheme@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
 */
 
/**
 * @since 1.5.0
 * @version 1.2 (2012-03-14)
 */

if (!defined('_PS_VERSION_'))
	exit;
		
		$this->_html .= '<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">';
		/* Save */
		$this->_html .= '
		<div class="margin-form">
			<input type="submit" class="button" name="submitSlider" value="'.$this->l('Save').'" />
		</div>';
		$this->_html .= '
				<fieldset><legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('General configuration').'</legend>';
				
		
		$this->_html .= $this->getParams()->getThemesTag( $this->getParams()->get('theme') );
		
		
		$this->_html .= $params->inputTag( $this->l('Slider Image Width:'), 'imgwidth', $this->getParams()->get('imgwidth'), 'px',' size="50" ' );	
		$this->_html .= $params->inputTag( $this->l('Slider Image Height:'), 'imgheight', $this->getParams()->get('imgheight'), 'px',' size="50" ' );	

		$this->_html .= $params->inputTag( $this->l('Thumbnail Width:'), 'thumbwidth', $this->getParams()->get('thumbwidth'), 'px',' size="50" ' );	
		$this->_html .= $params->inputTag( $this->l('Thumbnail Height:'), 'thumbheight', $this->getParams()->get('thumbheight'), 'px',' size="50" ' );	
		
		$this->_html .= $params->statusTag( $this->l('Show Thumbnail Navigator:'), 'image_navigator', $this->getParams()->get('image_navigator',1), 'image_navigator' );
		$this->_html .= $params->statusTag( $this->l('Auto:'), 'auto', $this->getParams()->get('auto',1), 'auto' );
		$this->_html .= $params->inputTag( $this->l('Delay:'), 'delay', $this->getParams()->get('delay'), '',' size="50" ' );
		$this->_html .= '</fieldset>';
		
		// source configuration
		$this->_html .= '<br><br><fieldset class="clearfix"><legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('Data Source Configuration').'</legend>';
		$this->_html .= $this->getParams()->getSourceDataTag( $this->getParams()->get('source') );	
		
		$this->_html .= '</fieldset>';	


	/* Begin fieldset slides */
		$this->_html .= '<br><br>
		<fieldset class="source-group sourceimages">
			<legend><img src="'._PS_BASE_URL_.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" alt="" /> '.$this->l('Slides configuration').'</legend>
			<strong>
				<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&addSlide">
					<img src="'._PS_ADMIN_IMG_.'add.gif" alt="" /> '.$this->l('Add Slide').'
				</a>
			</strong>';

		/* Display notice if there are no slides yet */
		if (!$slideminis)
			$this->_html .= '<p style="margin-left: 40px;">'.$this->l('You have not added any slides yet.').'</p>';
		else /* Display slides */
		{
			$this->_html .= '
			<div id="slidesContent" style="width: 400px; margin-top: 30px;">
				<ul id="slides">';

			foreach ($slideminis as $slidemini)
			{
				$this->_html .= '
					<li id="slides_'.$slidemini['id_slide'].'">
						<strong>#'.$slidemini['id_slide'].'</strong> '.$slidemini['title'].'
						<p style="float: right">'.
							$this->displayStatus($slidemini['id_slide'], $slidemini['active']).'
							<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&id_slide='.(int)($slidemini['id_slide']).'" title="'.$this->l('Edit').'"><img src="'._PS_ADMIN_IMG_.'edit.gif" alt="" /></a>
							<a href="'.AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&delete_id_slide='.(int)($slidemini['id_slide']).'" title="'.$this->l('Delete').'"><img src="'._PS_ADMIN_IMG_.'delete.gif" alt="" /></a>
						</p>
					</li>';
			}
			$this->_html .= '</ul></div>';
		}
		// End fieldset
		$this->_html .= '</fieldset>';
		
	/* Save */
		$this->_html .= '<br /><br />';
		$this->_html .= '
		<div class="margin-form">
			<input type="submit" class="button" name="submitSlider" value="'.$this->l('Save').'" />
		</div>';
	$this->_html .= '</form><br><br>';

	
?>