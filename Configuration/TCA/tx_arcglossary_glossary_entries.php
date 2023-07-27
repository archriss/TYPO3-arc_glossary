<?php

defined('TYPO3') or die();

return [
    'ctrl' => [
        'title' => 'LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xlf:tx_arcglossary_glossary_entries',
        'label' => 'term',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l18n_parent',
        'transOrigDiffSourceField' => 'l18n_diffsource',
        'default_sortby' => "ORDER BY term",
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
            'fe_group' => 'fe_group',
        ],
        'searchFields' => 'term,title,alias',
        'typeicon_classes' => [
            'default' => 'extension-arcglossary-glossary-entries',
        ],
    ],
    'types' => [
        '0' => ['showitem' => '--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,term,title,alias,type,description,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,--palette--;;language,--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,--palette--;;hidden,--palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access'],
    ],
    'palettes' => [
        'access' => [
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access',
            'showitem' => 'starttime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel, endtime;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel, --linebreak--, fe_group;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:fe_group_formlabel',
        ],
        'hidden' => [
            'showitem' => 'hidden;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:field.default.hidden',
        ],
        'language' => [
            'showitem' => 'sys_language_uid;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:sys_language_uid_formlabel,l18n_parent',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => ['type' => 'language'],
        ],
        'l18n_parent' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_arcglossary_glossary_entries',
                'foreign_table_where' => 'AND tx_arcglossary_glossary_entries.pid=###CURRENT_PID### AND tx_arcglossary_glossary_entries.sys_language_uid IN (-1,0)',
            ],
        ],
        'l18n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => '',
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'default' => 0,
                'items' => [
                    [
                        0 => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'l10n_mode' => 'exclude',
            'l10n_display' => 'defaultAsReadonly',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
            ],
        ],
        'fe_group' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.fe_group',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'default' => '',
                'size' => 5,
                'maxitems' => 20,
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hide_at_login',
                        -1,
                    ],
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.any_login',
                        -2,
                    ],
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.usergroups',
                        '--div--',
                    ],
                ],
                'exclusiveKeys' => '-1,-2',
                'foreign_table' => 'fe_groups',
            ],
        ],
        'term' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xlf:tx_arcglossary_glossary_entries.term',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xlf:tx_arcglossary_glossary_entries.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
            ],
        ],
        'alias' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xlf:tx_arcglossary_glossary_entries.alias',
            'config' => [
                'type' => 'text',
                'cols' => 30,
                'rows' => 5,
            ],
        ],
        'type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xlf:tx_arcglossary_glossary_entries.type',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => '',
                'items' => [
                    ['LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xlf:tx_arcglossary_glossary_entries.type.I.0', 'no'],
                    ['LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xlf:tx_arcglossary_glossary_entries.type.I.1', 'dfn'],
                    ['LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xlf:tx_arcglossary_glossary_entries.type.I.2', 'acronym'],
                    ['LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xlf:tx_arcglossary_glossary_entries.type.I.3', 'abbr'],
                ],
                'size' => 1,
                'maxitems' => 1,
            ],
        ],
        'description' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xlf:tx_arcglossary_glossary_entries.description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
    ],
];
