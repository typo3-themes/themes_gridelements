<?php
namespace KayStrobach\ThemesGridelements\Hooks;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * @todo missing docblock
 */
class PreHeaderRenderHook {

	/**
	 * @todo missing docblock
	 */
	function main($arg) {
		/** @var \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer */
		$pageRenderer = $arg['pageRenderer'];
		$pageRenderer->addCssFile(
			$pageRenderer->backPath . ExtensionManagementUtility::extRelPath('themes_gridelements') . 'Resources/Public/Stylesheets/t3skin_overrides.css'
		);
	}
}
