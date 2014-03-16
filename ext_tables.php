<?php

// Add Rootline fields for default meta-tags
$TYPO3_CONF_VARS['FE']['addRootLineFields'] = 'abstract,keywords,description,author,author_email' . $TYPO3_CONF_VARS['FE']['addRootLineFields'];

//
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/template.php']['preHeaderRenderHook'][] = 'Tx_ThemesGridelements_Hooks_PreHeaderRenderHook->main';