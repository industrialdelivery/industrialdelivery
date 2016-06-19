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

class MobassistantconnectorAccess
{
	const HASH_ALGORITHM     = 'sha256';
	const MAX_LIFETIME       = 86400; /* 24 hours */
	const TABLE_SESSION_KEYS = 'mobassistantconnector_session_keys';
	const TABLE_FAILED_LOGIN = 'mobassistantconnector_failed_login';

	public static function clearOldData()
	{
		$timestamp       = time();
		$date_clear_prev = Configuration::get('MOBASSISTANTCONNECTOR_CL_DATE');
		$date            = date('Y-m-d H:i:s', ($timestamp - self::MAX_LIFETIME));

		if ($date_clear_prev === false || ($timestamp - (int)$date_clear_prev) > self::MAX_LIFETIME)
		{
			Db::getInstance()->delete(self::TABLE_SESSION_KEYS, "`date_added` < '".pSQL($date)."'");
			Db::getInstance()->delete(self::TABLE_FAILED_LOGIN, "`date_added` < '".pSQL($date)."'");
			Configuration::updateValue('MOBASSISTANTCONNECTOR_CL_DATE', $timestamp);
		}
	}

	public static function getSessionKey($hash)
	{
		$login_data = unserialize(Configuration::get('MOBASSISTANTCONNECTOR'));

		if (hash(self::HASH_ALGORITHM, $login_data['login'].$login_data['password']) == $hash)
			return self::generateSessionKey($login_data['login']);
		else
			self::addFailedAttempt();

		return false;
	}

	public static function checkSessionKey($key)
	{
		$timestamp = time();
		$db_key = Db::getInstance()->getValue('SELECT `session_key` FROM `'._DB_PREFIX_.self::TABLE_SESSION_KEYS."` WHERE `session_key` = '"
			.pSQL($key)."' AND `date_added` > '".pSQL(date('Y-m-d H:i:s', ($timestamp - self::MAX_LIFETIME)))."'");

		if ($db_key)
			return true;
		else
			self::addFailedAttempt();

		return false;
	}

	private static function generateSessionKey($username)
	{
		$timestamp = time();
		$key = hash(self::HASH_ALGORITHM, _COOKIE_KEY_.$username.$timestamp);
		Db::getInstance()->insert(self::TABLE_SESSION_KEYS, array('session_key' => $key, 'date_added' => date('Y-m-d H:i:s', $timestamp)));

		return $key;
	}

	public static function addFailedAttempt()
	{
		$timestamp = time();
		Db::getInstance()->insert(self::TABLE_FAILED_LOGIN, array('ip' => $_SERVER['REMOTE_ADDR'], 'date_added' => date('Y-m-d H:i:s', $timestamp)));

		// Get count of failed attempts for last 24 hours and set delay
		$count_failed_attempts = Db::getInstance()->getValue('SELECT COUNT(`ip`) FROM `'._DB_PREFIX_.self::TABLE_FAILED_LOGIN
			."` WHERE `ip` = '".pSQL($_SERVER['REMOTE_ADDR'])."' AND `date_added` > '".pSQL(date('Y-m-d H:i:s', ($timestamp - self::MAX_LIFETIME)))."'");
		self::setDelay((int)$count_failed_attempts);
	}

	private static function setDelay($count_attempts)
	{
		if ($count_attempts <= 10)
			sleep(1);
		elseif ($count_attempts <= 20)
			sleep(2);
		elseif ($count_attempts <= 50)
			sleep(5);
		else
			sleep(10);
	}

}