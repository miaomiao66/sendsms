# laravel-sms


Mobile phone short message service package based on laravel5


  > support:创蓝253,容联·云通讯


## installation

Via Composer

``` php
composer require miaoqi/send-sms dev-master
```

composer.json


``` php
"miaoqi/send-sms": "dev-master"
```

## configuration

``` php
//service provider
'providers' => [
        // ...
        Miaoqi\SendSms\SendSmsServiceProvider::class
    ]
    
//aliases
'aliases' => [
    //...
    'SendSms' => Miaoqi\SendSms\Sms::class    
]

//create the configuration file
php artisan vendor:publish
```


## configuration items

   > 以下为本程序所支持的短信代理平台的配置参考：


``` php

//支持的短信平台是创蓝253
'api_send_url' => 'http://sms.253.com/msg/send',  //创蓝发送短信接口网址
'api_account' => 'your_account',                  //创蓝用户
'api_password' => 'your_password'                 //创蓝密码

//支持的短信平台是容联·云通讯
'api_account_sid' => 'your_account_sid', //主帐号,对应官网开发者主账号下的 ACCOUNT SID.
'api_auth_token' => 'your_auth_token',   //主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN.
'api_app_id' => 'your_app_id',           //应用Id,在官网应用列表中点击应用,对应应用详情中的APP ID,在开发调试的时候,可以使用官网自动为您分配的测试Demo的APP ID.
'api_server_ip' => 'server_ip',          //请求地址,沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com,生产环境(用户应用上线使用):app.cloopen.com.
'api_server_port' => 'server_port',      //请求端口,生产环境和沙盒环境一致.
'api_soft_version' => 'soft_version',    //REST版本号，在官网文档REST介绍中获得.
```

## Security

> If you discover any security related issues, please email miaoq92@163.com instead of using the issue tracker.

> 如果你发现任何相关的问题，请把问题以邮件的形式发送至miaoq92@163.com。


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
