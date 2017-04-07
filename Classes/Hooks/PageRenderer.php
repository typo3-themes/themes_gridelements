<?php

namespace KayStrobach\ThemesGridelements\Hooks;

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * Class/Function which adds the necessary ExtJS and pure JS stuff for themes.
 *
 * @author Thomas Deuling <typo3@coding.ms>
 */
class PageRenderer
{
    /**
     * wrapper function called by hook (\TYPO3\CMS\Core\Page\PageRenderer->render-preProcess).
     *
     * @param array                             $parameters   : An array of available parameters
     * @param \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer : The parent object that triggered this hook
     *
     * @return void
     */
    public function addJSCSS($parameters, &$pageRenderer)
    {
        // Add css
        $extensionFile = 'Resources/Public/Stylesheets/t3skin_overrides.css';
        $absolutePath = ExtensionManagementUtility::extPath('themes_gridelements', $extensionFile);
        $filename = PathUtility::getAbsoluteWebPath($absolutePath);
        $pageRenderer->addCssFile($filename, 'stylesheet', 'screen');
    }
}
