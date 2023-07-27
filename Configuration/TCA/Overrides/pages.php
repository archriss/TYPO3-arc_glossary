<?php

defined('TYPO3') or die();

call_user_func(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns(
        "pages",
        [
            'tx_arcglossary_donotparse' => [
                'exclude' => 1,
                'label' => 'LLL:EXT:arc_glossary/Resources/Private/Language/locallang_db.xml:pages.tx_arcglossary_donotparse',
                'config' => [
                    'type' => 'check',
                    'renderType' => 'checkboxToggle',
                    'default' => 0,
                ],
            ],
        ]
    );
});
