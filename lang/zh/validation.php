<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute 字段必须被接受。',
    'accepted_if' => '当 :other 为 :value 时，:attribute 字段必须被接受。',
    'active_url' => ':attribute 字段必须是一个有效的 URL。',
    'after' => ':attribute 字段必须是 :date 之后的日期。',
    'after_or_equal' => ':attribute 字段必须是 :date 之后或相同的日期。',
    'alpha' => ':attribute 字段只能包含字母。',
    'alpha_dash' => ':attribute 字段只能包含字母、数字、破折号和下划线。',
    'alpha_num' => ':attribute 字段只能包含字母和数字。',
    'array' => ':attribute 字段必须是一个数组。',
    'ascii' => ':attribute 字段只能包含单字节的字母数字字符和符号。',
    'before' => ':attribute 字段必须是 :date 之前的日期。',
    'before_or_equal' => ':attribute 字段必须是 :date 之前或相同的日期。',
    'between' => [
        'array' => ':attribute 字段的项目数必须在 :min 到 :max 之间。',
        'file' => ':attribute 字段的大小必须在 :min 到 :max 千字节之间。',
        'numeric' => ':attribute 字段的值必须在 :min 到 :max 之间。',
        'string' => ':attribute 字段的长度必须在 :min 到 :max 个字符之间。',
    ],
    'boolean' => ':attribute 字段必须是 true 或 false。',
    'can' => ':attribute 字段包含一个未经授权的值。',
    'confirmed' => ':attribute 字段的确认不匹配。',
    'current_password' => '密码不正确。',
    'date' => ':attribute 字段必须是一个有效的日期。',
    'date_equals' => ':attribute 字段必须是等于 :date 的日期。',
    'date_format' => ':attribute 字段与格式 :format 不匹配。',
    'decimal' => ':attribute 字段必须有 :decimal 位小数。',
    'declined' => ':attribute 字段必须被拒绝。',
    'declined_if' => '当 :other 是 :value 时，:attribute 字段必须被拒绝。',
    'different' => ':attribute 字段和 :other 必须不同。',
    'digits' => ':attribute 字段必须是 :digits 位数。',
    'digits_between' => ':attribute 字段必须在 :min 到 :max 位数之间。',
    'dimensions' => ':attribute 字段的图像尺寸无效。',
    'distinct' => ':attribute 字段有一个重复的值。',
    'doesnt_end_with' => ':attribute 字段不能以以下之一结尾: :values。',
    'doesnt_start_with' => ':attribute 字段不能以以下之一开头: :values。',
    'email' => ':attribute 字段必须是一个有效的电子邮件地址。',
    'ends_with' => ':attribute 字段必须以以下之一结尾: :values。',
    'enum' => '所选的 :attribute 无效。',
    'exists' => '所选的 :attribute 无效。',
    'extensions' => ':attribute 字段必须是以下扩展名之一: :values。',
    'file' => ':attribute 字段必须是一个文件。',
    'filled' => ':attribute 字段必须有一个值。',
    'gt' => [
        'array' => ':attribute 字段的项目数必须大于 :value 个。',
        'file' => ':attribute 字段的大小必须大于 :value 千字节。',
        'numeric' => ':attribute 字段的值必须大于 :value。',
        'string' => ':attribute 字段的长度必须大于 :value 个字符。',
    ],
    'gte' => [
        'array' => ':attribute 字段的项目数必须不少于 :value 个。',
        'file' => ':attribute 字段的大小必须大于或等于 :value 千字节。',
        'numeric' => ':attribute 字段的值必须大于或等于 :value。',
        'string' => ':attribute 字段的长度必须大于或等于 :value 个字符。',
    ],
    'hex_color' => ':attribute 字段必须是一个有效的十六进制颜色。',
    'image' => ':attribute 字段必须是图像。',
    'in' => '所选的 :attribute 无效。',
    'in_array' => ':attribute 字段必须在 :other 中存在。',
    'integer' => ':attribute 字段必须是整数。',
    'ip' => ':attribute 字段必须是一个有效的 IP 地址。',
    'ipv4' => ':attribute 字段必须是一个有效的 IPv4 地址。',
    'ipv6' => ':attribute 字段必须是一个有效的 IPv6 地址。',
    'json' => ':attribute 字段必须是一个有效的 JSON 字符串。',
    'lowercase' => ':attribute 字段必须是小写字母。',
    'lt' => [
        'array' => ':attribute 字段的项目数必须少于 :value 个。',
        'file' => ':attribute 字段的大小必须小于 :value 千字节。',
        'numeric' => ':attribute 字段的值必须小于 :value。',
        'string' => ':attribute 字段的长度必须少于 :value 个字符。',
    ],
    'lte' => [
        'array' => ':attribute 字段的项目数必须不多于 :value 个。',
        'file' => ':attribute 字段的大小必须小于或等于 :value 千字节。',
        'numeric' => ':attribute 字段的值必须小于或等于 :value。',
        'string' => ':attribute 字段的长度必须小于或等于 :value 个字符。',
    ],
    'mac_address' => ':attribute 字段必须是一个有效的 MAC 地址。',
    'max' => [
        'array' => ':attribute 字段的项目数不能超过 :max 个。',
        'file' => ':attribute 字段的大小不能超过 :max 千字节。',
        'numeric' => ':attribute 字段的值不能超过 :max。',
        'string' => ':attribute 字段的长度不能超过 :max 个字符。',
    ],
    'max_digits' => ':attribute 字段的位数不能超过 :max 位。',
    'mimes' => ':attribute 字段必须是以下类型的文件: :values。',
    'mimetypes' => ':attribute 字段必须是以下类型的文件: :values。',
    'min' => [
        'array' => ':attribute 字段的项目数不能少于 :min 个。',
        'file' => ':attribute 字段的大小不能少于 :min 千字节。',
        'numeric' => ':attribute 字段的值不能少于 :min。',
        'string' => ':attribute 字段的长度不能少于 :min 个字符。',
    ],
    'min_digits' => ':attribute 字段的位数不能少于 :min 位。',
    'missing' => ':attribute 字段必须缺失。',
    'missing_if' => '当 :other 是 :value 时，:attribute 字段必须缺失。',
    'missing_unless' => '除非 :other 是 :value，否则 :attribute 字段必须缺失。',
    'missing_with' => '当 :values 存在时，:attribute 字段必须缺失。',
    'missing_with_all' => '当 :values 都存在时，:attribute 字段必须缺失。',
    'multiple_of' => ':attribute 字段的值必须是 :value 的倍数。',
    'not_in' => '所选的 :attribute 无效。',
    'not_regex' => ':attribute 字段的格式无效。',
    'numeric' => ':attribute 字段必须是数字。',
    'password' => [
        'letters' => ':attribute 字段必须包含至少一个字母。',
        'mixed' => ':attribute 字段必须包含至少一个大写字母和一个小写字母。',
        'numbers' => ':attribute 字段必须包含至少一个数字。',
        'symbols' => ':attribute 字段必须包含至少一个符号。',
        'uncompromised' => '给定的 :attribute 已出现在数据泄露中。请选择不同的 :attribute。',
    ],
    'present' => ':attribute 字段必须存在。',
    'present_if' => '当 :other 是 :value 时，:attribute 字段必须存在。',
    'present_unless' => '除非 :other 是 :value，否则 :attribute 字段必须存在。',
    'present_with' => '当 :values 存在时，:attribute 字段必须存在。',
    'present_with_all' => '当 :values 存在时，:attribute 字段必须存在。',
    'prohibited' => ':attribute 字段是禁止的。',
    'prohibited_if' => '当 :other 是 :value 时，:attribute 字段是禁止的。',
    'prohibited_unless' => '除非 :other 在 :values 中，否则 :attribute 字段是禁止的。',
    'prohibits' => ':attribute 字段禁止 :other 存在。',
    'regex' => ':attribute 字段的格式无效。',
    'required' => ':attribute 字段是必需的。',
    'required_array_keys' => ':attribute 字段必须包含以下条目: :values。',
    'required_if' => '当 :other 是 :value 时，:attribute 字段是必需的。',
    'required_if_accepted' => '当 :other 被接受时，:attribute 字段是必需的。',
    'required_unless' => '除非 :other 在 :values 中，否则 :attribute 字段是必需的。',
    'required_with' => '当 :values 存在时，:attribute 字段是必需的。',
    'required_with_all' => '当 :values 都存在时，:attribute 字段是必需的。',
    'required_without' => '当 :values 不存在时，:attribute 字段是必需的。',
    'required_without_all' => '当 :values 都不存在时，:attribute 字段是必需的。',
    'same' => ':attribute 字段必须与 :other 匹配。',
    'size' => [
        'array' => ':attribute 字段必须包含 :size 项。',
        'file' => ':attribute 字段的大小必须是 :size 千字节。',
        'numeric' => ':attribute 字段的值必须是 :size。',
        'string' => ':attribute 字段的长度必须是 :size 个字符。',
    ],
    'starts_with' => ':attribute 字段必须以以下之一开头: :values。',
    'string' => ':attribute 字段必须是字符串。',
    'timezone' => ':attribute 字段必须是一个有效的时区。',
    'unique' => ':attribute 已经被占用。',
    'uploaded' => ':attribute 上传失败。',
    'uppercase' => ':attribute 字段必须是大写字母。',
    'url' => ':attribute 字段必须是一个有效的 URL。',
    'ulid' => ':attribute 字段必须是一个有效的 ULID。',
    'uuid' => ':attribute 字段必须是一个有效的 UUID。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
