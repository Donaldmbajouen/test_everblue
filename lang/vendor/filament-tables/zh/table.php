<?php

return [

    'column_toggle' => [

        'heading' => '列',

    ],

    'columns' => [

        'text' => [

            'actions' => [
                'collapse_list' => '显示少于 :count 项',
                'expand_list' => '显示更多 :count 项',
            ],

            'more_list_items' => '和 :count 项更多',
        ],

    ],

    'fields' => [

        'bulk_select_page' => [
            'label' => '选择/取消选择所有项以进行批量操作。',
        ],

        'bulk_select_record' => [
            'label' => '选择/取消选择项 :key 以进行批量操作。',
        ],

        'bulk_select_group' => [
            'label' => '选择/取消选择组 :title 以进行批量操作。',
        ],

        'search' => [
            'label' => '搜索',
            'placeholder' => '搜索',
            'indicator' => '搜索',
        ],
    ],

    'summary' => [

        'heading' => '总结',

        'subheadings' => [
            'all' => '所有 :label',
            'group' => ':group 总结',
            'page' => '此页面',
        ],

        'summarizers' => [

            'average' => [
                'label' => '平均',
            ],

            'count' => [
                'label' => '计数',
            ],

            'sum' => [
                'label' => '总和',
            ],

        ],

    ],

    'actions' => [

        'disable_reordering' => [
            'label' => '完成重新排序记录',
        ],

        'enable_reordering' => [
            'label' => '重新排序记录',
        ],

        'filter' => [
            'label' => '过滤',
        ],

        'group' => [
            'label' => '分组',
        ],

        'open_bulk_actions' => [
            'label' => '批量操作',
        ],

        'toggle_columns' => [
            'label' => '切换列',
        ],

    ],

    'empty' => [

        'heading' => '没有 :model',

        'description' => '创建一个 :model 以开始使用。',

    ],

    'filters' => [

        'actions' => [

            'apply' => [
                'label' => '应用过滤器',
            ],

            'remove' => [
                'label' => '移除过滤器',
            ],

            'remove_all' => [
                'label' => '移除所有过滤器',
                'tooltip' => '移除所有过滤器',
            ],

            'reset' => [
                'label' => '重置',
            ],

        ],

        'heading' => '过滤器',

        'indicator' => '活动过滤器',

        'multi_select' => [
            'placeholder' => '全部',
        ],

        'select' => [
            'placeholder' => '全部',
        ],

        'trashed' => [

            'label' => '已删除记录',

            'only_trashed' => '仅已删除记录',

            'with_trashed' => '包括已删除记录',

            'without_trashed' => '不包括已删除记录',

        ],

    ],

    'grouping' => [

        'fields' => [

            'group' => [
                'label' => '按...分组',
                'placeholder' => '按...分组',
            ],

            'direction' => [

                'label' => '分组方向',

                'options' => [
                    'asc' => '升序',
                    'desc' => '降序',
                ],

            ],

        ],

    ],

    'reorder_indicator' => '拖动并放置记录以排序。',

    'selection_indicator' => [

        'selected_count' => '已选择 1 条记录|已选择 :count 条记录',

        'actions' => [

            'select_all' => [
                'label' => '选择所有 :count',
            ],

            'deselect_all' => [
                'label' => '取消选择所有',
            ],

        ],

    ],

    'sorting' => [

        'fields' => [

            'column' => [
                'label' => '排序依据',
            ],

            'direction' => [

                'label' => '排序方向',

                'options' => [
                    'asc' => '升序',
                    'desc' => '降序',
                ],

            ],

        ],

    ],

];
