<?php

declare(strict_types=1);

namespace KayStrobach\ThemesGridelements\Tests\Functional;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ThemesGridelementsTest extends FunctionalTestCase
{
    protected array $coreExtensionsToLoad = ['install', 'form'];

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/gridelements',
        'typo3conf/ext/themes',
        'typo3conf/ext/themes_gridelements',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/Fixtures/pages.csv');
        // page TSconfig of the extension (theme_bootstrap includes Configuration/PageTS/)
        $this->get(ConnectionPool::class)->getConnectionForTable('pages')->update(
            'pages',
            ['TSconfig' => (string)file_get_contents(GeneralUtility::getFileAbsFileName('EXT:themes_gridelements/Configuration/PageTS/tsconfig.txt'))],
            ['uid' => 1]
        );
        $siteFolder = Environment::getConfigPath() . '/sites/school';
        GeneralUtility::mkdir_deep($siteFolder);
        copy(__DIR__ . '/Fixtures/sites/school/config.yaml', $siteFolder . '/config.yaml');
        $this->get(CacheManager::class)->getCache('core')->flush();
    }

    /**
     * @return array{constants: array<string, string>, setup: array}
     */
    private function frontendTypoScript(): array
    {
        $this->setUpFrontendRootPage(1, ['EXT:themes_gridelements/Tests/Functional/Fixtures/page.typoscript']);
        // the static template, as an integrator includes it (constants.txt / setup.txt)
        $this->get(ConnectionPool::class)->getConnectionForTable('sys_template')->update(
            'sys_template',
            ['include_static_file' => 'EXT:themes_gridelements/Configuration/TypoScript'],
            ['pid' => 1]
        );
        $response = $this->executeFrontendSubRequest(new InternalRequest('https://example.com/school/'));
        $body = (string)$response->getBody();
        self::assertSame(200, $response->getStatusCode(), substr(strip_tags($body), 0, 1000));
        self::assertSame(1, preg_match('/###(.*)###/s', $body, $matches), $body);
        return json_decode($matches[1], true, 512, JSON_THROW_ON_ERROR);
    }

    public function testConstantsAreImported(): void
    {
        $constants = $this->frontendTypoScript()['constants'];
        self::assertArrayHasKey('themes.configuration.siteName', $constants, 'Constants/themes.basic.typoscript');
        self::assertArrayHasKey('themes.configuration.baseurl', $constants);
    }

    public function testSetupOfLibraryAndElementsIsImported(): void
    {
        $setup = $this->frontendTypoScript()['setup'];
        self::assertArrayHasKey('defaultGridSetup.', $setup['lib.']['gridelements.'] ?? [], 'setup.txt itself');
        // one element per file of Elements/TypoScript/*.typoscript
        $elements = ['2ColumnGrid', '3ColumnGrid', '4ColumnGrid', 'accordion', 'carousel', 'container', 'row',
            'singleColumn', 'singleColumnHeaderFooter', 'singleColumnHorizontal', 'tabs'];
        foreach ($elements as $element) {
            self::assertArrayHasKey($element . '.', $setup['lib.']['gridelements.'] ?? [], 'Elements/TypoScript: ' . $element);
        }
        self::assertArrayHasKey('content.', $setup['lib.'] ?? [], 'Library/*.typoscript');
    }

    public function testPageTsConfigProvidesBackendLayoutsAndGridElements(): void
    {
        $tsConfig = BackendUtility::getPagesTSconfig(1);
        self::assertArrayHasKey('Content.', $tsConfig['mod.']['web_layout.']['BackendLayouts.'] ?? [], 'BackendLayouts/*.typoscript');
        self::assertSame(
            'FILE:EXT:themes_gridelements/Configuration/Elements/FlexForms/2ColumnGrid.xml',
            $tsConfig['tx_gridelements.']['setup.']['2ColumnGrid.']['flexformDS'] ?? null,
            'Elements/PageTS/*.typoscript'
        );
    }

    public function testFlexFormsUseTheFormatOfTypo3V13(): void
    {
        foreach (['2ColumnGrid', '3ColumnGrid', '4ColumnGrid'] as $grid) {
            $file = GeneralUtility::getFileAbsFileName('EXT:themes_gridelements/Configuration/Elements/FlexForms/' . $grid . '.xml');
            $xml = (string)file_get_contents($file);
            self::assertStringNotContainsString('<TCEforms', $xml, $grid . ': no TCEforms wrappers');
            $dataStructure = GeneralUtility::xml2array($xml);
            self::assertIsArray($dataStructure, $grid);
            foreach ($dataStructure['sheets']['sDEF']['ROOT']['el'] as $field => $configuration) {
                foreach ($configuration['config']['items'] ?? [] as $item) {
                    self::assertArrayHasKey('label', $item, $grid . ' ' . $field);
                    self::assertArrayHasKey('value', $item, $grid . ' ' . $field);
                }
            }
        }
    }

    public function testStylesheetOfThePageModuleIsRegistered(): void
    {
        $stylesheet = $GLOBALS['TYPO3_CONF_VARS']['BE']['stylesheets']['themes_gridelements'] ?? '';
        self::assertFileExists(GeneralUtility::getFileAbsFileName($stylesheet));
    }

    public function testNoDeprecatedIncludesAreLeft(): void
    {
        // <INCLUDE_TYPOSCRIPT> is deprecated in TYPO3 v13 and ignored in v14
        $path = GeneralUtility::getFileAbsFileName('EXT:themes_gridelements/Configuration/');
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if (in_array($file->getExtension(), ['typoscript', 'txt', 'tsconfig'], true)) {
                self::assertStringNotContainsString('<INCLUDE_TYPOSCRIPT', (string)file_get_contents($file->getPathname()), $file->getPathname());
            }
        }
    }
}
