<?php

return [

    'label' => '导出 :label',

    'modal' => [

        'heading' => '导出 :label',

        'form' => [

            'columns' => [

                'label' => '列',

                'form' => [

                    'is_enabled' => [
                        'label' => ':column 启用',
                    ],

                    'label' => [
                        'label' => ':column 标签',
                    ],

                ],

            ],

        ],

        'actions' => [

            'export' => [
                'label' => '导出',
            ],

        ],

    ],

    'notifications' => [

        'completed' => [

            'title' => '导出完成',

            'actions' => [

                'download_csv' => [
                    'label' => '下载 .csv',
                ],

                'download_xlsx' => [
                    'label' => '下载 .xlsx',
                ],

            ],

        ],

        'max_rows' => [
            'title' => '导出数据过多',
            'body' => '您不能一次导出超过 1 行数据。|您不能一次导出超过 :count 行数据。',
        ],

        'started' => [
            'title' => '导出已开始',
            'body' => '您的导出已开始，1 行数据将在后台处理中。完成后您将收到带有下载链接的通知。|您的导出已开始，:count 行数据将在后台处理中。完成后您将收到带有下载链接的通知。',
        ],

    ],

    'file_name' => 'export-:export_id-:model',

];
