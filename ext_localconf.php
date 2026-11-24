<?php

defined('TYPO3') || die('Access denied.');

// TYPO3 v12: rootline fields for the default meta tags (all fields are part of the rootline since v13)
if ((new \TYPO3\CMS\Core\Information\Typo3Version())->getMajorVersion() < 13) {
    $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] = implode(',', array_unique(array_filter(array_merge(
        \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', (string)($GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] ?? ''), true),
        ['layout', 'abstract', 'keywords', 'description', 'author', 'author_email']
    ))));
}

// colors of the grid columns in the page module
$GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['themes_gridelements'] = 'EXT:themes_gridelements/Resources/Public/Stylesheets/t3skin_overrides.css';
