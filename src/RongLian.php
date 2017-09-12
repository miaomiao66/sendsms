<?php

namespace Miaoqi\SendSms;

class RongLian
{
	private $AccountSid;
	private $AccountToken;
	private $AppId;
	private $ServerIP;
	private $ServerPort;
	private $SoftVersion;
	private $Batch;  //时间戳
	private $BodyType = "json";//包体格式，可填值：json 、xml

	function __construct()	
	{
        // $curConfigs = file_exists(config_path('sendsms.php')) ? require(config_path('sendsms.php')) : require(__DIR__ . '/config/default.php');
        $curConfigs = require(__DIR__ . '/config/default.php');
        $curConfigs = $curConfigs['agents']['RongLian'];
		$this->Batch = date("YmdHis");
        $this->AccountSid = $curConfigs['api_account_sid'];
        $this->AccountToken = $curConfigs['api_auth_token'];
        $this->AppId = $curConfigs['api_app_id'];
		$this->ServerIP = $curConfigs['api_server_ip'];
		$this->ServerPort = $curConfigs['api_server_port'];
		$this->SoftVersion = $curConfigs['api_soft_version'];
	}

    /**
     * 发起HTTPS请求
     *
     * @param $url       请求地址
     * @param $data      发送的请求数据
     * @param $header    请求头部数据
     * @param $post = 1  请求方式
     */
    function curl_post($url, $data, $header, $post = 1)
    {
        //初始化curl
        $ch = curl_init();
        //参数设置  
        $res = curl_setopt($ch, CURLOPT_URL, $url);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $post);
        if($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        //连接失败
        if($result == FALSE){
            $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";  
        }
        curl_close($ch);
        return $result;
    } 

   /**
    * 发送模板短信
    * 
    * @param to      短信接收的手机号码集合,用英文逗号分开
    * @param datas   内容数据
    * @param $tempId 模板Id
    */       
    function sendSMS($to, $datas, $tempId)
    {
        //主帐号鉴权信息验证,对必选参数进行判空。
        $auth = $this->accAuth();
        if($auth != ""){
            return $auth;
        }
        //拼接请求包体
        if($this->BodyType == "json"){
            $data = "";
            for($i=0; $i<count($datas); $i++){
                $data = $data . "'" . $datas[$i] . "',"; 
            }
            $body = "{'to':'$to','templateId':'$tempId','appId':'$this->AppId','datas':[" . $data . "]}";
        } else {
            $data = "";
            for($i=0; $i<count($datas); $i++){
                $data = $data. "<data>" . $datas[$i] . "</data>"; 
            }
            $body = "<TemplateSMS><to>$to</to><appId>$this->AppId</appId><templateId>$tempId</templateId><datas>" . $data . "</datas></TemplateSMS>";
        }
        // 大写的sig参数 
        $sig = strtoupper(md5($this->AccountSid . $this->AccountToken . $this->Batch));
        // 生成请求URL        
        $url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
        // 生成授权：主帐户Id + 英文冒号 + 时间戳。
        $authen = base64_encode($this->AccountSid . ":" . $this->Batch);
        // 生成包头  
        $header = array("Accept:application/$this->BodyType","Content-Type:application/$this->BodyType;charset=utf-8","Authorization:$authen");
        // 发送请求
        $result = $this->curl_post($url, $body, $header);
        if($this->BodyType == "json") {//JSON格式
           $datas = json_decode($result); 
        } else { //xml格式
           $datas = simplexml_load_string(trim($result, " \t\n\r"));
        }

        //重新装填数据
        if($datas->statusCode == 0){
            if($this->BodyType == "json"){
	            $datas->TemplateSMS = $datas->templateSMS;
	            unset($datas->templateSMS);   
            }
        }
        return $datas; 
    } 

   /**
    * 主帐号鉴权
    */   
    function accAuth()
    {
        if($this->ServerIP == ""){
            $data = new stdClass();
            $data->statusCode = '172004';
            $data->statusMsg = 'IP为空';
            return $data;
        }
        if($this->ServerPort <= 0){
            $data = new stdClass();
            $data->statusCode = '172005';
            $data->statusMsg = '端口错误（小于等于0）';
            return $data;
        }
        if($this->SoftVersion == ""){
            $data = new stdClass();
            $data->statusCode = '172013';
            $data->statusMsg = '版本号为空';
            return $data;
        } 
        if($this->AccountSid == ""){
            $data = new stdClass();
            $data->statusCode = '172006';
            $data->statusMsg = '主帐号为空';
            return $data;
        }
        if($this->AccountToken == ""){
            $data = new stdClass();
            $data->statusCode = '172007';
            $data->statusMsg = '主帐号令牌为空';
            return $data;
        }
        if($this->AppId == ""){
            $data = new stdClass();
            $data->statusCode = '172012';
            $data->statusMsg = '应用ID为空';
            return $data;
        }   
    }
}





?>