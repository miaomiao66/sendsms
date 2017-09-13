<?php

/**
 * RongLian.php
 *
 * 容联·云通讯代理商
 *
 * @author  miaoqi <miaoq92@163.com>
 * @license https://spdx.org/licenses/MIT.html MIT
 */
namespace Miaoqi\SendSms;

class RongLian
{
    /**
     * 主帐号,对应开官网发者主账号下的 ACCOUNT SID
     *
     * @var string
     */
    private $accountSid;

    /**
     * 主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
     *
     * @var string
     */
    private $accountToken;

    /**
     * 应用Id,在官网应用列表中点击应用,对应应用详情中的APP ID
     *
     * @var string
     */
    private $appId;

    /**
     * 请求地址:沙盒环境(用于应用开发调试):sandboxapp.cloopen.com,生产环境(用户应用上线使用):app.cloopen.com
     *
     * @var string
     */
    private $serverIP;

    /**
     * 请求端口，生产环境和沙盒环境一致
     *
     * @var string
     */
    private $serverPort;

    /**
     * REST版本号，在官网文档REST介绍中获得
     *
     * @var string
     */
    private $softVersion;

    /**
     * 时间戳
     *
     * @var string
     */
    private $batch;

    /**
     * 包体格式,可填值:json,xml
     *
     * @var string
     */
    private $bodyType = "json";


    /**
     * 构造函数
     *
     * @return null
     */
    function __construct()
    {
        // $curConfigs = file_exists(config_path('sendsms.php')) ? require(config_path('sendsms.php')) : require(__DIR__ . '/config/default.php');
        $curConfigs         = require __DIR__ . '/config/default.php';
        $curConfigs         = $curConfigs['agents']['RongLian'];
        $this->batch        = date("YmdHis");
        $this->accountSid   = $curConfigs['api_account_sid'];
        $this->accountToken = $curConfigs['api_auth_token'];
        $this->appId        = $curConfigs['api_app_id'];
        $this->serverIP     = $curConfigs['api_server_ip'];
        $this->serverPort   = $curConfigs['api_server_port'];
        $this->softVersion  = $curConfigs['api_soft_version'];

    }//end __construct()


    /**
     * 发起HTTPS请求
     *
     * @param string $url    请求地址
     * @param json   $data   发送的请求数据
     * @param array  $header 请求头部数据
     * @param number $post   请求方式
     *
     * @return mixed
     */
    function curl_post($url, $data, $header, $post=1)
    {
        // 初始化curl.
        $ch = curl_init();
        // 参数设置.
        $res = curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, $post);
        if ($post == TRUE)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        // 连接失败.
        if ($result == FALSE) {
            $result = "{\"statusCode\":\"172001\",\"statusMsg\":\"网络错误\"}";
        }

        curl_close($ch);
        return $result;

    }//end curl_post()


    /**
     * 发送模板短信
     *
     * @param string $to     短信接收的手机号码集合,用英文逗号分开
     * @param array  $datas  内容数据
     * @param number $tempId 模板Id
     *
     * @return mixed
     */
    function sendSMS($to, $datas, $tempId)
    {
        // 主帐号鉴权信息验证,对必选参数进行判空.
        $auth = $this->accAuth();
        if ($auth != "") {
            return $auth;
        }

        // 拼接请求包体.
        if ($this->bodyType == "json") {
            $data = "";
            for ($i=0; $i<count($datas); $i++) {
                $data = $data . "'" . $datas[$i] . "',";
            }

            $body = "{'to':'$to','templateId':'$tempId','appId':'$this->AppId','datas':[" . $data . "]}";
        } else {
            $data = "";
            for ($i=0; $i<count($datas); $i++) {
                $data = $data. "<data>" . $datas[$i] . "</data>";
            }

            $body = "<TemplateSMS><to>$to</to><appId>$this->AppId</appId><templateId>$tempId</templateId><datas>" . $data . "</datas></TemplateSMS>";
        }

        // 大写的sig参数.
        $sig = strtoupper(md5($this->accountSid . $this->accountToken . $this->batch));
        // 生成请求URL.
        $url = "https://$this->ServerIP:$this->ServerPort/$this->SoftVersion/Accounts/$this->AccountSid/SMS/TemplateSMS?sig=$sig";
        // 生成授权:主帐户Id + 英文冒号 + 时间戳.
        $authen = base64_encode($this->accountSid . ":" . $this->batch);
        // 生成包头.
        $header = array(
                   "Accept:application/$this->BodyType",
                   "Content-Type:application/$this->BodyType;charset=utf-8",
                   "Authorization:$authen",
                  );
        // 发送请求.
        $result = $this->curl_post($url, $body, $header);
        if ($this->bodyType == "json") {
            // JSON格式.
            $datas = json_decode($result);
        } else {
            // XML格式.
            $datas = simplexml_load_string(trim($result, " \t\n\r"));
        }

        // 重新装填数据.
        if ($datas->statusCode == 0) {
            if ($this->bodyType == "json") {
                $datas->templateSMS = $datas->templateSMS;
                unset($datas->templateSMS);
            }
        }

        return $datas;

    }//end sendSMS()


    /**
     * 主帐号鉴权
     *
     * @return null
     */
    function accAuth()
    {
        if ($this->serverIP == "") {
            $data = new stdClass();
            $data->statusCode = '172004';
            $data->statusMsg  = 'IP为空';
            return $data;
        }

        if ($this->serverPort <= 0) {
            $data = new stdClass();
            $data->statusCode = '172005';
            $data->statusMsg  = '端口错误（小于等于0）';
            return $data;
        }

        if ($this->softVersion == "") {
            $data = new stdClass();
            $data->statusCode = '172013';
            $data->statusMsg  = '版本号为空';
            return $data;
        }

        if ($this->accountSid == "") {
            $data = new stdClass();
            $data->statusCode = '172006';
            $data->statusMsg  = '主帐号为空';
            return $data;
        }

        if ($this->accountToken == "") {
            $data = new stdClass();
            $data->statusCode = '172007';
            $data->statusMsg  = '主帐号令牌为空';
            return $data;
        }

        if ($this->appId == "") {
            $data = new stdClass();
            $data->statusCode = '172012';
            $data->statusMsg  = '应用ID为空';
            return $data;
        }

    }//end accAuth()


}//end class
