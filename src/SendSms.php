<?php

class SendSms {

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

	/**
     * 配置信息
     *
     * @var array
     */
    protected static $configs;


    public function __construct()
    {
    	$curConfigs = self::$configs ?? require(dirname(__DIR__).'/config/default.php');
    	$this->sendUrl = self::getByKey($curConfigs, 'api_send_url');
    	$this->account = self::getByKey($curConfigs, 'api_account');
    	$this->password = self::getByKey($curConfigs, 'api_password');
    }

	/**
     * 设置自定义配置文件,格式参见config/default.php
     * 
     * @param string $filePath  设置的自定义文件的路径
     */
    public static function setConfigs(string $filePath)
    {
        if ($filePath && file_exists($filePath)) {
            self::$configs = require($filePath); 
        } else {
            throw new MissingException("Unable to load $filePath.");
        }
    }

	/**
	 * 发送短信
	 *
	 * @param string $mobile 		手机号码
	 * @param string $msg 			短信内容
	 * @param string $needstatus 	是否需要状态报告
	 */
	public function sendSMS( $mobile, $msg, $needstatus = 1) 
	{	
		$postArr = array (
				          'un' => $this->account,
				          'pw' => $this->password,
				          'msg' => $msg,
				          'phone' => $mobile,
				          'rd' => $needstatus
                     );
		
		$result = $this->curlPost($this->sendUrl , $postArr);
		$result = $this->execResult($result);
		return $result;
	}

	/**
	 * 通过CURL发送HTTP请求
	 * @param string $url  
	 * @param array $postFields 
	 * @return mixed
	 */
	private function curlPost($url,$postFields)
	{
		$postFields = http_build_query($postFields);
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
		$result = curl_exec ( $ch );
		curl_close ( $ch );
		return $result;
	}

	/**
	 * 处理返回值
	 * 
	 */
	private function execResult($result)
	{
		$result=preg_split("/[,\r\n]/",$result);
		return $result;
	}

	/**
     * 根据key获取value
     * 
     * @param array $array
     * @param string $key
     * @return NULL|array
     */
    private static function getByKey($array, $key)
    {
        return array_key_exists($key, $array) ? $array[$key] : NULL;
    }
}

$chuang = new Chuanglan();
$chuang->sendSMS('15010860635', '测试一下');

?>