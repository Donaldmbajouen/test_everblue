<?php

return [

    'label' => '查询构建器',

    'form' => [

        'operator' => [
            'label' => '操作符',
        ],

        'or_groups' => [

            'label' => '分组',

            'block' => [
                'label' => '析取 (OR)',
                'or' => '或',
            ],

        ],

        'rules' => [

            'label' => '规则',

            'item' => [
                'and' => '并且',
            ],

        ],

    ],

    'no_rules' => '(没有规则)',

    'item_separators' => [
        'and' => '并且',
        'or' => '或',
    ],

    'operators' => [

        'is_filled' => [

            'label' => [
                'direct' => '已填写',
                'inverse' => '为空',
            ],

            'summary' => [
                'direct' => ':attribute 已填写',
                'inverse' => ':attribute 为空',
            ],

        ],

        'boolean' => [

            'is_true' => [

                'label' => [
                    'direct' => '为真',
                    'inverse' => '为假',
                ],

                'summary' => [
                    'direct' => ':attribute 为真',
                    'inverse' => ':attribute 为假',
                ],

            ],

        ],

        'date' => [

            'is_after' => [

                'label' => [
                    'direct' => '在...之后',
                    'inverse' => '不在...之后',
                ],

                'summary' => [
                    'direct' => ':attribute 在 :date 之后',
                    'inverse' => ':attribute 不在 :date 之后',
                ],

            ],

            'is_before' => [

                'label' => [
                    'direct' => '在...之前',
                    'inverse' => '不在...之前',
                ],

                'summary' => [
                    'direct' => ':attribute 在 :date 之前',
                    'inverse' => ':attribute 不在 :date 之前',
                ],

            ],

            'is_date' => [

                'label' => [
                    'direct' => '为日期',
                    'inverse' => '不是日期',
                ],

                'summary' => [
                    'direct' => ':attribute 是 :date',
                    'inverse' => ':attribute 不是 :date',
                ],

            ],

            'is_month' => [

                'label' => [
                    'direct' => '为月份',
                    'inverse' => '不是月份',
                ],

                'summary' => [
                    'direct' => ':attribute 是 :month',
                    'inverse' => ':attribute 不是 :month',
                ],

            ],

            'is_year' => [

                'label' => [
                    'direct' => '为年份',
                    'inverse' => '不是年份',
                ],

                'summary' => [
                    'direct' => ':attribute 是 :year',
                    'inverse' => ':attribute 不是 :year',
                ],

            ],

            'form' => [

                'date' => [
                    'label' => '日期',
                ],

                'month' => [
                    'label' => '月份',
                ],

                'year' => [
                    'label' => '年份',
                ],

            ],

        ],

        'number' => [

            'equals' => [

                'label' => [
                    'direct' => '等于',
                    'inverse' => '不等于',
                ],

                'summary' => [
                    'direct' => ':attribute 等于 :number',
                    'inverse' => ':attribute 不等于 :number',
                ],

            ],

            'is_max' => [

                'label' => [
                    'direct' => '为最大值',
                    'inverse' => '大于',
                ],

                'summary' => [
                    'direct' => ':attribute 为最大值 :number',
                    'inverse' => ':attribute 大于 :number',
                ],

            ],

            'is_min' => [

                'label' => [
                    'direct' => '为最小值',
                    'inverse' => '小于',
                ],

                'summary' => [
                    'direct' => ':attribute 为最小值 :number',
                    'inverse' => ':attribute 小于 :number',
                ],

            ],

            'aggregates' => [

                'average' => [
                    'label' => '平均值',
                    'summary' => ':attribute 平均值',
                ],

                'max' => [
                    'label' => '最大值',
                    'summary' => ':attribute 最大值',
                ],

                'min' => [
                    'label' => '最小值',
                    'summary' => ':attribute 最小值',
                ],

                'sum' => [
                    'label' => '总和',
                    'summary' => ':attribute 总和',
                ],

            ],

            'form' => [

                'aggregate' => [
                    'label' => '汇总',
                ],

                'number' => [
                    'label' => '数字',
                ],

            ],

        ],

        'relationship' => [

            'equals' => [

                'label' => [
                    'direct' => '拥有',
                    'inverse' => '不拥有',
                ],

                'summary' => [
                    'direct' => '拥有 :count :relationship',
                    'inverse' => '不拥有 :count :relationship',
                ],

            ],

            'has_max' => [

                'label' => [
                    'direct' => '最多有',
                    'inverse' => '超过',
                ],

                'summary' => [
                    'direct' => '最多有 :count :relationship',
                    'inverse' => '超过 :count :relationship',
                ],

            ],

            'has_min' => [

                'label' => [
                    'direct' => '最少有',
                    'inverse' => '少于',
                ],

                'summary' => [
                    'direct' => '最少有 :count :relationship',
                    'inverse' => '少于 :count :relationship',
                ],

            ],

            'is_empty' => [

                'label' => [
                    'direct' => '为空',
                    'inverse' => '不为空',
                ],

                'summary' => [
                    'direct' => ':relationship 为空',
                    'inverse' => ':relationship 不为空',
                ],

            ],

            'is_related_to' => [

                'label' => [

                    'single' => [
                        'direct' => '是',
                        'inverse' => '不是',
                    ],

                    'multiple' => [
                        'direct' => '包含',
                        'inverse' => '不包含',
                    ],

                ],

                'summary' => [

                    'single' => [
                        'direct' => ':relationship 是 :values',
                        'inverse' => ':relationship 不是 :values',
                    ],

                    'multiple' => [
                        'direct' => ':relationship 包含 :values',
                        'inverse' => ':relationship 不包含 :values',
                    ],

                    'values_glue' => [
                        0 => ', ',
                        'final' => ' 或 ',
                    ],

                ],

                'form' => [

                    'value' => [
                        'label' => '值',
                    ],

                    'values' => [
                        'label' => '值列表',
                    ],

                ],

            ],

            'form' => [

                'count' => [
                    'label' => '数量',
                ],

            ],

        ],

        'select' => [

            'is' => [

                'label' => [
                    'direct' => '为',
                    'inverse' => '不为',
                ],

                'summary' => [
                    'direct' => ':attribute 为 :values',
                    'inverse' => ':attribute 不为 :values',
                    'values_glue' => [
                        ', ',
                        'final' => ' 或 ',
                    ],
                ],

                'form' => [

                    'value' => [
                        'label' => '值',
                    ],

                    'values' => [
                        'label' => '值列表',
                    ],

                ],

            ],

        ],

        'text' => [

            'contains' => [

                'label' => [
                    'direct' => '包含',
                    'inverse' => '不包含',
                ],

                'summary' => [
                    'direct' => ':attribute 包含 :text',
                    'inverse' => ':attribute 不包含 :text',
                ],

            ],

            'ends_with' => [

                'label' => [
                    'direct' => '以...结尾',
                    'inverse' => '不以...结尾',
                ],

                'summary' => [
                    'direct' => ':attribute 以 :text 结尾',
                    'inverse' => ':attribute 不以 :text 结尾',
                ],

            ],

            'equals' => [

                'label' => [
                    'direct' => '等于',
                    'inverse' => '不等于',
                ],

                'summary' => [
                    'direct' => ':attribute 等于 :text',
                    'inverse' => ':attribute 不等于 :text',
                ],

            ],

            'starts_with' => [

                'label' => [
                    'direct' => '以...开始',
                    'inverse' => '不以...开始',
                ],

                'summary' => [
                    'direct' => ':attribute 以 :text 开始',
                    'inverse' => ':attribute 不以 :text 开始',
                ],

            ],

            'form' => [

                'text' => [
                    'label' => '文本',
                ],

            ],

        ],

    ],

    'actions' => [

        'add_rule' => [
            'label' => '添加规则',
        ],

        'add_rule_group' => [
            'label' => '添加规则组',
        ],

    ],

];
