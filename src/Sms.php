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
		return $this->results()[$result[1]];
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

	/**
	 * 创蓝接口返回参数
	 * 
	 * @return array
	 */
	private function results()
	{
		return array(
			'0' =>'发送成功',
		    '101'=>'无此用户',
		    '102'=>'密码错',
		    '103'=>'提交过快',
		    '104'=>'系统忙',
		    '105'=>'敏感短信',
		    '106'=>'消息长度错',
		    '107'=>'错误的手机号码',
		    '108'=>'手机号码个数错',
		    '109'=>'无发送额度',
		    '110'=>'不在发送时间内',
		    '111'=>'超出该账户当月发送额度限制',
		    '112'=>'无此产品',
		    '113'=>'extno格式错',
		    '115'=>'自动审核驳回',
		    '116'=>'签名不合法，未带签名',
		    '117'=>'IP地址认证错',
		    '118'=>'用户没有相应的发送权限',
		    '119'=>'用户已过期',
		    '120'=>'内容不是白名单',
		    '121'=>'必填参数。是否需要状态报告，取值true或false',
		    '122'=>'5分钟内相同账号提交相同消息内容过多',
		    '123'=>'发送类型错误(账号发送短信接口权限)',
		    '124'=>'白模板匹配错误',
		    '125'=>'驳回模板匹配错误'
		);
	}
}


?>