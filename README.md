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

   > The following is a configuration reference for the SMS proxy platform supported by this program:


``` php

//The supported SMS platform is 创蓝253
'api_send_url' => 'http://sms.253.com/msg/send',  //Send SMS interface URL
'api_account' => 'your_account',                  //user
'api_password' => 'your_password'                 //password

//The supported SMS platform is 容联·云通讯
'api_account_sid' => 'your_account_sid', //The primary account,the AUTH TOKEN in the main account of the official website.
'api_auth_token' => 'your_auth_token',   //Master account token,the AUTH TOKEN in the main account of the official website.
'api_app_id' => 'your_app_id',           //APP ID.
'api_server_ip' => 'server_ip',          //Request the address,sandbox environment:sandboxapp.cloopen.com,the production environment:app.cloopen.com.
'api_server_port' => 'server_port',      //Request port,The production environment is consistent with the sandbox environment.
'api_soft_version' => 'soft_version',    //The version number of REST,get it in the official website documentation.

```

## Security

> If you discover any security related issues, please email miaoq92@163.com instead of using the issue tracker.

