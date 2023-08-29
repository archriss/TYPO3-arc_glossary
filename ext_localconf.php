<?php

if (!defined('TYPO3')) die ('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addUserTSConfig('options.saveDocNew.tx_arcglossary_glossary_entries=1');

// Register global fluid namespace
$GLOBALS['TYPO3_CONF_VARS']['SYS']['fluid']['namespaces']['arcGlossary'][] = 'Archriss\\ArcGlossary\\ViewHelpers';
