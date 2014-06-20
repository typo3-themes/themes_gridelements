<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * @todo Doc missing
 */
class Tx_ThemesGridelements_Hooks_PreHeaderRenderHook {
	function main($arg) {
		/** @var $pagerenderer t3lib_PageRenderer */
		$pagerenderer = $arg['pageRenderer'];
		$pagerenderer->addCssFile(
			$pagerenderer->backPath . ExtensionManagementUtility::extRelPath('themes_gridelements') . 'Resources/Public/Stylesheets/t3skin_overrides.css'
		);
	}
}
