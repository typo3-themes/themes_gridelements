<?php


$GLOBALS['TCA']['tt_content']['columns']['media']['displayCond'] = array(
	'OR' => array(
		'FIELD:CType:!=:gridelements_pi1',
		'FIELD:tx_gridelements_backend_layout:IN:container,row,singleColumn,singleColumnHorizontal,carousel',
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'media', 'gridelements_pi1', 'after:section_frame');
