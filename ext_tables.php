<?php

defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {

        // Add/register icons
        if (TYPO3_MODE === 'BE') {


            // Add Rootline fields for default meta-tags
            $TYPO3_CONF_VARS['FE']['addRootLineFields'] = 'layout,abstract,keywords,description,author,author_email,';
            $TYPO3_CONF_VARS['FE']['addRootLineFields'] .= $TYPO3_CONF_VARS['FE']['addRootLineFields'];
            $TYPO3_CONF_VARS['FE']['addRootLineFields'] = implode(',', array_unique(explode(',', $TYPO3_CONF_VARS['FE']['addRootLineFields'])));

            // TYPO3 skin css overrides
            $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][]
                = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($extKey) . 'Classes/Hooks/PageRenderer.php:KayStrobach\\ThemesGridelements\\Hooks\\PageRenderer->addJSCSS';

            // register svg icons: identifier and filename
            $iconsSvg = [
                'themes-backendlayout-content' => 'BackendLayouts/Content.svg',
                'themes-backendlayout-contentempty' => 'BackendLayouts/ContentEmpty.svg',
                'themes-backendlayout-contentstartsite' => 'BackendLayouts/ContentStartsite.svg',
                'themes-backendlayout-contentmenu' => 'BackendLayouts/ContentMenu.svg',
                'themes-backendlayout-contentmenusidebar' => 'BackendLayouts/ContentMenuSidebar.svg',
                'themes-backendlayout-contentsidebar' => 'BackendLayouts/ContentSidebar.svg',
                'themes-backendlayout-contentsidebarmenu' => 'BackendLayouts/ContentSidebarMenu.svg',
                'themes-backendlayout-contentspecial' => 'BackendLayouts/ContentSpecial.svg',
                'themes-backendlayout-default' => 'BackendLayouts/Default.svg',
                'themes-backendlayout-menucontent' => 'BackendLayouts/MenuContent.svg',
                'themes-backendlayout-menucontentsidebar' => 'BackendLayouts/MenuContentSidebar.svg',
                'themes-backendlayout-menusidebarcontent' => 'BackendLayouts/MenuSidebarContent.svg',
                'themes-backendlayout-sidebarcontent' => 'BackendLayouts/SidebarContent.svg',
                'themes-backendlayout-sidebarcontentmenu' => 'BackendLayouts/SidebarContentMenu.svg',
                'themes-backendlayout-sidebarmenucontent' => 'BackendLayouts/SidebarMenuContent.svg',
            ];
            $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
            foreach ($iconsSvg as $identifier => $path) {
                $iconRegistry->registerIcon(
                    $identifier,
                    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
                    ['source' => 'EXT:' . $extKey . '/Resources/Public/Icons/' . $path]
                );
            }
        }
    },
    $_EXTKEY
);