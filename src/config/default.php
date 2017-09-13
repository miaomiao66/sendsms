<?php

/**
 * 配置文件
 *
 * @author  miaoqi <miaoq92@163.com>
 * @license https://spdx.org/licenses/MIT.html MIT
 */

return [
    'default' => 'RongLian',

    'agents' => [

        'ChuangLan' => [
            'api_send_url' => 'http://sms.253.com/msg/send',
            'api_account' => 'your_account',
            'api_password' => 'your_password'
        ],

        'RongLian' => [
            // 主帐号,对应官网开发者主账号下的 ACCOUNT SID.
            'api_account_sid' => 'your_account_sid',

            // 主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN.
            'api_auth_token' => 'your_auth_token',

            // 应用Id,在官网应用列表中点击应用,对应应用详情中的APP ID,在开发调试的时候,可以使用官网自动为您分配的测试Demo的APP ID.
            'api_app_id' => 'your_app_id',

            // 请求地址,沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com,生产环境(用户应用上线使用):app.cloopen.com.
            'api_server_ip' => 'server_ip',

            // 请求端口,生产环境和沙盒环境一致.
            'api_server_port' => 'server_port',

            // REST版本号，在官网文档REST介绍中获得.
            'api_soft_version' => 'soft_version',
        ],
        
    ]
];

