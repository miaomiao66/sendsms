<?php

namespace Miaoqi\SendSms;

use Illuminate\Support\ServiceProvider;

class SendSmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/default.php' => config_path('sendsms.php')
        ]);
    }

    public function register()
    {
    	
    }
}
