<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Arc Glossary - Glossary system extension',
    'description' => '',
    'category' => 'misc',
    'author' => 'Christophe Monard',
    'author_email' => 'cmonard@archriss.com',
    'author_company' => 'Archriss',
    'state' => 'stable',
    'clearCacheOnLoad' => 0,
    'version' => '11.5',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
