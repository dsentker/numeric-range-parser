<?php

declare(strict_types=1);

namespace Dsentker\Tests;

use DSentker\DefaultNumericRangeParser;
use DSentker\RangeException;
use DSentker\StrictNumericRangeParser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class StrictNumericRangeParserTest extends TestCase
{

    #[DataProvider('normalizedArrayProvider')]
    public function testGetNormalizedArray(string $input, array $expected): void
    {
        $instance = new StrictNumericRangeParser();
        $result = $instance->parse($input);
        $this->assertEquals($expected, $result->toNormalizedArray());
    }

    public static function normalizedArrayProvider(): array
    {
        return [
            ['1-1', [1]],
            ['1-2', [1, 2]],
            ['2', [2]],
            ['0', [0]],
            ['-2', [2]],
            ['2-', [2]],
            ['--42--', [42]],
            ['2;2-2;2', [2]],
            ['2-4;11;6-7;11;9-10', [2, 3, 4, 6, 7, 9, 10, 11]],
        ];
    }

    public function testIterator(): void
    {
        $instance = new StrictNumericRangeParser();
        $result = $instance->parse('2-4;4;101');

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

    #[DataProvider('strictCheckThrowsProvider')]
    public function testStrictCheckThrows(string $input, int $expectedCode)
    {
        $this->expectException(RangeException::class);
        $this->expectExceptionCode($expectedCode);
        $instance = new StrictNumericRangeParser();
        $instance->parse($input);
    }

    public static function strictCheckThrowsProvider(): array
    {
        return [
            // Invalid range
            ['152-50', 1],
            ['1-0', 1],

            // Too many separators
            ['0-1-2', 2],
            ['0-1-2-20', 2],

            // Empty block
            ['10;;20', 4],
            ['', 4],

            // Invalid chars
            ['1,2', 8],
            ['5..10', 8],
        ];
    }
}