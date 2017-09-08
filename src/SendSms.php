<?php

namespace Miaoqi\SendSms;

class Sms 
{
	/**
     * 请求地址
     *
     * @var string
     */
	private $sendUrl;

	/**
     * 账户
     *
     * @var string
     */
	private $account;

	/**
     * 密码
     *
     * @var string
     */
	private $password;

    public function __construct()
    {
    	$curConfigs = file_exists(config_path('sendsms.php')) ? require(config_path('sendsms.php')) : require(__DIR__ . '/config/default.php');
    	$this->sendUrl = $curConfigs['api_send_url'];
    	$this->account = $curConfigs['api_account'];
    	$this->password = $curConfigs['api_password'];
    }

	/**
	 * 发送短信
	 *
	 * @param string $mobile 	  手机号码
	 * @param string $msg 		  短信内容
	 * @param string $needstatus  是否需要状态报告
	 */
	public function sendSMS($mobile, $msg, $needstatus = 1) 
	{	
		$postArr = array(
				'un' => $this->account,
				'pw' => $this->password,
		        'msg' => $msg,
		        'phone' => $mobile,
		        'rd' => $needstatus
			);
		$result = $this->curlPost($this->sendUrl, $postArr);
		$result = preg_split("/[,\r\n]/", $result);
		return $result;
	}

	/**
	 * 通过CURL发送HTTP请求
	 * 
	 * @param  string  $url  
	 * @param  array   $postFields 
	 * @return mixed
	 */
	private function curlPost($url, $postFields)
	{
		$postFields = http_build_query($postFields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}

?>