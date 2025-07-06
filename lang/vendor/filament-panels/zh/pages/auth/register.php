<?php

return [

    'title' => '登记',

    'heading' => '报名',

    'actions' => [

        'login' => [
            'before' => '或者',
            'label' => '登录您的帐户',
        ],

    ],

    'form' => [

        'email' => [
            'label' => '电子邮件',
        ],

        'name' => [
            'label' => '姓名',
        ],

        'password' => [
            'label' => '密码',
            'validation_attribute' => '密码',
        ],

        'password_confirmation' => [
            'label' => '确认密码',
        ],

        'actions' => [

            'register' => [
                'label' => '报名',
            ],

        ],

    ],

    'notifications' => [

        'throttled' => [
            'title' => '注册尝试次数过多',
            'body' => '请在 :seconds 秒后重试。',
        ],

    ],

];
