# themes_gridelements

This extension provides the gridelements data structures used in many themes.

These themes can then be exchanged without loosing actual data during the exchange of the theme.

## Requirements

TYPO3 12.4 or 13.4 with [gridelements](https://extensions.typo3.org/extension/gridelements) 12 or 13 and EXT:themes from
the branch `typo3-12-13-14` (branch `typo3-12-13-14` of this extension). The code is prepared for TYPO3 14, but there is
no release of gridelements for TYPO3 14 yet.

## Usage

* Static template "Configuration/TypoScript" (`constants.typoscript`, `setup.typoscript`), themes like theme_bootstrap and the
  templavoila_framework emulation (themes_adapter_tvframework) include these files.
* Page TSconfig `Configuration/PageTS/tsconfig.typoscript`: backend layouts and the grid elements (2, 3 and 4 columns,
  accordion, carousel, tabs, …), included by the themes.
* The files include their folders with `@import` (`<INCLUDE_TYPOSCRIPT>` is deprecated in TYPO3 v13 and ignored in
  v14). The file names `*.txt` stay, the themes reference them.

## Tests

Functional tests in docker against TYPO3 12 and 13, with the clone of themes next to this one (otherwise its branch from
GitHub):

    Build/Scripts/runTests.sh -t 12             # or 13
    Build/Scripts/runTests.sh -a                # all supported versions

Covered: constants and setup in a frontend request (every imported file), page TSconfig, format of the FlexForms and the
stylesheet of the page module.
