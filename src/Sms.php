<?php

namespace Miaoqi\SendSms;

class Sms
{
	public static function createObject($agent)
	{
		switch ($agent) {
			case 'RongLian':
				if (include_once("./RongLian.php")) {
					$object = new RongLian();
				}
				break;
			
			case 'ChuangLan':
				if (include_once("./ChuangLan.php")) {
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