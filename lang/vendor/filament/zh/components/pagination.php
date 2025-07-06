<?php

return [

    'label' => '分页导航',

    'overview' => '{1} 显示 1 个结果|[2,*] 显示 :first 到 :last (共 :total) 结果',

    'fields' => [

        'records_per_page' => [

            'label' => '每页',

            'options' => [
                'all' => '全部',
            ],

        ],

    ],

    'actions' => [

        'first' => [
            'label' => '第一的',
        ],

        'go_to_page' => [
            'label' => '转到页面：页面',
        ],

        'last' => [
            'label' => '最后的',
        ],

        'next' => [
            'label' => '下一个',
        ],

        'previous' => [
            'label' => '以前的',
        ],

    ],

];
