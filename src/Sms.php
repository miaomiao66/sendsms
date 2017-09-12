<?php

namespace Miaoqi\SendSms;

class Sms
{
	public static function createObject()
	{
		$curConfigs = file_exists(config_path('sendsms.php')) ? require(config_path('sendsms.php')) : require(__DIR__ . '/config/default.php');
		$agent = $curConfigs['default'];
		switch ($agent) {
			case 'RongLian':
				if (include_once(__DIR__ . "/RongLian.php")) {
					$object = new RongLian();
				}
				break;
			
			case 'ChuangLan':
				if (include_once(__DIR__ . "/ChuangLan.php")) {
					$object = new ChuangLan();
				}
				break;

			default:
				$object = '参数错误';
				break;
		}
		return $object;
	}
}



?>