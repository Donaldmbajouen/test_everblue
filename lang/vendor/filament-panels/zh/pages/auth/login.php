<?php

return [

    'title' => '登录',

    'heading' => '登入',

    'actions' => [

        'register' => [
            'before' => '或者',
            'label' => '注册一个帐户',
        ],

        'request_password_reset' => [
            'label' => '忘记密码？',
        ],

    ],

    'form' => [

        'email' => [
            'label' => '电子邮件',
        ],

        'password' => [
            'label' => '密码',
        ],

        'remember' => [
            'label' => '记住账号',
        ],

        'actions' => [

            'authenticate' => [
                'label' => '登入',
            ],

        ],

    ],

    'messages' => [

        'failed' => '这些凭证与我们的记录不符。',

    ],

    'notifications' => [

        'throttled' => [
            'title' => '登录尝试次数过多',
            'body' => '请在 :seconds 秒后重试。',
        ],

    ],

];
