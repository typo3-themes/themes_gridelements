backendlayouts {
    MenuContent {
        icon = EXT:themes_gridelements/Resources/Public/Icons/BackendLayouts/MenuContent.jpg
        name = MenuContent
        backend_layout (
		    colCount = 4
            rowCount = 3
            rows {
                1 {
                    columns {
                        1 {
                            name = LLL:EXT:themes_gridelements/Resources/Private/Language/BackendLayouts.xlf:area_feature
                            rowspan = 1
                            colspan = 4
                            colPos = 3
                        }
                    }
                }
                2 {
                    columns {
                        1 {
                            name = LLL:EXT:themes_gridelements/Resources/Private/Language/BackendLayouts.xlf:area_menu
                            rowspan = 1
                            colspan = 1
                            colPos = 1
                        }
                        2 {
                            name = LLL:EXT:themes_gridelements/Resources/Private/Language/BackendLayouts.xlf:area_content
                            rowspan = 1
                            colspan = 3
                            colPos = 0
                        }
                    }
                }
                3 {
                    columns {
                        1 {
                            name = LLL:EXT:themes_gridelements/Resources/Private/Language/BackendLayouts.xlf:area_extended
                            rowspan = 1
                            colspan = 4
                            colPos = 4
                        }
                    }
                }
            }
        )
    }
}