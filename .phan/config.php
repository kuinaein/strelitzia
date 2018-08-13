<?php
declare (strict_types = 1);

// ref: https://github.com/phan/phan/blob/master/.phan/config.php

return [
    // 解析のためにクラスやメソッドの情報を取得するディレクトリ。
    // これらと、以下の exclude_analysis_directory_list で指定した
    // ディレクトリとの差分が解析チェックの対象となる
    'directory_list' => [
        'app',
        'tests',
        'routes',
        'config',
        'database',
        'resources/php-stub',
        'vendor',
    ],

    // 解析チェックの対象から除外するディレクトリ。
    'exclude_analysis_directory_list' => [
        'vendor',
        'resources/php-stub',
    ],

    'suppress_issue_types' => [
        'PhanUndeclaredVariable',
        'PhanRedefinedInheritedInterface',
    ],
];
