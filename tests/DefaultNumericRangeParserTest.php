<?php

declare(strict_types=1);

namespace Dsentker\Tests;

use DSentker\DefaultNumericRangeParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class DefaultNumericRangeParserTest extends TestCase
{

    #[DataProvider('normalizedArrayProvider')]
    public function testGetNormalizedArray(string $input, array $expected): void
    {
        $instance = new DefaultNumericRangeParser();
        $result = $instance->parse($input);
        $this->assertEquals($expected, $result->toNormalizedArray());
    }

    public static function normalizedArrayProvider(): array
    {
        return [
            ['', []],
            ['-', []],
            [';;', []],
            [' ; - ; ', []],
            ['--', []],
            ['1-1', [1]],
            ['1-2', [1, 2]],
            ['2-0', [0, 1, 2]],
            ['2', [2]],
            ['0', [0]],
            ['1;', [1]],
            [';3', [3]],
            ['-2', [2]],
            ['2-', [2]],
            ['--42--', [42]],
            ['2;2-2;2', [2]],
            ['2-4;11;6-7;11;10-9', [2, 3, 4, 6, 7, 9, 10, 11]],
        ];
    }

    public function testIterator(): void
    {
        $instance = new DefaultNumericRangeParser();
        $result = $instance->parse('2-4;4;101;');

        $expected = (function(): \Generator {
            yield [0,2];
            yield [0,3];
            yield [0,4];
            yield [1,4];
            yield [2,101];
        })();

        foreach ($result as $parsedIndex) {
            [$blockIndex, $index] = $expected->current();
            $this->assertEquals($index, $parsedIndex);
            $this->assertEquals($blockIndex, $result->getIteratorIndex());
            $expected->next();
        }

    }

    public function testDifferentSeparators(): void
    {
        $instance = new DefaultNumericRangeParser('..', 'x');
        $result = $instance->parse('5..8x10');
        $this->assertEquals([5, 6, 7, 8, 10], $result->toNormalizedArray());
    }
}