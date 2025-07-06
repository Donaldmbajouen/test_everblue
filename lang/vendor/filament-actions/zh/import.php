<?php

return [

    'label' => '导入 :label',

    'modal' => [

        'heading' => '导入 :label',

        'form' => [

            'file' => [

                'label' => '文件',

                'placeholder' => '上传一个 CSV 文件',

                'rules' => [
                    'duplicate_columns' => '{0} 文件中不能包含多个空的列标题。|{1,*} 文件中不能包含重复的列标题：:columns。',
                ],

            ],

            'columns' => [
                'label' => '列',
                'placeholder' => '选择一列',
            ],

        ],

        'actions' => [

            'download_example' => [
                'label' => '下载示例 CSV 文件',
            ],

            'import' => [
                'label' => '导入',
            ],

        ],

    ],

    'notifications' => [

        'completed' => [

            'title' => '导入完成',

            'actions' => [

                'download_failed_rows_csv' => [
                    'label' => '下载失败行信息|下载失败行信息',
                ],

            ],

        ],

        'max_rows' => [
            'title' => '上传的 CSV 文件过大',
            'body' => '一次导入不能超过 1 行。|一次导入不能超过 :count 行。',
        ],

        'started' => [
            'title' => '导入已开始',
            'body' => '您的导入已开始，1 行将会在后台处理中。|您的导入已开始，:count 行将会在后台处理中。',
        ],

    ],

    'example_csv' => [
        'file_name' => ':importer-example',
    ],

    'failure_csv' => [
        'file_name' => 'import-:import_id-:csv_name-failed-rows',
        'error_header' => '错误',
        'system_error' => '系统错误，请联系支持。',
    ],

];
