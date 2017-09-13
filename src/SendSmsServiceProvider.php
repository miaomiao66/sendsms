<?php

/**
 * SendSmsServiceProvider.php
 *
 * 服务提供
 *
 * @author  miaoqi <miaoq92@163.com>
 * @license https://spdx.org/licenses/MIT.html MIT
 */
namespace Miaoqi\SendSms;

use Illuminate\Support\ServiceProvider;

class SendSmsServiceProvider extends ServiceProvider
{
    /**
     * 服务绑定
     *
     * @var    null
     * @return null
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/default.php' => config_path('sendsms.php')
        ]);

    }//end boot()


    /**
     * 服务注册
     *
     * @var    null
     * @return null
     */
    public function register()
    {

    }//end register()


}//end class
