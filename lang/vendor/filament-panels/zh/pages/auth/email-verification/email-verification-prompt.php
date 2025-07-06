<?php

return [

    'title' => '验证您的电子邮件地址',

    'heading' => '验证您的电子邮件地址',

    'actions' => [

        'resend_notification' => [
            'label' => '重新发送',
        ],

    ],

    'messages' => [
        'notification_not_received' => '没有收到我们发送的电子邮件？',
        'notification_sent' => '我们已向 :email 发送了一封电子邮件，其中包含有关如何验证您的电子邮件地址的说明。',
    ],

    'notifications' => [

        'notification_resent' => [
            'title' => '我们已重新发送该电子邮件。',
        ],

        'notification_resend_throttled' => [
            'title' => '重发尝试次数过多',
            'body' => '请在 :seconds 秒后重试。',
        ],

    ],

];
