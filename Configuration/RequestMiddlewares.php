<?php

return [
    'frontend' => [
        'archriss/arc-glossary/parser' => [
            'target' => \Archriss\ArcGlossary\Middleware\Parser::class,
            'before' => [
                'typo3/cms-frontend/content-length-headers'
            ],
        ],
    ]
];