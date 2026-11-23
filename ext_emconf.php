<?php

$EM_CONF['themes_gridelements'] = [
    'title' => 'Themes Base for Gridelements',
    'description' => '',
    'category' => 'fe',
    'version' => '9.0.0',
    'state' => 'stable',
    'clearcacheonload' => 0,
    'author' => 'Themes-Team (Jo Hasenau, Thomas Deuling, Kay Strobach)',
    'author_email' => 'typo3@kay-strobach.de',
    'author_company' => 'private',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-10.5.99',
            'themes' => '7.0.0-9.99.99',
            'gridelements' => '7.0.0-9.99.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
