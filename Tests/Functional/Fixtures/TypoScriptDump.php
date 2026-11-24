<?php

declare(strict_types=1);

namespace KayStrobach\ThemesGridelements\Tests\Functional\Fixtures;

use Psr\Http\Message\ServerRequestInterface;

/**
 * USER object of the test page: outputs the TypoScript of the request as JSON.
 */
final class TypoScriptDump
{
    public function dump(string $content, array $configuration, ServerRequestInterface $request): string
    {
        $typoScript = $request->getAttribute('frontend.typoscript');
        return '###' . json_encode([
            'constants' => $typoScript->getFlatSettings(),
            'setup' => $typoScript->getSetupArray(),
        ]) . '###';
    }
}
