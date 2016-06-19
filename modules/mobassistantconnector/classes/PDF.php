<?php
/**
 *	This file is part of Mobile Assistant Connector.
 *
 *   Mobile Assistant Connector is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Mobile Assistant Connector is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Mobile Assistant Connector.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  @author    eMagicOne <contact@emagicone.com>
 *  @copyright 2014-2015 eMagicOne
 *  @license   http://www.gnu.org/licenses   GNU General Public License
 */

class PDFMob extends PDFCore {
	public $lang_iso_code;

	public function render($display = 'D')
	{
		$render = false;
		$this->pdf_renderer->setFontForLang($this->lang_iso_code);

		foreach ($this->objects as $object)
		{
			$template = $this->getTemplateObject($object);

			if (!$template)
				break;

			if (empty($this->filename))
				$this->filename = $template->getFilename();

			$this->pdf_renderer->createHeader($template->getHeader());
			$this->pdf_renderer->createFooter($template->getFooter());
			$this->pdf_renderer->createContent($template->getContent());
			$this->pdf_renderer->writePage();
			$render = true;
			unset($template);
		}

		if ($render)
		{
			$this->pdf_renderer->output($this->filename, $display);
			return true;
		}

		return false;
	}
}