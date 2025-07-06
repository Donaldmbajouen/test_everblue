<?php

return [

    'builder' => [

        'actions' => [

            'clone' => [
                'label' => '克隆',
            ],

            'add' => [
                'label' => '添加到 :label',
            ],

            'add_between' => [
                'label' => '在块之间插入',
            ],

            'delete' => [
                'label' => '删除',
            ],

            'reorder' => [
                'label' => '移动',
            ],

            'move_down' => [
                'label' => '向下移动',
            ],

            'move_up' => [
                'label' => '向上移动',
            ],

            'collapse' => [
                'label' => '折叠',
            ],

            'expand' => [
                'label' => '展开',
            ],

            'collapse_all' => [
                'label' => '全部折叠',
            ],

            'expand_all' => [
                'label' => '全部展开',
            ],

        ],

    ],

    'checkbox_list' => [

        'actions' => [

            'deselect_all' => [
                'label' => '取消全选',
            ],

            'select_all' => [
                'label' => '全选',
            ],

        ],

    ],

    'file_upload' => [

        'editor' => [

            'actions' => [

                'cancel' => [
                    'label' => '取消',
                ],

                'drag_crop' => [
                    'label' => '拖动模式“裁剪”',
                ],

                'drag_move' => [
                    'label' => '拖动模式“移动”',
                ],

                'flip_horizontal' => [
                    'label' => '水平翻转图片',
                ],

                'flip_vertical' => [
                    'label' => '垂直翻转图片',
                ],

                'move_down' => [
                    'label' => '向下移动图片',
                ],

                'move_left' => [
                    'label' => '向左移动图片',
                ],

                'move_right' => [
                    'label' => '向右移动图片',
                ],

                'move_up' => [
                    'label' => '向上移动图片',
                ],

                'reset' => [
                    'label' => '重置',
                ],

                'rotate_left' => [
                    'label' => '向左旋转图片',
                ],

                'rotate_right' => [
                    'label' => '向右旋转图片',
                ],

                'set_aspect_ratio' => [
                    'label' => '设置纵横比为 :ratio',
                ],

                'save' => [
                    'label' => '保存',
                ],

                'zoom_100' => [
                    'label' => '缩放图片到 100%',
                ],

                'zoom_in' => [
                    'label' => '放大',
                ],

                'zoom_out' => [
                    'label' => '缩小',
                ],

            ],

            'fields' => [

                'height' => [
                    'label' => '高度',
                    'unit' => 'px',
                ],

                'rotation' => [
                    'label' => '旋转',
                    'unit' => '度',
                ],

                'width' => [
                    'label' => '宽度',
                    'unit' => 'px',
                ],

                'x_position' => [
                    'label' => 'X',
                    'unit' => 'px',
                ],

                'y_position' => [
                    'label' => 'Y',
                    'unit' => 'px',
                ],

            ],

            'aspect_ratios' => [

                'label' => '纵横比',

                'no_fixed' => [
                    'label' => '自由',
                ],

            ],

            'svg' => [

                'messages' => [
                    'confirmation' => '编辑 SVG 文件可能会导致缩放时质量损失。\n 您确定要继续吗？',
                    'disabled' => '编辑 SVG 文件已禁用，因可能导致缩放时质量损失。',
                ],

            ],

        ],

    ],

    'key_value' => [

        'actions' => [

            'add' => [
                'label' => '添加行',
            ],

            'delete' => [
                'label' => '删除行',
            ],

            'reorder' => [
                'label' => '重新排序行',
            ],

        ],

        'fields' => [

            'key' => [
                'label' => '键',
            ],

            'value' => [
                'label' => '值',
            ],

        ],

    ],

    'markdown_editor' => [

        'toolbar_buttons' => [
            'attach_files' => '附加文件',
            'blockquote' => '引用',
            'bold' => '加粗',
            'bullet_list' => '项目符号列表',
            'code_block' => '代码块',
            'heading' => '标题',
            'italic' => '斜体',
            'link' => '链接',
            'ordered_list' => '编号列表',
            'redo' => '重做',
            'strike' => '删除线',
            'table' => '表格',
            'undo' => '撤销',
        ],

    ],

    'radio' => [

        'boolean' => [
            'true' => '是',
            'false' => '否',
        ],

    ],

    'repeater' => [

        'actions' => [

            'add' => [
                'label' => '添加到 :label',
            ],

            'add_between' => [
                'label' => '插入到中间',
            ],

            'delete' => [
                'label' => '删除',
            ],

            'clone' => [
                'label' => '克隆',
            ],

            'reorder' => [
                'label' => '移动',
            ],

            'move_down' => [
                'label' => '向下移动',
            ],

            'move_up' => [
                'label' => '向上移动',
            ],

            'collapse' => [
                'label' => '折叠',
            ],

            'expand' => [
                'label' => '展开',
            ],

            'collapse_all' => [
                'label' => '全部折叠',
            ],

            'expand_all' => [
                'label' => '全部展开',
            ],

        ],

    ],

    'rich_editor' => [

        'dialogs' => [

            'link' => [

                'actions' => [
                    'link' => '链接',
                    'unlink' => '取消链接',
                ],

                'label' => 'URL',

                'placeholder' => '输入一个 URL',

            ],

        ],

        'toolbar_buttons' => [
            'attach_files' => '附加文件',
            'blockquote' => '引用',
            'bold' => '加粗',
            'bullet_list' => '项目符号列表',
            'code_block' => '代码块',
            'h1' => '标题',
            'h2' => '副标题',
            'h3' => '小标题',
            'italic' => '斜体',
            'link' => '链接',
            'ordered_list' => '编号列表',
            'redo' => '重做',
            'strike' => '删除线',
            'underline' => '下划线',
            'undo' => '撤销',
        ],

    ],

    'select' => [

        'actions' => [

            'create_option' => [

                'modal' => [

                    'heading' => '创建',

                    'actions' => [

                        'create' => [
                            'label' => '创建',
                        ],

                        'create_another' => [
                            'label' => '创建并创建另一个',
                        ],

                    ],

                ],

            ],

            'edit_option' => [

                'modal' => [

                    'heading' => '编辑',

                    'actions' => [

                        'save' => [
                            'label' => '保存',
                        ],

                    ],

                ],

            ],

        ],

        'boolean' => [
            'true' => '是',
            'false' => '否',
        ],

        'loading_message' => '加载中...',

        'max_items_message' => '只能选择 :count 项。',

        'no_search_results_message' => '没有匹配您搜索的选项。',

        'placeholder' => '选择一个选项',

        'searching_message' => '正在搜索...',

        'search_prompt' => '开始输入以搜索...',

    ],

    'tags_input' => [
        'placeholder' => '新标签',
    ],

    'text_input' => [

        'actions' => [

            'hide_password' => [
                'label' => '隐藏密码',
            ],

            'show_password' => [
                'label' => '显示密码',
            ],

        ],

    ],

    'toggle_buttons' => [

        'boolean' => [
            'true' => '是',
            'false' => '否',
        ],

    ],

    'wizard' => [

        'actions' => [

            'previous_step' => [
                'label' => '返回',
            ],

            'next_step' => [
                'label' => '下一步',
            ],

        ],

    ],

];
