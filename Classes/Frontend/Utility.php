<?php

declare(strict_types=1);

namespace KayStrobach\ThemesGridelements\Frontend;

final class Utility
{
    public function firstValue(mixed $content = [], array $conf = []): string
    {
        if (is_string($content)) {
            return $content;
        }
        if (is_array($content)) {
            return  implode('#', $content);
        }
        return (string)$content;
    }
}
