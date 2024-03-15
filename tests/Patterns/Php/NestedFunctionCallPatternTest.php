<?php

declare(strict_types=1);

namespace Tempest\Highlight\Tests\Patterns\Php;

use PHPUnit\Framework\TestCase;
use Tempest\Highlight\Languages\Php\Patterns\NestedFunctionCallPattern;
use Tempest\Highlight\Tests\Patterns\TestsPatterns;

class NestedFunctionCallPatternTest extends TestCase
{
    use TestsPatterns;

    public function test_pattern()
    {
        $this->assertMatches(
            pattern: new NestedFunctionCallPattern(),
            content: ' foo()',
            expected: 'foo',
        );

        $this->assertMatches(
            pattern: new NestedFunctionCallPattern(),
            content: '(foo()',
            expected: 'foo',
        );
    }
}
