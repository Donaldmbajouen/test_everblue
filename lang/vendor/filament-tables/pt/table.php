<?php

return [

    'column_toggle' => [

        'heading' => 'Colunas',

    ],

    'columns' => [

        'text' => [

            'actions' => [
                'collapse_list' => 'Mostrar menos :count',
                'expand_list' => 'Mostrar mais :count',
            ],

            'more_list_items' => 'e mais :count',

        ],

    ],

    'fields' => [

        'bulk_select_page' => [
            'label' => 'Marque/desmarque todos os itens para ações em massa.',
        ],

        'bulk_select_record' => [
            'label' => 'Selecionar/desmarcar item :key para ações em massa.',
        ],

        'bulk_select_group' => [
            'label' => 'Marcar/desmarcar o grupo :title para ações em massa.',
        ],

        'search' => [
            'label' => 'Procurar',
            'placeholder' => 'Procurar',
            'indicator' => 'Procurar',
        ],

    ],

    'summary' => [

        'heading' => 'Resumo',

        'subheadings' => [
            'all' => 'Todos :label',
            'group' => ':group resumo',
            'page' => 'Esta página',
        ],

        'summarizers' => [

            'average' => [
                'label' => 'Média',
            ],

            'count' => [
                'label' => 'Contar',
            ],

            'sum' => [
                'label' => 'Soma',
            ],

        ],

    ],

    'actions' => [

        'disable_reordering' => [
            'label' => 'Concluir a reordenação dos registros',
        ],

        'enable_reordering' => [
            'label' => 'Reordenar registros',
        ],

        'filter' => [
            'label' => 'Filtro',
        ],

        'group' => [
            'label' => 'Grupo',
        ],

        'open_bulk_actions' => [
            'label' => 'Ações em massa',
        ],

        'toggle_columns' => [
            'label' => 'Alternar colunas',
        ],

    ],

    'empty' => [

        'heading' => 'Não :model',

        'description' => 'Crie um :model para começar.',

    ],

    'filters' => [

        'actions' => [

            'apply' => [
                'label' => 'Aplicar filtros',
            ],

            'remove' => [
                'label' => 'Remover filtro',
            ],

            'remove_all' => [
                'label' => 'Remover todos os filtros',
                'tooltip' => 'Remover todos os filtros',
            ],

            'reset' => [
                'label' => 'Reiniciar',
            ],

        ],

        'heading' => 'Filtros',

        'indicator' => 'Filtros ativos',

        'multi_select' => [
            'placeholder' => 'Todos',
        ],

        'select' => [
            'placeholder' => 'Todos',
        ],

        'trashed' => [

            'label' => 'Registros excluídos',

            'only_trashed' => 'Apenas registros excluídos',

            'with_trashed' => 'Com registros excluídos',

            'without_trashed' => 'Sem registros excluídos',

        ],

    ],

    'grouping' => [

        'fields' => [

            'group' => [
                'label' => 'Agrupar por',
                'placeholder' => 'Agrupar por',
            ],

            'direction' => [

                'label' => 'Direção do grupo',

                'options' => [
                    'asc' => 'Ascendente',
                    'desc' => 'Descendente',
                ],

            ],

        ],

    ],

    'reorder_indicator' => 'Arraste e solte os registros em ordem.',

    'selection_indicator' => [

        'selected_count' => '1 registro selecionado|:count registros selecionados',

        'actions' => [

            'select_all' => [
                'label' => 'Selecionar tudo :count',
            ],

            'deselect_all' => [
                'label' => 'Desmarcar tudo',
            ],

        ],

    ],

    'sorting' => [

        'fields' => [

            'column' => [
                'label' => 'Ordenar por',
            ],

            'direction' => [

                'label' => 'Direção de classificação',

                'options' => [
                    'asc' => 'Ascendente',
                    'desc' => 'Descendente',
                ],

            ],

        ],

    ],

];
