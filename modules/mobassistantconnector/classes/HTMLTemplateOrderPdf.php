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

class HTMLTemplateOrderPdf extends HTMLTemplate
{
	public $order;
	public $available_in_your_account = false;

	public function __construct($order, $smarty)
	{
		$this->order = $order;
		$this->smarty = $smarty;
		$this->title = 'Order #'.$this->order->id;

		// header informations
		$this->date = Tools::displayDate($order->date_add, null);

		// footer informations
		$this->shop = new Shop((int)$this->order->id_shop);
	}

	public function getContent()
	{
		$invoice_address = new Address((int)$this->order->id_address_invoice);
		$formatted_invoice_address = AddressFormat::generateAddress($invoice_address, array(), '<br />', ' ');
		$formatted_delivery_address = '';

		if ($this->order->id_address_delivery != $this->order->id_address_invoice)
		{
			$delivery_address = new Address((int)$this->order->id_address_delivery);
			$formatted_delivery_address = AddressFormat::generateAddress($delivery_address, array(), '<br />', ' ');
		}

		$customer = new Customer((int)$this->order->id_customer);
		$carrier = new Carrier((int)$this->order->id_carrier);
		$data = array(
			'order' => $this->order,
			'order_details' => $this->order->getProducts(),
			'delivery_address' => $formatted_delivery_address,
			'invoice_address' => $formatted_invoice_address,
			'tax_excluded_display' => Group::getPriceDisplayMethod($customer->id_default_group),
			'customer' => $customer,
			'carrier' => $carrier,
		);
		$this->smarty->assign($data);
		$order_pdf_tpl = _PS_MODULE_DIR_.'/mobassistantconnector/views/templates/front/order_pdf.tpl';

		return $this->smarty->fetch($order_pdf_tpl);
	}

	public function getFilename()
	{
		return 'pdf_order_'.$this->order->id.'.pdf';
	}

	public function getBulkFilename()
	{
	}

}