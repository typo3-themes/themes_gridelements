<?php



$tempColumns = array(
    'tx_themesgridelements_buttoncontent' => array(
        'exclude' => 1,
        'label'   => 'LLL:EXT:themes_gridelements/Resources/Private/Language/locallang_db.xlf:tt_content.tx_themesgridelements_buttoncontent',
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

$GLOBALS['TCA']['tt_content']['types']['themes_gridelements_buttoncontent_pi1']['showitem'] = 'header,header_link,tx_themesgridelements_buttoncontent';