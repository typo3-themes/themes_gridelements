<?php

defined('TYPO3') || die('Access denied.');

call_user_func(
    function ($extKey) {
        // Add Rootline fields for default meta-tags
        $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] = 'layout,abstract,keywords,description,author,author_email,';
        $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] .= $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'];
        $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'] = implode(',', array_unique(explode(',', $GLOBALS['TYPO3_CONF_VARS']['FE']['addRootLineFields'])));
        // TYPO3 skin css overrides
        $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][]
            = \KayStrobach\ThemesGridelements\Hooks\PageRenderer::class . '->addJSCSS';
    },
    'themes_gridelements'
);
