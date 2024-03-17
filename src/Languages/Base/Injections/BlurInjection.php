<?php

declare(strict_types=1);

namespace Tempest\Highlight\Languages\Base\Injections;

use Tempest\Highlight\Highlighter;
use Tempest\Highlight\Injection;
use Tempest\Highlight\Languages\Base\IsHighlightInjection;

final readonly class BlurInjection implements Injection
{
    use IsHighlightInjection;

    private function getToken(): string
    {
        return '~';
    }

    private function getClassname(): string
    {
        return 'hl-blur';
    }
}