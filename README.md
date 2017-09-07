# laravel-sms


基于laravel5开发的轻量化手机短信服务包，特点：简单，灵活


  > 支持：创蓝253


## 安装

Via Composer

``` php
composer require miaoqi/send-sms dev-master
```

composer.json


``` php
"miaoqi/send-sms": "dev-master"
```

## 配置

``` php
//服务提供者
'providers' => [
        // ...
        Miaoqi\SendSms\SendSmsServiceProvider::class
    ]
    
//别名
'aliases' => [
    //...
    'SendSms' => Miaoqi\SendSms\Sms::class    
]

//创建配置文件
php artisan vendor:publish
```


## 配置项

   > 以下为本程序所支持的短信代理平台的配置参考：


``` php

//支持的短信平台是创蓝253
'api_send_url' => 'http://sms.253.com/msg/send',  //创蓝发送短信接口网址
'api_account' => 'your_account',                  //创蓝用户
'api_password' => 'your_password'                 //创蓝密码
```

## Security

> If you discover any security related issues, please email miaoq92@163.com instead of using the issue tracker.

> 如果你发现任何相关的问题，请把问题以邮件的形式发送至miaoq92@163.com。


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
