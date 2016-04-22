<?php

return [
    'sourcePath' => __DIR__ . '/../',
    'messagePath' => __DIR__,
    'languages' => [
        'de',
        'fa-IR',
        'fi',
        'fr',
        'it',
        'nl',
        'pt-BR',
        'ru',
        'uk',
    ],
    'translator' => 'Yii::t',
    'sort' => false,
    'overwrite' => true,
    'removeUnused' => false,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
        '/tests',
        '/vendor',
    ],
    'format' => 'php',
];
