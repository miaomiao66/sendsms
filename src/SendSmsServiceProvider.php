<?php

namespace SendSms;

use Illuminate\Support\ServiceProvider;

class SendSmsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            dirname(dirname(__FILE__)) . '/config/default.php' => config_path('sendsms.php')
        ]);
    }

    public function register()
    {

    }
}
