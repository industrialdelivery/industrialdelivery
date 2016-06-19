<?php
/**
 *    This file is part of Mobile Assistant Connector.
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

require_once '../../../config/config.inc.php';
include_once 'functions.php';

$key      = Tools::getValue('key');
$function = Tools::getValue('call_function');
$push_ids = Tools::getValue('push_ids');
$value    = Tools::getValue('value');

if (!isAuthenticated($key))
	die(Tools::jsonEncode('Authentication error'));

if ($function && function_exists($function))
{
	if ($function == 'changeStatus')
		echo changeStatus($push_ids, $value);
	elseif ($function == 'deleteDevice')
		echo deleteDevice($push_ids);
	else
		echo call_user_func($function);
}
else
	die(Tools::jsonEncode('error'));

function isAuthenticated($key)
{
	$login_data = unserialize(Configuration::get('MOBASSISTANTCONNECTOR'));

	if (hash('sha256', $login_data['login'].$login_data['password']._COOKIE_KEY_) == $key)
		return true;

	return false;
}

function getDevices()
{
	$devices_obj = new DbQuery();
	$devices_obj->select('
		mpn.`id`,
		mpn.`new_order`,
		mpn.`new_customer`,
		mpn.`order_statuses`,
		mpn.`id_shop`,
		mpn.`app_connection_id`,
		mpn.`status`,
		mpn.`device_unique_id`,
		md.`account_email`,
		md.`device_name`,
		md.`last_activity`,
		c.`iso_code` AS currency_iso
	');
	$devices_obj->from('mobassistantconnector_push_notifications', 'mpn');
	$devices_obj->leftJoin('mobassistantconnector_devices', 'md', 'md.`device_unique_id` = mpn.`device_unique_id`');
	$devices_obj->leftJoin('currency', 'c', 'c.`id_currency` = mpn.`currency_code`');
	$devices_sql = $devices_obj->build();
	$devices = Db::getInstance()->executeS($devices_sql);

	if (!$devices)
		$devices = array();

	$devices        = replaceNull($devices);
	$statuses_db    = OrderState::getOrderStates(Configuration::get('PS_LANG_DEFAULT'));
	$count_statuses = count($statuses_db);
	$statuses       = array();

	for ($i = 0; $i < $count_statuses; $i++)
		$statuses[$statuses_db[$i]['id_order_state']] = $statuses_db[$i]['name'];

	$devices = formDevices($devices, $statuses);

	return Tools::jsonEncode($devices);
}

function changeStatus($ids, $value)
{
	$ids = prepareIds($ids);

	if (!$ids)
		return Tools::jsonEncode('Parameters are incorrect');

	$result = Db::getInstance()->update('mobassistantconnector_push_notifications', array('status' => (int)$value), '`id` IN ('.$ids.')');

	if ($result)
		return Tools::jsonEncode('success');

	return Tools::jsonEncode('Some error occurred');
}

function deleteDevice($ids)
{
	$ids = prepareIds($ids);

	if (!$ids)
		return Tools::jsonEncode('Parameters are incorrect');

	$result = Db::getInstance()->delete('mobassistantconnector_push_notifications', '`id` IN ('.$ids.')');
	deleteEmptyDevices();

	if ($result)
		return Tools::jsonEncode('success');

	return Tools::jsonEncode('Some error occurred');
}

function formDevices($devices, $statuses)
{
	$count_devices  = count($devices);
	$devices_output = array();

	for ($i = 0; $i < $count_devices; $i++)
	{
		$device_unique = !$devices[$i]['device_unique_id'] ? 'Unknown' : $devices[$i]['device_unique_id'];

		if ($devices[$i]['order_statuses'])
		{
			if ((int)$devices[$i]['order_statuses'] == -1)
				$devices[$i]['order_statuses'] = 'All';
			else
			{
				$push_statuses       = explode('|', $devices[$i]['order_statuses']);
				$count_push_statuses = count($push_statuses);
				$view_statuses       = array();

				for ($j = 0; $j < $count_push_statuses; $j++)
				{
					if (isset($statuses[$push_statuses[$j]]))
						$view_statuses[] = $statuses[$push_statuses[$j]];
				}

				$devices[$i]['order_statuses'] = implode(', ', $view_statuses);
			}
		}

		if ($devices[$i]['id_shop'] > 0)
		{
			$shop = new Shop($devices[$i]['id_shop']);
			$devices[$i]['shop'] = $shop->name;
		}
		else
			$devices[$i]['shop'] = 'All';

		if ($devices[$i]['last_activity'] == '0000-00-00 00:00:00')
			$devices[$i]['last_activity'] = '';

		if ($device_unique == 'Unknown')
			$devices[$i]['device_name'] = 'Unknown';

		$devices_output[$device_unique]['device_name']   = !$devices[$i]['device_name'] ? '-' : $devices[$i]['device_name'];
		$devices_output[$device_unique]['account_email'] = !$devices[$i]['account_email'] ? '-' : $devices[$i]['account_email'];
		$devices_output[$device_unique]['last_activity'] = !$devices[$i]['last_activity'] ? '-' : $devices[$i]['last_activity'];
		$devices_output[$device_unique]['pushes'][]      = array(
			'id'                => $devices[$i]['id'],
			'new_order'         => $devices[$i]['new_order'],
			'new_customer'      => $devices[$i]['new_customer'],
			'order_statuses'    => !$devices[$i]['order_statuses'] ? '-' : $devices[$i]['order_statuses'],
			'shop'              => $devices[$i]['shop'],
			'app_connection_id' => $devices[$i]['app_connection_id'],
			'currency_iso'      => !$devices[$i]['currency_iso'] ? '-' : $devices[$i]['currency_iso'],
			'status'            => $devices[$i]['status'],
		);
	}

	return $devices_output;
}

function replaceNull($data)
{
	if (!is_array($data))
		$data = array();

	foreach ($data as $index => $values)
	{
		foreach ($values as $key => $value)
		{
			if ($value === null)
				$data[$index][$key] = '';
		}
	}

	return $data;
}

function prepareIds($data)
{
	if (!$data)
		return false;

	$ids   = array();
	$arr   = explode(',', $data);
	$count = count($arr);

	for ($i = 0; $i < $count; $i++)
		$ids[] = (int)trim($arr[$i]);

	return implode(',', $ids);
}