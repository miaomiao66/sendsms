<?php

namespace Miaoqi\SendSms;

use Illuminate\Support\ServiceProvider;

class SendSmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            dirname(dirname(__FILE__)) . '/src/config/default.php' => config_path('sendsms.php')
        ]);
    }

    public function register()
    {

    }
}
