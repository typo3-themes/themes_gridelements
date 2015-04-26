<?php



$tempColumns = array(
    'tx_themesgridelements_buttoncontent' => array(
        'exclude' => 1,
        'label'   => 'LLL:EXT:themes_gridelements/Resources/Private/Language/ButtonContent.xlf:tx_themesgridelements_buttoncontent',
        'config'  => array(
            'type'           => 'inline',
            'appearance'     => array(
                'levelLinksPosition'              => 'top',
                'showPossibleLocalizationRecords' => TRUE,
                'showRemovedLocalizationRecords'  => TRUE,
                'showAllLocalizationLink'         => TRUE,
                'showSynchronizationLink'         => TRUE,
                'enabledControls'                 => array(
                    'info'     => TRUE,
                    'new'      => TRUE,
                    'dragdrop' => TRUE,
                    'sort'     => TRUE,
                    'hide'     => TRUE,
                    'delete'   => TRUE,
                    'localize' => TRUE,
                )
            ),
            'inline'         => array(
                'inlineNewButtonStyle' => 'display: inline-block;',
            ),
            'behaviour'      => array(
                'localizationMode'                     => 'select',
                'localizeChildrenAtParentLocalization' => TRUE,
            ),
            'foreign_table'  => 'tx_themesgridelements_buttoncontent',
            'foreign_field'  => 'tt_content',
            'foreign_sortby' => 'sorting',
            'size'           => 5,
            'autoSizeMax'    => 20,
        )
    ),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $tempColumns);

$GLOBALS['TCA']['tt_content']['types']['themes_gridelements_buttoncontent_pi1']['showitem'] = '--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general, header, header_link, tx_themesgridelements_buttoncontent, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.appearance, --palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.frames;frames, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, --palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.visibility;visibility, --palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.access;access, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.extended, --div--;LLL:EXT:lang/locallang_tca.xlf:sys_category.tabs.category, categories';

$GLOBALS['TCA']['tt_content']['columns']['media']['displayCond'] = array(
	'OR' => array(
		'FIELD:CType:!=:gridelements_pi1',
		'FIELD:tx_gridelements_backend_layout:IN:container,row,singleColumn,singleColumnHorizontal,carousel',
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tt_content', 'media', 'gridelements_pi1', 'after:section_frame');
